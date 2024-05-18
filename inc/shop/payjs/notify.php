<?php

/**
 * 讯虎微信H5异步通知
 */

header('Content-type:text/html; Charset=utf-8');
date_default_timezone_set('Asia/Shanghai');
ob_start();
require_once dirname(__FILE__) . "../../../../../../../wp-load.php";
ob_end_clean();

$_Config = _cao('payjs_config');
if (empty($_Config['mchid']) || empty($_Config['key'])) {
    exit('error');
}

// 配置通信参数
$config = [
    'mchid' => $_Config['mchid'], // 配置商户号
    'key'   => $_Config['key'], // 配置通信密钥
];

// 初始化 Payjs
$payjs = new \Xhat\Payjs\Payjs($config);
$data = $payjs->notify();

if (is_array($data) && $data['return_code'] == 1 && !empty($data['out_trade_no'])) {
    //商户本地订单号
    $out_trade_no = $data['out_trade_no'];
    //交易号
    $trade_no = $data['transaction_id'];
    //发送支付成功回调用
    $RiClass = new RiClass;
    $RiClass->send_order_trade_notify($out_trade_no, $trade_no);
    echo 'success'; //当支付平台接收到此消息后，将不再重复回调当前接口
} else {
    echo 'error';
}
exit();
