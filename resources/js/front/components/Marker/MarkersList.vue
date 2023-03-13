<template>
    <div>
        <div class="relative">
            <input
                type="text"
                :class="['form-control block w-full px-3 py-1.5 text-base text-mine-shaft bg-white bg-clip-padding border border-solid rounded transition ease-in-out m-0 flex-1 focus:text-mine-shaft focus:bg-white focus:border-orange focus:outline-none focus:shadow-orange', errorMessage ? 'border-red-600' : 'border-gray-300']"
                id="plate_number"
                name="plate_number"
                v-model="plateNumber"
                placeholder="Wpisz numer tablicy rejestracyjnej"
            >

            <span class="block absolute top-1/2 right-2 -translate-y-1/2">
                <svg class="h-4 w-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/></svg>
            </span>
        </div>

        <Loader v-if="loading" />
        <div class="mt-4" v-else>
            <a :href="marker.link" class="flex items-center w-full text-sm mt-4 border-t border-t-gray-600 border-t-solid pt-4 first-of-type:mt-0 first-of-type:border-t-0 hover:text-purple-heart transition-colors group" v-for="marker in filteredMarkers" :key="marker.id">
                <div class="w-full">
                    <span class="block font-display">{{ marker.plate_number }}</span>
                    <p class="mt-1">{{ shortenAdditionalInfo(marker.additional_info) }}</p>
                    <span class="flex items-center w-full mt-1" v-show="marker.formatted_address">
                        <svg class="h-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path class="transition-colors group-hover:fill-purple-heart" d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z"/></svg>
                        <span class="block ml-1">{{ marker.formatted_address }}</span>
                    </span>
                </div>
                <div class="h-8 w-auto">
                    <svg class="h-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path class="transition-colors group-hover:fill-purple-heart" d="M246.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L178.7 256 41.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z"/></svg>
                </div>
            </a>
        </div>

        <div class="mt-4">
            <Pagination
                v-if="markers.meta && markers.meta.pagination.total_pages > 1"
                :pagination="markers.meta.pagination"
                @selectPage="getMarkers"
                @prevPage="prevPage"
                @nextPage="nextPage"
            ></Pagination>
        </div>
    </div>
</template>

<script>
import { toRaw, defineAsyncComponent } from 'vue';
export default {
    components: {
        Pagination: defineAsyncComponent(() => import('./../Partials/Pagination.vue')),
        Loader: defineAsyncComponent(() => import('./../Partials/Loader.vue')),
    },
    props: [
        'extMarkers',
        'type',
    ],
    data() {
        return {
            plateNumber: '',
            errorMessage: '',
            loading: false,

            markers: {},
        };
    },
    mounted() {
        this.markers = this.extMarkers;

        this.searchDebounce = this.$root.debounce(() => this.getMarkers());
    },
    methods: {
        getMarkers(page = 1) {
            this.loading = true;

            axios.get('/ajax/markers', {
                params: {
                    plate_number: this.plateNumber,
                    type: this.type,
                    page,
                    paginate: true,
                },
            })
                .then((response) => {
                    this.markers = response.data.markers;
                    this.loading = false;
                })
                .catch((error) => {
                    this.$root.toast(error.response.data.message, 'danger');
                    this.loading = false;
                });
        },
        shortenAdditionalInfo(text) {
            return this.$root.cutWords(text, 10);
        },
        prevPage() {
            this.selectPage(parseInt(this.markers.meta.pagination.current_page, 10) - 1);
        },
        nextPage() {
            this.selectPage(parseInt(this.markers.meta.pagination.current_page, 10) + 1);
        },
    },
    watch: {
        plateNumber(newVal) {
            this.searchDebounce();
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
