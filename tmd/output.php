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

    /**
     * 输出错误时 主要是输出错误信息，所以第一个参数是 错误信息
     * 不传第二个参数 默认根据请求是否为ajax来判断
     * 第二个参数 如果传入字符串 会强制输出错误页面 并以第二个参数作为 点击确定的跳转链接。
     * 第二个参数 如果传入数组 会强制输出JSON数据
     */
    static function error($msg='参数错误', $ext=array())
    {
        $ajax = $ext ? is_array($ext) : input::isAjax();
        // $ext 为非空数组则 强制输出JSON，为非空其它值 强制输出页面，
        // $ext 为空 则根据请求 是否为ajax 进行输出

        $msg = is_scalar($msg) ? $msg : var_export($msg, true);

        if ($ajax) {
            $ext['ok'] = false;
            $ext['msg'] = $msg;
            exit(json_encode($ext, JSON_UNESCAPED_UNICODE));
        }

        $url = is_string($ext) ? $ext : 'javascript:history.back();';
        include './app/_config/error.phtml';
        exit;
    }

    /**
     * 输出成功时 一般只需要输出 ok=true 偶尔需要输出跳转链接，所以第一个参数为链接
     * 不传第二个参数 默认根据请求是否为ajax来判断
     * 第二个参数 如果传入字符串 会强制输出成功页面 并以第二个参数作为提示信息
     * 第二个参数 如果传入数组 会强制输出JSON数据
     */
    static function success($url='', $ext=array())
    {
        $ajax = $ext ? is_array($ext) : input::isAjax();
        // $ext 为非空数组则 强制输出JSON，为非空其它值 强制输出页面，
        // $ext 为空 则根据请求 是否为ajax 进行输出

        if ($ajax) {
            $ext['ok'] = true;
            $ext['url'] = $url;
            exit(json_encode($ext, JSON_UNESCAPED_UNICODE));
        }

        $url = empty($url) ? 'javascript:location=document.referrer;' : $url;
        $msg = empty($ext) ? '操作成功！' : $ext;
        include './app/_config/success.phtml';
        exit;
    }

    static function dump()
    {
        foreach (func_get_args() as $one) {
            echo '<pre>';
            if ($one and is_string($one)) {
                echo htmlspecialchars($one);
            } else {
                var_export($one);
            }
            echo "</pre>\n";
        }
//        exit;
    }

}