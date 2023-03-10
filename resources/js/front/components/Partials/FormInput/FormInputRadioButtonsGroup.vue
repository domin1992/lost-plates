<template>
    <div>
        <span :class="['font-bold text-sm mb-1', {'text-red-600' : errorMessage}]" v-if="label">{{ label }}</span>
        <div class="flex flex-col md:flex-row">
            <div class="relative" v-for="(option, key) in options" :key="option.value">
                <input
                    type="radio"
                    class="invisible absolute top-0 left-0 w-0 h-0 peer"
                    :name="name"
                    :id="`${name}_${option.value}`"
                    :value="option.value"
                    :checked="option.value === modelValue"
                    @input="$emit('update:modelValue', option.value)"
                >
                <label
                    :for="`${name}_${option.value}`"
                    :class="['inline-block h-[2.25rem] leading-[2.25rem] px-4 bg-purple-heart text-white transition-colors cursor-pointer hover:bg-cyan focus-visible:bg-cyan peer-checked:bg-cyan', {'rounded-l': key === 0}, {'rounded-r': key === options.length - 1}]"
                >
                    {{ option.label }}
                </label>
            </div>
        </div>
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
        options: Array,
        description: String,
    },
}
</script>
