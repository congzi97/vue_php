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

/**
 * 接口返回数据
 * @param $msg
 * @param int $code
 * @param array $data
 */
function message($msg,$code = -1,$data = []){
    $eTime=microtime(true);
    $total=$eTime-START_TIME;
    $array['code'] = $code;
    $array['msg']  = $msg;
    $array['data'] = $data;
    $array['timeConsuming']  = $total;
    if (true === DEBUG){
        print_r($array);
        exit();
    }
    $json = json_encode($array);
    unset($array);
    unset($_REQUEST);
    unset($_GET);
    unset($_POST);
    echo $json;
    exit();
}

/**
 * 获取配置
 * @param $name
 * @return array|mixed
 */
function config($name){
    return get::config($name);
}

/**
 * 获取一行数据
 * @param $table
 * @param $where
 * @param array $op_where
 * @return mixed
 */
function pdo_fetch($table,$where,$op_where = []){
    $conn = db::table($table);
    $all = $conn
        ->where($where,$op_where)
        ->getOne();
    return $all;
}
/**
 * 获取多行数据
 * @param $table
 * @param $where
 * @param array $op_where
 * @return mixed
 */
function pdo_fetchAll($table,$where,$op_where = []){
    $conn = db::table($table);
    $all = $conn
        ->where($where,$op_where)
        ->getAll();
    return $all;
}

/**
 * 获取行数
 * @param $table
 * @param $where
 * @param array $op_where
 * @return mixed
 */
function pdo_row($table,$where,$op_where = []){
    $conn = db::table($table);
    $all = $conn
        ->where($where,$op_where)
        ->getRow();
    return $all;
}

/**
 * 查询SQL语句
 * @param $sql
 * @param array $data
 * @param bool $fetch
 * @param bool $all
 * @return array|int|mixed
 */
function pdo_sql($sql,$data = [],$fetch = true,$all = false){
    if (true === $fetch){
        $res = db::query( $sql, $data,$row = false,$all);
    }else{
        $res = db::query( $sql, $data,$row = true);
    }
    return $res;
}

function is_md5($password) {
    return preg_match("/^[a-z0-9]{32}$/", $password);
}
function getIP()
{
    global $ip;
    if (getenv("HTTP_CLIENT_IP"))
        $ip = getenv("HTTP_CLIENT_IP");
    else if(getenv("HTTP_X_FORWARDED_FOR"))
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    else if(getenv("REMOTE_ADDR"))
        $ip = getenv("REMOTE_ADDR");
    else $ip = "Unknow";
    return $ip;
}

