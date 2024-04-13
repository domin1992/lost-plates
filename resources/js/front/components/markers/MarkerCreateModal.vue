<template>
    <Dialog v-model:visible="visible" modal :header="$t('createMarkerModal.title')">
        <form @submit.prevent="onSubmit">
            <div class="self-center">
                <FormInputRadioButtonsGroup
                    name="type"
                    v-model="type"
                    :required="true"
                    :errorMessage="$messageBagFirstError(errors, 'type')"
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
                    name="plateNumber"
                    v-model="plateNumber"
                    :required="true"
                    :errorMessage="$messageBagFirstError(errors, 'plateNumber')"
                />
            </div>

            <div class="mt-4" v-if="type == 'lost'">
                <FormInputText
                    :label="$t('createMarkerModal.radiusOptional')"
                    name="radius"
                    v-model="radius"
                    suffix="km"
                    :required="false"
                    :errorMessage="$messageBagFirstError(errors, 'radius')"
                />
            </div>

            <span class="block mt-4 text-gray-600 text-sm" v-if="type == 'lost'">{{ $t('createMarkerModal.addYourPhoneNumberOrEmailLost') }}</span>

            <span class="block mt-4 text-gray-600 text-sm" v-else>{{ $t('createMarkerModal.addYourPhoneNumberOrEmailFound') }}</span>

            <div class="mt-4">
                <FormInputText
                    :label="$t('common.phoneNumber')"
                    name="phoneNumber"
                    v-model="phoneNumber"
                    :required="false"
                    :errorMessage="$messageBagFirstError(errors, 'phoneNumber')"
                    :description="$t('createMarkerModal.yourPhoneNumberWillBeInvisible')"
                />
            </div>
            <div class="mt-4">
                <FormInputEmail
                    :label="$t('common.email')"
                    name="email"
                    v-model="email"
                    :required="false"
                    :errorMessage="$messageBagFirstError(errors, 'email')"
                    :description="$t('createMarkerModal.yourEmailWillBeInvisible')"
                />
            </div>
            <div class="mt-4" v-if="type == 'lost'">
                <FormInputCheckbox
                    :label="$t('createMarkerModal.notifyWhenSomeoneAddPin')"
                    name="notifyWhenFound"
                    v-model="notifyWhenFound"
                    :required="false"
                    :errorMessage="$messageBagFirstError(errors, 'notifyWhenFound')"
                />
            </div>
            
            <div class="mt-4">
                <FormInputTextarea
                    :label="$t('createMarkerModal.additionalInformation')"
                    name="additionalInfo"
                    v-model="additionalInfo"
                    :required="false"
                    :errorMessage="$messageBagFirstError(errors, 'additionalInfo')"
                />
            </div>

            <div class="mt-4" v-if="type == 'found'">
                <Dropzone
                    :label="$t('createMarkerModal.images')"
                    name="media"
                    :required="false"
                    :filesCount="5"
                    fileType="image"
                    imageType="marker"
                    :errorMessage="$messageBagFirstError(errors, 'media')"
                    @change="mediaUpdated"
                />
            </div>

            <div class="mt-4 self-end">
                <button type="submit" class="inline-block h-[2.25rem] leading-[2.25rem] px-4 bg-purple-heart text-white transition-colors rounded hover:bg-cyan focus-visible:bg-cyan">{{ $t('common.submit') }}</button>
            </div>
        </form>
    </Dialog>
</template>

<script setup lang="ts">

import Dialog from 'primevue/dialog';
import { defineAsyncComponent, inject, nextTick, onMounted, onUnmounted, ref } from 'vue';
import { emitter, type Events } from '@front/utils/eventBus';
import { useMarkersStore } from '@front/stores/markers';
import { MarkerType } from '@front/interfaces/MarkerType';
import type { ApiRequestFailedException } from '../../exceptions/ApiRequestFailedException';
import type { MessageBagError } from '@front/interfaces/MessageBagError';
import { NotificationService } from '@front/services/NotificationService';
import type { Marker } from '@front/interfaces/Marker';

const Button = defineAsyncComponent(() => import('./../partials/Button.vue'));
const FormInputRadioButtonsGroup = defineAsyncComponent(() => import('./../partials/form-input/FormInputRadioButtonsGroup.vue'));
const FormInputText = defineAsyncComponent(() => import('./../partials/form-input/FormInputText.vue'));
const FormInputEmail = defineAsyncComponent(() => import('./../partials/form-input/FormInputEmail.vue'));
const FormInputCheckbox = defineAsyncComponent(() => import('./../partials/form-input/FormInputCheckbox.vue'));
const FormInputTextarea = defineAsyncComponent(() => import('./../partials/form-input/FormInputTextarea.vue'));
const Dropzone = defineAsyncComponent(() => import('./../partials/form-input/Dropzone.vue'));

const markersStore = useMarkersStore();

const notificationService = inject<NotificationService>('notificationService');

const visible = ref<boolean>(false);
const errors = ref<MessageBagError>({});
const type = ref<MarkerType>(MarkerType.Lost);
const plateNumber = ref<string>('');
const lat = ref<number>(0);
const lng = ref<number>(0);
const radius = ref<string>('');
const phoneNumber = ref<string>('');
const email = ref<string>('');
const notifyWhenFound = ref<boolean>(false);
const additionalInfo = ref<string>('');
const uploadedMediaIds = ref<string[]>([]);

onMounted(() => {
    nextTick(() => {
        emitter.on('toggleCreateMarkerModal', toggleModal);
    });
});

onUnmounted(() => emitter.off("toggleCreateMarkerModal", toggleModal));

const toggleModal = (event: Events['toggleCreateMarkerModal']): void => {
    lat.value = event.lat;
    lng.value = event.lng;
    visible.value = !visible.value;
};

const mediaUpdated = (_uploadedMediaIds: string[]): void => {
    uploadedMediaIds.value = _uploadedMediaIds;
};

const onSubmit = (): void => {
    markersStore.store(
        type.value,
        plateNumber.value,
        lat.value,
        lng.value,
        radius.value,
        phoneNumber.value,
        email.value,
        additionalInfo.value,
        notifyWhenFound.value
    )
    .then((response: {message: string; marker: Marker} | null) => {
        if (!response) return;

        notificationService?.success(response.message);
        closeModal();
        markersStore.selectMarker(response.marker.id);
    })
    .catch((error: ApiRequestFailedException) => errors.value = error.getMessageBagError());
};

const closeModal = (): void => {
    visible.value = false;
    errors.value = {};
    type.value = MarkerType.Lost;
    plateNumber.value = '';
    lat.value = 0;
    lng.value = 0;
    radius.value = '';
    phoneNumber.value = '';
    email.value = '';
    notifyWhenFound.value = false;
    additionalInfo.value = '';
};

</script>
