// Importar las configuraciones
require('./bootstrap');

// Asignar vue al objeto de ventana para que este disponible de manera global
window.Vue = require('vue').default;

// Importar librerías y ficheros necesarios
import store from './store'
import router from './router'
import HighchartsVue from 'highcharts-vue'
import VueMaterial from 'vue-material'
import { MdButton, MdContent, MdTabs } from 'vue-material/dist/components'
import 'vue-material/dist/vue-material.min.css'
import 'vue-material/dist/theme/default.css'
import VuePaginate from 'vue-paginate'

// Definir los componentes globales
Vue.component('header-component', require('./components/header/headerComponent.vue').default);
Vue.component('all-post-component', require('./pages/post/allPostComponent').default);
Vue.component('an-post-component', require('./pages/post/anPostComponent').default);

// Components de las librerias
Vue.use(HighchartsVue)
Vue.use(VueMaterial)
Vue.use(MdButton)
Vue.use(MdContent)
Vue.use(MdTabs)
Vue.use(VuePaginate)

// Instancia de vue
const app = new Vue({
    el: '#app',
    router,
    store
});