<?php
namespace source\admin;
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
 *  | 时间:  2017/9/7
 *  ----------------------------------------
 *
 *  *---------------------------------------
 *  | 描述: 这家伙很懒，什么都没有留下
 *  ----------------------------------------
 */
class user{
    /**
     * 手动添加会员
     */
    public static function addUser(){
        $request    = \CZ::$app->request;
        $email      = $request->get(email);
        $password   = $request->get(password);
        $password2  = $request->get(password2);
        self::addUserCheck($email,$password,$password2);
        // 添加到数据库 , 调用sign提供的接口
        \source\user\sign::ManualAdd($email,$password);
    }
    /**
     * 添加会员检查
     * @param $email
     * @param $password
     * @param $password2
     */
    private static function addUserCheck($email,$password,$password2){
        if ( '' === $email){
            message('请输入邮箱');
        }
        if ( '' === $password ){
            message('请输入密码');
        }
        if ( '' === $password2 ){
            message('请输入确定密码');
        }
        if (false === \check::email($email)){
            message('邮箱错误');
        }
        if (false === \check::password($password)){
            message('请输入正确的密码');
        }
        if ( true === \source\user\is::email($email)){
            message('邮箱已经注册');
        }
        if ( $password !== $password2){
            message('两次密码不相同');
        }
    }
}

