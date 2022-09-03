import axios from 'axios'
import Vue from 'vue'

// axios.defaults.headers.common["Authorization"] = "Bearer " + localStorage.getItem("school_token");
const $http = axios.create({
    baseURL: `${process.env.VUE_APP_BASE_API_URL}`
})

window.$http = $http
Vue.prototype.$http = $http
export default $http