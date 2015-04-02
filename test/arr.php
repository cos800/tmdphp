<?php
require '../tmd/arr.php';
require '../tmd/output.php';


$tmp = \tmd\arr::implode([])==='';
\tmd\output::dump($tmp);

$tmp3 = \tmd\arr::implode([''])==='';
\tmd\output::dump($tmp3);

$tmp3 = \tmd\arr::implode([false])==='';
\tmd\output::dump($tmp3);

$tmp3 = \tmd\arr::implode(['false'])==='false';
\tmd\output::dump($tmp3);

$tmp3 = \tmd\arr::implode([null])==='';
\tmd\output::dump($tmp3);

$tmp3 = \tmd\arr::implode(['null'])==='null';
\tmd\output::dump($tmp3);

$tmp3 = \tmd\arr::implode([0])==='';
\tmd\output::dump($tmp3);

$tmp3 = \tmd\arr::implode(['0'])==='';
\tmd\output::dump($tmp3);


$tmp2 = \tmd\arr::implode([0,1,23])==='1,23';
\tmd\output::dump($tmp2);


