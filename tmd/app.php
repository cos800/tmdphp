<?php

namespace tmd;


class app
{

    static $method = 'index';


    static function run()
    {
        date_default_timezone_set('PRC');
        self::sessionStart();

        self::runApp();
    }

    private static function getClass()
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

        $classPath = '\\app\\'.$namespace.'\\'.$class.'Controller';
        return $classPath;
    }

    private static function getMethod() {
        if (empty($_GET['m'])) {
            $method = 'index';
            $param = array();
        } else {
            $param = explode('/', $_GET['m']);

            $method = self::$method = array_shift($param);
            preg_match('~^[a-z0-9]+$~i', $method) or trigger_error('url:m', E_USER_ERROR);
        }

        if (input::isPost()){
            $method .= '_post';
        }else{
            $method .= '_get';
        }

        return array($method, $param);
    }

    private static function runApp()
    {
        $ctrName = self::getClass();
        list($method, $param) = self::getMethod();

        $ctrObj = new $ctrName;
        $result = call_user_func_array(array($ctrObj, $method), $param);

        if (is_null($result)) {
            return;
        } elseif (is_scalar($result)) { // 标量 包含了 integer 、 float 、 string 或 boolean 的变量
            echo $result;
        } else { // is_array($ret) or is_object($ret)
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * @param $method 为NULL则返回当前method，为空字符串则不设置（默认index）
     * @param null $namespace
     * @param null $append
     * @return string 返回URL
     */
    static function url($method=NULL, $namespace=NULL, $append=NULL)
    {
        $url = '?';

        if (is_null($namespace)) { // NULL 默认 当前
            if (!empty($_GET['n'])) {
                $url .= "n=$_GET[n]&";
            }
            if (!empty($_GET['c'])) {
                $url .= "c=$_GET[c]&";
            }
        } elseif (!empty($namespace)) {
            $url .= "n=$namespace&";
        }

        if (is_null($method)) { // NULL 默认 当前
            if (!empty($_GET['m'])) {
                $url .= "m=$_GET[m]&";
            }
        } elseif (!empty($method)) {
            $url .= "m=$method&";
        }

        if (!empty($append)) {
            if (is_array($append)) {
                $append = http_build_query($append);
            }
            $url .= $append.'&';
        }

        return substr($url, 0, -1);
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

