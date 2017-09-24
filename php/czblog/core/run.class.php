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
use source;
class run {
    static protected $route = [];
    static protected $apiUrl = '';
    static protected $controller = '';
    static protected $function = '';
    public static function main(){
        // 判断域名是否可访问
        if (!self::domain()){
            message($_SERVER['HTTP_HOST'].'不允许访问',0);
        }

        exit();
//        $cache = CZ::$app->cache();
//        $cache->set('test','test',100);
//        var_dump($cache->get('test'));


//        if (DEBUG === true) {
//            error_reporting(E_ALL);
//            ini_set('display_errors','On');
//        } else {
//            error_reporting(E_ALL);
//            ini_set('display_errors','Off');
//            ini_set('log_errors', 'On');
//            ini_set('error_log', CZ_BLOG_DIR. 'var/log/error/error.log');
//        }
        // API模式
        if ( true === API){
            include API_DIR.'/main.php';
//            self::apiMain();
//            self::removeMagicQuotes();
//            self::getMCF();
        }
    }

    /**
     * 判断是否非法访问
     */
    static private function legality(){
        ksort($_POST);
        $str = '';
        foreach ($_POST as $key => $value){
            if ('sign' !== $key){
                $str .= $key.'='.$value.'&';
            }
        }
        $str = $str.'key='.SERVER_TOKEN;
        $md  = strtoupper(md5($str));
        if ($md !== $_POST['sign']){
            message('Access TOKEN');
        }
    }
    /**
     * MCF main入口检查函数
     * @return bool
     */
    static private function getMCF(){
        self::$apiUrl = explode('/',trim($_SERVER['PATH_INFO'],'/'));
        if ( 0 <  strpos(self::$apiUrl[0],'.')){
            display::urlMain(self::$apiUrl);
        }
        // 检查是否合法
        self::legality();
        if ('api' === strtolower(self::$apiUrl[0])){
            $routes = config('route');
            unset(self::$apiUrl[0]);
            $api = implode('/',self::$apiUrl);
            self::$route = empty($routes[$api]) ? 'no' : $routes[$api];
            unset($routes);
            if ( 'no' === self::$route){
                message('API - 404 ',404);
            }
            return self::routeUrl();
        }
        if ( false === API){
            return self::mcUrl();
        }else{
            message('禁止访问');
        }
    }

    /**
     * api接口执行main
     */
    private static function apiMain(){
        // 显示图片
        if ( strpos($_SERVER['PATH_INFO'],'.')){


        }
        // 检查数据是否正确
        $res = paw::rsaDecrypt($_REQUEST['sid']);
        $data  = array();
        if (empty($res)){
            $res = paw::authcode(base64_decode($_REQUEST['sid']),'DECODE',KEY);
            if (empty($res)){
                message('读取数据失败');
            }
            $m_sid = explode('&',$res);
            $array = array('sid','token','time');
            for ($i = 0 ; $i < count($m_sid);$i++){
                $tmp = explode('=',$m_sid[$i]);
                if (!in_array($tmp[0],$array)  || '' === $tmp[0] || '' === $tmp[1]){
                    message('数据丢失'.$tmp[0].'='.$tmp[1]);
                }
                $data[$tmp[0]] = $tmp[1];
            }
            session::init($data['sid']);
        }else{
            session_start();
            $sessionId = session_id();
            $data['token'] = md5(get::rand(16).'/'.time());
            $data['sid']   = $sessionId;
            $data['time']  = time();
        }
        define('TOKEN',$data['token']);
        define('SID',$data['sid']);
        define('USER_ID',session::get('uid'));
        $_REQUEST['sid']    = $data['sid'];
        $_REQUEST['token']  = $data['token'];
    }
    /**
     * MC模式URL
     * @return bool
     */
    private static function mcUrl(){
        self::$controller   = self::$apiUrl[0];
        self::$function     = self::$apiUrl[1];
        return self::routeCheck();
    }

    public static function api($controller,$function){
        self::$controller   = $controller;
        self::$function     = $function;
        return self::routeCheck();
    }
    /**
     * 路由检查
     * @return bool
     */
    private static function routeCheck(){
        $class = 'APP\Controller\\'.strtolower(self::$controller);
        // 判断控制器是否存在
        if (!file_exists(APP_DIR.'/controller/'.ucfirst(self::$controller).'Controller.php')){
            message('404找不到请求的URL',404);
        }
        if (!class_exists($class)){
            message('404找不到请求的URL',404);
        }
        // 控制器方法是否存在
        if (!method_exists($class,self::$function)){
            message('404找不到请求的URL',404);
        }
        $function = self::$function;
        $obj        = new $class();
        $resData    = $obj->$function();
        return $resData;
    }
    /**
     * 判断域名是否可以访问
     * @return bool
     */
    private static function domain(){
        $SERVER_DOMAIN_NAME = str_replace('http://','',SERVER_DOMAIN_NAME);
        $SERVER_DOMAIN_NAME = str_replace('https://','',$SERVER_DOMAIN_NAME);
        if ($_SERVER['HTTP_HOST'] !== $SERVER_DOMAIN_NAME){
            if ($_SERVER['HTTP_ORIGIN'] !== CLIENT_DOMAIN_NAME || $_SERVER['HTTP_HOST'] !== SERVER_DOMAIN_NAME){
                message('HOST禁止访问');
            }
        }
        return true;
    }
    /*
     * 解密
     */
    private static function apiDecode(){
        if (!isset($_POST['sendServerDecodeStr'])){
            return false;
        }
        $decodeStr = paw::rsaDecrypt($_POST['sendServerDecodeStr']);
        $_POST['sendServerDecodeStr'] = $decodeStr;
        if (false === $decodeStr){
            return false;
        }
        $decodeArr = explode(',',$decodeStr);
        foreach ($decodeArr as $key => $value){
            $key = $decodeArr[$key];
            $value = paw::rsaDecrypt($_REQUEST[$key]);
            $_REQUEST[$key] = $value;
            if (isset($_POST[$key])){
                $_POST[$key] = $value;
            }else{
                $_GET[$key] = $value;
            }
        }

    }
}
