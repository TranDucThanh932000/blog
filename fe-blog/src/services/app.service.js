import $http from '@/plugins/axios';

const AppService = {
    getMenu(){
        return $http.get('/menu')
    },
    getMainBlog(id){
        return $http.get(`/blog/${id}`)
    }
}

export default AppService