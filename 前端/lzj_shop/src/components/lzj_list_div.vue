<template>
  <div
    class="lzj_list_div"
    v-for="(i, inde) in lzj_shop_obj.items_data"
    :key="inde"
  >
    <div v-if="lzj_shop_obj.items_class_show">
      <div class="lzj_list_div_title">
        <div class="lzj_list_div_title_sum">
          <div class="lzj_list_div_title_tag"></div>
          <div class="lzj_list_div_title_text">{{ i.class }}</div>
        </div>
        <div style="clear: both"></div>
        <div class="lzj_list_div_div" v-for="(ii, ind) in i.data" :key="ind">
          <div class="lzj_list_div_div_list">
            <router-link :to="{ name: 'info', params: { shopid: ii.id } }">
              <lzjlistdivitem
                :item_price="ii.price"
                :item_img_url="ii.img_url"
                :item_title="ii.title"
                :item_url="ii.url"
              ></lzjlistdivitem
            ></router-link>
          </div>
        </div>
      </div>
      <div style="clear: both"></div>
    </div>
    <div v-else>
      <div class="lzj_list_div_title">
        <div class="lzj_list_div_title_sum">
          <div class="lzj_list_div_title_tag"></div>
          <div
            class="lzj_list_div_title_text"
            @click="lzj_class_name_click(i.id)"
          >
            {{ i.class }}
          </div>
        </div>
        <div style="clear: both"></div>
        <div class="lzj_list_div_div" v-for="(ii, ind) in i.data" :key="ind">
          <div
            v-for="(iii, iin) in ii.data"
            :key="iin"
            class="lzj_list_div_div_list"
          >
            <router-link :to="{ name: 'info', params: { shopid: iii.id } }">
              <lzjlistdivclassitem
                :item_price="iii.price"
                :item_img_url="iii.img_url"
                :item_title="iii.title"
                :item_url="iii.url"
                :id="'item' + iii.id"
              >
              </lzjlistdivclassitem
            ></router-link>
          </div>
        </div>
      </div>
      <div style="clear: both"></div>
    </div>
  </div>
</template>

<script>
import { defineComponent, inject } from "@vue/runtime-core";
import lzjlistdivitem from "@/components/lzj_list_div_items.vue";
import lzjlistdivclassitem from "@/components/lzj_list_div_class_items.vue";
export default defineComponent({
  components: { lzjlistdivitem, lzjlistdivclassitem },
  emits: ["lzj_class_name_click"],
  setup(props, context) {
    const lzj_shop_obj = inject("lzj_shop_obj");
    const lzj_class_name_click = (id) => {
      context.emit("lzj_class_name_click", id);
    };
    return {
      lzj_shop_obj,
      lzj_class_name_click,
    };
  },
});
</script>

<style>
.lzj_list_div {
  width: 100%;
  background-color: #ececec;
  position: relative;
  padding-top: 35px;
  padding-left: 3%;
  padding-bottom: 15px;
}

.lzj_list_div_title {
  width: auto;
  text-align: left;
  height: 35px;
}

.lzj_list_div_title_sum {
  position: relative;
  width: 100%;
  height: 100%;
  margin-bottom: 10px;
}

.lzj_list_div_title_tag {
  position: relative;
  width: 10px;
  height: 100%;
  float: left;
  margin-right: 15px;
  background-color: #c30000;
}

.lzj_list_div_title_text {
  position: relative;
  float: left;
  width: auto;
  max-width: 70%;
  overflow: hidden;
  font-size: 32px;
  height: 35px;
  line-height: 35px;
}

.lzj_list_div_div {
  width: 100%;
  height: auto;
  background-color: #ffffff;
  position: relative;
}

.lzj_list_div_div_list {
  width: 47%;
  margin-top: 10px;
  margin-left: 1%;
  height: auto;
  background-color: #ffffff;
  float: left;
  position: relative;
}
</style>