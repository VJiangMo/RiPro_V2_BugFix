<?php
// 要求noindex 公众号服务配置
ob_start();
ob_end_clean();
if (!_cao('is_sns_weixin')) {
    ripro_wp_die('非法访问','网站未开启微信公众号登录');die;
}


$opt = _cao('sns_weixin');
// 判断微信模式
if ($opt['sns_weixin_mod'] != 'mp') {
    ripro_wp_die('非法访问','网站未开启微信公众号登录');die;
}
$RiProSNS = new RiProSNS();

//是否微信服务器参数验证 valid

if ( !$RiProSNS->checkSignature() ) {
    die('参数验证错误');
}

if (isset($_GET["echostr"])) {
    die($_GET['echostr']);
}

//处理异步数据 推送消息 MsgType event 接受XML数据 推送事件
$inputData = file_get_contents('php://input'); //监听是否有数据传入
if (empty($inputData)) {
    die('没有收到到数据');
}

//获取微信服务器发来的信息
$getRevData = $RiProSNS->getRevData();
$getRevType = $getRevData['MsgType']; //获取接收消息的类型
$openid = $getRevFrom = $getRevData['FromUserName']; //获取消息发送者 openid
$getRevContent = $getRevData['Content']; //内容正文

$getEvent = (!empty($getRevData['Event'])) ? strtolower($getRevData['Event']) : '';

// # 调试模式...
// $content = var_export($getRevData, true);
// file_put_contents(__DIR__ . '/input_result.txt', $content);

$snsInfo=array();

switch($getRevType) {
    case 'text':
        if (true) {
            // 关键词搜索文章
            $array_posts = array();
            $data = new WP_Query(array('s' => $getRevContent,'posts_per_page' => 5));
            $posts = '';
            while ( $data->have_posts() ) : $data->the_post();
                $posts .= '\n'.get_the_title().'\n';
                $posts .= get_permalink().'\n';
            endwhile;
            if ($posts) {
                $resmsg = '为您搜索到以下内容： \n'.$posts;
            }else{
                $resmsg = '抱歉，没有搜索到相关内容';
            }
            $RiProSNS->SendMessage($resmsg,$snsInfo['openid']);
            
        }
        break;
    case 'event':
        // 扫码登录
        if ($getEvent=='subscribe') {
            $scene_id = substr($getRevData['EventKey'],8);
        }elseif ($getEvent=='scan'){
            $scene_id = $getRevData['EventKey'];
        }else{
            $scene_id = '';
        }
        
        //修复非扫码事件重复通知问题
        if (empty($scene_id) || !$scene_id) {
            die('');
        }
        
        //获取用户信息
        $_snsInfo = $RiProSNS->getUserInfo($openid);
        $snsInfo  = [
            'openid'  => $openid,
            'unionid' => $_snsInfo['unionid'],
            'nick'    => $_snsInfo['nickname'],
            'avatar'  => $_snsInfo['headimgurl'],
        ];
        if (isset($snsInfo) && $snsInfo['openid']) {
            //写入数据库
            global $wpdb;
            $sql = $wpdb->update($wpdb->cao_mpwx_log,array('openid' =>$snsInfo['openid']),array('scene_id' => $scene_id),array('%s'),array('%s'));
            if($sql){
                //处理本地业务逻辑
                $mpwx_get_user_id_type = _cao('is_mp_bind_open') ? 'unionid' : 'openid';
                $RiProSNS->SendMessage('扫码登录成功',$snsInfo['openid']);
                $RiProSNS->go_callback('mpweixin', $openid, $snsInfo, 'openid');
            }
           
        }
        
        break;
    default:
        exit;
}

die('');
