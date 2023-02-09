<template>
  <div class="lzj_shop_cart">
    <div class="lzj_shop_cart_foot">
      <div style="float: left">总价：</div>
      <div class="lzj_shop_cart_foot_price">￥{{ lzj_shop_obj.sum }}</div>
      <div class="lzj_shop_cart_foot_right" v-if="lzj_shop_obj.check_sum > 0">
        去结算（ {{ lzj_shop_obj.check_sum }} ）
      </div>
      <div
        class="lzj_shop_cart_foot_right"
        style="background-color: dimgrey"
        v-else
      >
        未选中
      </div>
    </div>
    <div class="lzj_shop_cart">
      <div
        v-for="(i, index) in lzj_shop_obj.data"
        :key="index"
        @click="lzj_update_cart_update_check(index)"
      >
        <lzjshopcart
          :shop_info="i"
          :index="index"
          :addcut="lzj_shop_obj.addcut"
          :checkimg="lzj_shop_obj.checkimg"
          @lzj_update_cart_card="lzj_update_cart_card"
        ></lzjshopcart>
      </div>
      <div style="height: 90px; max-width: 20%"></div>
    </div>
    <div
      v-if="lzj_shop_obj.check_sum > 0"
      class="lzj_shop_obj_del"
      @click.stop="lzj_shop_obj_del"
    >
      删除（ {{ lzj_shop_obj.check_sum }} ）
    </div>
  </div>
</template>

