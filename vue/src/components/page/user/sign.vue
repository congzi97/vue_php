<template>
  <div class="app-main">
    <!--头部-->
    <!-- <x-header :left-options="{showBack: true}">登录</x-header> -->
    <div class="sign" style="height:100%;width:100%;background:url('/static/img/dt-bg.gif') no-repeat;background-size: 100% 100%;">
      <div class="avatar">
        <img src="/static/img/user.jpg" alt="">
      </div>

      <div class="from box-absolute">
        <div class="f-item">
          <div class="icon">
            <i class="iconfont icon-youxiang"></i>
          </div>
          <div class="input">
            <input type="text" v-model="email" placeholder='请输入邮箱号'>
          </div>
        </div>
        <div class="f-item">
          <div class="icon" style="padding-top: 2px;">
            <i class="iconfont icon-xiugaimima" style="font-size: 25px;"></i>
          </div>
          <div class="input">
            <input id="password" type="password" v-model="password" placeholder='请输入密码'>
          </div>
          <div class="f-right" @click="lookPass('password')">
            <i class="iconfont " :class="passwordIcon"></i>
          </div>
        </div>

        <div class="f-item">
          <div class="icon" style="padding-top: 2px;">
            <i class="iconfont icon-xiugaimima" style="font-size: 25px;"></i>
          </div>
          <div class="input">
            <input id="password2" type="password" v-model="password2" placeholder='确定密码'>
          </div>
          <div class="f-right" @click="lookPass('password2')">
            <i class="iconfont " :class="password2Icon"></i>
          </div>
        </div>

        <div class="f-item">
          <div class="icon" style="padding-top: 2px;">
            <i class="iconfont icon-yanzhengma3" style="font-size: 25px;"></i>
          </div>
          <div class="input" style="width: 55%;">
            <input id="emailCode" v-model="emailCode" placeholder='请输入验证码'>
          </div>
          <div class="f-right" style="width: 35%;" @click="getCode">
            <x-button mini type="primary" :disabled="code.disabled">{{code.text}}</x-button>
          </div>
        </div>

        <verifySlider :done="VSDone" ref="verifySlider"></verifySlider>

        <div style="margin: 20px 0;" @click="sign">
          <x-button plain type="warn" style="border-radius:99px;">注册</x-button>
        </div>

      </div>

    </div>
    <tip :show="isTip" :text="tipText" :isLoad="isLoad"></tip>
  </div>
</template>

<script>
  import { XButton, XHeader } from 'vux'
  import verifySlider from '../../common/verifySlider.vue'
  import tip from '../../common/tip.vue'
  import {sign} from '../../../http/api'
  export default {
    components: {
      tip,XHeader,XButton,verifySlider
    },
    data () {
      return {
          email     : '',
          password  : '',
          password2 : '',
          passwordIcon  : ' icon-xianshimima ',
          password2Icon : ' icon-xianshimima ',
          verify    : false,
          isTip     : false,
          isLoad    : false,
          tipText   : '',
          emailCode:'',
          code:{
              'text': '获取验证码',
              'disabled':false,
              'time'  : 300,
          }
      }
    },
    created(){
      this.setTitle('注册');
      this.$store.dispatch('update',{
        'model':'common',
        'key':'slideRoute',
        'value':'/tab4'
      });
    },
    mounted(){

    },
    methods:{
      lookPass(id){
        let obj = document.getElementById(id);
        if ( 'text' === obj.type){
          this[id+'Icon'] = ' icon-xianshimima ';
          obj.setAttribute("type","password");
        }else{
          this[id+'Icon'] = ' icon-yincangmima ';
          obj.setAttribute("type","text");
        }
      },
      getCode(){
        this.isTip    = true;
        this.isLoad   = true;
        this.tipText  = '请稍候...';
        if (false === this.verify){
          this.isTip = true;
          this.isLoad = false;
          this.tipText = '请拖动验证码';
          setTimeout(()=> {
            this.isTip = false
          },1500);
          return false;
        }
        if ('' === this.email){
          this.isTip = true;
          this.isLoad = false;
          this.tipText = '请输入邮箱';
          setTimeout(()=> {
            this.isTip = false
          },1500);
          return false;
        }
        if (false === this.isEmail(this.email)){
          this.isTip = true;
          this.isLoad = false;
          this.tipText = '邮箱号不正确';
          setTimeout(()=> {
            this.isTip = false
          },1500);
          return false;
        }

        sign({
          'email'     : [this.email],
          'password'  : [this.password],
          'password2' : [this.password2],
          'mod'       : ['getCode']
        }).then(res => {
          setTimeout(()=> {
            this.isTip = false
          },1500);
          this.isTip = true;
          this.isLoad = false;
          this.tipText = res['msg'];
          if ( 200 === res['code']){
            this.code.disabled  = true;
            setInterval(()=>{
              this.code.time--;
              this.code.text      = '剩余'+this.code.time+'秒';
            },1000);
          }
        })
          .catch(res => {
            setTimeout(()=> {
              this.isTip = false
            },1500);
            this.isTip = true;
            this.isLoad = false;
            this.tipText = '请检查网络';
          })
      },
      sign(){
        this.isTip = true;
        this.isLoad = true;
        this.tipText = '注册中...';
        if (false === this.check()){
          setTimeout(()=> {
            this.isTip = false
          },1500);
          return false;
        }
        sign({
          'email'     : [this.email],
          'password'  : [this.password],
          'password2' : [this.password2],
          'mod'       : ['sign'],
          'emailCode' : [this.emailCode]
        }).then(res => {
          this.isTip = true;
          this.isLoad = false;
          this.tipText = res['msg'];
          setTimeout(()=> {
            this.isTip = false
          },1500);
          if ( 200 === res['code']){
            setTimeout(()=> {
              this.$router.push('/tab4');
            },1500);
          }
        })
          .catch(res => {
            setTimeout(()=> {
              this.isTip = false
            },1500);
            this.isTip = true;
            this.isLoad = false;
            this.tipText = '连接服务失败';
          })

      },
      check(){
        if (false === this.verify){
          this.isTip = true;
          this.isLoad = false;
          this.tipText = '请拖动验证码';
          return false;
        }
        if ('' === this.email){
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
        if ('' === this.password2){
          this.isTip = true;
          this.isLoad = false;
          this.tipText = '请输入二次密码';
          return false;
        }
        if ('' === this.emailCode){
          this.isTip = true;
          this.isLoad = false;
          this.tipText = '请输入验证码';
          return false;
        }
        if (false === this.isEmail(this.email)){
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
      VSDone(){
        this.verify = true;
      }
    },
    watch: {
      isTip (val) {
//        if (val) {
//          setTimeout(() => {
//            this.isTip = false
//          }, 1000)
//        }
      }
    }
  }
</script>
<style>


</style>
