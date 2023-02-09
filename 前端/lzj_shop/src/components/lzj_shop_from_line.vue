<template>
  <input
    type="text"
    class="lzj_shop_from_line"
    :placeholder="props.base_info.tip"
    v-model="props.base_info.value"
  />
</template>

<script>
import { watch } from "@vue/runtime-core";
export default {
  props: {
    index: {
      type: Number,
      defalut: 0,
    },
    base_info: {
      type: Object,
      defalut: {},
    },
  },
  setup(props, context) {
    watch(
      () => props.base_info,
      (val) => {
        context.emit("lzj_update_input", props.base_info, props.index);
      },
      {
        deep: true,
      }
    );
    return {
      props,
    };
  },
};
</script>

<style>
.lzj_shop_from_line {
  width: 93%;
  padding-left: 20px;
  border-radius: 10px;
  background-color: #ffffff;
  min-height: 70px;
  text-align: left;
  font-size: 25px;
  line-height: 70px;
  margin-top: 15px;
}
</style>