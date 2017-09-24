<template>
  <div>

    <transition name="fade">
      <div v-if="true === show">
        <div class="tip-mask"></div>
        <div class="top-tip"  v-if="true === isLoad">
          <load-more :tip="text"></load-more>
        </div>
        <div class="top-tip"  v-else>
          <p>{{text}}</p>
        </div>
      </div>
    </transition>

  </div>
</template>

<script>
  import { LoadMore} from 'vux'
  export default {
    name:'tip',
    components: {
      LoadMore
    },
    data(){
        return {
        };
    },
    props:{
      show:{
        type : Boolean,
        default : false
      },
      text:{
        type : String,
        default : 'hello'
      },
      isLoad:{
        type : Boolean,
        default : false
      }
    },
    created(){

    },
  }
</script>
<style>
  .top-tip {position: fixed;left: 0;top:0;width: 100%;height: 40px;z-index: 51;text-align: center;line-height: 40px;background: #444444;color: #fff;}
  .top-tip .weui-loadmore{width: auto;margin: 0;margin-top: .6rem;}
  .tip-mask {position: fixed;left: 0;top:0;width: 100%;height: 100%;background: #000000;opacity: 0.5;z-index: 50;}
</style>

