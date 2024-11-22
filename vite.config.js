import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'; 
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js'],
            refresh: true,
        }),
        vue({ 
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: { 
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
            '~bootstrap' :path.resolve(__dirname, 'node_modules/bootstrap'),
        },
    },
    server: {
        host: '0.0.0.0',
        strictPort: true,
        port: 5174,
        hmr: {
            host: 'localhost',
            clientPort: 5174,
            protocol: 'ws'
        },
        watch: {
		    // https://vitejs.dev/config/server-options.html#server-watch
            usePolling: true
        }
    }
});
