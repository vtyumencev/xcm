export {};

declare global {
    interface Window {
        XCMSettingsPublic: {
            contentVersion: string,
            ajaxUrl: string,
            categories: {
                id: number,
                name: string,
                consent_type: string,
                consent_types: string,
                necessary: boolean|string,
                vendors: {
                    provider: string;
                    id: number,
                }[]
            }[],
            reloadOnUpdate: boolean,
        };
    }

    interface Window {
        dataLayer: Record<string, any>[];
    }
}