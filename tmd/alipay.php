<?php

namespace tmd;

class alipay {
    private $gateway_url = 'https://mapi.alipay.com/gateway.do?';

    private $pid = '';
    private $key = '';

    function __construct($pid, $key)
    {
        $this->pid = $pid;
        $this->key = $key;
    }

    /**
     * @param $out_trade_no
     * @param $subject
     * @param $total_fee
     * @param string $return_url
     * @param string $notify_url
     * @return string
     */
    function paymentUrl($out_trade_no, $subject, $total_fee, $return_url='', $notify_url='')
    {
        $param = array(
            'service' => 'create_direct_pay_by_user',
            'partner' => $this->pid,
            '_input_charset' => 'utf-8',
            'out_trade_no' => $out_trade_no,
            'subject' => $subject,
            'payment_type' => 1,
            'total_fee' => $total_fee,
            'seller_id' => $this->pid,
            // 选填参数
            'notify_url' => $notify_url,
            'return_url' => $return_url,
        );

        $param = array_filter($param);
        ksort($param);

        $param['sign'] = md5($this->queryString($param) . $this->key);
        $param['sign_type'] = 'MD5';

        return $this->gateway_url.http_build_query($param);
    }

    function queryString($para) {
        $arg = '';
        foreach($para as $key=>$val) {
            $arg .= "$key=$val&";
        }
        //去掉最后一个&字符
        $arg = substr($arg, 0, -1);
        return $arg;
    }

    function verify($data, $adv=false)
    {
        $sign = $data['sign'];
        unset($data['sign'], $data['sign_type']);

        $data = array_filter($data);
        ksort($data);

        $mysign = md5($this->queryString($data) . $this->key);

        if ($mysign!==$sign) {
            return false;
        }

//        if ($adv) {
//
//        }

        return $data;
    }
}

