<template>
  <div class="lzj_list_right_menu" v-if="lzj_shop_obj.menu.menu_show">
    <div class="lzj_list_right_menu_left" @click="lzj_chang_show">
      <img
        class="lzj_list_right_menu_left_img"
        :src="lzj_shop_obj.menu.back_img"
      />
    </div>
    <div class="lzj_list_right_menu_right">
      <lzjshoprightmenuoneclass></lzjshoprightmenuoneclass>
    </div>
    <div style="clear: both"></div>
  </div>
</template>

<script>
import { inject } from "@vue/runtime-core";
import lzjshoprightmenuoneclass from "@/components/lzj_shop_right_menu_one_class.vue";
export default {
  components: {
    lzjshoprightmenuoneclass,
  },
  emits: ["lzj_update_show"],
  setup(props, context) {
    const lzj_shop_obj = inject("lzj_shop_obj");
    const lzj_chang_show = () => {
      context.emit("lzj_update_show");
    };
    return {
      lzj_shop_obj,
      lzj_chang_show,
    };
  },
};
</script>

<style>
.lzj_list_right_menu {
  position: fixed;
  width: 100%;
  height: 100%;
  overflow: scroll;
  z-index: 999;
}

.lzj_list_right_menu_left {
  position: relative;
  width: 15%;
  height: 100%;
  float: left;
  opacity: 0.8;
  background-color: #424141;
  /* 盒布局居中 */
  display: flex;
  justify-content: center;
  align-items: center;
}

.lzj_list_right_menu_left_img {
  width: 60px;
  height: 60px;
}

.lzj_list_right_menu_right {
  position: relative;
  width: 85%;
  height: 100%;
  float: left;
  background-color: #ececec;
  overflow: scroll;
}
</style>