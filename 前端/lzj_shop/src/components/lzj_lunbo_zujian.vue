<template>
  <div class="lzj_lunbo_zujian" ref="lzj_lunbo_info">
    <div v-if="!show_r && show_li" class="lzj_lunbo_zujian_list">
      <span
        class="lzj_lunbo_zujian_list_li"
        v-for="(i, ikey) in lzj_lunbo_data"
        :key="ikey"
      >
        <span v-if="ikey == index" style="color: red">•</span>
        <span v-else>•</span>
      </span>
    </div>
    <div v-else-if="show_li" class="lzj_lunbo_zujian_list_r">
      <span
        class="lzj_lunbo_zujian_list_li"
        v-for="(i, ikey) in lzj_lunbo_data"
        :key="ikey"
      >
        <span v-if="ikey == index" style="color: red">•</span>
        <span v-else>•</span>
      </span>
    </div>
    <div class="lzj_lunbo_zujian_body" ref="lzj_lunbo_body">
      <div
        v-for="i in lzj_lunbo_data"
        class="lzj_lunbo_zujian_body_div"
        :style="{ width: all_width + 'px' }"
        :key="i"
      >
        <router-link
          :to="{ name: 'info', params: { shopid: i.id } }"
          class="lzj_lunbo_zujian_body_route"
          v-if="goto_state"
        >
          <div v-if="i.show_title" class="lzj_lunbo_zujian_body_title">
            {{ i.title }}
          </div>
          <div v-if="i.show_price" class="lzj_lunbo_zujian_body_price">
            ￥{{ i.price }}
          </div>
          <img :src="i.img_url" />
        </router-link>
        <div v-else class="lzj_lunbo_zujian_body_route">
          <div v-if="i.show_title" class="lzj_lunbo_zujian_body_title">
            {{ i.title }}
          </div>
          <div v-if="i.show_price" class="lzj_lunbo_zujian_body_price">
            ￥{{ i.price }}
          </div>
          <img :src="i.img_url" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {
  defineComponent,
  inject,
  onMounted,
  ref,
  watch,
  watchEffect,
} from "vue";

// 轮播功能封装
// index代表当前索引号
// show_li代表是否显示底部圆点
// show_right代表底部圆点是否显示在右侧
// lzj_lunbo_data是其他组件使用private传入，inject接收

export default defineComponent({
  props: {
    index: {
      type: Number,
      default: 0,
    },
    show_li: {
      type: Boolean,
      default: false,
    },
    show_right: {
      type: Boolean,
      default: false,
    },
    goto_state: {
      type: Boolean,
      default: false,
    },
  },
  setup(props, context) {
    const lzj_lunbo_data = inject("lzj_lunbo_data");
    const lzj_lunbo_info = ref(null);
    const lzj_lunbo_body = ref(null);
    const all_width = ref(null);
    const index = ref(0);
    const show_r = ref(false);
    const show_li = ref(false);
    const goto_state = ref(false);

    watch(
      () => props.index,
      (val) => {
        index.value = val;
        lzj_lunbo_body.value.style.marginLeft =
          -1 * val * all_width.value + "px";
      }
    );

    watchEffect(() => {
      show_r.value = props.show_right;
      goto_state.value = props.goto_state;
      show_li.value = props.show_li;
    });

    onMounted(() => {
      all_width.value = lzj_lunbo_info.value.clientWidth;
    });

    return {
      lzj_lunbo_data,
      lzj_lunbo_info,
      all_width,
      lzj_lunbo_body,
      goto_state,
      index,
      show_r,
    };
  },
});
</script>

<style>
.lzj_lunbo_zujian {
  width: 100%;
  height: 100%;
  position: relative;
}

.lzj_lunbo_zujian_list {
  text-align: center;
  height: 30px;
  line-height: 30px;
  width: 100%;
  bottom: 0;
  position: absolute;
  z-index: 100;
}

.lzj_lunbo_zujian_list_r {
  text-align: center;
  height: 30px;
  line-height: 30px;
  width: auto;
  right: 0;
  bottom: 0;
  position: absolute;
  z-index: 100;
}

.lzj_lunbo_zujian_list_li {
  position: relative;
  width: 20px;
  height: 20px;
  line-height: 25px;
  padding-left: 5px;
  padding-right: 5px;
  font-size: 40px;
}

.lzj_lunbo_zujian_body {
  position: relative;
  width: 999999px;
  height: 100%;
  transition: all 0.5s;
}

.lzj_lunbo_zujian_body_div {
  position: relative;
  height: 100%;
  text-align: center;
  float: left;
}

.lzj_lunbo_zujian_body_div > .lzj_lunbo_zujian_body_route > img {
  width: 100%;
  height: 100%;
  position: absolute;
  top: 0;
  left: 0;
}

.lzj_lunbo_zujian_body_div
  > .lzj_lunbo_zujian_body_route
  > .lzj_lunbo_zujian_body_title {
  position: relative;
  z-index: 100;
  font-weight: bold;
  margin-top: 15px;
  width: auto;
  height: 40px;
  font-size: 30px;
  line-height: 40px;
}

.lzj_lunbo_zujian_body_div
  > .lzj_lunbo_zujian_body_route
  > .lzj_lunbo_zujian_body_price {
  position: absolute;
  right: 10px;
  bottom: 25px;
  z-index: 100;
  font-weight: bold;
  color: red;
  margin-top: 15px;
  width: auto;
  height: 45px;
  font-size: 40px;
  line-height: 45px;
}
</style>