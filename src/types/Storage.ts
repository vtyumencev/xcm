import UserConfig from "./UserConfig";
import ContestTypes from "./ContestTypes";
import ProviderForBlocking from "./ProviderForBlocking";
import Events from "./Events";
import {Unsubscribe} from "nanoevents";

export default interface Storage {
    getUserConfig: () => UserConfig,
    getContestContestsTypes: () => ContestTypes,
    isProviderBlocked: (providerName: string) => ProviderForBlocking|false,
    emit<K extends keyof Events>(
        this: this,
        event: K,
        ...args: Parameters<Events[K]>
    ): void,
    on<K extends keyof Events>(this: this, event: K, cb: Events[K]): Unsubscribe
}