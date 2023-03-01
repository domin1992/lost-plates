import axios from 'axios';
import Vue from 'vue';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const app = createApp({
    methods: {
        // initMap(){
        //     this.$emit('init-map', {});
        // },
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
    }
});

// app.component('ExampleComponent', defineAsyncComponent(() => import("./front/components/ExampleComponent.vue")));

app.config.globalProperties.emitter = mitt();

app.mount('#lost-plates');