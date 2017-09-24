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
 *  | 时间:  2017/8/20
 *  ----------------------------------------
 *
 *  *---------------------------------------
 *  | 描述: 这家伙很懒，什么都没有留下
 *  ----------------------------------------
 */

return  [
    'init'          =>  ['mc'=>'common/init','login'=>false,'method'=>'post'],
    'sign'          =>  ['mc'=>'user/sign','login'=>false,'method'=>'post'],
    'login'         =>  ['mc'=>'user/login','login'=>false,'method'=>'post'],
    'getVerify'     =>  ['mc'=>'common/getVerify','login'=>false,'method'=>'post'],
    'forgetPass'    =>  ['mc'=>'user/forgetPass','login'=>false,'method'=>'post'],
    'config'        =>  ['mc'=>'common/getConfig','login'=>false,'method'=>'post'],
    'updateUser'    =>  ['mc'=>'user/update','login'=>true,'method'=>'post'],
    'forum'         =>  ['mc'=>'forum/index','login'=>false,'method'=>'post'],
    'admin'         =>  ['mc'=>'admin/index','login'=>true,'method'=>'post'],
];
