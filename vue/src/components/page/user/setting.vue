<template>
  <div>
    <x-header :left-options="{showBack: true}">设置</x-header>

    <div class="cell-box">
      <v-cell text="我的资料" :img="$store.state.user.avatar" link="/user/info"></v-cell>
    </div>

    <div class="cell-box">
      <v-cell text="更改密码" icon=" icon-yanzhengma3 " link="/user/modify/pass"></v-cell>
    </div>



  </div>
</template>
<script>
  import { XHeader } from 'vux'
  import VCell from '../../common/cell.vue'
  export default {
    components: {
      XHeader,VCell
    },
    data(){
      return {

      };
    },
    created(){
      this.setTitle('设置');
      this.$store.dispatch('update',{
        'model':'common',
        'key':'slideRoute',
        'value':'/tab4'
      });
    }
  }
</script>
<style>

</style>
