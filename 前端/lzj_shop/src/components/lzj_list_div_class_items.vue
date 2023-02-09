<template>
  <div class="lzj_list_div_class_items">
    <div class="lzj_list_div_class_items_head">{{ props.item_title }}</div>
    <div class="lzj_list_div_class_items_body">
      <img
        style="position: relative; width: 100%; height: 100%"
        :src="item_img_url"
      />
    </div>
    <div class="lzj_list_div_class_items_foot">￥{{ props.item_price }}</div>
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

<style >
.lzj_list_div_class_items {
  width: 100%;
  height: 300px;
  position: relative;
}

.lzj_list_div_class_items_head {
  width: 100%;
  height: 10%;
  position: absolute;
  top: 0px;
  line-height: 30px;
  font-weight: bold;
  font-size: 24px;
  text-align: center;
  z-index: 1;
}

.lzj_list_div_class_items_body {
  width: 100%;
  height: 100%;
  z-index: 0;
  position: absolute;
}

.lzj_list_div_class_items_foot {
  width: 100%;
  height: 10%;
  position: absolute;
  bottom: 0px;
  z-index: 1;
  text-align: right;
  line-height: 30px;
  font-size: 25px;
  font-weight: bold;
  color: #eb0000;
  padding-right: 10px;
}
</style>