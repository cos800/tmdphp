<?php
namespace tmd;

class curl {

    static $error = '';

    static function request($url, $get=array(), $post=array()) {
        self::$error = '';

        if ($get) {
            $url .= strpos($url, '?') ? '&' : '?';
            $url .= is_string($get) ? $get : http_build_query($get);
        }
        $ch = curl_init($url);

        if ($post) {
            $post = is_string($post) ? $post : http_build_query($post);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 返回内容 而不是直接输出
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 支持 https
        curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1); // 支持 微信
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // 支持 跳转
//        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 设置 User-Agent

        $rst = curl_exec($ch);
        if ($rst===false) {
            self::$error = curl_error($ch);
            curl_close($ch);
            return false;
        }
        curl_close($ch);
        return $rst;
    }

//    static function error() {
//        return self::$error;
//    }

    static function requestJson($url, $get=array(), $post=array()) {
        $rst = self::request($url, $get, $post);
        if ($rst===false) {
            return false;
        }

        $json = json_decode($rst, true);
        if (is_null($json)) {
            self::$error = 'JSON Decode Error: '.$rst;
            return false;
        }
        return $json;
    }
}