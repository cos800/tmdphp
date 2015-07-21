<?php
namespace tmd;

class model {
    static function optionHtml($prop)
    {
        $a = static::$$prop;
        $html = '';
        foreach ($a as $k=>$v) {
            $html .= "<option value=\"$k\">$v</option>";
        }
        return $html;
    }
}