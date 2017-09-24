<template>
  <transition name="flip-list">
    <div class="cell-item" @click=" '' === link ? '' : $router.push(link) ">
      <input
        @change="fileChange"
        v-if=" true === selectFile"
        type="file"
        style="width: 100%;height: 80%;opacity: 0;position: absolute;z-index: 5;"
      >
      <div class="cell-left" v-if=" '' !== icon">
        <i class="icon iconfont " :class="icon"></i>
      </div>
      <div class="cell-left" v-else-if="false === imgRight && '' !== img">
        <img :src="img" alt="图片">
      </div>
      <div class="cell-text" :style="[
        {'textAlign' : true === textRight ? 'right' : ''} ,
        {'width' : '' === icon || '' === img || true === imgRight ? '87%' : '77%'}
      ]">
        {{text}}
        <span style="float: right;overflow: hidden;height: 30px;width:70%;text-align: right;" v-if=" '' !== info">{{info}}</span>
        <img :src="img" alt="图片" style="float: right;" v-if="true === imgRight">
      </div>
      <div class="cell-right" v-if="true === linkRight">
        <i class="icon iconfont icon-right"></i>
      </div>

    </div>
  </transition>
</template>

<script>
  export default {
    name:'v-cell',
    data(){
      return {
      };
    },
    props:{
      selectFileChange:{
        type:Function
      },
      selectFile:{
        type:Boolean,
        default : false
      },
      img:{
        type : String,
        default : ''
      },
      imgRight:{
        type : Boolean,
        default : false
      },
      icon:{
        type : String,
        default : ''
      },
      text:{
        type : String,
        default : ''
      },
      info:{
        type : String,
        default : ''
      },
      linkRight : {
        type : Boolean,
        default : false,
      },
      link : {
        type:String,
        default:''
      },
      textRight:{
        type : Boolean,
        default:false
      }
    },
    created(){

    },
    methods : {
      fileChange(e){
        /**
         * 返回文件信息，
         * @type {FileList}
         */
        let files = e.target.files || e.dataTransfer.files;
        files = files[0];
        let arr  = files['name'].split('.');
        let type = arr[arr.length - 1];
        arr.splice(arr.length - 1,1);
        this.selectFileChange({
          'name'      : files['name'],
          'nameArr'   : {
            'type':type,
            'name':arr.toString()
          },
          'size'      : files['size'],
          'type'      : files['type'],
          'obj'       : files,
        });
      }
    }
  }
</script>
<style>
  .cell-item {position: relative;overflow: hidden;height: 40px;width: 96%;background: #fff;padding: 5px 2%;border-bottom: 1px solid #efefef;}
  .cell-item:after{border-bottom: 0;}
  .cell-left {float: left;width: 10%;text-align: center;height: 100%;}
  .cell-item img {width: 30px;height: 30px;border-radius: 100%;margin-right: 5px;margin-top: 5px;}
  .cell-left i {font-size: 25px;color: #00c2ea;}
  .cell-text {float: left;width: 77%;text-align: left;height: 100%;line-height: 40px;margin-left: 3%;}
  .cell-right {float: right;width: 10%;text-align: center;height: 100%;line-height: 40px;}
  .flip-list-move {
    transition: transform 1s;
  }
</style>

