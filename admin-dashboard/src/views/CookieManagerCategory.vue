<script setup lang="ts">
import CustomRouterLink from "@/components/CustomRouterLink.vue";
import {useSource} from "@/composables/useSource";
import {unref} from "vue";
import LoadingSkeleton from "@/components/LoadingSkeleton.vue";

const props = defineProps<{
    categoryId: string
}>();

const { data: cookieCategory, trigger } = useSource<CookieCategory>(
    () => `categories/${props.categoryId}`,
    'manager-category',
    'Category',
    {
        onReady(data: CookieCategory) {
            data.necessary = data.necessary === '1'
        },
    }
);

</script>

<template>
    <div v-if="cookieCategory">
        <RouterView :cookieCategory="cookieCategory" @updated="trigger" />
    </div>
    <LoadingSkeleton v-else />
</template>