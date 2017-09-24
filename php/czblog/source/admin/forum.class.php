<?php
namespace source\admin;
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
 *  | 时间:  2017/9/7
 *  ----------------------------------------
 *
 *  *---------------------------------------
 *  | 描述: 这家伙很懒，什么都没有留下
 *  ----------------------------------------
 */

class forum{
    /**
     * 初始化 forum模板
     * @return array
     */
    public static function init(){
        $classification = self::getClassification();
        $data = [
            'classification'    =>  $classification,

        ];
        message('success',200,$data);
    }

    public static function add(){
        $classification = $_REQUEST['classification'];



    }
    /**
     * 获取论坛分类
     * @return int|mixed
     */
    private static function getClassification(){
        $mem    =   \cacheMem::init();
        $name   =   'getClassification';
        $res    =   $mem::get($name);
        if ( false === $res){
            $res = \db::table('forum_classification')->getAll();
        }
        return $res;
    }
}
