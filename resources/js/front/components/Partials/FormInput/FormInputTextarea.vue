<template>
    <div>
        <label :for="name" :class="['font-bold text-sm mb-1', {'text-red-600' : errorMessage}]">{{ label }}</label>
        <div class="flex flex-row flex-nowrap mt-1">
            <textarea
                :class="['form-control block w-full px-3 py-1.5 text-base text-mine-shaft bg-white bg-clip-padding border border-solid rounded transition ease-in-out m-0 flex-1 focus:text-mine-shaft focus:bg-white focus:border-orange focus:outline-none focus:shadow-orange', errorMessage ? 'border-red-600' : 'border-gray-300']"
                :id="name"
                :name="name"
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
            ></textarea>
        </div>
        <div class="mt-1 text-red-600 text-sm text-bold" v-if="errorMessage">{{ Array.isArray(errorMessage) && errorMessage[0] !== undefined ? errorMessage[0] : (errorMessage !== undefined ? errorMessage : "") }}</div>
        <div class="mt-1 text-gray-600 text-xs text-bold" v-if="!errorMessage && description">{{ description }}</div>
    </div>
</template>

<script>
export default {
    props: {
        label: String,
        name: String,
        modelValue: String,
        errorMessage: [String, Array],
        required: Boolean,
        description: String,
    },
}
</script>
