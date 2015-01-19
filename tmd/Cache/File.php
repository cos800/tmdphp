<?php

namespace TMD\Cache;


class File extends _cache {
    public $dir = './app/storage/cache/';
    public $suffix = '.cache.php';

    function set($name, $data, $expire=null, $original=false) {
        $name = $this->_filename($name);
        if (!$original) {
            $data = array(
                'expire' => time() + (is_null($expire) ? $this->expire : $expire),
                'data' => $data,
            );
        }
        $data = '<?php return ' . var_export($data, true) . ';';
        return file_put_contents($name, $data);
    }

    function get($name, $retAll=false) {
        $name = $this->_filename($name);
        if (!file_exists($name)) {
            return false;
        }
        $data = include $name;
        if ($retAll) {
            return $data;
        }
        if (time()>$data['expire']) {
            return false;
        }
        return $data['data'];
    }

    function delete($name) {
        $name = $this->_filename($name);
        if (!file_exists($name)) {
            return true;
        }
        return unlink($name);
    }

    function clear() {
        $name = $this->_filename('*');
        $success = true;
        foreach (glob($name) as $r) {
            if (!unlink($r)) {
                $success = false;
            }
        }
        return $success;
    }

    function inc($name, $step=1) {
        $data = $this->get($name, true);
        if (!is_int($data['data']) and !is_float($data['data'])) {
            return false;
        }
        $data['data'] += $step;
        $this->set($name, $data, null, true);
        return $data['data'];
    }

    function dec($name, $step=1) {
        $this->inc($name, -$step);
    }

    function _filename($name) {
        return $this->dir.$this->prefix.$name.$this->suffix;
    }
} 