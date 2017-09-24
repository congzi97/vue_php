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
 *  | 时间:  2017/6/27
 *  ----------------------------------------
 *
 *  *---------------------------------------
 *  | 描述: 这家伙很懒，什么都没有留下
 *  ----------------------------------------
 */
class sort{

    /**
     * 二维数组 根据某个值排序
     * @param $array
     * @param $field
     * @param string $direction
     * @return array
     */
    public static function TDSort($array,$field,$direction = 'SORT_DESC'){
        if (!is_array($array)){
            return $array;
        }
        if ($direction != 'SORT_DESC'){
            if ($direction != 'SORT_ASC'){
                return $array;
            }
        }
        $arrSort = array();
        foreach($array AS $uniqid => $row){
            foreach($row AS $key=>$value){
                $arrSort[$key][$uniqid] = $value;
            }
        }
        array_multisort($arrSort[$field], constant($direction), $array);

        return $array;

    }
}