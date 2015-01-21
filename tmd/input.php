<?php

namespace tmd;

class input
{

    static function val($key)
    {
        return isset($_POST[$key]) ? $_POST[$key] : (isset($_GET[$key]) ? $_GET[$key] : '');
    }

    static function str($key)
    {
        return htmlspecialchars(trim(static::val($key)));
    }

    static function isAjax()
    {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])==='xmlhttprequest');
    }

    static function isGet()
    {
        return ($_SERVER['REQUEST_METHOD']==='GET');
    }

    static function isPost()
    {
        return ($_SERVER['REQUEST_METHOD']==='POST');
    }
}