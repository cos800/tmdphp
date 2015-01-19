<?php

namespace TMD\Cache;


abstract class _cache {
    public $prefix = '';
    public $expire = 1;

    function __construct($config=array()) {
        foreach ($config as $key=>$val) {
            isset($this->$key) or trigger_error('Undefined property: '.__CLASS__.'::$'.$key, E_USER_ERROR);
            $this->$key = $val;
        }
    }

    abstract function set($name, $data, $expire=null);

    abstract function get($name);

    abstract function delete($name);

    abstract function clear();

    abstract function inc($name, $step=1);

    abstract function dec($name, $step=1);
}