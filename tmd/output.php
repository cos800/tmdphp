<?php


namespace tmd;


class output {

    static function download()
    {
        
    }

    static function redirect($url, $time=0, $msg='')
    {
        if (headers_sent()) {
            echo "<meta http-equiv=\"refresh\" content=\"$time; url=$url\" />";
        } else {
            header("refresh:$time;url=$url");
        }
        exit($msg);
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
//        exit;
    }

}