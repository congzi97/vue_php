<template>
    <div>
      <x-header :left-options="{showBack: true}">超级管理员</x-header>
      <msg v-if=" true !== this.$store.state.power.getDone ||  1 !== this.$store.state.power.adminID ||  1 !== this.$store.state.power.loginAdmin" :title="power.title" :description="power.description" :buttons="power.buttons" :icon="power.icon"></msg>
      <div v-else>
        <!--顶部tab-->
        <tab style="background: #35495e;" defaultColor="#fff">
          <tab-item selected @on-item-click="onItemClick">模块管理</tab-item>
          <tab-item @on-item-click="onItemClick">MySQL数据库</tab-item>
          <tab-item @on-item-click="onItemClick">网站管理</tab-item>
        </tab>
        <!--模块管理显示-->
        <v-m-list v-if=" 0 === this.tabIndex "></v-m-list>
        <adminMySql v-if=" 1 === this.tabIndex "></adminMySql>
        <admin v-if=" 2 === this.tabIndex "></admin>
      </div>
    </div>
</template>
<script>
  import { Tab, TabItem, Msg,XHeader  } from 'vux'
  import {init} from '../../../http/api'
  import VMList from './modular/all'
  import adminMySql from './modular/mysql'
  import admin from './modular/admin'
  export default {
    components: {
      Tab, TabItem, Msg,XHeader,VMList,adminMySql,admin
    },
    data () {
      return {
        power:{
          is:false,
          title:'没有权限',
          description: '不是超级管理员禁止访问...',
          icon: 'warn',
          buttons: [{
            type: 'primary',
            text: '重新加载',
            onClick: this.resLoading.bind(this)
          }, {
            type: 'default',
            text: '返回个人中心',
            link: '/tab4'
          }]
        },
        tabIndex:0,
      }
    },
    created () {
      this.setTitle('超级管理员');
      this.$store.dispatch('update', {
        'model': 'common',
        'key': 'slideRoute',
        'value': '/tab4'
      });
    },
    methods: {
      onItemClick (index) {
        this.tabIndex = index;
      },
      resLoading(){
        if ( false === this.$store.state.user.getDone){
          this.initData();
        }else{
          this.$vux.toast.text('没有权限', 'top')
        }
      },
      initData(){
        init({}).then(res => {
          console.log(res);
          if ( 200 === res['code']){
            res['data']['power']['modular'] = JSON.parse(res['data']['power']['modular']);
            this.$store.dispatch('update',{
              'model':'',
              'key':'user',
              'value':res['data']['user']
            });
            this.$store.dispatch('update',{
              'model':'',
              'key':'power',
              'value':res['data']['power']
            });
            // 保存登录信息
            setStore('sid',res['data']['user']['sid']);
          }else{
            this.$vux.toast.text(res['msg']);
          }
        });
      },
    },
  }
</script>
