<?php

class tmdPage {
    public $count;
    public $limit;
    public $pageKey;
    public $urlFmt;
    
    public $pages;
    public $page;
    public $offset;

    public $prevFmt = '<li><a href="%s">%s</a></li>';
    public $prevFmt2 = '<li class="disabled"><span>%s</span></li>';
    
    public $nextFmt = '<li><a href="%s">%s</a></li>';
    public $nextFmt2 = '<li class="disabled"><span>%s</span></li>';

    public $pageFmt = '<li><a href="%s">%s</a></li>';
    public $pageFmt2 = '<li class="active"><span>%s</span></li>';

    public $firstFmt = '<li><a href="%s">%s</a></li>';
    public $lastFmt = '<li><a href="%s">%s</a></li>';
    public $skipFmt = '<li><span>...</span></li>';
                        
    function __construct($count, $limit=20, $pageKey='p', $urlFmt=false) {
        $this->count = $count;
        $this->limit = $limit;
        $this->pageKey = $pageKey;
        $this->pages = max(1, ceil($count/$limit));
        $this->page = max(1, (int)$_GET[$this->pageKey]);
        $this->offset = ($this->page-1)*$limit;
        if ($urlFmt) {
            $this->urlFmt = $urlFmt;
        }else{
            $param = $_GET;
            $param[$pageKey] = '%s';
            $this->urlFmt = U('', $param);
        }
    }

    function prevPage() {
        $url = $this->url($this->page-1);
        if ($this->page>1) {
            return sprintf($this->prevFmt, $url);
        } else {
            return $this->prevFmt2;
        }
    }
    
    function nextPage() {
        $url = $this->url($this->page+1);
        if ($this->page<$this->pages) {
            return sprintf($this->nextFmt, $url);
        } else {
            return $this->nextFmt2;
        }
    }
    // 
    function allPage($s=false, $e=false) {
        if (!$s) $s = 1;
        if (!$e) $e = $this->pages;
        
        $ret = '';
        for (; $s <= $e; $s++) {
            if ($s==$this->page) {
                $ret .= sprintf($this->pageFmt2, $s);
            } else {
                $ret .= sprintf($this->pageFmt, $this->url($s), $s);
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
            $ret = sprintf($this->firstFmt, $this->url(1), 1) . $ret;
        } elseif ($s>2) {
            $ret = sprintf($this->firstFmt, $this->url(1), 1) . $this->skipFmt . $ret;
        }
        if ($e==$this->pages-1) {
            $ret .= sprintf($this->lastFmt, $this->url($this->pages), $this->pages);
        } elseif ($e<$this->pages-1) {
            $ret .= $this->skipFmt . sprintf($this->lastFmt, $this->url($this->pages), $this->pages);
        }
        return $ret;
    }
    
    function url($page) {
        return sprintf($this->urlFmt, $page);
    }
}
