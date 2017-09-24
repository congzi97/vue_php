<template>
    <div>
      <x-header :left-options="{showBack: true}">手动添加会员</x-header>
      <msg v-if=" true !== this.$store.state.power.getDone ||  true !== this.$store.state.power.modular.user ||  1 !== this.$store.state.power.loginAdmin" :title="power.title" :description="power.description" :buttons="power.buttons" :icon="power.icon"></msg>
      <div class="page-content" style="margin-top: 20px;" v-else>
        <divider>请填写会员信息</divider>
        <group>
          <x-input v-model="email" title="邮箱" placeholder="请输入邮箱" is-type="email"></x-input>
          <x-input v-model="password" title="密码" placeholder="请输入密码" type="password"></x-input>
          <x-input v-model="password2" title="确定密码" placeholder="确定密码" type="password"></x-input>
        </group>
        <div style="margin: 20px 0;border-top: 1px solid #efefef;" @click="addUser">
          <x-button type="primary" action-type="button" :show-loading="button.disabled" :disabled="button.disabled">{{button.text}}</x-button>
        </div>
      </div>

      <div v-transfer-dom>
        <x-dialog v-model="showDialog" hide-on-blur :dialog-style="{'max-width': '100%', width: '100%', height: '50%', 'background-color': 'transparent'}">
          <p style="color:#fff;text-align:center;" @click="showDialog = false">
            <span style="font-size:30px;" v-html="showDialogText"></span>
            <br>
            <br>
            <x-icon type="ios-close-outline" style="fill:#fff;"></x-icon>
          </p>
        </x-dialog>
      </div>

    </div>
</template>
<script>
  import { Msg,TransferDomDirective as TransferDom , XDialog,XButton, XInput, Group, XHeader,Divider } from 'vux'
  import {admin} from '../../../../../http/api'
  export default {
    directives: {
      TransferDom
    },
    components: {
      Msg,XDialog,XButton, XInput, Group, XHeader,Divider
    },
    data () {
      return {
        button:{
          disabled:false,
          text:'确定',
        },
        email:'',
        password:'',
        password2:'',
        showDialog    : false,
        showDialogText: '',
      }
    },
    created () {
      this.setTitle('手动添加会员');
      this.$store.dispatch('update', {
        'model': 'common',
        'key': 'slideRoute',
        'value': '/admin/modular/user/index'
      });
    },
    methods:{
      addUser(){
        this.button.disabled  = true;
        this.button.text      = '请稍候...';
        admin({
          'mod':'addUser',
          'email':this.email,
          'password':this.password,
          'password2':this.password2,
        })
          .then(res => {
            this.button.disabled  = false;
            this.button.text      = '确定';
            this.showDialog = true;
            this.showDialogText = res['msg'];
          })
          .catch(res => {
            this.button.disabled  = false;
            this.button.text      = '确定';
            this.showDialog = true;
            this.showDialogText = '请检查网络';
          })
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
