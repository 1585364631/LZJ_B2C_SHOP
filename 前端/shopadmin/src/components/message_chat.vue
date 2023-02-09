<template>
  <div class="lzj_shop_chat_message" v-if="props.lzj_message.from">
    <div class="lzj_shop_chat_message_text_img">
      <img :src="props.lzj_img.my" style="height: 50px; border-radius: 5px" />
    </div>
    <div class="lzj_shop_chat_message_text_sanjiao_me"></div>
    <div class="lzj_shop_chat_message_text">
      <div
        v-html="props.lzj_message.text.split('\n').join('<br />')"
        style="word-break: break-all"
      ></div>
    </div>

    <div style="clear: both"></div>
  </div>
  <div class="lzj_shop_chat_message" v-else>
    <div class="lzj_shop_chat_message_text_img" style="float: left">
      <img :src="props.lzj_img.you" style="height: 50px; border-radius: 5px" />
    </div>
    <div class="lzj_shop_chat_message_text_sanjiao_you"></div>
    <div
      class="lzj_shop_chat_message_text"
      style="float: left; background-color: #ffffff"
    >
      <div
        v-html="props.lzj_message.text.split('\n').join('<br />')"
        style="word-break: break-all"
      ></div>
    </div>

    <div style="clear: both"></div>
  </div>
</template>

<script>
export default {
  props: {
    lzj_message: {
      type: Object,
      default: {},
    },
    lzj_img: {
      type: Object,
      default: {},
    },
  },
  setup(props, context) {
    return { props };
  },
};
</script>

<style>
.lzj_shop_chat_message {
  position: relative;
  width: 92%;
  font-size: 17px;
  margin-left: 4%;
}

.lzj_shop_chat_message_text {
  position: relative;
  /* 消息样式 */
  /* width: 80%; */
  max-width: 80%;
  float: right;
  background-color: #12a5f3;
  text-align: left;
  border-radius: 5px;
  line-height: 30px;
  padding: 10px;
  padding-left: 20px;
  padding-right: 20px;
}

.lzj_shop_chat_message_text_img {
  position: relative;
  width: 10%;
  height: 50px;
  float: right;
}

.lzj_shop_chat_message_text_sanjiao_me {
  max-width: 6%;
  float: right;
  width: 30px;
  height: 15px;
  border: 0px;
  border-left: 20px solid #12a5f3;
  top: 15px;
  left: -1px;
  border-right: 3px solid transparent;
  border-top: 15px solid transparent;
  border-bottom: 15px solid transparent;
  position: relative;
}

.lzj_shop_chat_message_text_sanjiao_you {
  max-width: 6%;
  float: left;
  width: 30px;
  height: 15px;
  border: 0px;
  border-right: 20px solid #ffffff;
  top: 15px;
  right: -1px;
  border-left: 3px solid transparent;
  border-top: 15px solid transparent;
  border-bottom: 15px solid transparent;
  position: relative;
}

.lzj_shop_chat_span {
  display: inline-block;
  position: relative;
  width: 25px;
  height: 25px;
  top: 4px;
  background-size: 100% 100%;
}
</style>