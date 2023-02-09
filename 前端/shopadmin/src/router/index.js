import { createMemoryHistory, createRouter, createWebHashHistory } from "vue-router"
import home from "../views/home"
import login from "../views/login"
import view404 from "../views/view404"
import homeindex from "../components/homeindex"
import baseread from "../components//baseread"
import oneclass from "../components/oneclass"
import twoclass from "../components/twoclass"
import shoplist from "../components/shoplist"
import collect from "../components/collect"
import shopfrom from "../components/from"
import shopcart from "../components/cart"
import userlist from "../components/userlist"
import address from "../components/address"
import message from "../components/message"
const routes = [
    {
        name: "home",
        path: '/',
        component: home,
        meta: {
            title: "后台管理面板"
        },
        children: [
            {
                name: 'index',
                path: "",
                component: homeindex
            }, {
                name: 'baseread',
                path: "baseread",
                component: baseread
            }, {
                name: "oneclass",
                path: "oneclass",
                component: oneclass
            }, {
                name: "twoclass",
                path: "twoclass",
                component: twoclass
            }, {
                name: "shoplist",
                path: "shoplist",
                component: shoplist
            }, {
                name: "collect",
                path: "collect",
                component: collect
            }, {
                name: "shopfrom",
                path: "shopfrom",
                component: shopfrom
            }, {
                name: "shopcart",
                path: "shopcart",
                component: shopcart
            }, {
                name: "userlist",
                path: "userlist",
                component: userlist
            }, {
                name: "address",
                path: "address",
                component: address
            }, {
                name: "message",
                path: "message",
                component: message
            }
        ]
    }, {
        name: "login",
        path: '/login',
        component: login,
        meta: {
            title: "后台管理面板登入页面"
        }
    }, {
        path: "/:catchAll(.*)",
        component: view404
    }
]
const router = createRouter({
    history: createMemoryHistory(),
    routes,
})
export default router