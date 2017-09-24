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
 *  | 时间:  2017/9/1
 *  ----------------------------------------
 *
 *  *---------------------------------------
 *  | 描述: 这家伙很懒，什么都没有留下
 *  ----------------------------------------
 */
class forum{
    /**
     * 获取论坛分类
     * @return int
     */
    protected static function getType(){
        $all = \db::table('forum_type')->getAll();
        return $all;
    }
    /**
     * 获取论坛
     * @return array
     */
    protected static function get(){
        $all = \db::table('forum')->getAll();
        $count = count($all);
        $array = [];
        $tmp   = [];
        $sql   = 'SELECT name FROM {prefix}forum_type  WHERE `id`=:id ';
        for ($i = 0; $i < $count;$i++){
            $array[$i]['id']        = \paw::encrypt($all[$i]['id']);
            $array[$i]['name']      = $all[$i]['name'];
            $array[$i]['icon']      = $all[$i]['icon'];
            $array[$i]['iconType']  = $all[$i]['iconType'];
            $array[$i]['info']      = $all[$i]['info'];
            $array[$i]['type']      = $all[$i]['type'];
            if (isset($tmp[$all[$i]['type']])){
                $array[$i]['typeName']      = $tmp[$all[$i]['type']]['name'];
            }else{
                $tmpAll = \db::query($sql,[
                    ':id'=>$all[$i]['type']
                ]);
                $array[$i]['typeName'] = empty($tmpAll) ? '' : $tmpAll['name'];
                $tmp[$all[$i]['type']] = $tmpAll;
            }
        }
        unset($tmp);
        return $array;
    }

}

