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
 *  | 时间:  2017/8/19
 *  ----------------------------------------
 *
 *  *---------------------------------------
 *  | 描述: 这家伙很懒，什么都没有留下
 *  ----------------------------------------
 */
spl_autoload_register(function ($class_name) {
    if (isset($_REQUEST['loaderClass'][$class_name])){
        return true;
    }
    $arr = explode('\\',$class_name);
    // 加载 类库
    if (file_exists(CZ_BLOG_DIR.'/core/'.$class_name.'.class.php')){
        $_REQUEST['loaderClass'][$class_name] = $class_name;
        @include CZ_BLOG_DIR.'/core/'.$class_name.'.class.php';
    }
    else if ('source' === $arr[0]){
        // 加载 `APP返回数据`
        if (file_exists(CZ_BLOG_DIR.'/source/'.$arr[1].'/'.$arr[2].'.class.php')){
            $_REQUEST['loaderClass'][$class_name] = $class_name;
            @include CZ_BLOG_DIR.'/source/'.$arr[1].'/'.$arr[2].'.class.php';
        }
    } else if ('app' === strtolower($arr[0])){
        $file = APP_DIR.'/'.strtolower($arr[1]).'/'.ucfirst($arr[2]).$arr[1].'.php';
        if (file_exists($file)){
            $_REQUEST['loaderClass'][$class_name] = $class_name;
            @include $file;
            return true;
        }
    }



});
