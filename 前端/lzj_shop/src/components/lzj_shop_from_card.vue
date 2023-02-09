<template>
  <div class="lzj_shop_from_line2 lzj_shop_from_div2">
    <div class="lzj_shop_from_id">订单编号：{{ props.from_card.id }}</div>
    <div class="lzj_shop_from_body">
      <div class="lzj_shop_from_body_left">
        <img :src="props.from_card.img" style="width: 95%" />
      </div>
      <div class="lzj_shop_from_body_right">
        {{ props.from_card.title }}
      </div>
    </div>
    <div style="clear: both"></div>
    <div class="lzj_shop_from_price">
      价格：￥{{
        Math.round(props.from_card.price * props.from_card.sum * 100) / 100
      }}
    </div>
    <div class="lzj_shop_from_price_right" v-if="props.in_index">
      <div class="lzj_shop_from_price_right_div" @click="cut">
        <img style="width: 36px" src="http://static.000081.xyz/img/cutsum.png" />
      </div>
      <input
        type="text"
        class="lzj_shop_from_price_right_text"
        v-model.number="props.from_card.sum"
        maxlength="6"
        @input="yesno($event)"
      />
      <div class="lzj_shop_from_price_right_div" @click="add">
        <img style="width: 36px" src="http://static.000081.xyz/img/addsum.png" />
      </div>
    </div>
  </div>
</template>

<script>
import { watch } from "@vue/runtime-core";
import shopfromid from "@/function/shop_from_id";

export default {
  props: {
    from_card: {
      type: Object,
      defalut: {},
    },
    in_index: {
      type: Boolean,
      defalut: true,
    },
  },
  setup(props, context) {
    const add = () => {
      props.from_card.sum++;
    };
    const cut = () => {
      if (props.from_card.sum == 1) {
        return;
      }
      props.from_card.sum--;
    };
    const yesno = (e) => {
      if (props.from_card.sum <= 0) {
        props.from_card.sum = 1;
        return;
      }
      context.emit("lzj_update_sum", props.from_card.sum);
    };

    return {
      props,
      add,
      cut,
      yesno,
    };
  },
};
</script>

<style>
.lzj_shop_from_line2 {
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

.lzj_shop_from_div2 {
  padding-top: 15px;
  margin-left: 3.5%;
  line-height: 40px;
  padding-bottom: 15px;
  position: relative;
}

.lzj_shop_from_id {
  font-weight: bold;
  border-bottom: 3px solid #e3e3e3;
  padding-bottom: 10px;
}

.lzj_shop_from_price {
  font-weight: bold;
  border-top: 3px solid #e3e3e3;
  padding-top: 20px;
  margin-top: 10px;
  line-height: 40px;
  margin-bottom: 5px;
  font-size: 30px;
  color: #b30000;
}

.lzj_shop_from_price_right {
  position: absolute;
  height: 40px;
  right: 20px;
  bottom: 20px;
  text-align: center;
}

.lzj_shop_from_price_right_div {
  position: relative;
  width: 36px;
  height: 36px;
  border: 2px solid #efefef;
  float: left;
  background-color: #ffffff;
}

.lzj_shop_from_price_right_text {
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

.lzj_shop_from_body {
  position: relative;
  margin-top: 10px;
}

.lzj_shop_from_body_left {
  position: relative;
  width: 20%;
  display: flex;
  justify-content: center;
  float: left;
  align-items: center;
}

.lzj_shop_from_body_right {
  padding-top: 10px;
  padding-left: 10px;
  float: left;
  position: relative;
  width: 80%;
}
</style>