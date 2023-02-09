<template>
  <div class="lzj_shop_class">
    <lzjlistrightmenu @lzj_update_show="lzj_update_show"></lzjlistrightmenu>
    <lzjlistdiv @lzj_class_name_click="lzj_class_name_click"></lzjlistdiv>
    <lzjshopnavfoot :foot_nav="lzj_shop_class_obj.foot_nav"></lzjshopnavfoot>
  </div>
</template>

<script>
import lzjshopnavfoot from "@/components/lzj_shop_nav_foot.vue";
import lzjlistrightmenu from "@/components/lzj_list_right_menu.vue";
import lzjlistdiv from "@/components/lzj_list_div.vue";
import { reactive } from "@vue/reactivity";
import { provide } from "@vue/runtime-core";
import axios from "axios";
export default {
  components: {
    lzjshopnavfoot,
    lzjlistdiv,
    lzjlistrightmenu,
  },
  setup() {
    const lzj_shop_class_data = () => {
      lzj_shop_class_obj.items_data = [];
      axios
        .get("http://47.106.68.150:81/shoplist/class")
        .then((response) => {
          Object.assign(
            lzj_shop_class_obj.items_data,
            JSON.parse(response.data.data)
          );
        })
        .catch(function (error) {
          console.log(error);
        });
    };
    const lzj_shop_class_obj = reactive({
      menu: {
        back_img: "http://static.000081.xyz/img/001_r6_c2.png",
        menu_show: false,
        up_show_img: "http://static.000081.xyz/img/sc_6_to.png",
        down_show_img: "http://static.000081.xyz/img/sc_6_bo.png",
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
          url: "cart",
        },
        {
          img_url: "http://static.000081.xyz/img/sc_center_logo.png",
          title: "个人中心",
          url: "user",
        },
      ],
      items_data: [],
    });
    const lzj_update_show = () => {
      lzj_shop_class_obj.menu.menu_show = !lzj_shop_class_obj.menu.menu_show;
    };
    const lzj_class_name_click = (id) => {
      lzj_shop_class_obj.menu.menu_show = true;
      for (var i = 0; i < lzj_shop_class_obj.items_data.length; i++) {
        if (lzj_shop_class_obj.items_data[i].id == id) {
          lzj_shop_class_obj.items_data[i].show = true;
        }
      }
    };
    lzj_shop_class_data();
    provide("lzj_shop_obj", lzj_shop_class_obj);
    return {
      lzj_shop_class_obj,
      lzj_update_show,
      lzj_class_name_click,
    };
  },
};
</script>

<style>
.lzj_shop_class {
  position: relative;
  width: 100%;
  overflow: hidden;
  background-color: #ececec;
}
</style>