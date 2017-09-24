<template>
  <div>
    <x-header :left-options="{showBack: true}">
      论坛 - 模块
      <i class="icon iconfont icon-fujian" slot="right" style="font-size: 30px;color: #19ce8b;" @click="$router.push('/admin/modular/forum/add')"></i>
    </x-header>
    <msg v-if=" true !== this.$store.state.power.getDone ||  true !== this.$store.state.power.modular.user ||  1 !== this.$store.state.power.loginAdmin || true === this.loadFail" :title="power.title" :description="power.description" :buttons="power.buttons" :icon="power.icon"></msg>
    <div v-else>
      <search @on-submit="searchSubmit" :auto-fixed="false" v-model="search.value"></search>
      <v-cell text="添加论坛分类" linkRight></v-cell>

    </div>
  </div>
</template>
<script>
  import {  Search,Msg,XHeader } from 'vux'
  import VCell from '../../../../common/cell'
  import {admin} from './../../../../../http/api'
  export default {
    components: {
      Search,Msg,XHeader,VCell
    },
    data () {
      return {
        loadFail:false,
        search:{
          text:'',
          value:''
        },
        power:{
          title:'没有权限',
          description: '你当前没有权限操作',
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
      this.setTitle('论坛 - 模块');
      this.$store.dispatch('update', {
        'model': 'common',
        'key': 'slideRoute',
        'value': '/admin/index'
      });
      // 读取配置
      admin({
        'mod':['moduleForumInit']
      })
        .then(res => {
          this.$store.dispatch('update',{
            'model' : 'forum',
            'key'   : 'classification',
            'value' : res['data']['classification'],
          })
        })
        .catch(() => {
          this.loadFail = true;
        })
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
