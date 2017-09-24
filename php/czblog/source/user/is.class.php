<?php
namespace source\user;
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
 *  | 时间:  2017/8/22
 *  ----------------------------------------
 *
 *  *---------------------------------------
 *  | 描述: 这家伙很懒，什么都没有留下
 *  ----------------------------------------
 */
class is {
    /**
     * 会员ID 是否存在
     * @param $uid
     * @return bool
     */
    public static function uid($uid){
        $uid = intval($uid);
        if ( 0 >= $uid || !is_int($uid)){
            return false;
        }
        if ( 0 < pdo_row('user',['uid'=>$uid])){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 判断邮箱是否存在，true存在 false不存在
     * @return bool
     */
    public static function email($email){
        if ( false  === \check::email($email)){
            return false;
        }
        if ( 0 < pdo_row('user_info',['email'=>$email])){
           return true;
        }else{
            return false;
        }
    }

    public static function locking($uid){
        $uid = intval($uid);
        $row = pdo_row('user_locking',['member_id'=>$uid,'status'=>1],['and']);
        if ( 0 < $row){
            return true;
        }else{
            return false;
        }
    }
}

