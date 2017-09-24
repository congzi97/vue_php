<template>
  <div class="app-main">
    <!--头部-->
    <x-header :left-options="{showBack: false}">CZ博客</x-header>

    <swiper :list="demo01_list" ></swiper>

    <!-- 导航 start -->
    <div class="pet_circle_nav" style="background: #fff">
      <ul class="pet_circle_nav_list">
        <li><a href="JavaScript:;" class="iconfont pet_nav_sy icon-shequ6 "></a><span>社区</span></li>
        <li @click="$router.push('/forum/index')"><a href="JavaScript:;" class="iconfont pet_nav_zhangzhishi icon-luntan "></a><span>论坛</span></li>
        <li><a href="JavaScript:;" class="iconfont pet_nav_kantuya ">&#xe62b;</a><span>产品</span></li>
        <li><a href="JavaScript:;" class="iconfont pet_nav_mengzhuanti icon-xiaoxi"></a><span>交流</span></li>
        <li><a href="JavaScript:;" class="iconfont pet_nav_meirong icon-yuandaima"></a><span>源码</span></li>
        <li><a href="JavaScript:;" class="iconfont pet_nav_yiyuan ">&#xe602;</a><span>模块</span></li>
        <li><a href="JavaScript:;" class="iconfont pet_nav_dianpu icon-kaifa"></a><span>开发</span></li>
        <li><a href="javascript:;" class="iconfont pet_nav_gengduo ">&#xe600;</a><span>更多</span></li>
      </ul>
      <!--点击更多显示的内容-->
      <div class="pet_more_list">
        <div class="pet_more_list_block">
        <div class="iconfont pet_more_close">×</div>
        <div class="pet_more_list_block">
          <div class="pet_more_list_block_name">
            <div class="pet_more_list_block_name_title">阅读 Read</div>
            <a class="pet_more_list_block_line">
              <i class="iconfont pet_nav_xinxianshi pet_more_list_block_line_ico">&#xe61e;</i>
              <div class="pet_more_list_block_line_font">新鲜事</div>
            </a>
            <a class="pet_more_list_block_line">
              <i class="iconfont pet_nav_zhangzhishi pet_more_list_block_line_ico">&#xe607;</i>
              <div class="pet_more_list_block_line_font">趣闻</div>
            </a>
            <a class="pet_more_list_block_line">
              <i class="iconfont pet_nav_kantuya pet_more_list_block_line_ico">&#xe62c;</i>
              <div class="pet_more_list_block_line_font">阅读</div>
            </a>
            <a class="pet_more_list_block_line">
              <i class="iconfont pet_nav_mengzhuanti pet_more_list_block_line_ico">&#xe622;</i>
              <div class="pet_more_list_block_line_font">专题</div>
            </a>
            <a class="pet_more_list_block_line">
              <i class="iconfont pet_nav_bk pet_more_list_block_line_ico">&#xe629;</i>
              <div class="pet_more_list_block_line_font">订阅</div>
            </a>
            <a class="pet_more_list_block_line">
              <i class="iconfont pet_nav_wd pet_more_list_block_line_ico">&#xe602;</i>
              <div class="pet_more_list_block_line_font">专栏</div>
            </a>
            <div class="pet_more_list_block_name_title pet_more_list_block_line_height">服务 Service</div>
            <a class="pet_more_list_block_line">
              <i class="iconfont pet_nav_xinxianshi pet_more_list_block_line_ico">&#xe61e;</i>
              <div class="pet_more_list_block_line_font">新鲜事</div>
            </a>
            <a class="pet_more_list_block_line">
              <i class="iconfont pet_nav_zhangzhishi pet_more_list_block_line_ico">&#xe607;</i>
              <div class="pet_more_list_block_line_font">趣闻</div>
            </a>
            <a class="pet_more_list_block_line">
              <i class="iconfont pet_nav_kantuya pet_more_list_block_line_ico">&#xe62c;</i>
              <div class="pet_more_list_block_line_font">阅读</div>
            </a>
            <a class="pet_more_list_block_line">
              <i class="iconfont pet_nav_mengzhuanti pet_more_list_block_line_ico">&#xe622;</i>
              <div class="pet_more_list_block_line_font">专题</div>
            </a>
            <a class="pet_more_list_block_line">
              <i class="iconfont pet_nav_bk pet_more_list_block_line_ico">&#xe629;</i>
              <div class="pet_more_list_block_line_font">订阅</div>
            </a>
            <a class="pet_more_list_block_line">
              <i class="iconfont pet_nav_wd pet_more_list_block_line_ico">&#xe602;</i>
              <div class="pet_more_list_block_line_font">专栏</div>
            </a>
          </div>
        </div>
        </div>
      </div>
    </div>
    <!-- 导航 end -->

    <!--内容显示-->


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
      <tabbar-item badge="2" link = '/tab4'>
        <i slot="icon" class=" iconfont icon-wode" style="font-size: 1.8rem;"></i>
        <span slot="label">我的</span>
      </tabbar-item>
    </tabbar>
  </div>
</template>
<script>
// 导航更多
// 阅读  : 新鲜事 最新教程 热门教程 好评教程 关注(开发者)
// 服务  : 爬虫  嗅探
  import { Swiper, XHeader, Tabbar, TabbarItem } from 'vux'
  export default {
    components: {
      Swiper,
      XHeader,
      Tabbar,
      TabbarItem
    },
    data(){
      return {
        demo01_list: [{
          url: 'javascript:',
          img: 'https://static.vux.li/demo/1.jpg',
          title: '送你一朵fua'
        }, {
          url: 'javascript:',
          img: 'https://static.vux.li/demo/2.jpg',
          title: '送你一辆车'
        }, {
          url: 'javascript:',
          img: 'https://static.vux.li/demo/3.jpg',
          title: '送你一次旅行'
        }]
      };
    },
    created(){
      this.setTitle('首页');
    }
  }
</script>
<style>
  .app-main .weui-tabbar__item{padding: 0;padding-top: 5px;}
  .app-main .weui-tabbar__icon  sup {top: 0px;}
  .app-main .vux-reddot:after, .vux-reddot-border:after, .vux-reddot-s:after {top: 2px;}
  .app-main .weui-tabbar__label {font-size: 16px;}
</style>
