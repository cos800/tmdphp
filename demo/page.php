<?php
require '../tmd/page.php';

use tmd\page as page;

$thisUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME']

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>page demo</title>
    <link rel="stylesheet" href="http://cdn.staticfile.org/twitter-bootstrap/3.2.0/css/bootstrap.min.css"/>
    <script src="http://cdn.staticfile.org/twitter-bootstrap/3.2.0/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container-fluid">
        <h3>更复杂一点的URL：</h3>
        <dl class="dl-horizontal">

            <dt>带其它GET参数：</dt>
            <dd><a href="<?php echo $thisUrl ?>?asd=qwe"><?php echo $thisUrl ?>?asd=qwe</a></dd>
            
            <dt>带PATHINFO：</dt>
            <dd><a href="<?php echo $thisUrl ?>/asd/qwe"><?php echo $thisUrl ?>/asd/qwe</a></dd>

            <dt>带PATHINFO和GET：</dt>
            <dd><a href="<?php echo $thisUrl ?>/asd/qwe?asd=qwe"><?php echo $thisUrl ?>/asd/qwe?asd=qwe</a></dd>
        </dl>
        <div class="row">
            <div class="col-md-4">
                <?php
                for($cnt=100; $cnt<=300; $cnt+=20) {
                    $page = new page($cnt);
                ?>
                    <ul class="pagination">
                        <?php
                        echo $page->bestPage();
                        ?>
                    </ul>
                    <br/>
                <?php } ?>
            </div>
            <div class="col-md-4">
                <?php for($cnt=100; $cnt<=300; $cnt+=20) { ?>
                    <ul class="pagination">
                        <?php
                        $page = new page($cnt);
                        echo $page->goodPage();
                        ?>
                    </ul>
                    <br/>
                <?php } ?>
            </div>
            <div class="col-md-4">
                <ul class="pager">
                    <?php
                    $page = new page(100);
                    echo $page->prevPage();
                    echo $page->nextPage();
                    ?>
                </ul>
                <br/>
                <ul class="pager">
                    <?php
                    $page = new page(100);
                    $page->prevFmt = '<li class="previous"><a href="%s">上一页</a></li>';
                    $page->prevFmt2 = '<li class="previous disabled"><a href="#">上一页</a></li>';

                    $page->nextFmt = '<li class="next"><a href="%s">下一页</a></li>';
                    $page->nextFmt2 = '<li class="next disabled"><a href="#">下一页</a></li>';
                    ?>
                    <?php echo $page->prevPage() ?>
                    <?php echo $page->nextPage() ?>
                </ul>
                <br/>
                <ul class="pagination">
                    <?php
                    $page = new page(425);
                    echo $page->allPage();
                    ?>
                </ul>
                <br/>
                共 <?php echo $page->count ?> 条记录，
                每页显示 <?php echo $page->limit ?> 条，
                共 <?php echo $page->pages ?> 页，
                当前第 <?php echo $page->page ?> 页。
            </div>
        </div>
    </div>

</body>
</html>