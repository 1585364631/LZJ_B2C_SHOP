<template>
  <div class="lzj_message_div" v-if="props.lzj_massage_obj.show">
    <div class="lzj_message_div_text">{{ props.lzj_massage_obj.title }}</div>
  </div>
</template>

<script>
import { watch } from "@vue/runtime-core";
export default {
  props: {
    lzj_massage_obj: {
      type: Object,
      defalut: {},
    },
  },
  setup(props, context) {
    watch(
      () => props.lzj_massage_obj,
      () => {
        if (props.lzj_massage_obj.show) {
          setTimeout(() => {
            context.emit("lzj_update_massage");
          }, props.lzj_massage_obj.showtime * 1000);
        }
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
.lzj_message_div {
  width: 100%;
  position: fixed;
  bottom: 120px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.lzj_message_div_text {
  width: auto;
  position: relative;
  margin: 15px;
  line-height: 28px;
  border-radius: 15px;
  padding: 10px;
  color: white;
  font-size: 25px;
  background-color: #202023;
  opacity: 0.5;
}
</style>