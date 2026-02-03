import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import '../css/app.css';
import { initializeTheme } from './composables/useAppearance';
import Alpine from "alpinejs";
import Chart from "chart.js/auto";

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

declare global {
  interface Window {
    Alpine: any;
    Chart: any;
  }
}

window.Alpine = Alpine;
window.Chart = Chart;
Alpine.start();

// Only boot Inertia when an Inertia root exists on the page.
if (document.querySelector('[data-page]')) {
    createInertiaApp({
        title: (title) => (title ? `${title} - ${appName}` : appName),
        resolve: (name) =>
            resolvePageComponent(
                `./pages/${name}.vue`,
                import.meta.glob<DefineComponent>('./pages/**/*.vue'),
            ),
        setup({ el, App, props, plugin }) {
            createApp({ render: () => h(App, props) })
                .use(plugin)
                .mount(el);
        },
        progress: {
            color: '#4B5563',
        },
    });
}
// This will set light / dark mode on page load...
initializeTheme();
