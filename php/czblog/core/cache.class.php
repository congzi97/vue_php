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
 *  | 时间:  2017/8/20
 *  ----------------------------------------
 *
 *  *---------------------------------------
 *  | 描述: 这家伙很懒，什么都没有留下
 *  ----------------------------------------
 */
class cache{

   private static $type          =   '';
   private static $ins           =   '';
   private static $obj           =   '';
   public static function init($type = false){
       self::$type = $type;
       if ( empty(self::$type)){
           return false;
       }
       if (self::$ins instanceof self){
           return self::$ins;
       }
       self::$ins = new self();
       return self::$ins;
   }

   private function __construct(){
       // 获取数据
       $config      =   config('cache');
       if ( false === self::$type){
           self::$type  =   $config['default'];
       }
       try{
           // 缓存扩展是否存在
           if (!in_array(self::$type,$config['cacheStart']) || false === extension_loaded(self::$type)){
               throw new CZBlogException('请开启'.self::$type.'扩展');
           }
           // 连接
           self::connect();
           // 连接成功
           return self::$obj;
       }catch (Exception $exception){
           CZBlogException::api($exception);
       }
   }
   private static function connect( ? array $config){
       switch (self::$type){
           case 'memcache':
               self::$obj = new Memcache();
               if (!self::$obj->addServer($config['memcache']['host'],$config['memcache']['port'])){
                   throw new CZBlogException('连接memcached失败');
               }
               break;
           case 'memcached':
               self::$obj = new Memcached();
               if (!self::$obj->addServers($config['memcached']['servers'])){
                   throw new CZBlogException('连接memcached失败');
               }
               break;
           case 'redis':
               self::$obj   = new Redis();
               if (!self::$obj->connect($config['redis']['host'],$config['redis']['port'])){
                   throw new CZBlogException('连接Redis失败');
               }
               break;
       }
   }

    /**
     * 设置/添加缓存
     * @param null|string $key
     * @param null|string $value
     * @param int|null $expiredTime
     * @return bool
     */
   public function set( ? string $key , ? string $value , ? int $expiredTime = 1800){
       if ( empty($key) || empty($value) || !is_int($expiredTime)){
           return false;
       }
       if ( '' === self::$ins || '' === self::$obj){
           return false;
       }
       // 判断是否存在
       if ( true === self::get($key)){
           return self::replace($key,$value,$expiredTime);
       }

       return self::$obj->set($key,$value,$expiredTime);
   }

    /**
     * 获取缓存
     * @param null|string $key
     * @return array|bool|mixed|string
     */
   public function get( ? string $key){
       if ( '' === self::$ins || '' === self::$obj){
           return false;
       }
       return self::$obj->get($key);
   }

    /**
     * 删除数据
     * @param $key
     * @return bool
     */
    public function delete( ? string $key){
        if ( '' === self::$ins || '' === self::$obj){
            return false;
        }
        return self::$obj->delete($key);
    }

    /**
     * 添加数据但会覆盖原数据
     * @param $key
     * @param $value
     * @param int $expiredTime
     * @return bool
     */
    public function replace( ? string $key, ? string $value, ? int $expiredTime = 1800){
        if ( '' === self::$ins || '' === self::$obj){
            return false;
        }
        return self::$obj->replace($key,$value,$expiredTime);
    }

    /**
     * 清除所有缓存
     * @return bool
     */
    public function flush(){
        if ( '' === self::$ins || '' === self::$obj){
            return false;
        }
        return self::$obj->flush();
    }

    /**
     * 缓存加法 比如 key的value为1 increment(key,5)后key的value为1+5 = 6
     * @param $key
     * @param $number
     * @return int
     */
    public function increment( ? string $key, ? int $number){
        if ( '' === self::$ins || '' === self::$obj){
            return false;
        }
        return self::$obj->increment($key,$number);
    }

    /**
     * 缓存减法 比如 key的value为1 decrement(key,5)后key的value为1-5 = -4
     * @param $key
     * @param $number
     * @return int
     */
    public function decrement( ? string $key, ? int $number){
        if ( '' === self::$ins || '' === self::$obj){
            return false;
        }
        return self::$obj->decrement($key,$number);
    }

    /**
     * 设置多条数据
     * @param array|null $key
     * @param int $exp
     * @return bool
     */
    public function setMulti( ? array  $key, ? int $exp = 60){
        if ( '' === self::$ins || '' === self::$obj){
            return false;
        }
        return self::$obj->setMulti($key,$exp);
    }
    /**
     * 获取多条数据
     * @param $key
     * @return mixed
     */
    public function getMulti( ? array $key){
        if ( '' === self::$ins || '' === self::$obj){
            return false;
        }
        return self::$obj->getMulti($key);
    }


}

