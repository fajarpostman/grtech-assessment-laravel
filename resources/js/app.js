import '../css/app.css'
import './bootstrap'

import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { createApp, h } from 'vue'
import { ZiggyVue } from '../../vendor/tightenco/ziggy'
import Antd from 'ant-design-vue'
import 'ant-design-vue/dist/reset.css'

const el = document.getElementById('app')

// createInertiaApp({
// resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
//   setup({ el, App, props, plugin }) {
//     createApp({ render: () => h(App, props) })
//       .use(plugin)
//       .use(Antd)
//       .mount(el)
//   },
// })

// InertiaProgress.init()

createInertiaApp({
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
        app.use(plugin)
        app.use(Antd)
        app.use(ZiggyVue)
        app.mount(el)
    },
    progress: {
        color: '#4B5563'
    },
})
