<template>
    <div class="map-wrapper">
        <div class="map-holder">
            <div class="alert alert-info information-toast alert-dismissible fade show">
                Kliknij na mapie, aby dodać pineskę.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="map"></div>
        </div>
        <div class="filters-wrapper">
            <h1 class="text-center">
                <p class="logo">
                    Lost plates
                </p>
            </h1>
            <div class="filters">
                <div class="form-group">
                    <label for="plate_number">Szukaj po numerze tablicy</label>
                    <input type="text" class="form-control" id="plate_number" name="plate_number" v-model="plateNumber" v-on:keyup="plateNumberChanged()">
                </div>
            </div>
            <map-markers-list
                v-bind:markers="markers"
                v-bind:active-marker-id="activeMarkerId"
            ></map-markers-list>
            <images-gallery></images-gallery>
        </div>
    </div>
</template>

<script>
    export default {
        props: [],
        data(){
            return {
                map: null,
                newMarker: null,
                plateNumber: '',
                timeout: null,
                mapMarkers: [],
                markers: [],
                activeMarkerId: 0,
            };
        },
        created(){
        },
        mounted(){
            let self = this;

            this.$root.$on('init-map', (params) => this.initMap(params));
            this.$root.$on('refresh-map', (params) => this.initMap(params));
            $(document).delegate('#show-add-marker-form', 'click', () => {
                self.showAddMarkerForm();
            });
        },
        methods: {
            initMap(params = {}){
                let self = this;
                let mapCenter = {lat: 51.9259872, lng: 18.880941};

                if($('#map').length <= 0)
                    return;

                this.map = new google.maps.Map($('#map')[0], {
                    center: mapCenter,
                    zoom: 7,
                });

                this.map.addListener('click', function(mapsMouseEvent){
                    if(null == self.newMarker){
                        self.placeNewMarker(mapsMouseEvent.latLng);
                    }
                    else{
                        self.removeNewMarker();
                    }
                });

                google.maps.event.addListener(this.map, 'idle', function(){
                    self.getMarkers();
                });

                this.setMapCenterBasedOnNavigator();

                this.getMarkers();
            },
            setMapCenterBasedOnNavigator(){
                let self = this;

                if(navigator.geolocation){
                    navigator.geolocation.getCurrentPosition((position) => {
                        if(null != self.map){
                            self.map.setCenter({
                                lat: position.coords.latitude,
                                lng: position.coords.longitude
                            });
                            self.map.setZoom(11);
                        }
                    });
                }
            },
            placeNewMarker(latLng){
                let infowindow = new google.maps.InfoWindow({
                    content: '<div class="text-center"><p>Tutaj pojawi się pineska<br>z informacją o tablicach rejestracyjnych</p><button type="button" id="show-add-marker-form" class="btn btn-primary btn-sm">Dodaj</button></div>',
                });

                this.newMarker = new google.maps.Marker({
                    position: latLng,
                    map: this.map,
                    title: 'Dodaj pineskę',
                });

                infowindow.open(this.map, this.newMarker);
            },
            removeNewMarker(){
                this.newMarker.setMap(null);
                this.newMarker = null;
            },
            showAddMarkerForm(){
                this.$root.$emit('show-add-marker-form', {
                    lat: this.newMarker.position.lat(),
                    lng: this.newMarker.position.lng()
                });
            },
            plateNumberChanged(){
                let self = this;

                clearTimeout(self.timeout);

                self.timeout = setTimeout(() => {
                    self.getMarkers();
                }, 500);
            },
            getMarkers(){
                let self = this;

                let bounds = self.map.getBounds();
                if(undefined === bounds){
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

                if('' != self.plateNumber){
                    params.plate_number = self.plateNumber;
                }

                axios.get('/ajax/markers', {
                    params: params
                })
                .then(response => {
                    self.markers = response.data.markers;
                    self.setMarkersOnMap();
                })
                .catch(errors => {
                    console.log(errors.response);
                });
            },
            setMarkersOnMap(){
                let self = this;

                for(let i in this.mapMarkers){
                    this.mapMarkers[i].setMap(null);
                }
                this.mapMarkers = [];

                let marker;
                for(let i in this.markers){
                    marker = new google.maps.Marker({
                        position: {
                            lat: parseFloat(self.markers[i].lat),
                            lng: parseFloat(self.markers[i].lng)
                        },
                        map: self.map,
                        icon: ('lost' == self.markers[i].type ? '/images/markers/danger.png' : '/images/markers/primary.png'),
                        title: self.markers[i].plate_number,
                        marker_id: self.markers[i].id,
                    });

                    google.maps.event.addListener(marker, 'click', (function(marker){
                        return function(){
                            self.setActiveMarker(marker.marker_id);
                        }
                    })(marker));

                    this.mapMarkers.push(marker);
                }
            },
            setActiveMarker(markerId){
                if(this.activeMarkerId == markerId)
                    this.activeMarkerId = 0;
                else
                    this.activeMarkerId = markerId;
            },
            discoverPhoneNumber(markerId){
                let self = this;

                axios.get('/ajax/markers/' + markerId + '/phone-number')
                .then(response => {
                    for(let i in self.markers){
                        if(self.markers[i].id == markerId){
                            self.markers[i].phone_number = response.data.phone_number;
                            self.markers[i].phone_number_visible = true;
                        }
                    }
                })
                .catch(errors => {
                    console.log(errors.response);
                });
            },
            discoverEmail(markerId){
                let self = this;

                axios.get('/ajax/markers/' + markerId + '/email')
                .then(response => {
                    for(let i in self.markers){
                        if(self.markers[i].id == markerId){
                            self.markers[i].email = response.data.email;
                            self.markers[i].email_visible = true;
                        }
                    }
                })
                .catch(errors => {
                    console.log(errors.response);
                });
            },
        },
    }
</script>
