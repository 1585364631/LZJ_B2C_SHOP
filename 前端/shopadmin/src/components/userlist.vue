<template>
  <div style="position: relative; width: 100%; height: 100%">
    <el-dialog
      v-model="obj.show"
      :title="obj.title"
      width="60%"
      :draggable="true"
    >
      <div style="margin-bottom: 5px">
        <el-tag class="shoplisttag">用户名</el-tag>
        <el-input
          style="width: 300px; height: 30px"
          v-model="obj.template.username"
          placeholder="请输入用户名（必填）"
        ></el-input>
      </div>
      <div style="margin-bottom: 5px">
        <el-tag class="shoplisttag">密码</el-tag>
        <el-input
          style="width: 300px; height: 30px"
          v-model="obj.template.password"
          placeholder="请输入密码（必填）"
        ></el-input>
      </div>
      <div style="margin-bottom: 5px">
        <el-tag class="shoplisttag">昵称</el-tag>
        <el-input
          style="width: 300px; height: 30px"
          v-model="obj.template.name"
          placeholder="请输入昵称（必填）"
        ></el-input>
      </div>
      <div style="margin-bottom: 5px">
        <el-tag class="shoplisttag">邮箱</el-tag>
        <el-input
          style="width: 300px; height: 30px"
          v-model="obj.template.email"
          placeholder="请输入邮箱（可选）"
        ></el-input>
      </div>
      <div style="margin-bottom: 5px">
        <el-tag class="shoplisttag">头像</el-tag>
        <el-input
          style="width: 300px; height: 30px"
          v-model="obj.template.headimg"
          placeholder="请输入头像地址（可选）"
        ></el-input>
      </div>
      <div style="margin-bottom: 5px">
        <el-tag class="shoplisttag">手机号码</el-tag>
        <el-input
          style="width: 300px; height: 30px"
          v-model="obj.template.phone"
          placeholder="请输入手机号码（可选）"
        ></el-input>
      </div>
      <div style="margin-bottom: 5px">
        <el-tag class="shoplisttag">默认选中地址</el-tag>
        <el-input
          style="width: 300px; height: 30px"
          v-model="obj.template.address"
          placeholder="请输入默认选中地址（可选）"
        ></el-input>
      </div>
      <div style="margin-bottom: 5px">
        <el-tag class="shoplisttag">用户类型</el-tag>
        <el-input
          style="width: 400px; height: 30px"
          v-model="obj.template.admin"
          placeholder="请输入用户类型（0为普通用户，1为管理员用户）"
        ></el-input>
      </div>
      <template #footer>
        <span class="dialog-footer">
          <el-button @click="obj.show = false">取消</el-button>
          <el-button type="primary" @click="confirmclick">确认</el-button>
        </span>
      </template>
    </el-dialog>
    <el-button
      type="success"
      style="padding: 10px; font-size: 15px; float: left"
      @click="addclick"
      round
      >添加用户</el-button
    >
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
    <div style="clear: both; margin-bottom: 10px"></div>
    <el-table
      :data="obj.showdata"
      height="80%"
      style="width: 100%; margin-bottom: 10px"
    >
      <el-table-column
        v-for="(i, index) in Object.keys(obj.struct)"
        :key="index"
        :prop="i"
        :label="obj.struct[i]"
      />
      <el-table-column fixed="right" label="操作">
        <template #default="scope">
          <el-button link type="primary" @click="editclick(scope.$index)"
            >编辑</el-button
          >
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
import { ElMessage, ElMessageBox } from "element-plus";
import router from "@/router";
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
        username: "用户名",
        password: "密码",
        name: "昵称",
        email: "邮箱",
        headimg: "头像",
        phone: "手机号码",
        address: "默认选中地址",
        admin: "用户类型",
      },
      show: false,
      template: {},
      title: "",
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
          .post(url.getlist, data)
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
              .post(url.deletelist, data)
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

    const editclick = (row) => {
      obj.show = true;
      obj.template = obj.showdata[row];
      obj.title = "编辑用户";
    };
    const addclick = () => {
      obj.show = true;
      obj.template = {
        id: "0",
        username: "",
        password: "",
        name: "",
        email: "",
        headimg: "",
        phone: "",
        address: "",
        admin: "",
      };
      obj.title = "添加用户";
    };

    const confirmclick = () => {
      if (cookies.get("token") && cookies.get("token") != "") {
        let data = new FormData();
        data.append("token", cookies.get("token"));
        data.append("data", JSON.stringify(obj.template));
        axios
          .post(url.editlist, data)
          .then(function (response) {
            if (JSON.parse(response.data.data).token == false) {
              ElMessage("未登入");
              router.push({ name: "login" });
            } else {
              obj.show = false;
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
    };

    onMounted(() => {
      getlist();
    });

    return {
      obj,
      searchinput,
      updatepage,
      deleteclick,
      editclick,
      addclick,
      confirmclick,
    };
  },
};
</script>

<style>
</style>