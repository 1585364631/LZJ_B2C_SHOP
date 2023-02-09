<template>
  <div class="lzj_shop_confirm_line">
    <div class="lzj_shop_confirm_line_title">
      {{ props.lzj_shop_obj.title }} ：
    </div>
    <div class="lzj_shop_confirm_line_text" v-if="props.lzj_shop_obj.price">
      {{ props.lzj_shop_obj.text }}元
    </div>
    <div class="lzj_shop_confirm_line_text" v-else>
      {{ props.lzj_shop_obj.text }}
    </div>
    <div style="clear: both"></div>
  </div>
</template>

<script>
export default {
  props: {
    lzj_shop_obj: {
      type: Object,
      defaluf: {},
    },
  },
  setup(props) {
    return {
      props,
    };
  },
};
</script>

<style >
.lzj_shop_confirm_line {
  position: relative;
  text-align: left;
  width: 100%;
  margin-bottom: 5px;
}

.lzj_shop_confirm_line_title {
  width: 25%;
  font-size: 25px;
  position: relative;
  float: left;
  color: #a4a4a4;
}

.lzj_shop_confirm_line_text {
  font-size: 25px;
  max-width: 75%;
  position: relative;
  float: left;
}
</style>