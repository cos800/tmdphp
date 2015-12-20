<?php
namespace tmd;

class model {
    private $_table;
    private $_keyField = 'id';
    private $_data;
    private $_dataUpdate;
    private static $_instance;

    static function optionHtml($prop)
    {
        $a = static::$$prop;
        $html = '';
        foreach ($a as $k=>$v) {
            $html .= "<option value=\"$k\">$v</option>";
        }
        return $html;
    }

    function __get($name)
    {
        return $this->_data[$name];
    }

    function __set($name, $value)
    {
        $this->_data[$name] = $this->_dataUpdate[$name] = $value;
    }

    function __construct($data)
    {
        $this->_data = $data;

        static::$_instance = $this;
    }

    function getInstance() {
        return static::$_instance;
    }

    function __destruct($data)
    {

        // TODO: Implement __destruct() method.
    }

    static function create($data) {

    }

    static function read($where) {

    }

    static function save($sets, $where) {

    }

    static function delete($where=null) {
        if ($where===null) {

        }else{

        }
    }
}