<template>
  <div>
    <x-header :left-options="{showBack: true}">超级版主管理后台</x-header>
    <msg v-if=" false === power.is" :title="power.title" :description="power.description" :buttons="power.buttons" :icon="power.icon"></msg>
    <div v-else>
      <p>有权限</p>
    </div>
  </div>
</template>
<script>
  import {  Msg,XHeader  } from 'vux'
  import {init} from '../../../http/api'
  export default {
    components: {
      Msg,XHeader
    },
    data () {
      return {
        power:{
          is:false,
          title:'没有权限',
          description: '需要超级版主或以上权限访问...',
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
      this.setTitle('超级版主管理后台');
      this.$store.dispatch('update', {
        'model': 'common',
        'key': 'slideRoute',
        'value': '/tab4'
      });
      if (true === this.$store.state.power.getDone &&  3 >= this.$store.state.power.adminID &&  1 === this.$store.state.power.loginAdmin){
        this.power.is  =  false;
      }
    },
    methods: {
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
