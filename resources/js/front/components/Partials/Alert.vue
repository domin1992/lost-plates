<template>
    <div :class="['alert rounded-lg py-5 px-6 mx-auto text-base inline-flex items-center alert-dismissible fade show', colorClasses(), customClasses]" role="alert" ref="alert">
        {{ message }}
        <button type="button" class="btn-close box-content text-sm leading-none w-4 ml-auto border-none rounded-none opacity-50 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-yellow-900 hover:opacity-75 hover:no-underline" data-bs-dismiss="alert" :aria-label="$t('common.close')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"/></svg></button>
    </div>
</template>

<script>
import { Alert } from 'bootstrap.native';
export default {
    props: [
        'message',
        'type',
        'customClasses',
    ],
    data() {
        return {
            alert: null,
        };
    },
    mounted() {
        this.alert = new Alert(this.$refs.alert);

        this.$refs.alert.addEventListener('closed.bs.alert', () => {
            this.$emit('closed');
        });
    },
    methods: {
        colorClasses() {
            if (this.type === 'info') {
                return 'bg-blue-100 text-blue-700';
            } else if (this.type === 'success') {
                return 'bg-green-100 text-green-700';
            }
        },
    },
}
</script>
