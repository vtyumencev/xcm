<script setup lang="ts">

import apiFetch from '@wordpress/api-fetch';
import {onMounted, ref} from "vue";
import ProcessingButton from "@/components/ProcessingButton.vue";
import PageHeader from "@/components/PageHeader.vue";
import ContestSelect from "@/components/ContestSelect.vue";
import {useRouter} from "vue-router";
import Quill from "quill";
import "quill/dist/quill.snow.css";

const router = useRouter();

const emits = defineEmits<{
    updated: []
}>();

const props = defineProps<{
    cookieCategory: CookieCategory
}>();

const processing = ref(false);
const deleteProcessing = ref(false);
const error = ref<WPRestError|null>();

const descEl = ref();
const quill = ref();

onMounted(() => {
    quill.value = new Quill(descEl.value, {
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'link'],
            ],
        },
        placeholder: 'Description...',
        theme: 'snow',
    });

});

onMounted(() => {
    //TODO: (?) destroy the editor
})

const saveForm = async (e: Event) => {

    error.value = null;

    const formData = new FormData(e.target as HTMLFormElement);
    formData.append('description', quill.value.getSemanticHTML())

    processing.value = true;
    await apiFetch({
        path: `categories/${props.cookieCategory.id}`,
        method: 'POST',
        body: formData
    }).then(() => {
        emits('updated');
    }).catch((e) => {
        return error.value = e;
    });

    processing.value = false;
}

const deleteEntry = async () => {

    deleteProcessing.value = true;

    await apiFetch<{
        id: string
    }>({
        path: `categories/${props.cookieCategory.id}`,
        method: 'DELETE',
    }).then(e => {
        router.push({
            name: 'manager',
        });
    }).catch(e => {
    }).finally(() => {
        deleteProcessing.value = false;
    });

}

</script>

<template>
    <div>
        <PageHeader>
            Category {{ cookieCategory.name }}
        </PageHeader>
        <form class="space-y-5" @submit.prevent="saveForm">
            <div>
                <div class="">Name</div>
                <input v-model="cookieCategory.name" class="w-full" type="text" name="name">
                <div class="text-red-600">{{ error?.data?.params?.name }}</div>
            </div>
            <div>
                <div class="">Description</div>
                <div ref="descEl" v-html="cookieCategory.description"></div>
            </div>
            <div>
                <label>
                    <input v-model="cookieCategory.necessary" type="checkbox" name="necessary">
                    Always active without user contest
                </label>
            </div>
            <div>
                <div class="text-">Contest types</div>
                <ContestSelect v-model="cookieCategory.contest_types" />
            </div>
            <div class="flex justify-between">
                <div class="">
                    <ProcessingButton :processing="processing">
                        Save
                    </ProcessingButton>
                    <RouterLink
                        :to="{ name: 'manager-category-vendors' }"
                        class="
                        ml-2
                        rounded-md
                        cursor-pointer
                        px-5
                        py-2
                        font-bold
                        border
                        border-solid
                        border-blue-600
                        text-blue-600
                        no-underline
                        bg-transparent">
                        Edit Vendors
                    </RouterLink>
                </div>
                <ProcessingButton :processing="deleteProcessing" appearance="delete" type="button" @click="deleteEntry">
                    Delete
                </ProcessingButton>
            </div>
        </form>
    </div>
</template>