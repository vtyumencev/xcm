<script setup lang="ts">

import apiFetch from '@wordpress/api-fetch';
import PageHeader from "@/components/PageHeader.vue";
import {onMounted, ref} from "vue";
import CustomRouterLink from "@/components/CustomRouterLink.vue";

const props = defineProps<{
    cookieCategory: CookieCategory
}>();

const vendors = ref<CookieVendor[]>();

onMounted(async () => {
    vendors.value = await apiFetch<CookieVendor[]>({
        path: `vendors?category=${props.cookieCategory.id}`,
        method: 'GET',
    });
});

</script>

<template>
    <PageHeader :buttons="[
            {
                label: 'Add new',
                link: { name: 'manager-category-vendors-new' }
            },
        ]">
        Vendors of {{ cookieCategory.name }}
    </PageHeader>
    <div>
        <div class="grid grid-cols-[2fr_1fr] font-bold pb-2">
            <div class="">Vendor's name</div>
            <div class="">Cookies Count</div>
        </div>
        <div v-for="vendor of vendors" class="py-2 px-2 -mx-2 hover:bg-gray-100 rounded-sm">
            <div class="grid grid-cols-[2fr_1fr]">
                <CustomRouterLink
                    class="text-lg"
                    :to="{ name: 'manager-category-vendors-edit', params: { vendorId: vendor.id } }">
                    {{ vendor.name }}
                </CustomRouterLink>
                <div class="">{{ vendor.cookies_count ?? '0' }}</div>
            </div>
        </div>
    </div>
</template>