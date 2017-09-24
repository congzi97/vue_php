<template>
  <div class="app-main">
    <!--头部-->
    <!-- <x-header :left-options="{showBack: true}">登录</x-header> -->

    <div class="login" style="height:100%;width:100%;background:url('/static/img/dt-bg.gif') no-repeat;background-size: 100% 100%;">
      <div class="avatar">
        <img src="/static/img/user.jpg" alt="">
      </div>

      <div class="from box-absolute">
        <div class="f-item">
          <div class="icon">
            <i class="iconfont icon-wode"></i>
          </div>
          <div class="input">
            <input type="text" v-model="userName" placeholder='请输入账户'>
          </div>
        </div>
        <div class="f-item">
          <div class="icon" style="padding-top: 2px;">
            <i class="iconfont icon-xiugaimima" style="font-size: 25px;"></i>
          </div>
          <div class="input">
            <input id="password" type="password" v-model="password" placeholder='请输入密码'>
          </div>
          <div class="f-right" @click="lookPassword('password')">
            <i class="iconfont " :class="passwordIcon"></i>
          </div>
        </div>
        <div class="f-item" v-if=" '' !== verifySrc">
          <div class="input" style="width: 60%;">
            <input id="verify" type="text" v-model="code" placeholder='请输入验证码'>
          </div>
          <div class="f-right" style="width: 40%;" >
            <img :src="verifySrc" alt="">
          </div>
        </div>

        <verifySlider :done="VSDone" ref="verifySlider"></verifySlider>

        <div style="font-size: .6rem;margin: 0px 0;height: 30px;line-height: 30px;">
          <a href="JavaScript:;" @click="$router.push('/user/forgetPass')" style="color: #ccc;float: right;">忘记密码?</a>
        </div>
       <div style="margin: 20px 0;" @click="loginOk">
         <x-button plain type="warn" style="border-radius:99px;">登录</x-button>
       </div>

      </div>
      <tip :show="isTip" :text="tipText" :isLoad="isLoad"></tip>
    </div>
  </div>
</template>

<script>
  import { Divider, XButton, XHeader } from 'vux'
  import verifySlider from '../../common/verifySlider.vue'
  import tip from '../../common/tip.vue'
  import {login,getVerify,init} from '../../../http/api'
  import {setSessionStore,getSessionStore,setStore} from '../../../http/mUtils'
  export default {
    components: {
      tip,Divider,XHeader,XButton,verifySlider
    },
    data () {
      return {
        userName:'',
        password:'',
        passwordIcon:'icon-xianshimima',
        verify    : false,
        isTip     : false,
        isLoad    : false,
        tipText   : '',
        code      : '',
        verifySrc : '',
      }
    },
    created(){
        this.setTitle('登录');
        this.$store.dispatch('update',{
          'model':'common',
          'key':'slideRoute',
          'value':'/tab4'
        });
    },
    methods:{
      MGetVerify(){
        getVerify({}).then(res => {
          this.verifySrc = res['data']['src']
        });
      },
      loginOk(){
        this.isTip = true;
        this.isLoad = true;
        this.tipText = '正在登录...';
        if (false === this.check()){
          return ;
        }
        login({
          'email' : [this.userName],
          'password' : [this.password],
          'verify' : [this.code]
        }).then(res => {
          this.isLoad = false;
          this.tipText = res['msg'];
          if ( 200 === res['code']){
            this.getInitData();
          }else{
            if ('undefined' !== res['data']['verify']){
              if ( true === res['data']['verify']){
                this.MGetVerify();
              }
            }
          }
        })
          .catch(res => {
            this.isLoad = false;
            this.tipText = '无法连接服务器';
          })
      },
      getInitData(){
        setTimeout(() => {
          this.$router.push('/tab4');
        },2000);
        init({
          'test':'test',
          'encode':['adf']
        }).then(res => {
          if ( 200 === res['code']){
            this.$store.dispatch('update',{
              'model':'',
              'key':'user',
              'value':res['data']['user']
            });
            setSessionStore('initData',res['data']);
            setSessionStore('sid',res['data']['user']['sid']);
            setStore('sid',res['data']['user']['sid']);
          }else{
            this.$vux.toast.text(res['msg']);
          }
        });
      },
      check(){
        if (false === this.verify){
          this.isTip = true;
          this.isLoad = false;
          this.tipText = '请拖动验证码';
          return false;
        }
        if ('' === this.userName){
          this.isTip = true;
          this.isLoad = false;
          this.tipText = '请输入邮箱';
          return false;
        }
        if ('' === this.password){
          this.isTip = true;
          this.isLoad = false;
          this.tipText = '请输入密码';
          return false;
        }
        if (false === this.isEmail(this.userName)){
          this.isTip = true;
          this.isLoad = false;
          this.tipText = '邮箱号不正确';
          return false;
        }
        if (false === this.isPassword(this.password)){
          this.isTip = true;
          this.isLoad = false;
          this.tipText = '密码最少为8位字母和数字(不能包含特殊字符)';
          return false;
        }
        return true;
      },
      lookPassword(id){
        let obj = document.getElementById(id);
        if ( 'text' === obj.type){
          this[id+'Icon'] = ' icon-xianshimima ';
          obj.setAttribute("type","password");
        }else{
          this[id+'Icon'] = ' icon-yincangmima ';
          obj.setAttribute("type","text");
        }
      },
      VSDone(){
        this.verify = true;
      }
    },
    watch: {
      isTip (val) {
        if (val) {
          setTimeout(() => {
            this.isTip = false
          }, 2000)
        }
      }
    }
  }
</script>
<style>

</style>
