import { createApp } from 'vue'
import router from './router'
import App from './App.vue'
import VueCookies from 'vue-cookies'


const app = createApp(App)
app.config.globalProperties.$cookies = VueCookies;
app.use(router)
app.mount('#lzj_app')
router.afterEach(to => {
    if (to.meta.title) {
        document.title = to.meta.title;
    }
    document.body.scrollTop = 0
    document.documentElement.scrollTop = 0
    document.getElementById("lzj_app").scrollTop = 0
    window.pageYOffset = 0
})