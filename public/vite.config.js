import { defineConfig } from 'vite';
import { dirname, resolve } from 'node:path'


export default defineConfig({
    build: {
        lib: {
            entry: resolve(import.meta.dirname, 'src/main.js'),
            name: 'public',
            fileName: 'public',
        },
    }
});
