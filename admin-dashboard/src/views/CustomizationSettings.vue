<script setup lang="ts">

import {onMounted, ref} from "vue";
import apiFetch from '@wordpress/api-fetch';
import Editor from "@/components/Editor.vue";
import ProcessingButton from "@/components/ProcessingButton.vue";
import PageHeader from "@/components/PageHeader.vue";

interface CustomizationSettings {
    overview_title: string,
    overview_description: string,
}

const processing = ref(false);
const settings = ref<CustomizationSettings>();

onMounted(async () => {
    settings.value = await apiFetch<CustomizationSettings>({
        path: `customization`,
        method: 'GET',
    });
});

const save = () => {
    processing.value = true;

    apiFetch<CustomizationSettings>({
        path: `customization`,
        method: 'POST',
        data: settings.value
    }).finally(() => {
        processing.value = false;
    });

}

</script>

<template>
    <div v-if="settings" class="">
        <PageHeader>
            Customization Settings
        </PageHeader>
        <div>
            <div class="">Overview title</div>
            <input class="w-full" type="text" v-model="settings.overview_title">
        </div>
        <div class="mt-5">
            <div class="">Description</div>
            <Editor class="w-full" v-model="settings.overview_description" />
        </div>
        <div class="mt-5">
            <ProcessingButton :processing="processing" @click="save">
                Save
            </ProcessingButton>
        </div>
    </div>
</template>

<style scoped>

</style>