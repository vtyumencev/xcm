import { addQueryArgs } from '@wordpress/url';
import {type Ref, unref} from "vue";

export function createLocaleMiddleware(locale: Ref) {
    return (options: any, next: any) => {

        options.path = addQueryArgs(options.path, {
            'locale': unref(locale)
        })

        return next(options);
    };
}