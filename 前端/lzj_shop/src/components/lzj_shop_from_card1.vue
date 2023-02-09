<template>
  <div class="lzj_shop_from_card1_box">
    <div class="lzj_shop_from_card1_div" :style="{ border: props.bordercolor }">
      <div class="lzj_shop_from_card1_div_leftright">
        <img
          src="http://static.000081.xyz/img/sc_location_logo.png"
          style="height: 35px; width: 28px"
        />
      </div>
      <div class="lzj_shop_from_card1_div_box">
        <div class="lzj_shop_from_card1_div_box_title">
          {{ people }}：{{ name }}
        </div>
        <div class="lzj_shop_from_card1_div_box_phone">{{ phone }}</div>
        <div style="clear: both"></div>
        <div>收货地址：{{ address }}</div>
      </div>
      <div class="lzj_shop_from_card1_div_leftright" @click="lzj_update_fn">
        <img
          src="http://static.000081.xyz/img/sc_9_r.png"
          style="height: 30px; width: 20px"
        />
      </div>
    </div>
  </div>
</template>

<script>
import { ref } from "@vue/reactivity";
export default {
  props: {
    base_info: {
      type: Object,
      defalut: {},
    },
    bordercolor: {
      type: String,
      default: "",
    },
  },
  setup(props, context) {
    const name = ref(props.base_info[0].value);
    const people = ref(props.base_info[0].tip);
    const phone = ref(props.base_info[1].value);
    const address = ref(props.base_info[2].value + props.base_info[3].value);
    const lzj_update_fn = () => {
      context.emit("lzj_update_fn");
    };
    return {
      name,
      lzj_update_fn,
      props,
      address,
      phone,
      people,
    };
  },
};
</script>

<style>
.lzj_shop_from_card1_box {
  display: flex;
}

.lzj_shop_from_card1_div {
  position: relative;
  width: 93%;
  border-radius: 10px;
  background-color: #ffffff;
  min-height: 140px;
  text-align: left;
  font-size: 25px;
  line-height: 70px;
  margin-top: 15px;
  margin-left: 3.5%;
  line-height: 40px;
}

.lzj_shop_from_card1_div_leftright {
  position: relative;
  width: 10%;
  height: 100%;
  float: left;
  display: flex;
  justify-content: center;
  align-items: center;
}

.lzj_shop_from_card1_div_box {
  position: relative;
  width: 80%;
  float: left;
  height: 100%;
  padding-top: 20px;
  padding-bottom: 20px;
}

.lzj_shop_from_card1_div_box_title {
  position: relative;
  max-width: 50%;
  overflow: hidden;
  font-size: 28px;
  float: left;
  color: #353535;
  font-weight: bold;
}
.lzj_shop_from_card1_div_box_phone {
  margin-top: 8px;
  position: relative;
  max-width: 50%;
  overflow: hidden;
  float: right;
  font-size: 26px;
}
</style>