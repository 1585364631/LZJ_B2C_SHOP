<template>
  <div class="collect">
    <div class="collecthead">
      <el-input
        v-model="obj.search"
        style="width: 200px; height: 30px; float: right"
        size="small"
        placeholder="输入搜索内容"
        @input="searchinput"
      />
      <div
        style="float: right; font-size: 15px; height: 30px; margin-right: 20px"
      >
        当前显示条数：
        <el-input-number v-model="obj.limit" :step="1" />
        <el-select
          v-model="obj.selectvalue"
          class="m-2"
          placeholder="选择搜索字段"
          size="small"
          style="margin-left: 20px"
        >
          <el-option
            v-for="(item, index) in obj.word"
            :key="index"
            :label="item.key"
            :value="item.value"
          />
        </el-select>
      </div>
    </div>
    <div style="clear: both; margin-bottom: 10px"></div>
    <el-table
      :data="obj.showdata"
      height="80%"
      style="width: 100%; margin-bottom: 10px"
    >
      <el-table-column prop="id" label="ID" />
      <el-table-column prop="shopid" label="商品id" />
      <el-table-column prop="username" label="用户名" />
      <el-table-column prop="collectdate" label="时间" />
      <el-table-column fixed="right" label="操作">
        <template #default="scope">
          <el-button link type="primary" @click="deleteclick(scope.$index)"
            >删除</el-button
          >
        </template>
      </el-table-column>
    </el-table>
    <div class="footpage">
      <el-pagination
        background
        layout="prev, pager, next"
        :total="obj.sum"
        v-model:current-page="obj.page"
        v-model:page-size="obj.limit"
        @current-change="updatepage"
      />
    </div>
  </div>
</template>

<script>
import { reactive, ref } from "@vue/reactivity";
import { getCurrentInstance, onMounted, watch, watchEffect } from "vue";
import router from "@/router";
import { ElMessage, ElMessageBox } from "element-plus";
export default {
  setup() {
    let internalInstance = getCurrentInstance();
    let cookies = internalInstance.appContext.config.globalProperties.$cookies;
    let axios = internalInstance.appContext.config.globalProperties.$axios;
    let url = internalInstance.appContext.config.globalProperties.$url;
    const obj = reactive({
      limit: 15,
      search: "",
      page: 1,
      selectvalue: "",
      data: [],
      showdata: [],
      sum: 0,
      word: [],
      cachedata: [],
      struct: {
        id: "ID",
        shopid: "商品ID",
        username: "用户名",
        collectdate: "日期",
      },
    });

    watchEffect(() => {
      if (obj.data.length > 0) {
        obj.word = [];
        var keysz = Object.keys(obj.data[0]);
        for (var i = 0; i < keysz.length; i++) {
          var cacheobj = {};
          cacheobj.value = keysz[i];
          cacheobj.key = obj.struct[cacheobj.value];
          obj.word.push(cacheobj);
        }
      }
    });

    const getlist = () => {
      if (cookies.get("token") && cookies.get("token") != "") {
        let data = new FormData();
        data.append("token", cookies.get("token"));
        axios
          .post(url.getcollect, data)
          .then(function (response) {
            if (JSON.parse(response.data.data).token == false) {
              ElMessage("未登入");
              router.push({ name: "login" });
            }
            obj.data = JSON.parse(response.data.data).data;
            obj.cachedata = obj.data;
            obj.sum = obj.cachedata.length;
            searchinput();
          })
          .catch(function (error) {
            console.log(error);
          });
      } else {
        ElMessage("未登入");
        router.push({ name: "login" });
      }
    };

    watch(
      () => obj.selectvalue,
      () => {
        searchinput();
      }
    );

    const searchinput = () => {
      obj.sum = Object.keys(obj.data).length;
      obj.cachedata = obj.data;
      obj.page = 1;
      if (obj.selectvalue == "") {
        return;
      }

      if (obj.search != "") {
        var cacheobj = [];
        for (var i = 0; i < Object.keys(obj.data).length; i++) {
          if (!Boolean(obj.data[i][obj.selectvalue])) {
            continue;
          }
          if (
            obj.data[i][obj.selectvalue]
              .toLowerCase()
              .search(obj.search.toLowerCase()) != -1
          ) {
            cacheobj.push(obj.data[i]);
          }
        }
        obj.sum = Object.keys(cacheobj).length;
        obj.cachedata = cacheobj;
      }
    };

    watchEffect(() => {
      var end = obj.page * obj.limit;
      var start = (obj.page - 1) * obj.limit;
      if (start < 0) {
        start = 0;
      }
      if (end < obj.limit) {
        end = obj.limit;
      }
      if (end >= obj.sum) {
        end = obj.sum;
      }
      var cacheobj = [];
      for (var i = start; i < end; i++) {
        cacheobj.push(obj.cachedata[i]);
      }
      obj.showdata = cacheobj;
    });

    const updatepage = (val) => {};

    const deleteclick = (row) => {
      var id = obj.showdata[row].id;
      ElMessageBox.confirm(
        '是否确认删除 "' + obj.showdata[row].id + '"',
        "警告！",
        {
          confirmButtonText: "确认",
          cancelButtonText: "取消",
          type: "warning",
        }
      )
        .then(() => {
          if (cookies.get("token") && cookies.get("token") != "") {
            let data = new FormData();
            data.append("token", cookies.get("token"));
            data.append("id", id);
            axios
              .post(url.deletecollect, data)
              .then(function (response) {
                if (JSON.parse(response.data.data).token == false) {
                  ElMessage("未登入");
                  router.push({ name: "login" });
                } else {
                  ElMessage({
                    type: "success",
                    message: JSON.parse(response.data.data).text,
                  });
                  getlist();
                }
              })
              .catch(function (error) {
                console.log(error);
              });
          } else {
            ElMessage("未登入");
            router.push({ name: "login" });
          }
        })
        .catch(() => {
          ElMessage({
            type: "info",
            message: "操作取消",
          });
        });
    };

    onMounted(() => {
      getlist();
    });

    return {
      obj,
      searchinput,
      updatepage,
      deleteclick,
    };
  },
};
</script>

<style>
.collect {
  position: relative;
  width: 100%;
  height: 100%;
}

.collecthead {
  position: relative;
  width: 100%;
  text-align: left;
}
</style>