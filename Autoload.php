<?php
namespace TMD;

spl_autoload_register(function($class) {
    if(strpos($class, 'TMD\\')===0) {
        $file = str_replace('\\', '/', substr($class, 4)) . '.php';
        require_once __DIR__.'/'.$file;
    }
});