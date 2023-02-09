<template>
  <div class="lzj_shop_my_from">
    <div
      class="lzj_shop_my_from_head"
      v-for="(i, keys) in lzj_shop_obj_head"
      :key="keys"
      @click="lzj_shop_head_update_check(i.id)"
    >
      <div class="lzj_shop_my_from_head_title">
        <div
          class="lzj_shop_my_from_head_text"
          style="
            border-bottom: 5px solid #cd364d;
            color: #cd364d;
            font-weight: bold;
          "
          v-if="lzj_shop_head_check == i.id"
        >
          {{ i.title }}
        </div>
        <div class="lzj_shop_my_from_head_text" v-else>{{ i.title }}</div>
      </div>
    </div>
    <div class="lzj_shop_my_from_head_solid" style="left: 33.3%"></div>
    <div class="lzj_shop_my_from_head_solid" style="left: 66.6%"></div>
    <div style="clear: both"></div>
    <div
      v-for="(i, index) in lzj_shop_obj.data"
      :key="index"
      @click="lzj_shop_goto_from(i.id)"
    >
      <div v-if="lzj_shop_head_check == 0">
        <lzjshopmyfromcard :myfrom="i"></lzjshopmyfromcard>
      </div>
      <div v-else-if="lzj_shop_head_check == 1">
        <lzjshopmyfromcard
          :myfrom="i"
          v-if="i.status == '订单生成'"
        ></lzjshopmyfromcard>
      </div>
      <div v-else-if="lzj_shop_head_check == 2">
        <lzjshopmyfromcard
          :myfrom="i"
          v-if="i.status == '未付款'"
        ></lzjshopmyfromcard>
      </div>
    </div>
  </div>
</template>

<script>
import { reactive, ref } from "@vue/reactivity";
import lzjshopmyfromcard from "../components/lzj_shop_myfrom_card.vue";
import { getCurrentInstance } from "@vue/runtime-core";
import { useRouter } from "vue-router";
import axios from "axios";
export default {
  components: {
    lzjshopmyfromcard,
  },
  setup() {
    let internalInstance = getCurrentInstance();
    let cookies = internalInstance.appContext.config.globalProperties.$cookies;
    var lzj_shop_my_from_url = "http://47.106.68.150:81/shopfrom/list";
    const router = useRouter();
    const lzj_shop_obj_head = reactive([
      {
        title: "全部",
        id: 0,
      },
      {
        title: "订单生成",
        id: 1,
      },
      {
        title: "未付款",
        id: 2,
      },
    ]);
    const lzj_shop_obj = reactive({
      data: [
        {
          id: "202206250017959803423",
          status: "订单生成",
          price: 1201,
          time: "2015-1-12 14:33:10",
          img: "http://static.000081.xyz/img/sc_11_1.jpg",
        },
      ],
    });

    const lzj_shop_goto_from = (id) => {
      router.push({
        name: "confirm",
        query: {
          fromid: id,
        },
      });
    };

    const lzj_shop_head_check = ref(0);
    const lzj_shop_head_update_check = (i) => {
      lzj_shop_head_check.value = i;
    };
    const lzj_shop_my_from_init = () => {
      if (cookies.get("token") && cookies.get("token") != "") {
        let data = new FormData();
        data.append("token", cookies.get("token"));
        axios
          .post(lzj_shop_my_from_url, data)
          .then(function (response) {
            var js = JSON.parse(response.data.data);
            if (js.token != false) {
              lzj_shop_obj.data = js.data;
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
    lzj_shop_my_from_init();
    return {
      lzj_shop_obj_head,
      lzj_shop_head_check,
      lzj_shop_obj,
      lzj_shop_head_update_check,
      lzj_shop_goto_from,
    };
  },
};
</script>

<style>
.lzj_shop_my_from {
  position: relative;
  width: 100%;
  height: 100%;
  background-color: #ececec;
  overflow: scroll;
}

.lzj_shop_my_from_head {
  position: relative;
  float: left;
  width: 33.3%;
  height: 100px;
  background-color: #ffffff;
}

.lzj_shop_my_from_head_solid {
  position: absolute;
  width: 4px;
  height: 40px;
  margin-top: 30px;
  background-color: #eeeeee;
}

.lzj_shop_my_from_head_title {
  height: 100%;
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
}

.lzj_shop_my_from_head_text {
  position: relative;
  width: auto;
  height: 100%;
  line-height: 100px;
  font-size: 33px;
}
</style>