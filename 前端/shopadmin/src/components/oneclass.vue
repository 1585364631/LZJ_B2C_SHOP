<template>
  <div class="oneclass">
    <div v-if="obj.show">
      <listdivVue
        :obj="obj.template"
        :objtitle="obj.title"
        :listbutton="obj.listbutton"
        @listclick="listclick"
      ></listdivVue>
    </div>
    <el-table :data="obj.tableData" height="90%" style="width: 100%">
      <el-table-column prop="id" label="ID" />
      <el-table-column prop="name" label="名称" />
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
import listdivVue from "./listdiv.vue";
import router from "@/router";
import { ElMessage, ElMessageBox } from "element-plus";

export default {
  components: {
    listdivVue,
  },
  setup() {
    let internalInstance = getCurrentInstance();
    let cookies = internalInstance.appContext.config.globalProperties.$cookies;
    let axios = internalInstance.appContext.config.globalProperties.$axios;
    let url = internalInstance.appContext.config.globalProperties.$url;
    const obj = reactive({
      tableData: [
        {
          id: "0",
          name: "衣服",
        },
      ],
      show: false,
      template: [
        {
          title: "id",
          value: "",
          open: false,
        },
        {
          title: "名称",
          value: "",
          open: true,
        },
      ],
      title: "",
      listbutton: [
        {
          title: "保存",
        },
        {
          title: "取消",
        },
      ],
    });

    const editclick = (row) => {
      var rows = obj.tableData[row];
      obj.template[0].value = rows.id;
      obj.template[1].value = rows.name;
      obj.title = "编辑一级分类";
      obj.show = true;
    };

    const listclick = (status) => {
      if (status && obj.template[1].value != "") {
        if (cookies.get("token") && cookies.get("token") != "") {
          let data = new FormData();
          data.append("token", cookies.get("token"));
          data.append("id", obj.template[0].value);
          data.append("name", obj.template[1].value);
          axios
            .post(url.editoneclass, data)
            .then(function (response) {
              if (JSON.parse(response.data.data).token == false) {
                ElMessage("未登入");
                router.push({ name: "login" });
              }
              ElMessage(JSON.parse(response.data.data).text);
              getlist();
              obj.show = false;
            })
            .catch(function (error) {
              console.log(error);
            });
        } else {
          ElMessage("未登入");
          router.push({ name: "login" });
        }
      } else {
        obj.show = false;
      }
    };

    const addclick = () => {
      obj.template[0].value = 0;
      obj.template[1].value = "";
      obj.title = "添加一级分类";
      obj.show = true;
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
              .post(url.deleteoneclass, data)
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

    const getlist = () => {
      if (cookies.get("token") && cookies.get("token") != "") {
        let data = new FormData();
        data.append("token", cookies.get("token"));
        axios
          .post(url.getoneclass, data)
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
      editclick,
      listclick,
      addclick,
      deleteclick,
    };
  },
};
</script>

<style>
.oneclass {
  position: relative;
  width: 100%;
  height: 100%;
  overflow: hidden;
}
</style>