<?php

function pager($count, $limit=20) {
    require_once './tmdPage.class.php';
    $Page = new tmdPage($count, $limit);
    $Page->prevFmt = '<li class="previous"><a href="%s">上一页</a></li>';
    $Page->prevFmt2 = '<li class="previous disabled"><a href="#">上一页</a></li>';
    $Page->nextFmt = '<li class="next"><a href="%s">下一页</a></li>';
    $Page->nextFmt2 = '<li class="next disabled"><a href="#">下一页</a></li>';
    $limit = $Page->offset.','.$Page->limit;
    $html = '<ul class="pager">'.$Page->prevPage().$Page->nextPage().'</ul>';
    return array($limit, $html, $Page);
}
function page($count, $limit=20) {
    require_once './tmdPage.class.php';
    $Page = new tmdPage($count, $limit);
}