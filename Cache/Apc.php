<?php

namespace TMD\Cache;


class Apc extends _cache {
    function set($name, $data, $expire=null) {
        return apc_store($this->prefix.$name, $data, is_null($expire) ? $this->expire : $expire);
    }

    function get($name) {
        return apc_fetch($this->prefix.$name);
    }

    function delete($name) {
        return apc_delete($this->prefix.$name);
    }

    function clear() {
        return apc_clear_cache();
    }

    function inc($name, $step=1) {
        return apc_inc($this->prefix.$name, $step);
    }

    function dec($name, $step=1) {
        return apc_dec($this->prefix.$name, $step);
    }
}