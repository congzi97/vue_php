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
class db {

    // 判断是否已经new
    static private $ins         = null;
    // 配置
    static private $config      = array();
    // 连接数据库成功后对象
    static private $conn        = null;
    // 当前操作数据
    static private $this_data   = array();
    // 是否开启事物
    static private $begin       = false;
    static private $affairBegin = false;
    /**
     * 获取配置、连接数据库
     * db constructor.
     * @throws CZPhpException
     */
    private function __construct(){
        // 读取配置
        self::$config = get::config('db');
        $dsn = 'mysql:host='.self::$config['host'].';dbname='.self::$config['database'].';port='.self::$config['port'].';';
        $conn = new \PDO($dsn,self::$config['username'],self::$config['password']);
        if (!$conn){
            CZBlogException::sql('连接数据库失败');
        }
        // 连接成功
        $conn->exec('USE  '.self::$config['database']);
        $conn->exec('SET NAMES '.self::$config['charset']);
        // 开启异常
        $conn->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
        $conn->setAttribute(\PDO::ATTR_AUTOCOMMIT, false);
        $conn->setAttribute(PDO::ATTR_PERSISTENT,true);
        self::$conn = $conn;
    }

    /**
     * 初始化本类
     * @return db|null
     */
    static private function init(){
        if (self::$ins instanceof self){
            return self::$ins;
        }
        self::$ins = new self();
        return self::$ins;
    }

    /**
     * 初始化数据库表
     * @param null|string $table
     * @return self
     */
    static public function table( ? string $table){
        $newSelf = self::init();
        $table = self::$config['prefix'].$table;
        if (true === self::$config['checkTable']){
            if (false === self::isTable($table)){
                message('数据库不存在表'.$table,-1);
            }
        }
        self::$this_data['table'] = self::addChar($table);
        return $newSelf;
    }

