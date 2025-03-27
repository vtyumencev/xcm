import Storage from "./types/Storage";
const gtm = () => {

    let appStorage: Storage;

    const dataLayer = window.dataLayer = window.dataLayer || [];

    function gtag(...args: unknown[]) {
        dataLayer.push(arguments);
    }

    const defaultConfig = {
        'ad_storage': 'denied',
        'ad_user_data': 'denied',
        'ad_personalization': 'denied',
        'analytics_storage': 'denied'
    }

    return {
        start(storage: Storage) {
            appStorage = storage;
            gtag('consent', 'default', Object.assign(defaultConfig, appStorage.getConsentConsentsTypes()));
            if (appStorage.getUserConfig()) {
                gtag('event', 'update_consent');
            }

            appStorage.on('consentUpdated', () => {
                this.update();
            })
        },
        update() {
            gtag('consent', 'update', Object.assign(defaultConfig, appStorage.getConsentConsentsTypes()));
            gtag('event', 'update_consent');
        }
    }
}

export default gtm();