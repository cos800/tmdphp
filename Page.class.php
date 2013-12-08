<?php

class Page {
    public $count;
    public $limit;
    public $pages;
    public $page;
    public $offset;

    public $prevText = '上一页';
    public $nextText = '下一页';
    public $disableFmt = '<li class="pg-disable"><span>{$text}</span></li>';
    public $activeFmt = '<li class="pg-active"><span>{$text}</span></li>';
    public $pageFmt = '<li><a href="{$url}">{$text}</a></li>';
    public $skipFmt = '<li><span>...</span></li>';
            
    function __construct($count, $limit=20) {
        
    }
    function prevPage($hide=FALSE) {
        if ($this->page>1) {
            $url = $this->url($this->page-1);
            return sprintf('<a href="%s">%s</a>', $url, $this->prevText);
        } elseif ($hide) {
            return '';
        } else {
            return sprintf('<span class="pg-disable">%s</span>', $this->prevText);
        }
    }
    function nextPage($hide=FALSE) {
        if ($this->page<$this->pages) {
            $url = $this->url($this->page+1);
            return sprintf('<a href="%s">%s</a>', $url, $this->nextText);
        } elseif ($hide) {
            return '';
        } else {
            return sprintf('<span class="pg-disable">%s</span>', $this->nextText);
        }
    }
    // 
    function allPage() {
        $ret = '';
        for ($p = 1; $p <= $this->pages; $p++) {
            if ($p==$this->page) {
                $ret .= sprintf('<span class="pg-active">%s</span>', $p);
            }else{
                $ret .= sprintf('<a href="%s">%s</a>', $this->url($p), $p);
            }
        }
        return $ret;
    }
    // like google baidu
    function goodPage($pages) {

    }
    // like github
    function bestPage() {
        
    }
}
