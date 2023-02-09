<template>
  <div>
    <div class="lzj_shop_chat_foot">
      <div class="lzj_shop_chat_foot_left_img" @click="lzjupdateshow(true)">
        <div class="lzj_shop_chat_foot_left_img_border">
          <img :src="props.lzj_shop_emoji.open" style="width: 30px" />
        </div>
      </div>
      <div class="lzj_shop_chat_foot_center_text">
        <textarea
          class="lzj_shop_chat_foot_center_textarea"
          v-model="lzj_textarea_text"
          ref="lzj_textarea"
        ></textarea>
      </div>
      <div
        class="lzj_shop_chat_foot_center_send"
        @click="lzj_send_message"
        v-if="lzj_textarea_text != ''"
      >
        发送
      </div>
      <div
        class="lzj_shop_chat_foot_center_send"
        style="background-color: #cdcdce; color: #a1a3a6"
        v-else
      >
        发送
      </div>
      <div style="clear: both"></div>
      <div v-if="lzjemojishow">
        <div class="lzj_shop_chat_foot_foot">
          <div
            class="lzj_shop_chat_foot_foot_close"
            :style="{
              'background-image': `url(${props.lzj_shop_emoji.close})`,
            }"
            @click="lzjupdateshow(false)"
          ></div>
          <div
            v-for="(i, index) in props.lzj_shop_emoji.data"
            :key="index"
            @click="lzj_message_emoji_click(i)"
          >
            <div
              class="lzj_shop_chat_foot_foot_close"
              :style="{ 'background-image': `url(${i.img})` }"
              v-if="i.open"
            ></div>
          </div>
        </div>
      </div>
    </div>
    <div
      v-if="!lzjemojishow"
      style="position: relative; width: 100%; height: 80px"
    ></div>
    <div v-else style="position: relative; width: 100%; height: 300px"></div>
    <div
      id="lzj_shop_chat_foot_id"
      style="position: relative; width: 100%"
    ></div>
  </div>
</template>

<script>
import { ref } from "@vue/reactivity";
import { onMounted, watch } from "@vue/runtime-core";
var gotobottom = (id) => {
  setTimeout(() => {
    document.querySelector(id).scrollIntoView({
      behavior: "smooth",
      block: "center",
      inline: "nearest",
    });
  }, 100);
};
export default {
  props: {
    lzj_shop_emoji: {
      type: Object,
      default: {},
    },
  },

  setup(props, context) {
    const lzjemojishow = ref(false);
    const lzj_textarea = ref(null);
    const lzj_textarea_text = ref("");
    const lzjupdateshow = (i) => {
      lzjemojishow.value = i;
      gotobottom("#lzj_shop_chat_foot_id");
    };
    onMounted(() => {
      gotobottom("#lzj_shop_chat_foot_id");
    });
    const lzj_send_message = () => {
      var cache = {
        from: true,
        text: lzj_textarea_text.value,
      };
      lzj_textarea_text.value = "";
      context.emit("lzj_shop_update_message", cache);
      gotobottom("#lzj_shop_chat_foot_id");
    };
    const lzj_message_emoji_click = (i) => {
      lzj_textarea_text.value = lzj_textarea_text.value + i.code;
      setTimeout(() => {
        lzj_textarea.value.focus();
      }, 100);
    };

    watch(
      () => lzj_textarea_text.value,
      () => {
        setTimeout(() => {
          lzj_textarea.value.style.height = "";
          lzj_textarea.value.style.height =
            lzj_textarea.value.scrollHeight + "px";
        }, 110);
      }
    );
    return {
      lzjemojishow,
      props,
      lzjupdateshow,
      lzjupdateshow,
      lzj_textarea,
      lzj_textarea_text,
      lzj_send_message,
      lzj_message_emoji_click,
    };
  },
};
</script>

<style>
.lzj_shop_chat_foot {
  position: fixed;
  width: 100%;
  bottom: 0;
  background-color: #ffffff;
  z-index: 999;
  max-width: 640px;
}

.lzj_shop_chat_foot_left_img {
  position: relative;
  width: 12%;
  margin-left: 1%;
  height: 80px;
  display: flex;
  justify-content: center;
  align-items: center;
  float: left;
}

.lzj_shop_chat_foot_left_img_border {
  border: 1px solid #12a5f3;
  display: flex;
  justify-content: center;
  padding: 10px;
  border-radius: 5px;
  align-items: center;
}

.lzj_shop_chat_foot_center_text {
  border-radius: 5px;
  position: relative;
  float: left;
  min-height: 55px;
  margin-top: 12.5px;
  margin-bottom: 12.5px;
  width: 63%;
  background-color: #ececec;
}

.lzj_shop_chat_foot_center_textarea {
  position: relative;
  width: 100%;
  padding-left: 10px;
  white-space: pre-line;
  padding-right: 10px;
  padding-top: 13px;
  padding-bottom: 12px;
  line-height: 30px;
  height: 55px;
  border: none;
  font-size: 25px;
  resize: none;
  background-color: #ececec;
  max-height: 400px;
}

.lzj_shop_chat_foot_center_send {
  position: relative;
  float: right;
  width: 18%;
  font-size: 25px;
  line-height: 55px;
  color: #12a5f3;
  height: 55px;
  margin-right: 3%;
  border-radius: 5px;
  border: 1px solid #1ba8f3;
  margin-top: 15px;
}

.lzj_shop_chat_foot_foot {
  border-top: 3px solid #ebebeb;
  position: relative;
  padding-top: 10px;
  padding-bottom: 10px;
  display: flex;
  justify-content: center;
  align-items: center;
  /* justify-content: space-evenly; */
  flex-wrap: wrap;
  overflow: scroll;
  max-height: 220px;
  flex-direction: row-reverse;
}

.lzj_shop_chat_foot_foot_close {
  position: relative;
  float: right;
  width: 55px;
  height: 55px;
  background-size: 100% 100%;
  background-image: url("http://static.000081.xyz/img/emoji/ok.png");
  margin-bottom: 10px;
  margin-left: 5px;
  margin-right: 5px;
}
</style>