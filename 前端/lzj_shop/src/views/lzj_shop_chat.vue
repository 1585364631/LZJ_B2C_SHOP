<template>
  <div class="lzj_shop_chat" id="lzj_shop_chat">
    <div>
      <!-- <div
        class="lzj_shop_chat_head"
        v-if="lzj_shop_cart_base.head_show"
        @click="lzj_goto_shop_info"
      >
        <lzjshopchatcard :lzj_shop_info="lzj_shop_info"></lzjshopchatcard>
      </div> -->
      <div
        class="lzj_shop_chat_body"
        v-for="(i, index) in lzj_shop_obj.data"
        :key="index"
        id="lzj_shop_chat_body"
      >
        <lzjshopchatmessage
          :lzj_message="i"
          :lzj_img="lzj_shop_obj.img"
        ></lzjshopchatmessage>
      </div>
      <div style="clear: both"></div>
      <div
        class="lzj_shop_chat_head"
        v-if="lzj_shop_cart_base.head_show"
        @click="lzj_goto_shop_info"
      >
        <lzjshopchatcard
          :lzj_shop_info="lzj_shop_info"
          @lzj_shop_change_card_show="lzj_shop_change_card_show"
        ></lzjshopchatcard>
      </div>
      <lzjshopchatfoot
        :lzj_shop_emoji="lzj_shop_emoji"
        @lzj_shop_update_message="lzj_shop_update_message"
      ></lzjshopchatfoot>
    </div>
  </div>
</template>

<script>
import { reactive, ref } from "@vue/reactivity";
import lzjshopchatcard from "../components/lzj_shop_chat_card.vue";
import lzjshopchatmessage from "../components/lzj_shop_chat_message.vue";
import lzjshopchatfoot from "../components/lzj_shop_chat_foot.vue";
import { useRouter } from "vue-router";
import axios from "axios";
import { getCurrentInstance, onBeforeUnmount, watch } from "@vue/runtime-core";

function codeinimg(text, obj) {
  try {
    var pattern = /\[\/(.*?)\]/gims;
    var cachesz = text.match(pattern);
    cachesz.forEach((element) => {
      for (var i = 0; i < obj.length; i++) {
        if (element == obj[i].code) {
          var bg =
            '<div class="lzj_shop_chat_span" style="background-image: url(\'' +
            obj[i].img +
            "')\"></div>";
          text = text.replace(element, bg);
          break;
        }
      }
    });
  } catch (e) {}
  return text;
}

var gotobottom1 = (id) => {
  document.querySelector(id).scrollIntoView({
    behavior: "smooth",
    block: "center",
    inline: "nearest",
  });
};

