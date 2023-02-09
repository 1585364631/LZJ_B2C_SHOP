<template>
  <div class="view404">
    <div style="margin-right: 20px">
      <el-icon style="display: block"><PictureRounded /></el-icon>
    </div>
    <div>404! 页面不存在</div>
  </div>
</template>

<script setup>
import router from "@/router";
import { ElMessage } from "element-plus";
ElMessage("3秒后跳转");
setTimeout(() => {
  ElMessage("2秒后跳转");
  setTimeout(() => {
    ElMessage("1秒后跳转");
    setTimeout(() => {
      router.push({ name: "login" });
    }, 1000);
  }, 1000);
}, 1000);
</script>

<style >
.view404 {
  position: relative;
  width: 100%;
  height: 100%;
  font-size: 60px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: chocolate;
  background-image: url("https://gimg2.baidu.com/image_search/src=http%3A%2F%2Fimg.zcool.cn%2Fcommunity%2F01b97655683aa40000012b205d0020.jpg&refer=http%3A%2F%2Fimg.zcool.cn&app=2002&size=f9999,10000&q=a80&n=0&g=0n&fmt=auto?sec=1659576793&t=7c0b1b3aa1ccb47a7be9cfb50fcdb2c3");
}
</style>