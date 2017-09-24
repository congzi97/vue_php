<template>
  <div class="admin-mysql">
    <group>
      <cell title="MySQL数据库" value="总大小:240KB"></cell>
      <cell-form-preview :list="list"></cell-form-preview>
    </group>
    <div class="pet_circle_nav" style="background: #fff">
      <ul class="pet_circle_nav_list">
        <li><a href="JavaScript:;" class="iconfont pet_nav_sy icon-cloud"></a><span>备份数据库</span></li>
        <li><a href="JavaScript:;" class="iconfont pet_nav_zhangzhishi icon-huifu"></a><span>恢复备份</span></li>
      </ul>
    </div>

  </div>
</template>
<script>
  import { CellFormPreview, Group, Cell } from 'vux'
  export default {
    name : 'adminMySql',
    components: {
      CellFormPreview,
      Group,
      Cell
    },
    data () {
      return {
        list: [{
          label: '数据库名称',
          value: 'blog'
        }, {
          label: '数据库表',
          value: '26张'
        }]
      }
    },
    created () {

    }
  }
</script>
<style>
  .admin-mysql .weui-cells {margin-top: 0;}
  .admin-mysql .vux-no-group-title {margin-top: 0;}
  .admin-mysql .pet_circle_nav_list li {width: 50%;}
</style>
