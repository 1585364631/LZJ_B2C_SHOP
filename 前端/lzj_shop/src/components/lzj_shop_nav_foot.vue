<template>
  <div class="lzj_shop_nav_foot">
    <div
      class="lzj_shop_nav_foot_div"
      v-for="(i, inde) in props.foot_nav"
      :key="inde"
    >
      <div class="lzj_shop_nav_foot_div_box">
        <router-link :to="i.url">
          <div class="lzj_shop_nav_foot_div_img">
            <img
              style="position: relative; width: 100%; height: 100%"
              :src="i.img_url"
            />
          </div>
          <div class="lzj_shop_nav_foot_div_text">{{ i.title }}</div>
        </router-link>
      </div>
    </div>
    <div style="clear: both"></div>
  </div>
  <!-- 强制校准位置 -->
  <div style="position: relative; width: 100%; height: 80px"></div>
</template>

<script>
export default {
  props: {
    foot_nav: {
      type: Object,
      default: {},
    },
  },
  setup(props, context) {
    return {
      props,
    };
  },
};
</script>

<style>
.lzj_shop_nav_foot {
  position: fixed;
  max-width: 640px;
  width: 100%;
  height: 80px;
  background-color: #ffffff;
  padding-left: 1%;
  bottom: 0px;
  z-index: 99;
}
.lzj_shop_nav_foot_div {
  position: relative;
  width: 33.3%;
  height: 100%;
  float: left;
  text-align: center;
  border-right: 3px solid #f4f3f3;
  /* 盒布局居中 */
  display: flex;
  justify-content: center;
  align-items: center;
}

.lzj_shop_nav_foot_div_box {
  position: absolute;
  width: auto;
}

.lzj_shop_nav_foot_div_img {
  width: 25px;
  height: 25px;
  position: relative;
  float: left;
}

.lzj_shop_nav_foot_div_text {
  text-align: left;
  float: left;
  position: relative;
  max-height: 70%;
  overflow: hidden;
  height: 25px;
  font-size: 23px;
  line-height: 25px;
  margin-left: 10px;
}
</style>