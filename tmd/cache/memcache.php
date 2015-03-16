<?php

namespace tmd\cache;



class memcache extends _cache {
    public $_mamcache;
    public $host;
    public $port;

    function __construct($config=array()) {
        parent::__construct($config);

        if (defined('IS_SAE')) {
            $this->_mamcache = memcache_init() or trigger_error('Memcache Init Error', E_USER_ERROR);
        }else{
            $this->_mamcache = memcache_connect($this->host, $this->port) or trigger_error('Memcache Connect Error', E_USER_ERROR);
        }
    }

    function set($name, $data, $expire=null) {
        return memcache_set($this->_mamcache, $this->prefix.$name, $data, 0, is_null($expire) ? $this->expire : $expire);
    }

    function get($name) {
        return memcache_get($this->_mamcache, $this->prefix.$name);
    }

    function delete($name) {
        return memcache_delete($this->_mamcache, $this->prefix.$name);
    }

    function clear() {
        return memcache_flush($this->_mamcache);
    }

    function inc($name, $step=1) {
        return memcache_increment($this->_mamcache, $this->prefix.$name, $step);
    }

    function dec($name, $step=1) {
        return memcache_decrement($this->_mamcache, $this->prefix.$name, $step);
    }
}