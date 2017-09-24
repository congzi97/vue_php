<template>
    <div style="background: #cbc8d9;">

      <x-header :left-options="{showBack: true}">找回密码</x-header>

      <div class="from" style="padding: 10px 11px;color: #1AAD19;">
        <div class="f-item">
          <div class="input" style="width: 70%;">
            <input :disabled="passInput" type="text" v-model="email" placeholder='请输入邮箱'>
          </div>
          <div class="f-right" style="width: 30%;" @click="getVerify">
            <x-button mini type="primary" :disabled="noneGetButton" :show-loading="loadGetButton">{{textGetButton}}</x-button>
          </div>
        </div>
        <div class="f-item">
          <div class="input" style="width: 70%;">
            <input :disabled="!passInput" type="text" v-model="verify" placeholder='请输入验证码'>
          </div>
          <div class="f-right" style="width: 30%;">
            <x-button mini type="primary">{{second}}秒</x-button>
          </div>
        </div>

        <transition name="slide-fade">
          <div v-if=" true === passInput">
            <div class="f-item">
              <div class="input" style="width: 100%;">
                <input type="password" v-model="password" placeholder='请输入密码'>
              </div>
            </div>
            <div class="f-item">
              <div class="input" style="width: 100%;">
                <input type="password" v-model="password2" placeholder='确定密码'>
              </div>
            </div>
          </div>
        </transition>

        <verifySlider :done="VSDone"></verifySlider>
      </div>

      <div style="margin: 10px 0;" @click="resetPass">
        <x-button :disabled="noneButton" :show-loading="loadButton" plain type="warn" style="border-radius:99px;">
          {{textButton}}
        </x-button>
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
  import { TransferDomDirective as TransferDom , XDialog,XButton,XHeader } from 'vux'
  import verifySlider from '../../common/verifySlider.vue'
  import {forgetPass} from '../../../http/api'
  export default {
    directives: {
      TransferDom
    },
    components: {
      XDialog,XButton,XHeader,verifySlider
    },
    data () {
      return {
        email         : '',
        verify        : '',
        noneGetButton : false,
        loadGetButton : false,
        textGetButton : '获取验证码',
        noneButton    : true,
        loadButton    : false,
        textButton    : '确定',
        passInput     : false,
        second        : 300,
        showDialog    : false,
        showDialogText: '',
        password      : '',
        password2     : '',
        verifySlider  : false,
      }
    },
    created () {
      this.setTitle('找回密码');
      this.$store.dispatch('update', {
        'model': 'common',
        'key': 'slideRoute',
        'value': '/tab4'
      });
    },
    methods:{
      resetPass(){
        if ( '' === this.verify ){
          this.showDialog     = true;
          this.showDialogText = '请输入验证码';
          return ;
        }
        if (  '' === this.password || '' === this.password2){
          this.showDialog     = true;
          this.showDialogText = '请输入密码';
          return ;
        }
        this.noneButton = true;
        this.loadButton = true;
        this.textButton = '请稍候...';

        forgetPass({
          'mod' : 'res',
          'email':this.email,
          'verify':this.verify,
          'password':this.password,
          'password2':this.password2,
        }).then(res => {
          this.noneButton = false;
          this.loadButton = false;
          this.textButton = '确定';
          this.showDialog     = true;
          this.showDialogText = res['msg'];
        })
          .catch(res => {
            this.noneButton = false;
            this.loadButton = false;
            this.textButton = '确定';

            this.showDialog     = true;
            this.showDialogText = res['msg'];
          });
      },
      getVerify(){
        if ( false === this.verifySlider){
          this.showDialog     = true;
          this.showDialogText = '请拖动验证码';
          return ;
        }
        if ( '' === this.email || false === this.isEmail(this.email)){
          this.showDialog     = true;
          this.showDialogText = '请输入正确的邮箱';
          return ;
        }
        this.noneGetButton = true;
        this.loadGetButton = true;
        this.textGetButton = '请稍候';
        forgetPass({
          'mod' : 'getVerify',
          'email':this.email
        }).then(res => {
          this.noneGetButton = false;
          this.loadGetButton = false;
          this.textGetButton = '获取验证码';
          this.showDialog     = true;
          this.showDialogText = res['msg'];
          if ( 200 === res['code']){
            this.passInput  = true;
            this.noneButton = false;
            let s = '';
            s = setInterval(()=>{
              this.second -= 1;
              if ( 0 === this.second){
                clearInterval(s)
              }
            },1000);
          }
        })
          .catch(res => {
            this.noneGetButton = false;
            this.loadGetButton = false;
            this.textGetButton = '获取验证码';
            this.showDialog     = true;
            this.showDialogText = res;
          })
      },
      VSDone(){
        this.verifySlider = true;
      }
    }
  }
</script>
