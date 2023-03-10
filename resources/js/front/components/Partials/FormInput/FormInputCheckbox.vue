<template>
    <div>
        <div class="flex flex-nowrap">
            <input
                type="checkbox"
                :class="['form-check-input block appearance-none h-4 w-4 border border-gray-300 rounded-sm bg-white checked:bg-orange checked:border-orange focus:outline-none transition duration-200 mt-1 align-top bg-no-repeat bg-center bg-contain float-left mr-2 cursor-pointer js-organization-payment-card-default-checkbox', errorMessage ? 'border-red-600' : 'border-gray-300']"
                :id="name"
                :name="name"
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
            >
            <label :for="name" :class="['form-check-label block w-full', {'text-red-600' : errorMessage}]">{{ label }}</label>
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
        prefix: String,
        suffix: String,
        required: Boolean,
        description: String,
    },
}
</script>
