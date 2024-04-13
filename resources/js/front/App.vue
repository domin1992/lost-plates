<template>
    <Header />
    <RouterView />
</template>

<script setup lang="ts">

import { defineAsyncComponent } from 'vue';

const Header = defineAsyncComponent(() => import('@front/components/partials/Header.vue'));

</script>