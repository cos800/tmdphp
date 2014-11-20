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
        $url = app::url($method, $namespace, $append);
        header("Location: $url");
        exit;
    }
    static function error($msg='', $ext=array())
    {
        $ext['ok'] = false;
        $ext['msg'] = is_scalar($msg) ? $msg : var_export($msg, true);
        echo json_encode($ext);
        exit;
    }
    static function success($url='', $ext=array())
    {
        $ext['ok'] = true;
        $ext['url'] = $url;
        echo json_encode($ext);
        exit;
    }

    static function dump()
    {
        foreach (func_get_args() as $one) {
            echo '<pre>';
            if (is_scalar($one)) {
                echo htmlspecialchars($one);
            } else {
                var_export($one);
            }
            echo "</pre>\n";
        }
        exit;
    }
}