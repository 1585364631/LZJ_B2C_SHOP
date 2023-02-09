<template>
  <div style="position: relative; width: 100%; height: 80px"></div>
  <div class="lzj_shop_from_foot">
    {{ props.lzj_title }}
  </div>
</template>
<script>
import { useRouter } from "vue-router";
export default {
  props: {
    lzj_title: {
      type: String,
      default: "",
    },
  },
  setup(props, context) {
    return {
      props,
    };
  },
};
</script>
<style>
.lzj_shop_from_foot {
  position: fixed;
  width: 100%;
  bottom: 0;
  color: #feffff;
  line-height: 80px;
  font-size: 33px;
  height: 80px;
  z-index: 999;
  background-color: #c40000;
}
</style>