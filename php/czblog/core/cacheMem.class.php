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
class cacheMem{

    static public $obj = '';
    static private $memcachedConfig = [];
    static private $ins = '';
    static public function init(){
        if (self::$ins instanceof self){
            return self::$ins;
        }
        self::$ins = new self();
        return self::$ins;
    }
    static private function main(){
        if (!class_exists('memcached')){
            return [-1,'请开启memcached扩展'];
        }
        // 获取配置
        $config = config('cache');
        if (!isset($config['memcached'])){
            return [-1,'请配置memcached'];
        }
        self::$memcachedConfig = $config['memcached'];
        unset($config);
        // 连接 memcached
        try{
            $m = new Memcached();
            if (!$m->addServers(self::$memcachedConfig['servers'])){
                throw new MemcachedException('连接memcached失败');
            }
            self::$obj = $m;
            return self::$obj;
        }catch (MemcachedException $exception){
            CZBlogException::core($exception);
            return [-1,'连接memcached失败'];
        }
    }

    /**
     * 设置数据
     * @param $key
     * @param $value
     * @param int $exp
     * @return bool
     */
    public static function add($key,$value,$exp = 1800){
        $obj = empty(self::$obj) ? self::main() : self::$obj;
        return $obj->add($key,$value,$exp);
    }
    /**
     * 获取数据
     * @param $key
     * @return mixed
     */
    public static function get($key){
        $obj    = empty(self::$obj) ? self::main() : self::$obj;
        return $obj->get($key);
    }

    /**
     * 删除数据
     * @param $key
     * @return bool
     */
    public static function delete($key){
        $obj    = empty(self::$obj) ? self::main() : self::$obj;
        return $obj->delete($key);
    }

    /**
     * 添加数据但会覆盖原数据
     * @param $key
     * @param $value
     * @param int $exp
     * @return bool
     */
    public static function replace($key,$value,$exp = 1800){
        $obj    = empty(self::$obj) ? self::main() : self::$obj;
        return $obj->replace($key,$value,$exp = 1800);
    }

    /**
     * 清除所有缓存
     * @return bool
     */
    public static function flush(){
        $obj    = empty(self::$obj) ? self::main() : self::$obj;
        return $obj->flush();
    }

    /**
     * 缓存加法 比如 key的value为1 increment(key,5)后key的value为1+5 = 6
     * @param $key
     * @param $number
     * @return int
     */
    public static function increment($key,$number){
        $obj    = empty(self::$obj) ? self::main() : self::$obj;
        return $obj->increment($key,$number);
    }

    /**
     * 缓存减法 比如 key的value为1 decrement(key,5)后key的value为1-5 = -4
     * @param $key
     * @param $number
     * @return int
     */
    public static function decrement($key,$number){
        $obj    = empty(self::$obj) ? self::main() : self::$obj;
        return $obj->decrement($key,$number);
    }

    /**
     * 设置多条数据
     * @param array|null $key
     * @param int $exp
     * @return bool
     */
    public static function setMulti( ? array  $key,$exp = 60){
        $obj = empty(self::$obj) ? self::main() : self::$obj;
        return $obj->setMulti($key,$exp);
    }
    /**
     * 获取多条数据
     * @param $key
     * @return mixed
     */
    public static function getMulti( ? array $key){
        $obj    = empty(self::$obj) ? self::main() : self::$obj;
        return $obj->getMulti($key);
    }


}

