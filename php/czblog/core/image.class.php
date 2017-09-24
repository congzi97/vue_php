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
 *  | 时间:  2017/7/15
 *  ----------------------------------------
 *
 *  *---------------------------------------
 *  | 描述: 这家伙很懒，什么都没有留下
 *  ----------------------------------------
 */


class image{
    /**
     * 高清压缩图片
     * @param $source 源图片地址
     * @param $saveName 保存地址
     * @param float $percent 缩小比例
     * @return bool
     */
    static public function HDCompression($source,$saveName,$percent = 0.5){
        if (!file_exists($source)){
            return false;
        }
        list( $width ,  $height )   =  getimagesize ( $source );
        (int)$new_width             =  $width  *  $percent ;
        (int)$new_height            =  $height  *  $percent;
        $image_p                    =  imagecreatetruecolor ( $new_width ,  $new_height );
        $hz                         =  get::fileHZ($source);
        switch (strtolower($hz)){
            case 'jpg':
                $function = 'imagecreatefromjpeg';
                break;
            case 'jpeg':
                $function = 'imagecreatefromjpeg';
                break;
            case 'png':
                $function = 'imagecreatefrompng';
                break;
            case 'gif':
                $function = 'imagecreatefromgif';
                break;
        }
        $image  =  $function($source);
        if (!$image){
            return false;
        }
        imagecopyresampled ( $image_p ,  $image ,  0 ,  0 ,  0 ,  0 ,  $new_width ,  $new_height ,  $width ,  $height );
        imagejpeg($image_p,$saveName);
        return true;
    }
}