<script setup lang="ts">

import apiFetch from '@wordpress/api-fetch';
import ProcessingButton from "@/components/ProcessingButton.vue";
import {ref} from "vue";
import PageHeader from "@/components/PageHeader.vue";
import {useRouter} from "vue-router";
import ProcessingFooter from "@/components/ProcessingFooter.vue";

defineProps<{
    cookieCategory: CookieCategory
}>();

const router = useRouter();
const processing = ref(false);
const error = ref<WPRestError>();

const create = async (e: Event) => {
    const formEl = e.target as HTMLFormElement;

    error.value = undefined;
    processing.value = true;

    const data = await apiFetch<{
        id: string
    }>({
        path: `vendors`,
        method: 'POST',
        body: new FormData(formEl),
    }).catch(e => {
        error.value = e
    });

    if (data) {
        await router.push({
            name: 'manager-category-vendors-edit',
            params: { vendorId: data.id }
        })
    }

    processing.value = false;
}

</script>

<template>
    <div class="">
        <PageHeader>
            New Vendor for {{ cookieCategory.name }}
        </PageHeader>
        <form @submit.prevent="create">
            <input type="hidden" name="category_id" :value="cookieCategory.id" />
            <div class="space-y-5">
                <div>
                    <div class="">Vendor's name (e.g.: YouTube, Instagram, Google Analytics, etc.)</div>
                    <input class="w-full" type="text" name="name">
                    <div class="text-red-600">{{ error?.data?.params?.name }}</div>
                </div>
                <ProcessingFooter :error="error">
                    <ProcessingButton :processing="processing">
                        Create
                    </ProcessingButton>
                </ProcessingFooter>
            </div>
        </form>
    </div>
</template>