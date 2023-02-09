<template>
  <div class="lzj_shop_from">
    <div v-if="lzj_shop_obj.one">
      <div
        v-for="(i, index) in lzj_shop_address_list.data"
        :key="index"
        @click.stop="lzj_shop_address_change(i.id)"
      >
        <lzjshopfromcard1
          :base_info="i.data"
          v-if="lzj_shop_address_list.check == i.id"
          :bordercolor="'3px solid #fd9d5c'"
        ></lzjshopfromcard1>
        <lzjshopfromcard1 :base_info="i.data" v-else></lzjshopfromcard1>
      </div>

      <lzjshopfromline
        v-for="(i, index) in lzj_shop_obj.base_info"
        :key="index"
        :base_info="i"
        :index="index"
        @lzj_update_input="lzj_update_input"
      >
      </lzjshopfromline>
      <lzjshopsendtime
        :send_time="lzj_shop_obj.send_time"
        @lzj_update_check="lzj_update_check"
        :in_index="true"
      >
      </lzjshopsendtime>
      <lzjshopfromcard
        :from_card="lzj_shop_obj.from_card"
        @lzj_update_sum="lzj_update_sum"
        :in_index="true"
      >
      </lzjshopfromcard>
      <div @click="lzj_from_fn">
        <lzjshopfromfoot :lzj_title="'提交订单'"></lzjshopfromfoot>
      </div>
    </div>
    <div v-else>
      <lzjshopfromcard1
        :base_info="lzj_shop_obj.base_info"
        @lzj_update_fn="lzj_update_fn"
      ></lzjshopfromcard1>
      <lzjshopsendtime
        :send_time="lzj_shop_obj.send_time"
        @lzj_update_check="lzj_update_check"
        :in_index="false"
      ></lzjshopsendtime>
      <lzjshopfromcard
        :from_card="lzj_shop_obj.from_card"
        @lzj_update_sum="lzj_update_sum"
        :in_index="false"
      ></lzjshopfromcard>
      <div @click="lzj_from_fn">
        <lzjshopfromfoot :lzj_title="'结算'"></lzjshopfromfoot>
      </div>
    </div>
    <div style="height: 100px"></div>
  </div>
  <lzjmessagediv
    :lzj_massage_obj="lzj_massage_obj"
    @lzj_update_massage="lzj_update_massage"
  ></lzjmessagediv>
</template>

<script>
import lzjshopsendtime from "../components/lzj_shop_from_send_time.vue";
import lzjshopfromcard from "../components/lzj_shop_from_card.vue";
import lzjshopfromfoot from "../components/lzj_shop_from_foot.vue";
import lzjshopfromline from "../components/lzj_shop_from_line.vue";
import lzjshopfromcard1 from "../components/lzj_shop_from_card1.vue";
import lzjmessagediv from "../components/lzj_message_div.vue";

