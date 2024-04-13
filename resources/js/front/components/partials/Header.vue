<template>
    <header class="py-4 bg-white fixed z-40 w-full top-0 left-0 shadow-lg">
        <div class="px-4 flex justify-between">
            <RouterLink :to="{ name: 'home' }" class="logo font-display uppercase inline-block">
                Lost plates
            </RouterLink>

            <!-- <button class="w-4 h-4">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M0 96C0 78.3 14.3 64 32 64H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H416c17.7 0 32 14.3 32 32z"/></svg>
            </button> -->
        </div>
    </header>
</template>

<script setup lang="ts">

import { RouterLink } from 'vue-router';

</script>
