import AppService from "@/services/app.service"
export const actions = {
    updateUser({commit}, payload) {
        commit('updateUser', payload)
    },
    getCurrentUser({commit}) {
        return AppService.getCurrentUser()
        .then((res) => {
            if(res.status === 200){
                let data = {}
                data.roles = res.data.roles
                data.id = res.data.id
                data.username = res.data.username
                data.email = res.data.email
                data.phone = res.data.phone
                data.name = res.data.name
                data.avatar = res.data.avatar
                commit('updateUser', data)
            }
        })
        .catch(() => {
            localStorage.removeItem('myblog_token')
        })
    },
    updateCategory({commit}, payload) {
        commit('updateCategory', payload)
    },
}