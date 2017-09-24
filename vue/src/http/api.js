
import fetch from './fetch'

// 定义接口

// 初始化接口
export const init = (data) => fetch('init', data);

// 注册接口
export const sign = (data) => fetch('sign',data);
// 注册接口
export const login = (data) => fetch('login',data);
// 获取验证码接口
export const getVerify = (data) => fetch('getVerify',data);
// 找回密码接口
export const forgetPass = (data) => fetch('forgetPass',data);
// 获取配置接口
export const config = (data) => fetch('config',data);
// 更新会员资料接口
export const updateUser = (data, upload = false, uploadCallBack = '') => fetch('updateUser',data, upload, uploadCallBack);
// 论坛
export const forum = (data) => fetch('forum',data);
// 后台接口
export const admin = (data, upload = false, uploadCallBack = '') => fetch('admin',data, upload, uploadCallBack);