<script>
import { reactive } from "@vue/reactivity";
import lzjshopcart from "../components/lzj_shop_cart_card.vue";
import axios from "axios";
import {
  getCurrentInstance,
  onMounted,
  onUpdated,
  watch,
  watchEffect,
} from "@vue/runtime-core";
import { useRouter } from "vue-router";
export default {
  components: {
    lzjshopcart,
  },
  setup() {
    let internalInstance = getCurrentInstance();
    let cookies = internalInstance.appContext.config.globalProperties.$cookies;
    const router = useRouter();
    var lzj_shop_cart_list_url = "http://47.106.68.150:81/shopcart/list";
    var lzj_shop_cart_change_url = "http://47.106.68.150:81/shopcart/change";
    var lzj_shop_cart_del_url = "http://47.106.68.150:81/shopcart/del";
    const lzj_shop_obj = reactive({
      checkimg: {
        nocheck: "http://static.000081.xyz/img/sc_12_3.jpg",
        oncheck: "http://static.000081.xyz/img/sc_12_2.jpg",
      },
      addcut: {
        add: "http://static.000081.xyz/img/addsum.png",
        cut: "http://static.000081.xyz/img/cutsum.png",
      },
      sum: 0,
      check_sum: 0,
      data: [
        {
          id: 0,
          title: "巧罗进口料手工黑松露巧克力礼盒8口味400G零食包邮",
          price: 1200,
          number: 1,
          sum: 1200,
          img: "http://static.000081.xyz/img/sc_5_1.jpg",
          check: false,
        },
      ],
    });

    if (cookies.get("token") && cookies.get("token") != "") {
      let data = new FormData();
      data.append("token", cookies.get("token"));
      axios
        .post(lzj_shop_cart_list_url, data)
        .then(function (response) {
          var js = JSON.parse(response.data.data);
          if (js.token != false) {
            lzj_shop_obj.data = js.data;
            lzj_update_cart_sum();
          } else {
            cookies.set("routerpath", { name: "home" });
            router.push({ name: "login" });
          }
        })
        .catch(function (error) {
          console.log(error);
        });
    } else {
      router.push({ name: "login" });
    }

    const lzj_update_cart_card = (shop_info, i) => {
      lzj_shop_obj.data[i] = shop_info;
      if (cookies.get("token") && cookies.get("token") != "") {
        let data = new FormData();
        data.append("token", cookies.get("token"));
        data.append("id", lzj_shop_obj.data[i].id);
        data.append("number", lzj_shop_obj.data[i].number);
        data.append("check", lzj_shop_obj.data[i].check);
        axios
          .post(lzj_shop_cart_change_url, data)
          .then(function (response) {
            var js = JSON.parse(response.data.data);
          })
          .catch(function (error) {
            console.log(error);
          });
      } else {
        router.push({ name: "login" });
      }
      lzj_update_cart_sum();
    };

    const lzj_shop_obj_del = () => {
      var sz = [];
      for (var i = lzj_shop_obj.data.length - 1; i >= 0; i--) {
        if (lzj_shop_obj.data[i].check) {
          sz.push(lzj_shop_obj.data[i].id);
          lzj_shop_obj.data.splice(i, 1);
        }
      }
      lzj_update_cart_sum();
      if (cookies.get("token") && cookies.get("token") != "") {
        let data = new FormData();
        data.append("token", cookies.get("token"));
        data.append("id", sz.join(";"));
        axios
          .post(lzj_shop_cart_del_url, data)
          .then(function (response) {})
          .catch(function (error) {
            console.log(error);
          });
      } else {
        useRouter().push({ name: "login" });
      }
    };

    const lzj_update_cart_update_check = (i) => {
      lzj_shop_obj.data[i].check = !lzj_shop_obj.data[i].check;
      if (cookies.get("token") && cookies.get("token") != "") {
        let data = new FormData();
        data.append("token", cookies.get("token"));
        data.append("id", lzj_shop_obj.data[i].id);
        data.append("number", lzj_shop_obj.data[i].number);
        data.append("check", lzj_shop_obj.data[i].check);
        axios
          .post(lzj_shop_cart_change_url, data)
          .then(function (response) {
            // var js = JSON.parse(response.data.data);
          })
          .catch(function (error) {
            console.log(error);
          });
      } else {
        useRouter().push({ name: "login" });
      }
      lzj_update_cart_sum();
    };

    const lzj_update_cart_sum = () => {
      lzj_shop_obj.sum = 0;
      lzj_shop_obj.check_sum = 0;
      for (var i = 0; i < lzj_shop_obj.data.length; i++) {
        lzj_shop_obj.data[i].sum =
          lzj_shop_obj.data[i].number * lzj_shop_obj.data[i].price;
        lzj_shop_obj.data[i].sum =
          Math.round(lzj_shop_obj.data[i].sum * 100) / 100;
        if (lzj_shop_obj.data[i].check) {
          lzj_shop_obj.sum += lzj_shop_obj.data[i].sum;
          lzj_shop_obj.check_sum++;
        }
      }
      lzj_shop_obj.sum = Math.round(lzj_shop_obj.sum * 100) / 100;
    };

    onMounted(() => {
      lzj_update_cart_sum();
    });

    return {
      lzj_shop_obj,
      lzj_update_cart_card,
      lzj_update_cart_update_check,
      lzj_shop_obj_del,
    };
  },
};
</script>

<style>
.lzj_shop_cart {
  position: relative;
  width: 100%;
  height: 100%;
  overflow: scroll;
  background-color: #f2f2f2;
}

.lzj_shop_cart_foot {
  position: fixed;
  background-color: #ffffff;
  width: 100%;
  z-index: 9999;
  height: 90px;
  bottom: 0;
  line-height: 90px;
  float: left;
  font-size: 25px;
  color: red;
  font-weight: bold;
  text-align: left;
  padding-left: 40px;
}

.lzj_shop_cart_foot_price {
  position: relative;
  float: left;
  font-size: 35px;
}

.lzj_shop_cart_foot_right {
  height: 60px;
  margin-top: 15px;
  text-align: center;
  max-width: 40%;
  padding-left: 20px;
  padding-right: 20px;
  right: 0;
  position: absolute;
  line-height: 60px;
  font-weight: normal;
  font-size: 25px;
  color: white;
  border-radius: 5px;
  min-width: 30%;
  margin-right: 15px;
  float: right;
  background-color: #e20200;
}

.lzj_shop_obj_del {
  position: fixed;
  text-align: center;
  background-color: #e20200;
  color: white;
  border-radius: 5px;
  height: 50px;
  line-height: 50px;
  z-index: 9999;
  top: 20px;
  padding-left: 10px;
  padding-right: 10px;
  right: 20px;
  font-size: 20px;
}
</style>