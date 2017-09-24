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
 *  | 时间:  2017/8/26
 *  ----------------------------------------
 *
 *  *---------------------------------------
 *  | 描述: 这家伙很懒，什么都没有留下
 *  ----------------------------------------
 */
use source;
class common extends \APP\Model\common {
    /**
     * 初始化数据
     * @return array|mixed
     */
    public static function init(){
        return parent::init();
    }

    /**
     * 获取验证码
     */
    public static function getVerify(){
        message('success',200,['src'=>source\common\verify::getSrc()]);
    }

    /**
     * 获取配置
     * @return array
     */
    public static function getConfig(){
        $request = \CZ::$app->request;
        $mod  =  $request->get('mod');
        if ( 'UserUpdateInfo' === $mod){
            $config = \get::config('user');
            if (empty($config)){
                return [];
            }
            message('success',200,['app_user_modify_config'=>$config['modifyInfo']]);
        }
    }

}

