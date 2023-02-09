<template>
  <div class="shoplist">
    <el-dialog
      v-model="obj.show"
      :title="obj.title"
      width="80%"
      :draggable="true"
    >
      <div style="text-align: left">
        <div style="margin-bottom: 5px">
          <el-tag class="shoplisttag">名称</el-tag>
          <el-input
            style="width: 200px; height: 30px"
            v-model="obj.template.name"
            placeholder="请输入商品名称"
          ></el-input>
          <el-tag class="shoplisttag">分类</el-tag>
          <el-select
            v-model="obj.template.class"
            class="m-2"
            placeholder="请选择分类"
          >
            <el-option
              v-for="(item, index) in obj.twoclass"
              :key="index"
              :label="item.name"
              :value="item.name"
            />
          </el-select>
        </div>
        <div style="margin-bottom: 5px; margin-top: 5px">
          <el-tag class="shoplisttag">折扣前价格</el-tag>
          <el-input
            style="width: 200px; height: 30px"
            v-model="obj.template.lastprice"
            placeholder="请输入折扣前价格"
          ></el-input>
          <el-tag class="shoplisttag">折扣后价格</el-tag>
          <el-input
            style="width: 200px; height: 30px"
            v-model="obj.template.nowprice"
            placeholder="请输入折扣后价格"
          ></el-input>
        </div>
        <div style="margin-bottom: 5px">
          <el-tag class="shoplisttag">邮费</el-tag>
          <el-input
            style="width: 200px; height: 30px"
            v-model="obj.template.fare"
            placeholder="请输入邮费"
          ></el-input>
          <el-tag class="shoplisttag">库存</el-tag>
          <el-input
            style="width: 200px; height: 30px"
            v-model="obj.template.inventory"
            placeholder="请输入库存"
          ></el-input>
        </div>
        <div style="margin-bottom: 5px">
          <el-tag class="shoplisttag">推荐值</el-tag>
          <el-input
            style="width: 200px; height: 30px"
            v-model="obj.template.recommend"
            placeholder="请输入推荐值(0-3)"
          ></el-input>
        </div>
        <div style="margin-bottom: 5px">
          <el-tag class="shoplisttag">介绍标题</el-tag>
          <el-input
            style="width: 200px; height: 30px"
            v-model="obj.template.shopinfotitle"
            placeholder="请输入介绍标题"
          ></el-input>
        </div>
        <div style="margin-bottom: 5px">
          <el-tag class="shoplisttag">主页轮播图</el-tag>
          <el-input
            style="width: 250px; height: 30px"
            v-model="obj.template.indeximg"
            placeholder="请输入主页轮播图地址"
          ></el-input>
          <el-tag class="shoplisttag">次页轮播图</el-tag>
          <el-input
            style="width: 250px; height: 30px"
            v-model="obj.template.homeimg"
            placeholder="请输入次页轮播图地址"
          ></el-input>
        </div>
        <div style="margin-bottom: 5px">
          <el-tag class="shoplisttag">次页缩略图</el-tag>
          <el-input
            style="width: 250px; height: 30px"
            v-model="obj.template.shophomeimg"
            placeholder="请输入次页缩略图地址"
          ></el-input>
          <el-tag class="shoplisttag">分类列表图</el-tag>
          <el-input
            style="width: 250px; height: 30px"
            v-model="obj.template.shoplistimg"
            placeholder="请输入分类列表图地址"
          ></el-input>
        </div>
        <div style="margin-bottom: 5px">
          <el-tag class="shoplisttag">商品页轮播图</el-tag>
          <el-input
            v-model="obj.template.shopinfoimg"
            style="width: 400px"
            rows="2"
            type="textarea"
            placeholder="请输入商品页轮播图，英文逗号(,)分割"
          />
        </div>
        <div style="margin-bottom: 5px">
          <el-tag class="shoplisttag">商品介绍内容</el-tag>
          <el-input
            v-model="obj.template.introduce"
            style="width: 400px"
            rows="5"
            type="textarea"
            placeholder="请输入介绍内容"
          />
        </div>
      </div>
      <template #footer>
        <span class="dialog-footer">
          <el-button @click="obj.show = false">取消</el-button>
          <el-button type="primary" @click="confirmclick">确认</el-button>
        </span>
      </template>
    </el-dialog>
    <div class="shoplisthead">
      <el-button
        type="success"
        style="padding: 10px; font-size: 15px; margin-right: 20px"
        @click="addclick"
        round
        >添加商品</el-button
      >

      <el-input
        v-model="obj.search"
        style="width: 200px; height: 30px; float: right"
        size="small"
        placeholder="输入商品名搜索内容"
        @input="searchinput"
      />
      <div
        style="float: right; font-size: 15px; height: 30px; margin-right: 20px"
      >
        当前显示条数：
        <el-input-number v-model="obj.limit" :step="1" />
      </div>
    </div>
    <el-table
      :data="obj.showdata"
      height="80%"
      style="width: 100%; margin-bottom: 10px"
    >
      <el-table-column fixed="left" width="100" prop="id" label="ID" />
      <el-table-column fixed="left" width="100" prop="name" label="名称" />
      <el-table-column width="100" prop="class" label="分类" />
      <el-table-column width="100" prop="lastprice" label="折扣前价格" />
      <el-table-column width="100" prop="nowprice" label="折扣后价格" />
      <el-table-column width="100" prop="fare" label="邮费" />
      <el-table-column width="100" prop="inventory" label="库存" />
      <el-table-column width="100" prop="recommend" label="推荐值" />
      <el-table-column width="170" prop="createtime" label="创建时间" />
      <el-table-column width="170" prop="updatetime" label="更新时间" />
      <el-table-column width="170" prop="shopinfotitle" label="介绍标题" />
      <el-table-column width="200" prop="showtitle" label="介绍内容" />
      <el-table-column width="150" fixed="right" label="操作">
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
      tableData: [],
      saveData: [],
      sum: 0,
      showdata: [],
      page: 1,
      limit: 15,
      search: "",
      template: {},
      show: false,
      title: "",
      twoclass: [],
    });

    const deleteclick = (row) => {
      var id = obj.showdata[row].id;
      ElMessageBox.confirm(
        '是否确认删除 "' + obj.showdata[row].name + '"',
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
              .post(url.deleteshoplist, data)
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

    const confirmclick = () => {
      if (cookies.get("token") && cookies.get("token") != "") {
        let data = new FormData();
        data.append("token", cookies.get("token"));
        data.append("data", JSON.stringify(obj.template));
        axios
          .post(url.setshoplist, data)
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
      obj.title = "添加商品";
      obj.show = true;
      obj.template = {
        class: "",
        createtime: "",
        fare: "",
        homeimg: "",
        id: 0,
        indeximg: "",
        introduce: "",
        inventory: "",
        lastprice: "",
        name: "",
        nowprice: "",
        recommend: "",
        shophomeimg: "",
        shopinfoimg: "",
        shopinfotitle: "",
        shopintroduceimg: null,
        shoplistimg: "",
        updatetime: "",
      };
    };

    const editclick = (i) => {
      obj.title = "编辑商品";
      obj.show = true;
      obj.template = obj.showdata[i];
    };

    const searchinput = () => {
      obj.sum = Object.keys(obj.saveData).length;
      obj.tableData = obj.saveData;
      obj.page = 1;
      if (obj.search != "") {
        var cacheobj = [];
        for (var i = 0; i < Object.keys(obj.tableData).length; i++) {
          if (!Boolean(obj.tableData[i].name)) {
            continue;
          }
          if (
            obj.tableData[i].name
              .toLowerCase()
              .search(obj.search.toLowerCase()) != -1
          ) {
            cacheobj.push(obj.tableData[i]);
          }
        }
        obj.sum = Object.keys(cacheobj).length;
        obj.tableData = cacheobj;
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
        obj.tableData[i].showtitle = computetext(obj.tableData[i].introduce);
        if (obj.tableData[i].class == "" || obj.tableData[i].class == null) {
          obj.tableData[i].class = "未分类";
        }
        if (
          obj.tableData[i].fare == "" ||
          obj.tableData[i].fare == null ||
          parseFloat(obj.tableData[i].fare) == 0
        ) {
          obj.tableData[i].fare = "免邮费";
        }
        if (
          obj.tableData[i].inventory == "" ||
          obj.tableData[i].inventory == null
        ) {
          obj.tableData[i].inventory = "无限";
        }
        cacheobj.push(obj.tableData[i]);
      }
      obj.showdata = cacheobj;
    });

    const computetext = (text) => {
      if (text.length >= 100) {
        return text.substring(0, 94) + "……";
      }
      return text;
    };

    const updatepage = (val) => {};

    const getlist = () => {
      if (cookies.get("token") && cookies.get("token") != "") {
        let data = new FormData();
        data.append("token", cookies.get("token"));
        axios
          .post(url.getshoplist, data)
          .then(function (response) {
            if (JSON.parse(response.data.data).token == false) {
              ElMessage("未登入");
              router.push({ name: "login" });
            }
            obj.tableData = JSON.parse(response.data.data).data;
            obj.saveData = JSON.parse(response.data.data).data;
            obj.sum = Object.keys(obj.tableData).length;
            obj.twoclass = JSON.parse(response.data.data).class;
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
      computetext,
      searchinput,
      addclick,
      updatepage,
      editclick,
      confirmclick,
      deleteclick,
    };
  },
};
</script>

<style>
.shoplist {
  position: relative;
  width: 100%;
  height: 100%;
  overflow: hidden;
}

.footpage {
  position: relative;
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
}

.shoplisthead {
  position: relative;
  width: 100%;
  margin-bottom: 10px;
  text-align: left;
}

.shoplisttag {
  height: 30px;
  margin-right: 10px;
  margin-left: 10px;
}

.shoplisttext {
  width: 200px;
  height: 30px;
}
</style>