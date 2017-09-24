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
class CZBlogException extends \Exception{


    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    static public function sql($msg){
        $file_name = date('Ymd').'_sql_error.text';
        CZPhp\log::error($msg,$file_name);
        \outputAD::error($msg);
    }
    static public function core($e){
        $msg = 'Error:'.$e->getMessage()."\n";
        $msg.= $e->getTraceAsString()."\n";
        $msg.= '异常行号：'.$e->getLine()."\n";
        $msg.= '所在文件：'.$e->getFile()."\n";
        $file_name = 'core_error.text';
        log::error($msg,$file_name,'error');
    }
    public static function api($e){
        $msg = 'Error:'.$e->getMessage()."\n";
        $msg.= $e->getTraceAsString()."\n";
        $msg.= '异常行号：'.$e->getLine()."\n";
        $msg.= '所在文件：'.$e->getFile()."\n";
        $file_name = 'api_error.text';
        log::error($msg,$file_name,'api_error');
        unset($e);
        message('很抱歉，程序异常，请联系管理员');
    }
}

