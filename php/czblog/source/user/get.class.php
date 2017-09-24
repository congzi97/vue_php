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
 *  | 时间:  2017/8/28
 *  ----------------------------------------
 *
 *  *---------------------------------------
 *  | 描述: 这家伙很懒，什么都没有留下
 *  ----------------------------------------
 */
use source;
class get{
    /**
     * 获取会员头像URL
     * @param $str
     * @return mixed
     */
    public static function avatarUrl($str){
        $mem = \cacheMem::init();
        $md  = 'userAvatarUrl'.md5($str);
        $res = $mem::get($md);
        if ( false === $res ){
            $type = \get::fileHZ($str);
            $data = \paw::encrypt('c=userAvatar&src='.$str).'.'.$type;
            $mem::add($md,$data,time()+3600);
            return $data;
        }else{
            return $res;
        }
    }
    /**
     * 获取会员资料
     * @param $uid
     * @return array|mixed
     */
    public static function info($uid){
        $uid = intval($uid);
        $all = pdo_fetch('user_info',['member_id'=>$uid]);
        if (empty($all)){
            return [];
        }
        return $all;
    }
    /**
     * 获取登录记录
     * @param $email
     * @return array|mixed
     */
    public static function loginRecord( $email ){
        if ( '' === $email || false === \check::email($email)){
            return [];
        }
        $mem = \cacheMem::init();
        $res = $mem::get('loginRecord_'.$email);
        if ( false === $res){
            $all = pdo_fetchAll('user_login',['email'=>$email]);
            // 添加缓存  半个小时后过期
            $mem::add('loginRecord_'.$email,$all,5400);
            return $all;
        }else{
            return $res;
        }
    }

}
