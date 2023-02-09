<template>
  <div class="lzj_shop_info">
    <div class="lzj_shop_info_div1">
      <lzjlunbozj
        :index="index"
        :show_li="true"
        :show_right="false"
        :goto_state="false"
      ></lzjlunbozj>
    </div>
    <div style="clear: both"></div>
    <div class="lzj_shop_info_div2_head">
      <div class="lzj_shop_info_div2_head_title">
        {{ lzj_shop_obj.shop_info.title }}
      </div>
      <div class="lzj_shop_info_div2_head_now_price">
        价格：￥{{ lzj_shop_obj.shop_info.now_price }}
      </div>
      <div
        v-if="lzj_shop_obj.shop_info.last_price != ''"
        class="lzj_shop_info_div2_head_last_price"
      >
        ￥{{ lzj_shop_obj.shop_info.last_price }}
      </div>
      <div
        class="lzj_shop_info_div2_head_fare"
        v-if="
          lzj_shop_obj.shop_info.fare == '' ||
          lzj_shop_obj.shop_info.fare == '0'
        "
      >
        运费：免运费
      </div>
      <div v-else class="lzj_shop_info_div2_head_fare">
        运费：￥{{ lzj_shop_obj.shop_info.fare }}
      </div>
      <div style="clear: both"></div>
    </div>
    <div style="clear: both"></div>
    <div class="lzj_shop_info_footdiv">
      {{ text_filter }}
      <div v-if="lock" class="lzj_shop_info_show" @click="lzj_show_more">
        更多详情
      </div>
    </div>
    <div style="clear: both"></div>
    <lzjshopinfofoot
      :lzj_shop_obj="lzj_shop_obj"
      @lzj_update_show="lzj_update_show"
      @lzj_add_cart="lzj_add_cart"
      :id="id"
    ></lzjshopinfofoot>
  </div>
</template>

<script>
import { reactive, ref } from "@vue/reactivity";
import { useRouter } from "vue-router";
import { computed, getCurrentInstance, provide } from "@vue/runtime-core";
import lzjlunbozj from "@/components/lzj_lunbo_zujian.vue";
import lzjshopinfofoot from "@/components/lzj_shop_info_foot.vue";
import axios from "axios";
export default {
  components: {
    lzjlunbozj,
    lzjshopinfofoot,
  },
  setup() {
    let internalInstance = getCurrentInstance();
    let cookies = internalInstance.appContext.config.globalProperties.$cookies;
    var lzj_shop_info_collect_url = "http://47.106.68.150:81/shoplist/collect";
    var lzj_shop_info_collect_add_url =
      "http://47.106.68.150:81/shoplist/addcollect";
    var lzj_shop_info_cart_in_url = "http://47.106.68.150:81/shopcart/add";
    const route = useRouter();
    const id = ref(route.currentRoute.value.params.shopid);
    const index = ref(0);
    const lock = ref(true);
    const lzj_shop_obj_get = () => {
      axios
        .get("http://47.106.68.150:81/shoplist/info/" + id.value)
        .then((response) => {
          Object.assign(
            lzj_shop_obj.shop_info,
            JSON.parse(response.data.data).shop_info
          );
          Object.assign(
            lzj_shop_obj.lunbo_data,
            JSON.parse(response.data.data).lunbo_data
          );
        })

        .catch(function (error) {
          console.log(error);
        });
    };
    const lzj_shop_obj = reactive({
      shop_info: {
        id: 0,
        title: "巧罗进口料手工黑松露巧克力礼盒8口味400G零食包邮",
        now_price: 1200,
        last_price: 20.0,
        fare: 0,
        text: "2022年6月21日，菜鸟驿站“驿起聊天”第六场媒体沟通会在重庆举行，菜鸟驿站重庆城市负责人阅践在分享中提到，菜鸟驿站在重庆开城6年以来，站点数量快速增长，去年开通送货上门服务后，累计超80%驿站提供天猫淘宝包裹按需送货服务，快递服务站点逐步扩展成社区服务综合站。",
      },
      foot_nav: {
        add: false,
        small: [
          { title: "客服", img: "http://static.000081.xyz/img/yuan.png" },
          { title: "收藏", img: "http://static.000081.xyz/img/sc_sc.png" },
          { title: "已收藏", img: "http://static.000081.xyz/img/sc_sc1.png" },
        ],
        big: [
          {
            title: "加入购物车",
            src: "",
            color: "#ff9900",
          },
          {
            title: "立即购买",
            src: "",
            color: "#d40000",
          },
        ],
      },
      lunbo_data: [],
    });

    const lzj_update_show = () => {
      if (cookies.get("token") && cookies.get("token") != "") {
        let data = new FormData();
        data.append("token", cookies.get("token"));
        data.append("id", id.value);
        axios
          .post(lzj_shop_info_collect_add_url, data)
          .then(function (response) {
            var js = JSON.parse(response.data.data);
            if (js.token == true) {
              lzj_shop_obj.foot_nav.add = js.collect;
            } else {
              if (cookies.get("token")) {
                cookies.remove("token");
              }
              route.push({ name: "login" });
            }
          })
          .catch(function (error) {
            console.log(error);
          });
      } else {
        route.push({ name: "login" });
      }
    };

    const lzj_add_cart = () => {
      if (cookies.get("token") && cookies.get("token") != "") {
        let data = new FormData();
        data.append("token", cookies.get("token"));
        data.append("id", id.value);
        axios
          .post(lzj_shop_info_cart_in_url, data)
          .then(function (response) {
            var js = JSON.parse(response.data.data);
            if (js.token == true) {
              if (js.add) {
                route.push({ name: "cart" });
              }
            } else {
              if (cookies.get("token")) {
                cookies.remove("token");
              }
              route.push({ name: "login" });
            }
          })
          .catch(function (error) {
            console.log(error);
          });
      } else {
        route.push({ name: "login" });
      }
    };

    const text_filter = computed(() => {
      const text = lzj_shop_obj.shop_info.text;
      if (lock.value) {
        if (text.length >= 100) {
          return text.substring(0, 94) + "......";
        }
      }
      return text;
    });

    const lzj_show_more = () => {
      lock.value = false;
    };

    var timer;
    const timer_up = () => {
      timer = setInterval(() => {
        next();
      }, 3000);
    };
    const next = () => {
      index.value++;
      clearInterval(timer);
      if (index.value >= lzj_shop_obj.lunbo_data.length) {
        index.value = 0;
      }
      timer_up();
    };
    timer_up();
    provide("lzj_lunbo_data", lzj_shop_obj.lunbo_data);
    lzj_shop_obj_get();
    if (cookies.get("token") && cookies.get("token") != "") {
      let data = new FormData();
      data.append("token", cookies.get("token"));
      data.append("id", id.value);
      axios
        .post(lzj_shop_info_collect_url, data)
        .then(function (response) {
          var js = JSON.parse(response.data.data);
          if (js.token == true) {
            lzj_shop_obj.foot_nav.add = js.collect;
          }
        })
        .catch(function (error) {
          console.log(error);
        });
    }
    return {
      id,
      lzj_shop_obj,
      index,
      text_filter,
      lzj_show_more,
      lock,
      lzj_update_show,
      lzj_add_cart,
    };
  },
};
</script>

