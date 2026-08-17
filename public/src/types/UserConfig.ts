export default interface UserConfig {
    content_version: string,
    plugin_version: string,
    consent: Record<number, boolean>
}