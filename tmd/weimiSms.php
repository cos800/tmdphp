<?php

namespace tmd;


class weimiSms {
    private $uid = '';
    private $pas = '';

    function __construct($uid, $pas)
    {
        $this->uid = $uid;
        $this->pas = $pas;
    }

    function send($mob, $cid, $par)
    {
        $post = [
            'uid' => $this->uid,
            'pas' => $this->pas,
            'mob' => $mob,
            'cid' => $cid,
            'type' => 'json',
        ];

        $i = 1;
        foreach ($par as $p) {
            $post['p'.$i++] = $p;
        }

        return curl::requestJson("http://api.weimi.cc/2/sms/send.html", flase, $post);
    }
}