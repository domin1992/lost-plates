<template>
    <div class="mt-14 flex flex-col h-[calc(100vh-3.5rem)] relative">
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
