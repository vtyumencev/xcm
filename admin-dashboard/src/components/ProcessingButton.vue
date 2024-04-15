<script setup lang="ts">

type ButtonStyle = 'save' | 'delete';
type ButtonType = 'submit' | 'button';

defineProps<{
    processing?: boolean,
    appearance?: ButtonStyle,
    type?: ButtonType
}>();

</script>

<template>
    <button
        :type="type"
        :class="{
            'bg-opacity-50 pointer-events-none': processing,
            'bg-blue-600 text-white': !appearance,
            'outline outline-1 bg-transparent text-red-700 outline-red-700': appearance === 'delete'
        }"
        class="border-none rounded-md cursor-pointer px-5 py-2 font-bold">
        <span v-if="processing">
            Processing...
        </span>
        <span v-else>
            <slot />
        </span>
    </button>
</template>
