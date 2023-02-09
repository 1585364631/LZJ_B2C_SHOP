<template>
  <div class="lzj_shop_address">
    <div v-if="in_index == 0">
      <div
        v-for="(i, index) in lzj_shop_obj.data"
        :key="index"
        @click="lzj_update(i, index)"
      >
        <lzjshopfromcard1
          :base_info="i.data"
          v-if="i.id == lzj_shop_obj.default"
          :bordercolor="'3px solid #fd9d5c'"
        ></lzjshopfromcard1>
        <lzjshopfromcard1 :base_info="i.data" v-else></lzjshopfromcard1>
      </div>
      <div style="position: relative; height: 90px"></div>
      <div class="lzj_shop_address_foot" @click="lzj_shop_address_add">
        新增地址
      </div>
    </div>
    <div v-else>
      <div>
        <lzjshopfromline
          v-for="(i, index) in lzj_address_obj.data"
          :key="index"
          :base_info="i"
        ></lzjshopfromline>
      </div>
      <div class="lzj_default_address">
        设置默认地址
        <input
          type="radio"
          name="defaultaddress"
          :value="true"
          v-model="lzj_shop_address_check"
          :checked="lzj_shop_address_check"
        />是
        <input
          type="radio"
          name="defaultaddress"
          :value="false"
          v-model="lzj_shop_address_check"
          :checked="!lzj_shop_address_check"
        />否
      </div>
      <div
        class="lzj_shop_address_foot_save"
        @click="lzj_shop_address_del"
        v-if="!lzj_shop_address_add_status"
      >
        删除
      </div>
      <div class="lzj_shop_address_foot_save" @click="lzj_shop_address_save">
        保存
      </div>
    </div>
  </div>
</template>

