<?php

/**
 * epay异步通知
 */

header('Content-type:text/html; Charset=utf-8');
date_default_timezone_set('Asia/Shanghai');
ob_start();
require_once dirname(__FILE__) . "../../../../../../../wp-load.php";
ob_end_clean();

if (empty($_GET)) {exit;}

if ($_GET['type'] == 'alipay') {
    $yzfConfig = _cao('epay_alipay');
} elseif ($_GET['type'] == 'wxpay') {
    $yzfConfig = _cao('epay_weixin');
} else {
    exit('pay type error');
}

//签名方式 不需修改
$yzfConfig['sign_type'] = strtoupper('MD5');
//字符编码格式 目前支持 gbk 或 utf-8
$yzfConfig['input_charset'] = strtolower('utf-8');

if (empty($yzfConfig['partner']) || empty($yzfConfig['key'])) {
    exit('error');
}

require_once get_template_directory() . '/inc/class/pay.epay.class.php';
//计算得出通知验证结果
$EpayNotify    = new EpayNotify($yzfConfig);
$verify_result = $EpayNotify->verifyNotify();

if ($verify_result) {
    // 处理本地业务逻辑
    if ($_GET['trade_status'] == 'TRADE_SUCCESS') {
        //商户本地订单号
        $out_trade_no = $_GET['out_trade_no'];
        //易支付交易号
        $trade_no = $_GET['trade_no'];
        //发送支付成功回调用
        $RiClass = new RiClass;
        $RiClass->send_order_trade_notify($out_trade_no, $trade_no);
    }
    echo 'success';exit();

} else {
    //验证失败
    echo "fail";exit();
}
