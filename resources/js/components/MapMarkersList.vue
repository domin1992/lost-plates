<template>
    <div class="results">
        <div v-bind:class="['result-item', {'active' : activeMarkerId == marker.id}]" v-for="marker in markers" v-if="undefined !== markers">
            <div class="icon-wrapper">
                <img v-bind:src="('lost' == marker.type ? '/images/markers/danger.png' : '/images/markers/primary.png')" class="img-fluid" alt="marker">
            </div>
            <div class="content-wrapper">
                <p><strong>{{ marker.plate_number }}</strong></p>
                <p v-if="marker.phone_number"><i class="fas fa-phone"></i> <button class="btn-naked btn-link" title="Odkryj" v-on:click="discoverPhoneNumber(marker.id)">{{ marker.phone_number }}</button></p>
                <p v-if="marker.email"><i class="fas fa-envelope"></i> <button class="btn-naked btn-link" title="Odkryj" v-on:click="discoverEmail(marker.id)">{{ marker.email }}</button></p>
                <p v-if="marker.additional_info"><small>{{ marker.additional_info }}</small></p>
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
        },
    }
</script>