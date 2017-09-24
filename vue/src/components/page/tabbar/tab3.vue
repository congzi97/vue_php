<template>
  <div class="app-main">
    <!--头部-->
    <x-header :left-options="{showBack: false}">do not show Back</x-header>

    <swipeout>

      <swipeout-item @on-close="handleEvents('on-close')" @on-open="handleEvents('on-open')" transition-mode="follow">
        <div slot="right-menu">
          <swipeout-button @click.native="onButtonClick('fav')" type="primary">A</swipeout-button>
          <swipeout-button @click.native="onButtonClick('delete')" type="warn">B</swipeout-button>
        </div>
        <div slot="content" class="demo-content vux-1px-t">
          <panel :list="list" :type="type"></panel>
        </div>
      </swipeout-item>

    </swipeout>
    <!--底部-->
    <div style="height: 66px;width: 100%;"></div>
    <tabbar style="position: fixed;">
      <tabbar-item selected link = '/'>
        <i slot="icon" class=" iconfont icon-shouye" style="font-size: 1.8rem;"></i>
        <span slot="label">首页</span>
      </tabbar-item>
      <tabbar-item show-dot link = '/tab2'>
        <i slot="icon" class=" iconfont icon-zhuanti" style="font-size: 1.8rem;"></i>
        <span slot="label">专题</span>
      </tabbar-item>
      <tabbar-item selected link = '/tab3'>
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
  import { Swipeout, SwipeoutItem, SwipeoutButton ,Panel, XHeader, Tabbar, TabbarItem } from 'vux'
  export default {
    components: {
      XHeader,
      Tabbar,
      TabbarItem,
      Panel,
      Swipeout, SwipeoutItem, SwipeoutButton
    },
    data(){
      return {
        disabled: false,
        type:'1',
        list: [{
          src: this.$store.state.user.avatar,
          title: this.$store.state.user.nick,
          desc: '信息',
          url: ''
        }],
      };
    },
    created(){
      this.setTitle('消息');
    },
    methods: {
      onButtonClick (type) {
        alert('on button click ' + type)
      },
      handleEvents (type) {
        console.log('event: ', type)
      }
    },
  }
</script>
