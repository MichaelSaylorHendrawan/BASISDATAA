import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap'
import('@/Assets/css/main.css')
import('@fortawesome/fontawesome-free/css/all.css');
import { ZiggyVue } from 'ziggy-js';
const pages = import.meta.glob('./Pages/**/*.vue')

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        return pages[`./Pages/${name}.vue`]
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        app.use(plugin)
        app.use(ZiggyVue);
        app.mount(el)
    },
})