<style>
.lzj_shop_info {
  width: 100%;
  height: 100%;
  overflow: scroll;
  position: relative;
  background-color: #f2f2f2;
}

.lzj_shop_info_div1 {
  position: relative;
  width: 100%;
  height: 60%;
  overflow: hidden;
}

.lzj_shop_info_div2 {
  position: relative;
  width: 100%;
  height: auto;
}

.lzj_shop_info_div2_head {
  position: relative;
  width: 100%;
  height: auto;
  text-align: left;
  background-color: #ffffff;
}

.lzj_shop_info_div2_head_title {
  position: relative;
  width: 100%;
  font-size: 29px;
  padding: 10px 10px 15px 15px;
}

.lzj_shop_info_div2_head_now_price {
  position: relative;
  width: auto;
  font-size: 28px;
  line-height: 30px;
  color: #eb6363;
  font-weight: bold;
  padding-bottom: 15px;
  float: left;
  padding-left: 15px;
}

.lzj_shop_info_div2_head_last_price {
  float: left;
  position: relative;
  font-size: 23px;
  line-height: 25px;
  margin-left: 15px;
  top: 8px;
  text-decoration: line-through;
}

.lzj_shop_info_div2_head_fare {
  position: relative;
  float: right;
  font-size: 23px;
  top: 8px;
  line-height: 25px;
  right: 15px;
}

.lzj_shop_info_footdiv {
  max-height: 9999999px;
  position: relative;
  width: 95%;
  margin-left: 2.5%;
  border-radius: 15px;
  margin-top: 20px;
  margin-bottom: 20px;
  padding: 10px;
  text-align: left;
  background-color: #ffffff;
  line-height: 32px;
  font-size: 21px;
  word-break: break-all;
}

.lzj_shop_info_show {
  position: absolute;
  font-size: 21px;
  line-height: 32px;
  bottom: 10px;
  right: 15px;
  color: #d40000;
}
</style>