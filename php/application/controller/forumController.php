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
 *  | 时间:  2017/9/1
 *  ----------------------------------------
 *
 *  *---------------------------------------
 *  | 描述: 这家伙很懒，什么都没有留下
 *  ----------------------------------------
 */
class forum extends \APP\Model\forum {

    public static function index(){
        $request = \CZ::$app->request;
        $mod  =  $request->get('mod');
        if ('getForumList' === $mod){
            self::get();
        }

    }
    /**
     * 获取论坛数据
     */
    public static function get(){
        $mem = \cacheMem::init();
        // 判断缓存是否存在
        $name = md5('forumList');
        $res = $mem::get($name);
        if ( false === $res){
            // 向model拿数据
            $f_list = parent::get();
            $type = parent::getType();
            $list = [
                'type'  =>    $type,
                'list'  =>    $f_list,
            ];
            $mem::add($name,$list);
        }else{
            $list = $res;
            unset($res);
        }
        message('获取论坛列表成功',200,$list);
    }

}

