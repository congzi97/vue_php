<template>
  <div>
    <transition :name="transitionName">
      <router-view class="Router"></router-view>
    </transition>
  </div>
</template>
<script>
  export default {
    data(){
      return{
        transitionName : 'slide-right'
      }
    },
    created(){
    },
    watch: {
      '$route' (to, from) {
        if ( '/admin/index' === to.path || '/admin/maxPower' === to.path ){
          this.transitionName = 'slide-right';
          return;
        }
        const toDepth = to.path.substring(0, to.path.length-2).split('/').length
        const fromDepth = from.path.substring(0, from.path.length-2).split('/').length
        this.transitionName = toDepth < fromDepth ? 'slide-right' : 'slide-left'
      }
    }
  }
</script>

