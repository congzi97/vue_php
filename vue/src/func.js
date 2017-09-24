import Vue from 'vue'
// 定义网页标题
Vue.prototype.setTitle = (title => {
  document.title = 'CZ的博客 - '+title;
});
// 定义获取class元素方法
Vue.prototype.getClassName = ( className => {
  let objArray= new Array();//定义返回对象数组
  let tags=document.getElementsByTagName("*");//获取页面所有元素
  let index = 0;
  let i ;
  for(i in tags){
    if( 1 === tags[i].nodeType ){
      if( className === tags[i].getAttribute("class") ){
        objArray[index]=tags[i];
        index++;
      }
    }
  }
  return objArray;
});
// 验证邮箱
Vue.prototype.isEmail = (email => {
  let reg = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return reg.test(email);
});
// 验证手机号码
Vue.prototype.isPhone = (str => {
  let reg = /^1(3|4|5|7|8)\d{9}$/;
  return reg.test(str);
});
// 密码检查
Vue.prototype.isPassword = (password =>{
  let str = password;
  if (str === null || str.length < 8) {
    return false;
  }
  let reg = new RegExp(/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9a-zA-Z]{6,20}$/);
  return reg.test(str);
});
/**
 * 去除两边的一个字符
 * @type {function(*)}
 */
Vue.prototype.trimrl = ((str) => {
  let tmp = str.substr(1);
  return tmp.substring(0,tmp.length - 1);
});
/**
 * 去除字符串中的字符
 * @type {function(*=, *)}
 */
Vue.prototype.trim = ((char, type) => {
  if (char) {
    if (type === 'left') {
      return this.replace(new RegExp('^\\'+char+'+', 'g'), '');
    } else if (type === 'right') {
      return this.replace(new RegExp('\\'+char+'+$', 'g'), '');
    }
    return this.replace(new RegExp('^\\'+char+'+|\\'+char+'+$', 'g'), '');
  }
  return this.replace(/^\s+|\s+$/g, '');
});
/**
 * input type="file" 获取预览图片地址
 * @type {function(*=)}
 */
Vue.prototype.getInputUrl = ((file) => {
  let url = null ;
  if (window.createObjectURL!=undefined) { // basic
    url = window.createObjectURL(file) ;
  } else if (window.URL!=undefined) { // mozilla(firefox)
    url = window.URL.createObjectURL(file) ;
  } else if (window.webkitURL!=undefined) { // webkit or chrome
    url = window.webkitURL.createObjectURL(file) ;
  }
  return url ;
});
Vue.prototype.getIndexOf = function(arr,val) {
  for (let i = 0; i < arr.length; i++) {
    if (arr[i] === val) return i;
  }
  return -1;
};
Vue.prototype.removeArray = function(arr,val) {
  let index = this.getIndexOf(val);
  if (index > -1) {
    arr.splice(index, 1);
  }
};

