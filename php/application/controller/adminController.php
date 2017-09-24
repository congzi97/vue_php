<?php
namespace APP\Controller;
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
 *  | 时间:  2017/9/2
 *  ----------------------------------------
 *
 *  *---------------------------------------
 *  | 描述: 这家伙很懒，什么都没有留下
 *  ----------------------------------------
 */
use source;
use APP;
class admin{

    /**
     * 后台接口统一方法(方便权限验证)
     *
     */
    public static function  index(){
        $request = \CZ::$app->request;
        $mod = $request->get('mod',false);
        $uid        = \session::get('uid');
        if ( false === source\user\is::uid($uid)){
            message('请登录再来');
        }
        $myPower    = APP\Controller\user::getPower();
        if (empty($myPower) ||  1 !== $myPower['loginAdmin']){
            message('没有权限...');
        }
//        if (!isset($myPower['modular'][$mod]) || false === $myPower['modular'][$mod] ){
//            return false;
//        }
        $_REQUEST['power'] = $myPower;
        unset($myPower);
        switch ($mod){
            case 'moduleForumInit':
                source\admin\forum::init();
                break;
            case 'addUser':
                source\admin\user::addUser();
                break;
            case 'addForum':

                break;
        }

    }


}
