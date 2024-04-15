<script setup lang="ts">

import type {RouteLocationRaw} from "vue-router";

defineProps<{
    to: string|RouteLocationRaw
}>();
</script>

<template>
    <RouterLink :to="to" v-slot="{ href, isActive, navigate }" custom>
        <a
            :class="isActive ? 'text-black' : 'text-blue-600'"
            class="focus:text-blue-600 no-underline"
            :href="isActive ? undefined : href">
            <slot/>
        </a>
    </RouterLink>
</template>