<template>
  <div v-for="(i, keys) in lzj_shop_obj.items_data" :key="keys">
    <div class="lzj_shop_right_menu_one_class" @click="lzj_one_class(i.id)">
      <div class="lzj_shop_right_menu_one_class_title">{{ i.class }}</div>
      <div class="lzj_shop_right_menu_one_class_img">
        <img :src="lzj_shop_obj.menu.up_show_img" v-if="i.show" />
        <img :src="lzj_shop_obj.menu.down_show_img" v-else />
      </div>
    </div>
    <div style="clear: both"></div>

    <div v-if="i.show">
      <div
        v-for="(ii, keyss) in i.data"
        :key="keyss"
        @click="lzj_two_class_onclick(ii.data[0].id)"
      >
        <div class="lzj_shop_right_menu_one_class_text">
          {{ ii.class }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { inject } from "@vue/runtime-core";
var gotoid = (id) => {
  var id = "#item" + id;
  document.querySelector(id).scrollIntoView({
    behavior: "smooth",
    block: "center",
    inline: "nearest",
  });
};
export default {
  setup() {
    const lzj_shop_obj = inject("lzj_shop_obj");
    const lzj_one_class = (id) => {
      for (var i = 0; i < lzj_shop_obj.items_data.length; i++) {
        if (lzj_shop_obj.items_data[i].id == id) {
          lzj_shop_obj.items_data[i].show = !lzj_shop_obj.items_data[i].show;
        }
      }
    };
    const lzj_two_class_onclick = (id) => {
      lzj_shop_obj.menu.menu_show = false;
      gotoid(id);
    };
    return {
      lzj_shop_obj,
      lzj_one_class,
      lzj_two_class_onclick,
    };
  },
};
</script>

<style >
.lzj_shop_right_menu_one_class {
  position: relative;
  width: 100%;
  height: 80px;
  text-align: left;
  background-color: #ffffff;
  border-bottom: 3px solid #d8d8d8;
}

.lzj_shop_right_menu_one_class_title {
  position: relative;
  max-width: 70%;
  height: 40px;
  padding-top: 20px;
  padding-left: 20px;
  line-height: 40px;
  font-size: 30px;
  float: left;
}

.lzj_shop_right_menu_one_class_img {
  position: relative;
  margin-top: 20px;
  width: 10%;
  line-height: 40px;
  height: 40px;
  float: right;
  display: flex;
  justify-content: center;
  align-items: center;
}
.lzj_shop_right_menu_one_class_text {
  position: relative;
  width: 100%;
  height: 60px;
  line-height: 40px;
  padding-top: 10px;
  text-align: left;
  padding-left: 50px;
  overflow: hidden;
  font-size: 25px;
  border-bottom: 3px solid #d8d8d8;
}
</style>