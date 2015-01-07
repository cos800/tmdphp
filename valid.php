<?php


namespace tmd;


class valid
{
    static function getExtName($file)
    {
        return strtolower(pathinfo($file, PATHINFO_EXTENSION));
    }
    
    static function fileExt($filename, $allowExt)
    {
        $ext = self::getExtName($filename);
        if (!is_array($allowExt)) {
            $allowExt = arr::explode(strtolower($allowExt));
        }
        return in_array($ext, $allowExt) ? $ext : false;
    }

    static function posInt($val)
    {
        return preg_match('/^[1-9]\d*$/', $val) ? $val : false;
    }


}