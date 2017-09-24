<template>
  <div>
    <x-header :left-options="{showBack: true}">我的资料</x-header>

    <!--<div class="cell-box" style="padding-top: 0;">-->
      <!--<v-cell text="邮箱" :info="$store.state.user.email"></v-cell>-->
    <!--</div>-->

    <div class="cell-box">
      <v-cell text="我的头像" selectFile :selectFileChange="SAvatar" :img="$store.state.user.avatar" imgRight linkRight></v-cell>
    </div>

    <div class="cell-box user-modify-info">
      <v-cell text="昵称" :info="$store.state.user.nick" linkRight link="/user/modify/nick"></v-cell>
      <v-cell text="性别" :info="$store.state.user.sex" linkRight @click.native="sexShow = true"></v-cell>
      <v-cell text="年龄" :info="String($store.state.user.age)" linkRight link="/user/modify/age"></v-cell>
      <group style="margin-top: 0">
        <datetime title="生日" v-model="limitHourValue" :start-date="startDate" :end-date="endDate" format="YYYY-MM-DD" @on-change="change"></datetime>
      </group>
      <group label-width="5em" label-align="left">
        <x-address title="城市" @on-hide="cityHide" v-model="value2" raw-value :list="addressData" value-text-align="right"></x-address>
      </group>
    </div>

    <div class="cell-box">
      <v-cell text="签名" :info="$store.state.user.autograph" linkRight link="/user/modify/autograph"></v-cell>
    </div>

    <div v-transfer-dom>
      <x-dialog v-model="showDialog" hide-on-blur :dialog-style="{'max-width': '100%', width: '100%', height: '50%', 'background-color': 'transparent'}">
        <p style="color:#fff;text-align:center;" v-if=" true === progress.show">
          <linearLoading :progress="progress.value" :text="progress.text" :show="progress.show" :dText="progress.dText"></linearLoading>
        </p>
        <p style="color:#fff;text-align:center;" @click="showDialog = false" v-else>
          <span style="font-size:30px;" v-html="showDialogText"></span>
          <br>
          <br>
          <x-icon type="ios-close-outline" style="fill:#fff;"></x-icon>
        </p>
      </x-dialog>
    </div>
    <actionsheet v-model="sexShow" :menus="sexMenus" @on-click-menu="sexClick"></actionsheet>

  </div>

</template>
<script>
  import {  XAddress, ChinaAddressV3Data, Group, Datetime,Actionsheet, TransferDomDirective as TransferDom , XDialog,XHeader, Value2nameFilter as value2name  } from 'vux'
  import VCell from '../../common/cell'
  import linearLoading from '../../common/linearLoading'
  import {config,updateUser} from '../../../http/api'
  import {setSessionStore,getSessionStore} from '../../../http/mUtils'
  export default {
    directives: {
      TransferDom
    },
    components: {
      XAddress,Group, Datetime, Actionsheet, XDialog,XHeader,VCell,linearLoading
    },
    data(){
      return{
        mod:'',
        value:'',
        value2: ['广东省', '深圳市', '龙岗区'],
        showDialog    : false,
        showDialogText: '',
        sexShow: false,
        sexMenus: {
          nan : '男',
          nv  : '女',
          bm  : '保密',
        },
        limitHourValue: this.$store.state.user.birthday,
        startDate: '1900-01-01',
        endDate: '',
        addressData: ChinaAddressV3Data,
        progress:{
          value:0,
          text:'正在上传头像:',
          show:false,
          dText:'完成'
        }
      }
    },
    created(){
      this.setTitle('我的资料');
      if ( 0 === this.$store.state.config.app_user_modify_config.length){
        this.getConfig();
      }
      let obj = new Date();
      let m = obj.getMonth() + 1;
      this.endDate = obj.getFullYear()+'-'+m+'-'+obj.getDate();
    },
    methods:{
      cityHide(status){
        if (true === status){
          this.value = value2name(this.value2, ChinaAddressV3Data);
          this.mod   = 'city';
          this.modify();
        }
      },
      change (value) {
        this.value = value;
        this.mod   = 'birthday';
        this.modify();
      },
      sexClick (key) {
        this.value = this.sexMenus[key];
        this.mod   = 'sex';
        this.modify();
      },
      modify(){
        updateUser({
          'mod':[this.mod],
          'value':this.value
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
            // 更新 storage
            let initSessionStoreData  = getSessionStore('initData');
            initSessionStoreData = JSON.parse(initSessionStoreData);
            initSessionStoreData['user'][res['data']['key']] = res['data']['value'];
            setSessionStore('initData',initSessionStoreData);
          }
        }).catch(res => {
          this.showDialog = true;
          this.showDialogText = '网络错误';
        })

      },
      getConfig(){
        this.$store.dispatch('update',{
          'model':'common',
          'key':'slideRoute',
          'value':'/user/setting'
        });
        this.$store.dispatch('update', {
          'model': 'common',
          'key': 'isLoad',
          'value': true
        });
        // 获取数据
        config({
          'mod':'UserUpdateInfo'
        }).then(res => {
          this.$store.dispatch('update', {
            'model' : 'common',
            'key'   : 'isLoad',
            'value' : false
          });
          this.$store.dispatch('update', {
            'model' : 'config',
            'key'   : 'app_user_modify_config',
            'value' : res['data']['app_user_modify_config']
          });
        })
          .catch(res => {
            this.$store.dispatch('update', {
              'model': 'common',
              'key': 'isLoad',
              'value': false
            });
          })
      },
      SAvatar(e){
        console.log(e);
        if ( '' === e.name){
          this.showDialog = true;
          this.showDialogText = '请选择图片';
          return;
        }
        let config = this.$store.state.config.app_user_modify_config;
        let typeArr = config.avatar.type.split(',');
        if (false === typeArr.contains(e.nameArr.type)){
          this.showDialog = true;
          this.showDialogText = '不支持'+e.nameArr.type+'格式';
          return;
        }
        if (config.avatar.size * 1024 < e.size){
          this.showDialog = true;
          this.showDialogText = '不能大于'+config.avatar.size+'KB';
          return;
        }
        // 显示上传进度条
        this.showDialog = true;
        this.progress.show = true;
        // 上传 ...
        updateUser({
          'mod':['avatar'],
          'value':'avatar'
        },{
          'avatar':e.obj
        },(progressEvent => {
          let per = 100 * progressEvent.loaded/progressEvent.total;
          this.progress.value = per;
          if ( 100 === per){
            setTimeout(()=>{
              this.progress.show = false;
              this.showDialog = true;
              //this.showDialogText = '上传完成';
            },500);
          }
        }))
          .then(res => {
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
<style>
  .user-modify-info select{text-align: right;}
  .user-modify-info .vux-no-group-title{margin-top: 0;}
  .user-modify-info .weui-cells:before{border-top: 0;}
  .user-modify-info .weui-cells:after{border-bottom: 0;}

</style>



