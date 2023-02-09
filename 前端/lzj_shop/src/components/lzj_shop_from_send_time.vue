<template>
  <div v-if="!props.in_index">
    <div class="lzj_shop_from_line1 lzj_shop_from_div1">
      <div style="font-weight: bold">送货时间：</div>
      <div>
        <div class="lzj_shop_from_list_div" style="padding-left: 0px">
          {{ props.send_time.data[props.send_time.check_id].text }}
        </div>
        <div style="clear: both"></div>
      </div>
    </div>
  </div>
  <div v-else>
    <div class="lzj_shop_from_line1 lzj_shop_from_div1">
      <div style="font-weight: bold">送货时间：</div>
      <div
        v-for="(i, index) in props.send_time.data"
        :key="index"
        @click="check_id(index)"
      >
        <div class="lzj_shop_from_list">
          <input
            v-if="i.id == props.send_time.check_id"
            type="radio"
            name="lzjtimeselect"
            :value="i.id"
            checked
          />
          <input v-else type="radio" name="lzjtimeselect" :value="i.id" />
        </div>
        <div class="lzj_shop_from_list_div">{{ i.text }}</div>
        <div style="clear: both"></div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    send_time: {
      type: Object,
      defalut: {},
    },
    in_index: {
      typeof: Boolean,
      default: false,
    },
  },
  setup(props, context) {
    const check_id = (index) => {
      props.send_time.check_id = index;
      context.emit("lzj_update_check", index);
    };
    return { props, check_id };
  },
};
</script>

<style>
.lzj_shop_from_line1 {
  width: 93%;
  padding-left: 20px;
  border-radius: 10px;
  background-color: #ffffff;
  min-height: 70px;
  text-align: left;
  font-size: 25px;
  line-height: 70px;
  margin-top: 15px;
}

.lzj_shop_from_div1 {
  padding-top: 15px;
  margin-left: 3.5%;
  line-height: 30px;
  padding-bottom: 15px;
  position: relative;
}

.lzj_shop_from_list {
  position: relative;
  float: left;
  max-width: 3%;
}

.lzj_shop_from_list_div {
  position: relative;
  margin-top: 3px;
  padding-left: 10px;
  float: left;
  max-width: 97%;
}
</style>