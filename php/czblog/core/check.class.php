<?php
/** *---------------------------------------
 *  | CjForever
 *  ----------------------------------------
 *
 *  *---------------------------------------
 *  | 作者:  CJForever by: 544301197@qq.com
 *  ----------------------------------------
 *
 *  *---------------------------------------
 *  | 版权:  © 2017 <CjForever>  Inc
 *  ----------------------------------------
 *
 *  *---------------------------------------
 *  | 时间:  2017/7/28
 *  ----------------------------------------
 *
 *  *---------------------------------------
 *  | 描述: 这家伙很懒，什么都没有留下
 *  ----------------------------------------
 */
/**
 * 公共检查数据
 * Class check
 * @package CZPhp
 */
class check{
    /**
     * 是否是GET提交的
     */
    public static function isGet(){
        return $_SERVER['REQUEST_METHOD'] == 'GET' ? true : false;
    }

    /**
     * 是否是POST提交
     * @return bool
     */
    public static function isPost() {
        return $_SERVER['REQUEST_METHOD'] == 'POST' ? true : false;
    }
    /**
     * 检查是否是时间格式
     * @param $dateTime
     * @return bool
     */
    public static function dateTime($dateTime){
        $ret = strtotime($dateTime);
        return $ret !== FALSE;
    }
    /**
     * 判断某个数字是否在区间内
     * 例: [0,10] (1,10) (0,10] [1,10)
     * @param $str
     * @param $int
     * @return bool
     */
    static public function intBetween($str,$int){
        if (is_string($str) == false){
            $str = strval($str);
        }
        if (preg_match('/\[([0-9]+),([0-9]+)\]/',$str) == true){
            $str = ltrim($str,'[');
            $str = rtrim($str,']');
            $arr = explode(',',$str);
            return $arr[0] <= $int && $int <= $arr[1];
        }
        if (preg_match('/\(([0-9]+),([0-9]+)\)/',$str) == true){
            $str = ltrim($str,'(');
            $str = rtrim($str,')');
            $arr = explode(',',$str);
            return $arr[0] < $int && $int < $arr[1];
        }
        if (preg_match('/\(([0-9]+),([0-9]+)\]/',$str) == true){
            $str = ltrim($str,'(');
            $str = rtrim($str,']');
            $arr = explode(',',$str);
            return $arr[0] < $int && $int <= $arr[1];
        }
        if (preg_match('/\[([0-9]+),([0-9]+)\)/',$str) == true){
            $str = ltrim($str,'[');
            $str = rtrim($str,')');
            $arr = explode(',',$str);
            return $arr[0] <= $int && $int < $arr[1];
        }

        return false;
    }
    /**
     * 判断邮箱格式
     * @param $str
     * @return bool
     */
    static public function email($str){
        if (false === filter_var($str, FILTER_VALIDATE_EMAIL)){
            return false;
        }
        return true;
//        if ($str == '')
//            return false;
//        $res  =  preg_match('/([a-zA-Z0-9_-])+@([a-zA-Z0-9_-]{2,8})(\.[a-zA-Z0-9_-])+/',$str);
//        return $res == 1 ? true : false;
    }
    static public function password($str){
        if ($str == '')
            return false;
        $res  =  preg_match('/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9a-zA-Z]{6,20}$/',$str);
        return $res == 1 ? true : false;
    }
    /**
     * 检测上传文件格式是否允许
     * @param $type
     * @param $fileName
     * @return bool
     */
    static public function fileName($type,$fileName){
        if (strstr($fileName,'.') == false)
            return false;
        $arr = explode('.',$fileName);
        $fileType = strtolower(end($arr));
        if (strstr($type,',') == false){
            if (strtolower($type) == $fileType)
                return true;
            else
                return false;
        }else{
            $arr2 = explode(',',$type);
            for ($i = 0; $i < count($arr2); $i++){
                if (strtolower($arr2[$i]) == $fileType){
                    return true;
                }
            }
            return false;
        }
    }

    /**
     * 判断是否是全中文
     * @param $str
     * @return bool
     */
    static public function isAllChinese($str){
        if(!preg_match("[^\x80-\xff]","$str")){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 判断是否包含中文
     * @param $str
     * @return bool
     */
    static public function isChinese($str){
        if (preg_match("/([\x81-\xfe][\x40-\xfe])/", $str, $match)) {
            return true;
        } else {
            return false;
        }
    }

}

