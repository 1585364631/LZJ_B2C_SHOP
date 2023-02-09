import { createRouter, createWebHashHistory } from "vue-router"
import lzj_shop_index_router from "../views/lzj_shop_index.vue"
import lzj_shop_home_router from "../views/lzj_shop_home.vue"
import lzj_shop_class_router from "../views/lzj_shop_class.vue"
import lzj_shop_info_router from "../views/lzj_shop_info.vue"
import lzj_shop_from_router from "../views/lzj_shop_from.vue"
import lzj_shop_confirm_router from "../views/lzj_shop_confirm.vue"
import lzj_shop_user_router from "../views/lzj_shop_user.vue"
import lzj_shop_my_from_router from "../views/lzj_shop_my_from.vue"
import lzj_shop_cart_router from "../views/lzj_shop_cart.vue"
import lzj_shop_address_router from "../views/lzj_shop_address.vue"
import lzj_shop_chat_router from "../views/lzj_shop_chat.vue"
import lzj_shop_login_router from "../views/lzj_shop_login.vue"
import lzj_shop_404_router from "../views/lzj_shop_404.vue"


const routes = [
    {
        name: "index",
        path: '/',
        component: lzj_shop_index_router,
        meta: {
            title: "亿启商城首页"
        }
    },
    {
        name: "home",
        path: "/home", component: lzj_shop_home_router, meta: {
            title: "亿启商城首页"
        }
    },
    {
        name: "class",
        path: "/class", component: lzj_shop_class_router, meta: {
            title: "商品分类"
        }
    },
    {
        name: "info",
        path: "/info/:shopid", component: lzj_shop_info_router, meta: {
            title: "商品"
        }
    },
    {
        name: "from",
        path: "/from/:shopid", component: lzj_shop_from_router, meta: {
            title: "确认订单"
        }
    },
    {
        name: "confirm",
        path: "/confirm/", component: lzj_shop_confirm_router, meta: {
            title: "付款详情"
        }, query: {
            name: "fromid",
            name: "token"
        },
    },
    {
        name: "user",
        path: "/user", component: lzj_shop_user_router, meta: {
            title: "个人中心"
        }
    },
    {
        name: "myfrom",
        path: "/myfrom", component: lzj_shop_my_from_router, meta: {
            title: "我的订单"
        }
    },
    {
        name: "cart",
        path: "/cart", component: lzj_shop_cart_router, meta: {
            title: "购物车"
        }
    }, {
        name: "address",
        path: "/address", component: lzj_shop_address_router, meta: {
            title: "地址管理"
        }
    }, {
        name: "chat",
        path: "/chat/", component: lzj_shop_chat_router, meta: {
            title: "联系卖家"
        }, query: {
            name: "shopid",
        },
    }, {
        name: "login",
        path: "/login", component: lzj_shop_login_router, meta: {
            title: "登录"
        },
    },
    {
        path: '/:pathMatch(.*)',
        component: lzj_shop_404_router
    }

]

const router = createRouter({
    history: createWebHashHistory(),
    routes,
})



export default router