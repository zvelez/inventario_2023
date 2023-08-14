require('./bootstrap');

import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap";
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faChevronDown, faPen, faUserCheck, faUserLock } from '@fortawesome/free-solid-svg-icons'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

library.add(faChevronDown)
library.add(faPen)
library.add(faUserCheck)
library.add(faUserLock)

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .mixin({ methods: { route } })
            .component('font-awesome-icon', FontAwesomeIcon)
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });
