import { defineConfig } from 'vite';

// https://vitejs.dev/config/
export default defineConfig({
  build: {
    // rollupOptions: {
    //   input: './src/main.js',
    //   output: {
    //     entryFileNames: `[name].js`,
    //     chunkFileNames: `[name].js`,
    //     assetFileNames: `[name].[ext]`
    //   }
    // },
    lib: {
      entry: './src/main.ts',
      fileName: () => 'scripts.js',
      name: 'xenioPrivacy',
      formats: ['iife'],
    },
  },
  plugins: [

  ]
})
