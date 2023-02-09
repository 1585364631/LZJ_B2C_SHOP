<template>
  <div class="lzj_shop_user">
    <div class="lzj_shop_user_head">
      <div class="lzj_shop_user_head_img">
        <img
          :src="lzj_shop_user_obj.img"
          style="width: 85%; height: 85%; border-radius: 50%"
        />
      </div>
      <div class="lzj_shop_user_head_text">{{ lzj_shop_user_obj.name }}</div>
      <div style="clear: both" @click="lzj_shop_outgin"></div>
    </div>

    <div
      class="lzj_shop_user_head_line"
      style="margin-top: 10px"
      @click="lzj_shop_user_click('myfrom')"
    >
      <div class="lzj_shop_user_head_line_img">
        <img
          src="http://static.000081.xyz/img/sc_9_r.png"
          style="width: 20px; height: 50%"
        />
      </div>
      我的订单
    </div>
    <div class="lzj_shop_user_head_line" @click="lzj_shop_user_click('cart')">
      <div class="lzj_shop_user_head_line_img">
        <img
          src="http://static.000081.xyz/img/sc_9_r.png"
          style="width: 20px; height: 50%"
        />
      </div>
      我的购物车
    </div>
    <div
      class="lzj_shop_user_head_line"
      style="margin-top: 20px"
      @click="lzj_shop_user_click('address')"
    >
      <div class="lzj_shop_user_head_line_img">
        <img
          src="http://static.000081.xyz/img/sc_9_r.png"
          style="width: 20px; height: 50%"
        />
      </div>
      收货地址管理
    </div>
    <div class="lzj_shop_user_outgin" @click="lzj_shop_outgin">退出登录</div>
    <lzjshopnavfoot :foot_nav="lzj_shop_user_obj.foot_nav"></lzjshopnavfoot>
  </div>
</template>

<script>
import { getCurrentInstance, reactive, ref } from "@vue/runtime-core";
import { useRouter } from "vue-router";
import { gettoken } from "@/function/gettoken";
import axios from "axios";
import lzjshopnavfoot from "@/components/lzj_shop_nav_foot.vue";
export default {
  components: {
    lzjshopnavfoot,
  },
  setup() {
    let internalInstance = getCurrentInstance();
    let cookies = internalInstance.appContext.config.globalProperties.$cookies;
    const lzj_shop_user_obj = reactive({
      name: "测试",
      img: "",
      foot_nav: [
        {
          img_url: "http://static.000081.xyz/img/sc_home_logo.png",
          title: "商品主页",
          url: "/home",
        },

        {
          img_url: "http://static.000081.xyz/img/sc_goods_logo.png",
          title: "商品分类",
          url: "/class",
        },
        {
          img_url: "http://static.000081.xyz/img/sc_center_logo.png",
          title: "个人中心",
          url: "user",
        },
      ],
    });

    const router = useRouter();
    const lzj_shop_user_click = (i) => {
      router.push({
        name: i,
      });
    };
    const lzj_shop_outgin = () => {
      if (cookies.get("token")) {
        cookies.remove("token");
      }
      cookies.set("routerpath", { name: router.currentRoute.value.name });
      router.push({ name: "login" });
    };

    const lzj_shop_login_search = () => {
      if (cookies.get("token") && cookies.get("token") != "") {
        let data = new FormData();
        data.append("token", cookies.get("token"));
        axios
          .post("http://47.106.68.150:81/shopuser/info", data)
          .then(function (response) {
            var js = JSON.parse(response.data.data);
            if (js.token != false) {
              lzj_shop_user_obj.name = js.data.name;
              lzj_shop_user_obj.img = js.data.img;
            } else {
              lzj_shop_outgin();
            }
          })
          .catch(function (error) {
            console.log(error);
          });
      } else {
        lzj_shop_outgin();
      }
    };
    lzj_shop_login_search();

    return {
      lzj_shop_user_click,
      lzj_shop_outgin,
      lzj_shop_user_obj,
    };
  },
};
</script>

<style>
.lzj_shop_user {
  position: relative;
  width: 100%;
  height: 100%;
  background-color: #ececec;
}

.lzj_shop_user_head {
  position: relative;
  width: 100%;
  height: 100px;
  background-color: #ffffff;
}

.lzj_shop_user_head_img {
  width: 16%;
  height: 100%;
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: 50%;
  overflow: hidden;
  float: left;
}

.lzj_shop_user_head_text {
  width: 74%;
  height: 100%;
  line-height: 100px;
  overflow: hidden;
  font-size: 30px;
  padding-left: 15px;
  text-align: left;
  float: left;
}

.lzj_shop_user_head_line {
  width: 92%;
  position: relative;
  height: 70px;
  margin-left: 4%;
  border: 2px;
  text-align: left;
  border-radius: 5px;
  padding-left: 20px;
  padding-right: 40px;
  font-size: 23px;
  line-height: 70px;
  background-color: #ffffff;
  border: 1px solid rgb(227, 220, 220);
}

.lzj_shop_user_head_line_img {
  position: absolute;
  width: 40px;
  height: 100%;
  right: 0;
  z-index: 999;
  display: flex;
  justify-content: center;
  align-items: center;
}

.lzj_shop_user_outgin {
  position: relative;
  width: 70%;
  font-weight: bold;
  color: white;
  height: 80px;
  font-size: 30px;
  margin-left: 15%;
  margin-top: 40px;
  border-radius: 10px;
  line-height: 80px;
  background-color: red;
}
</style>