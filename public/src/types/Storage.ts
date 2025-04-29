import UserConfig from "./UserConfig";
import ConsentTypes from "./ConsentTypes";
import ProviderForBlocking from "./ProviderForBlocking";
import Events from "./Events";
import {Unsubscribe} from "nanoevents";

export default interface Storage {
    prolongConfig: () => void,
    getUserConfig: () => UserConfig,
    getConsentConsentsTypes: () => ConsentTypes,
    isProviderBlocked: (providerName: string) => ProviderForBlocking|false,
    emit<K extends keyof Events>(
        this: this,
        event: K,
        ...args: Parameters<Events[K]>
    ): void,
    on<K extends keyof Events>(this: this, event: K, cb: Events[K]): Unsubscribe
}