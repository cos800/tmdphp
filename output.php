<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-11-18
 * Time: 下午3:54
 */

namespace tmd;


class output {
    static function download()
    {
        
    }
    static function redirect($method='', $namespace='', $append='')
    {
        $url = route::url($method, $namespace, $append);
        header("Location: $url");
        exit;
    }
}