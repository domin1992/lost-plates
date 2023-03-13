import axios from 'axios';
import { createApp, defineAsyncComponent } from 'vue/dist/vue.esm-bundler';
import mitt from 'mitt';
import Toastify from 'toastify-js';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

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

app
    .component('BigMap', defineAsyncComponent(() => import("./components/Map/BigMap.vue")))
    .component('GalleryPreview', defineAsyncComponent(() => import("./components/GalleryPreview.vue")))
    .component('MarkersList', defineAsyncComponent(() => import("./components/Marker/MarkersList.vue")));

app.mount('#lost-plates');

document.addEventListener("DOMContentLoaded", () => {
    document.querySelector(".js-sidebar-opener").addEventListener("click", () => {
        document.querySelector(".js-sidebar").classList.toggle("translate-x-full");
    });
    document.querySelector(".js-sidebar-closer").addEventListener("click", () => {
        document.querySelector(".js-sidebar").classList.toggle("translate-x-full");
    });
});