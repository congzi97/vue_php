<template>
    <div>
      <div v-if="true === show" class="linearLoading-box">
        <div class="linearLoading">
          <div class="progress" :style="[
          {width: progress < 100 ? progress+'%' : '100%'  }
        ]"></div>
        </div>
        <div v-if=" progress <= 100" class="linearLoading-text" v-html="text + progress + '%' "></div>
        <div class="linearLoading-text" v-html="dText" v-else></div>
      </div>
    </div>
</template>
<script>
  export default {
    props:{
      show:{
        type : Boolean,
        default:false,
      },
      text:{
        type:String,
        default:''
      },
      progress : {
        type:Number,
        default:0
      },
      dText:{
        type:String,
        default:'完成'
      },
      done:Function
    },
    watch:{
      progress:function (val) {
        if ( 100 <= val){
          this.done();
        }
      }
    }
  }
</script>
<style>
.linearLoading {width: 80%;margin: 10px 10%;height: 10px;border: 1px solid #303435;position: relative;border-radius: 10px;background: #303435;}
.linearLoading  .progress {position: absolute;left: 0;width: 0;background: #fff;z-index: 5;height: 7px;border-radius: 10px;top: 1px;}
 .linearLoading-text {width: 100%;text-align: center;margin-top: 10px;}
</style>
