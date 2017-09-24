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
 *  | 时间:  2017/8/26
 *  ----------------------------------------
 *
 *  *---------------------------------------
 *  | 描述: 这家伙很懒，什么都没有留下
 *  ----------------------------------------
 */
class session {

    /**
     * 初始化
     * @param $sid
     */
    public static function init($sid){
        ini_set('session.auto_start', 0);
        ini_set('session.cookie_domain', 'localhost');
        session_id($sid);
        session_start();
    }
    /**
     * 设置session
     * @param $name `Session名称`
     * @param $value `session值`
     * @param int $time 超时时间(秒)
     */
    public static function set($name,$value,$time = 0){
        $_SESSION[$name] = $value;
        $_SESSION[$name.'_Expires'] = 0 === $time ? 0 : time() + $time;
    }
    /**
     * 获取Session值
     * @param $name
     * @return null
     */
    public static function get($name){
        //检查Session是否已过期
        if(isset($_SESSION[$name.'_Expires']) ){
            if ( 0 === $_SESSION[$name.'_Expires']){
                return $_SESSION[$name];
            }else{
                if ($_SESSION[$name.'_Expires']>time()){
                    return $_SESSION[$name];
                }else{
                    Session::clear($name);
                    return null;
                }
            }
        }else{
            Session::clear($name);
            return null;
        }
    }
    /**
     * 清除某一Session值
     * @param $name `Session名称`
     */
    public static function clear($name){
        unset($_SESSION[$name]);
        unset($_SESSION[$name.'_Expires']);
    }
    /**
     * 重置销毁Session
     */
    public static function destroy(){
        unset($_SESSION);
        session_destroy();
    }

}

