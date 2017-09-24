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
 *  | 时间:  2017/8/28
 *  ----------------------------------------
 *
 *  *---------------------------------------
 *  | 描述: 这家伙很懒，什么都没有留下
 *  ----------------------------------------
 */
class arrayOpera{

    /**
     * 直接删除数组方法
     * @param $data
     * @param $key
     * @return mixed
     */
    public static function remove($data, $key){
        if(!array_key_exists($key, $data)){
            return $data;
        }
        $keys = array_keys($data);
        $index = array_search($key, $keys);
        if($index !== FALSE){
            unset($data[$index]);
            array_splice($data, $index, 1);
        }
        return $data;
    }

    /**
     * 重装数组方法
     * @param $data
     * @param $delKey
     * @return array
     */
    public static function remove_all($data,$delKey){
        $newArray = array();
        if(is_array($data)) {
            foreach($data as $key => $value) {
                if($key !== $delKey) {
                    $newArray[$key] = $value;
                }
            }
        }else {
            $newArray = $data;
        }
        return $newArray;
    }

}

