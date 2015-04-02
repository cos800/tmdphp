<?php

namespace tmd;


class arr {
    // 连接成字符串
    static function implode($arr, $sep=',')
    {
        if (empty($arr)) {
            return '';
        }
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

    /**
     * 表格型二维数组 转为 键值对型一维数组
     * @param array $a 二维数组
     * @return array 一维数组
     */
    static function table2array($a)
    {
        $dat = [];
        foreach ($a as $b) {
            list($k, $v) = array_values($b);
            $dat[$k] = $v;
        }
        return $dat;
    }
}

