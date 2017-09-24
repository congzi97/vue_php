<?php
namespace APP\Model;
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
use source;
class common{

    /**
     * 初始化数据
     * @return array|mixed
     */
    protected static function init(){
        $mem = \cacheMem::init();
        $name = md5('initData');
        $res = $mem::get($name);
        if ( false === $res){
            $userRes = self::initUser();
            $userRes['getDone'] = true;
            $powerRes  = \APP\Controller\user::getPower();
            $powerRes['getDone'] = true;
            $all = [200,'获取成功',[
                'user'  =>  $userRes,
                'power' =>  $powerRes
            ]];
            $mem::add($name,$all);
            return $all;
        }
        return $res;
    }

    /**
     * 获取会员资料
     * @return array|mixed
     */
    static private function initUser(){
        if ( true === \session::get('login')){
            // 获取缓存数据
            $mem = \cacheMem::init();
            $res = $mem::get(\session::get('m_uid'));
            if (empty($res)){
                $_REQUEST['mSid'] = base64_encode(\paw::authcode('sid='.$_REQUEST['sid'].'&token='.$_REQUEST['token'].'&time='.time(),'ENCODE',KEY));
                $all = source\user\get::info(\session::get('uid'));
                $all = \arrayOpera::remove($all,'id');
                $p   = source\user\get::avatarUrl($all['avatar']);
                $all['avatar']  = SERVER_DOMAIN_NAME.'/'.$p;
                $all['di']      = 1 === $all['di'] ? true : false;
                $all['login']   = true;
                $all['sid']     = $_REQUEST['mSid'];
                $mem::add(\session::get('m_uid'),$all,3600);
                return $all;
            }else{
                return $res;
            }
        }
        $_REQUEST['mSid'] = base64_encode(\paw::authcode('sid='.$_REQUEST['sid'].'&token='.$_REQUEST['token'].'&time='.time(),'ENCODE',KEY));
        $src = source\user\get::avatarUrl('avatar.jpg');
        return [
            'login'     => false,
            'sid'       => $_REQUEST['mSid'],
            'member_id' => 0,
            'email'     => '',
            'avatar'    =>  SERVER_DOMAIN_NAME.'/'.$src,
            'jifen'     => 0,
            'experience'=> 0,
            'bi'        => 0,
            'nick'      => '游客('.\get::rand(6).')',
            'di'        => false,
            'sex'       => '保密',
            'birthday'  => '1900-01-01',
            'age'       => 0,
            'city'      => '广东深圳市',
            'autograph' => '这家伙很懒...',
        ];
    }

}

