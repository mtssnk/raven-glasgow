import { defineConfig } from 'vite'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
  plugins: [tailwindcss()],
  build: {
    outDir: 'assets/dist',
    emptyOutDir: true,
    rollupOptions: {
      input: 'assets/css/main.css',
      output: {
        assetFileNames: '[name][extname]',
      },
    },
  },
})
