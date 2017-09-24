<template>
  <div class="app-main tab4">

    <!-- 未登录显示 -->
    <div class="notLogin" v-if=" false === $store.state.user.login">
      <!--头部-->
      <blur :blur-amount=40 :url="$store.state.user.avatar" class="u-h-bg">
        <p class="center"><img :src="$store.state.user.avatar"></p>
        <p style="padding-top: 10px;">
          {{$store.state.user.nick}}
          <i class="icon iconfont " :class="'男' == $store.state.user.sex ? ' icon-nan icon-nan-color ' : ' icon-nv icon-nv-color ' "></i>
          <br />
          {{$store.state.user.autograph}}
        </p>
      </blur>

      <div class="" style="margin: 20% 0 ;">
        <x-button plain type="primary" link="/login" style="border-radius:99px;">登录</x-button>
        <div class="" style="margin:50px 0 ;">
          <x-button plain type="primary" link="/sign" style="border-radius:99px;">注册</x-button>
        </div>
      </div>


    </div>
    <!-- 登录显示 -->
    <div class="d-login " v-else>
      <!--头部-->
      <!--<x-header :left-options="{showBack: false}">-->
        <!--个人中心-->
        <!--<a slot="right" style="top: 10px;"><i class="iconfont icon-wode"></i></a>-->
      <!--</x-header>-->

      <blur :blur-amount=40 :url="$store.state.user.avatar" class="u-h-bg">
        <p class="center"><img :src="$store.state.user.avatar"  @click="seeAvatar=true"></p>
        <p style="padding-top: 10px;">
          {{$store.state.user.nick}}
          <i class="icon iconfont " :class="'男' == $store.state.user.sex ? ' icon-nan icon-nan-color ' : ' icon-nv icon-nv-color ' "></i>
          <br />
          {{$store.state.user.autograph}}
        </p>
      </blur>

      <card :header="{title:'我的钱包'}" style="background: #fff;color: #44CC00;">
        <div slot="content" class="card-demo-flex card-demo-content01">
          <div class="vux-1px-l vux-1px-r">
            <span>{{$store.state.user.bi}}</span>
            <br/>
            <i class="icon iconfont icon-icon-test"></i>
          </div>
          <div class="vux-1px-r">
            <span>{{$store.state.user.jifen}}</span>
            <br/>
            <i class="icon iconfont icon-jifen"></i>
          </div>
          <div class="vux-1px-r">
            <span>{{$store.state.user.experience}}</span>
            <br/>
            <i class="icon iconfont icon-jingyan"></i>
          </div>
        </div>
      </card>


      <!--显示后台进入链接-->
      <div v-if="true === $store.state.power.getDone && 1 === $store.state.power.loginAdmin">
        <div class="cell-box" v-if=" 1 === $store.state.power.adminID">
          <v-cell text="超级管理员" icon=" icon-chaojiguanliyuan " link="/admin/maxPower" linkRight></v-cell>
        </div>
        <div class="cell-box" v-if=" 2 === $store.state.power.adminID">
          <v-cell text="站长" icon=" icon-chaojiguanliyuan " link="/admin/admin-001" linkRight></v-cell>
        </div>
        <div class="cell-box" v-if=" 3 === $store.state.power.adminID">
          <v-cell text="超级版主" icon=" icon-chaojiguanliyuan " link="/admin/admin-001" linkRight></v-cell>
        </div>
        <div class="cell-box" v-if=" 4 === $store.state.power.adminID">
          <v-cell text="版主" icon=" icon-chaojiguanliyuan " link="/admin/admin-001" linkRight></v-cell>
        </div>
      </div>
      <!--设置-->
      <div class="cell-box">
        <v-cell text="设置" icon=" icon-setting " link="/user/setting" linkRight></v-cell>
      </div>

    </div>
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
      <tabbar-item link = '/tab3'>
        <i slot="icon" class=" iconfont icon-xiaoxi" style="font-size: 1.8rem;"></i>
        <span slot="label">消息</span>
      </tabbar-item>
      <tabbar-item selected badge="2" link = '/tab4'>
        <i slot="icon" class=" iconfont icon-wode" style="font-size: 1.8rem;"></i>
        <span slot="label">我的</span>
      </tabbar-item>
    </tabbar>

    <div v-transfer-dom>
      <x-dialog v-model="seeAvatar" class="dialog-demo">
        <div class="img-box">
          <img :src="$store.state.user.avatar" style="max-width:100%">
        </div>
        <div @click="seeAvatar=false">
          <span class="vux-close"></span>
        </div>
      </x-dialog>
    </div>

  </div>
</template>
<script>
  import VCell from '../../common/cell.vue'
  import { XDialog,XButton,Grid, GridItem, Divider, Tabbar, TabbarItem,  Blur, Card , TransferDomDirective as TransferDom   } from 'vux'
  export default {
    directives: {
      TransferDom
    },
    components: {
      XDialog,
      XButton,
      Tabbar,
      TabbarItem,
      Divider,
      Grid,
      GridItem,
      Blur,
      Card,
      VCell,
    },
    data(){
      return{
        seeAvatar:false,
      }
    },
    created(){
      this.setTitle('个人中心');
    }
  }
</script>
<style>
  .cell-box {position: relative;overflow: hidden;padding-top: 20px;}

  .tab4 .weui-panel {margin-top: 0;background: #35495e;}
  .tab4 .weui-panel:before{border-top: 0;}
  .tab4 .weui-panel h4 {color: #fff;}
  .tab4  .vux-header-right {top: 10px;}

.u-h-bg{
  background: url("/static/img/bg.jpeg");
  background-size: 100% 100%;
  color: #fff;
  font-size: 18px;
  text-align: center;
  padding-top: 20px;
}
  .center {

  }
  .center img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    border: 4px solid #ececec;
  }
</style>
<style scoped lang="less">
  @import '~vux/src/styles/1px.less';
  .card-demo-flex {
    display: flex;
  }
  .card-demo-content01 {
    padding: 10px 0;
  }
  .card-padding {
    padding: 15px;
  }
  .card-demo-flex > div {
    flex: 1;
    text-align: center;
    font-size: 12px;
  }
  .card-demo-flex span {
    color: #f74c31;
  }
</style>
<style lang="less" scoped>
  @import '~vux/src/styles/close';
  .dialog-demo {
    .weui-dialog{
      border-radius: 8px;
      padding-bottom: 8px;
    }
    .dialog-title {
      line-height: 30px;
      color: #666;
    }
    .img-box {
      height: 350px;
      overflow: hidden;
    }
    .vux-close {
      margin-top: 8px;
      margin-bottom: 8px;
    }
  }
</style>
