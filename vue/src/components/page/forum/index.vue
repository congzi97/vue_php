<template>
    <div>
      <x-header :left-options="{showBack: true}">
        论坛
        <!--<i class="icon iconfont icon-right" slot="right" style="font-size: 30px;color: #19ce8b;"  @click="setFocus" ></i>-->
      </x-header>
      <!--搜索显示-->
      <search
        @result-click="resultClick"
        @on-change="getResult"
        :results="search.results"
        v-model="search.value"
        auto-scroll-to-top top="0px"
        @on-focus="onFocus"
        @on-cancel="onCancel"
        @on-submit="onSubmit"
        ref="search"></search>
      <!--tab选项卡-->
      <!--<div style="width: 100%;overflow:scroll;-webkit-overflow-scrolling:touch;">-->
        <!--<tab style="width:100%;" bar-active-color="#668599" :line-width="1">-->
          <!--<tab-item selected>最新帖子</tab-item>-->
          <!--<tab-item>热门帖子</tab-item>-->
          <!--<tab-item>精品帖子</tab-item>-->
        <!--</tab>-->
      <!--</div>-->
      <!--<ul class="post-list">-->
        <!--<li v-for="item in 3 ">-->
          <!--<div class="p-title">刚发的上海警察贴，想删，</div>-->
          <!--<div class="p-info">-->
            <!--<div class="author">秋秋112233</div>-->
            <!--<div class="time">-->
              <!--<span class="icon t-view">604</span>-->
              <!--<span>09-01 20:52</span>-->
            <!--</div>-->
          <!--</div>-->
        <!--</li>-->
        <!--<div style="height: 30px;line-height: 30px;background: #fff;padding-right: 20px;">-->
          <!--<span style="float: right;">-->
            <!--查看更多-->
            <!--<i class="icon iconfont icon-right" style="font-size: 20px;color: #19ce8b;float: right;"></i>-->
          <!--</span>-->
        <!--</div>-->
      <!--</ul>-->
      <divider>交流</divider>
      <div class="forum-list" style="background: #fff;">
        <div class="item" v-for="item in 4">
          <div class="forum-icon">
            <i class="icon iconfont icon-official"></i>
          </div>
          <div class="info">
            <h3>XX论坛</h3>
            <p>论坛信息</p>
          </div>
          <div class="right">
            <i class="icon iconfont icon-right"></i>
          </div>
        </div>
      </div>


    </div>
</template>
<script>
  import { Divider, Search, Tab, TabItem,XHeader } from 'vux'
  import {forum as getForum } from '../../../http/api'
  export default {
    components: {
      Divider, Search, Tab, TabItem,XHeader
    },
    data () {
      return {
        search:{
          results: [],
          value: '',
        }
      }
    },
    created () {
      this.setTitle('论坛');
      this.$store.dispatch('update', {
        'model': 'common',
        'key': 'slideRoute',
        'value': '/'
      });
      this.getForumList();
    },
    methods:{
      getForumList(){
        getForum({
          'mod':'getForumList'
        })
          .then(res => {
            console.log(res);
          })
      },
      setFocus () {
        this.$refs.search.setFocus()
      },
      resultClick (item) {
        window.alert('you click the result item: ' + JSON.stringify(item))
      },
      getResult (val) {
        this.results = val ? this.getMoreSearch(this.value) : []
      },
      onSubmit () {
        this.$refs.search.setBlur()
        this.$vux.toast.show({
          type: 'text',
          position: 'top',
          text: 'on submit'
        })
      },
      onFocus () {
        // 点击时候触发

      },
      onCancel () {
        // 点击取消

      },
      getMoreSearch (val) {
        let rs = []
        for (let i = 0; i < 20; i++) {
          rs.push({
            title: `${val} result: ${i + 1} `,
            other: i
          })
        }
        return rs
      }
    }
  }
</script>
<style>
  .item {position: relative;overflow: hidden;border-bottom: 1px solid #efefef;margin: 5px 0 5px 15px;}
  .item .forum-icon {float: left;width: 20%;text-align: center;}
  .item .forum-icon i {font-size: 40px;color: #19ce8b;}
  .item .info{float: left;line-height: 20px;width: 80%;margin-top: 10px;}
  .item .info h3 {font-size: 1rem;}
  .item .info span {font-size: .6rem;}
  .item .right {position: absolute;top: 25px;right: 20px;}
  .post-list li {
    background: #fff;
    display: block;
    padding: 10px;
    overflow: hidden;
    border-bottom: 1px solid #dfdfdf;
  }
  .post-list li .p-title {
    word-break: break-all;
    word-wrap: break-word;
    font-size: 16px;
    line-height: 22px;
    margin-bottom: 10px;
  }
  .post-list li .p-info {
    margin-top: 10px;
    font-size: 14px;
    line-height: 20px;
  }
  .post-list li .p-info .author {
    float: left;
    color: #4b7bab;
  }
  .post-list li .p-info .time {
    float: right;
    color: #afafaf;
  }
  .post-list li .p-info .time .t-view {
    padding-left: 15px;
    background-position: 0 -376px;
    margin-right: 5px;
  }
</style>

