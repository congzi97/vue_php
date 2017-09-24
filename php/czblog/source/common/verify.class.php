<?php
namespace source\common;
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
 *  *---------------------------------------n
 *  | 时间:  2017/8/23
 *  ----------------------------------------
 *
 *  *---------------------------------------
 *  | 描述: 这家伙很懒，什么都没有留下
 *  ----------------------------------------
 */

class verify{

    /**
     * 判断验证码
     * @param $code
     * @return array
     */
    public static function is($code){
        if ( '' === $code){
            return [-1,'请输入验证码'];
        }
        if ( empty($_SESSION['verifyExpires']) || empty($_SESSION['verifyDaAn']) ){
            return [-1,'验证码数据丢失'];
        }
        $config = config('config');
        $timeArr  = \get::timeDifference(true,$_SESSION['verifyExpires']);
        if (empty($timeArr) || $config['expired'] > $timeArr[2]){
            return [-1,'验证码失效了'];
        }

        if (strtolower($code) !== strtolower($_SESSION['verifyDaAn'])){
            return [-1,'验证码错误'];
        }
        return [200,'验证通过'];
    }
    /**
     * 显示验证码
     */
    public static function display(){
        // 获取验证码配置
        $config = config('config');
        $info = $config['verify'];
        $type = strtolower($info['picType']);
        $obj = new \ValidateCode($info);
        if ('xh' === $type){
            $obj->doimg();
            exit();
        }
        $obj->GIF_Image_Code();
        exit();
    }
    /**
     * 获取验证码SRC
     * @return string
     */
    public static function getSrc(){
        self::getCode();
        $_SESSION['verifyDaAn']     = self::$info['daAn'];
        $_SESSION['verifyCode']     = self::$info['code'];
        $_SESSION['verifyExpires']  = time();
        $_REQUEST['mSid'] = base64_encode(\paw::authcode('sid='.$_REQUEST['sid'].'&token='.$_REQUEST['token'].'&time='.time(),'ENCODE',KEY));
        $pas = \paw::authcode('c=verify&time='.time(),'ENCODE',KEY);
        return SERVER_DOMAIN_NAME.'/'.base64_encode($pas).'.png?sid='.$_REQUEST['mSid'];
    }

    public static $info = [];
    public static function getCode(){
        $tmpConfig = empty(self::$info) ? config('config') : self::$info;
        self::$info = $tmpConfig['verify'];
        unset($tmpConfig);
        // 验证码类型 1.字母+数字 2.字母 3.数字 4.加法 5.减法 (加法、减法char必须是数字int)
        switch (self::$info['type']){
            case 1:
                $daAn = \get::rand(self::$info['length']);
                self::$info['code'] = $daAn;
                self::$info['daAn'] = $daAn;
                break;
            case 2:
                $daAn = \get::rand(self::$info['length'],0);
                self::$info['code'] = $daAn;
                self::$info['daAn'] = $daAn;
                break;
            case 3:
                $daAn = \get::rand(self::$info['length'],1);
                self::$info['code'] = $daAn;
                self::$info['daAn'] = $daAn;
                break;
            case 4:
                self::$info['code'] = self::getJiaJianCode('jia');
                break;
            case 5:
                self::$info['code'] = self::getJiaJianCode('jian');
                break;
        }
    }
    private static function getJiaJianCode($type = 'jia'){
        if ('jia' === $type){
            $number     = rand(1,100);
            $number2    = rand(1,100);
            $daAn       = $number + $number2;
            self::$info['daAn'] = $daAn;
            $code = $number . '加'.$number2;
            return $code;
        }
        while (true){
            $number     = rand(1,100);
            $number2    = rand(1,100);
            $daAn       = $number - $number2;
            if (0 >= $daAn){
                continue;
            }
            self::$info['daAn'] = $daAn;
            $code = $number . '减'.$number2;
            return $code;
        }

    }

}
