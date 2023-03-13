<template>
    <div :class="['bg-white absolute bottom-0 left-0 right-0 mx-auto rounded transition-all p-4 flex flex-col z-10 box-border', activeMarker ? 'translate-y-0 block' : 'translate-y-full hidden', detailsExpanded ? 'h-[calc((100vh-3.5rem))] w-full' : 'h-48 w-5/6']" ref="details">
        <button class="self-end w-4 h-4 js-hide-details" v-show="detailsExpanded" @click="hideDetails">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"/></svg>
        </button>

        <div>
            <div :class="['flex justify-between', {'mt-8': detailsExpanded}]">
                <span :class="['block font-display text-lg']">{{ activeMarker?.plate_number }}</span>
                <span :class="['block whitespace-nowrap rounded-full px-[0.65em] pt-[0.35em] pb-[0.25em] text-center align-baseline text-[0.75em] font-bold text-white', {'bg-primary': activeMarker?.type == 'found'}, {'bg-danger': activeMarker?.type == 'lost'}]">{{ activeMarker?.type == 'found' ? 'Znaleziono' : 'Zgubiono' }}</span>
            </div>
            <p class="mt-2 text-sm" v-show="activeMarker?.additional_info && !detailsExpanded">{{ shortenAdditionalInfo(activeMarker?.additional_info) }}</p>
            <p class="mt-4 text-sm" v-show="activeMarker?.additional_info && detailsExpanded">{{ activeMarker?.additional_info }}</p>
            <span class="mt-2 text-sm" v-show="!detailsExpanded && activeMarker?.marker_media.length">Zdjęcia: {{ activeMarker?.marker_media.length }}</span>
            <div class="flex mt-4 gap-2" v-show="detailsExpanded && activeMarker?.marker_media.length">
                <button
                    class="block w-16 h-16"
                    v-for="markerMedia in activeMarker?.marker_media"
                    :key="markerMedia.id"
                    @click="previewMarkerMedia(markerMedia.media.id)"
                >
                    <img class="object-cover w-full h-full" :src="markerMedia.media.url.miniature">
                </button>
            </div>
        </div>

        <MarkerComments :marker="activeMarker" @refreshMarker="$emit('refreshMarker', {markerId: activeMarker.id})" />

        <div class="mt-4 text-center">
            <a :href="activeMarker?.link" class="inline-block h-[2.25rem] leading-[2.25rem] px-4 bg-purple-heart text-white transition-colors rounded hover:bg-cyan focus-visible:bg-cyan js-active-marker-link">Szczegóły</a>
        </div>
    </div>
</template>

<script>
import { toRaw, defineAsyncComponent } from 'vue';
export default {
    components: {
        MarkerComments: defineAsyncComponent(() => import('./../Marker/MarkerComments.vue')),
    },
    props: [
        'activeMarker',
        'detailsExpanded',
    ],
    data() {
        return {
            detailsExpanded: false,
        };
    },
    mounted() {
        this.$refs.details.addEventListener('click', (e) => {
            if (
                !e.target.matches('.js-hide-details')
                && !e.target.closest('.js-hide-details')
                && !e.target.matches('.js-active-marker-link')
                && !e.target.closest('.js-active-marker-link')
            ) {
                this.detailsExpanded = true;
            }
        });
    },
    methods: {
        hideDetails() {
            this.detailsExpanded = false;
            this.$emit('detailsHidden');
        },
        shortenAdditionalInfo(text) {
            return this.$root.cutWords(text, 10);
        },
        previewMarkerMedia(markerMediaId) {
            this.emitter.emit('gallery-preview', {
                gallery: this.activeMarker.marker_media.map((markerMedia) => toRaw(markerMedia.media)),
                activeMediaId: markerMediaId,
            });
        },
    },
}
</script>
