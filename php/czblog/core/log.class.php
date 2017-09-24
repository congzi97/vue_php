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
 *  | 时间:  2017/8/21
 *  ----------------------------------------
 *
 *  *---------------------------------------
 *  | 描述: 这家伙很懒，什么都没有留下
 *  ----------------------------------------
 */

class log{

    private static $filename = '';
    private static $dir = '';
    private static function checkFile(){
        if (file_exists(self::$dir.self::$filename)){
            $fileSize = filesize(self::$dir.self::$filename);
            if (ceil($fileSize/1024) > 1024){
                $tmpArr = explode('.',self::$filename);
                $fileName = $tmpArr[0];
                $fileType = $tmpArr[1];
                $i = 1;
                while (true) {
                    $i ++;
                    // 寻找新的文件名
                    if (file_exists(self::$dir.$fileName.'_'.$i.'.'.$fileType)){
                        $fileSize = filesize(self::$dir.$fileName.'_'.$i.'.'.$fileType);
                        if (ceil($fileSize/1024) > 1024){
                            continue;
                        }else{
                            $open = fopen(self::$dir.$fileName.'_'.$i.'.'.$fileType,'w');
                            fclose($open);
                            return self::$dir.$fileName.'_'.$i.'.'.$fileType;
                        }
                    }else{
                        return self::$dir.$fileName.'_'.$i.'.'.$fileType;
                    }
                }
            }else{
                return self::$dir.self::$filename;
            }
        }else{
            $open = fopen(self::$dir.self::$filename,'w');
            fclose($open);
            return self::$dir.self::$filename;
        }
    }

    /**
     * 记录错误日志
     * @param $content
     * @param null $file_name
     * @return bool
     */
    static public function error($content,$file_name = null){
        if ('' === $content){
            return false;
        }
        $dir = CZ_BLOG_DIR.'/var/log/error/'.date('Ymd').'/';
        if (!is_dir($dir) or !file_exists($dir)){
            mkdir($dir,0777,true);
        }
        self::$dir = $dir;
        self::$filename = $file_name.'.txt';
        $pathFile  =  self::checkFile();
        // 写入 内容
        if (file_exists($pathFile)){
            $fopen = fopen($pathFile,'a');
            fwrite($fopen,$content);
            fclose($fopen);
            return true;
        }else{
            $fopen = fopen(date('Ymd').'_error'.'.txt','a');
            fwrite($fopen,$content);
            fclose($fopen);
            return true;
        }
    }

    /**
     * 记录日志
     * @param $content
     * @param null $file_name
     * @param null $d_dir
     * @return bool
     */
    static public function main($content,$file_name = null,$d_dir = null){
        if ('' === $content){
            return false;
        }
        if (null == $d_dir){
            $dir = CZ_BLOG_DIR.'/var/log/'.date('Ymd').'/';
        }else{
            $dir = CZ_BLOG_DIR.'/var/log/'.date('Ymd').'/'.$d_dir.'/';
        }

        if (!is_dir($dir)){
            mkdir($dir,0777,true);
        }

        self::$dir = $dir;
        self::$filename = null == $file_name ? date('Ymd').'.txt' : $file_name.'.txt' ;
        $pathFile  =  self::checkFile();
        if (file_exists($pathFile)){
            $fopen = fopen($pathFile,'a');
            fwrite($fopen,$content);
            fclose($fopen);
            return true;
        }else{
            $fopen = fopen(date('Ymd').'.txt','a');
            fwrite($fopen,$content);
            fclose($fopen);
            return true;
        }
    }

}





