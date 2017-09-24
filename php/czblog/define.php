<?php

/** *---------------------------------------
 *  | CjForever
 *  ----------------------------------------
 *
 *  *---------------------------------------
 *  | 作者:  CJForever by: notexpired@163.com
 *  ----------------------------------------
 *
 *  *---------------------------------------
 *  | 版权:  © 2017 <CjForever>  Inc
 *  ----------------------------------------
 *
 *  *---------------------------------------
 *  | 时间:  2017/8/19
 *  ----------------------------------------
 *
 *  *---------------------------------------
 *  | 描述: 这家伙很懒，什么都没有留下
 *  ----------------------------------------
 */

#############初始化前定义,初始化后请勿修改 start #############
define('KEY','CZPhp.cn-2017');

#############初始化前定义,初始化后请勿修改 end  #############
/// 客户端域名
define('CLIENT_DOMAIN_NAME','http://localhost:8080');
/// 服务器域名
define('SERVER_DOMAIN_NAME','http://czblog.com');
// 主目录路径
define('ROOT_DIR',dirname(__DIR__));
// app 目录路径
define('APP_DIR',ROOT_DIR.'/application');
// api 目录路径
define('API_DIR',ROOT_DIR.'/api');
// czBlog 目录路径
define('CZ_BLOG_DIR',ROOT_DIR.'/czblog');
// runtime 目录路径
define('RUNTIME_DIR',ROOT_DIR.'/runtime');
// public 目录路径
define('PUBLIC_DIR',ROOT_DIR.'/public');
// 附件 目录路径
define('UPLOAD_DIR',CZ_BLOG_DIR.'/var/upload/');
// 会员头像 目录路径
define('USER_AVATAR_DIR',CZ_BLOG_DIR.'/var/user/avatar/');
// 调试模式
define('DEBUG',false);
// API模式
define('API',true);
// token 令牌 保持和前端相同
define('SERVER_TOKEN','CZ_BLOG_TOKEN');

