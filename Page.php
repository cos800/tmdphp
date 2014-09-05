<?php
namespace TMD;

class Page {
    public $count; // 总记录数
    public $limit; // 每页显示记录数
    public $pageKey; // URL中页数参数名，比如：page
    public $urlFmt; // 自定义分页URL
    
    public $pages; // 总页数
    public $page; // 当前页数
    public $offset; // offset 用于mysql查询 偏移量

    public $prevFmt = '<li><a href="%s">上一页</a></li>';
    public $prevFmt2 = '<li class="disabled"><span>上一页</span></li>';
    
    public $nextFmt = '<li><a href="%s">下一页</a></li>';
    public $nextFmt2 = '<li class="disabled"><span>下一页</span></li>';

    public $pageFmt = '<li><a href="%s">%s</a></li>';
    public $pageFmt2 = '<li class="active"><span>%s</span></li>';

    public $firstFmt = '<li><a href="%s">%s</a></li>';
    public $lastFmt = '<li><a href="%s">%s</a></li>';
    public $skipFmt = '<li><span>...</span></li>';
                        
    function __construct($count, $limit=20, $urlFmt=false, $pageKey='page') {
        $this->count = $count;
        $this->limit = $limit;
        $this->pageKey = $pageKey;
        $this->pages = max(1, ceil($count/$limit));
        $this->page = max(1, (int)@$_GET[$pageKey]);
        $this->offset = ($this->page-1)*$limit;
        if ($urlFmt) {
            $this->urlFmt = $urlFmt;
        } else {
            $param = $_GET;
            $param[$pageKey] = '__PAGE__';
            if (defined('THINK_PATH')) { // 如果是ThinkPHP框架
                $this->urlFmt = U('', $param);
            }else{
                $this->urlFmt = $_SERVER['PHP_SELF'].'?'.http_build_query($param);
            }
        }
    }
    //  上一页
    function prevPage() {
        if ($this->page > 1) {
            $url = $this->url($this->page-1);
            return sprintf($this->prevFmt, $url);
        } else {
            return $this->prevFmt2;
        }
    }
    // 下一页
    function nextPage() {
        if ($this->page < $this->pages) {
            $url = $this->url($this->page+1);
            return sprintf($this->nextFmt, $url);
        } else {
            return $this->nextFmt2;
        }
    }

    /**
     *
     * @param int $s 开始页数
     * @param int $e 结束页数
     * @return string 分页html
     */
    function allPage($s=null, $e=null) {
        if (empty($s)) $s = 1;
        if (empty($e)) $e = $this->pages;
        
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

    /**
     * 像这样显示分页
     * 6 7 8 9 [10] 11 12 13 14
     * @param int $n
     * @return string 分页HTML
     */
    function goodPage($n=4) {
        if ($this->pages <= ($n*2+1) ) { // 总页数小于等于9
            return $this->allPage(); // 显示所有分页
        }
        $s = max(1, $this->page-$n); // 开始页数 = 当前页数 - $n  或是 1
        $e = $s+$n*2; // 计算结束页数
        if ($e > $this->pages) { // 如果结束页大于总页数
            $e = $this->pages; // 结束页 = 总页数
            $s = $e-$n*2; // 开始页 = 结束页 - $n*2
        }
        return $this->allPage($s, $e);
    }

    /**
     * 像这样显示分页
     * 1 ... 7 8 9 [10] 11 12 13 ... 99
     * @param int $n
     * @return string 分页HTML
     */
    function bestPage($n=3) {
        if ($this->pages <= (($n+2)*2+1) ) {
            return $this->allPage(); // 显示所有分页
        }
        $s = max(1, $this->page-$n); // 开始页数 = 当前页数 - $n  或是 1
        $e = $s+$n*2; // 计算结束页数
        if ($e > $this->pages) { // 如果结束页大于总页数
            $e = $this->pages; // 结束页 = 总页数
            $s = $e-$n*2; // 开始页 = 结束页 - $n*2
        }
        $ret = $this->allPage($s, $e);

        if ($s==2) { // 从第二页开始的
            $ret = sprintf($this->firstFmt, $this->url(1), 1) . $ret; // 加上第一页链接
        } elseif ($s>2) {
            $ret = sprintf($this->firstFmt, $this->url(1), 1) . $this->skipFmt . $ret; // 加上第一页链接和省略号
        }
        if ($e == $this->pages-1) { // 结束在倒数第二页
            $ret .= sprintf($this->lastFmt, $this->url($this->pages), $this->pages); // 直接加上最后一页链接
        } elseif ($e < $this->pages-1) { // 结束在倒数第二页之前
            $ret .= $this->skipFmt . sprintf($this->lastFmt, $this->url($this->pages), $this->pages); // 加上省略号和最后一页链接
        }
        return $ret;
    }
    
    function url($page) {
        return str_replace('__PAGE__', $page, $this->urlFmt);
//        return sprintf($this->urlFmt, $page);
    }
}
