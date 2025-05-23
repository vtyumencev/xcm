type CookieCategory = {
    id: number,
    name: string,
    name_default?: string,
    description: string,
    vendors_count?: number,
    consent_types?: string,
    // WordPress get_results casts booleans into string
    necessary: boolean|string,
}