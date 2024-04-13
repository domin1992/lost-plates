<template>
    <button
        :type="filteredType()"
        :class="buttonClass()"
        :disabled="props.disabled"
        v-if="determineTag() === 'button'"
        @click="$emit('click')"
    >
        <slot />
    </button>
    
    <RouterLink
        v-if="determineTag() === 'routerLink'"
        :class="buttonClass()"
        :to="props.to ? props.to : '/'"
    >
        <slot />
    </RouterLink>
    
    <a
        :href="props.to && typeof props.to == 'string' ? props.to : '/'"
        :class="buttonClass()"
        v-if="determineTag() === 'link'"
    >
        <slot />
    </a>
</template>

<script setup lang="ts">

import { RouterLink } from 'vue-router';

const props = defineProps({
    type: String,
    to: [String, Object],
    class: String,
    disabled: Boolean,
});

const defaultButtonClass = 'inline-block h-[2.25rem] leading-[2.25rem] px-4 bg-purple-heart text-white transition-colors rounded hover:bg-cyan focus-visible:bg-cyan';

const buttonClass = (): String => {
    return `${defaultButtonClass} ${props.class}`;
}

const determineTag = (): String => {
    return props.type === 'link'
        ? 'link'
        : (
            props.type === 'routerLink'
                ? 'routerLink'
                : 'button'
        );
};

const filteredType = (): 'button' | 'submit' | 'reset' => {
    if (!props.type || !['button', 'submit', 'reset'].includes(props.type)) {
        return 'button';
    }

    return props.type as 'button' | 'submit' | 'reset';
};

</script>
