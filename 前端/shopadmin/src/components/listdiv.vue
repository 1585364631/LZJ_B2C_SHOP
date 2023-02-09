<template>
  <div class="listdiv">
    <div class="listcard">
      <div style="padding: 20px; line-height: 40px">
        <div>{{ props.objtitle }}</div>
        <div v-for="(i, index) in props.obj" :key="index">
          <el-input
            v-if="i.open"
            v-model="i.value"
            :placeholder="i.title"
            style="width: 300px"
          />
        </div>
        <div style="margin-top: 10px">
          <el-button type="success" round @click="buttonclick(true)">{{
            props.listbutton[0].title
          }}</el-button>
          <el-button type="info" round @click="buttonclick(false)">{{
            props.listbutton[1].title
          }}</el-button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    obj: {
      type: Object,
      default: {},
    },
    objtitle: {
      type: String,
      default: "",
    },
    listbutton: {
      type: Object,
      default: {},
    },
  },
  setup(props, context) {
    const buttonclick = (status) => {
      context.emit("listclick", status);
    };
    return {
      props,
      buttonclick,
    };
  },
};
</script>

<style>
.listdiv {
  position: absolute;
  z-index: 999;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #393d49;
  opacity: 0.8;
}

.listcard {
  position: relative;
  background-color: #ffffff;
}
</style>