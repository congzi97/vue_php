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
class get {

    static private $toStr = '';

    /**
     * 数组转字符串
     * @param array|null $data
     * @return string
     */
    public static function toParams( ? array  $data){
        foreach ($data as $key => $value){
            if (is_array($data[$key])){
                self::toParams($data[$key]);
            }else{
                $toStr = '' === self::$toStr ? $key.'='.$value : '&'.$key.'='.$value;
                self::$toStr .= $toStr;
            }
        }
        return self::$toStr;
    }
    static private $toArr = [];

    /**
     * 字符串转数组
     * @param $data
     * @return array
     */
    public static function toArray( $data){
        $arr = explode('&',$data);
        $count = count($arr);
        for ($i = 0; $i < $count ; $i++){
            $tmp = explode('=',$arr[$i]);
            self::$toArr[$tmp[0]] = $tmp[1];
        }
        return self::$toArr;
    }

    /**
     * 获取配置数据
     * @param null|string $filename
     * @param null|string $parame
     * @return array|mixed
     */
    public static function config( ? string $filename, ? string $parame = ''){
        if (file_exists(ROOT_DIR.'/config/'.$filename.'.php')){
            if ( 'cache' !== $filename){
                $res = cacheMem::get('config_'.$filename);
                if (false == $res){
                    $data = @include ROOT_DIR.'/config/'.$filename.'.php';
                    cacheMem::add('config_'.$filename,$data);
                    return $data;
                }else{
                    return $res;
                }
            }else{
                $data = @include ROOT_DIR.'/config/'.$filename.'.php';
                return $data;
            }
        }else if (file_exists(APP_DIR.'/config/'.$filename.'.php')){
            // 获取APP配置
            $res = cacheMem::get('config_app_'.$filename);
            if (false == $res){
                $data = @include APP_DIR.'/config/'.$filename.'.php';
                cacheMem::add('config_app_'.$filename,$data);
                return $data;
            }else{
                return $res;
            }
        }else{
            return [];
        }


    }
    /**
     * 获取随机字符串
     * @param int $length
     * @param int $type
     * @return string
     */
    public static function rand($length = 4,$type = 2){
        $str = [
            'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            '0123456789',
            'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
        ];
        $str = isset($str[$type]) ? $str[$type] : $str[2];
        $count = strlen($str);
        $randData = '';
        for ($i = 0 ; $i < $length;$i++){
            $randData .= $str[rand(0,$count - 1)];
        }
        return $randData;
    }

    /**
     * 上传文件时获取错误信息
     * @param $number
     * @return string
     */
    public static function uploadError($number){
        switch ($number){
            case 1:
                return '上传的文件大于服务器限制的值';
                break;
            case 2:
                return '上传文件的大小超过了HTML表单MAX_FILE_SIZE选项指定的值';
                break;
            case 3:
                return '文件只有部分被上传';
                break;
            case 4:
                return '没有文件被上传';
                break;
            case 6:
                return '没有指定临时文件夹';
                break;
            default:
                return  '未知错误';
                break;
        }
    }

    /**
     * 获取字符串长度，支持中英文
     * @param $str
     * @return int
     */
    public static function strLength($str){
        preg_match_all("/./us", $str, $matches);
        return count(current($matches));
    }
    /**
     * 获取 文件后缀
     * @param $filename
     * @return mixed
     */
    public static function fileHZ($filename){
        return pathinfo($filename,PATHINFO_EXTENSION);
    }

    /**
     * 时间差
     * @param bool $day1  默认 获取实时时间
     * @param $day2
     * @return bool|string
     */
    public static function timeDifference($day1 = true, $day2){
        $second1 = $day1 == true ? time() : strtotime($day1);
        $second2 = strtotime($day2);
        if (ceil($second1 - $second2) < 0)
            return false;
        //返回字符串  格式 天:时:分:秒
        $str = ceil(($second1 - $second2) / (3600 * 24)).':'.ceil(($second1 - $second2)/3600).':'.ceil(($second1 - $second2) /60).':'.ceil($second1 - $second2);
        return explode(':',$str);
    }
    // 根据 时间 返回 格式 月份-天数 时:分
    public static function timezh($time){
        $arr = explode(' ',$time);
        $arr1 = explode('-',$arr[0]);
        $arr2 = explode(':',$arr[1]);
        return $arr1[1].'-'.$arr1[2].' '.$arr2[0].':'.$arr2[1];
    }
    // 根据 时间 返回 格式 年-月份-天数
    public static function timent($time){
        $arr = explode(' ',$time);
        $arr1 = explode('-',$arr[0]);
        $arr2 = explode(':',$arr[1]);
        return $arr1[0].'-'.$arr1[1].'-'.$arr1[2];
    }
    /**
     * 时间前
     * @param $the_time
     * @return string
     */
    public static function beforeTime($the_time){
        $now_time = time();
        $show_time = strtotime($the_time);
        $dur = $now_time - $show_time;
        if($dur < 60){
            return $dur.'秒前';
        }else if($dur < 3600){
            return floor($dur/60).'分钟前';
        }else if($dur < 86400) {
            return floor($dur/3600).'小时前';
        }else if($dur < 259200) {//3天内
            return floor($dur / 86400) . '天前';
        }else{
            return $the_time;
        }
    }
    /**
     * 获取时间
     * @return false|string
     */
    public static function time(){
        return date('Y-m-d H:i:s',time());
    }
    /**
     * 获取 设备 操作系统
     * @return string
     */
    public static function USER_AGENT(){

        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $_is_pc = (strpos($agent, 'windows')) ? true : false;
        $_is_mac = (strpos($agent, 'Mac')) ? true : false;
        $_is_iphone = (strpos($agent, 'iphone')) ? true : false;
        $_is_ipad = (strpos($agent, 'ipad')) ? true : false;
        $_is_android = (strpos($agent, 'android')) ? true : false;

        if($_is_pc)
            return 'Windows';

        if($_is_mac)
            return 'Mac';

        if($_is_iphone)
            return 'iPhone';

        if($_is_ipad)
            return 'iPad';

        if($_is_android)
            return 'Android';

        return 'Unknown';
    }

    /**
     * 返回浏览器名称
     * @return string
     */
    public static function getBrowser(){
        if(!empty($_SERVER['HTTP_USER_AGENT']))
        {
            $br = $_SERVER['HTTP_USER_AGENT'];
            if (preg_match('/MSIE/i',$br)){
                $br = 'MSIE';
            }
            elseif (preg_match('/Firefox/i',$br)){
                $br = 'Firefox';
            }elseif (preg_match('/Chrome/i',$br)){
                $br = 'Chrome';
            }elseif (preg_match('/Safari/i',$br)){
                $br = 'Safari';
            }elseif (preg_match('/Opera/i',$br)){
                $br = 'Opera';
            }else {
                $br = 'Other';
            }
            return $br;
        }else
            return "Chrome";
    }

    /**
     * 获取客户端,及浏览器所在的电脑的ip地址
     * @return mixed
     */
    public static function getip()
    {
        return $_SERVER['REMOTE_ADDR'];
    }
}

