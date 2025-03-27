import { fileURLToPath, URL } from 'node:url';
import replace from '@rollup/plugin-replace';

import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [
    replace({
      'process.env.NODE_ENV': JSON.stringify('production')
    }),
    vue(),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    }
  },
  build: {
    lib: {
      entry: "src/main.ts",
      formats: ["iife"],
      fileName: () => 'scripts.js',
      name: "App",
    },
  },
})
