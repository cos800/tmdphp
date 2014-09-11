<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-9-9
 * Time: 下午12:26
 */

namespace TMD;


class View {
    public $dir = './app/views/';
    public $ext = '.phtml';
    public $cacheDir = './app/storage/views/';
    public $cacheTime = 1;

    public $_replace = array(
        '~\{(\$[a-z0-9_]+)\}~i' => '<?php echo $1 ?>', // {$name}

        '~\{(\$[a-z0-9_]+)\.([a-z0-9_]+)\}~i' => '<?php echo $1[\'$2\'] ?>', // {$arr.key}

        '~\{(\$[a-z0-9_]+)\.([a-z0-9_]+)\.([a-z0-9_]+)\}~i'
        => '<?php echo $1[\'$2\'][\'$3\'] ?>', // {$arr.key.key2}

        '~<\?php\s+include\s*\(\s*(.+?)\s*\)\s*;?\s*\?>~i'
        => '<?php include \$this->_include($2, __DIR__) ?>', // ＜?php include('inc/top.php'); ?＞

        '~<\?=\s*~' => '<?php echo ', // <?=
    );


}
// {$xxx} => <?php isset($xxx) and echo($xxx) ? >
// {$xxx.yyy} => <?php isset($xxx['yyy']) and echo($xxx['yyy']) ? >
// {$xxx.yyy.zzz} => <?php isset($xxx['yyy']['zzz']) and echo($xxx['yyy']['zzz']) ? >
// {$xxx.$a} => <?php isset($xxx["a_$b"]) and echo($xxx["a_$b"]) ? >
// {$xxx.$a.yyy}
// {$xxx.$a.$b}
// {$a+1}
// {$b-$b}
// {$news.addtime|timeformat}
// {$news.addtime|date='y-m-d',###}
// {$news.title|html|substring}
