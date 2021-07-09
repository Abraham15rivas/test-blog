// Importaciones
import Vue from 'vue'
import VueRouter from 'vue-router'
import homeComponent from '../pages/home/homeComponent.vue'
import createPostComponent from '../pages/post/createPostComponent.vue'
import anPostComponent from '../pages/post/anPostComponent.vue'
import indexCommentComponent from '../pages/comment/indexCommentComponent.vue'
import indexAnalyticComponent from '../pages/analytic/indexAnalyticComponent.vue'

// asignar
Vue.use(VueRouter)

// rutas
const routes = [
    {
        path: '/',
        name: 'Root',
        component: homeComponent,
        props: true
    },
    {
        path: '/home',
        name: 'Home',
        component: homeComponent,
        props: true
    },
    {
        path: '/post',
        name: 'Post',
        component: createPostComponent,
        props: true
    },
    {
        path: '/post/:id',
        name: 'AnPost',
        component: anPostComponent,
        props: true
    },
    {
        path: '/comment',
        name: 'Comment',
        component: indexCommentComponent,
        props: true
    },
    {
        path: '/analytic',
        name: 'Analytics',
        component: indexAnalyticComponent,
        props: true
    }
]

// instancia
const router = new VueRouter({
    mode: 'history',
    base: process.env.BASE_URL,
    routes
})

// exportar objeto
export default router