<script>
import { reactive, ref } from "@vue/reactivity";
import lzjshopfromcard1 from "../components/lzj_shop_from_card1.vue";
import lzjshopfromline from "../components/lzj_shop_from_line.vue";
import { getCurrentInstance, watch } from "@vue/runtime-core";
import { useRouter } from "vue-router";
import axios from "axios";
export default {
  components: {
    lzjshopfromcard1,
    lzjshopfromline,
  },
  setup() {
    let internalInstance = getCurrentInstance();
    let cookies = internalInstance.appContext.config.globalProperties.$cookies;
    const router = useRouter();
    var lzj_shop_address_list_url = "http://47.106.68.150:81/shopaddress/list";
    var lzj_shop_address_list_add_url = "http://47.106.68.150:81/shopaddress/add";
    var lzj_shop_address_list_update_url =
      "http://47.106.68.150:81/shopaddress/update";
    var lzj_shop_address_list_delete_url =
      "http://47.106.68.150:81/shopaddress/delete";
    var lzj_shop_address_default_update_url =
      "http://47.106.68.150:81/shopaddress/updatedefault";
    const in_index = ref(0);
    //单选框值判断
    const lzj_shop_address_check = ref(false);
    //判断是否为新增地址
    const lzj_shop_address_add_status = ref(false);
    //当前点击id
    const oncheck = ref(0);
    //添加栏对象
    const lzj_address_obj = reactive({
      data: [],
    });
    const lzj_shop_address_init = () => {
      if (cookies.get("token") && cookies.get("token") != "") {
        let data = new FormData();
        data.append("token", cookies.get("token"));
        axios
          .post(lzj_shop_address_list_url, data)
          .then(function (response) {
            var js = JSON.parse(response.data.data);
            if (js.token == true) {
              lzj_shop_obj.data = js.data;
              lzj_shop_obj.default = js.defaultid;
              oncheck.value = js.defaultid;
            } else {
              cookies.set("routerpath", {
                name: router.currentRoute.value.name,
              });
              router.push({ name: "login" });
            }
          })
          .catch(function (error) {
            console.log(error);
          });
      } else {
        cookies.set("routerpath", { name: router.currentRoute.value.name });
        router.push({ name: "login" });
      }
    };

    const lzj_shop_address_del = () => {
      in_index.value = 0;
      if (!lzj_shop_address_add_status.value) {
        let data = new FormData();
        data.append("token", cookies.get("token"));
        data.append("id", oncheck.value);
        axios
          .post(lzj_shop_address_list_delete_url, data)
          .then(function (response) {
            var js = JSON.parse(response.data.data);
            if (js.token == false) {
              cookies.set("routerpath", {
                name: router.currentRoute.value.name,
              });
              router.push({ name: "login" });
            }
            setTimeout(() => {
              lzj_shop_address_init();
            }, 500);
          })
          .catch(function (error) {
            console.log(error);
          });
      }
    };

    //点击地址更改信息
    const lzj_update = (i, index) => {
      lzj_shop_address_add_status.value = false;
      lzj_address_obj.data = i.data;
      oncheck.value = i.id;
      if (lzj_shop_obj.default == i.id) {
        lzj_shop_address_check.value = true;
      } else {
        lzj_shop_address_check.value = false;
      }
      in_index.value = 1;
    };

    //保存地址
    const lzj_shop_address_save = () => {
      if (in_index.value == 2) {
        for (var i = 0; i < lzj_address_new_obj.data.length - 1; i++) {
          if (lzj_address_new_obj.data[i].value == "") {
            in_index.value = 0;
            return;
          }
        }
        if (cookies.get("token") && cookies.get("token") != "") {
          let data = new FormData();
          data.append("token", cookies.get("token"));
          data.append("data", JSON.stringify(lzj_address_new_obj));
          axios
            .post(lzj_shop_address_list_add_url, data)
            .then(function (response) {
              var js = JSON.parse(response.data.data);
              if (js.token == true) {
                lzj_address_new_obj.id = js.id;
                setTimeout(() => {
                  if (lzj_shop_address_check.value == true) {
                    let data = new FormData();
                    data.append("token", cookies.get("token"));
                    data.append("id", js.id);
                    axios
                      .post(lzj_shop_address_default_update_url, data)
                      .then(function (response) {})
                      .catch(function (error) {
                        console.log(error);
                      });
                    lzj_shop_obj.default = js.id;
                  }
                }, 250);
              } else {
                cookies.set("routerpath", {
                  name: router.currentRoute.value.name,
                });
                router.push({ name: "login" });
              }
            })
            .catch(function (error) {
              console.log(error);
            });
          setTimeout(() => {
            lzj_shop_address_init();
          }, 500);
        } else {
          cookies.set("routerpath", { name: router.currentRoute.value.name });
          router.push({ name: "login" });
        }
        in_index.value = 0;
        return;
      }
      var cachei = 0;
      for (var i = 0; i < lzj_shop_obj.data.length; i++) {
        if (oncheck.value == lzj_shop_obj.data[i].id) {
          cachei = i;
          break;
        }
      }
      for (var i = 0; i < lzj_shop_obj.data[cachei].data.length - 1; i++) {
        if (lzj_shop_obj.data[cachei].data[i].value == "") {
          alert("有字段没填");
          return;
        }
      }

      if (cookies.get("token") && cookies.get("token") != "") {
        let data = new FormData();
        data.append("token", cookies.get("token"));
        data.append(
          "data",
          JSON.stringify({ id: oncheck.value, data: lzj_address_obj.data })
        );
        axios
          .post(lzj_shop_address_list_update_url, data)
          .then(function (response) {
            var js = JSON.parse(response.data.data);
            if (js.token == false) {
              cookies.set("routerpath", {
                name: router.currentRoute.value.name,
              });
              router.push({ name: "login" });
            }
          })
          .catch(function (error) {
            console.log(error);
          });
        if (lzj_shop_address_check.value == true) {
          let data = new FormData();
          data.append("token", cookies.get("token"));
          data.append("id", oncheck.value);
          axios
            .post(lzj_shop_address_default_update_url, data)
            .then(function (response) {
              var js = JSON.parse(response.data.data);
              if (js.token == false) {
                cookies.set("routerpath", {
                  name: router.currentRoute.value.name,
                });
                router.push({ name: "login" });
              }
            })
            .catch(function (error) {
              console.log(error);
            });
          lzj_shop_obj.default = oncheck.value;
        }
        setTimeout(() => {
          lzj_shop_address_init();
        }, 500);
      }
      in_index.value = 0;
    };

    //添加地址
    const lzj_address_new_obj = reactive({
      id: 0,
      data: [],
    });
    const lzj_shop_address_add = () => {
      lzj_shop_address_add_status.value = true;
      var maxid = 0;
      for (var i = 0; i < lzj_shop_obj.data.length; i++) {
        if (lzj_shop_obj.data[i].id > maxid) {
          maxid = lzj_shop_obj.data[i].id;
        }
      }
      lzj_shop_address_check.value = false;
      maxid++;
      (lzj_address_new_obj.data = [
        {
          tip: "收货人",
          value: "",
        },
        {
          tip: "手机号码",
          value: "",
        },
        {
          tip: "地区信息",
          value: "",
        },
        {
          tip: "详细地址",
          value: "",
        },
        {
          tip: "邮政编码",
          value: "",
        },
      ]),
        (lzj_address_new_obj.id = maxid);
      lzj_address_obj.data = lzj_address_new_obj.data;
      in_index.value = 2;
    };
    const lzj_shop_obj = reactive({
      default: 0,
      data: [],
    });

    //监听返回键
    // watch(
    //   () => in_index,
    //   () => {
    //     if (in_index.value == 0) {
    //       window.removeEventListener(
    //         "popstate",
    //         () => {
    //           in_index.value = 0;
    //         },
    //         false
    //       );
    //     } else {
    //       if (window.history && window.history.pushState) {
    //         history.pushState(null, null, document.URL);
    //         window.addEventListener(
    //           "popstate",
    //           () => {
    //             in_index.value = 0;
    //           },
    //           false
    //         );
    //       }
    //     }
    //   },
    //   {
    //     deep: true,
    //   }
    // );

    lzj_shop_address_init();

    return {
      lzj_shop_obj,
      in_index,
      lzj_update,
      lzj_shop_address_add,
      lzj_address_new_obj,
      oncheck,
      lzj_address_obj,
      lzj_shop_address_check,
      lzj_shop_address_save,
      lzj_shop_address_del,
      lzj_shop_address_add_status,
    };
  },
};
</script>

<style>
.lzj_shop_address {
  position: relative;
  width: 100%;
  height: 100%;
  overflow: scroll;
  background-color: #ececec;
}
.lzj_shop_address_foot {
  position: fixed;
  width: 100%;
  bottom: 0;
  height: 90px;
  font-size: 35px;
  line-height: 90px;
  color: white;
  background-color: #c40000;
}
.lzj_shop_address_foot_save {
  position: relative;
  height: 90px;
  width: 93%;
  background-color: #c40000;
  margin-top: 40px;
  border-radius: 5px;
  margin-left: 3.5%;
  color: white;
  line-height: 90px;
  font-size: 38px;
}

.lzj_default_address {
  width: 100%;
  height: 50px;
  margin-top: 30px;
  line-height: 50px;
  font-size: 28px;
  font-weight: bold;
}
</style>