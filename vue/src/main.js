/**
 * @Author: congzi
 * @Date:   2017-08-24T19:48:30+08:00
 * @Last modified by:   congzi
 * @Last modified time: 2017-08-25T19:36:22+08:00
 */
import Vue from 'vue'
import Vuex from 'vuex'
import FastClick from 'fastclick'
import VueRouter from 'vue-router'
import App from './App'
import  { ToastPlugin } from 'vux'
Vue.use(ToastPlugin)
Vue.use(VueRouter)
Vue.use(Vuex)
import router from './router/index'
FastClick.attach(document.body)
Vue.config.debug = true
Vue.config.productionTip = false
// 引入函数库
import func from './func'
import state from './store/state'
import mutations from './store/mutations'
import actions from './store/action'
const store = new Vuex.Store({
  'state': state,
  'mutations': mutations,
  'actions': actions
});

router.beforeEach(function (to, from, next) {
  store.dispatch('update', {
    'model': 'common',
    'key': 'to',
    'value': to
  });
  store.dispatch('update', {
    'model': 'common',
    'key': 'from',
    'value': from
  });
  store.dispatch('update', {
    'model': 'common',
    'key': 'isLoad',
    'value': true
  });
  next()
})
router.afterEach(function (to) {
  store.dispatch('update', {
    'model': 'common',
    'key': 'isLoad',
    'value': false
  })
})

new Vue({
  router,
  store,
  render: h => h(App),

}).$mount('#app-box')
