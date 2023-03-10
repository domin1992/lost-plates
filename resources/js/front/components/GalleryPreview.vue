<template>
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto z-50 bg-gray-800/70" tabindex="-1" aria-hidden="true" ref="modal">
        <div class="modal-dialog relative w-[98%] max-w-2xl mx-auto mt-20 pointer-events-none">
            <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                <div class="modal-body relative pt-12 p-4">
                    <button type="button" class="box-content text-sm leading-none w-4 ml-auto border-none rounded-none opacity-50 absolute top-2 right-2 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-yellow-900 hover:opacity-75 hover:no-underline" data-bs-dismiss="modal" aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"/></svg></button>
                    
                    <div class="splide" ref="splide">
                        <div class="splide__track">
                            <ul class="splide__list">
                                <li class="block splide__slide" v-for="mediaItem in media" :key="mediaItem.id">
                                    <img class="" :src="mediaItem.url.full_hd">
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { Modal } from 'bootstrap.native';
import Splide from '@splidejs/splide';
export default {
    data() {
        return {
            modal: null,
            splide: null,
            media: [],
            initialActiveMediaId: null,
        };
    },
    mounted() {
        this.emitter.on('gallery-preview', (params) => this.initGallery(params));

        this.$refs.modal.addEventListener('shown.bs.modal', () => {
            this.initSplide();
        });

        this.$refs.modal.addEventListener('hidden.bs.modal', () => {
            if (this.splide) {
                this.splide.destroy();
            }
        });
    },
    methods: {
        initGallery(params) {
            this.media = params.gallery;
            this.initialActiveMediaId = params.activeMediaId;

            if (!this.modal) {
                this.modal = new Modal(this.$refs.modal);
            }

            this.modal.show();
        },
        initSplide() {
            if (this.splide) {
                this.splide.destroy();
            }

            const start = this.media.findIndex((mediaItem) => mediaItem.id === this.initialActiveMediaId) ?? 0;

            this.splide = new Splide(this.$refs.splide, {
                type: 'loop',
                perPage: 1,
                pagination: false,
                start,
            }).mount();
        },
    },
}
</script>