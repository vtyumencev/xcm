<script setup lang="ts">

defineProps<{
    error?: WPRestError
}>();

</script>

<template>
    <div class="">
        <div v-if="error" class="mb-5 text-red-600 py-3 px-5 bg-red-50">
            Error: {{ error.message }}
        </div>
        <div class="">
            <slot />
        </div>
    </div>
</template>
