<template>
    <div class="results">
        <div v-bind:class="['result-item', {'active' : activeMarkerId == marker.id}]" v-for="marker in markers" v-if="undefined !== markers">
            <div class="icon-wrapper">
                <img v-bind:src="('lost' == marker.type ? '/images/markers/danger.png' : '/images/markers/primary.png')" class="img-fluid" alt="marker">
            </div>
            <div class="content-wrapper">
                <p><strong>{{ marker.plate_number }}</strong></p>

                <p v-if="marker.phone_number && (undefined === marker.phone_number_visible || !marker.phone_number_visible)"><i class="fas fa-phone"></i> <button class="btn-naked btn-link" title="Odkryj" v-on:click="discoverPhoneNumber(marker.id)">{{ marker.phone_number }}</button></p>
                <p v-if="marker.phone_number && undefined !== marker.phone_number_visible && marker.phone_number_visible"><i class="fas fa-phone"></i> <a v-bind:href="'tel:' + marker.phone_number">{{ marker.phone_number }}</a></p>

                <p v-if="marker.email && (undefined === marker.email_visible || !marker.email_visible)"><i class="fas fa-envelope"></i> <button class="btn-naked btn-link" title="Odkryj" v-on:click="discoverEmail(marker.id)">{{ marker.email }}</button></p>
                <p v-if="marker.email && undefined !== marker.email_visible && marker.email_visible"><i class="fas fa-envelope"></i> <a v-bind:href="'mailto:' + marker.email">{{ marker.email }}</a></p>

                <p v-if="marker.additional_info"><small>{{ marker.additional_info }}</small></p>

                <div class="miniatures-gallery miniatures-gallery-presentation" v-if="marker.marker_media.length > 0">
                    <div class="miniature-wrapper" v-for="markerMedia in marker.marker_media">
                        <button type="button" class="miniature-item" v-on:click="showImageGallery(marker.id, markerMedia.id)">
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
        props: ['markers', 'activeMarkerId'],
        data(){
            return {};
        },
        created(){

        },
        mounted(){

        },
        methods: {
            discoverPhoneNumber(markerId){
                this.$parent.discoverPhoneNumber(markerId);
            },
            discoverEmail(markerId){
                this.$parent.discoverEmail(markerId);
            },
            showImageGallery(markerId, markerMediaId){
                this.$root.$emit('show-images-gallery', {
                    marker_id: markerId,
                    marker_media_id: markerMediaId,
                });
            }
        },
    }
</script>