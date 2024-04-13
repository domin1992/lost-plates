<template>
    <div>
        <label :for="name" :class="['font-bold text-sm mb-1', {'text-red-600' : errorMessage}]" v-if="label">{{ label }}</label>
        <div class="flex flex-row flex-nowrap mt-1">
            <textarea
                :class="['form-control block w-full px-3 py-1.5 text-base text-mine-shaft bg-white bg-clip-padding border border-solid rounded transition ease-in-out m-0 flex-1 focus:text-mine-shaft focus:bg-white focus:border-orange focus:outline-none focus:shadow-orange', errorMessage ? 'border-red-600' : 'border-gray-300']"
                :id="name"
                :name="name"
                :placeholder="placeholder"
                v-model="value"
            ></textarea>
        </div>
        <div class="mt-1 text-red-600 text-sm text-bold" v-if="errorMessage">{{ Array.isArray(errorMessage) && errorMessage[0] !== undefined ? errorMessage[0] : (errorMessage !== undefined ? errorMessage : "") }}</div>
        <div class="mt-1 text-gray-600 text-xs text-bold" v-if="!errorMessage && description">{{ description }}</div>
    </div>
</template>

<script setup lang="ts">

import { computed } from 'vue';

const $emit = defineEmits({
    'update:modelValue': (value: string) => true,
});

const props = defineProps<{
    label?: string,
    name: string,
    modelValue: string,
    errorMessage: string,
    required: boolean,
    description?: string,
    placeholder?: string,
}>();

const value = computed({
    get() {
        return props.modelValue;
    },
    set(value: string) {
        $emit('update:modelValue', value);
    },
});

</script>
