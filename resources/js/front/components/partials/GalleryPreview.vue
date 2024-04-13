<template>
    <div class="splide" ref="splide">
        <div class="splide__track">
            <ul class="splide__list">
                <li class="block splide__slide" v-for="mediaItem in media" :key="mediaItem.id">
                    <img class="" :src="mediaItem.url.full_hd">
                </li>
            </ul>
        </div>
    </div>
</template>
<script setup lang="ts">

import Splide from '@splidejs/splide';
import { inject, nextTick, onMounted, onUnmounted, ref } from 'vue';
import type { Media } from '@front/interfaces/Media';

const dialogRef = inject<any>('dialogRef');

const splide = ref<HTMLElement | null>(null);
const splideInstance = ref<Splide | null>(null);
const media = ref<Media[]>([]);
const initialActiveMediaId = ref<string | null>(null);

onMounted(() => {
    media.value = dialogRef.value.data.media;
    initialActiveMediaId.value = dialogRef.value.data.initialActiveMediaId;

    nextTick(() => setTimeout(() => initSplide(), 500));
});

onUnmounted(() => clearData());

const initSplide = (): void => {
    if (splideInstance.value) {
        splideInstance.value.destroy();
    }

    if (!splide.value) return;

    const start = media.value.findIndex((mediaItem) => mediaItem.id === initialActiveMediaId.value) ?? 0;

    splideInstance.value = new Splide(splide.value, {
        type: 'loop',
        perPage: 1,
        pagination: false,
        start,
    }).mount();
};

const clearData = (): void => {
    media.value = [];
    initialActiveMediaId.value = null;
};
</script>