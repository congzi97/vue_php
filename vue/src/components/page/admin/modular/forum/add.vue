<template>
    <div>
      <x-header :left-options="{showBack: true}">添加论坛</x-header>
      <msg v-if=" true !== this.$store.state.power.getDone ||  true !== this.$store.state.power.modular.user ||  1 !== this.$store.state.power.loginAdmin" :title="power.title" :description="power.description" :buttons="power.buttons" :icon="power.icon"></msg>
      <div class="page-content" style="margin-top: 20px;" v-else>
        <divider>请填写论坛信息</divider>
        <group>
          <selector v-model="classificationVal" title="分类" :options="classification" placeholder="请选择"></selector>
          <x-input placeholder="论坛名称" ></x-input>
          <selector v-model="add.logoType" title="logo类型" :options="['iconfont', '图片Src']" placeholder="请选择"></selector>

          <x-input v-if="'iconfont' === add.logoType" placeholder="icon名称"></x-input>

          <div v-else>
            <v-cell style="border-bottom: 0px;border-top: 1px solid #efefef;" text="上传logo" selectFile :selectFileChange="SelectImg"  :img="add.src" imgRight linkRight></v-cell>
            <x-input placeholder="图片地址(Src)"></x-input>
          </div>

          <x-textarea placeholder="论坛信息"></x-textarea>
        </group>

        <div style="margin: 20px 0;border-top: 1px solid #efefef;" @click="addForum">
          <x-button type="primary" action-type="button" :show-loading="button.disabled" :disabled="button.disabled">{{button.text}}</x-button>
        </div>
      </div>

      <div v-transfer-dom>
        <x-dialog v-model="showDialog" hide-on-blur :dialog-style="{'max-width': '100%', width: '100%', height: '50%', 'background-color': 'transparent'}">
          <p style="color:#fff;text-align:center;" v-if=" true === progress.show">
            <linearLoading :progress="progress.value" :text="progress.text" :show="progress.show" :dText="progress.dText"></linearLoading>
          </p>
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
  import { Msg,XTextarea , Selector, TransferDomDirective as TransferDom , XDialog,XButton, XInput, Group, XHeader,Divider } from 'vux'
  import {admin} from '../../../../../http/api'
  import VCell from '../../../../common/cell'
  import linearLoading from '../../../../common/linearLoading'
  export default {
    directives: {
      TransferDom
    },
    components: {
      linearLoading,Msg,XTextarea ,Selector, XDialog,XButton, XInput, Group, XHeader,Divider,VCell
    },
    data () {
      return {
        button:{
          disabled:false,
          text:'确定',
        },
        classification:[],
        classificationVal:'',
        showDialog    : false,
        showDialogText: '',
        progress:{
          value:0,
          text:'正在上传头像:',
          show:false,
          dText:'完成'
        },
        add:{
          logoType : 'iconfont',
          src:'/static/img/add.png',
          // 允许的格式
          imgType:['png','jpg','jpeg','gif'],
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
      this.setTitle('手动添加会员');
      this.$store.dispatch('update', {
        'model': 'common',
        'key': 'slideRoute',
        'value': '/admin/modular/forum/index'
      });
      let length = this.$store.state.forum.classification.length;
      if ( 0 < length){
        for (let i = 0; i < length;i++){
          if ( 0 === i ){
            this.classificationVal = this.$store.state.forum.classification[i]['name'];
          }
          this.classification.push(this.$store.state.forum.classification[i]['name']);
        }
      }
    },
    methods:{
      SelectImg(e){
        if ( '' === e.name){
          this.showDialog = true;
          this.showDialogText = '请选择LOGO';
          return;
        }
        if (false === this.add.imgType.contains(e.nameArr.type)){
          this.showDialog = true;
          this.showDialogText = '不支持'+e.nameArr.type+'格式';
          return;
        }
        // 直接上传，返回Src

      },
      addForum(){
        this.button.disabled  = true;
        this.button.text      = '请稍候...';
        admin({
          'mod':'addForum',
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
