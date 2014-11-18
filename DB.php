<?php
/**
 * @todo: 可能直接继承PDO 效率会更高一点，到时对比一下。
 * @todo: where...
 */

namespace tmd;


class db {
    public $dsn = '';

    public $type = 'mysql';
    public $host = '127.0.0.1';
    public $port = '3306';
    public $dbname = '';
    public $charset = 'utf8';

    public $username = '';
    public $password = '';
    public $options = array(
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        \PDO::ATTR_ORACLE_NULLS => \PDO::NULL_TO_STRING,
    );

    public $PDO;

    public $prefix = '';

    public $sqls = array();

    function __construct($config=array()) {
        if (is_string($config)) {
            $this->dsn = $config;
        } else {
            foreach ($config as $key=>$val) {
                isset($this->$key) or trigger_error('Undefined property: '.__CLASS__.'::$'.$key, E_USER_ERROR);
                $this->$key = $val;
            }
            if (empty($this->dsn)) {
                $this->dsn = "{$this->type}:host={$this->host};port={$this->port};dbname={$this->dbname};charset={$this->charset}";
            }
        }
        $this->PDO = new \PDO($this->dsn, $this->username, $this->password, $this->options);

    }

    function query($sql, $args=array()) {
        if (func_num_args()>2) {
            $args = array_slice(func_get_args(), 1);
        }
        if (!$args) {
            $stmt = $this->PDO->query($sql);
        }else{
            $stmt = $this->PDO->prepare($sql);
            $stmt->execute($args);
        }
        if (is_array($this->sqls)) {
            $this->sqls[] = $stmt->queryString;
        }
        return $stmt;
    }


    function __call($name, $args) {
        $name = strtolower($name);

        $tmp = array('getall','getone','getval','getarr1','getarr2','getarr3');
        if (in_array($name, $tmp)) {

            if (in_array($name, array('getone','getval'))) {
                $args[0] .= ' LIMIT 1';
            }

            $stmt = call_user_func_array(array($this,'query'), $args);

            switch ($name) {
                case 'getall':
                    return $stmt->fetchAll();
                case 'getone':
                    return $stmt->fetch();
                case 'getval':
                    return $stmt->fetchColumn();
                case 'getarr1':
                    return $stmt->fetchAll(\PDO::FETCH_COLUMN);
                case 'getarr2':
                    return $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);
                case 'getarr3':
                    return $stmt->fetchAll(\PDO::FETCH_GROUP | \PDO::FETCH_UNIQUE);
            }
        }

//        if (method_exists($this->PDO, $name)) {
            return call_user_func_array($this->PDO, $name);
//        }

    }

    function exec($sql) {
        if (is_array($this->sqls)) {
            $this->sqls[] = $sql;
        }
        return $this->PDO->exec($sql);
    }


    function insert($table, $data, $makeSql=false) {
        $keys = implode('`,`', array_keys($data));
        $vals = implode(',', array_map(array($this->PDO, 'quote'), $data));
        $table = $this->table($table);
        $sql = "INSERT INTO $table (`$keys`) VALUES ($vals) ";
        if ($makeSql) {
            return $sql;
        }
        $this->exec($sql);
        return $this->PDO->lastInsertId();
    }

    function update($table, $data, $whe, $makeSql=false) {
        $sets = array();
        foreach ($data as $key=>$val) {
            if (is_array($val)) {
                $val = $val[0];
            } else {
                $val = $this->PDO->quote($val);
            }
            $sets[] = "`$key`=" . $val;
        }
        $sets = implode(',', $sets);
        $table = $this->table($table);
        $sql = "UPDATE $table SET $sets " . $this->where($whe);
        return $makeSql ? $sql : $this->exec($sql.'LIMIT 1');
    }

    function delete($table, $whe, $makeSql=false) {
        $table = $this->table($table);
        $sql = "DELETE FROM $table " . $this->where($whe);
        return $makeSql ? $sql : $this->exec($sql.'LIMIT 1');
    }

    function where($whe, $pre=true) {
        if(is_string($whe)) {
            $sql = $whe;
        }elseif(is_array($whe)) {
            foreach($whe as $key=>$val) {
                $key = $this->_whereKeyParse($key); // todo: !!!

            }
        }
        if ($sql and $pre) {
            $sql = 'WHERE '.$sql;
        }
        return $sql;
    }

    function _whereKeyParse($key) {
        list($key, $oper) = explode(':', $key);
        if(strpos($key, '.')!==false) {
            list($tbl, $key) = explode('.', $key);
            $tbl = "`$tbl`.";
        }else{
            $tbl = '';
        }
        return array(
            "$tbl`$key`",
            $oper?:'=',
        );
    }

    function table($table, $prefix=null) {
        if (is_null($prefix)) {
            $prefix = $this->prefix;
        }
        if (strpos($table, '.')) { // 如果有点 说明是跨库的表
            $tmp = explode('.', $table);
            $db = '`'.trim($tmp[0]).'`.';
            $table = trim(array_pop($tmp));
        }else{
            $db = '';
            $table = trim($table);
        }

        if (strpos($table, ' ')) { // 如果有空格 说明用到了别名
            $tmp = explode(' ', $table);
            $table = $tmp[0];
            $as = ' AS `'. array_pop($tmp) .'`';
        }else{
            $as = '';
        }
        return "$db`$prefix$table`$as";

        // 可以处理以下各种情况
        // table
        // table as tbl
        // table tbl
        // db2.table
        // db2.table as tbl
        // db2.table tbl
    }

    function lastSql($ret=false) {
        $i = count($this->sqls)-1;
        $sql = $this->sqls[$i];
        echo htmlspecialchars($sql);
    }
}

$config = array(
    'username' => 'root',
    'password' => 'root',
    'dbname' => 'fjly',
);
$DB = new DB($config);

//$all = $DB->getAll("select * from fy_user limit 5");
//var_export($all);

//$one = $DB->getOne("select * from fy_user where id=1");
//var_export($one);

//$cnt = $DB->getVal("select count(*) from fy_user");
//var_export($cnt);

//$all = $DB->getArr1("SELECT `user` FROM `fy_user` LIMIT 5");
//var_export($all);

//$all = $DB->getArr2("SELECT `id`, `title` FROM `fy_article` LIMIT 5");
//var_export($all);

//$all = $DB->getArr3("SELECT id,user,email,avatar,mobile from fy_user limit 5");
//var_export($all);

$DB->test('a', 'b', 'c', 'd', 'e');
$DB->test('a', array('b', 'c', 'd', 'e'));