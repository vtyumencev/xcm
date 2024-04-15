<script setup lang="ts">
import apiFetch from '@wordpress/api-fetch';
import {ref, unref} from "vue";
import PageHeader from "@/components/PageHeader.vue";
import ProcessingButton from "@/components/ProcessingButton.vue";
import ProcessingFooter from "@/components/ProcessingFooter.vue";
import {useRouter} from "vue-router";

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
        path: `categories`,
        method: 'POST',
        body: new FormData(formEl),
    }).catch(e => {
        error.value = e
    });

    if (data) {
        await router.push({
            name: 'manager-category',
            params: { categoryId: data.id }
        })
    }

    processing.value = false;
}

</script>

<template>
    <div>
        <PageHeader>
            {{ unref($route.meta.title) }}
        </PageHeader>
        <form @submit.prevent="create">
            <div class="space-y-5">
                <div>
                    <div class="">Category's name</div>
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