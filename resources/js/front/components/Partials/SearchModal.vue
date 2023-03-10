<template>
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto z-50 bg-gray-800/70" tabindex="-1" aria-hidden="true" ref="modal">
        <div class="modal-dialog relative w-[98%] max-w-2xl mx-auto mt-20 pointer-events-none">
            <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                <div class="modal-body relative p-4 flex">
                    <div class="w-full">
                        <input
                            type="text"
                            :class="['form-control block w-full px-3 py-1.5 text-base text-mine-shaft bg-white bg-clip-padding border border-solid rounded transition ease-in-out m-0 flex-1 focus:text-mine-shaft focus:bg-white focus:border-purple-heard focus:outline-none focus:shadow-purple-heart', errorMessage ? 'border-danger' : 'border-gray-300']"
                            id="plate_number"
                            v-model="plateNumber"
                            placeholder="Wpisz numer rejestracyjny"
                            @keyup.enter="search"
                            ref="plateNumber"
                        >
                        <div class="mt-1 text-danger text-sm text-bold" v-if="errorMessage">{{ errorMessage }}</div>
                    </div>

                    <button class="inline-block h-[2.25rem] leading-[2.25rem] px-4 mx-4 bg-purple-heart text-white transition-colors rounded hover:bg-cyan focus-visible:bg-cyan" @click="search">Szukaj</button>
                    
                    <button type="button" class="box-content text-sm leading-none w-4 ml-auto border-none rounded-none opacity-50 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-yellow-900 hover:opacity-75 hover:no-underline" data-bs-dismiss="modal" aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"/></svg></button>
                    
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Modal } from 'bootstrap.native';
export default {
    data() {
        return {
            plateNumber: '',
            errorMessage: '',
            modal: null,
        };
    },
    mounted() {
        this.emitter.on('search-modal', (params) => this.initModal(params));

        this.$refs.modal.addEventListener('shown.bs.modal', () => {
            this.$refs.plateNumber.focus();
        });
        this.$refs.modal.addEventListener('hidden.bs.modal', () => {
            this.plateNumber = '';
            this.errorMessage = '';
        });
    },
    methods: {
        initModal(params) {
            if (!this.modal) {
                this.modal = new Modal(this.$refs.modal);
            }

            this.modal.show();
        },
        search() {
            this.errorMessage = '';

            axios.get('/ajax/markers', {
                params: {
                    plate_number: this.plateNumber,
                }
            })
                .then((response) => {
                    if (!response.data.markers.length) {
                        this.errorMessage = 'Nie znaleziono pojazdu o podanym numerze rejestracyjnym';
                    } else {
                        this.$emit('onFoundResults', response.data.markers);
                        this.modal.hide();
                    }
                });
        },
    },
}
</script>
