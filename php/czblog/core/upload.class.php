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
 *  | 时间:  2017/9/4
 *  ----------------------------------------
 *
 *  *---------------------------------------
 *  | 描述: 这家伙很懒，什么都没有留下
 *  ----------------------------------------
 */
class upload{
    // 配置，路径配置
    public $configPath = [];
    // 允许的 格式、大小
    private $allow = [];
    // 保存的路径
    private $savePath = '';
    // 是否随机生成文件名
    private $isRandName = false;
    // 文件名
    private $filename = '';
    /**
     * 初始化
     * upload constructor.
     * @param string $savePath 保存路径 默认从 常量(UPLOAD_DIR => CZ_BLOG_DIR.'/var/upload/')开始
     * @param array $allow
     */
    public function __construct($savePath = '',$allow = [
        'type'  =>  ['rar','zip','7z'], // 格式为数组
        'size'  =>  1024, // KB
    ]){
        $this->savePath = empty($savePath) ? 'default/' : $savePath;
        $this->allow = $allow;
    }
    /**
     * 检查文件
     * @param $file
     * @return array|bool
     */
    private function check( $file ){
        if (empty($file)){
            return [-1,'请选择文件'];
        }
        $error = $file['error'];
        if ( 0 < $error){
            message(get::uploadError($error));
        }
        // 获取文件格式
        $type = get::fileHZ($file['name']);
        if (!in_array(strtolower($type),$this->allow['type'])){
            message('不支持'.$type.'格式的附件');
        }
        // 判断大小
        $size = floatval($file['size']/1024); // KB
        if ($size > $this->allow['size']){
            message('附件不能大于'.$this->allow['size'].'/KB');
        }
        return true;
    }

    /**
     * 上传单个文件
     * @param $file
     * @return bool
     */
    public function one( $file){
        $this->check($file);
        // 上传操作
        if ( true === $this->isRandName){
            $name       =   get::fileHZ($file['name']);
            $newName    =   md5(get::rand(6)).'.'.$name;
        }else{
            $newName    =   $file['name'];
        }
        // 组成保存路径
        $savePath = UPLOAD_DIR.$this->savePath;
        // 移动文件
        return @move_uploaded_file($file['tmp_name'],$savePath);
    }

}

