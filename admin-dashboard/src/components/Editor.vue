<script setup lang="ts">

import {onMounted, ref} from "vue";
import Quill from "quill";

const props = defineProps<{
    modelValue: string
}>();

const value = props.modelValue;

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void
}>();

const quill = ref();
const fieldEl = ref();

onMounted(() => {
    quill.value = new Quill(fieldEl.value, {
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'link'],
            ],
        },
        placeholder: 'Description...',
        theme: 'snow',
    });

    quill.value.on('text-change', () => {
        emit('update:modelValue', quill.value.getSemanticHTML())
    });

});

</script>

<template>
    <div ref="fieldEl" v-html="value"></div>
</template>

<style scoped>

</style>