<template>
  <div class="lzj_index_nav">
    <div class="lzj_index_nav_imgdiv">
      <img :src="props.nav_data.nav_img_src" @click="inshow" />
    </div>
    <div style="clear: both"></div>
    <div class="lzj_index_nav_jian" v-if="lzj_show_nav"></div>
    <div style="clear: both"></div>
    <div v-if="lzj_show_nav">
      <div v-for="(i, ikeyss) in props.nav_data.data" :key="ikeyss">
        <router-link :to="i.src">
          <div class="lzj_index_nav_div">
            <div class="lzj_index_nav_div_img">
              <img class="lzj_index_nav_imgdivimg" :src="i.img_src" />
            </div>
            <div class="lzj_index_nav_title">{{ i.title }}</div>
          </div>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
import { reactive, ref } from "@vue/reactivity";
import { watch, watchEffect } from "@vue/runtime-core";

export default {
  props: {
    nav_data: {
      type: Object,
      default: {},
    },
  },
  setup(props, context) {
    const lzj_show_nav = ref(false);

    const inshow = () => {
      lzj_show_nav.value = !lzj_show_nav.value;
    };
    return {
      props,
      lzj_show_nav,
      inshow,
    };
  },
};
</script>

<style>
.lzj_index_nav {
  width: 100%;
  height: 100%;
  position: relative;
  z-index: 99999;
}

.lzj_index_nav_imgdiv {
  width: 80px;
  height: 80px;
  opacity: 0.5;
  border-radius: 10px;
  position: relative;
  float: right;
  margin-right: 10px;
  background-color: black;
}
.lzj_index_nav_imgdivimg {
  width: 60%;
  height: 60%;
  margin-top: 20%;
}
.lzj_index_nav_title {
  font-size: 30px;
  position: relative;
  line-height: 60px;
  height: 60px;
}

.lzj_index_nav_jian {
  width: 40px;
  height: 20px;
  position: relative;
  float: right;
  border-bottom: 12px solid rgb(255, 255, 255);
  border-left: 20px solid transparent;
  border-right: 20px solid transparent;
  margin-right: 20px;
  margin-bottom: 1px;
}

.lzj_index_nav_div {
  height: 60px;
  width: 200px;
  background-color: rgb(255, 255, 255);
}
.lzj_index_nav_div_img {
  position: relative;
  width: 50px;
  height: 50px;
  top: 5px;
  left: 5px;
  float: left;
}
</style>