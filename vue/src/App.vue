<template>
  <div :id="true === $store.state.common.isSlide ? 'slide' : '' ">
    <loading v-model="this.$store.state.common.isLoad"></loading>
    <transition :name="transitionName">
      <router-view class="Router"></router-view>
    </transition>
  </div>
</template>

<script>
  import { Loading, Alert } from 'vux'
  import {init} from './http/api'
  import {setSessionStore,getSessionStore,setStore} from './http/mUtils'
  export default {
    data (){
      return {
        notSlider:['vux-img','verify-slider-box','demo-content vux-1px-t'],
        isClick:false,
        transitionName: 'slide-right'  // 默认动态路由变化为slide-right
      }
    },
    created(){
//      init({
//        'test':'test',
//        'encode':['adf']
//      }).then(res => {
//        console.log(res);
//      });
      console.log('执行了APP.VUE');
      this.transitionName = this.$store.state.common.transitionName;
      if ( false === this.$store.state.user.getDone){
        this.initData();
      }

    },
    mounted () {
        this.slideBack();
    },
    methods:{
      initData(){
        init({
          'test':'test',
          'encode':['adf']
        }).then(res => {
          console.log(res);
          if ( 200 === res['code']){
            res['data']['power']['modular'] = JSON.parse(res['data']['power']['modular']);
            this.$store.dispatch('update',{
              'model':'',
              'key':'user',
              'value':res['data']['user']
            });
            this.$store.dispatch('update',{
              'model':'',
              'key':'power',
              'value':res['data']['power']
            });
            // 保存登录信息
            setStore('sid',res['data']['user']['sid']);
          }else{
            this.$vux.toast.text(res['msg']);
          }
        });
      },
      slideBack(){
        let obj = document.getElementById('slide');
        let startX  = 0;
        let endX    = 0;
        obj.addEventListener('touchstart',(e) => {
          startX  = 0;
          endX    = 0;
          let target = e.target;
          while (target.nodeType !== 1) target = target.parentNode;
          if (target.tagName !== 'SELECT' && target.tagName !== 'INPUT' && target.tagName !== 'TEXTAREA'){
            e.preventDefault();
          }
          let tar = e.touches[0];
          startX = tar['pageX'];
        });
        obj.addEventListener('touchmove',(e) => {
          let tar = e.touches[0];
          endX = tar['pageX'];
        });
        obj.addEventListener('touchend',(e) => {
          if (false === this.$store.state.common.isSlide){
            return;
          }
          // weui-media-box
          if ( 0 <= e.target['className'].indexOf('weui-media-box')){
            startX  = 0;
            endX    = 0;
            return;
          }
          // console.log('=>'+'app-main Router'+'/'+e.target['className']+'/'+'app-main Router' !== e.target['className']);
          console.log('本次滑动的元素:class='+ e.target['className']+';ID='+e.target['id']);
          if (true === this.notSlider.contains(e.target['className']) ||
            true === this.notSlider.contains(e.target['id']) ){
            startX  = 0;
            endX    = 0;
            return;
          }
          let cha = endX - startX;
          if (0 === cha || 0 === endX || 0 === startX){
            return;
          }
          if (cha > 50 || cha < -50){
              if (['tab1','tab2','tab3','tab4'].contains(this.$store.state.common.to['name'])){
                this.tabbarGo(endX,startX);
                startX  = 0;
                endX    = 0;
              }else{
                  if (cha > 50){
                      this.$router.push(this.$store.state.common.slideRoute);
                      this.$store.dispatch('update', {
                        'model': 'common',
                        'key': 'transitionName',
                        'value': 'slide-left'
                      });
                      startX  = 0;
                      endX    = 0;
                  }
              }
          }
        });
      },
      tabbarGo(endX,startX){
        let cha = endX - startX;
        // 向右滑动，
        if (  50 < cha){
          switch (this.$route.name) {
            case 'tab1':
              this.$router.push('/tab2');
              break;
            case 'tab2':
                this.$router.push('/tab3');
              break;
            case 'tab3':
                  this.$router.push('/tab4');
              break;
            case 'tab4':
                  this.$router.push('/');
              break;
          }
          this.$store.dispatch('update', {
            'model': 'common',
            'key': 'transitionName',
            'value': 'slide-right'
          });
        }else if (cha < -50){
          // 向左滑动
          switch (this.$route.name) {
            case 'tab1':
                this.$router.push('/tab4');
              break;
            case 'tab2':
                this.$router.push('/');
              break;
            case 'tab3':
                  this.$router.push('/tab2');
              break;
            case 'tab4':
                  this.$router.push('/tab3');
              break;
          }
          this.$store.dispatch('update', {
            'model': 'common',
            'key': 'transitionName',
            'value': 'slide-left'
          });
        }
      },
    },
    components: {
      Loading,
      Alert
    },
    watch: {
      '$route' (to, from) {
        const toDepth = to.path.substring(0, to.path.length-2).split('/').length
        const fromDepth = from.path.substring(0, from.path.length-2).split('/').length
        this.transitionName = toDepth < fromDepth ? 'slide-right' : 'slide-left'
      }
    }
  }
</script>
<style lang="less">
@import '~vux/src/styles/reset.less';
body {
  background-color: #fbf9fe;
}
</style>
<style>

.Router {
  position: absolute;
  width: 100%;
  transition: all 0.5s ease;
  top: 0;height: 100%;
}

.slide-left-enter,
.slide-right-leave-active {
  opacity: 0;
  -webkit-transform: translate(100%, 0);
  transform: translate(100%, 0);
}

.slide-left-leave-active,
.slide-right-enter {
  opacity: 0;
  -webkit-transform: translate(-100%, 0);
  transform: translate(-100% 0);
}

.fade-enter-active, .fade-leave-active {
  transition: opacity .5s
}
.fade-enter, .fade-leave-to /* .fade-leave-active in below version 2.1.8 */ {
  opacity: 0
}

/* 可以设置不同的进入和离开动画 */
.slide-fade-enter-active {
  transition: all .3s ease;
}
.slide-fade-leave-active {
  transition: all .8s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}
.slide-fade-enter, .slide-fade-leave-to
  /* .slide-fade-leave-active for below version 2.1.8 */ {
  transform: translateX(10px);
  opacity: 0;
}


/* verify slider item*/
.vs-item{height: 40px;width: 100%;position: relative;overflow: hidden;border-bottom: 1px solid #fff;margin-bottom: 25px;}
.vs-item #verify-slider{height: 100%;width: 50px;position: absolute;left: 0;top: 0;z-index: 10;background: #fff;text-align: center;}
.vs-item #verify-slider i {font-size: 1.5rem;color: #333;}
.vs-item #verify-slider-width {position: absolute;left: 0;top: 0;height: 100%;width: 0;background: #19ce8b;}
.vs-item #verify-slider-box {position: absolute;left: 0;top: 0;width: 100%;height: 100%;}
.vs-item .verify-slider-tip {width: 100%;height: 40px;line-height: 40px;text-align: center;color:#44CC00;}


  .icon-nan-color {color: #4A90E2;}
.icon-nv-color {color: #EA5A49;}
</style>
