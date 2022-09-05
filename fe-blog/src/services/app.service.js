import $http from '@/plugins/axios';

const AppService = {
    getMenu(){
        return $http.get('/menu')
    },
    getMainBlog(id){
        return $http.get(`/blog/${id}`)
    },
    getSideBarAdmin(){
        return $http.get('/sidebar-admin')
    },
    getBlogWithPagination(page){
        return $http.get(`/blog/list-blog?pageNumber=${page}`)
    },
    login(data){
        return $http.post('/login', data)
    },
    logout(){
        return $http.post('/logout')
    },
    getBlogPreview(id){
        return $http.get(`/blog/preview/${id}`)
    },
    acceptBlog(id){
        let form = new FormData()
        form.append('id', id)
        return $http.post('/blog/accept', form)
    },
    cancelBlog(id){
        return $http.delete('/blog/cancel/' + id)
    },
    getListUnaccept(page){
        return $http.get('/blog/list-blog-unaccept?pageNumber=' + page)
    },
    checkAbility(ability){
        return $http.get('/user/check-ability/' + ability)
    }
}

export default AppService