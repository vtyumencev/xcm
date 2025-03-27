import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import apiFetch from '@wordpress/api-fetch';

import './index.css'

import App from './App.vue'
import router from './router'

declare global {
    interface Window {
        xenioCookiesSettings: {
            nonce: string
        };
    }
}

const app = createApp(App)

app.use(createPinia());
app.use(router);

apiFetch.use(apiFetch.createNonceMiddleware( window.xenioCookiesSettings.nonce ));
apiFetch.use(apiFetch.createRootURLMiddleware( '/wp-json/xcm/v1/' ));

app.mount('#xcc-admin-app')

