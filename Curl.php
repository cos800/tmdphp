<?php
namespace TMD;

class Curl {

    private static $error = '';

    static function request($url, $get=array(), $post=array()) {
        self::$error = '';

        if ($get) {
            $url .= (strpos($url, '?')===false) ? '?' : '&';
            $url .= is_string($get) ? $get : http_build_query($get);
        }
        $ch = curl_init($url);

        if ($post) {
            $post = is_string($post) ? $post : http_build_query($post);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 返回内容 而不是直接输出
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // 支持跳转
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 支持https

        $rst = curl_exec($ch);
        if ($rst===false) {
            self::$error = 'CURL Error: '.curl_error($ch);
        }
        curl_close($ch);
        return $rst;
    }

    static function error() {
        return self::$error;
    }

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