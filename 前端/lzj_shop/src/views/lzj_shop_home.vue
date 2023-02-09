<template>
  <div class="lzj_shop_home_css">
    <lzjshophomehead></lzjshophomehead>
    <lzjlistdiv></lzjlistdiv>
    <lzjshopnavfoot :foot_nav="lzj_shop_home_obj.foot_nav"></lzjshopnavfoot>
  </div>
</template>

<script>
import lzjshophomehead from "@/components/lzj_shop_home_head.vue";
import lzjlistdiv from "@/components/lzj_list_div.vue";
import lzjshopnavfoot from "@/components/lzj_shop_nav_foot.vue";
import { reactive } from "@vue/reactivity";
import { provide } from "@vue/runtime-core";
import axios from "axios";
export default {
  components: { lzjshophomehead, lzjlistdiv, lzjshopnavfoot },
  setup() {
    const lzj_home_data = () => {
      lzj_shop_home_obj.items_data = [];
      lzj_shop_home_obj.lunbo_data = [];
      axios
        .get("http://47.106.68.150:81/shoplist/home")
        .then((response) => {
          Object.assign(
            lzj_shop_home_obj.items_data,
            JSON.parse(response.data.data)
          );
        })
        .catch(function (error) {
          console.log(error);
        });
      axios
        .get("http://47.106.68.150:81/shoplist/homeimg")
        .then((response) => {
          Object.assign(
            lzj_shop_home_obj.lunbo_data,
            JSON.parse(response.data.data)
          );
        })
        .catch(function (error) {
          console.log(error);
        });
    };

    const lzj_shop_home_obj = reactive({
      head_img: "http://static.000081.xyz/img/headimg.png",
      head_name: "亿启商城",
      wx_img: "http://static.000081.xyz/img/sc_2_3.jpg",
      email: "1146268899@qq.com",
      guanggao: {
        guanggao_open: true,
        guanggao_text:
          "亿启商城专卖，有任何问题请联系我们的微信公众：1146268899@qq.com",
        guanggao_img: "http://static.000081.xyz/img/guanggaoimg.png",
        guanggao_img_text: "进店领取红包，100%必中奖",
        guanggao_src: "http://www.baidu.com",
      },
      foot_nav: [
        {
          img_url: "http://static.000081.xyz/img/sc_goods_logo.png",
          title: "商品分类",
          url: "/class",
        },
        {
          img_url: "http://static.000081.xyz/img/sc_car_logo.png",
          title: "购物车",
          url: "/cart",
        },
        {
          img_url: "http://static.000081.xyz/img/sc_center_logo.png",
          title: "个人中心",
          url: "user",
        },
      ],
      items_class_show: true,
      items_data: [],
      lunbo_data: [],
    });
    provide("lzj_shop_obj", lzj_shop_home_obj);
    lzj_home_data();
    return { lzj_shop_home_obj };
  },
};
</script>

<style>
.lzj_shop_home_css {
  position: relative;
  background-color: #f8f8f8;
  width: 100%;
  height: 100%;
}
</style>