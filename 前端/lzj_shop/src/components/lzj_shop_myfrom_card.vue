<template>
  <div class="lzj_shop_myfrom_card">
    <div class="lzj_shop_myfrom_card_img">
      <img :src="props.myfrom.img" style="width: 75%" />
    </div>
    <div class="lzj_shop_myfrom_card_text">
      <div class="lzj_shop_myfrom_card_text_price">
        价格：<span style="color: #d55c62; font-weight: bold"
          >￥{{ props.myfrom.price }}</span
        >
      </div>
      <div class="lzj_shop_myfrom_card_text_title">
        订号：{{ props.myfrom.id }}
      </div>
      <div class="lzj_shop_myfrom_card_text_text">
        状态：{{ props.myfrom.status }}
      </div>
      <div class="lzj_shop_myfrom_card_text_text">
        时间：{{ props.myfrom.time }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    myfrom: {
      type: Object,
      default: {},
    },
  },
  setup(props) {
    return {
      props,
    };
  },
};
</script>

<style>
.lzj_shop_myfrom_card {
  position: relative;
  width: 100%;
  height: 200px;
  background-color: #f7f7f7;
  border-bottom: 5px solid #e9e9e9;
}

.lzj_shop_myfrom_card_img {
  position: relative;
  width: 30%;
  height: 100%;
  float: left;
  display: flex;
  justify-content: center;
  align-items: center;
}

.lzj_shop_myfrom_card_text {
  position: relative;
  float: left;
  padding-top: 30px;
  text-align: left;
  padding-bottom: 30px;
  width: 70%;
  height: 100%;
}

.lzj_shop_myfrom_card_text_title {
  position: relative;
  font-weight: bold;
  max-width: 100%;
  overflow: hidden;
  font-size: 25px;
  line-height: 30px;
  margin-bottom: 10px;
}

.lzj_shop_myfrom_card_text_text {
  position: relative;
  margin-top: 20px;
  font-size: 25px;
  line-height: 30px;
  width: 100%;
  overflow: hidden;
  color: #676767;
}

.lzj_shop_myfrom_card_text_price {
  position: absolute;
  line-height: 30px;
  font-size: 25px;
  right: 45px;
  margin-top: 50px;
}
</style>