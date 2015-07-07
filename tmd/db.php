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

    public $user = '';
    public $pwd = '';
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
        $this->PDO = new \PDO($this->dsn, $this->user, $this->pwd, $this->options);

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
//            var_export($args);die;
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
            return call_user_func_array(array($this->PDO, $name), $args);
//        }

    }

    function exec($sql) {
        if (is_array($this->sqls)) {
            $this->sqls[] = $sql;
        }
        return $this->PDO->exec($sql);
    }

    function selectOne($table, $fields, $whe)
    {
        if (is_array($fields)) {
            $fields = '`' . implode('`, `', $fields) . '`';
        }
        $sql = "SELECT $fields FROM " . $this->table($table) . $this->where($whe);
        return $this->getOne($sql);
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
            if (is_array($val)) { // 执行原生SQL
                $val = $val[0]; // 'views' => ['views+1']
            } else {
                $val = $this->PDO->quote($val);
            }
            $sets[] = "`$key`=" . $val;
        }
        $sets = implode(',', $sets);
        $table = $this->table($table);

        $sql = "UPDATE $table SET $sets" . $this->where($whe);
        return $makeSql ? $sql : $this->exec($sql.' LIMIT 1');
    }

    function delete($table, $whe, $makeSql=false) {
        $sql = "DELETE FROM " . $this->table($table) . $this->where($whe);
        return $makeSql ? $sql : $this->exec($sql.' LIMIT 1');
    }

    function where($whe, $pre=' WHERE ') {
        if (empty($whe)) {
            return '';
        } elseif (is_string($whe)) {
            $sql = $whe;
<<<<<<< HEAD
        }elseif(is_array($whe)) {
            if (empty($whe['__OR__'])) { // 判断条件之间的关系
=======
        } elseif (is_array($whe)) {
            if (isset($whe['||'])) { // 判断条件之间的关系
                $logic = ' OR ';
                unset($whe['||']);
            }else{
>>>>>>> 140d3aa25f35a928aa0e99bf9b641f7bfe800491
                $logic = ' AND ';
            }else{
                $logic = ' OR ';
                unset($whe['__OR__']);
            }

            $sql = array();
            foreach($whe as $key=>$val) {
                if (is_int($key)) { // 子条件
                    $sql[] = '(' . $this->where($val, '') . ')';
                    continue;
                }

                if (strpos($key, ':')) {// 其他比较
                    $sql[] = $this->_whereParse($key, $val);
                    continue;
                }

                 // 默认为 = 比较
                $val = $this->PDO->quote($val);
                $sql[] = "`$key`=$val";
            }
            $sql = implode($logic, $sql);
        }

        return $pre.$sql;
    }

    function _whereParse($key, $val) {
        list($key, $oper) = explode(':', $key);
        $oper = strtoupper(trim($oper));
        if (is_array($val)) {
            if (empty($val)) return '0';
            $val = array_map(array($this->PDO, 'quote'), $val);
            $val = '('.implode(',',$val).')';
        }else{
            $val = $this->PDO->quote($val);
        }
        return "`$key` $oper $val";
    }

    function table($table, $as='') {
//        if (is_null($prefix)) { // 使用默认表前缀
//            $prefix = $this->prefix;
//        }
        if (strpos($table, '.')) { // 如果有点 说明是跨库的表
            $tmp = explode('.', $table);
            $db = '`'.trim($tmp[0]).'`.';
            $table = trim(array_pop($tmp));
        } else {
            $db = '';
            $table = $this->prefix . trim($table);
        }

//        if (strpos($table, ' ')) { // 如果有空格 说明用到了别名
//            $tmp = explode(' ', $table);
//            $table = $tmp[0];
//            $as = ' AS `'. array_pop($tmp) .'`';
//        } else {
//            $as = '';
//        }

        if ($as) {
            $as = " AS `$as`";
        }

        return "$db`$table`$as";
    }

    function lastSql($ret=false) {
        $i = count($this->sqls)-1;
        $sql = $this->sqls[$i];
        if ($ret) {
            return $sql;
        }else{
            echo htmlspecialchars($sql);
        }
    }

}

