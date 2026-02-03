<script setup lang="ts">

import PageHeader from "@/components/PageHeader.vue";
import LanguageSelector from "@/components/LanguageSelector.vue";
import apiFetch from '@wordpress/api-fetch';
import {onMounted, ref} from "vue";
import ProcessingButton from "@/components/ProcessingButton.vue";

interface Settings {
    is_active: boolean,
    reload_on_update: boolean,
    languages: {
        default: string,
        list: string[]
    },
}

const processing = ref(false);
const settings = ref<Settings>();

onMounted(async () => {
    settings.value = await apiFetch<Settings>({
        path: `settings`,
        method: 'GET',
    });
});

const save = () => {
    processing.value = true;

    apiFetch<Settings>({
        path: `settings`,
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
            Settings
        </PageHeader>
<!--        <div class="">-->
<!--            <div class="">Default language</div>-->
<!--            <LanguageSelector v-model="settings.languages.default" class="w-full" :language-settings="{-->
<!--                current: settings.languages.default,-->
<!--                list: settings.languages.list-->
<!--            }" />-->
<!--        </div>-->
        <div>
            <label>
                <input type="checkbox" v-model="settings.is_active" />
                Consent Manager is active
            </label>
        </div>
        <div>
            <label>
                <input type="checkbox" v-model="settings.reload_on_update" />
                Reload on consent update
            </label>
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