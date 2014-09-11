<?php
//

namespace TMD;


class DB {
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
    );

    public $_pdo;

    public $prefix = 'fy_';

    function __construct($config=array()) {
        if (is_string($config)) {
            $this->dsn = $config;
        }else{
            foreach ($config as $key=>$val) {
                isset($this->$key) or trigger_error('Undefined property: '.__CLASS__.'::$'.$key, E_USER_ERROR);
                $this->$key = $val;
            }
            if (empty($this->dsn)) {
                $this->dsn = "{$this->type}:host={$this->host};port={$this->port};dbname={$this->dbname};charset={$this->charset}";
            }
        }
        $this->_pdo = new \PDO($this->dsn, $this->username, $this->password, $this->options);
//        $this->_pdo->setAttribute()
    }
    function getAll($sql, $arr=array()) {
//        if ($arr) {
            $sth = $this->_pdo->prepare($sql);
            $sth->execute($arr);


//        }

    }
    function getOne($sql) {

    }
    function getVal($sql) {

    }
    function getArr($sql) {

    }
    function insert($sql) {

    }
    function update($sql) {

    }
    function delete($sql) {

    }
    function makeWhere($whe) {

    }
    function table($table) {
        return $this->prefix.$table;
    }
    function test() {
        $sql = "select * from `fy_articlexx` where `status`=1 order by `sortime` desc limit 5";
        $all = $this->_pdo->query($sql);

        var_dump($this->_pdo->errorInfo());
        echo '<pre>';
        var_dump($all);
//        foreach ($all as $r) {
//            var_dump($r);
//        }

    }
}
$config = array(
    'username' => 'root',
    'password' => 'root',
    'dbname' => 'fjly',
);
$DB = new DB($config);

$DB->test();
