<template>
    <div class="map-wrapper">
        <Alert
            :message="$t('bigMap.clickOnMapToAddPin')"
            type="info"
            customClasses="absolute top-12 left-0 right-0 w-5/6 z-10 mt-28"
            @closed="alertClosed"
            v-if="shouldShowAlert()"
        />

        <div class="h-[calc(100vh-3.5rem)]" ref="map"></div>

        <MapDetails
            :activeMarker="activeMarker"
            @detailsHidden="() => {closeInfoWindow(); activeMarker = null;}"
            @refreshMarker="refreshMarker"
        />

        <button class="fixed bottom-4 right-4 w-16 h-16 rounded-full bg-purple-heart flex justify-center items-center hover:bg-cyan focus:bg-cyan transition-colors" @click="triggerSeach">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" v-show="!usingSearch"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path class="fill-white" d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/></svg>
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" v-show="usingSearch"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path class="fill-white" d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"/></svg>
        </button>

        <SearchModal
            @onFoundResults="foundSearchResults"
        />

        <CreateMarkerModal
            @markerStored="getMarkers()"
        />
    </div>
</template>

<script>
import { Loader } from "@googlemaps/js-api-loader";
import { defineAsyncComponent, isProxy, toRaw } from 'vue';
import Cookies from 'js-cookie';
import { trans } from 'laravel-vue-i18n';
export default {
    components: {
        Alert: defineAsyncComponent(() => import('./../Partials/Alert.vue')),
        MapDetails: defineAsyncComponent(() => import('./MapDetails.vue')),
        SearchModal: defineAsyncComponent(() => import('./../Partials/SearchModal.vue')),
        CreateMarkerModal: defineAsyncComponent(() => import('./CreateMarkerModal.vue')),
    },
    data() {
        return {
            map: null,
            newMarker: null,
            plateNumber: '',
            timeout: null,
            mapMarkers: [],
            markers: [],
            activeMarker: null,
            infoWindow: null,
            usingSearch: false,
        };
    },
    mounted() {
        this.initMap();

        document.addEventListener('click', (e) => {
            if (e.target.matches('.js-toggle-create-marker-modal')) {
                this.emitter.emit('create-marker-modal', {
                    lat: toRaw(this.newMarker).position.lat(),
                    lng: toRaw(this.newMarker).position.lng(),
                });
            }
        });
    },
    methods: {
        initMap(params = {}) {
            new Loader({
                apiKey: window.globals.googleCloudApiKey,
                version: "weekly",
                libraries: ["places", "geocoding"],
            })
                .load()
                .then(() => {
                    let mapCenter = {lat: 51.9259872, lng: 18.880941};

                    this.map = new google.maps.Map(this.$refs.map, {
                        center: mapCenter,
                        zoom: 7,
                        streetViewControl: false,
                        fullscreenControl: false,
                        zoomControlOptions: {
                            position: google.maps.ControlPosition.RIGHT_TOP,
                        },
                    });

                    this.map.addListener('click', (mapsMouseEvent) => {
                        this.activeMarker = null;

                        if (this.infoWindow) {
                            this.infoWindow.close();
                        }

                        if (null == this.newMarker) {
                            this.placeNewMarker(mapsMouseEvent.latLng);
                        } else {
                            this.removeNewMarker();
                        }
                    });

                    google.maps.event.addListener(this.map, 'idle', () => {
                        if (!this.usingSearch) {
                            this.getMarkers();
                        }
                    });

                    this.setMapCenterBasedOnNavigator();

                    this.getMarkers();
                });
        },
        setMapCenterBasedOnNavigator() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    if (null != this.map) {
                        this.map.setCenter({
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        });
                        this.map.setZoom(11);
                    }
                });
            }
        },
        placeNewMarker(latLng) {
            const infoWindow = new google.maps.InfoWindow({
                content: `<div class="text-center"><p>${trans('bigMap.hereWillAppearPinWithInformationAboutRegistrationPlate')}</p><button type="button" class="inline-block mt-2 h-[18px] leading-none px-2 mx-2 bg-purple-heart text-sm text-white transition-colors rounded hover:bg-cyan focus-visible:bg-cyan js-toggle-create-marker-modal">${trans('common.add')}</button></div>`,
            });

            this.newMarker = new google.maps.Marker({
                position: latLng,
                map: this.map,
                title: trans('bigMap.addPin'),
            });

            infoWindow.open(this.map, this.newMarker);
        },
        removeNewMarker() {
            isProxy(this.newMarker) ? toRaw(this.newMarker).setMap(null) : this.newMarker.setMap(null);
            this.newMarker = null;
        },
        showAddMarkerForm() {
            this.emitter.emit('show-add-marker-form', {
                lat: this.newMarker.position.lat(),
                lng: this.newMarker.position.lng()
            });
        },
        getMarkers() {
            let bounds = this.map.getBounds();
            if (undefined === bounds) {
                return;
            }

            let params = {
                corners: {
                    nw_lat: bounds.getNorthEast().lat(),
                    nw_lng: bounds.getSouthWest().lng(),
                    se_lat: bounds.getSouthWest().lat(),
                    se_lng: bounds.getNorthEast().lng(),
                }
            };

            if (this.plateNumber) {
                params.plate_number = this.plateNumber;
            }

            axios.get('/ajax/markers', {
                params: params
            })
                .then((response) => {
                    isProxy(this.mapMarkers)
                        ? toRaw(this.mapMarkers).map((marker) => marker.setMap(null))
                        : this.mapMarkers.map((marker) => marker.setMap(null));
                    this.markers = response.data.markers;
                    this.setMarkersOnMap();
                });
        },
        setMarkersOnMap() {
            this.mapMarkers = [];

            this.filteredMarkers.map((marker) => {
                const _marker = new google.maps.Marker({
                    position: {
                        lat: parseFloat(marker.lat),
                        lng: parseFloat(marker.lng),
                    },
                    map: this.map,
                    icon: ('lost' == marker.type ? '/images/markers/danger.png' : '/images/markers/primary.png'),
                    title: marker.plate_number,
                    marker_id: marker.id,
                });

                google.maps.event.addListener(_marker, 'click', ((_marker) => {
                    return () => {
                        this.setActiveMarker(_marker.marker_id);
                    };
                })(_marker));

                this.mapMarkers.push(_marker);
            });
        },
        setActiveMarker(markerId) {
            if (this.infoWindow) {
                this.infoWindow.close();
            }

            if (this.activeMarker && this.activeMarker.id == markerId) {
                this.activeMarker.id = 0;
            } else {
                this.activeMarker = this.filteredMarkers.find((marker) => marker.id == markerId);
                this.infoWindow = new google.maps.InfoWindow({
                    content:
                        `<div class="text-center">
                            ${trans(`common.${this.activeMarker.type}`)}<br>
                            <strong>${this.activeMarker.plate_number}</strong>
                        </div>`,
                });
                this.infoWindow.open(this.map, this.mapMarkers.find((marker) => marker.marker_id == markerId));
            }
        },
        closeInfoWindow() {
            if (this.infoWindow) {
                this.infoWindow.close();
            }
        },
        triggerSeach() {
            if (!this.usingSearch) {
                this.emitter.emit('search-modal');
            } else {
                this.getMarkers();
            }

            this.usingSearch = false;
        },
        foundSearchResults(markers) {
            this.usingSearch = true;

            isProxy(this.mapMarkers)
                ? toRaw(this.mapMarkers).map((marker) => marker.setMap(null))
                : this.mapMarkers.map((marker) => marker.setMap(null));
            this.markers = markers;
            this.setMarkersOnMap();

            if (this.filteredMarkers.length > 1) {
                const bounds = new google.maps.LatLngBounds();

                markers.map((marker) => bounds.extend(new google.maps.LatLng(marker.lat, marker.lng))),

                this.map.fitBounds(bounds);
            } else {
                this.map.setCenter({
                    lat: parseFloat(this.filteredMarkers[0].lat),
                    lng: parseFloat(this.filteredMarkers[0].lng),
                });
                this.map.setZoom(16);
            }
        },
        alertClosed() {
            Cookies.set('bigMapAlertClosed', '1', { expires: 3 });
        },
        shouldShowAlert() {
            return !Cookies.get('bigMapAlertClosed');
        },
        refreshMarker(params) {
            axios.get(`/ajax/markers/${params.markerId}`)
                .then((response) => {
                    this.markers = Object.entries(toRaw(this.markers))
                        .map((entry) => {
                            if (entry[0] !== 'meta') {
                                entry[1] = response.data.marker;
                            }

                            return entry;    
                        });

                    this.setMarkersOnMap();

                    if (this.activeMarker && this.activeMarker.id == params.markerId) {
                        this.activeMarker = response.data.marker;
                    }
                });
        },
    },
    computed: {
        filteredMarkers() {
            return Object.entries(toRaw(this.markers))
                .filter((entry) => entry[0] !== 'meta')
                .map((entry) => entry[1]);
        },
    },
}
</script>
