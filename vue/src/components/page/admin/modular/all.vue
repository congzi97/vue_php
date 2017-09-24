<template>
  <div class="components-all">
    <div class="pet_circle_nav" style="background: #fff">
      <ul class="pet_circle_nav_list">
        <li @click="$router.push('/admin/modular/user/index')">
          <a href="JavaScript:;" class="iconfont pet_nav_sy icon-huiyuan "></a>
          <span>会员模块</span>
        </li>
        <li @click="$router.push('/admin/modular/forum/index')">
          <a href="JavaScript:;" class="iconfont pet_nav_zhangzhishi icon-luntan "></a>
          <span>论坛模块</span>
        </li>
      </ul>
    </div>
  </div>
</template>
<script>
  export default {
    name:'v-m-list',
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
  .components-all .pet_circle_nav_list li {width: 50%;}
</style>
