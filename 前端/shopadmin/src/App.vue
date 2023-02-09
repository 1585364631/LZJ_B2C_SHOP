<template>
  <router-view></router-view>
</template>

<script>
export default {
  name: "App",
  components: {},
};
</script>

<style>
* {
  box-sizing: border-box;
  padding: 0px;
  border: 0px;
  margin: 0px;
}
::-webkit-scrollbar {
  display: none;
}

#app {
  position: absolute;
  height: 100%;
  width: 100%;
}
</style>
