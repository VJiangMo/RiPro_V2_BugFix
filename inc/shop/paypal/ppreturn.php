<?php
/**
 * PayPal successfull payment return
 */


header('Content-type:text/html; Charset=utf-8');
date_default_timezone_set('Asia/Shanghai');
ob_start();
require_once dirname(__FILE__) . "../../../../../../../wp-load.php";
ob_end_clean();


if (!_cao('is_paypal',false)) {
    exit('PayPal NOT');
}

require_once get_template_directory() . '/inc/shop/paypal/class/paypal.php';
require_once get_template_directory() . '/inc/shop/paypal/class/httprequest.php';
$opt = _cao('paypal');
$config = array_merge( array( 
    'username' => 'aaa',
    'password' => 'bbb',
    'signature' => 'ccc',
    'return' => '',
    'cancel' => '',
    'debug' => false,
), $opt);


$r = new PayPal($config);

$final = $r->doPayment();


// $content = var_export($final, true) . PHP_EOL . 'Details:' . var_export($r->getCheckoutDetails($final['TOKEN']), true);
// file_put_contents(__DIR__ . '/notify_result.txt', $content);


// 
if ($final['ACK'] == 'Success' && isset($final['TOKEN'])) {


    $pp_order = $r->getCheckoutDetails($final['TOKEN']);

    //商户本地订单号
    $out_trade_no = $pp_order['INVNUM'];
    //交易号
    $trade_no = $final['TRANSACTIONID'];
    //发送支付成功回调用
    $RiClass = new RiClass;
    $RiClass->send_order_trade_notify($out_trade_no, $trade_no);


    $order = $RiClass->get_pay_order_info($out_trade_no);

    // 有订单并且已经支付
    if (!empty($order) && $order->status == 1) {
        if (!is_user_logged_in() && _cao('is_ripro_v2_nologin_pay')){
            $RiClass->AddPayPostCookie(0, $order->post_id, $order->order_trade_no);
            RiSession::set('current_pay_ordernum',0);
        }
        
        if( $order->post_id>0 ){
            wp_redirect( get_the_permalink( $order->post_id ) );exit;
        }else{
            wp_redirect(get_user_page_url());exit;
        }
    }

} else {
	wp_redirect(home_url('/'));exit;
}
?>