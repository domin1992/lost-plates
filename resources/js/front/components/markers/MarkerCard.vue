<template>
    <div
        :class="[
            'p-4 bg-white flex flex-col absolute bottom-0 left-0 h-14 w-full md:top-0 md:bottom-auto md:left-auto md:right-0 md:h-full md:w-1/4 transition-transform transform translate-y-full md:translate-x-full md:translate-y-0 shadow-lg z-50 overflow-hidden',
            {'transform-none md:transform-none': selectedMarker},
            !detailsExpanded ? 'h-24 cursor-pointer md:cursor-auto' : 'h-full',
        ]"
        @click="showDetails"
    >
        <button class="self-end w-4 h-4 js-hide-details" v-show="detailsExpanded" @click="hideDetails">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"/></svg>
        </button>

        <div>
            <div :class="['flex justify-between', {'mt-8': detailsExpanded}]">
                <span :class="['block font-display text-lg']">{{ selectedMarker?.plateNumber }}</span>
                <span :class="['block whitespace-nowrap rounded-full px-[0.65em] pt-[0.35em] pb-[0.25em] text-center align-baseline text-[0.75em] font-bold text-white', {'bg-primary': selectedMarker?.type == 'found'}, {'bg-danger': selectedMarker?.type == 'lost'}]">{{ $t(`common.${selectedMarker?.type}`) }}</span>
            </div>
            <span class="block" v-if="selectedMarker?.hasPhoneNumber">
                <span class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="h-4 block mr-1"><path d="M164.9 24.6c-7.7-18.6-28-28.5-47.4-23.2l-88 24C12.1 30.2 0 46 0 64C0 311.4 200.6 512 448 512c18 0 33.8-12.1 38.6-29.5l24-88c5.3-19.4-4.6-39.7-23.2-47.4l-96-40c-16.3-6.8-35.2-2.1-46.3 11.6L304.7 368C234.3 334.7 177.3 277.7 144 207.3L193.3 167c13.7-11.2 18.4-30 11.6-46.3l-40-96z"/></svg>
                    {{ selectedMarker?.phoneNumber }}
                    <Button
                        type="button"
                        class="text-sm ml-1"
                        v-if="!selectedMarker?.isPhoneNumberRevealed"
                        @click="revealPhoneNumber"
                    >
                        {{ $t('markerCard.revealPhoneNumber') }}
                    </Button>
                </span>
            </span>
            <p class="mt-4 text-sm" v-show="selectedMarker?.additionalInfo && detailsExpanded">{{ selectedMarker?.additionalInfo }}</p>
            <span class="mt-2 text-sm" v-show="!detailsExpanded && selectedMarker?.markerMedia.length">{{ $t('mapDetails.photos') }}: {{ selectedMarker?.markerMedia.length }}</span>
            <div class="flex mt-4 gap-2" v-show="detailsExpanded && selectedMarker?.markerMedia.length">
                <button
                    class="block w-16 h-16"
                    v-for="markerMedia in selectedMarker?.markerMedia"
                    :key="markerMedia.id"
                    @click="previewMarkerMedia(markerMedia.media.id)"
                >
                    <img class="object-cover w-full h-full" :src="markerMedia.media.url.miniature">
                </button>
            </div>
        </div>

        <MarkerComments
            v-if="detailsExpanded"
            :marker="selectedMarker"
        />

        <div class="mt-4 flex justify-center" v-if="detailsExpanded">
            <Button
                type="button"
                @click="submitContact"
            >
                {{ $t('markerCard.submitContact') }}
            </Button>
        </div>

        <DynamicDialog />
    </div>
</template>

<script setup lang="ts">

import { useMarkersStore } from '@front/stores/markers';
import { storeToRefs } from 'pinia';
import { defineAsyncComponent, onMounted, ref, toRaw } from 'vue';
import { useDialog } from 'primevue/usedialog';
import DynamicDialog from 'primevue/dynamicdialog';

const MarkerComments = defineAsyncComponent(() => import('./MarkerComments.vue'));
const MarkerSubmitContactForm = defineAsyncComponent(() => import('./MarkerSubmitContactForm.vue'));
const GalleryPreview = defineAsyncComponent(() => import('./../partials/GalleryPreview.vue'));
const Button = defineAsyncComponent(() => import('./../partials/Button.vue'));

const dialog = useDialog();
const dialogRef = ref<any>(null);
const markersStore = useMarkersStore();
const { selectedMarker } = storeToRefs(markersStore);

const detailsExpanded = ref<boolean>(false);

onMounted(() => {
    if (window.matchMedia("(min-width: 768px)").matches) {
        detailsExpanded.value = true;
    }
});

const showDetails = (e: any): void => {
    const { target } = e;

    if (
        !target.matches('.js-hide-details')
        && !target.closest('.js-hide-details')
        && !detailsExpanded.value
    ) {
        detailsExpanded.value = true;
    }
};

const hideDetails = (): void => {
    if (!window.matchMedia("(min-width: 768px)").matches) {
        detailsExpanded.value = false;
    }
    markersStore.selectMarker(null);
};

const previewMarkerMedia = (mediaId: string): void => {
    dialogRef.value = dialog.open(GalleryPreview, {
        data: {
            media: selectedMarker.value?.markerMedia.map((markerMedia) => toRaw(markerMedia.media)),
            initialActiveMediaId: mediaId,
        },
    });
};

const revealPhoneNumber = (): void => {
    if (!selectedMarker?.value) return;

    markersStore.phoneNumber(selectedMarker.value.id);
};

const submitContact = (): void => {
    if (!selectedMarker?.value) return;

    dialogRef.value = dialog.open(MarkerSubmitContactForm);
};

</script>
