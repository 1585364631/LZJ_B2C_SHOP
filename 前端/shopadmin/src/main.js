import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import VueCookies from 'vue-cookies'
import axios from 'axios'
import ElementPlus from 'element-plus'
import * as ElIcon from '@element-plus/icons-vue'
import 'element-plus/dist/index.css'
import urlbase from './function/urlbase'



const app = createApp(App)
for (let iconName in ElIcon) {
    app.component(iconName, ElIcon[iconName])
}

app.config.globalProperties.$cookies = VueCookies;
app.config.globalProperties.$axios = axios
app.config.globalProperties.$url = urlbase
app.use(router)
app.use(ElementPlus)
app.mount('#app')
router.afterEach(to => {
    if (to.meta.title) {
        document.title = to.meta.title;
    }
})
