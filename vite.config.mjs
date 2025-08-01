import { defineConfig } from 'vite';

export default defineConfig({
    build: {
        outDir: 'dist',
        emptyOutDir: true,
        rollupOptions: {
            input: 'src/getterms.js',
            output: {
                entryFileNames: 'getterms.bundle.js',
                format: 'iife',
            },
        },
    },
});