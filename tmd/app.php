<?php

namespace tmd;


class app
{
    static function run()
    {
        date_default_timezone_set('PRC');
        static::sessionStart();
        static::loadLib('_common');

        $ctrObj = static::newClass();
        $ret = static::callMethod($ctrObj);

        if (is_null($ret)) {
            return;
        } elseif (is_scalar($ret)) { // 标量 包含了 integer 、 float 、 string 或 boolean 的变量
            echo $ret;
        } else { // is_array($ret) or is_object($ret)
            echo json_encode($ret);
        }
    }
    static function newClass()
    {
        // namespace
        if (empty($_GET['n'])) {
            $namespace = 'index';
        }else{
            preg_match('~^[a-z0-9/]+$~i', $_GET['n']) or trigger_error('url:n', E_USER_ERROR);
            $namespace = strtr($_GET['n'], '/', '\\');
        }
        // class
        if (empty($_GET['c'])) { // 默认与namespace最后一级同名
            if ($tmp = strrchr($namespace, '\\')) {
                $class = substr($tmp, 1);
            }else{
                $class = $namespace;
            }
        }else{
            preg_match('~^[a-z0-9]+$~i', $_GET['c']) or trigger_error('url:c', E_USER_ERROR);
            $class = $_GET['c'];
        }
        $class .= 'Controller';

        $classPath = '\\app\\'.$namespace.'\\'.$class;
        $ctrObj = new $classPath;

        return $ctrObj;
    }

    static function callMethod($ctrObj)
    {
        if (empty($_GET['m'])) {
            $method = 'index';
            $param = array();
        } else {
            $param = explode('/', $_GET['m']);
            $method = array_shift($param);

            preg_match('~^[a-z0-9]+$~i', $method) or trigger_error('url:m', E_USER_ERROR);
        }
        if (input::isPost()){
            $method .= '_post';
        }else{
            $method .= '_get';
        }
        $result = call_user_func_array(array($ctrObj, $method), $param);

        return $result;
    }

    static function url($method='', $namespace='', $append='')
    {
        if (empty($method)) {
            $method = $_GET['m'];
        }
        if (empty($namespace)) {
            $namespace = $_GET['n'];
            if (!empty($_GET['c'])) {
                $namespace .= '&c='.$_GET['c'];
            }
        }
        if (is_array($append)) {
            $append = http_build_query($append);
        }
        $url = "?n=$namespace&m=$method&$append";
        return $url;
    }

    static function loadLib($name)
    {
        static $libs = array();
        if (!isset($libs[$name])) {
            $libs[$name] = require "./app/_config/$name.php";
        }
        return $libs[$name];
    }
    static function sessionStart()
    {
        session_start();
    }
}

