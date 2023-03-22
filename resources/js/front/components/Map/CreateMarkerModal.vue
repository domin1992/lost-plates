<template>
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto z-50 bg-gray-800/70" tabindex="-1" aria-hidden="true" ref="modal">
        <div class="modal-dialog relative w-[98%] max-w-2xl mx-auto mt-20 pointer-events-none">
            <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                <div class="modal-body relative p-4 flex flex-col">
                    <button type="button" class="box-content text-sm leading-none w-4 ml-auto border-none rounded-none opacity-50 absolute top-2 right-2 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-yellow-900 hover:opacity-75 hover:no-underline" data-bs-dismiss="modal" :aria-label="$t('common.close')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"/></svg></button>

                    <div class="self-center">
                        <FormInputRadioButtonsGroup
                            name="type"
                            v-model="type"
                            :required="true"
                            :errorMessage="errors.type !== undefined ? errors.type : null"
                            :options="[
                                {
                                    label: $t('common.lost'),
                                    value: 'lost',
                                },
                                {
                                    label: $t('common.found'),
                                    value: 'found',
                                },
                            ]"
                        />
                    </div>

                    <div class="mt-4">
                        <FormInputText
                            :label="$t('common.plateNumber')"
                            name="plate_number"
                            v-model="plateNumber"
                            :required="true"
                            :errorMessage="errors.plate_number !== undefined ? errors.plate_number : null"
                        />
                    </div>

                    <div class="mt-4" v-if="type == 'lost'">
                        <FormInputText
                            :label="$t('createMarkerModal.radiusOptional')"
                            name="radius"
                            v-model="radius"
                            suffix="km"
                            :required="false"
                            :errorMessage="errors.radius !== undefined ? errors.radius : null"
                        />
                    </div>

                    <span class="block mt-4 text-gray-600 text-sm" v-if="type == 'lost'">{{ $t('createMarkerModal.addYourPhoneNumberOrEmailLost') }}</span>

                    <span class="block mt-4 text-gray-600 text-sm" v-else>{{ $t('createMarkerModal.addYourPhoneNumberOrEmailFound') }}</span>

                    <div class="mt-4">
                        <FormInputText
                            :label="$t('common.phoneNumber')"
                            name="phone_number"
                            v-model="phoneNumber"
                            :required="false"
                            :errorMessage="errors.phone_number !== undefined ? errors.phone_number : null"
                            :description="$t('createMarkerModal.yourPhoneNumberWillBeInvisible')"
                        />
                    </div>
                    <div class="mt-4">
                        <FormInputEmail
                            :label="$t('common.email')"
                            name="email"
                            v-model="email"
                            :required="false"
                            :errorMessage="errors.email !== undefined ? errors.email : null"
                            :description="$t('createMarkerModal.yourEmailWillBeInvisible')"
                        />
                    </div>
                    <div class="mt-4" v-if="type == 'lost'">
                        <FormInputCheckbox
                            :label="$t('createMarkerModal.notifyWhenSomeoneAddPin')"
                            name="notify_when_found"
                            v-model="notifyWhenFound"
                            :required="false"
                            :errorMessage="errors.notify_when_found !== undefined ? errors.notify_when_found : null"
                        />
                    </div>
                    
                    <div class="mt-4">
                        <FormInputTextarea
                            :label="$t('createMarkerModal.additionalInformation')"
                            name="additional_info"
                            v-model="additionalInfo"
                            :required="false"
                            :errorMessage="errors.description !== undefined ? errors.description : null"
                        />
                    </div>

                    <div class="mt-4" v-if="type == 'found'">
                        <Dropzone
                            mediaType="marker"
                            :errorMessage="errors.media !== undefined ? errors.media : null"
                            :description="$t('createMarkerModal.maxFivePhotos')"
                            @updated="mediaUpdated"
                        />
                    </div>

                    <div class="mt-4 self-end">
                        <button class="inline-block h-[2.25rem] leading-[2.25rem] px-4 bg-purple-heart text-white transition-colors rounded hover:bg-cyan focus-visible:bg-cyan" @click.stop="store">{{ $t('common.submit') }}</button>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Modal } from 'bootstrap.native';
import { defineAsyncComponent } from 'vue';
export default {
    components: {
        FormInputRadioButtonsGroup: defineAsyncComponent(() => import('./../Partials/FormInput/FormInputRadioButtonsGroup.vue')),
        FormInputText: defineAsyncComponent(() => import('./../Partials/FormInput/FormInputText.vue')),
        FormInputEmail: defineAsyncComponent(() => import('./../Partials/FormInput/FormInputEmail.vue')),
        FormInputTextarea: defineAsyncComponent(() => import('./../Partials/FormInput/FormInputTextarea.vue')),
        FormInputCheckbox: defineAsyncComponent(() => import('./../Partials/FormInput/FormInputCheckbox.vue')),
        Dropzone: defineAsyncComponent(() => import('./../Partials/FormInput/Dropzone.vue')),
    },
    data() {
        return {
            modal: null,
            lat: null,
            lng: null,
            type: 'lost',
            plateNumber: '',
            radius: '',
            phoneNumber: '',
            email: '',
            additionalInfo: '',
            notifyWhenFound: false,
            media: [],

            errors: {},
        };
    },
    mounted() {
        this.emitter.on('create-marker-modal', (params) => this.initModal(params));
    },
    methods: {
        initModal(params = {}) {
            this.lat = params.lat;
            this.lng = params.lng;

            if (!this.modal) {
                this.modal = new Modal(this.$refs.modal);
            }

            this.modal.show();
        },
        store() {
            axios.post('/ajax/markers', {
                lat: this.lat,
                lng: this.lng,
                type: this.type,
                plate_number: this.plateNumber,
                radius: this.radius,
                phone_number: this.phoneNumber,
                email: this.email,
                additional_info: this.additionalInfo,
                notify_when_found: this.notifyWhenFound,
                media: this.media,
            })
                .then((response) => {
                    this.plate_number = this.radius = this.additional_info = '';

                    this.$root.toast(response.data.message);

                    this.modal.hide();

                    this.$emit('markerStored');
                })
                .catch((error) => {
                    this.errors = error.response.data.errors;
                });
        },
        mediaUpdated(mediaIds) {
            this.media = mediaIds;
        },
    },
}
</script>
