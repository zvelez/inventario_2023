require('./bootstrap');

import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap";
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { VueReCaptcha } from 'vue-recaptcha-v3'
import { faChevronDown, faClipboardList, faEllipsisVertical, faEye, faMagnifyingGlass, faPlus, faPen, faPrint, faSpinner, faTruckArrowRight, faTruckField, 
  faTruckRampBox, faUserCheck, faUserLock } from '@fortawesome/free-solid-svg-icons'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

library.add(faChevronDown)
library.add(faClipboardList)
library.add(faEye)
library.add(faEllipsisVertical)
library.add(faMagnifyingGlass)
library.add(faPlus)
library.add(faPen)
library.add(faPrint)
library.add(faSpinner)
library.add(faTruckArrowRight)
library.add(faTruckField)
library.add(faTruckRampBox)
library.add(faUserCheck)
library.add(faUserLock)

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) => require(`./Pages/${name}.vue`),
  setup({ el, app, props, plugin }) {
    return createApp({ render: () => h(app, props) })
      .use(plugin)
      .use(VueReCaptcha, {
        siteKey: '6LfbKQooAAAAAKQIRQxwG1AmO6q3hnmHdTHFJuel',
        loaderOptions: {
          useRecaptchaNet: true
        }
      })
      .mixin({ methods: { route } })
      .component('font-awesome-icon', FontAwesomeIcon)
      .mount(el);
  },
});

InertiaProgress.init({ color: '#4B5563' });
