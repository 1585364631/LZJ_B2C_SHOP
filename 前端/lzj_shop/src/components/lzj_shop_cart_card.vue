<template>
  <div class="lzj_shop_cart_card">
    <div class="lzj_shop_cart_card_check">
      <img
        v-if="props.shop_info.check"
        :src="props.checkimg.oncheck"
        class="lzj_shop_cart_card_check_img"
      />
      <img
        v-else
        :src="props.checkimg.nocheck"
        class="lzj_shop_cart_card_check_img"
      />
    </div>
    <div class="lzj_shop_cart_card_right" @click.stop="lzj_shop_cart_card_goto">
      <div class="lzj_shop_cart_card_right_img">
        <img :src="props.shop_info.img" style="width: 100%; height: 100%" />
      </div>
      <div class="lzj_shop_cart_card_right_text">
        {{ props.shop_info.title }}
        <br /><br />
        单价：￥{{ props.shop_info.price }}
      </div>
    </div>
    <div style="clear: both"></div>
    <div class="lzj_shop_cart_card_foot">
      <div class="lzj_shop_cart_card_foot_price">
        ￥{{ props.shop_info.sum }}
      </div>
      合计：
    </div>
    <div class="lzj_shop_cart_card_price_right">
      <div class="lzj_shop_cart_card_price_right_div" @click.stop="cut">
        <img style="width: 36px" :src="props.addcut.cut" />
      </div>
      <input
        type="text"
        class="lzj_shop_cart_card_price_right_text"
        v-model.number="props.shop_info.number"
        @click.stop=""
        maxlength="6"
        @input="yesno($event)"
      />
      <div class="lzj_shop_cart_card_price_right_div" @click.stop="add">
        <img style="width: 36px" :src="props.addcut.add" />
      </div>
    </div>
  </div>
</template>

<script>
import { useRouter } from "vue-router";
export default {
  props: {
    shop_info: {
      type: Object,
      default: {},
    },
    checkimg: {
      type: Object,
      default: {},
    },
    addcut: {
      type: Object,
      default: {},
    },
    index: {
      type: Number,
      default: 0,
    },
  },
  setup(props, context) {
    const router = useRouter();
    const add = () => {
      props.shop_info.number++;
      lzj_update();
    };
    const cut = () => {
      if (props.shop_info.number == 1) {
        return;
      }
      props.shop_info.number--;
      lzj_update();
    };
    const yesno = (e) => {
      if (props.shop_info.number <= 0) {
        props.shop_info.number = 1;
        return;
      }
      lzj_update();
    };

    const lzj_shop_cart_card_goto = () => {
      router.push({
        name: "info",
        params: { shopid: props.shop_info.id },
      });
    };

    const lzj_update = () => {
      context.emit("lzj_update_cart_card", props.shop_info, props.index);
    };
    return {
      props,
      add,
      cut,
      yesno,
      lzj_shop_cart_card_goto,
    };
  },
};
</script>

<style>
.lzj_shop_cart_card {
  position: relative;
  width: 100%;
  min-height: 250px;
  background-color: #ffffff;
  border-bottom: 3px solid #eaeaea;
}

.lzj_shop_cart_card_check {
  position: relative;
  width: 10%;
  height: 100%;
  float: left;
}

.lzj_shop_cart_card_check_img {
  position: relative;
  width: 60%;
  margin-top: 60px;
  border-radius: 50%;
}

.lzj_shop_cart_card_right {
  position: relative;
  width: 90%;
  height: 160px;
  padding-top: 20px;
  padding-bottom: 20px;
  float: right;
  text-align: left;
}

.lzj_shop_cart_card_right_img {
  height: 120px;
  position: relative;
  width: 20%;
  float: left;
}

.lzj_shop_cart_card_right_text {
  position: relative;
  line-height: 30px;
  width: 80%;
  float: right;
  padding-left: 15px;
  padding-right: 15px;
  font-size: 25px;
}

.lzj_shop_cart_card_foot {
  position: relative;
  width: 100%;
  text-align: left;
  height: 70px;
  padding-left: 10%;
  line-height: 70px;
  font-size: 25px;
  float: left;
  color: red;
}

.lzj_shop_cart_card_foot_price {
  position: absolute;
  height: 70px;
  line-height: 70px;
  font-weight: bold;
  float: left;
  font-size: 28px;
  z-index: 999;
  margin-left: 12%;
}

.lzj_shop_cart_card_price_right {
  position: absolute;
  height: 40px;
  right: 20px;
  bottom: 20px;
  text-align: center;
}

.lzj_shop_cart_card_price_right_div {
  position: relative;
  width: 36px;
  height: 36px;
  border: 2px solid #efefef;
  float: left;
  background-color: #ffffff;
}

.lzj_shop_cart_card_price_right_text {
  max-width: 120px;
  float: left;
  position: relative;
  overflow: hidden;
  height: 40px;
  text-align: center;
  line-height: 40px;
  font-size: 28px;
  background-color: #efefef;
}
</style>