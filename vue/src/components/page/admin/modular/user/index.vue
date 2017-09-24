<template>
    <div>
      <x-header :left-options="{showBack: true}">
        会员 - 模块
        <i class="icon iconfont icon-fujian" slot="right" style="font-size: 30px;color: #19ce8b;" @click="$router.push('/admin/modular/user/add')"></i>
      </x-header>
      <msg v-if=" true !== this.$store.state.power.getDone ||  true !== this.$store.state.power.modular.user ||  1 !== this.$store.state.power.loginAdmin" :title="power.title" :description="power.description" :buttons="power.buttons" :icon="power.icon"></msg>
      <div v-else>
        <search @on-submit="searchSubmit" :auto-fixed="false" v-model="search.value"></search>
        <v-cell text="锁定/解锁会员" linkRight></v-cell>

      </div>
    </div>
</template>
<script>
  import {  Search,Msg,XHeader } from 'vux'
  import VCell from '../../../../common/cell'
  export default {
    components: {
      Search,Msg,XHeader,VCell
    },
    data () {
      return {
        search:{
          text:'',
          value:''
        },
        power:{
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
      }
    },
    created () {
      this.setTitle('会员 - 模块');
      this.$store.dispatch('update', {
        'model': 'common',
        'key': 'slideRoute',
        'value': this.$store.state.common.from.path
      });
      console.log(this.$store.state)
    },
    methods:{
      searchSubmit(){
        alert('Submit')
      },
      resLoading(){
        if ( false === this.$store.state.user.getDone){
          this.initData();
        }else{
          this.$vux.toast.text('没有权限', 'top')
        }
      },
    }
  }
</script>
