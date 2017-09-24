/**
 * @Author: congzi
 * @Date:   2017-08-25T14:39:39+08:00
 * @Last modified by:   congzi
 * @Last modified time: 2017-08-25T15:29:45+08:00
 */
import {
  baseUrl,
  RSA_PUBLIC_KEY,
  TOKEN
} from '../config/config'
import RAS from 'jsencrypt'
import Vue from 'vue'
import { AjaxPlugin } from 'vux'
Vue.use(AjaxPlugin)
import {
  getStore,
  getSessionStore,
  setSessionStore
} from './mUtils'
import { md5 } from 'vux'
const randomString = (len => {
  len = len || 10;
  let $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  let maxPos = $chars.length;
  let pwd = '';
  for (let i = 0; i < len; i++) {
    pwd += $chars.charAt(Math.floor(Math.random() * maxPos));
  }
  return pwd;
});
const getSid = () => {
  let str = getStore('sid')
  if (str !== null) {
    return str
  }
  let timestamp1 = Date.parse(new Date());
  return ['sid='+md5(randomString(10))+'&token='+md5(randomString(16))+'&time='+md5(timestamp1)];
}
/**
 * 生成 sign
 * @param data
 * @returns {string}
 */
const getSign = (data) => {
  let str = '';
  let array = new Array();
  Object.keys(data).forEach(function (key) {
    // 将key合并为数组
    array.push(key);
  });
  array.sort();
  for (let i =0; i <array.length;i++){
    str += array[i]+'='+data[array[i]]+'&';
  }
  str = str+'key='+TOKEN;
  str = md5(str);
  return str.toLocaleUpperCase();
};
// 暂时删除 get 方法，因为加密后传输数据较大
export default async (url, data, upload = false, uploadCallBack = '') => {
  AjaxPlugin.$http.defaults.headers['Content-Type'] = 'application/x-www-form-urlencoded;charset=UTF-8;';
  url = baseUrl + url;
  let rsaObj = new RAS.JSEncrypt()
  rsaObj.setPublicKey(RSA_PUBLIC_KEY)
  let fd = new FormData()
  let sid = getSid()
  let en_sid = '';
  if ( 'object' === typeof sid){
    en_sid = rsaObj.encrypt(sid[0]);
  }else{
    en_sid = sid;
  }
  let signData = new Array();
  if (JSON.stringify(data) !== '{}') {
    let str = ''
    Object.keys(data).forEach(function (key) {
      if (typeof data[key] === 'object') {
        let tmp = rsaObj.encrypt(data[key][0]);
        fd.append(key, tmp);
        signData[key] = data[key][0];
        str += ',' + key
      } else {
        fd.append(key, data[key])
        signData[key] = data[key];
      }
    })
    if (str !== '') {
      str = str.substr(1)
      // 告诉服务器加密的字符串(key)
      let tmp = rsaObj.encrypt(str);
      fd.append('sendServerDecodeStr', tmp)
      signData['sendServerDecodeStr'] = str;
    }
  }
  // ------------添加全局数据 start-----------
  fd.append('sid',en_sid);
  signData['sid'] = en_sid;
  let rand = randomString(16);
  fd.append('rand', rand);
  signData['rand'] = rand;
  // ------------添加全局数据 end  -----------
  let sign = getSign(signData);
  fd.append('sign', sign);
  if (upload === false) {
    return new Promise((resolve, reject) => {
      AjaxPlugin.$http.post(url, fd)
        .then(res => {
          resolve(res['data'])
        }, () => {
          reject({'code': -1, 'msg': '连接服务器失败'})
        })
        .catch(()=> {
          reject({'code': -1, 'msg': '连接服务器失败'})
        })
    })
      .catch(err => {
        return err;
      })
  }
  // 导入上传附件对象
  Object.keys(upload).forEach(function (key) {
    fd.append(key,upload[key]);
  })
  // 上传附件
  return new Promise((resolve, reject) => {
    AjaxPlugin.$http.post(url, fd, {
      onUploadProgress: function (progressEvent) {
        // let per = 100 * progressEvent.loaded/progressEvent.total;
        // reject({'code':2,'msg':'正在上传附件','progressBar':per})
        '' === uploadCallBack ? '' : uploadCallBack(progressEvent);
      }
    })
      .then(res => {
        resolve(res['data'])
      }, () => {
        reject({'code': -1, 'msg': '连接服务器失败'})
      })
      .catch(() => {
        reject({'code': -1, 'msg': '连接服务器失败'})
      })
  })
    .catch(err => {
      return err
    })
}
