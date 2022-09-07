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
    },
    getCurrentUser(){
        return $http.get('/user/current-user')
    },
    getSubMenu(){
        return $http.get('/menu/menu-child')
    },
    saveBlog(data){
        return $http.post('/blog/store', data)
    },
    getBlogBySearch(txtSearch, page){
        return $http.get('/blog/search', {
            params: {
                txtSearch,
                page
            }
        })
    },
    getBlogByCategorySearch(txtSearch, category, page){
        if(!category){
            category = ''
        }
        return $http.get('/blog/search-category', {
            params: {
                txtSearch,
                category,
                page
            }
        })
    },
    getMenuById(id){
        return $http.get('/menu/' + id)
    }
}

export default AppService