<?php


namespace tmd;


class valid
{
    static function getFileExt($file)
    {
        return strtolower(pathinfo($file, PATHINFO_EXTENSION));
    }

    static function getLength($val)
    {
        return mb_strlen($val, 'utf-8');
    }

    static function getWidth($val)
    {
        return mb_strwidth($val, 'utf-8');
    }
    
    static function fileExt($filename, $allowExt)
    {
        $ext = self::getFileExt($filename);
        if (!is_array($allowExt)) {
            $allowExt = arr::explode(strtolower($allowExt));
        }
        return in_array($ext, $allowExt) ? $ext : false;
    }

    static function posInt($val)
    {
        return preg_match('/^[1-9]\d*$/', $val) ? $val : false;
    }

    static function mobile($val)
    {
        return preg_match('/^1\d{10}$/', $val) ? $val : false;
    }
    
    static function email($val)
    {
//        return preg_match('/^[a-z0-9_.]{1,20}@([a-z0-9\-]{1,10}\.){1,2}[a-z]{2-7}$/', $val) ? $val : false;
        return filter_var($val, FILTER_VALIDATE_EMAIL);
    }

    static function tel($val)
    {
        return preg_match('/^(\d{3,4}-)?\d{7,8}$/', $val) ? $val : false;
    }

    static function name($val)
    {
        return preg_match('/^[\x{4e00}-\x{9fa5}]{2-4}$/u', $val) ? $val : false;
    }
    
    static function idCard($val)
    {
        return preg_match('/^\d{17}[\dXx]$/', $val) ? $val : false;
    }

    static function url($val)
    {
        return preg_match('/^https?:\/\/[^\s]{4,}$/i', $val) ? $val : false;
//        return filter_var($val, FILTER_VALIDATE_URL);
    }

    static function qq($val)
    {
        return preg_match('/^[1-9]\d{4,9}$/', $val) ? $val : false;
    }

//    static function ip($val)
//    {
//        return preg_match('/^([1-9]\d{0,2}\.){3}[1-9]\d{0,2}$/', $val) ? $val : false;
//    }

}