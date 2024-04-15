<template>
    <div class="mt-20 flex flex-col h-[calc(100vh-5rem)] relative overflow-hidden">
        <FullScreenMap
            class="h-full"
        />

        <MarkerCard />

        <MarkerCreateModal />
    </div>
</template>

<script setup lang="ts">

import { defineAsyncComponent, ref } from 'vue';

const FullScreenMap = defineAsyncComponent(() => import('@front/components/map/FullScreenMap.vue'));
const MarkerCard = defineAsyncComponent(() => import('@front/components/markers/MarkerCard.vue'));
const MarkerCreateModal = defineAsyncComponent(() => import('@front/components/markers/MarkerCreateModal.vue'));

</script>
