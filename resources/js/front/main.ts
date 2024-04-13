// Stylesheets
import '~/node_modules/@splidejs/splide/dist/css/splide.min.css';
import '~/node_modules/toastify-js/src/toastify.css';
import '~/node_modules/primevue/resources/themes/lara-light-purple/theme.css';
import '~/node_modules/primevue/resources/primevue.min.css';
import '~/node_modules/primeicons/primeicons.css';
import './assets/scss/front.scss';

import { createApp } from 'vue';
import App from './App.vue';
import router from './router/router';
import { i18nVue } from 'laravel-vue-i18n';
import { createPinia } from 'pinia';
import axios from 'axios';
import PrimeVue from 'primevue/config';
import { MarkersService } from './services/MarkersService';
import { ConfigService } from './services/ConfigService';
import { UserLanguageService } from './services/UserLanguageService';
import { LocalizationService } from './services/LocalizationService';
import { emitter } from './utils/eventBus';
import { MessageBagErrorPlugin } from './utils/messageBagErrorPlugin';
import type { MessageBagError } from './interfaces/MessageBagError';
import { NotificationService } from './services/NotificationService';
import { MarkerCommentsService } from './services/MarkerCommentsService';
import DialogService from 'primevue/dialogservice'
import { MediaService } from './services/MediaService';
import { PlatesService } from './services/PlatesService';
import { TurnstileService } from './services/TurnstileService';

declare global {
    interface Window {
        turnstile: Turnstile.Turnstile;
    }
}

// Create axios instance
const axiosInstance = axios.create({
    baseURL: `${import.meta.env.VITE_APP_URL}/api/`,
    timeout: 1000,
    headers: {
        'Accept': 'application/json',
    },
});

// Create Vue app
const app = createApp(App);

app.use(MessageBagErrorPlugin);
declare module "@vue/runtime-core" {
    //Bind to `this` keyword
    interface ComponentCustomProperties {
      $messageBagFirstError: (messageBagError: MessageBagError, key: string) => string;
      $messageBagMessage: (messageBagError: MessageBagError) => string;
    }
  }

// Instatiante config service
const configService = new ConfigService(
    import.meta.env.VITE_GOOGLE_CLOUD_API_KEY,
    import.meta.env.VITE_TURNSTILE_SITE_KEY
);

// Define services in one plate
const services = {
    axios: axiosInstance,
    configService,
    emitter,
    localizationService: new LocalizationService(),
    markersService: new MarkersService(axiosInstance),
    markerCommentsService: new MarkerCommentsService(axiosInstance),
    mediaService: new MediaService(axiosInstance),
    notificationService: new NotificationService(),
    platesService: new PlatesService(axiosInstance),
    turnstileService: new TurnstileService(configService),
    userLanguageService: new UserLanguageService(configService),
};

// Provide services for components
Object.entries(services).forEach(([key, instance]) => {
    app.provide(key, instance);
});

// Use PrimeVue
app.use(PrimeVue);
app.use(DialogService);

// Use i18n
app.use(i18nVue, {
    lang: services.userLanguageService.getUserLanguage(),
    resolve: async (lang: string) => {
        const langs = import.meta.glob('../../../lang/*.json');
        return await langs[`../../../lang/${lang}.json`]();
    },
});

// Use Pinia and provide services
app.use(createPinia());

// Use router
app.use(router);

// Mount app
app.mount('#lostplates');
