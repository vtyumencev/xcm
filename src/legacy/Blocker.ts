import Storage from "./types/Storage";
import ProviderForBlocking from "./types/ProviderForBlocking";

let blockedNodes = [];
let appStorage: Storage;

const providers = [
    {
        slug: 'youtube',
        name: 'YouTube',
        hosts: ['www.youtube', 'youtube'],
        dashicons: 'youtube'
    },
    {
        slug: 'instagram',
        name: 'Instagram',
        hosts: ['instagram', 'www.instagram'],
        dashicons: 'instagram'
    },
    {
        slug: 'twitter',
        name: 'Twitter',
        hosts: ['twitter', 'www.twitter', 'platform.twitter.com', 'twitter.com'],
        dashicons: 'twitter'
    },
];

const randomString = (length: number) => {
    let result = '';
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    const charactersLength = characters.length;
    let counter = 0;
    while (counter < length) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
        counter += 1;
    }
    return result;
}

const getProviderBySrc = (src: string) => {
    try {
        const iframeUrl = new URL(src);
        const urlHost = iframeUrl.host;
        return providers.find(provider => {
            return provider.hosts.find(host => urlHost.startsWith(host));
        });
    } catch (exception) {
        return null;
    }
}

const unblockAllNodes = () => {
    document.querySelector('body').classList.remove('xcc--blocking');
    blockedNodes.forEach(({ node, position, placeholderId }) => {

        if (node.nodeName.toLowerCase() === "iframe") {
            const frame = document.getElementById(placeholderId);
            const iframe = document.createElement("iframe");

            node.getAttributeNames().forEach(attribute => {
                iframe.setAttribute(attribute, node.getAttribute(attribute))
            });

            frame.parentNode.insertBefore(iframe, frame);
        } else {
            document[position].appendChild(node);
        }
    });

    document.querySelectorAll('.embed-placeholder').forEach(el => {
        el.remove();
    });

    blockedNodes.splice(0, blockedNodes.length);
}

const iframePlaceholder = (node, provider, objectOptions) => {

    const options = {
        absolute: false,
        ...objectOptions
    }

    const placeholderId = 'xcc-placeholder-' + randomString(13);
    const placeholderBody = `
        <div class="embed-placeholder embed-placeholder--${provider.slug} ${options.absolute ? 'embed-placeholder--absolute' : ''}" id="${placeholderId}">
            <div class="embed-placeholder__wrapper">
                <div class="embed-placeholder__inner">
                    <div class="embed-placeholder-content">
                        <div class="embed-placeholder-content__title">
                            Empfohlener externer Inhalt
                        </div>
                        <div class="embed-placeholder-content__main">
                            <div class="embed-placeholder-content__main-text">
                                <p>An dieser Stelle finden Sie einen externen Inhalt von ${provider.name}, der den Artikel ergänzt und von der Redaktion empfohlen wird. Sie können ihn sich mit einem Klick anzeigen lassen und wieder ausblenden.</p>
                            </div>
                            <div class="embed-placeholder-content__main-picture">
                                <div class="embed-placeholder-content__main-picture-inner">
                                    <div class="embed-placeholder-content__main-icon">
                                        <span class="dashicons dashicons-${provider.dashicons}"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="embed-placeholder-content__switch">
                            <button class="embed-placeholder-switch">
                                <span class="embed-placeholder-switch__icon"></span>
                                <span class="embed-placeholder-switch__text">
                                    Externer Inhalt
                                </span>
                            </button>
                        </div>
                        <div class="embed-placeholder-content__warning">
                            <p>Ich bin damit einverstanden, dass mir externe Inhalte angezeigt werden. Damit können personenbezogene Daten an Drittplattformen übermittelt werden. Mehr dazu in unserer Datenschutzerklärung.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;

    node.insertAdjacentHTML(
        "beforebegin",
        placeholderBody
    );

    const placeholderEl = document.getElementById(placeholderId);

    const button = placeholderEl.querySelector('.embed-placeholder-switch');

    button.addEventListener('click', (e) => {
        //unblockAllNodes();
    });

    return placeholderId;
}

const mutationObserver = (mutations: MutationRecord[]) => {
    for (const { addedNodes } of mutations) {
        for (const node of addedNodes) {
            if (node instanceof HTMLElement && node.classList.contains('wp-block-embed')) {
                console.log(node)
            }
            // if (
            //     (
            //         node instanceof HTMLIFrameElement ||
            //         node instanceof HTMLScriptElement
            //     ) &&
            //     node.src &&
            //     node.nodeName &&
            //     ['iframe', 'script'].includes(node.nodeName.toLowerCase())
            // ) {
            //     const blockingDomain = appStorage.checkDomainForBlocking(node.src);
            //
            //     if (! blockingDomain) {
            //         return;
            //     }
            //
            //     const provider = getProviderBySrc(node.src);
            //     if (! provider) {
            //         return;
            //     }
            //
            //     if (provider.slug === 'twitter') {
            //         const blockQuote = [...node.parentElement.children].find(c => c.classList.contains('twitter-tweet'));
            //         blockQuote?.classList.add('xcc-found-blockquote');
            //     }
            //
            //     let placeholderId = iframePlaceholder(node, provider, {
            //             absolute: window.getComputedStyle(node).position === 'absolute'
            //         });
            //
            //     const nodePosition =
            //         document.head.compareDocumentPosition(node) &
            //         Node.DOCUMENT_POSITION_CONTAINED_BY
            //             ? "head"
            //             : "body";
            //
            //     blockedNodes.push({
            //         position: nodePosition,
            //         node: node.cloneNode(),
            //         placeholderId: placeholderId
            //     });
            //
            //     node.remove();
            // }
        }
    }
}

const Blocker = () => {
    return {
        start(storage: Storage) {
            appStorage = storage;
            const nodesObserver = new MutationObserver(mutationObserver);
            nodesObserver.observe(document.documentElement, {
                childList: true,
                subtree: true,
            });
        }
    }
}

export default Blocker();