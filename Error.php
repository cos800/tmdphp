<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-9-9
 * Time: 上午10:40
 */

namespace TMD;

// 以下级别的错误不能由用户定义的函数来处理： E_ERROR 、 E_PARSE 、 E_CORE_ERROR 、 E_CORE_WARNING 、 E_COMPILE_ERROR 、 E_COMPILE_WARNING
// E_WARNING  E_NOTICE  E_STRICT  E_RECOVERABLE_ERROR  E_DEPRECATED
// E_USER_ERROR  E_USER_WARNING  E_USER_NOTICE  E_USER_DEPRECATED
class Error {
    function setDev() {
        error_reporting(E_ALL|E_STRICT);
        ini_set('display_errors', 1);
        set_error_handler('\\TMD\\Error::handler');
    }
    function setPro() {

    }
    static function handler($errno, $errstr, $errfile, $errline, $errcontext) {
        switch ($errno) {

        }
    }

    static function isAjax() {
        if (isset($_GET['ajax'])) {
            return $_GET['ajax'];
        }
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest') {
            return true;
        }
        return false;
    }
}
//function exception_error_handler($errno, $errstr, $errfile, $errline ) {
//    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
//}
//set_error_handler("exception_error_handler");
//
///* Trigger exception */
//strpos();

//require '../php_error.php';
//\php_error\reportErrors();
strpos();

//trigger_error();