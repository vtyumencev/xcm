import Storage from "./types/Storage";
import ProviderForBlocking from "./types/ProviderForBlocking";
import {createNanoEvents} from "nanoevents";
import Events from "./types/Events";

const emitter = createNanoEvents<Events>()

const setCookie = (cname: string, cvalue: string, exdays: number) => {
    const d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    let expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

const getCookie = (cname: string) => {
    let name = cname + "=";
    let ca = document.cookie.split(';');
    for(let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) === 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

const storage = () => {

    const settings = window.XCMSettingsPublic;

    return {
        on<E extends keyof Events>(event: E, callback: Events[E]) {
            return emitter.on(event, callback)
        },

        emit<K extends keyof Events>(event: K, ...args: Parameters<Events[K]>) {
            return emitter.emit(event, ...args);
        },

        getUserConfig() {
            let userConfig;
            try {
                const settingsRaw = getCookie('xcm');
                userConfig = JSON.parse(decodeURIComponent(settingsRaw));
            } catch (e) {

            }

            return userConfig;
        },

        prolongConfig() {
            setCookie('xcm', encodeURIComponent(JSON.stringify(this.getUserConfig())), 365);
        },

        getConsentConsentsTypes() {
            const userConfig = this.getUserConfig();
            const consentList: Record<string, string> = {};

            if (!userConfig?.consent) {
                return consentList;
            }

            for (const category of settings.categories) {

                if (! userConfig.consent[category.id]) {
                    continue;
                }

                const consentArray = category.consent_types?.split(',') ?? [];

                for (const consentValue of consentArray) {
                    consentList[consentValue] = 'granted';
                }
            }

            return consentList;
        },

        isProviderBlocked(providerName): ProviderForBlocking|boolean {
            const userConfig = this.getUserConfig();

            for (const category of settings.categories) {

                if (userConfig?.consent[category.id]) {
                    continue;
                }

                for (const vendor of category.vendors) {
                    if (category.necessary === '0' && (vendor.provider && providerName.includes(vendor.provider))) {
                        return {
                            provider: vendor.provider,
                            category_id: category.id,
                            category_name: category.name,
                        };
                    }
                }
            }
            return false;
        }
    } as Storage
}

export default storage();