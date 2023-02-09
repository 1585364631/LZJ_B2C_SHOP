<template>
  <div class="message">
    <div class="message_left">
      <div
        v-for="(i, index) in obj.data.data"
        :key="index"
        class="message_div"
        @click="messageclick(i.id)"
      >
        <el-avatar shape="square" :size="50" :src="i.img" />
        <span style="line-height: 50px; font-size: 20px; margin-left: 10px">{{
          i.username
        }}</span>
      </div>
    </div>
    <div class="message_right">
      <div class="message_top" ref="message_top">
        <div
          v-for="(i, index) in lzj_shop_obj.data"
          :key="index"
          style="margin-top: 10px"
        >
          <lzjshopchatmessage
            :lzj_message="i"
            :lzj_img="lzj_shop_obj.img"
          ></lzjshopchatmessage>
        </div>
        <div id="gomessagebottom" ref="gomessagebottom"></div>
      </div>
      <div class="message_bottom" ref="message_bottom">
        <div class="message_card">
          <div class="messagetext">
            <el-input
              v-model="message_input"
              type="textarea"
              placeholder="请输入消息"
              style="width: 100%"
              resize="none"
              :rows="5"
            />
          </div>
          <div class="messagesend">
            <el-button
              type="primary"
              round
              style="display: block"
              @click="messagesend"
              >发送</el-button
            >
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script >
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

function usersort(sz) {
  var newsz = [];
  newsz = Object.keys(sz).sort(function (a, b) {
    return Date.parse(sz[b + ""].time) - Date.parse(sz[a + ""].time);
  });
  var newobj = {};
  for (var i in newsz) {
    newobj[sz[newsz[i] + ""].id] = sz[newsz[i] + ""];
  }
  return newobj;
}

