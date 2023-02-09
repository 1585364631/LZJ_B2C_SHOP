# LZJ_B2C_SHOP
本项目为B2C商城系统，初步接触微服务，前端分为前台和后台，前台（移动端）使用Vue3+Axios+Router等技术开发，后台（电脑端）使用Vue3+Axios+Router+Element UI Plus开发，后端使用PHP的Swoft微服务框架开发，项目运行为docker容器，使用docker-compose编排

# 测试地址
## 前台（移动端，浏览器可使用F12功能选择移动样式）地址：
http://shop.000081.xyz/

## 后台（PC端）地址：
http://shopadmin.000081.xyz/  
测试账号：admin  
测试密码：admin

# docker镜像以及构建文件：
由于github单文件限制只能100mb，所以docker镜像文件和镜像构建文件使用阿里云盘
http://pan.000081.xyz/%E5%88%86%E4%BA%AB/LZJ_B2C_SHOP

## [二选一]自行构建镜像
下载网盘中的shop.zip文件并且解压，进入每个文件夹中自行docker build构建镜像，随后修改docker-compose.yaml的镜像即可

## [二选一]使用docker导入已经构建好的镜像
下载shopimg.tar文件，并且导入镜像
```bash
docker load -i shopimg.tar
```
