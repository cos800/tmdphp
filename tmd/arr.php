<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-11-21
 * Time: 下午5:05
 */

namespace tmd;


class arr {
    // 连接成字符串
    static function implode($arr, $sep=',')
    {
        return implode($sep, array_filter(array_map('trim', $arr)));
    }

    // 分割为数组
    static function explode($str, $sep=',')
    {
        return array_filter(array_map('trim', explode($sep, $str)));
    }

    // 取其中一列组成的数组
    static function column(&$arr, $key, $unique=true)
    {
        if (function_exists('array_column')) {
            $list = array_column($arr, $key);
        }else{
            $list = array();
            foreach ($arr as $r) {
                $list[] = $r[$key];
            }
        }
        return $unique ? array_unique($list) : $list;
    }
}