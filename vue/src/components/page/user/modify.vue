<template>
    <div>
        <x-header :left-options="{showBack: true}">
          更改{{title}}
          <i class="icon iconfont icon-wancheng" slot="right" style="font-size: 30px;color: #19ce8b;" @click="modify"></i>
        </x-header>
        <div v-if=" 'nick' === this.mod">
          <group>
            <x-input v-model="value" placeholder="请输入新的昵称"></x-input>
          </group>
        </div>
        <div v-if=" 'age' === this.mod">
          <group>
            <x-input type="number" v-model="value" placeholder="请输入年龄"></x-input>
          </group>
        </div>
        <div v-if=" 'autograph' === this.mod">
          <group >
            <x-textarea v-model="value" :max="trimArr[1]" autosize show-counter placeholder="请输入签名"></x-textarea>
          </group>
        </div>
        <div v-if=" 'pass' === this.mod">
          <group>
            <x-input type="password" v-model="value" placeholder="原密码"></x-input>
            <x-input type="password" v-model="value2" placeholder="新的密码"></x-input>
            <x-input type="password" v-model="value3" placeholder="确定新的密码"></x-input>
          </group>
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
  import { XTextarea,TransferDomDirective as TransferDom , XDialog,XInput, Group, XHeader } from 'vux'
  import {updateUser} from '../../../http/api'
  import {setSessionStore,getSessionStore} from '../../../http/mUtils'
  export default {
    directives: {
      TransferDom
    },
    components: {
      XTextarea,XDialog,XInput, Group, XHeader
    },
    data () {
      return {
        title:'',
        mod:'',
        value:'',
        value2:'',
        value3:'',
        showDialog    : false,
        showDialogText: '',
        trimArr:[]
      }
    },
    created () {
      this.mod = this.$route.params.name;
      switch (this.$route.params.name){
        case 'nick':
          this.setTitle('更改昵称');
          this.title  =  '昵称';
          break;
        case 'age':
          this.setTitle('更改年龄');
          this.title  =  '年龄';
          break;
        case 'autograph':
          this.setTitle('更改签名');
          this.title  =  '签名';
          let qmtrim = this.trimrl(this.$store.state.config.app_user_modify_config.qm);
          this.trimArr = qmtrim.split('.');
          break;
        case 'pass':
          this.setTitle('更改密码');
          this.title  =  '密码';
          break;
      }
      this.$store.dispatch('update', {
        'model' : 'common',
        'key'   : 'slideRoute',
        'value' : '/user/info'
      });
    },
    methods:{
      modify(){
        if ( '' === this.value ){
            this.showDialog = true;
            this.showDialogText = '请输入'+this.title;
            return ;
        }
        if ( 'pass' === this.mod){
          if ('' === this.value2){
            this.showDialog = true;
            this.showDialogText = '请输入新的密码';
            return ;
          }
          if ('' === this.value3){
            this.showDialog = true;
            this.showDialogText = '请确定新的密码';
            return ;
          }
        }

        updateUser({
          'mod':[this.mod],
          'value':this.value,
          'value2':this.value2,
          'value3':this.value3,
        }).then(res => {
          this.showDialog = true;
          this.showDialogText = res['msg'];
          if ( 200 === res['code']){
            // 实时更新状态
            this.$store.dispatch('update', {
              'model' : 'user',
              'key'   : res['data']['key'],
              'value' : res['data']['value']
            });
          }
        }).catch(res => {
          this.showDialog = true;
          this.showDialogText = '网络错误';
        })

      }
    }
  }
</script>
