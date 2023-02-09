<template>
  <div class="baseread">
    <div
      style="
        text-align: center;
        font-weight: bold;
        margin-bottom: 30px;
        font-size: 25px;
      "
    >
      使用必读
    </div>
    <div style="text-align: left; text-indent: 2em">
      SWOFT是首个基于 Swoole 原生协程的新时代 PHP
      高性能协程全栈框架，内置协程网络服务器及常用的协程客户端，常驻内存，不依赖传统的
      PHP-FPM，全异步非阻塞 IO
      实现，以类似于同步客户端的写法实现异步客户端的使用，没有复杂的异步回调，没有繁琐的
      yield, 有类似 Go
      语言的协程、灵活的注解、强大的全局依赖注入容器、完善的服务治理、灵活强大的
      AOP、标准的 PSR
      规范实现等等，可以用于构建高性能的Web系统、API、中间件、基础服务等等。
    </div>
  </div>
</template>

<script>
export default {};
</script>

<style>
.baseread {
  position: relative;
  width: 100%;
  height: 100%;
  overflow: scroll;
}
</style>