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
 *  | 时间:  2017/8/23
 *  ----------------------------------------
 *
 *  *---------------------------------------
 *  | 描述: 这家伙很懒，什么都没有留下
 *  ----------------------------------------
 */
use source;
class display{

    public static function urlMain($pathInfo){
        $arr = explode('.',$pathInfo[0]);
        $pas = \paw::authcode(base64_decode($arr[0]),'DECODE',KEY);
        if ( '' === $pas){
            $pas = paw::decrypt($arr[0],KEY);
        }
        $array = get::toArray($pas);
        if ('verify' === $array['c']){
            source\common\verify::display();
        }elseif ('userAvatar' === $array['c']){
            self::img(USER_AVATAR_DIR.$array['src']);
        }

    }

    /**
     * 输出图片
     * @param $src
     */
    static private function img($src){
        header('Content-type:image/*');
        echo file_get_contents($src);
        exit();
    }
}
