<template>
  <div class="app-main">
    <!--头部-->
    <x-header :left-options="{showBack: false}">do not show Back</x-header>

    我是tab2

    <!--底部-->
    <div style="height: 66px;width: 100%;"></div>
    <tabbar style="position: fixed;">
      <tabbar-item link = '/'>
        <i slot="icon" class=" iconfont icon-shouye" style="font-size: 1.8rem;"></i>
        <span slot="label">首页</span>
      </tabbar-item>
      <tabbar-item selected show-dot link = '/tab2'>
        <i slot="icon" class=" iconfont icon-zhuanti" style="font-size: 1.8rem;"></i>
        <span slot="label">专题</span>
      </tabbar-item>
      <tabbar-item link = '/tab3'>
        <i slot="icon" class=" iconfont icon-xiaoxi" style="font-size: 1.8rem;"></i>
        <span slot="label">消息</span>
      </tabbar-item>
      <tabbar-item badge="2" link = '/tab4'>
        <i slot="icon" class=" iconfont icon-wode" style="font-size: 1.8rem;"></i>
        <span slot="label">我的</span>
      </tabbar-item>
    </tabbar>
  </div>
</template>
<script>
  import { XHeader, Tabbar, TabbarItem } from 'vux'
  export default {
    components: {
      XHeader,
      Tabbar,
      TabbarItem
    },
    created(){
      this.setTitle('专题');
    }
  }
</script>
