<template>
  <div class="twoclass">
    <el-dialog v-model="obj.show" :title="obj.title">
      <span>父类：</span>
      <el-select
        v-model="obj.selectvalue"
        class="m-2"
        placeholder="请选择父类"
        size="large"
      >
        <el-option
          v-for="(item, index) in obj.oneclassdata"
          :key="index"
          :label="item.name"
          :value="item.name"
        />
      </el-select>
      <div style="margin-top: 20px"></div>
      <span>名称：</span>
      <el-input
        v-model="obj.classname"
        placeholder="请输入名称"
        size="large"
        class="m-2"
        style="display: inline"
      ></el-input>
      <template #footer>
        <span class="dialog-footer">
          <el-button @click="obj.show = false">取消</el-button>
          <el-button type="primary" @click="confirmclick">确认</el-button>
        </span>
      </template>
    </el-dialog>
    <el-table :data="obj.tableData" height="90%" style="width: 100%">
      <el-table-column prop="id" label="ID" />
      <el-table-column prop="name" label="名称" />
      <el-table-column label="父类">
        <template #default="scope">
          <el-tag>{{ scope.row.onename }}</el-tag>
        </template>
      </el-table-column>
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
    <div style="text-align: left">
      <el-button
        type="success"
        style="margin-top: 20px; padding: 10px; font-size: 15px"
        @click="addclick"
        round
        >添加分类</el-button
      >
    </div>
  </div>
</template>

<script>
import { reactive } from "@vue/reactivity";
import { getCurrentInstance, onMounted } from "vue";
import router from "@/router";
import { ElMessage, ElMessageBox } from "element-plus";
export default {
  components: {},
  setup() {
    let internalInstance = getCurrentInstance();
    let cookies = internalInstance.appContext.config.globalProperties.$cookies;
    let axios = internalInstance.appContext.config.globalProperties.$axios;
    let url = internalInstance.appContext.config.globalProperties.$url;
    const obj = reactive({
      tableData: [],
      oneclassdata: [],
      show: false,
      title: "",
      selectvalue: "",
      classname: "",
      selectid: "",
    });

    const getlist = () => {
      if (cookies.get("token") && cookies.get("token") != "") {
        let data = new FormData();
        data.append("token", cookies.get("token"));
        axios
          .post(url.gettwoclass, data)
          .then(function (response) {
            if (JSON.parse(response.data.data).token == false) {
              ElMessage("未登入");
              router.push({ name: "login" });
            }
            obj.tableData = JSON.parse(response.data.data).data;
          })
          .catch(function (error) {
            console.log(error);
          });
        axios
          .post(url.getoneclass, data)
          .then(function (response) {
            if (JSON.parse(response.data.data).token == false) {
              ElMessage("未登入");
              router.push({ name: "login" });
            }
            obj.oneclassdata = JSON.parse(response.data.data).data;
          })
          .catch(function (error) {
            console.log(error);
          });
      } else {
        ElMessage("未登入");
        router.push({ name: "login" });
      }
    };

    const deleteclick = (row) => {
      var id = obj.tableData[row].id;
      ElMessageBox.confirm(
        '是否确认删除 "' + obj.tableData[row].name + '"',
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
              .post(url.deletetwoclass, data)
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
                  obj.show = false;
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
      var rows = obj.tableData[row];
      obj.title = "编辑分类";
      obj.selectvalue = obj.oneclassdata[row].name;
      obj.classname = obj.tableData[row].name;
      obj.selectid = obj.tableData[row].id;
      obj.show = true;
    };

    const confirmclick = () => {
      if (cookies.get("token") && cookies.get("token") != "") {
        let data = new FormData();
        data.append("token", cookies.get("token"));
        if (obj.title == "添加分类") {
          data.append("id", 0);
        } else {
          data.append("id", obj.selectid);
        }
        data.append("parent", obj.selectvalue);
        data.append("name", obj.classname);
        axios
          .post(url.edittwoclass, data)
          .then(function (response) {
            if (JSON.parse(response.data.data).token == false) {
              ElMessage("未登入");
              router.push({ name: "login" });
            }
            ElMessage({
              type: "success",
              message: JSON.parse(response.data.data).text,
            });
            getlist();
          })
          .catch(function (error) {
            console.log(error);
          });
      } else {
        ElMessage("未登入");
        router.push({ name: "login" });
      }
      obj.show = false;
    };

    const addclick = () => {
      obj.title = "添加分类";
      obj.selectvalue = "";
      obj.classname = "";
      obj.show = true;
    };

    onMounted(() => {
      getlist();
    });

    return {
      obj,
      addclick,
      editclick,
      confirmclick,
      deleteclick,
    };
  },
};
</script>

<style>
.twoclass {
  position: relative;
  width: 100%;
  height: 100%;
  overflow: hidden;
}
</style>