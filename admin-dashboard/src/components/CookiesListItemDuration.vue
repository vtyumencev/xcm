<script setup lang="ts">

defineProps<{
    modelValue: CookieDuration
}>();

defineEmits<{
    updateValue: []
}>();

</script>

<template>
    <div class="flex">
        <!--                    <input type="hidden" v-model="item.duration" />-->
        <select class="basis-1 flex-grow max-w-none" v-model="modelValue.period">
            <option value="session">Session</option>
            <option value="minutes">Minutes</option>
            <option value="hours">Hours</option>
            <option value="days">Days</option>
            <option value="months">Months</option>
            <option value="years">Years</option>
        </select>
        <input
            v-if="modelValue.period !== 'session'"
            class="basis-1 flex-grow"
            type="number"
            v-model="modelValue.value"
            min="1"
            max="9999" />
    </div>
</template>