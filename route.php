<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-11-17
 * Time: 下午3:19
 */

namespace tmd;


class route {
//    public $appDir = 'app';
//    public $appNs = '\\app';
//    public $libDir = 'lib';
//
//    public $urlNsKey = 'n';
//    public $urlClassKey = 'c';
//    public $urlMethodKey = 'm';
//
//    public $defNs = 'index';
//    public $defMethod = 'index';
//
//    public $classSuffix = '.php';

    function newClass() {
        // namespace
        if (empty($_GET['n'])) {
            $namespace = 'index';
        }else{
            preg_match('~^[a-z0-9/]+$~', $_GET['n']) or trigger_error('url:n', E_USER_ERROR);
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
            preg_match('~^[a-z0-9]+$~', $_GET['c']) or trigger_error('url:c', E_USER_ERROR);
            $class = $_GET['c'];
        }
        $class .= 'Controller';

        $classPath = '\\app\\'.$namespace.'\\'.$class;
        $obj = new $classPath;


    }

    function callMethod() {

        if (empty($_GET['m'])) {
            $method = 'index';
            $param = array();
        } else {
            $param = explode('/', $_GET['m']);
            $method = array_shift($param);

            preg_match('~^[a-z0-9_]+$~i', $method) or trigger_error('url:m', E_USER_ERROR);
        }
        // todo:.....
    }

}
$a = '\\tmd\\route';
$b = new $a;
var_dump($b);