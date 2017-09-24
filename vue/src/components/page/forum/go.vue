<template>
    <div>
      <x-header :left-options="{showBack: true}">XX论坛</x-header>

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
      this.setTitle('XX论坛');
      this.$store.dispatch('update', {
        'model': 'common',
        'key': 'slideRoute',
        'value': '/'
      });
    }
  }
</script>
