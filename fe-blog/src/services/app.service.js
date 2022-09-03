import $http from '@/plugins/axios';

const AppService = {
    getMenu(){
        return $http.get('/menu')
    }
}

export default AppService