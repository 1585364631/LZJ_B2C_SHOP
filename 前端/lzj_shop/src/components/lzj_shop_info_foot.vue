<template>
  <div>
    <div class="lzj_shop_info_foot_box">
      <div class="lzj_shop_info_foot_box_button">
        <router-link :to="{ name: 'chat', query: { shopid: id } }">
          <img
            class="lzj_shop_info_foot_box_button_img"
            :src="lzj_shop_obj.foot_nav.small[0].img"
          />
          <div class="lzj_shop_info_foot_box_button_text">
            {{ lzj_shop_obj.foot_nav.small[0].title }}
          </div>
        </router-link>
      </div>
      <div
        class="lzj_shop_info_foot_box_button"
        @click="lzj_change_show"
        v-if="lzj_shop_obj.foot_nav.add"
      >
        <div>
          <img
            class="lzj_shop_info_foot_box_button_img"
            :src="lzj_shop_obj.foot_nav.small[2].img"
          />
          <div class="lzj_shop_info_foot_box_button_text">
            {{ lzj_shop_obj.foot_nav.small[2].title }}
          </div>
        </div>
      </div>
      <div
        class="lzj_shop_info_foot_box_button"
        v-else
        @click="lzj_change_show"
      >
        <div>
          <img
            class="lzj_shop_info_foot_box_button_img"
            :src="lzj_shop_obj.foot_nav.small[1].img"
          />
          <div class="lzj_shop_info_foot_box_button_text">
            {{ lzj_shop_obj.foot_nav.small[1].title }}
          </div>
        </div>
      </div>
      <div
        class="lzj_shop_info_foot_box_big_button"
        :style="{ background: lzj_shop_obj.foot_nav.big[0].color }"
      >
        <div
          class="lzj_shop_info_foot_box_big_button_text"
          @click="lzj_add_cart"
        >
          {{ lzj_shop_obj.foot_nav.big[0].title }}
        </div>
      </div>
      <router-link :to="{ name: 'from', params: { shopid: id } }">
        <div
          class="lzj_shop_info_foot_box_big_button"
          :style="{ background: lzj_shop_obj.foot_nav.big[1].color }"
        >
          <div class="lzj_shop_info_foot_box_big_button_text">
            {{ lzj_shop_obj.foot_nav.big[1].title }}
          </div>
        </div>
      </router-link>
    </div>
    <div class="lzj_shop_info_foot_box_foot"></div>
  </div>
</template>

<script>
import { reactive, ref } from "@vue/reactivity";
import { watch, watchEffect } from "@vue/runtime-core";
export default {
  props: {
    lzj_shop_obj: {
      type: Object,
      defalut: {},
    },
    id: {},
  },
  emits: ["lzj_update_show", "lzj_add_cart"],
  setup(props, context) {
    const lzj_shop_obj = props.lzj_shop_obj;
    const lzj_change_show = () => {
      context.emit("lzj_update_show");
    };
    const lzj_add_cart = () => {
      context.emit("lzj_add_cart");
    };
    const id = ref(props.id);

    return {
      props,
      lzj_shop_obj,
      lzj_change_show,
      id,
      lzj_add_cart,
    };
  },
};
</script>

<style >
.lzj_shop_info_foot_box {
  width: 100%;
  height: 90px;
  position: fixed;
  bottom: 0;
  background-color: #ffffff;
  z-index: 99999;
}

.lzj_shop_info_foot_box_button {
  width: 13%;
  height: 100%;
  position: relative;
  float: left;
  background-color: #ffffff;
  border-top: 2px solid #e9e9e9;
  border-bottom: 2px solid #e9e9e9;
  border-left: 2px solid #e9e9e9;
  display: flex;
  justify-content: center;
  align-items: center;
}
.lzj_shop_info_foot_box_big_button {
  width: 37%;
  height: 100%;
  position: relative;
  float: left;
  background-color: #ff9900;
  display: flex;
  justify-content: center;
  align-items: center;
}

.lzj_shop_info_foot_box_button_img {
  position: relative;
  height: 30px;
}

.lzj_shop_info_foot_box_button_text {
  position: relative;
  line-height: 20px;
  height: 20px;
  font-size: 20px;
}

.lzj_shop_info_foot_box_big_button_text {
  position: relative;
  height: 45px;
  line-height: 45px;
  font-size: 30px;
  color: #fffaf5;
}

.lzj_shop_info_foot_box_foot {
  width: 100%;
  height: 90px;
  position: relative;
}
</style>