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
        return htmlspecialchars(trim(self::val($key)));
    }

    static function txt($key)
    {
        return htmlspecialchars(rtrim(self::val($key)));
    }

    static function int($key)
    {
        $val = (int)self::val($key);
        return max(0, $val);
    }

    static function time($key)
    {
        return strtotime(self::val($key)) ?: time();
    }

    static function all($keys)
    {
        $keys = arr::explode($keys);
        $data = array();
        foreach ($keys as $k) {
            $key = substr($k, 1);
            switch ($k[0]) {
                case '+':
                    $data[$key] = self::int($key);
                    break;
                case '~':
                    $data[$key] = self::val($key);
                    break;
                case '*':
                    $data[$key] = self::txt($key);
                    break;
                case '@':
                    $data[$key] = self::time($key);
                    break;
                default:
                    $data[$k] = self::str($k);
            }
        }
        return $data;
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