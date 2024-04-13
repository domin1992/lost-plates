<template>
    <div>
        <label :for="name" :class="['font-bold text-sm mb-1', {'text-red-600' : errorMessage}]" v-if="label">{{ label }}</label>
        <div class="flex flex-row flex-nowrap mt-1">
            <span class="px-3 py-1.5 text-base leading-[1.2] border-t border-l border-b border-solid border-gray-300 bg-gray-300" v-if="prefix">{{ prefix }}</span>
            <input
                type="text"
                :class="['form-control block w-full px-3 py-1.5 text-base text-mine-shaft bg-white bg-clip-padding border border-solid rounded transition ease-in-out m-0 flex-1 focus:text-mine-shaft focus:bg-white focus:border-orange focus:outline-none focus:shadow-orange', {'rounded-tl-none rounded-bl-none' : prefix}, {'rounded-tr-none rounded-br-none' : suffix}, errorMessage ? 'border-red-600' : 'border-gray-300']"
                :id="name"
                :name="name"
                :placeholder="placeholder"
                v-model="value"
            >
            <span class="px-3 py-1.5 text-base leading-[1.4] border-t border-r border-b border-solid border-gray-300 bg-gray-300 rounded-r" v-if="suffix">{{ suffix }}</span>
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
    prefix?: string,
    suffix?: string,
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
