<script setup lang="ts">
import {ref} from "vue";
import {useSortable} from "@vueuse/integrations/useSortable";
import CookiesListItemDuration from "@/components/CookiesListItemDuration.vue";

const cookiesListEl = ref<HTMLElement>();

const props = defineProps<{
    cookies: Cookie[]
}>();

const { option } = useSortable(cookiesListEl, props.cookies);
option('animation', 200);

const addCookie = () => {
    props.cookies.push({
        name: '',
        duration: {
            period: 'session',
            value: 0,
        }
    });
}

const deleteCookie = (item: Cookie, index: number) => {

    if (! Array.isArray(props.cookies)) {
        return;
    }

    props.cookies.splice(index, 1);
}

</script>

<template>
    <div v-show="cookies && cookies.length" class="mb-2">
        <div class="grid grid-cols-[2fr_1fr_60px] gap-2">
            <div class="font-bold">Name</div>
            <div class="font-bold">Duration</div>
            <div class=""></div>
        </div>
        <div class="" ref="cookiesListEl">
            <div
                class="cookie-item grid grid-cols-[2fr_1fr_60px] items-center gap-2 hover:bg-gray-200 py-1 px-2 -mx-2"
                v-for="(item, index) of cookies"
                :key="item.name">
                <div class="">
                    <input class="w-full" type="text" v-model="item.name" required />
                </div>
                <CookiesListItemDuration v-model="item.duration" />
                <div class="flex justify-end">
                    <button type="button" class="bg-transparent border-0 text-black cursor-pointer" @click="deleteCookie(item, index)">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <div>
        <button type="button" class="border bg-transparent border-solid border-gray-400 rounded-[4px] px-4 py-2 cursor-pointer" @click="addCookie">Add New</button>
    </div>
</template>