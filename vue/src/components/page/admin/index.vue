<template>
    <div>
        <x-header :left-options="{showBack: true}">头部内容</x-header>

    </div>
</template>
<script>
  import { XHeader } from 'vux'

  export default {
    components: {
      XHeader
    },
    data () {
      return {}
    },
    created () {
      this.setTitle('标题');
      this.$store.dispatch('update', {
        'model': 'common',
        'key': 'slideRoute',
        'value': '/'
      });
    }
  }
</script>
