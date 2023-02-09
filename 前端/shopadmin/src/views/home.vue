<template>
  <div class="home">
    <el-container>
      <el-header height="60px">
        <div class="header_title">{{ obj.headertitle }}</div>
        <el-menu
          :default-active="obj.defaultselect"
          class="el-menu-demo"
          mode="horizontal"
          background-color="#393d49"
          text-color="#fff"
          active-text-color="#ffd04b"
          @select="obj.headerhandleSelect"
          style="
            max-width: 65%;
            min-width: 50%;
            height: 100%;
            float: left;
            border: 0px;
          "
        >
          <el-menu-item
            v-for="(i, index) in obj.nav"
            :key="index"
            :index="i.index"
            >{{ i.title }}</el-menu-item
          >
        </el-menu>
        <div class="header_outgin" @click="obj.outgin">
          <el-icon style="margin-right: 5px; height: 20px; width: 20px" circle>
            <SwitchButton />
          </el-icon>
          退出登入
        </div>
      </el-header>
      <el-container>
        <el-aside width="15%">
          <el-menu
            active-text-color="#ffd04b"
            background-color="#545c64"
            class="el-menu-vertical-demo"
            text-color="#fff"
            @select="obj.asidehandleSelect"
            style="border-right-width: 0"
            :default-active="obj.asideselect"
          >
            <div v-for="(i, index) in obj.nav" :key="index">
              <div v-if="i.index == obj.headinselect">
                <el-menu-item
                  v-for="(ii, index1) in i.nav"
                  :index="i.index + '-' + ii.index"
                  :key="index1"
                >
                  {{ ii.title }}
                </el-menu-item>
              </div>
            </div>
          </el-menu>
        </el-aside>
        <el-container>
          <el-main>
            <router-view></router-view>
          </el-main>
          <el-footer height="60px">
            <div class="foot_title">{{ obj.copyright }}</div>
          </el-footer>
        </el-container>
      </el-container>
    </el-container>
  </div>
</template>

<script>
import { reactive, ref } from "@vue/reactivity";
import { getyear } from "@/function/getyear";
import { ElMessage } from "element-plus";
import router from "@/router";
import { gettoken } from "@/function/gettoken";
import { getCurrentInstance, onMounted } from "@vue/runtime-core";
import { useRouter } from "vue-router";
export default {
  setup() {
    let internalInstance = getCurrentInstance();
    let cookies = internalInstance.appContext.config.globalProperties.$cookies;
    let axios = internalInstance.appContext.config.globalProperties.$axios;
    let url = internalInstance.appContext.config.globalProperties.$url;
    const obj = reactive({
      // 导航列表
      nav: [
        {
          title: "基础",
          index: "0",
          nav: [
            {
              title: "使用必读",
              index: "0",
            },
          ],
        },
        {
          title: "分类",
          index: "1",
          nav: [
            {
              title: "一级分类",
              index: "0",
            },
            {
              title: "二级分类",
              index: "1",
            },
          ],
        },
        {
          title: "商品",
          index: "2",
          nav: [
            {
              title: "商品列表",
              index: "0",
            },
            {
              title: "收藏列表",
              index: "1",
            },
            {
              title: "订单列表",
              index: "2",
            },
            {
              title: "购物车列表",
              index: "3",
            },
          ],
        },
        {
          title: "用户",
          index: "3",
          nav: [
            {
              title: "用户列表",
              index: "0",
            },

            {
              title: "地址列表",
              index: "1",
            },
          ],
        },
        {
          title: "聊天",
          index: "4",
          nav: [
            {
              title: "客服中心",
              index: "0",
            },
          ],
        },
      ],

      // 路由列表
      route: {
        "0-0": "baseread",
        "1-0": "oneclass",
        "1-1": "twoclass",
        "2-0": "shoplist",
        "2-1": "collect",
        "2-2": "shopfrom",
        "2-3": "shopcart",
        "3-0": "userlist",
        "3-1": "address",
        "4-0": "message",
      },

      // 头部导航栏
      // 基础选择项
      defaultselect: "0",
      headinselect: "0",
      headertitle: "后台管理面板",
      headerhandleSelect: (key, keyPath) => {
        obj.headinselect = key;
        obj.asideselect = "0";
        obj.defaultselect = "0";
      },

      // 底部版权
      copyright: "Copyright @" + getyear() + " LZJ. All rights reserved.",

      // 左侧导航栏
      asideselect: "0",
      asidehandleSelect: (key, index) => {
        router.push({ name: obj.route[key] });
      },

      // 退出登入
      outgin: () => {
        ElMessage("退出成功");
        cookies.set("token", "");
        router.push({ name: "login" });
      },
    });

    onMounted(() => {
      if (cookies.get("token") && cookies.get("token") != "") {
        let data = new FormData();
        data.append("token", cookies.get("token"));
        axios
          .post(url.token, data)
          .then(function (response) {
            if (JSON.parse(response.data.data).token == false) {
              ElMessage("未登入");
              router.push({ name: "login" });
            }
          })
          .catch(function (error) {
            console.log(error);
          });
      } else {
        ElMessage("未登入");
        router.push({ name: "login" });
      }
    });

    return {
      obj,
    };
  },
};
</script>

<style>
.home {
  position: relative;
  width: 100%;
  height: 100%;
  overflow: hidden;
  display: flex;
}

.el-header {
  background-color: #393d49;
  color: #fff3d0;
  text-align: left;
  line-height: 60px;
  --el-header-padding: 0px !important;
  overflow: hidden;
}

.header_outgin {
  position: absolute;
  float: right;
  right: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 15px;
  cursor: pointer;
}

.header_title {
  position: relative;
  float: left;
  padding-left: 20px;
  padding-right: 20px;
  width: 15%;
  text-align: center;
  color: #ffd04b;
}

.el-footer {
  background-color: #393d49;
  color: #333;
  text-align: center;
  color: #ffd04b;
}

.foot_title {
  position: relative;
  text-align: center;
  line-height: 60px;
}

.el-aside {
  background-color: #545c64;
  color: #333;
  text-align: center;
}

.el-main {
  position: relative;
  width: 100%;
  height: 100%;
  overflow: hidden;
  background-color: #eeeeee;
  color: #333;
  text-align: center;
}

.el-menu {
  background-color: #0757b3;
}
</style>