import lzjshopchatmessage from "../components/message_chat.vue";
import { reactive, ref } from "@vue/reactivity";
import {
  getCurrentInstance,
  onBeforeUnmount,
  onMounted,
  watchEffect,
} from "vue";
import listdivVue from "./listdiv.vue";
import router from "@/router";
import { ElMessage, ElMessageBox } from "element-plus";
export default {
  components: {
    lzjshopchatmessage,
  },
  setup() {
    let internalInstance = getCurrentInstance();
    let cookies = internalInstance.appContext.config.globalProperties.$cookies;
    let axios = internalInstance.appContext.config.globalProperties.$axios;
    let url = internalInstance.appContext.config.globalProperties.$url;
    var timer;
    const message_input = ref("");
    const gomessagebottom = ref(null);
    const message_top = ref(null);
    const message_bottom = ref(null);

    const messagesend = () => {
      if (message_input.value == "") {
        ElMessage("输入内容不能为空");
        return;
      }
      if (cookies.get("token") && cookies.get("token") != "") {
        let data = new FormData();
        data.append("token", cookies.get("token"));
        data.append("text", message_input.value);
        data.append("id", obj.selectid.substring(3));
        axios
          .post(url.sendmessage, data)
          .then(function (response) {
            if (JSON.parse(response.data.data).token == false) {
              router.push({ name: "login" });
            }
            if (JSON.parse(response.data.data).text != "发送成功") {
              ElMessage(JSON.parse(response.data.data).text);
            }
            message_input.value = "";
            console.log(JSON.parse(response.data.data));
            clearInterval(timer);
            getmessage();
          })
          .catch(function (error) {
            console.log(error);
          });
      } else {
        ElMessage("未登入");
        router.push({ name: "login" });
      }
    };

    const updatescroll = () => {
      gomessagebottom.value.style.height =
        message_bottom.value.clientHeight + "px";
    };

    const updatesort = () => {
      obj.data.data = usersort(obj.data.data);
      if (obj.selectid == "") {
        for (var key in obj.data.data) {
          obj.selectid = key;
          break;
        }
      }
    };

    const updatetime = () => {
      clearInterval(timer);
      timer = setInterval(() => {
        getmessage();
      }, 2000);
    };

    const updatedata = () => {
      lzj_shop_obj.img.you = obj.data.data[obj.selectid].img;
      lzj_shop_obj.data = obj.data.data[obj.selectid].data;
      for (var i in lzj_shop_obj.data) {
        lzj_shop_obj.data[i].text = codeinimg(
          lzj_shop_obj.data[i].text,
          lzj_shop_emoji.data
        );
      }
      gotobottom();
    };

    const gotobottom1 = () => {
      gomessagebottom.value.scrollIntoView({
        behavior: "smooth",
        block: "center",
        inline: "nearest",
      });
    };

    const gotobottom = () => {
      setTimeout(() => {
        if (message_top.value.scrollTop == 0) {
          gotobottom1();
        }
        try {
          if (
            message_top.value.scrollHeight - 100 <=
            message_top.value.scrollTop + message_top.value.clientHeight
          ) {
            gotobottom1();
          }
        } catch {}
      }, 100);
    };

    const messageclick = (id) => {
      if (obj.selectid != id) {
        obj.selectid = id;
        updatedata();
      }
    };

    const obj = reactive({
      data: [],
      selectid: "",
    });

    const lzj_shop_obj = reactive({
      img: {
        my: "http://static.000081.xyz/img/sc_12_1.jpg",
        you: "http://static.000081.xyz/img/sc_14_1.jpg",
      },
      data: [
        {
          from: false,
          text: "你好，客服",
        },
      ],
    });

    onBeforeUnmount(() => {
      clearInterval(timer);
    });

    const getmessage = () => {
      if (cookies.get("token") && cookies.get("token") != "") {
        let data = new FormData();
        data.append("token", cookies.get("token"));
        axios
          .post(url.getmessage, data)
          .then(function (response) {
            if (JSON.parse(response.data.data).token == false) {
              ElMessage("未登入");
              router.push({ name: "login" });
            }
            obj.data = JSON.parse(response.data.data).data;
            lzj_shop_obj.img.my = obj.data.myimg;
            updatesort();
            updatedata();
            updatetime();
          })
          .catch(function (error) {
            console.log(error);
          });
      } else {
        ElMessage("未登入");
        router.push({ name: "login" });
      }
    };

    onMounted(() => {
      getmessage();
      updatescroll();
      gotobottom();
    });

    const lzj_shop_emoji = reactive({
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
      messageclick,
      lzj_shop_emoji,
      lzj_shop_obj,
      message_input,
      obj,
      gomessagebottom,
      message_top,
      message_bottom,
      messagesend,
    };
  },
};
</script>

<style>
.message {
  position: relative;
  width: 100%;
  height: 100%;
  background-color: #ffffff;
  overflow: hidden;
}

.message_left {
  position: absolute;
  width: 25%;
  height: 100%;
  overflow: scroll;
  left: 0;
  background-color: #eae8e7;
}

.message_right {
  position: absolute;
  width: 75%;
  height: 100%;
  right: 0;
  overflow: hidden;
}

.message_top {
  position: relative;
  width: 100%;
  height: 100%;
  overflow: scroll;
  padding-top: 10px;
  padding-bottom: 10px;
  background-color: #fff0f0;
}

.message_bottom {
  display: flex;
  position: absolute;
  width: 100%;
  height: 30%;
  bottom: 0;
  z-index: 999;
  background-color: antiquewhite;
}

.messagesend {
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 5px;
}

.messagetext {
  width: 95%;
  margin-top: 1%;
  display: flex;
  justify-content: center;
  align-items: center;
  margin-left: 2.5%;
}

.message_card {
  position: relative;
  width: 100%;
  height: 100%;
}

.message_div {
  display: flex;
  justify-content: left;
  padding-left: 20px;
  margin-top: 10px;
  align-items: left;
  margin-bottom: 10px;
  padding-bottom: 10px;
  border-bottom: 1px solid #000;
}
</style>