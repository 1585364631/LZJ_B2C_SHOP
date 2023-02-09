<template>
  <div class="lzj_shop_confirm">
    <div class="lzj_shop_confirm_head">
      <div class="lzj_shop_confirm_head_img">
        <img src="http://static.000081.xyz/img/sc_9_g.png" style="width: 50px" />
      </div>
      <div class="lzj_shop_confirm_head_title">付款成功</div>
      <div class="lzj_shop_confirm_head_text">
        等待对方发货，联系对方尽快发货
      </div>
      <div style="clear: both"></div>
    </div>

    <div class="lzj_shop_confirm_body">
      <div class="lzj_shop_confirm_body_title">交易信息</div>
      <div class="lzj_shop_confirm_foot">
        <div v-for="(i, k) in lzj_shop_obj.data" :key="k">
          <lzjshopconfirmline :lzj_shop_obj="i"></lzjshopconfirmline>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { reactive } from "@vue/reactivity";
import { useRouter } from "vue-router";
import axios from "axios";
import { getCurrentInstance } from "@vue/runtime-core";
import lzjshopconfirmline from "../components/lzj_shop_confirm_line.vue";
export default {
  components: {
    lzjshopconfirmline,
  },
  setup() {
    const router = useRouter();
    const lzj_shop_id = router.currentRoute.value.params.shopid;
    let internalInstance = getCurrentInstance();
    let cookies = internalInstance.appContext.config.globalProperties.$cookies;
    var lzj_shop_confirm_url = "http://47.106.68.150:81/shopfrom/update";
    const lzj_shop_obj = reactive({
      data: [
        {
          title: "商品信息",
          text: "匡威官方旗舰店 ChuckTaylor 男女高帮经典款帆布鞋 正品 102307",
        },
        {
          title: "商品总价",
          text: "1200.00",
          price: true,
        },
        {
          title: "邮费",
          text: "10.00",
          price: true,
        },
        {
          title: "应付总价",
          text: "1210.00",
          price: true,
        },
        {
          title: "实际付款",
          text: "1210.00",
          price: true,
        },
        {
          title: "交易时间",
          text: "2015-01-27",
        },
      ],
    });

    if (cookies.get("token") && cookies.get("token") != "") {
      let data = new FormData();
      data.append("token", cookies.get("token"));
      data.append("id", router.currentRoute.value.query.fromid);
      data.append("key", router.currentRoute.value.query.token);
      axios
        .post(lzj_shop_confirm_url, data)
        .then(function (response) {
          var js = JSON.parse(response.data.data);
          if (js.token != false) {
            lzj_shop_obj.data = js.data;
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
      cookies.set("routerpath", {
        name: router.currentRoute.value.name,
      });
      router.push({ name: "login" });
    }

    return {
      lzj_shop_obj,
    };
  },
};
</script>

<style>
.lzj_shop_confirm {
  width: 100%;
  position: relative;
  height: 100%;
  background-color: #ececec;
  overflow: scroll;
}

.lzj_shop_confirm_head {
  width: 96%;
  margin-left: 2%;
  padding-top: 10px;
  padding-bottom: 10px;
  height: 110px;
  border-bottom: 3px solid #dfdfdf;
  text-align: left;
}

.lzj_shop_confirm_head_img {
  width: 12%;
  position: relative;
  height: 100%;
  float: left;
  display: flex;
  justify-content: center;
  align-items: center;
}

.lzj_shop_confirm_head_title {
  width: 88%;
  font-size: 30px;
  font-weight: bold;
  line-height: 35px;
  padding-top: 13px;
}
.lzj_shop_confirm_head_text {
  width: 88%;
  font-size: 23px;
  line-height: 25px;
  color: #1f1f1f;
}

.lzj_shop_confirm_body {
  width: 88%;
  margin-top: 100px;
  margin-left: 6%;
  position: relative;
}

.lzj_shop_confirm_body_title {
  width: 100%;
  font-size: 25px;
  font-weight: bold;
  text-align: left;
  padding-bottom: 2px;
  border-bottom: 3px solid #dfdfdf;
}

.lzj_shop_confirm_foot {
  width: 100%;
  margin-top: 50px;
  height: 100px;
  position: relative;
}
</style>