export {};

declare global {
    interface Window {
        XCMSettingsPublic: {
            ajaxUrl: string,
            categories: {
                id: number,
                name: string,
                contest_types: string,
                necessary: boolean|string,
                vendors: {
                    provider: string;
                    id: number,
                }[]
            }[]
        };
    }

    interface Window {
        dataLayer: Record<string, any>[];
    }
}