var gotobottom = (id) => {
  setTimeout(() => {
    var lzj_shop_chat_body = document.getElementById("lzj_shop_chat");
    if (
      lzj_shop_chat_body.scrollHeight - 200 <=
      lzj_shop_chat_body.scrollTop + lzj_shop_chat_body.clientHeight
    ) {
      gotobottom1(id);
    }
  }, 100);
};
export default {
  components: {
    lzjshopchatcard,
    lzjshopchatmessage,
    lzjshopchatfoot,
  },
  setup() {
    const router = useRouter();
    let internalInstance = getCurrentInstance();
    let cookies = internalInstance.appContext.config.globalProperties.$cookies;

    var lzj_shop_chat_url = "http://47.106.68.150:81/shopchat/chat";
    var lzj_shop_chat_send_url = "http://47.106.68.150:81/shopchat/send";
    var timer;

    const lzj_shop_cart_base = reactive({
      //头部发送宝贝链接展示
      head_show: false,
      //商品id
      shop_id: 0,
    });
    lzj_shop_cart_base.shop_id = router.currentRoute.value.query.shopid;
    if (lzj_shop_cart_base.shop_id) {
      lzj_shop_cart_base.head_show = true;
    }
    const lzj_goto_shop_info = () => {
      router.push({
        name: "info",
        params: {
          shopid: lzj_shop_cart_base.shop_id,
        },
      });
    };

    const lzj_shop_change_card_show = () => {
      lzj_shop_cart_base.head_show = false;
      var cachedadata = {
        from: true,
        text: lzj_shop_info.title,
        // text:
        //   '<div class="lzj_shop_chat_baby" style="background-image: url(\'' +
        //   lzj_shop_info.img +
        //   "')\"></div>",
      };
      lzj_shop_obj.data.push(cachedadata);
      lzj_shop_update_message(cachedadata);
    };

    const lzj_shop_info = reactive({
      title: "巧罗进口料手工黑松露巧克力礼盒8口味400G零食包邮",
      price: 1200,
      img: "http://static.000081.xyz/img/sc_5_1.jpg",
      id: 0,
    });
    const lzj_shop_obj = reactive({
      img: {
        my: "https://cube.elemecdn.com/9/c2/f0ee8a3c7c9638a54940382568c9dpng.png",
        you: "http://static.000081.xyz/img/sc_12_1.jpg",
      },
      data: [
        {
          from: true,
          text: "你好，客服",
        },
      ],
    });

    const lzj_shop_update_time = () => {
      clearInterval(timer);
      timer = setInterval(() => {
        lzj_shop_chat_init();
      }, 1500);
    };

    onBeforeUnmount(() => {
      clearInterval(timer);
    });

    const lzj_shop_chat_init = () => {
      if (cookies.get("token") && cookies.get("token") != "") {
        let data = new FormData();
        data.append("token", cookies.get("token"));
        axios
          .post(lzj_shop_chat_url, data)
          .then(function (response) {
            var js = JSON.parse(response.data.data);
            if (js.token == false) {
              router.push({ name: "login" });
            }
            lzj_shop_obj.data = js.data;
            lzj_update_text();
            setTimeout(() => {
              gotobottom("#lzj_shop_chat_foot_id");
            }, 200);
            lzj_shop_update_time();
          })
          .catch(function (error) {
            console.log(error);
          });
      } else {
        router.push({ name: "login" });
      }
    };

    lzj_shop_chat_init();

    const lzj_update_text = () => {
      for (var i = 0; i < lzj_shop_obj.data.length; i++) {
        lzj_shop_obj.data[i].text = codeinimg(
          lzj_shop_obj.data[i].text,
          lzj_shop_emoji.data
        );
      }
    };

    const lzj_shop_update_message = (cachedata) => {
      clearInterval(timer);
      if (cookies.get("token") && cookies.get("token") != "") {
        let data = new FormData();
        data.append("token", cookies.get("token"));
        data.append("text", cachedata.text);
        axios
          .post(lzj_shop_chat_send_url, data)
          .then(function (response) {
            var js = JSON.parse(response.data.data);
            if (js.token == false) {
              router.push({ name: "login" });
            }
            lzj_shop_chat_init();
            setTimeout(() => {
              gotobottom1("#lzj_shop_chat_foot_id");
            }, 100);
          })
          .catch(function (error) {
            console.log(error);
          });
      } else {
        router.push({ name: "login" });
      }
    };

    const lzj_shop_emoji = reactive({
      open: "http://static.000081.xyz/img/sc_14_2.png",
      close: "http://static.000081.xyz/img/emoji/guanbi.png",
      data: [
        {
          name: "ok",
          img: "http://static.000081.xyz/img/emoji/ok.png",
          code: "[/ok]",
          open: true,
        },
        {
          name: "保佑",
          img: "http://static.000081.xyz/img/emoji/baoyou.png",
          code: "[/baoyou]",
          open: true,
        },
        {
          name: "偷笑",
          img: "http://static.000081.xyz/img/emoji/touxiao.png",
          code: "[/touxiao]",
          open: true,
        },

        {
          name: "再见",
          img: "http://static.000081.xyz/img/emoji/zaijian.png",
          code: "[/zaijian]",
          open: true,
        },
        {
          name: "口罩",
          img: "http://static.000081.xyz/img/emoji/kouzhao.png",
          code: "[/kouzhao]",
          open: true,
        },
        {
          name: "吃瓜",
          img: "http://static.000081.xyz/img/emoji/chigua.png",
          code: "[/chigua]",
          open: true,
        },
        {
          name: "吐",
          img: "http://static.000081.xyz/img/emoji/tu.png",
          code: "[/tu]",
          open: true,
        },
        {
          name: "吓",
          img: "http://static.000081.xyz/img/emoji/xia.png",
          code: "[/xia]",
          open: true,
        },
        {
          name: "呆",
          img: "http://static.000081.xyz/img/emoji/dai.png",
          code: "[/dai]",
          open: true,
        },
        {
          name: "呲牙",
          img: "http://static.000081.xyz/img/emoji/ziya.png",
          code: "[/ziya]",
          open: true,
        },
        {
          name: "哈欠",
          img: "http://static.000081.xyz/img/emoji/haqian.png",
          code: "[/haqian]",
          open: true,
        },
        {
          name: "响指",
          img: "http://static.000081.xyz/img/emoji/xiangzhi.png",
          code: "[/xiangzhi]",
          open: true,
        },
        {
          name: "哦呼",
          img: "http://static.000081.xyz/img/emoji/ohu.png",
          code: "[/ohu]",
          open: true,
        },
        {
          name: "喜极而泣",
          img: "http://static.000081.xyz/img/emoji/xijierqi.png",
          code: "[/xijierqi]",
          open: true,
        },
        {
          name: "喜欢",
          img: "http://static.000081.xyz/img/emoji/xihuan.png",
          code: "[/xihuan]",
          open: true,
        },
        {
          name: "嗑瓜子",
          img: "http://static.000081.xyz/img/emoji/keguazi.png",
          code: "[/keguazi]",
          open: true,
        },
        {
          name: "嘘声",
          img: "http://static.000081.xyz/img/emoji/xusheng.png",
          code: "[/xusheng]",
          open: true,
        },
        {
          name: "嘟嘟",
          img: "http://static.000081.xyz/img/emoji/dudu.png",
          code: "[/dudu]",
          open: true,
        },
        {
          name: "囧",
          img: "http://static.000081.xyz/img/emoji/jiong.png",
          code: "[/jiong]",
          open: true,
        },
        {
          name: "墨镜",
          img: "http://static.000081.xyz/img/emoji/mojing.png",
          code: "[/mojing]",
          open: true,
        },
        {
          name: "大哭",
          img: "http://static.000081.xyz/img/emoji/daku.png",
          code: "[/daku]",
          open: true,
        },
        {
          name: "大笑",
          img: "http://static.000081.xyz/img/emoji/daxiao.png",
          code: "[/daxiao]",
          open: true,
        },
        {
          name: "奋斗",
          img: "http://static.000081.xyz/img/emoji/fendou.png",
          code: "[/fendou]",
          open: true,
        },
        {
          name: "奸笑",
          img: "http://static.000081.xyz/img/emoji/jianxiao.png",
          code: "[/jianxiao]",
          open: true,
        },
        {
          name: "委屈",
          img: "http://static.000081.xyz/img/emoji/weiqu.png",
          code: "[/weiqu]",
          open: true,
        },
        {
          name: "嫌弃",
          img: "http://static.000081.xyz/img/emoji/xianqi.png",
          code: "[/xianqi]",
          open: true,
        },
        {
          name: "害羞",
          img: "http://static.000081.xyz/img/emoji/haixiu.png",
          code: "[/haixiu]",
          open: true,
        },
        {
          name: "尴尬",
          img: "http://static.000081.xyz/img/emoji/ganga.png",
          code: "[/ganga]",
          open: true,
        },
        {
          name: "微笑",
          img: "http://static.000081.xyz/img/emoji/weixiao.png",
          code: "[/weixiao]",
          open: true,
        },
        {
          name: "思考",
          img: "http://static.000081.xyz/img/emoji/sikao.png",
          code: "[/sikao]",
          open: true,
        },
        {
          name: "惊喜",
          img: "http://static.000081.xyz/img/emoji/jingxi.png",
          code: "[/jingxi]",
          open: true,
        },
        {
          name: "惊讶",
          img: "http://static.000081.xyz/img/emoji/jingya.png",
          code: "[/jingya]",
          open: true,
        },
        {
          name: "打call",
          img: "http://static.000081.xyz/img/emoji/dacall.png",
          code: "[/dacall]",
          open: true,
        },
        {
          name: "抓狂",
          img: "http://static.000081.xyz/img/emoji/zhuakuang.png",
          code: "[/zhuakuang]",
          open: true,
        },
        {
          name: "抠鼻",
          img: "http://static.000081.xyz/img/emoji/koubi.png",
          code: "[/koubi]",
          open: true,
        },
        {
          name: "抱拳",
          img: "http://static.000081.xyz/img/emoji/baoquan.png",
          code: "[/baoquan]",
          open: true,
        },
        {
          name: "捂眼",
          img: "http://static.000081.xyz/img/emoji/wuyan.png",
          code: "[/wuyan]",
          open: true,
        },
        {
          name: "捂脸",
          img: "http://static.000081.xyz/img/emoji/wulian.png",
          code: "[/wulian]",
          open: true,
        },
        {
          name: "撇嘴",
          img: "http://static.000081.xyz/img/emoji/piezui.png",
          code: "[/piezui]",
          open: true,
        },
        {
          name: "支持",
          img: "http://static.000081.xyz/img/emoji/zhichi.png",
          code: "[/zhichi]",
          open: true,
        },
        {
          name: "无语",
          img: "http://static.000081.xyz/img/emoji/wuyu.png",
          code: "[/wuyu]",
          open: true,
        },
        {
          name: "星星眼",
          img: "http://static.000081.xyz/img/emoji/xingxingyan.png",
          code: "[/xingxingyan]",
          open: true,
        },
        {
          name: "歪嘴",
          img: "http://static.000081.xyz/img/emoji/waizui.png",
          code: "[/waizui]",
          open: true,
        },
        {
          name: "滑稽",
          img: "http://static.000081.xyz/img/emoji/huaji.png",
          code: "[/huaji]",
          open: true,
        },
        {
          name: "点赞",
          img: "http://static.000081.xyz/img/emoji/dianzan.png",
          code: "[/dianzan]",
          open: true,
        },
        {
          name: "爱心",
          img: "http://static.000081.xyz/img/emoji/aixin.png",
          code: "[/aixin]",
          open: true,
        },
        {
          name: "狗头",
          img: "http://static.000081.xyz/img/emoji/goutou.png",
          code: "[/goutou]",
          open: true,
        },
        {
          name: "生气",
          img: "http://static.000081.xyz/img/emoji/shengqi.png",
          code: "[/shengqi]",
          open: true,
        },
        {
          name: "生病",
          img: "http://static.000081.xyz/img/emoji/shengbing.png",
          code: "[/shengbing]",
          open: true,
        },
        {
          name: "疑惑",
          img: "http://static.000081.xyz/img/emoji/yihuo.png",
          code: "[/yihuo]",
          open: true,
        },
        {
          name: "疼",
          img: "http://static.000081.xyz/img/emoji/teng.png",
          code: "[/teng]",
          open: true,
        },
        {
          name: "笑",
          img: "http://static.000081.xyz/img/emoji/xiao.png",
          code: "[/xiao]",
          open: true,
        },
        {
          name: "笑哭",
          img: "http://static.000081.xyz/img/emoji/xiaoku.png",
          code: "[/xiaoku]",
          open: true,
        },
        {
          name: "给心心",
          img: "http://static.000081.xyz/img/emoji/geixinxin.png",
          code: "[/geixinxin]",
          open: true,
        },
        {
          name: "翻白眼",
          img: "http://static.000081.xyz/img/emoji/fanbaiyan.png",
          code: "[/fanbaiyan]",
          open: true,
        },
        {
          name: "脸红",
          img: "http://static.000081.xyz/img/emoji/lianhong.png",
          code: "[/lianhong]",
          open: true,
        },
        {
          name: "调皮",
          img: "http://static.000081.xyz/img/emoji/tiaopi.png",
          code: "[/tiaopi]",
          open: true,
        },
        {
          name: "辣眼睛",
          img: "http://static.000081.xyz/img/emoji/layanjing.png",
          code: "[/layanjing]",
          open: true,
        },
        {
          name: "酸了",
          img: "http://static.000081.xyz/img/emoji/suanle.png",
          code: "[/suanle]",
          open: true,
        },
        {
          name: "阴险",
          img: "http://static.000081.xyz/img/emoji/yinxian.png",
          code: "[/yinxian]",
          open: true,
        },
        {
          name: "难过",
          img: "http://static.000081.xyz/img/emoji/nanguo.png",
          code: "[/nanguo]",
          open: true,
        },
        {
          name: "鼓掌",
          img: "http://static.000081.xyz/img/emoji/guzhang.png",
          code: "[/guzhang]",
          open: true,
        },
      ],
    });

    return {
      lzj_shop_info,
      lzj_shop_cart_base,
      lzj_shop_obj,
      lzj_shop_emoji,
      lzj_goto_shop_info,
      lzj_shop_update_message,
      lzj_shop_change_card_show,
    };
  },
};
</script>

<style>
.lzj_shop_chat {
  width: 100%;
  position: relative;
  height: 100%;
  overflow: scroll;
  background-color: #ececec;
}

.lzj_shop_chat_head {
  position: relative;
  width: 94%;
  margin-left: 3%;
  border-radius: 10px;
  margin-top: 15px;
  margin-bottom: 40px;
}

.lzj_shop_chat_body {
  position: relative;
  margin-bottom: 30px;
  margin-top: 30px;
  width: 100%;
}

.lzj_shop_chat_span {
  display: inline-block;
  position: relative;
  width: 35px;
  height: 35px;
  top: 8px;
  background-size: 100% 100%;
}

.lzj_shop_chat_baby {
  display: inline-block;
  position: relative;
  width: 200px;
  height: 200px;
  top: 8px;
  background-size: 100% 100%;
}
</style>