<template>
  <div>
    <!--网站管理-->
    <div class="square-tip">
      <div class="square-tip-title">配置说明</div>
      <div class="square-tip-box">
        <div class="square-tip-content">
          1、根目录下有config文件夹<br />
          2、根目录下APP(默认application)\config文件夹<br />
        </div>
      </div>
    </div>
    <divider>权限管理</divider>
    <v-cell text="会员用户组" linkRight></v-cell>
    <v-cell text="会员用户组权限" linkRight></v-cell>
    <divider>配置说明</divider>


  </div>
</template>
<script>
  import {Divider} from 'vux'
  import VCell from '../../../common/cell'
  export default {
    components: {
      Divider,VCell
    },
    name:'admin',
    props:{

    },
    data () {
      return {}
    },
    created () {

    }
  }
</script>
<style>

</style>
