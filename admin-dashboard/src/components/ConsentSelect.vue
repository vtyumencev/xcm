<script setup lang="ts">

import {onMounted, onUnmounted, ref} from "vue";
import TomSelect from "tom-select";
import 'tom-select/dist/scss/tom-select.scss';

const props = defineProps<{
    modelValue?: string,
}>();

const select = ref();
const instance = ref<TomSelect>();

const options = [
    { id: 'ad_storage', title: 'ad_storage' },
    { id: 'ad_user_data', title: 'ad_user_data' },
    { id: 'ad_personalization', title: 'ad_personalization' },
    { id: 'analytics_storage', title: 'analytics_storage' },
    { id: 'functionality_storage', title: 'functionality_storage' },
    { id: 'personalization_storage', title: 'personalization_storage' },
    { id: 'security_storage', title: 'security_storage' },
];

onMounted(() => {

    const selected = props.modelValue ? props.modelValue?.split(',') ?? [] : [];

    const hasCustomOption = !options.find((optionValue) => {
        selected.find((selValue) => {
            return optionValue.id === selValue
        })
    });

    if (selected.length > 0 && hasCustomOption) {
        for (const value of selected) {
            options.push({
                id: value,
                title: value
            });
        }
    }

    instance.value = new TomSelect(select.value,{
        valueField: 'id',
        labelField: 'title',
        searchField: ['title'],
        persist: false,
        createOnBlur: true,
        create: true,
        items: props.modelValue?.split(',') ?? [],
        options: options
    });
});

onUnmounted(() => {
    instance.value?.destroy();
});

</script>

<template>
    <input ref="select" class="w-full" autocomplete="off" name="consent_types">
</template>