<template>
    <Message :severity="props.severity" :closable="props.closable">
        <slot />
    </Message>
</template>

<script setup lang="ts">

import Message from 'primevue/message';

const props = defineProps<{
    severity: string;
    closable: boolean;
}>();

</script>