    /**
     * sql语句查询
     * @param null|string $sql
     * @param null|array $data
     * @param bool $row
     * @param bool $fetchAll
     * @return array|int|mixed
     */
    static public function query( ? string $sql, $data = null,$row = false,$fetchAll = false){
        self::init();
        $thisStr = strtoupper(substr(trim($sql),0,6));
        $rowArray = array('CREATE','UPDATE','INSERT','DELETE');
        $sql = str_replace('{prefix}',self::$config['prefix'],$sql);
        foreach ($data as $key=>$value){
            $data[$key] = self::__SafeFilter($value);
        }
        try{
            $stm = self::$conn->prepare($sql,array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
            if (self::$conn->errorCode() != '00000'){
                CZBlogException::sql("查询失败\r\nSQL语句[{$sql}]");
            }
            $stm->execute($data);
            self::record($sql);
            self::wipeData();
            if (in_array($thisStr,$rowArray) || true === $row){
                return $stm->rowCount();
            }else{
                return $fetchAll == true ? $stm->fetchAll(\PDO::FETCH_ASSOC) : $stm->fetch(\PDO::FETCH_ASSOC);
            }
        }catch (PDOException $exception){
            self::illegalRecord($sql);
            message('程序异常',-1);
        }
    }
    /**
     * 插入数据
     * @param array|null $data
     * @param bool|null $return_id
     * @return int
     */
    public function insert( ? array  $data , ? bool $return_id = false){
        if (self::$ins instanceof self){
            self::toParams($data);
            $sql = 'INSERT INTO  '.self::$this_data['table'].' ('.implode(',',array_keys(self::$this_data['fieldKey'])).') VALUES ('.implode(',',array_values(self::$this_data['fieldKey'])).')';
            $row = self::rowCount($sql);
            return true == $return_id ? self::$conn->lastInsertId() : $row;
        }else{
            message('请先执行table方法',-1);
        }

    }

    /**
     * 更新数据
     * @param array|null $data
     * @return int
     */
    public function update( ? array $data){
        if (self::$ins instanceof self){
            self::toParams($data);
            $fieldKV = '';
            foreach (self::$this_data['fieldKey'] as $key => $value){
                $fieldKV .= '' === $fieldKV ? $key.'='.$value : ','.$key.'='.$value;
            }
            $sql = 'UPDATE  '.self::$this_data['table'].' SET '.$fieldKV.' '.self::$this_data['where'].' '.self::$this_data['group'].' '.self::$this_data['order'].' '.self::$this_data['number'];
            return self::rowCount($sql);
        }else{
            message('请先执行table方法',-1);
        }
    }

    /**
     * MySQL删除语句
     * @return int
     */
    public function del(){
        if (self::$ins instanceof self){
            if ('' == self::$this_data['where'] || !isset(self::$this_data['where'])){
                message('禁止访问',-1);
            }
            $sql = 'DELETE FROM  '.self::$this_data['table'].' '.self::$this_data['where'].' '.self::$this_data['group'].' '.self::$this_data['order'].' '.self::$this_data['number'];
            return self::rowCount($sql);
        }else{
            message('请先执行table方法',-1);
        }
    }

    /**
     * 获取总数
     * @return int
     */
    public function getRow(){
        if (self::$ins instanceof self){
            $sql = 'SELECT * FROM '.self::$this_data['table'].' '.self::$this_data['where'].' '.self::$this_data['group'].' '.self::$this_data['order'].' '.self::$this_data['limit'];
            return self::rowCount($sql);
        }else{
            message('请先执行table方法',-1);
        }
    }
    /**
     * 获取一行数据
     * @return int
     */
    public function getOne(){
        if (self::$ins instanceof self){
            $sql = 'SELECT * FROM '.self::$this_data['table'].' '.self::$this_data['where'].' '.self::$this_data['group'].' '.self::$this_data['order'].' '.self::$this_data['limit'];
            return self::__fetch($sql);
        }else{
            message('请先执行table方法',-1);
        }
    }
    /**
     * 获取多行数据
     * @return int
     */
    public function getAll(){
        if (self::$ins instanceof self){
            $sql = 'SELECT * FROM '.self::$this_data['table'].' '.self::$this_data['where'].' '.self::$this_data['group'].' '.self::$this_data['order'].' '.self::$this_data['limit'];
            return self::__fetch($sql,true);
        }else{
            message('请先执行table方法',-1);
        }
    }
    /**
     * 返回数据集查询
     * @param $sql
     * @param bool $fetchAll
     * @return array|mixed
     */
    static private function __fetch($sql,$fetchAll = false){
        if (self::$begin == false){
           self::$conn->beginTransaction();
        }
        try{
            $stm = self::$conn->prepare($sql,array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
            if (self::$conn->errorCode() != '00000'){
                CZBlogException::sql("查询失败\r\nSQL语句[{$sql}]");
                throw new PDOException('语句错误');
            }

            $stm->execute(self::$this_data['fieldValue']);
            $all = $fetchAll == true ? $stm->fetchAll(\PDO::FETCH_ASSOC) : $stm->fetch(\PDO::FETCH_ASSOC);
            self::record($sql);
            if (self::$begin == false){
                self::$conn->commit();
            }

            self::wipeData();
            return $all;
        }catch (PDOException $exception){
            self::illegalRecord($sql);
            self::$conn->rollBack();
            message('操作失败',-1);
        }
    }
    /**
     * 执行返回行数语句
     * @param null|string $sql
     * @return int
     */
    static private function rowCount( ? string $sql){
        if (self::$begin == false){
            self::$conn->beginTransaction();
        }
        try{
            $stm = self::$conn->prepare($sql,array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
            if (self::$conn->errorCode() != '00000'){
                CZBlogException::sql("查询失败\r\nSQL语句[{$sql}]");
                throw new PDOException('语句错误');
            }
            $stm->execute(self::$this_data['fieldValue']);
            $count = $stm->rowCount();
            self::record($sql);
            if (self::$begin == false){
                self::$conn->commit();
            }
            self::wipeData();
            return $count;
        }catch (PDOException $exception){
            self::illegalRecord($sql);
            self::$conn->rollBack();
            message('操作失败',-1);
        }
    }

    /**
     * 分页获取数据
     * @param $page
     * @param int $pageSize
     * @return Db|null
     */
    public function limit($page,$pageSize = 10){
        $page = is_numeric($page) && $page > 0 ?  $page : 1 ;
        $pageSize = intval($pageSize);
        $xpage = intval(($page - 1) * $pageSize);
        self::$this_data['limit'] = " limit {$xpage},{$pageSize}";
        return $this;
    }
    /**
     * 更新、删除时候的个数
     * @param int|null $number
     * @return null
     */
    public function number( ? int $number){
        self::$this_data['number'] = 'limit  '.$number;
        return $this;
    }
    /**
     * 添加 order
     * @param $data
     * @return null
     */
    public function order($data,$sort = 'DESC'){
        if (is_string($data)){
            if (true === self::$config['checkField']){
                if (false === self::isField($data)){
                    message('The '.$data.' field does not exist',-1);
                }
            }
            self::$this_data['order'] = ' ORDER BY '. $data.'  '.$sort;
            return $this;
        }

        self::$this_data['order'] = ' ORDER BY '. implode(',',$data) .'  '.$sort;
        return $this;
    }
    /**
     * 添加 group
     * @param $data
     * @return null
     */
    public function group($data,$sort = 'DESC'){
        if (is_string($data)){
            if (true === self::$config['checkField']){
                if (false === self::isField(trim($data))){
                    message('The '.$data.' field does not exist',-1);
                }
            }
            self::$this_data['group'] = ' GROUP BY '. $data .'  '.$sort;
            return $this;
        }
        self::$this_data['group'] = ' GROUP BY '. implode(',',$data) .'  '.$sort;
        return $this;
    }

    /**
     * 为SQL语句添加条件
     * @param array|null $data
     * @param null $op_data
     * @return $this
     */
    public function where( ? array $data,$op_data = null){
        # key=:key ...
        # :key=value
        $value_array = array();
        $whereKV = '';
        $index = 0;
        foreach ($data as $key => $value){
            $key = trim($key);
            $value = trim($value);
            // 是否检查字段
            if (true == self::$config['checkField']){
                if (false == self::isField($key)){
                    message('The '.$data.' field does not exist',-1);
                }
            }
            if (is_array($data[$key])){
                $o = isset($data[$key][1]) ? $data[$key][1] : self::$config['defaultO'];
            }else{
                $o = self::$config['defaultO'];
            }
            if ('' === $whereKV){
                $whereKV = ' WHERE '.self::addChar($key).$o.':'.$key;
            }else{
                if (null == $op_data){
                    $whereKV .= ' '.self::$config['defaultC'].' '.self::addChar($key).$o.':'.$key;
                }else{
                    $c = isset($op_data[$index - 1]) ? $op_data[$index - 1] : self::$config['defaultC'];
                    $whereKV .= ' '.self::ar($c).' '.self::addChar($key).$o.':'.$key;
                }
            }
            $index++;
            $value_array[':'.$key] = self::__SafeFilter(is_array($data[$key]) ? $data[$key][0] : $data[$key] );
        }
        self::$this_data['where']    = $whereKV;
        self::$this_data['fieldValue']  = isset(self::$this_data['fieldValue']) ? array_merge(self::$this_data['fieldValue'],$value_array) : $value_array;
        return $this;
    }
    static private function ar($ar){
        $ar = strtolower($ar);
        if ('and' == $ar){
            return 'and';
        }else if ('or' == $ar){
            return 'or';
        }else{
            return 'and';
        }
    }
    /**
     * 转为字符串
     * @param array|null $data
     * @return array
     */
    static private function toParams( ? array $data){
        $key_array = array();
        $value_array = array();
        foreach ($data as $key => $value){
            $key = trim($key);
            $value = trim($value);
            if (true == self::$config['checkField']){
                if (false == self::isField($key)){
                    message('The '.$key.' field does not exist',-1);
                }
            }
            $key_array[self::addChar($key)]   = ':'.$key;
            $value_array[':'.$key]            = self::__SafeFilter($value);
        }
        self::$this_data['fieldKey']   = $key_array;
        self::$this_data['fieldValue'] = isset(self::$this_data['fieldValue']) ? array_merge(self::$this_data['fieldValue'],$value_array) : $value_array;

        return $key_array;
    }

    /**
     * 记录MySQL语句
     * @param $sql
     * @return bool
     */
    static private function record($sql){
        if (false === self::$config['record']){
            return true;
        }
        $str = empty(self::$this_data['fieldValue'])  ? '为空' : implode(',',self::$this_data['fieldValue']);
        $content = "\r\n[用户:token->".TOKEN.",uid->".USER_ID."\r\n查询语句:{$sql}\r\n源数据:".$str."]";
        log::main($content,date('Ymd').'_select_sql.txt','sql');
        return true;
    }
    /**
     * 记录非法MySQL语句
     * @param $sql
     * @return bool
     */
    static private function illegalRecord($sql){
        $str = empty(self::$this_data['fieldValue'])  ? '为空' : implode(',',self::$this_data['fieldValue']);
        $content = "\r\n[用户:(token->".TOKEN.",uid->".USER_ID.")执行了非法语句\r\n语句:{$sql}\r\n源数据:{$str}]";
        log::main($content,date('Ymd').'_illegal_sql.txt','sql');
        return true;
    }
    /**
     * 清除数据
     */
    static private function wipeData(){
        if (self::$begin == false){
            self::$ins        = null;
            self::$config     = array();
            self::$conn       = null;
            self::$this_data  = array();
        }else{
            self::$this_data  = array();
        }
    }
    /**
     * 判断表是否存在
     * @param $surface
     * @return bool
     */
    static private function isTable( ? string $surface){
        $sql = "SHOW TABLES FROM " . self::$config['database'];
        $prepare = self::$conn->prepare($sql);
        $prepare->execute();
        $all = $prepare->fetchAll(\PDO::FETCH_NUM);
        for ($i = 0; $i < count($all);$i++){
            if ($surface == $all[$i][0]){
                return true;
            }
        }

        return false;
    }

    /**
     * 判断字段是否存在
     * @param $table
     * @param null|string $field
     * @return bool
     */
    static private function isField(? string $field,$table = null){
        $table = self::$this_data['table'];
        $sql = 'SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME=\''.trim($table,'`').'\' AND TABLE_SCHEMA=\''.self::$config['database'].'\'';
        $prepare = self::$conn->prepare($sql);
        $prepare->execute();
        $all = $prepare->fetchAll(\PDO::FETCH_ASSOC);
        $count = count($all);
        for ($i = 0; $i < $count ; $i++){
            if ($all[$i]['COLUMN_NAME'] == $field){
                return true;
            }
        }

        return false;
    }
    /**
     * 添加``
     * @param $str
     * @return string
     */
    static private function addChar(? string $str){
        return '`'.$str.'`';
    }
    /**
     * 过滤值
     * @param $value
     * @return string
     */
    static private function __SafeFilter ($value){
        $value = trim($value);
        $ra=Array('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/','/script/','/javascript/','/vbscript/','/expression/','/applet/','/meta/','/xml/','/blink/','/link/','/style/','/embed/','/object/','/frame/','/layer/','/title/','/bgsound/','/base/','/onload/','/onunload/','/onchange/','/onsubmit/','/onreset/','/onselect/','/onblur/','/onfocus/','/onabort/','/onkeydown/','/onkeypress/','/onkeyup/','/onclick/','/ondblclick/','/onmousedown/','/onmousemove/','/onmouseout/','/onmouseover/','/onmouseup/','/onunload/');
        $value    = addslashes($value);
        $value    = preg_replace($ra,'',$value);
        return htmlspecialchars($value,ENT_QUOTES);
    }

    static public function begin(){
        $newSelf = self::init();
        if (self::$begin == false)
            self::$conn->beginTransaction();
        self::$begin = true ;
        return $newSelf;
    }
    static public function commit() {
        if (self::$begin == false){
            throw new CZBlogException('请初始化数据库');
        }
        if (self::$begin == true)
            self::$conn->commit();
        return self;
    }

    static public function rollback() {
        if (self::$begin == false){
            throw new CZBlogException('请初始化数据库');
        }
        if (self::$begin == true)
            self::$conn->rollback();
        return self;
    }
}



