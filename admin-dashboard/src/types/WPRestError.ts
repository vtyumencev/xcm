type WPRestError = {
    code: string,
    data: {
        params?: {
            [key: string]: string
        },
    },
    message: string,
}