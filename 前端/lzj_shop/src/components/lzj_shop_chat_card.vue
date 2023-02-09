<template>
  <div class="lzj_shop_chat_card">
    <div class="lzj_shop_chat_card_head">
      <div class="lzj_shop_chat_card_head_left">
        <img style="width: 100%" :src="props.lzj_shop_info.img" />
      </div>
      <div class="lzj_shop_chat_card_head_right">
        {{ props.lzj_shop_info.title }}
        <br />
        <div style="color: red; margin-top: 15px">
          单价：￥{{ props.lzj_shop_info.price }}
        </div>
      </div>
      <div style="clear: both"></div>
      <div
        class="lzj_shop_chat_card_head_foot"
        @click.stop="lzj_send_baby_link"
      >
        发送宝贝链接
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    lzj_shop_info: {
      type: Object,
      default: {},
    },
  },
  setup(props, context) {
    const lzj_send_baby_link = () => {
      context.emit("lzj_shop_change_card_show");
    };
    return {
      props,
      lzj_send_baby_link,
    };
  },
};
</script>

<style >
.lzj_shop_chat_card {
  position: relative;
  width: 100%;
  background: #ffffff;
}

.lzj_shop_chat_card_head {
  position: relative;
  padding-top: 20px;
  padding-bottom: 10px;
  width: 90%;
  margin-left: 5%;
}

.lzj_shop_chat_card_head_left {
  position: relative;
  width: 20%;
  float: left;
  height: 100%;
}
.lzj_shop_chat_card_head_right {
  position: relative;
  text-align: left;
  width: 75%;
  float: left;
  height: 100%;
  margin-left: 2.5%;
  font-size: 26px;
}

.lzj_shop_chat_card_head_foot {
  margin-top: 30px;
  position: relative;
  margin-left: 15%;
  font-size: 25px;
  line-height: 50px;
  color: #2fb0f4;
  border-radius: 5px;
  width: 70%;
  border: 1px solid #2fb0f4;
  font-weight: bold;
}
</style>