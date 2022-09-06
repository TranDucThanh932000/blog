import Vue from 'vue'
import App from './App.vue'
import vuetify from './plugins/vuetify'
import { store } from './stores'
import router from './routers'
import CKEditor from 'ckeditor4-vue'

Vue.config.productionTip = false
Vue.use(CKEditor)

new Vue({
  vuetify,
  router,
  store,
  render: h => h(App)
}).$mount('#app')
