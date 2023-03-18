<template>
    <div>
        <div class="h-64" ref="map"></div>
    </div>
</template>

<script>
import { Loader } from "@googlemaps/js-api-loader";
export default {
    props: [
        'type',
        'lat',
        'lng',
        'coordsList',
    ],
    data() {
        return {
            map: null,
        };
    },
    mounted() {
        this.initMap();
    },
    methods: {
        initMap() {
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

                    if (this.lat && this.lng) {
                        mapCenter = {lat: parseFloat(this.lat), lng: parseFloat(this.lng)};
                        this.map.setCenter(mapCenter);
                        this.map.setZoom(15);

                        new google.maps.Marker({
                            position: {
                                lat: parseFloat(this.lat),
                                lng: parseFloat(this.lng),
                            },
                            map: this.map,
                            icon: ('lost' == this.type ? '/images/markers/danger.png' : '/images/markers/primary.png'),
                        });
                    }

                    if (this.coordsList) {
                        this.coordsList.map((marker) => {
                            new google.maps.Marker({
                                position: {
                                    lat: parseFloat(marker.lat),
                                    lng: parseFloat(marker.lng),
                                },
                                map: this.map,
                                icon: ('lost' == marker.type ? '/images/markers/danger.png' : '/images/markers/primary.png'),
                            });
                        });

                        if (this.coordsList.length > 1) {
                            const bounds = new google.maps.LatLngBounds();

                            this.coordsList.map((marker) => bounds.extend(new google.maps.LatLng(parseFloat(marker.lat), parseFloat(marker.lng)))),

                            this.map.fitBounds(bounds);
                        } else {
                            this.map.setCenter({
                                lat: parseFloat(this.coordsList[0].lat),
                                lng: parseFloat(this.coordsList[0].lng),
                            });
                            this.map.setZoom(16);
                        }
                    }
                });
        },
    },
}
</script>
