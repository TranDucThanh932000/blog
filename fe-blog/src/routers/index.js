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
                path: 'accept-blog',
                name: 'accept-blog',
                component: () => import('@/views/AcceptBlog.vue') 
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
            },
            {
                path: 'preview/:id',
                name: 'preview',
                component: () => import('@/views/PreviewDetailBlog.vue')
            }
        ]
    }
]

const router = new VueRouter({
    mode: 'history',
    base: process.env.BASE_URL,
    routes
})


router.beforeEach(async (to, from ,next) => {
    axios.defaults.headers.common["Authorization"] = "Bearer " + localStorage.getItem('myblog_token')
    // axios({
    //     method: 'get',
    //     baseURL: `${process.env.VUE_APP_BASE_API_URL}`,
    //     url :'/checkAuthToken'
    // })
    // .then(async (res) => {

    // })
    if(localStorage.getItem('myblog_token') && to.name === 'login'){
        next({ name: 'homepage' })
    }else{
        next()
    }
})

export default router