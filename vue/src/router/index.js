/**
 * @Author: congzi
 * @Date:   2017-08-24T19:15:28+08:00
 * @Last modified by:   congzi
 * @Last modified time: 2017-08-25T02:09:08+08:00
 */

import Vue from 'vue'
import Router from 'vue-router'
import tab1 from '@/components/page/tabbar/tab1'
import tab2 from '@/components/page/tabbar/tab2'
import tab3 from '@/components/page/tabbar/tab3'
import tab4 from '@/components/page/tabbar/tab4'

Router.prototype.goBack = function () {
  this.isBack = true
  window.history.go(-1)
}
Vue.use(Router)
export default new Router({
  mode: 'history',
  routes: [
    {path: '',name: 'tab1',component: tab1},
    {path: '/tab2',name: 'tab2',component: tab2},
    {path: '/tab3',name: 'tab3',component: tab3},
    {path: '/tab4', name: 'tab4',component: tab4,},
    {path: '/login', name: 'login', component: function (resolve) {require(['@/components/page/user/login'], resolve)}},
    {path: '/sign', name: 'login', component: function (resolve) {require(['@/components/page/user/sign'], resolve)}},
    {
      path:'/user/:name',name:'user',component:function (resolve) {require(['@/components/page/user/app'], resolve)},
      children:[
        {path:'/user/setting',name:'user_setting',component:function (resolve) {require(['@/components/page/user/setting'], resolve)}},
        {path:'/user/info',name:'user_info',component:function (resolve) {require(['@/components/page/user/info'], resolve)}},
        {path:'/user/forgetPass',name:'user_forgetPass',component:function (resolve) {require(['@/components/page/user/forgetPass'], resolve)}},
        {path:'/user/modify/:name',name:'user_modify',component:function (resolve) {require(['@/components/page/user/modify'], resolve)}}
      ]
    },
    {
      path:'/forum/:name',name:'forum',component:function (resolve) {require(['@/components/page/forum/app'], resolve)},
      children:[
        {path:'/forum/index',name:'forum_index',component:function (resolve) {require(['@/components/page/forum/index'], resolve)}},
        {path:'/forum/index/:id',name:'forum_index',component:function (resolve) {require(['@/components/page/forum/go'], resolve)}},
      ]
    },
    {
      // 后台路由
      path:'/admin/:name',name:'admin',component:function (resolve) {require(['@/components/page/admin/app'], resolve)},
      children:[
        {path:'/admin/index',name:'admin_index',component:function (resolve) {require(['@/components/page/admin/index'], resolve)}},
        {path:'/admin/maxPower',name:'admin_maxPower',component:function (resolve) {require(['@/components/page/admin/maxPower'], resolve)}},
      ]
    },
    {
      // 会员模块下路由
      path:'/admin/modular/user/:name',name:'modular_user_app',
      component:function (resolve) {require(['@/components/page/admin/modular/user/app'], resolve)},
      children:[
        {path:'/admin/modular/user/index',name:'modular_user_index',component:function (resolve) {require(['@/components/page/admin/modular/user/index'], resolve)}},
        {path:'/admin/modular/user/add',name:'modular_user_add',component:function (resolve) {require(['@/components/page/admin/modular/user/add'], resolve)}},
      ]
    },
    {
      // 论坛模块下路由
      path:'/admin/modular/forum/:name',name:'modular_forum_app',
      component:function (resolve) {require(['@/components/page/admin/modular/forum/app'], resolve)},
      children:[
        {path:'/admin/modular/forum/index',name:'modular_forum_index',component:function (resolve) {require(['@/components/page/admin/modular/forum/index'], resolve)}},
        {path:'/admin/modular/forum/add',name:'modular_forum_add',component:function (resolve) {require(['@/components/page/admin/modular/forum/add'], resolve)}},
      ]
    },



  ]
})
