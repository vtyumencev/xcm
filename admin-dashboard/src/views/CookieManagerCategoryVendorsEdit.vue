<script setup lang="ts">
import apiFetch from '@wordpress/api-fetch';
import {useSource} from "@/composables/useSource";
import ProcessingButton from "@/components/ProcessingButton.vue";
import PageHeader from "@/components/PageHeader.vue";
import {ref} from "vue";
import ProcessingFooter from "@/components/ProcessingFooter.vue";
import {useRouter} from "vue-router";
import CookiesList from "@/components/CookiesList.vue";
import ProviderSelect from "@/components/ProviderSelect.vue";

const props = defineProps<{
    cookieCategory: CookieCategory,
    vendorId: string
}>();

const processing = ref(false);
const deleteProcessing = ref(false);
const error = ref<WPRestError>();
const router = useRouter();

const onSourceReady = (data: CookieVendor) => {
    if (! Array.isArray(data.cookies)) {
        data.cookies = [];
    }
}

const { data: vendor } = useSource<CookieVendor>(
    () => `vendors/${props.vendorId}`,
    'manager-category-vendors-edit',
    'Vendor',
    {
        onReady: onSourceReady,
    }
);

const save = async (e: Event) => {
    const formEl = e.target as HTMLFormElement;

    const body = new FormData(formEl);

    body.append('cookies', JSON.stringify(vendor.value?.cookies));

    processing.value = true;
    error.value = undefined;

    await apiFetch<{
        id: string
    }>({
        path: `vendors/${props.vendorId}`,
        method: 'POST',
        body: body,
    }).catch(e => {
        error.value = e;
    });

    processing.value = false;
}

const deleteEntry = async () => {

    deleteProcessing.value = true;
    error.value = undefined;

    await apiFetch<{
        id: string
    }>({
        path: `vendors/${props.vendorId}`,
        method: 'DELETE',
    }).then(e => {
        router.push({
            name: 'manager-category-vendors-index',
        });
    }).catch(e => {
        return error.value = e;
    }).finally(() => {
        deleteProcessing.value = false;
    });

}

</script>

<template>
    <div v-if="vendor">
        <PageHeader>
            Vendor {{ vendor.name }}
        </PageHeader>
        <form class="" @submit.prevent="save">
            <input type="hidden" name="category_id" :value="cookieCategory.id" />
            <div class="space-y-5">
                <div>
                    <div class="">Vendor's name</div>
                    <input class="w-full" type="text" name="name" required v-model="vendor.name">
                    <div class="text-red-600">{{ error?.data?.params?.name }}</div>
                </div>
                <div>
                    <div class="">Vendor's privacy webpage</div>
                    <input class="w-full" type="text" name="link" v-model="vendor.link">
                </div>
                <div>
                    <div class="">Description</div>
                    <textarea class="w-full" type="text" name="description" rows="5" v-model="vendor.description"></textarea>
                </div>
                <div>
                    <div class="">Provider of content (embed name in gutenberg blocks)</div>
                    <ProviderSelect v-model="vendor.provider" />
                </div>
                <div>
                    <div class="">Cookies</div>
                    <div class="p-2 border border-solid border-gray-400 rounded-md">
                        <CookiesList :cookies="vendor.cookies" />
                    </div>
                </div>
                <ProcessingFooter :error="error">
                    <div class="flex justify-between">
                        <ProcessingButton :processing="processing">
                            Save
                        </ProcessingButton>
                        <ProcessingButton :processing="deleteProcessing" appearance="delete" type="button" @click="deleteEntry">
                            Delete
                        </ProcessingButton>
                    </div>
                </ProcessingFooter>
            </div>
        </form>
    </div>
</template>