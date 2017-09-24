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
 *  | 时间:  2017/8/24
 *  ----------------------------------------
 *
 *  *---------------------------------------
 *  | 描述: api接口数据加载文件
 *  ----------------------------------------
 */


// 获取api信息
$pathInfo       =   $_SERVER['PATH_INFO'];
$m_route_name   =   md5($pathInfo);
$mem            =   cacheMem::init();
$m_route        =   $mem::get();
$route          =   $mem;


if (false === DEBUG){
    if ('post' === strtolower($_REQUEST['route']['method'])){
        if (false === check::isPost()){
            message('请使用POST提交');
        }
    }else{
        if (false === check::isGet()){
            message('请使用GET提交');
        }
    }
}
if ( '' === TOKEN){
    message('Access Denied');
}
if (true === $_REQUEST['route']['login']){
    if ( true !== session::get('login')){
        message('请登录再访问',-1);
    }
}

$mc     = explode('/',$_REQUEST['route']['mc']);
$res    = run::api($mc[0],$mc[1]);
message($res[1],$res[0],$res[2]);