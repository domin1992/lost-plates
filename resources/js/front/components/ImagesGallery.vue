<template>
    <div class="modal fade" id="images-gallery" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
                <div class="modal-body">
                    <img v-bind:src="currentMarkerMedia.media.url.hd" class="img-fluid" v-if="null != currentMarkerMedia">
                </div>
                <div class="modal-footer" v-if="null != marker">
                    <div v-bind:class="['image-miniature-wrapper', {'active' : null != currentMarkerMedia && markerMedia.id == currentMarkerMedia.id}]" v-for="markerMedia in marker.marker_media">
                        <button type="button" v-on:click="showMarkerMedia(markerMedia.id)">
                            <img v-bind:src="markerMedia.media.url.miniature" class="img-fluid">
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [],
        data(){
            return {
                marker: null,
                currentMarkerMedia: null,
            };
        },
        created(){

        },
        mounted(){
            this.$root.$on('show-images-gallery', (params) => this.trigger(params));
        },
        methods: {
            trigger(params = {}){
                this.getMarker(params.marker_id, params.marker_media_id);
            },
            getMarker(markerId, markerMediaId){
                let self = this;

                axios.get('/ajax/markers/' + markerId)
                .then(response => {
                    self.marker = response.data.marker;
                    for(let i in self.marker.marker_media){
                        if(markerMediaId == self.marker.marker_media[i].id){
                            self.currentMarkerMedia = self.marker.marker_media[i];
                            $('#images-gallery').modal('show');
                        }
                    }
                })
                .catch(errors => {
                    console.log(errors.response);
                });
            },
            showMarkerMedia(markerMediaId){
                for(let i in this.marker.marker_media){
                    if(markerMediaId == this.marker.marker_media[i].id){
                        this.currentMarkerMedia = this.marker.marker_media[i];
                    }
                }
            },
        },
    }
</script>