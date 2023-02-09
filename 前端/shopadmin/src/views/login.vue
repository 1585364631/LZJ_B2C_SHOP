<template>
  <div class="login">
    <div class="login_number">
      <el-input
        v-model="username"
        placeholder="请输入管理员用户名"
        class="login_ge"
      ></el-input>
      <el-input
        v-model="password"
        type="password"
        placeholder="请输入管理员密码"
        class="login_ge"
      ></el-input>
      <el-button class="login_ge" @click="login">登入</el-button>
    </div>
  </div>
</template>

<script>
import { ref } from "@vue/reactivity";
import {
  getCurrentInstance,
  onMounted,
  watch,
  watchEffect,
} from "@vue/runtime-core";
import { ElMessage } from "element-plus";
import { gettoken } from "@/function/gettoken";
import { useRouter } from "vue-router";
export default {
  setup() {
    const username = ref("");
    const password = ref("");

    let internalInstance = getCurrentInstance();
    let cookies = internalInstance.appContext.config.globalProperties.$cookies;
    let axios = internalInstance.appContext.config.globalProperties.$axios;
    let url = internalInstance.appContext.config.globalProperties.$url;
    const router = useRouter();

    const login = () => {
      if (username.value == "" || password.value == "") {
        ElMessage("账号密码不能为空");
        return;
      }
      if (username.value.length < 5 || password.value.length < 5) {
        ElMessage("账号或密码格式不规范");
        return;
      }
      let data = new FormData();
      data.append("token", cookies.get("token"));
      data.append("username", username.value);
      data.append("password", password.value);
      axios
        .post(url.login, data)
        .then(function (response) {
          if (JSON.parse(response.data.data).login) {
            router.push({ name: "home" });
          }
          ElMessage(JSON.parse(response.data.data).text);
        })
        .catch(function (error) {
          console.log(error);
        });
    };

    onMounted(() => {
      if (cookies.get("token") && cookies.get("token") != "") {
        let data = new FormData();
        data.append("token", cookies.get("token"));
        axios
          .post(url.token, data)
          .then(function (response) {
            if (JSON.parse(response.data.data).token != false) {
              router.push({ name: "home" });
            }
          })
          .catch(function (error) {
            console.log(error);
          });
      } else {
        cookies.set("token", gettoken());
      }
    });

    return {
      username,
      password,
      login,
    };
  },
};
</script>

<style>
.login {
  position: relative;
  width: 100%;
  height: 100%;
  overflow: hidden;
  display: flex;
  justify-content: center;
  align-items: center;
  background-image: url("https://gimg2.baidu.com/image_search/src=http%3A%2F%2Fimg.zcool.cn%2Fcommunity%2F01b97655683aa40000012b205d0020.jpg&refer=http%3A%2F%2Fimg.zcool.cn&app=2002&size=f9999,10000&q=a80&n=0&g=0n&fmt=auto?sec=1659576793&t=7c0b1b3aa1ccb47a7be9cfb50fcdb2c3");
}

.login_number {
  position: absolute;
  width: 300px;
  text-align: center;
}

.login_ge {
  margin-top: 12px;
  margin-bottom: 12px;
  padding-left: 20px;
  padding-right: 20px;
}
</style>