import { lzj_get_from_id } from "@/function/shop_from_id";
import { reactive } from "@vue/reactivity";
import { useRouter } from "vue-router";
import axios from "axios";
import { getCurrentInstance } from "@vue/runtime-core";
export default {
  components: {
    lzjshopsendtime,
    lzjshopfromcard,
    lzjshopfromfoot,
    lzjshopfromline,
    lzjshopfromcard1,
    lzjmessagediv,
  },

  setup() {
    const router = useRouter();
    const lzj_shop_id = router.currentRoute.value.params.shopid;
    let internalInstance = getCurrentInstance();
    let cookies = internalInstance.appContext.config.globalProperties.$cookies;
    var lzj_shop_address_list_url = "http://47.106.68.150:81/shopaddress/list";
    var lzj_shop_info_url = "http://47.106.68.150:81/shopfrom/shop";
    var lzj_shop_from_add = "http://47.106.68.150:81/shopfrom/add";
    const lzj_shop_address_list = reactive({ data: [], check: 0 });

    const lzj_shop_address_change = (id) => {
      lzj_shop_address_list.check = id;
      lzj_shop_address_list.data.forEach((element) => {
        if (element.id == id) {
          lzj_shop_obj.base_info = element.data;
        }
      });
    };

    const lzj_shop_from_init = () => {
      if (cookies.get("token") && cookies.get("token") != "") {
        let data = new FormData();
        data.append("token", cookies.get("token"));
        axios
          .post(lzj_shop_address_list_url, data)
          .then(function (response) {
            var js = JSON.parse(response.data.data);
            if (js.token != false) {
              lzj_shop_address_list.data = js.data;
              lzj_shop_address_list.check = js.defaultid;
              lzj_shop_address_list.data.forEach((element) => {
                if (element.id == js.defaultid) {
                  lzj_shop_obj.base_info = element.data;
                }
              });
            } else {
              cookies.set("routerpath", {
                name: router.currentRoute.value.name,
              });
              router.push({ name: "login" });
            }
          })
          .catch(function (error) {
            console.log(error);
          });

        data.append("id", lzj_shop_id);
        axios
          .post(lzj_shop_info_url, data)
          .then(function (response) {
            var js = JSON.parse(response.data.data);
            if (js.token != false) {
              lzj_shop_obj.from_card = js.data;
            } else {
              cookies.set("routerpath", {
                name: router.currentRoute.value.name,
              });
              router.push({ name: "login" });
            }
          })
          .catch(function (error) {
            console.log(error);
          });
      } else {
        cookies.set("routerpath", { name: router.currentRoute.value.name });
        router.push({ name: "login" });
      }
    };

    //选中状态
    const lzj_shop_obj = reactive({
      one: true,
      base_info: [
        {
          tip: "收货人",
          value: "",
        },
        {
          tip: "手机号码",
          value: "",
        },
        {
          tip: "地区信息",
          value: "",
        },
        {
          tip: "详细地址",
          value: "",
        },
        {
          tip: "邮政编码",
          value: "",
        },
      ],
      send_time: {
        check_id: 0,
        data: [
          {
            id: 0,
            text: "工作日、双休日与假日均可送货",
          },
          {
            id: 1,
            text: "只有双休日、假日送货（工作日不用送货）",
          },
          {
            id: 2,
            text: "只有工作日送货（双休日、假日不用送）写字楼/商用地址客户选择",
          },
        ],
      },
      from_card: {
        id: "",
        img: "http://static.000081.xyz/img/sc_5_1.jpg",
        title: "巧罗进口料手工黑松露巧克力礼盒8口味400G零食包邮",
        postage: 0,
        price: 1200,
        sum: 1,
      },
    });

    //底部文本
    //初始化
    const lzj_massage_obj = reactive({
      show: false,
      title: "",
      showtime: 0,
    });
    const lzj_update_massage = () => {
      lzj_massage_obj.show = false;
      lzj_massage_obj.title = "";
      lzj_massage_obj.showtime = 0;
    };

    lzj_shop_obj.from_card.id = lzj_get_from_id();
    const lzj_update_input = (i, index) => {
      lzj_shop_obj.base_info[index] = i;
    };
    const lzj_update_check = (index) => {
      lzj_shop_obj.send_time.check_id = index;
    };
    const lzj_update_sum = (from_card) => {
      lzj_shop_obj.from_card.sum = from_card;
    };
    const lzj_from_fn = () => {
      if (lzj_shop_obj.base_info[0].value == "") {
        lzj_massage_obj.title = "请输入收货人";
        lzj_massage_obj.showtime = 3;
        lzj_massage_obj.show = true;
        return;
      }
      if (
        lzj_shop_obj.base_info[1].value == "" ||
        lzj_shop_obj.base_info[1].value.length != 11
      ) {
        lzj_massage_obj.title = "请正确输入手机号码";
        lzj_massage_obj.showtime = 3;
        lzj_massage_obj.show = true;
        return;
      }
      if (lzj_shop_obj.base_info[2].value == "") {
        lzj_massage_obj.title = "请输入地区信息";
        lzj_massage_obj.showtime = 3;
        lzj_massage_obj.show = true;
        return;
      }
      if (lzj_shop_obj.base_info[3].value == "") {
        lzj_massage_obj.title = "请输入详情地址";
        lzj_massage_obj.showtime = 3;
        lzj_massage_obj.show = true;
        return;
      }
      if (lzj_shop_obj.one) {
        lzj_shop_obj.one = false;
        return;
      }

      var lzj_cache_json = {
        fromid: lzj_shop_obj.from_card.id,
        shopid: lzj_shop_id,
        num: lzj_shop_obj.from_card.sum,
        name: lzj_shop_obj.base_info[0].value,
        phone: lzj_shop_obj.base_info[1].value,
        region: lzj_shop_obj.base_info[2].value,
        address: lzj_shop_obj.base_info[3].value,
        code: lzj_shop_obj.base_info[4].value,
        sendtime: "",
      };
      for (var i = 0; i < lzj_shop_obj.send_time.data.length; i++) {
        if (
          lzj_shop_obj.send_time.check_id == lzj_shop_obj.send_time.data[i].id
        ) {
          lzj_cache_json.sendtime = lzj_shop_obj.send_time.data[i].text;
          break;
        }
      }
      if (cookies.get("token") && cookies.get("token") != "") {
        let data = new FormData();
        data.append("token", cookies.get("token"));
        data.append("data", JSON.stringify(lzj_cache_json));
        axios
          .post(lzj_shop_from_add, data)
          .then(function (response) {
            var js = JSON.parse(response.data.data);
            console.log(js);
            if (js.token != false) {
              router.push({
                name: "confirm",
                query: {
                  fromid: js.id,
                  token: js.key,
                },
              });
            } else {
              cookies.set("routerpath", {
                name: router.currentRoute.value.name,
              });
              router.push({ name: "login" });
            }
          })
          .catch(function (error) {
            console.log(error);
          });
      } else {
        cookies.set("routerpath", {
          name: router.currentRoute.value.name,
        });
        router.push({ name: "login" });
      }
      // router.push({
      //   name: "confirm",
      //   params: {
      //     fromid: lzj_shop_obj.from_card.id,
      //   },
      // });
      return;
    };
    const lzj_update_fn = () => {
      lzj_shop_obj.one = true;
    };

    lzj_shop_from_init();
    return {
      lzj_shop_address_list,
      lzj_shop_obj,
      lzj_shop_id,
      lzj_massage_obj,
      lzj_update_input,
      lzj_update_check,
      lzj_update_sum,
      lzj_get_from_id,
      lzj_from_fn,
      lzj_update_fn,
      lzj_update_massage,
      lzj_shop_address_change,
    };
  },
};
</script>

<style>
.lzj_shop_from {
  width: 100%;
  height: 100%;
  position: relative;
  overflow: scroll;
  background-color: #ececec;
}
</style>