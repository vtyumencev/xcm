<script setup lang="ts">

import type {RouteParams} from "vue-router";

defineProps<{
    buttons?: {
        label: string,
        link: RouteParams
    }[]
}>();


</script>

<template>
    <div class="flex items-center">
        <h1>
            <slot />
        </h1>
        <div v-if="buttons" class="ml-5 space-x-2">
            <RouterLink
                v-for="button of buttons"
                :to="button.link"
                class="border border-solid px-3 py-1 rounded-sm no-underline">
                {{ button.label }}
            </RouterLink>
        </div>
    </div>
</template>