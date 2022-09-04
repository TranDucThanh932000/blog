import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter)

export const routes = [
    {
        path: '/',
        name: 'homepage',
        component: () => import('@/views/Homepage.vue')
    },
    {
        path: '/:id',
        name: 'detail-blog',
        component: () => import('@/views/DetailBlog.vue')
    },
    {
        path: '/admin',
        name: 'admin',
        component: () => import('@/views/Dashboard.vue'),
        children: [
            {
                path: 'list-blog',
                name: 'list-blog',
                component: () => import('@/views/ListBlog.vue') 
            },
            {
                path: 'create-blog',
                name: 'create-blog',
                component: () => import('@/views/CreateUpdateBlog.vue')
            },
            {
                path: 'update-blog',
                name: 'update-blog',
                component: () => import('@/views/CreateUpdateBlog.vue')
            }
        ]
    }
]

const router = new VueRouter({
    mode: 'history',
    base: process.env.BASE_URL,
    routes
})

export default router