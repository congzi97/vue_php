<template>
  <div class="vs-item" style="background: #ececec;border-bottom: 0;">
    <div id="verify-slider-width">
      <div class="verify-slider-tip" style="color: #fff;display: none;">验证通过</div>
    </div>
    <div id="verify-slider">
      <i id="verify-slider-box" class="iconfont">&#xe6e3;</i>
    </div>
    <div class="verify-slider-tip" style="display: block;">向右滑动</div>
  </div>
</template>
<script>
  export  default  {
    name:'verifySlider',
    props:{
      done:Function,
    },
    data () {
      return {
        sliderData:{
          is:true,
          width : 0,
        }
      }
    },
    mounted(){
      this.initSlider();
    },
    methods:{
      verifySlider(){
        let obj = document.getElementById('verify-slider-box');
        let startX  = 0;
        let endX    = 0;
        obj.addEventListener('touchstart',(e) => {
          let tar = e.touches[0];
          startX = tar['pageX'];
        });
        obj.addEventListener('touchmove',(e) => {
          if (false === this.sliderData.is){
            return;
          }
          let tar = e.touches[0];
          endX = tar['pageX'];
          let width = endX - startX;
          if (width >= this.sliderData.width){
            this.verifySliderOk();
            return;
          }
          document.getElementById('verify-slider').style.left   = width+'px';
          document.getElementById('verify-slider-width').style.width  = width+'px';
        });
        obj.addEventListener('touchend',(e) => {
          if (false === this.sliderData.is){
            return;
          }
          let width = endX - startX;
          startX  = 0;
          endX    = 0;
          if (width < this.sliderData.width){
            document.getElementById('verify-slider').style.left   = '0px';
            document.getElementById('verify-slider-width').style.width  = '0px';
          }else{
            this.verifySliderOk();
            return;
          }
        });
      },
      verifySliderOk(){
        // 禁止滑动
        this.sliderData.is = false;
        let oDiv = document.getElementById('verify-slider-box');
        oDiv.style.color  = '#44CC00';
        oDiv.innerHTML    = '&#xe63f;';
        document.getElementById('verify-slider').style.left   = this.sliderData.width+'px';
        document.getElementById('verify-slider-width').style.width  = this.sliderData.width+'px';
        let obj = this.getClassName('verify-slider-tip');
        obj[0].style.display = 'block';
        obj[1].style.display = 'none';
        // 回调函数
        this.done();
      },
      initSlider(){
        let obj = this.getClassName('vs-item');
        this.sliderData.width  = obj[0].offsetWidth - 50;
        this.verifySlider();
      }
    }
  }
</script>
<style>
  .vs-item{height: 40px;width: 100%;position: relative;overflow: hidden;border-bottom: 1px solid #fff;margin-bottom: 25px;}
  .vs-item #verify-slider{height: 100%;width: 50px;position: absolute;left: 0;top: 0;z-index: 10;background: #fff;text-align: center;}
  .vs-item #verify-slider i {font-size: 1.5rem;color: #333;}
  .vs-item #verify-slider-width {position: absolute;left: 0;top: 0;height: 100%;width: 0;background: #19ce8b;}
  .vs-item #verify-slider-box {position: absolute;left: 0;top: 0;width: 100%;height: 100%;}
  .vs-item .verify-slider-tip {width: 100%;height: 40px;line-height: 40px;text-align: center;color:#44CC00;}
</style>

