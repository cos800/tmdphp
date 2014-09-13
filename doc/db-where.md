
ThinkPHP 的 `where()`
比如要生成 `birthday>'2014-09-01' and birthday<'2014-09-30'`

你不能这么写：

    array(
        'birthday' => array('gt', '2014-09-01'),
        'birthday' => array('lt', '2014-09-30'),
    );

因为数组键名重复了~
你要这么写：

    array(
        'birthday' => array(
            array('gt', '2014-09-01'),
            array('lt', '2014-09-30'),
        ),
    );

而 `TMD\DB::where` 中你只要这么写：

    array(
        'birthday[>]' => '2014-09-01',
        'birthday[<]' => '2014-09-30',,
    );

简单吗？清晰吗？
而且在做搜索的时候，开始时间和结束时间都为选填的情况，这样的数组也更容易生成：

    $whe = array();
    if ($_GET['date1']) {
        $whe['birthday[>]'] => $_GET['date1'];
    }
    if ($_GET['date2']) {
        $whe['birthday[<]'] => $_GET['date2'];
    }

-----

再比如：要查询`这两年9月份`出生的宝宝，
SQL语句应该是这样的，`(birthday>'2014-09-01' and birthday<'2014-09-30') or (birthday>'2013-09-01' and birthday<'2013-09-30')`
ThinkPHP 的 `where` 写法是：

    array(
        '_LOGIC' => 'OR',
        array(
            'birthday' => array(
                array('gt', '2014-09-01'),
                array('lt', '2014-09-30'),
            ),
        ),
        array(
            'birthday' => array(
                array('gt', '2013-09-01'),
                array('lt', '2013-09-30'),
            ),
        ),
    );

完全是没有人性的设计嘛~

`TMD\DB::where()` 的写法是：

    array(
        '#OR' => true,
        array(
            'birthday[>]' => '2014-09-01',
            'birthday[<]' => '2014-09-30',
        ),
        array(
            'birthday[>]' => '2013-09-01',
            'birthday[<]' => '2013-09-30',
        ),
    );

统计了一下，整整少了103个字符！数组维数也少掉一级。

-----

以下是我计划实现的几个功能。

-----

    [
        'id' => 123
        'name' => 'tmdphp'
    ]

这个应该被转换为

    `id`='123' AND `name`='tmdphp'

-----

    [
        'id' => 123,
        'name' => 'tmdphp',
        '#OR' => true,
    ]

这个应该被转换为

    `id`='123' OR `name`='tmdphp'

-----

    [
        'id[>]' => '12', // `id` > '12'
        'id[<]' => '123', // `id` < '123'
        'id[>=]' => '23', // `id` >= '23'
        'id[<=]' => '234', // `id` <= '234'
        'id[!=]' => '44', // `id` != '44'
        'id[<>]' => '4', // `id` <> '4'
        'id[in]' => '1,12,123,1234', // `id` IN ('1','12','123','1234')
        'id[in]' => array(1,12,123,1234), // `id` IN ('1','12','123','1234')
        'id[not in]' => array(1,12,123,1234), // `id` NOT IN ('1','12','123','1234')
        'name[like]' => '%tmdphp%'; // `name` LIKE '%tmdphp%'
        'count(*)[>]' => '123', // count(*) > 123

        // 支持原始SQL语句
        'name[is]' => array('NULL'), // `name` IS NULL
        'name[not]' => array('NULL'), // `name` NOT NULL
        'lastlogin' => array('`lastpost`'), // `lastlogin` = `lastpost` // 注意lastpost不是字符串 是字段
        'logins[>]' => array('`posts`*2'), // `logins` > `posts`*2 // 登录次数是帖子数的2倍以上

        // 更原始的SQL语句
        "find_in_set(`tags`, 'tmdphp')", // 注意这个值 没有键名
        "instr(`title`, 'TMDPHP')", // 同上，这一行只是想告诉你，SQL中 很多时候 都可以拿 instr 代替 like，

        // 支持无限级子条件
        array( // 子条件 也不要指定键名。
            'title[like]' => '%tmdphp%',
            'intro[like]' => '%tmdphp%',
            '#OR' => true,
            array(
                // ...
            ),
        ),

        // 支持指定表
        'tb.field[>=]' => 123, // `tb`.`field` >= '123'


    ]