<template>
    <div class="flex-1 mt-4 overflow-y-hidden flex flex-col">
        <div class="overflow-y-auto">
            <div class="text-sm mt-4 border-t border-t-gray-600 border-t-solid pt-4 first-of-type:mt-0 first-of-type:pt-0 first-of-type:border-t-0" v-for="markerComment in markerComments" :key="markerComment.id">
                <p>{{ markerComment.content }}</p>
                <div class="flex flex-col lg:flex-row justify-between text-gray-600 mt-1">
                    <span class="block text-xs">~{{ markerComment.name }}</span>
                    <span class="block text-xs">{{ markerComment.createdAtDisplay }}</span>
                </div>
            </div>

            <p class="text-center text-gray-600" v-if="!markerComments?.length">
                {{ $t('markerComments.beFirstToComment') }}
            </p>
        </div>
        <div class="mt-auto pt-4">
            <FormInputText
                name="name"
                :placeholder="$t('common.firstName')"
                v-model="name"
                :errorMessage="$messageBagFirstError(errors, 'name')"
                :required="true"
            />

            <div class="mt-1">
                <FormInputTextarea
                    name="content"
                    :placeholder="$t('markerComments.comment')"
                    v-model="content"
                    :errorMessage="$messageBagFirstError(errors, 'content')"
                    :required="true"
                />
            </div>

            <div class="mt-1">
                <button class="block w-full h-[2.25rem] leading-[2.25rem] px-4 bg-purple-heart text-white transition-colors rounded hover:bg-cyan focus-visible:bg-cyan" @click="submitMarkerComment">{{ $t('markerComments.addComment') }}</button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">

import { computed, defineAsyncComponent, ref } from 'vue';
import type { Marker } from '@front/interfaces/Marker';
import type { MarkerComment } from '@front/interfaces/MarkerComment';
import type { MessageBagError } from '@front/interfaces/MessageBagError';
import { useMarkersStore } from '@front/stores/markers';
import type { ApiRequestFailedException } from '@front/exceptions/ApiRequestFailedException';

const FormInputText = defineAsyncComponent(() => import('./../partials/form-input/FormInputText.vue'));
const FormInputTextarea = defineAsyncComponent(() => import('./../partials/form-input/FormInputTextarea.vue'));

const markersStore = useMarkersStore();

const props = defineProps<{
    marker: Marker | null;
}>();

const name = ref<string>('');
const content = ref<string>('');
const errors = ref<MessageBagError>({});

const markerComments = computed(
    () => props?.marker?.markerComments
        .sort((a: MarkerComment, b: MarkerComment) => (new Date(b.createdAt)).getTime() - (new Date(a.createdAt)).getTime())
);

const submitMarkerComment = () => {
    if (props.marker?.id == null) return;

    markersStore.storeMarkerComment(
        props.marker.id,
        name.value,
        content.value
    )
        .then(() => {
            name.value = content.value = '';
        })
        .catch((error: ApiRequestFailedException) => {
            errors.value = error.getMessageBagError();
        });
};
</script>
