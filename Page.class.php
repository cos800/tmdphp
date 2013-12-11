<?php

class tmdPage {
    public $count;
    public $limit;
    public $pages;
    public $page;
    public $offset;

    public $prevText = '上一页';
    public $nextText = '下一页';

    public $skipHtml = '<li><span>...</span></li>';
    
    function __construct($count, $limit=20) {
        
    }
    function prevPage() {
        if ($this->page>1) {
            $url = $this->url($this->page-1);
            return sprintf('<li><a href="%s">%s</a></li>', $url, $this->prevText);
        } else {
            return sprintf('<li class="disabled"><span>%s</span></li>', $this->prevText);
        }
    }
    function nextPage() {
        if ($this->page<$this->pages) {
            $url = $this->url($this->page+1);
            return sprintf('<li><a href="%s">%s</a></li>', $url, $this->nextText);
        } else {
            return sprintf('<li class="disabled"><span>%s</span></li>', $this->nextText);
        }
    }
    // 
    function allPage($s=false, $e=false) {
        if (!$s) $s = 1;
        if (!$e) $e = $this->pages;
        
        $ret = '';
        for (; $s <= $e; $s++) {
            if ($s==$this->page) {
                $ret .= sprintf('<li class="active"><span>%s</span></li>', $s);
            }else{
                $ret .= sprintf('<li><a href="%s">%s</a></li>', $this->url($s), $s);
            }
        }
        return $ret;
    }
    // like google 
    function goodPage($n=4) {
        if ($this->pages<=($n*2+1)) { // 总页数小于等于9
            return $this->allPage(); // 显示所有分页
        }
        $s = max(1, $this->page-$n); // 开始页数 = 当前页数 - $n  或是 1
        $e = $s+$n*2; // 计算结束页数
        if ($e>$this->pages) { // 如果结束页大于总页数
            $e = $this->pages; // 结束页 = 总页数
            $s = $e-$n*2; // 开始页 = 结束页 - $n*2
        }
        return $this->allPage($s, $e);
    }
    // like github
    function bestPage($n=3) {
        if ($this->pages<=($n*2+1)) { // 总页数小于等于9
            return $this->allPage(); // 显示所有分页
        }
        $s = max(1, $this->page-$n); // 开始页数 = 当前页数 - $n  或是 1
        $e = $s+$n*2; // 计算结束页数
        if ($e>$this->pages) { // 如果结束页大于总页数
            $e = $this->pages; // 结束页 = 总页数
            $s = $e-$n*2; // 开始页 = 结束页 - $n*2
        }
        $ret = $this->allPage($s, $e);
        if ($s==2) {
            $ret = sprintf('<li><a href="%s">%s</a></li>', $this->url(1), 1).$ret;
        }elseif ($s>2) {
            $ret = sprintf('<li><a href="%s">%s</a></li><li><span>...</span></li>', $this->url(1), 1).$ret;
        }
        if ($e==$this->pages-1) {
            $ret .= sprintf('<li><a href="%s">%s</a></li>', $this->url($this->pages), $this->pages);
        }elseif ($e<$this->pages-1) {
            $ret .= sprintf('<li><a href="%s">%s</a></li>', $this->url($this->pages), $this->pages);
        }
        return $ret;
    }
    function url($page) {
        
    }
}
