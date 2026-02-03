import Modal from '@stiumentsev/modal';
import Storage from "./types/Storage";
import UserConfig from "./types/UserConfig";

const CLASS_CAT = 'js-xcm-manager-category-review';
let modalEl: HTMLElement;
let contentEl: HTMLElement;
let appStorage: Storage;

/**
 * Check if user has disabled one of the options
 */
const hasDisabledOptions = (oldConfig: UserConfig): boolean => {
    const currentUserConfig =  appStorage.getUserConfig();

    for (const consent of Object.entries(currentUserConfig.consent)) {
        if (oldConfig.consent[consent[0]] === true && consent[1] === false) {
            return true;
        }
    }

    return false;
}

const onClickCategory = (e) => {
    if (e.target.closest(`.${CLASS_CAT}`).classList.contains('active')) {
        e.target.closest(`.${CLASS_CAT}`).classList.remove('active');
    } else {
        e.target.closest(`.${CLASS_CAT}`).classList.add('active');
    }
}

const onClickVendor = (e) => {

    const vendorId = e.target.getAttribute('data-vendor-id');

    const modalEl = modalTemplate('xcm-modal-vendor');
    document.body.appendChild(modalEl);


    Modal.init(modalEl, {
        onOpening: async() => {

            await fetch(window.XCMSettingsPublic.ajaxUrl + '?action=xcm_vendor&vendorId=' + vendorId, {
            }).then(response => {
                return response.text()
            }).then(result => {
                modalEl.querySelector('.sx-modal-dialog__content').innerHTML = result;
            });
        },
        onClose: () => {
            setTimeout(() => {
                modalEl.remove();
            }, 500)
        }
    });

}

const submit = async (e) => {
    e.preventDefault();

    if (e.submitter.getAttribute('data-action') === 'accept-all') {
        e.target.querySelectorAll('.xcm-manager-category-switch input').forEach((el) => {
            el.checked = true;
        })
    }

    setTimeout(() => {
        Modal.destroy(modalEl);
    }, 100);

    const oldUserConfig =  appStorage.getUserConfig();

    await fetch(window.XCMSettingsPublic.ajaxUrl + '?action=xcm_update', {
        method: 'POST',
        body: new FormData(e.target)
    }).then(response =>  response.json());

    if (
        window.XCMSettingsPublic.reloadOnUpdate === true ||
        oldUserConfig && hasDisabledOptions(oldUserConfig) // Reload to clean tag injections and prevent several tag injections
    ) {
        window.location.reload();
    }

    appStorage.emit('consentUpdated');
}

const initContent = (contentEl: HTMLElement, categoryId: number = null) => {
    contentEl.querySelectorAll('.js-xcm-manager-category-toggle').forEach((itemEl) => {
        itemEl.addEventListener('click', onClickCategory);
    });

    contentEl.querySelectorAll('.js-xcm-vendor-review').forEach((itemEl) => {
        itemEl.addEventListener('click', onClickVendor);
    });

    if (categoryId) {
        const categoryEl = contentEl.querySelector(`.xcm-manager-category[data-id="${categoryId}"]`);
        if (categoryEl) {
            setTimeout(() => {
                categoryEl.classList.add('active');
                categoryEl.scrollIntoView({ behavior: "smooth" });
            }, 500);
        }
    }

    contentEl.querySelector('.js-xcm-manager-form').addEventListener('submit', submit);
}

const destroyContent = (contentEl) => {
    contentEl.querySelectorAll('.js-xcm-manager-category-toggle').forEach((itemEl) => {
        itemEl.removeEventListener('click', onClickCategory);
    });

    contentEl.querySelectorAll('.js-xcm-vendor-review').forEach((itemEl) => {
        itemEl.removeEventListener('click', onClickVendor);
    });

    contentEl.querySelector('.js-xcm-manager-form').removeEventListener('submit', submit);
}

const modalTemplate = (formId) => {
    const modalEl = document.createElement('div');
    modalEl.classList.add('sx-modal');
    modalEl.classList.add('xcm-modal');
    modalEl.setAttribute('id', formId);
    modalEl.innerHTML = `
            <div class="sx-modal__inner">
                <div class="sx-modal-dialog__bg"></div>
                <div class="sx-modal-dialog modal-dialog--lg">
                    <div class="sx-modal-dialog__container">
                        <div class="sx-modal-dialog__body js-sx-modal-dialog-body">
                            <div class="sx-modal-dialog__content"></div>
                        </div>
                    </div>
                </div>
                <div class="sx-modal-loading">
                    <div class="sx-modal-loading__body">
                        Wird geladen...
                    </div>
                </div>
            </div>`;
    document.body.appendChild(modalEl);
    return modalEl;
}

const Manager = () => {

    const fetchManagerContent = async () => {
        return await fetch(window.XCMSettingsPublic.ajaxUrl + '?action=xcm_overview', {
        }).then(response => {
            return response.text()
        }).then(result => {
            return result;
        });
    }

    return {

        show(closable = true, categoryId: number = null) {
            let fetchRequest = new Promise( (resolve) => resolve(true) );

            if (! modalEl) {
                modalEl = modalTemplate('xenio-consent-modal');
                contentEl = modalEl.querySelector('.sx-modal-dialog__content');
                document.body.appendChild(modalEl);

                fetchRequest = new Promise( async (resolve) => {
                    contentEl.innerHTML = await fetchManagerContent();
                    resolve(true);
                });
            }

            Modal.init(modalEl, {
                onOpening: async () => {
                    await fetchRequest;
                    initContent(contentEl, categoryId);
                },
                onClose: () => {
                    destroyContent(contentEl);
                },
                closable: closable
            });
        },

        start(storage: Storage) {
            appStorage = storage;

            window.addEventListener("popstate", () => {
                if (window.location.hash.startsWith("#consent-overview")) {
                    const filter = window.location.hash.match(/#consent-overview\.cat\.(\d*)/);
                    const categoryId = filter && (parseInt(filter[1]) ?? null);
                    this.show(true, categoryId);
                }
            });

            window.addEventListener('DOMContentLoaded', () => {
                if (!appStorage.getUserConfig()) {
                    this.show(false);
                } else {
                    appStorage.prolongConfig()
                }
            }, {
                once: true
            });
        },

        async acceptConsentType(consentType: string) {
            await fetch(window.XCMSettingsPublic.ajaxUrl + '?action=xcm_accept_consent_type', {
                method: 'POST',
                body: new URLSearchParams({ consent_type: consentType })
            }).then(response => response.json());

            if (
                window.XCMSettingsPublic.reloadOnUpdate === true
            ) {
                window.location.reload();
            }
        }
    }
};

export default Manager();