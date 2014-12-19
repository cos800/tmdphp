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
            $allowExt = arr::explode($allowExt);
        }
        return in_array($ext, $allowExt);
    }
}