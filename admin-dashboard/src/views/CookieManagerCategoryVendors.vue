<script setup lang="ts">

defineProps<{
    cookieCategory: CookieCategory
}>();

</script>

<template>
    <RouterView :cookie-category="cookieCategory" />
</template>