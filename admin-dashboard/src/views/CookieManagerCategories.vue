<script setup lang="ts">
import apiFetch from '@wordpress/api-fetch';
import {onMounted, ref, unref} from "vue";
import CustomRouterLink from "@/components/CustomRouterLink.vue";
import PageHeader from "@/components/PageHeader.vue";

const cookieCategories = ref<CookieCategory[]>();

onMounted(async () => {
    cookieCategories.value = await apiFetch<CookieCategory[]>({
        path: `categories`,
        method: 'GET',
    });
});

</script>

<template>
    <div>
        <PageHeader :buttons="[
            {
                label: 'Add new',
                link: { name: 'manager-categories-new' }
            },
        ]">
            {{ unref($route.meta.title) }}
        </PageHeader>
        <div class="grid grid-cols-[2fr_1fr] font-bold pb-2">
            <div class="">Category's name</div>
            <div class="">Vendors Count</div>
        </div>
        <div v-for="category of cookieCategories" class="py-2 px-2 -mx-2 hover:bg-gray-100 rounded-sm">
            <div class="grid grid-cols-[2fr_1fr]">
                <CustomRouterLink
                    class="text-lg"
                    :to="{ name: 'manager-category', params: { categoryId: category.id } }">
                    <span v-if="category.name">
                        {{ category.name }}
                    </span>
                    <span v-else-if="category.name_default">
                        <span class="text-gray-900">Unnamed category (</span>default: {{ category.name_default }}<span class="text-gray-900">)</span>
                    </span>
                    <span v-else>
                        <span class="text-gray-900">Unnamed category</span>
                    </span>
                </CustomRouterLink>
                <div class="">{{ category.vendors_count }}</div>
            </div>
        </div>
    </div>
</template>