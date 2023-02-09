<template>
  <div class="lzj_shop_search_div">
    <div class="lzj_shop_search_div_box_img">
      <img
        src="http://static.000081.xyz/img/ss.png"
        style="position: relative; height: 100%; left: 0px"
      />
    </div>
    <div class="lzj_shop_search_div_box">
      <input
        type="text"
        class="lzj_shop_search_div_box_text"
        placeholder="请输入要搜索的宝贝"
      />
    </div>
    <div class="lzj_shop_search_div_serach_div">
      <input
        type="button"
        value="搜索"
        class="lzj_shop_search_div_serach_div_button"
      />
    </div>
  </div>
</template>

<script>
export default {};
</script>

<style>
.lzj_shop_search_div {
  width: 100%;
  height: 70px;
  background-color: #f7f7f7;
  position: relative;
}

.lzj_shop_search_div_box {
  width: 68%;
  height: 50px;
  position: relative;
  top: 10px;
  left: 11%;
  float: left;
  margin-right: 2.5%;
}

.lzj_shop_search_div_box_img {
  text-align: left;
  position: absolute;
  height: 50px;
  width: 50%;
  left: 2.5%;
  background-color: white;
  border-top: 3px solid #eaeaea;
  border-bottom: 3px solid #eaeaea;
  border-left: 3px solid #eaeaea;

  top: 10px;
}

.lzj_shop_search_div_box_text {
  position: relative;
  width: 98%;
  height: 100%;
  font-size: 26px;
  line-height: 50px;
  border-top: 3px solid #eaeaea;
  border-bottom: 3px solid #eaeaea;
  border-radius: 3px solid #eaeaea;
}

.lzj_shop_search_div_serach_div {
  position: relative;
  float: right;
  width: 17.5%;
  height: 50px;
  right: 2.5%;
  top: 10px;
}

.lzj_shop_search_div_serach_div_button {
  position: relative;
  width: 100%;
  height: 100%;
  background-color: #c40000;
  border-radius: 5%;
  color: white;
  line-height: 50px;
  font-size: 23px;
}
</style>