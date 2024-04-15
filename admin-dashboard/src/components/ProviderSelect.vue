<script setup lang="ts">

import {onMounted, onUnmounted, ref} from "vue";
import TomSelect from "tom-select";
import 'tom-select/dist/scss/tom-select.scss';

const props = defineProps<{
    modelValue?: string,
}>();

const embedSelect = ref();
const instance = ref<TomSelect>();

const options = [
    { id: 'twitter', title: 'Twitter' },
    { id: 'youtube', title: 'YouTube' },
    { id: 'wordpress', title: 'WordPress' },
    { id: 'soundcloud', title: 'SoundCloud' },
    { id: 'spotify', title: 'Spotify' },
    { id: 'flickr', title: 'Flickr' },
    { id: 'vimeo', title: 'Vimeo' },
    { id: 'animoto', title: 'Animoto' },
    { id: 'cloudup', title: 'CloudUp' },
    { id: 'crowdsignal', title: 'Crowdsignal' },
    { id: 'dailymotion', title: 'Dailymotion' },
    { id: 'imgur', title: 'Imgur' },
    { id: 'issuu', title: 'Issuu' },
    { id: 'kickstarter', title: 'Kickstarter' },
    { id: 'mixcloud', title: 'Mixcloud' },
    { id: 'pocketcasts', title: 'Pocket Casts' },
    { id: 'reddit', title: 'Reddit' },
    { id: 'reverbnation', title: 'ReverbNation' },
    { id: 'screencast', title: 'Screencast' },
    { id: 'scribd', title: 'Scribd' },
    { id: 'slideshare', title: 'Slideshare' },
    { id: 'smugmug', title: 'SmugMug' },
    { id: 'speakerdeck', title: 'Speaker Deck' },
    { id: 'tiktok', title: 'TikTok' },
    { id: 'ted', title: 'TED' },
    { id: 'tumblr', title: 'Tumblr' },
    { id: 'videopress', title: 'VideoPress' },
    { id: 'wordpress_tv', title: 'WordPress.tv' },
    { id: 'amazon_kindle', title: 'Amazon Kindle' },
    { id: 'pinterest', title: 'Pinterest' },
    { id: 'wolfram', title: 'Wolfram' },
    { id: 'facebook', title: 'Facebook' },
    //{ id: 'instagram', title: 'Instagram' }
];

onMounted(() => {
    const hasCustomOption = !options.find((value) => value.id === props.modelValue);

    if (props.modelValue && hasCustomOption) {
        options.push({
            id: props.modelValue,
            title: props.modelValue
        });
    }

    instance.value = new TomSelect(embedSelect.value,{
        valueField: 'id',
        labelField: 'title',
        searchField: ['title'],
        persist: false,
        createOnBlur: true,
        create: true,
        items: [props.modelValue],
        options: options
    });
});

onUnmounted(() => {
    instance.value?.destroy();
});

</script>

<template>
    <select ref="embedSelect" class="w-full" autocomplete="off" name="provider"></select>
</template>