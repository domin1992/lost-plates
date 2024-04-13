<template>
    <div ref="map"></div>
</template>

<script setup lang="ts">

import { inject, nextTick, onMounted, ref, watch, toRaw } from "vue";
import { Loader } from "@googlemaps/js-api-loader"
import type { Marker } from "@front/interfaces/Marker";
import type { ConfigService } from "@front/services/ConfigService";
import type { LocalizationService } from "@front/services/LocalizationService";
import { useMarkersStore } from "@front/stores/markers";
import { debounce } from '@front/helpers';
import { storeToRefs } from "pinia";
import { trans } from "laravel-vue-i18n";
import { emitter } from "@front/utils/eventBus";
import { MarkerType } from "@front/interfaces/MarkerType";

const map = ref<HTMLElement | null>(null);
const mapInstance = ref<google.maps.Map | null>(null);
const mapMarkers = ref<google.maps.marker.AdvancedMarkerElement[]>([] as google.maps.marker.AdvancedMarkerElement[]);
const mapInfoWindow = ref<google.maps.InfoWindow | null>(null);
const selectedMarkerId = ref<string | null>(null);
const preventFromRefresh = ref<boolean>(false);
const newMarker = ref<google.maps.marker.AdvancedMarkerElement | null>(null);

const configService = inject<ConfigService>('configService');
const localizationService = inject<LocalizationService>('localizationService');

const markersStore = useMarkersStore();
const { markers } = storeToRefs(markersStore);

onMounted(() => {
    nextTick(() => {
        initMap();

        document.addEventListener("click", (e) => {
            const { target } = e;

            if (
                target instanceof HTMLElement
                && (
                    target.classList.contains('js-toggle-create-marker-modal')
                    || target.closest('.js-toggle-create-marker-modal')
                )
            ) {
                if (
                    !newMarker.value?.position?.lat
                    || !newMarker.value?.position?.lng
                ) {
                    return;
                }

                emitter.emit('toggleCreateMarkerModal', {
                    lat: newMarker.value.position.lat as number,
                    lng: newMarker.value.position.lng as number,
                });
            }
        });
    });
});

const emit = defineEmits({
    
});

watch(markers, async (newMarkers) => {
    mapInfoWindow.value?.close();

    removeNewMarker();

    mapMarkers.value.forEach((marker: google.maps.marker.AdvancedMarkerElement) => marker.map = null);

    mapMarkers.value = toRaw(newMarkers).map((marker) => {
        const markerImg = document.createElement('img');
        markerImg.src = MarkerType.Lost == marker.type
            ? '/images/markers/danger.png'
            : '/images/markers/primary.png';

        const mapMarker = new google.maps.marker.AdvancedMarkerElement({
            position: {
                lat: parseFloat(marker.lat.toString()),
                lng: parseFloat(marker.lng.toString()),
            },
            map: toRaw(mapInstance.value),
            content: markerImg,
            gmpClickable: true,
            title: marker.plate.number,
        });

        mapMarker.addListener('click', () => {
            removeNewMarker();

            preventFromRefresh.value = true;

            mapInfoWindow.value?.close();
            mapInfoWindow.value?.setContent(
                `<span class="text-center leading-1">${'lost' == marker.type ? trans('common.lost') : trans('common.found')} <strong class="block">${mapMarker.title}</strong></span>`
            );
            mapInfoWindow.value?.open(mapMarker.map, mapMarker);

            selectedMarkerId.value = marker.id;
            markersStore.selectMarker(marker.id);

            setTimeout(() => preventFromRefresh.value = false, 1500);
        });

        return mapMarker;
    });
});

const initMap = async (): Promise<void> => {
    const loader = new Loader({
        apiKey: configService?.getGoogleCloudApiKey() as string,
        version: 'weekly',
    });

    const { Map } = await loader.importLibrary('maps') as google.maps.MapsLibrary;
    const { AdvancedMarkerElement } = await loader.importLibrary('marker') as google.maps.MarkerLibrary;

    mapInfoWindow.value = new google.maps.InfoWindow();

    mapInstance.value = new Map(map.value as HTMLElement, {
        center: {lat: 51.9259872, lng: 18.880941},
        zoom: 6,
        streetViewControl: false,
        fullscreenControl: false,
        zoomControlOptions: {
            position: google.maps.ControlPosition.RIGHT_TOP,
        },
        mapId: '5d6fcef6daa07b0c',
    });

    mapInstance.value.addListener('click', (mapsMouseEvent: google.maps.MapMouseEvent) => {
        if (selectedMarkerId.value) {
            markersStore.selectMarker(null);
            selectedMarkerId.value = null;
        }

        mapInfoWindow.value?.close();

        if (newMarker.value) {
            removeNewMarker();
        } else {
            placeNewMarker(mapsMouseEvent.latLng);
        }
    });

    /*
    localizationService?.getUserLocalization(
        (position: GeolocationPosition) => {
            mapInstance.value?.setCenter({
                lat: position.coords.latitude,
                lng: position.coords.longitude
            });
            mapInstance.value?.setZoom(11);
        }
    );
    */

    mapInstance.value.addListener('idle', debounce(() => {
        if (!preventFromRefresh.value) getMarkers();
    }, 500));
}

const getMarkers = (): void => {
    let bounds = mapInstance.value?.getBounds();

    if (!bounds) {
        return;
    }

    markersStore.index(
        bounds.getNorthEast().lat(),
        bounds.getSouthWest().lng(),
        bounds.getSouthWest().lat(),
        bounds.getNorthEast().lng()
    );
};

const placeNewMarker = (latLng: google.maps.LatLng | null): void => {
    newMarker.value = new google.maps.marker.AdvancedMarkerElement({
        position: latLng,
        map: toRaw(mapInstance.value),
        title: trans('bigMap.addPin'),
    });

    mapInfoWindow.value?.setContent(
        `<div class="text-center"><p>${trans('bigMap.hereWillAppearPinWithInformationAboutRegistrationPlate')}</p><button type="button" class="inline-block mt-2 h-[18px] leading-none px-2 mx-2 bg-purple-heart text-sm text-white transition-colors rounded hover:bg-cyan focus-visible:bg-cyan js-toggle-create-marker-modal">${trans('common.add')}</button></div>`
    );

    mapInfoWindow.value?.open(newMarker.value.map, newMarker.value);
};

const removeNewMarker = (): void => {
    if (newMarker.value) {
        newMarker.value.map = null;
        newMarker.value = null;
    }
};

</script>
