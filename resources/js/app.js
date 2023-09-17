import {
    createSSRApp,
    h
} from 'vue';
import {
    createInertiaApp
} from '@inertiajs/vue3';
import {
    resolvePageComponent
} from 'laravel-vite-plugin/inertia-helpers';
import PrimeVue from 'primevue/config';
import "primevue/resources/themes/lara-light-indigo/theme.css";
import "primeflex/primeflex.scss";
import 'primeicons/primeicons.css';
import ToastService from 'primevue/toastservice';

createInertiaApp({
    title: title => `${title} - Link me`,
    resolve: (name) => {
        const page = resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        );
        return page;
    },
    setup({
        el,
        App,
        props,
        plugin
    }) {
        return createSSRApp({
                render: () => h(App, props)
            })
            .use(plugin)
            .use(PrimeVue)
            .use(ToastService)
            .mount(el)
    },
});
