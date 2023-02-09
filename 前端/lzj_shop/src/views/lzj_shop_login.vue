<template>
  <div class="lzj_shop_login">
    <div v-if="lzj_shop_login_obj.login">
      <div class="lzj_shop_login_title">登入页面</div>
      <div>
        <input
          class="lzj_shop_login_text"
          type="text"
          v-model="username"
          placeholder="请输入用户名(5-11位)"
        />
      </div>
      <div>
        <input
          class="lzj_shop_login_text"
          type="password"
          v-model="password"
          placeholder="请输入密码(5-11位)"
        />
      </div>
      <div class="lzj_shop_login_tip" @click="lzj_shop_login_change">
        没有账号？点我注册
      </div>
      <div class="lzj_shop_login_button" @click="lzj_shop_login">登入</div>
    </div>
    <div v-else>
      <div class="lzj_shop_login_title">注册页面</div>
      <div>
        <input
          class="lzj_shop_login_text"
          type="text"
          v-model="username"
          placeholder="请输入用户名(5-11位)"
        />
      </div>
      <div>
        <input
          class="lzj_shop_login_text"
          type="password"
          v-model="password"
          placeholder="请输入密码(5-11位)"
        />
      </div>
      <div>
        <input
          class="lzj_shop_login_text"
          type="text"
          v-model="name"
          placeholder="请输入昵称"
        />
      </div>
      <div class="lzj_shop_login_tip" @click="lzj_shop_login_change">
        已有账号？点我登入
      </div>
      <div class="lzj_shop_login_button" @click="lzj_shop_register">注册</div>
    </div>
    <div class="lzj_shop_login_error" v-text="lzj_shop_login_error"></div>
  </div>
</template>

<script>
import {
  getCurrentInstance,
  onMounted,
  reactive,
  ref,
} from "@vue/runtime-core";
import { gettoken } from "@/function/gettoken";
import axios from "axios";
import { useRouter } from "vue-router";
export default {
  setup() {
    let internalInstance = getCurrentInstance();
    let cookies = internalInstance.appContext.config.globalProperties.$cookies;
    const username = ref("");
    const password = ref("");
    const name = ref("");
    const router = useRouter();
    const lzj_shop_login_error = ref("");
    const lzj_shop_login_obj = reactive({
      login: true,
    });
    const lzj_shop_login_change = () => {
      lzj_shop_login_error.value = "";
      lzj_shop_login_obj.login = !lzj_shop_login_obj.login;
    };
    const lzj_shop_login_search = () => {
      if (cookies.get("token") && cookies.get("token") != "") {
        let data = new FormData();
        data.append("token", cookies.get("token"));
        axios
          .post("http://47.106.68.150:81/shopuser/token", data)
          .then(function (response) {
            if (JSON.parse(response.data.data).token != false) {
              if (cookies.get("routerpath")) {
                if (cookies.get("routerpath").name == "login") {
                  router.push({ name: "home" });
                } else {
                  router.push(cookies.get("routerpath"));
                }
              } else {
                router.push({ name: "home" });
              }
            }
          })
          .catch(function (error) {
            console.log(error);
          });
      } else {
        cookies.set("token", gettoken());
      }
    };

    onMounted(() => {
      lzj_shop_login_search();
    });
    const lzj_shop_login = () => {
      if (username.value == "" || password.value == "") {
        lzj_shop_login_error.value = "请按要求输入";
        return;
      }
      if (username.value.length > 11 || username.value.length < 5) {
        lzj_shop_login_error.value = "用户名长度不规范";
        return;
      }
      if (password.value.length > 11 || password.value.length < 5) {
        lzj_shop_login_error.value = "密码长度不规范";
        return;
      }
      let data = new FormData();
      data.append("token", cookies.get("token"));
      data.append("username", username.value);
      data.append("password", password.value);
      axios
        .post("http://47.106.68.150:81/shopuser/login", data)
        .then(function (response) {
          if (JSON.parse(response.data.data).login) {
            if (cookies.get("routerpath")) {
              if (cookies.get("routerpath").name == "login") {
                router.push({ name: "home" });
              } else {
                router.push(cookies.get("routerpath"));
              }
            } else {
              router.push({ name: "home" });
            }
          }
          lzj_shop_login_error.value = JSON.parse(response.data.data).text;
        })
        .catch(function (error) {
          console.log(error);
        });
    };
    const lzj_shop_register = () => {
      if (username.value == "" || password.value == "" || name == "") {
        lzj_shop_login_error.value = "请按要求输入";
        return;
      }
      if (username.value.length > 11 || username.value.length < 5) {
        lzj_shop_login_error.value = "用户名长度不规范";
        return;
      }
      if (password.value.length > 11 || password.value.length < 5) {
        lzj_shop_login_error.value = "密码长度不规范";
        return;
      }
      let data = new FormData();
      data.append("token", cookies.get("token"));
      data.append("username", username.value);
      data.append("password", password.value);
      data.append("name", name.value);
      axios
        .post("http://47.106.68.150:81/shopuser/registry", data)
        .then(function (response) {
          if (JSON.parse(response.data.data).registry) {
            router.push({ name: "home" });
          }
          lzj_shop_login_error.value = JSON.parse(response.data.data).text;
        })
        .catch(function (error) {
          console.log(error);
        });
    };

    return {
      lzj_shop_login,
      username,
      name,
      password,
      lzj_shop_login_obj,
      lzj_shop_login_change,
      lzj_shop_login_error,
      lzj_shop_register,
    };
  },
};
</script>

<style>
.lzj_shop_login {
  position: relative;
  width: 100%;
  height: 100%;
  overflow: scroll;
  background-color: #ececec;
  padding-top: 35%;
  background-image: url("http://static.000081.xyz/img/bg.png");
  background-size: 100% 100vh;
}

.lzj_shop_login_title {
  position: relative;
  width: 100%;
  height: 80px;
  font-weight: bold;
  font-size: 45px;
  line-height: 80px;
  color: red;
  margin-bottom: 15px;
}

.lzj_shop_login_text {
  position: relative;
  width: 80%;
  height: 60px;
  line-height: 60px;
  margin-top: 15px;
  padding-left: 20px;
  padding-right: 20px;
  font-size: 30px;
  border-radius: 15px;
}

.lzj_shop_login_button {
  position: relative;
  width: 150px;
  height: 60px;
  margin: 0 auto;
  margin-top: 50px;
  font-size: 28px;
  border-radius: 15px;
  line-height: 60px;
  font-weight: bold;
  color: white;
  background-color: rgb(226, 11, 11);
}

.lzj_shop_login_tip {
  position: relative;
  height: 50px;
  font-size: 28px;
  line-height: 50px;
  margin-top: 35px;
  font-weight: bold;
}

.lzj_shop_login_error {
  position: relative;
  height: 50px;
  margin-top: 50px;
  font-size: 28px;
  color: rgb(186, 243, 31);
  font-weight: bold;
}
</style>