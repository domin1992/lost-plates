import axios from 'axios';
import { createApp, defineAsyncComponent } from 'vue/dist/vue.esm-bundler';
import mitt from 'mitt';
import Toastify from 'toastify-js';
import { i18nVue } from 'laravel-vue-i18n';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['X-Requested-Locale'] = window.globals.locale;

const app = window.app = createApp({
    methods: {
        randomString(length = 32) {
            let text = "";
            const possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
            for (let i = 0; i < length; i++) {
                text += possible.charAt(Math.floor(Math.random() * possible.length));
            }
            return text;
        },
        debounce(func, timeout = 300) {
            let timer;
            return (...args) => {
                clearTimeout(timer);
                timer = setTimeout(() => { func.apply(this, args); }, timeout);
            };
        },
        cutWords(text, wordsCount = 20) {
            if (!text) {
                return '';
            }

            const splitted = text.split(" ");

            if (splitted.length <= wordsCount) {
                return text;    
            }

            return splitted.slice(0, wordsCount).join(" ") + "...";
        },
        toast(
            text,
            type = 'success',
            destination = null,
        ) {
            let style = {
                background: "#28A745",
            };

            if (type === 'danger') {
                style = {
                    background: "#DC3545",
                };
            }

            Toastify({
                text,
                duration: 3000,
                destination,
                newWindow: false,
                close: true,
                gravity: "top",
                position: "right",
                stopOnFocus: true,
                style,
            }).showToast();
        },
    }
});

app.config.globalProperties.emitter = mitt();

app.use(i18nVue, {
    lang: window.globals.locale,
    resolve: async lang => {
        const langs = import.meta.glob('../../../lang/*.json');
        return await langs[`../../../lang/${lang}.json`]();
    }
});

app
    .component('BigMap', defineAsyncComponent(() => import("./components/Map/BigMap.vue")))
    .component('GalleryPreview', defineAsyncComponent(() => import("./components/GalleryPreview.vue")))
    .component('MarkersList', defineAsyncComponent(() => import("./components/Marker/MarkersList.vue")))
    .component('MarkerComments', defineAsyncComponent(() => import("./components/Marker/MarkerComments.vue")))
    .component('MiniMap', defineAsyncComponent(() => import("./components/Marker/MiniMap.vue")))
    .component('RevealPhoneNumber', defineAsyncComponent(() => import("./components/Partials/RevealPhoneNumber.vue")))
    .component('EmailContactForm', defineAsyncComponent(() => import("./components/Marker/EmailContactForm.vue")));

app.mount('#lostplates');

document.addEventListener("DOMContentLoaded", () => {
    document.querySelector(".js-sidebar-opener").addEventListener("click", () => {
        document.querySelector(".js-sidebar").classList.toggle("translate-x-full");
    });
    document.querySelector(".js-sidebar-closer").addEventListener("click", () => {
        document.querySelector(".js-sidebar").classList.toggle("translate-x-full");
    });
});