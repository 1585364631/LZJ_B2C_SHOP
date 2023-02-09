<template>
  <div class="lzjlunbo">
    <div class="lzjlunbo_left">
      <div class="lzjlunbo_img" @click="last">
        <img
          :src="lzj_shop_index_obj.left_img_src"
          style="width: 100%; height: 100%"
        />
      </div>
    </div>
    <div class="lzjlunbo_body">
      <div class="lzjlunbo_body_lunbo">
        <lzjlunbozj :index="index" :goto_state="true"></lzjlunbozj>
      </div>
    </div>
    <div class="lzjlunbo_right">
      <div class="lzjlunbo_img" @click="next">
        <img
          :src="lzj_shop_index_obj.right_img_src"
          style="width: 100%; height: 100%"
        />
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, inject, onMounted, provide, ref } from "vue";
import lzjlunbozj from "@/components/lzj_lunbo_zujian.vue";
export default defineComponent({
  components: { lzjlunbozj },
  setup() {
    const lzj_shop_index_obj = inject("lzj_shop_index_obj");
    const lzj_lunbo_data = lzj_shop_index_obj.data;
    const index = ref(0);
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

    onMounted(() => {
      timer_up();
    });
    provide("lzj_lunbo_data", lzj_lunbo_data);
    return {
      lzj_shop_index_obj,
      index,
      next,
      last,
    };
  },
});
</script>

<style>
.lzjlunbo {
  width: 100%;
  position: relative;
  height: 100%;
  box-sizing: border-box;
}

.lzjlunbo_left {
  position: relative;
  width: 15%;
  height: 100%;
  float: left;
}

.lzjlunbo_body {
  position: relative;
  width: 70%;
  height: 100%;
  float: left;
}

.lzjlunbo_body_lunbo {
  position: relative;
  width: 100%;
  height: 90%;
  top: 5%;
  overflow: hidden;
}

.lzjlunbo_right {
  position: relative;
  width: 15%;
  float: left;
  height: 100%;
}

.lzjlunbo_img {
  position: relative;
  width: 40%;
  height: 20%;
  top: 40%;
  left: 30%;
}
</style>