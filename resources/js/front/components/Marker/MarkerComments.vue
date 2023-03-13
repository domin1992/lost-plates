<template>
    <div class="flex-1 mt-4 overflow-y-hidden flex flex-col">
        <div class="overflow-y-auto">
            <div class="text-sm mt-4 border-t border-t-gray-600 border-t-solid pt-4 first-of-type:mt-0 first-of-type:border-t-0" v-for="markerComment in markerComments" :key="markerComment.id">
                <p>{{ markerComment.content }}</p>
                <div class="flex flex-col lg:flex-row justify-between text-gray-600 mt-1">
                    <span class="block text-xs">~{{ markerComment.name }}</span>
                    <span class="block text-xs">{{ markerComment.created_at_display }}</span>
                </div>
            </div>

            <p class="text-center text-gray-600" v-if="!markerComments?.length">
                Bądź pierwszym i dodaj komentarz!
            </p>
        </div>
        <div class="mt-auto pt-4">
            <FormInputText
                name="name"
                placeholder="Imię"
                v-model="newCommentName"
                :errorMessage="errors.name !== undefined ? errors.name : null"
            />

            <div class="mt-1">
                <FormInputTextarea
                    name="content"
                    placeholder="Komentarz"
                    v-model="newCommentContent"
                    :errorMessage="errors.content !== undefined ? errors.content : null"
                />
            </div>

            <div class="mt-1">
                <button class="block w-full h-[2.25rem] leading-[2.25rem] px-4 bg-purple-heart text-white transition-colors rounded hover:bg-cyan focus-visible:bg-cyan" @click="submitMarkerComment">Dodaj komentarz</button>
            </div>
        </div>
    </div>
</template>

<script>
import { defineAsyncComponent } from 'vue';
export default {
    components: {
        FormInputText: defineAsyncComponent(() => import('./../Partials/FormInput/FormInputText.vue')),
        FormInputTextarea: defineAsyncComponent(() => import('./../Partials/FormInput/FormInputTextarea.vue')),
    },
    props: [
        'marker',
    ],
    data() {
        return {
            newCommentName: '',
            newCommentContent: '',

            errors: {},
        };
    },
    methods: {
        submitMarkerComment() {
            axios.post(`/ajax/markers/${this.marker.id}/comments`, {
                name: this.newCommentName,
                content: this.newCommentContent,
            })
                .then((response) => {
                    this.$emit('refreshMarker');

                    this.newCommentName = this.newCommentContent = '';
                })
                .catch((error) => {
                    this.errors = error.response.data.errors;
                });
        },
    },
    computed: {
        markerComments() {
            return this.marker?.marker_comments
                .sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
        },
    },
}
</script>
