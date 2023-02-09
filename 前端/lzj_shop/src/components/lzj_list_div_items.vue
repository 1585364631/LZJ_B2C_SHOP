<template>
  <div class="lzj_list_div_item">
    <div class="lzj_list_div_item_img">
      <img
        style="position: relative; width: 100%; height: 100%"
        :src="item_img_url"
      />
    </div>
    <div class="lzj_list_div_item_text">
      <div style="font-weight: bold; overflow: hidden">
        {{ props.item_title }}
      </div>
      <div style="font-weight: bold; color: #c70e0e; overflow: hidden">
        ￥{{ props.item_price }}
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent } from "@vue/runtime-core";

export default defineComponent({
  props: {
    item_title: {
      type: String,
      default: "美丽奇迹面膜",
    },
    item_img_url: {
      type: String,
      default: "http://static.000081.xyz/img/lunbo.png",
    },
    item_url: {
      type: String,
      default: "",
    },
    item_price: {
      type: String,
      default: "1200",
    },
  },
  setup(props, context) {
    return {
      props,
    };
  },
});
</script>

<style>
.lzj_list_div_item {
  width: 100%;
  height: 320px;
  position: relative;
  padding: 10px;
}
.lzj_list_div_item_img {
  position: relative;
  width: 100%;
  height: 70%;
}

.lzj_list_div_item_text {
  position: relative;
  width: 100%;
  height: 25%;
  margin-top: 5%;
  line-height: 40px;
  text-align: center;
  font-size: 24px;
}
</style>