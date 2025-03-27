export default interface UserConfig {
    consent_version: string,
    plugin_version: string,
    consent: Record<number, boolean>
}