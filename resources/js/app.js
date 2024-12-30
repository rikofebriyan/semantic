import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { AgGridVue } from 'ag-grid-vue3'; // Import AgGridVue
import { AllCommunityModule, ModuleRegistry } from 'ag-grid-community'; // Import AllCommunityModule and ModuleRegistry

// Register all Community features
ModuleRegistry.registerModules([AllCommunityModule]);

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        app.component('AgGridVue', AgGridVue); // Register AgGridVue component
        app.use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
