import axios from 'axios'
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
        path: '/login',
        name: 'login',
        component: () => import('@/views/Login.vue')
    },
    {
        path: '/search/:txtSearch',
        name: 'search',
        component: () => import('@/views/SearchHomePage.vue')
    },
    {
        path: '/create-blog',
        name: 'create-blog',
        component: () => import('@/views/CreateUpdateBlog.vue')
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
                path: 'accept-blog',
                name: 'accept-blog',
                component: () => import('@/views/AcceptBlog.vue') 
            },
            {
                path: 'update-blog',
                name: 'update-blog',
                component: () => import('@/views/CreateUpdateBlog.vue')
            },
            {
                path: 'preview/:id',
                name: 'preview',
                component: () => import('@/views/PreviewDetailBlog.vue')
            }
        ]
    },
    {
        path: '/blog/:id',
        name: 'detail-blog',
        component: () => import('@/views/DetailBlog.vue')
    },

]

const namePathAdmin = [
    'admin',
    'list-blog',
    'accept-blog',
    'preview'
]

const router = new VueRouter({
    mode: 'history',
    base: process.env.BASE_URL,
    routes
})

router.beforeEach(async (to, from ,next) => {
    axios.defaults.headers.common["Authorization"] = "Bearer " + localStorage.getItem('myblog_token')
    if(!router.app.$store.state.user && localStorage.getItem('myblog_token')){
        await router.app.$store.dispatch("getCurrentUser");
    }
    if(router.app.$store.state.user && to.name === 'login'){
        next({ name: 'homepage' })
    }else{
        if(router.app.$store.state.user){
            if(router.app.$store.state.user.roles.length === 3 && namePathAdmin.includes(to.name)){
                next({ name: 'homepage'})
            }else{
                next()
            }
        }else{
            if(namePathAdmin.includes(to.name)){
                next({ name: 'homepage'})
            }else{
                next()
            }
        }
    }
})

export default router