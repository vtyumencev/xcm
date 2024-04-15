type CookieVendor = {
    id: number,
    category_id: number,
    name: string,
    link: string,
    description?: string,
    script_domain?: string,
    cookies: Cookie[],
    cookies_count?: number,
    provider?: string
}