<script setup lang="ts">
import {type RouteLocationMatched, RouterView, useRoute} from 'vue-router'
import CustomRouterLink from "@/components/CustomRouterLink.vue";

import apiFetch from '@wordpress/api-fetch';
import {computed, onMounted, ref, toRef, unref} from "vue";
import {createLocaleMiddleware} from "@/helpers/middlewares/createLocaleMiddleware";
import LanguageSelector from "@/components/LanguageSelector.vue";

interface LanguageSettings {
    userLocale: string,
    current: string,
    list: string[]
}

const route = useRoute();

const isReady = ref(false);

const languages = ref<LanguageSettings>({
    userLocale: 'en',
    current: 'en',
    list: []
});

onMounted(async () => {

    languages.value = await apiFetch<LanguageSettings>({
        path: `languages`,
        method: 'GET',
    });

    isReady.value = true;

    apiFetch.use(createLocaleMiddleware(toRef(() => languages.value?.current)));
});

const breadcrumbs = computed(() => {
    const list = [] as RouteLocationMatched[];

    for (const r of route.matched) {

        if (! unref(r.meta.bc)) {
            break;
        }

        list.push(r);
    }

    return list;
});


</script>

<template>
    <div class="p-5 max-w-6xl mx-auto mt-5 bg-white">
        <header class="pb-3 flex justify-between items-center text-black border-0 border-b border-solid border-gray-300">
            <div class="flex space-x-5 text-lg">
                <div class="flex space-x-5">
                    <CustomRouterLink :to="{ name: 'manager' }">Categories</CustomRouterLink>
                    <CustomRouterLink :to="{ name: 'content' }">Customization</CustomRouterLink>
                    <CustomRouterLink :to="{ name: 'settings' }">Settings</CustomRouterLink>
                </div>
            </div>
            <Transition>
                <div v-if="languages.list.length > 0 && route.name !== 'settings'">
                    <label class="flex items-center">
                        Edit content in:
                        <LanguageSelector class="ml-2" v-model="languages.current" :language-settings="languages" />
                    </label>
                </div>
            </Transition>
        </header>
        <div v-if="isReady" class="mt-5">
            <div class="flex space-x-2 mb-2">
                <template v-for="(route, key) of breadcrumbs">
                    <RouterLink :to="{ name: route.name }">
                        {{ unref(route.meta.bc) }}
                    </RouterLink>
                    <div v-if="key !== breadcrumbs.length - 1" class="">-</div>
                </template>
            </div>
            <RouterView :key="languages.current" />
        </div>
    </div>
</template>

<style scoped>
.v-enter-active,
.v-leave-active {
    transition: all 0.2s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
    transform: translateY(3px);
}
</style>
