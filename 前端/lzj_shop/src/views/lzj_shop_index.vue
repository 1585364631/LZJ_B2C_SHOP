<template>
  <div class="lzj_shop_index" ref="lzj_shop_index_ref">
    <div class="lzj_shop_index_div">
      <div class="lzj_shop_index_div_bottom"><lzjlunbo></lzjlunbo></div>
      <div class="lzj_shop_index_div_right">
        <lzjindexnav :nav_data="lzj_shop_index_obj.nav_data"></lzjindexnav>
      </div>
    </div>
    <img :src="lzj_shop_index_obj.bg_img_src" class="lzj_shop_index_img" />
  </div>
</template>

<script>
import { reactive, ref } from "@vue/reactivity";
import lzjindexnav from "@/components/lzj_index_nav.vue";
import lzjlunbo from "@/components/lzj_lunbo.vue";
import { onMounted, onUpdated, provide } from "@vue/runtime-core";
import axios from "axios";
export default {
  components: { lzjlunbo, lzjindexnav },
  setup() {
    const lzj_shop_index_ref = ref(null);
    axios
      .get("http://47.106.68.150:81/shoplist/index")
      .then((response) => {
        Object.assign(lzj_shop_index_obj.data, JSON.parse(response.data.data));
      })
      .catch(function (error) {
        console.log(error);
      });
    const lzj_shop_index_obj = reactive({
      bg_img_src: "http://static.000081.xyz/img/bg.png",
      left_img_src: "http://static.000081.xyz/img/sc_arrow_l.png",
      right_img_src: "http://static.000081.xyz/img/sc_arrow_r.png",
      data: [],
      nav_data: {
        nav_img_src: "http://static.000081.xyz/img/sc_menu_logo.png",
        data: [
          {
            title: "店铺首页",
            img_src: "http://static.000081.xyz/img/sc_home_logo.png",
            src: "/home",
          },
          {
            title: "商品分类",
            img_src: "http://static.000081.xyz/img/sc_wood_logo.png",
            src: "/class",
          },
          {
            title: "购物车",
            img_src: "http://static.000081.xyz/img/sc_car_logo.png",
            src: "/cart",
          },
          {
            title: "联系卖家",
            img_src: "http://static.000081.xyz/img/sc_kf.png",
            src: "/chat",
          },
          {
            title: "个人中心",
            img_src: "http://static.000081.xyz/img/sc_center_logo.png",
            src: "/user",
          },
        ],
      },
    });
    provide("lzj_shop_index_obj", lzj_shop_index_obj);

    onUpdated(() => {});

    return {
      lzj_shop_index_obj,
      lzj_shop_index_ref,
    };
  },
};
</script>

<style>
.lzj_shop_index {
  position: relative;
  width: 100%;
  height: 100%;
  overflow: hidden;
}

.lzj_shop_index_img {
  width: 100%;
  height: 100vh;
  position: relative;
  z-index: -1;
}

.lzj_shop_index_div {
  width: 100%;
  position: absolute;
  height: 100%;
}

/* 底部轮播图插槽 */
.lzj_shop_index_div_bottom {
  width: 100%;
  position: absolute;
  height: 500px;
  margin-top: 550px;
}

/* 右边导航按钮 */
.lzj_shop_index_div_right {
  position: absolute;
  right: 0px;

  margin-top: 50%;
}
</style>