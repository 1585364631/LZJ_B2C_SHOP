<template>
  <div class="lzj_shop_home_head">
    <lzjshopsearchdiv></lzjshopsearchdiv>
    <div class="lzj_shop_home_head_lunbo" ref="lzj_shop_home_head_lunbo">
      <lzjlunbozj
        :index="index"
        :show_li="true"
        :show_right="true"
        :goto_state="true"
      ></lzjlunbozj>
    </div>
    <div class="lzj_shop_home_head_body">
      <div class="lzj_shop_home_head_body_img">
        <img
          class="lzj_shop_home_head_body_img1"
          :src="lzj_shop_obj.head_img"
        />
      </div>
      <div class="lzj_shop_home_head_body_div">
        <div style="font-weight: bold">{{ lzj_shop_obj.head_name }}</div>
        <div>
          <img
            :src="lzj_shop_obj.wx_img"
            class="lzj_shop_home_head_body_img2"
          />
          <span style="font-size: 20px">{{ lzj_shop_obj.email }}</span>
        </div>
      </div>
    </div>
    <div
      v-if="lzj_shop_obj.guanggao.guanggao_open"
      class="lzj_shop_home_head_gg"
    >
      <div class="lzj_shop_home_head_gg_text">
        {{ lzj_shop_obj.guanggao.guanggao_text }}
      </div>
      <div class="lzj_shop_home_head_gg_div">
        <div class="lzj_shop_home_head_gg_div_img">
          <img
            style="position: relative; width: 100%; height: 100%"
            :src="lzj_shop_obj.guanggao.guanggao_img"
          />
        </div>
        <div class="lzj_shop_home_head_gg_div_text">
          {{ lzj_shop_obj.guanggao.guanggao_img_text }}
        </div>
        <a :href="lzj_shop_obj.guanggao.guanggao_src">
          <div class="lzj_shop_home_head_gg_div_buttom">点击领取></div></a
        >
      </div>
    </div>
  </div>
</template>

<script>
import lzjshopsearchdiv from "@/components/lzj_shop_search_div.vue";
import lzjlunbozj from "@/components/lzj_lunbo_zujian.vue";
import { ref } from "@vue/reactivity";
import { inject, onMounted, provide } from "@vue/runtime-core";

export default {
  components: { lzjshopsearchdiv, lzjlunbozj },
  setup() {
    const lzj_shop_obj = inject("lzj_shop_obj");
    const index = ref(0);
    const lzj_lunbo_data = lzj_shop_obj.lunbo_data;

    var timer;
    const next = () => {
      clearInterval(timer);
      index.value++;
      if (index.value >= lzj_lunbo_data.length) {
        index.value = 0;
      }
      timer_up();
    };
    const last = () => {
      clearInterval(timer);
      index.value--;
      if (index.value < 0) {
        index.value = lzj_lunbo_data.length - 1;
      }
      timer_up();
    };

    const timer_up = () => {
      timer = setInterval(() => {
        next();
      }, 3000);
    };
    timer_up();
    // 监听鼠标移动
    const inright = () => {
      next();
    };
    const inleft = () => {
      last();
    };
    provide("lzj_lunbo_data", lzj_lunbo_data);
    return {
      index,
      lzj_shop_obj,
      inleft,
      inright,
    };
  },
};
</script>

<style>
.lzj_shop_home_head {
  width: 100%;
  height: auto;
  position: relative;
}

.lzj_shop_home_head_lunbo {
  overflow: hidden;
  width: 100%;
  height: 300px;
  position: relative;
}

.lzj_shop_home_head_body {
  width: 100%;
  height: 150px;
  position: relative;
  background-color: #ffffff;
}

.lzj_shop_home_head_body_div {
  position: relative;
  width: auto;
  height: auto;
  margin-left: 220px;
  line-height: 30px;
  padding-top: 15px;
  font-size: 25px;
  text-align: left;
}

.lzj_shop_home_head_body_img {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  position: absolute;
  left: 70px;
  top: -20px;
  overflow: hidden;
}
.lzj_shop_home_head_body_img1 {
  position: relative;
  width: 100%;
  height: 100%;
}

.lzj_shop_home_head_body_img2 {
  position: relative;
  top: 5px;
  width: 30px;
  height: 25px;
}

.lzj_shop_home_head_gg {
  border-top: 3px solid #eaeaea;
  position: relative;
  width: 100%;
  padding-bottom: 20px;
}

.lzj_shop_home_head_gg_text {
  width: 90%;
  position: relative;
  margin-left: 5%;
  font-weight: bold;
  text-align: left;
  font-size: 24px;
  padding: 20px;
}

.lzj_shop_home_head_gg_div {
  width: 96%;
  position: relative;
  margin-left: 2%;
  background-color: #c40000;
  height: 80px;
}

.lzj_shop_home_head_gg_div_img {
  width: 50px;
  height: 50px;
  position: relative;
  overflow: hidden;
  float: left;
  left: 15px;
  top: 15px;
}

.lzj_shop_home_head_gg_div_text {
  width: auto;
  position: relative;
  height: 40px;
  font-size: 23px;
  float: left;
  top: 20px;
  margin-left: 25px;
  line-height: 40px;
  max-width: 70%;
  color: white;
}

.lzj_shop_home_head_gg_div_buttom {
  width: 150px;
  height: 50px;
  float: right;
  position: relative;
  right: 15px;
  top: 15px;
  font-size: 24px;
  color: #c40000;
  font-weight: bold;
  line-height: 50px;
  background-color: #fff013;
}
</style>
