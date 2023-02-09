<template>
  <router-view></router-view>
</template>

<script>
export default {
  name: "App",
  components: {},
  setup() {},
};
</script>

<style>
* {
  box-sizing: border-box;
  padding: 0;
  border: 0;
  margin: 0;
}

a {
  text-decoration: none;
  color: rgb(37, 9, 9);
}

#lzj_app {
  position: absolute;
  text-align: center;
  width: 100%;
  height: 100%;
  max-width: 640px;
  overflow: scroll;
  color: #2c3e50;
}
</style>
