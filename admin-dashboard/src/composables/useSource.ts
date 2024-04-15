import {ref, watchEffect, toValue, type Ref, onBeforeUnmount} from 'vue'
import apiFetch from '@wordpress/api-fetch';
import {useRoute} from "vue-router";

export function useSource<T extends EntryBase>(
    url: () => string,
    routeName: string,
    sourceName: string,
    options?: {
        onReady?: (data: T) => void
    }) {

    const data = ref<T|null>();
    const error = ref(null);

    const route = useRoute();
    const fetchData = async () => {
        data.value = null;
        error.value = null;

        data.value = await apiFetch<T>({
            path: toValue(url),
            method: 'GET',
        });

        const categoryRoute = route.matched.find((item) => item.name === routeName);

        if (categoryRoute) {
            const title = categoryRoute.meta.title as Ref<string>;
            const bc = categoryRoute.meta.bc as Ref<string>;
            title.value = bc.value = data.value?.name ?? 'Unnamed ' + sourceName;
        }

        if (options?.onReady) {
            options.onReady(data.value);
        }
    }

    onBeforeUnmount(() => {
        const categoryRoute = route.matched.find((item) => item.name === routeName);

        if (categoryRoute) {
            const title = categoryRoute.meta.title as Ref<string|null>;
            const bc = categoryRoute.meta.bc as Ref<string|null>;
            title.value = bc.value = null;
        }
    });

    const trigger = async () => {
        await fetchData();
    }

    watchEffect(async () => {
        await fetchData();
    })

    return { data, error, trigger }
}