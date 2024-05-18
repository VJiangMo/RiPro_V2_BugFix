<?php

///////////////////////////// RITHEME.COM END ///////////////////////////

defined('ABSPATH') || exit;

/**
 * RiConf init
 */

/**
 * 初始化会员名称 如需修改改这里即可 可以用 add_filter()添加或修改
 * @var [type]
 */
$ri_vip_options = apply_filters('ri_vip_options', array(
    'nov'     => esc_html__('普通', 'ripro-v2'),
    'vip'     => esc_html__('VIP', 'ripro-v2'),
    'boosvip' => esc_html__('终身VIP', 'ripro-v2'),
));

/**
 * 初始化支付方式配置 如需修改改这里即可 可以用 add_filter()添加或修改
 * 当前可用方式key最多三个第三方接口
 * 41 支付宝 42 微信
 * 51 支付宝 52微信
 * 61 支付宝 62微信
 * 数字1结尾必须是支付宝类 2结尾必须是微信类
 */

//
$ri_pay_type_options = apply_filters('ri_pay_type_options', array(
    '1'  => array('name' => '支付宝', 'sulg' => 'alipay'),
    '2'  => array('name' => '微信', 'sulg' => 'weixinpay'),
    '11' => array('name' => '虎皮椒-支付宝', 'sulg' => 'hupijiao_alipay'),
    '12' => array('name' => '虎皮椒-微信', 'sulg' => 'hupijiao_weixin'),
    '21' => array('name' => '迅虎H5-支付宝', 'sulg' => 'xunhupay_alipay'),
    '22' => array('name' => '迅虎H5-微信', 'sulg' => 'xunhupay_weixin'),
    '31' => array('name' => 'PAYJS-支付宝', 'sulg' => 'payjs_alipay'),
    '32' => array('name' => 'PAYJS-微信', 'sulg' => 'payjs_weixin'),
    '41' => array('name' => '易支付-支付宝', 'sulg' => 'epay_alipay'),
    '42' => array('name' => '易支付-微信', 'sulg' => 'epay_weixin'),
    '55' => array('name' => 'PayPal', 'sulg' => 'paypal'),
    '77' => array('name' => '后台充值', 'sulg' => 'admin_pay'),
    '88' => array('name' => '卡密支付', 'sulg' => 'cdk_pay'),
    '99' => array('name' => '余额支付', 'sulg' => 'mycoin_pay'),
    //旧版本名称兼容
    '4'  => array('name' => '微信', 'sulg' => '__old_pay'),
    '5'  => array('name' => '微信', 'sulg' => '__old_pay'),
    '6'  => array('name' => '支付宝', 'sulg' => '__old_pay'),
    '7'  => array('name' => '支付宝', 'sulg' => '__old_pay'),
    '8'  => array('name' => '微信', 'sulg' => '__old_pay'),
    '9'  => array('name' => '支付宝', 'sulg' => '__old_pay'),
    '10' => array('name' => '微信', 'sulg' => '__old_pay'),
));

/**
 * 商城功能入口控制开关
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:54:54+0800
 * @return   boolean                  [description]
 */
function is_close_site_shop() {
    if (_cao('is_weixin_no_shop',false) && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
        return true;
    }

    if (_cao('is_login_site_shop',false) && !is_user_logged_in()) {
        return true;
    }

    return apply_filters('is_site_shop', empty(_cao('is_site_shop', '1')));
}

/**
 * 购买按钮名称
 * @Author Dadong2g
 * @date   2022-05-03
 * @return [type]
 */
function get_shop_paybtn_txt(){
    return trim(_cao('site_shop_paybtn_txt','立即购买'));
}



/**
 * 网站会员组配置信息
 * @Author   Dadong2g
 * @DateTime 2021-04-12T18:01:26+0800
 * @param    [type]                   $key [description]
 * @return   [type]                        [description]
 */
function site_vip() {
    $cao_opt = _cao('site_vip_options');
    $vip_opt = [];
    $vip_key = ['nov', 'vip', 'boosvip'];

    foreach ($vip_key as $type) {
        $vip_opt[$type] = array(
            'name'             => $cao_opt[$type . '_name'],
            'down_num'         => (int) $cao_opt[$type . '_downnum'],
            'down_rate'        => (float) $cao_opt[$type . '_download_rate'],
            'aff_ratio'        => (float) $cao_opt[$type . '_aff_ratio'],
            'author_aff_ratio' => (float) $cao_opt[$type . '_author_aff_float'],
        );
    }
    return $vip_opt;
}

/**
 * 获取会员开通套餐
 * @Author   Dadong2g
 * @DateTime 2021-04-14T11:35:42+0800
 * @return   [type]                   [description]
 */
function site_vip_pay_opt() {
    $cao_opt = _cao('vip_pay_opt', array());
    if (empty($cao_opt) && !is_array($cao_opt)) {
        return array();
    }
    return $cao_opt;
}

/**
 * 站内币信息获取
 * @Author   Dadong2g
 * @DateTime 2021-04-12T17:42:33+0800
 * @param    string                   $params [description]
 * @return   [type]                           [description]
 */
function site_mycoin($params = 'name') {
    switch ($params) {
    case 'name':
        return _cao('site_mycoin_name', '金币');
        break;
    case 'rate':
        return (float) _cao('site_mycoin_rate', '10');
        break;
    case 'icon':
        return _cao('site_mycoin_icon', 'fas fa-coins');
        break;
    case 'min_pay':
        return (int) _cao('site_mycoin_pay_minnum', 1);
        break;
    case 'max_pay':
        return (int) _cao('site_mycoin_pay_maxnum', 9999);
        break;
    case 'pay_arr':
        return explode(",", _cao('site_mycoin_pay_arr', '1,10,50,100,300,500'));
        break;
    default:
        return _cao('site_mycoin_name', '金币');
        break;
    }

}

/**
 * 转换站内币汇率
 * @Author   Dadong2g
 * @DateTime 2021-04-12T17:42:43+0800
 * @param    integer                  $num [description]
 * @param    string                   $to  [description]
 * @return   [type]                        [description]
 */
function convert_site_mycoin($num = 0, $to = 'coin') {
    // RMB汇率
    $site_rate = site_mycoin('rate');
    switch ($to) {
    case 'coin':
        $new_num = $num * $site_rate;
        break;
    case 'rmb':
        $new_num = $num / $site_rate;
        break;
    default:
        $new_num = $num;
        break;
    }
    return (float) $new_num;
}

/**
 * 获取用户余额 保留三位小数点
 * @Author   Dadong2g
 * @DateTime 2021-03-10T13:21:55+0800
 * @param    [type]                   $user_id [description]
 * @return   [type]                            [description]
 */
function get_user_mycoin($user_id = null) {

    if (empty($user_id)) {
        global $current_user;
        $user_id = $current_user->ID;
    }
    $mycoin = (float) get_user_meta($user_id, 'cao_balance', true);
    if (0 > $mycoin) {
        $mycoin = 0;
    }
    return round($mycoin,3);

}

/**
 * 更新用户余额
 * @Author   Dadong2g
 * @DateTime 2021-03-10T13:17:54+0800
 * @param    [type]                   $user_id  [description]
 * @param    integer                  $coin_num [-100 100 支持负数]
 * @return   [type]                             [description]
 */
function update_user_mycoin($user_id = null, $coin_num = 0) {

    if (empty($user_id)) {
        global $current_user;
        $user_id = $current_user->ID;
    }

    $this_coin = (float) get_user_meta($user_id, 'cao_balance', true);

    if (empty($this_coin)) {
        $this_coin = 0;
    }

    $new_coin = $this_coin + $coin_num;
    if (0 > $new_coin) {
        $new_coin = 0;
    }

    // 如果是扣除 添加已消费
    if ($coin_num < 0) {
        $old_c_coin = (float) get_user_meta($user_id, 'cao_consumed_balance', true);
        $new_c_coin = abs($coin_num) + $old_c_coin;
        update_user_meta($user_id, 'cao_consumed_balance', $new_c_coin);
    }

    return update_user_meta($user_id, 'cao_balance', $new_coin);

}

/**
 * 是否开启免登录购买
 * @Author   Dadong2g
 * @DateTime 2021-04-13T19:40:24+0800
 * @return   boolean                  [description]
 */
function is_site_nologin_pay() {
    return !empty(_cao('is_ripro_v2_nologin_pay', 1));
}

/**
 * 文章是否下载资源文章
 * @Author   Dadong2g
 * @DateTime 2021-04-13T09:48:55+0800
 * @param    [type]                   $post_ID [description]
 * @return   boolean                           [description]
 */
function is_post_shop_down($post_ID = null) {
    if (empty($post_ID)) {
        global $post;
        $post_ID = $post->ID;
    }
    $cao_status = get_post_meta($post_ID, 'cao_status', true);
    if (!empty($cao_status)) {
        return true;
    }
    return false;
}

/**
 * 是否付费查看内容
 * @Author   Dadong2g
 * @DateTime 2021-04-13T13:49:53+0800
 * @param    [type]                   $post [description]
 * @return   boolean                        [description]
 */
function is_post_shop_hide($post = null) {
    if (empty($post)) {
        global $post;
    }

    if (has_shortcode($post->post_content, 'rihide')) {
        return true;
    }
    return false;
}

/**
 * 是否付费查看视频
 * @Author   Dadong2g
 * @DateTime 2021-04-13T13:50:33+0800
 * @param    [type]                   $post [description]
 * @return   boolean                        [description]
 */
function is_post_shop_video($post_ID = null) {
    if (empty($post_ID)) {
        global $post;
        $post_ID = $post->ID;
    }

    if (get_post_meta($post_ID, 'cao_video', true)) {
        return true;
    }
    return false;
}

/**
 * 是否商品文章
 * @Author   Dadong2g
 * @DateTime 2021-04-13T13:56:45+0800
 * @param    [type]                   $post [description]
 * @return   boolean                        [description]
 */
function is_shop_post($post = null) {
    if (empty($post)) {
        global $post;
    }

    if (is_post_shop_down($post->ID)) {
        return true;
    }

    if (is_post_shop_video($post->ID)) {
        return true;
    }

    if (is_post_shop_hide($post)) {
        return true;
    }

    return false;
}

/**
 * 获取文章价格
 * @Author   Dadong2g
 * @DateTime 2021-04-13T09:55:07+0800
 * @param    [type]                   $post_ID   [description]
 * @param    string                   $user_type [description]
 * @return   [type]                              [description]
 */
function get_post_price($post_ID = null, $user_type = 'nov') {
    if (empty($post_ID)) {
        global $post;
        $post_ID = $post->ID;
    }

    $cao_price = (float) get_post_meta($post_ID, 'cao_price', 1);

    $cao_vip_rate = get_post_meta($post_ID, 'cao_vip_rate', 1);
    $cao_vip_rate = ($cao_vip_rate == '') ? 1 : (float) $cao_vip_rate;

    $cao_is_boosvip   = get_post_meta($post_ID, 'cao_is_boosvip', 1);
    $cao_boosvip_rete = (!empty($cao_is_boosvip)) ? 0 : $cao_vip_rate;

    $price = 0;
    switch ($user_type) {
    case 'nov':
        // 普通用户禁止购买 返回-1标识
        $is_nov_pay = get_post_meta($post_ID, 'cao_close_novip_pay', 1);
        $price      = (!empty($is_nov_pay)) ? -1 : $cao_price;
        break;
    case 'vip':
        $price = $cao_vip_rate * $cao_price;
        break;
    case 'boosvip':
        $price = $cao_boosvip_rete * $cao_price;
        break;
    default:
        $price = $cao_price;
        break;
    }
    return $price;
}

/**
 * 获取文章折扣
 * @Author   Dadong2g
 * @DateTime 2021-04-14T09:02:22+0800
 * @param    [type]                   $post_ID   [description]
 * @param    string                   $user_type [description]
 * @return   [type]                              [description]
 */
function get_post_vip_rate($post_ID = null, $user_type = 'nov') {
    if (empty($post_ID)) {
        global $post;
        $post_ID = $post->ID;
    }

    $cao_vip_rate = get_post_meta($post_ID, 'cao_vip_rate', 1);
    $cao_vip_rate = ($cao_vip_rate == '') ? 1 : (float) $cao_vip_rate;

    $cao_is_boosvip   = get_post_meta($post_ID, 'cao_is_boosvip', 1);
    $cao_boosvip_rete = (!empty($cao_is_boosvip)) ? 0 : $cao_vip_rate;

    $rete = 1;
    switch ($user_type) {
    case 'nov':
        $rete = 1;
        break;
    case 'vip':
        $rete = $cao_vip_rate;
        break;
    case 'boosvip':
        $rete = $cao_boosvip_rete;
        break;
    default:
        $rete = $cao_price;
        break;
    }
    return $rete;
}

/**
 * 获取文章商品到期时间
 * @Author   Dadong2g
 * @DateTime 2021-04-13T10:12:05+0800
 * @param    [type]                   $post_ID [description]
 * @return   [type]                            [description]
 */
function get_post_shop_expire_day($post_ID = null) {
    if (empty($post_ID)) {
        global $post;
        $post_ID = $post->ID;
    }
    $def  = _cao('shop_expire_day', '0');
    $meta = get_post_meta($post_ID, 'cao_expire_day', 1);

    if ($meta == '') {
        $meta = (int) $def;
    } else {
        $meta = (int) $meta;
    }

    if (empty($meta) || $meta == 0) {
        return 9999;
    } else {
        return $meta;
    }
}

/**
 * 获取资源下载地址信息
 * @Author   Dadong2g
 * @DateTime 2021-04-13T20:09:46+0800
 * @param    [type]                   $post_ID [description]
 * @return   [type]                            [description]
 */
function get_post_shop_downurl($post_ID = null) {

    if (empty($post) && empty($post_ID) && is_admin()) {
        $post_ID = (isset($_REQUEST['post'])) ? (int) $_REQUEST['post'] : 0;
    }

    $old_meta = get_post_meta($post_ID, 'cao_downurl', 1);
    $new_meta = get_post_meta($post_ID, 'cao_downurl_new', 1);
    if (!empty($new_meta) && is_array($new_meta)) {
        $post_down_info = $new_meta;
    } elseif (empty($new_meta) && empty($old_meta)) {
        $post_down_info = _cao('cao_downurl_new');
    } else {
        #旧版本数据完美兼容处理
        $post_down_info[] = array(
            'name' => '立即下载',
            'url'  => $old_meta,
            'pwd'  => get_post_meta($post_ID, 'cao_pwd', true),
        );
    }

    return (array) $post_down_info;
}

/**
 * 获取价格介绍组件
 * @Author   Dadong2g
 * @DateTime 2021-04-14T08:11:09+0800
 * @param    [type]                   $post_ID [description]
 * @return   [type]                            [description]
 */
function the_post_shop_priceo_options($post_ID = null) {
    if (empty($post_ID)) {
        global $post;
        $post_ID = $post->ID;
    }
    

    $def_price = get_post_price($post_ID, 'nov');

    // 所有免费
    if ($def_price == 0) {
        return false;
    }

    $site_vip = site_vip();

    echo '<ul class="pricing-options">';

    foreach ($site_vip as $type => $opt) {
        $_price = get_post_price($post_ID, $type);


        if (_cao('is_site_simple_price_opt',false)) {
            //没有折扣时
            if ($type == 'vip' && $def_price == $_price) {
                continue;
            }

            if ($type == 'boosvip' && $def_price > 0 && $_price == get_post_price($post_ID, 'vip')) {
                continue;
            }
        }
        
        $_pricetext = $_price . site_mycoin('name');
        if ($_price == -1) {
            $_pricetext = esc_html__('不可下载', 'ripro-v2');
        } elseif ($_price == 0) {
            $_pricetext = esc_html__('免费', 'ripro-v2');
        }

        $rete = get_post_vip_rate($post_ID, $type);

        if ($rete > 0 && $rete < 1 && $type == 'vip') {
            $_pricetext .= '<small class="badge badge-success-lighten ml-2">' . ($rete * 10) . esc_html__('折', 'ripro-v2') . '</small>';
        } elseif ($rete == 0 && $type == 'boosvip') {
            $_pricetext .= '<small class="badge badge-danger-lighten ml-2">' . esc_html__('推荐', 'ripro-v2') . '</small>';
        }

        echo '<li>';
        echo '<span>' . $opt['name'] . '</span>';
        echo '<b>' . $_pricetext . '</b>';
        echo '</li>';


    }
    echo '</ul>';

}

/**
 * 输出下载地址按钮
 * @Author   Dadong2g
 * @DateTime 2021-04-13T20:10:15+0800
 * @param    [type]                   $post_ID [description]
 * @return   [type]                            [description]
 */
function the_post_shop_downurl_btns($post_ID = null, $nonce = '') {
    if (empty($post_ID)) {
        global $post;
        $post_ID = $post->ID;
    }
    $arr = get_post_shop_downurl($post_ID);

    foreach ($arr as $key => $item) {
        $down_ids  = urlencode(base64_encode($post_ID . '-' . $key . '-' . $nonce));
        $down_link = get_goto_url('down', $down_ids);
        echo '<div class="btn-group btn-block mt-2" role="group">';
        echo '<a target="_blank" href="' . $down_link . '" class="btn btn-dark"><i class="fas fa-download"></i> ' . $item['name'] . '</a>';
        if (!empty($item['pwd'])) {
            echo '<button type="button" class="go-copy btn btn-sm btn-dark" data-toggle="tooltip" data-placement="top" title="' . esc_html__('点击复制密码', 'ripro-v2') . '" data-clipboard-text="' . $item['pwd'] . '"><span>' . esc_html__('密码：', 'ripro-v2') . '</span>' . $item['pwd'] . '</button>';
        }
        echo '</div>';
    }
    wp_enqueue_script('clipboard');
}

/**
 * 获取会员到期时间
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:56:19+0800
 * @param    [type]                   $user_id [description]
 * @return   [type]                            [description]
 */
function _get_user_vip_endtime($user_id = null) {
    if (!isset($user_id)) {
        global $current_user;
        $user_id = $current_user->ID;
    }
    $this_time = time();
    $end_data  = get_user_meta($user_id, 'cao_vip_end_time', true);
    $end_time  = strtotime($end_data);

    if (!is_timestamp($end_time)) {
        $end_time = $this_time;
    }
    if (_get_user_vip_type($user_id) == 'nov') {
        return $this_time;
    } else {
        return $end_time;
    }

}

/**
 * 获取会员类型
 * @Author   Dadong2g
 * @DateTime 2021-04-13T10:41:29+0800
 * @param    [type]                   $user_id [description]
 * @return   [type]                            [description]
 */
function _get_user_vip_type($user_id = null) {
    if (!isset($user_id)) {
        global $current_user;
        $user_id = $current_user->ID;
    }
    $this_time = time();
    $end_data  = get_user_meta($user_id, 'cao_vip_end_time', true);
    $end_time  = strtotime($end_data);

    if (!is_timestamp($end_time)) {
        $end_time = $this_time;
    }

    $vip_type = get_user_meta($user_id, 'cao_user_type', true);

    if ($vip_type != 'vip' || $end_time <= $this_time) {
        return 'nov';
    }

    if ($vip_type == 'vip' && $end_data == '9999-09-09') {
        return 'boosvip';
    }

    if ($vip_type == 'vip' && $end_time > $this_time) {
        return 'vip';
    }

    return 'nov';

}

/**
 * 获取会员角标
 * @Author   Dadong2g
 * @DateTime 2021-04-17T16:09:50+0800
 * @param    [type]                   $user_id [description]
 * @return   [type]                            [description]
 */
function _get_user_vip_type_badge($user_id = null, $tooltip = true) {

    if (is_close_site_shop()) {
        return '';
    }

    date_default_timezone_set(get_option('timezone_string')); //初始化本地时间
    $opt = site_vip();
    if (!isset($user_id)) {
        global $current_user;
        $user_id = $current_user->ID;
    }
    $type    = _get_user_vip_type($user_id);
    $endtime = date('Y-m-d', _get_user_vip_endtime($user_id));
    switch ($type) {
    case 'nov':
        $css = 'badge badge-info-lighten';
        break;
    case 'vip':
        $css = 'badge badge-success-lighten';
        break;
    case 'boosvip':
        $css = 'badge badge-danger-lighten';
        break;
    default:
        $css = 'badge badge-info-lighten';
        break;
    }

    $tip = (!$tooltip) ? '' : 'data-toggle="tooltip" data-placement="right" title="' . esc_html__('到期时间：', 'ripro-v2') . $endtime . '"';

    return '<span class="' . $css . '" ' . $tip . '">' . $opt[$type]['name'] . '</span>';
}

/**
 * 是否时间戳
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:56:36+0800
 * @param    [type]                   $timestamp [description]
 * @return   boolean                             [description]
 */
function is_timestamp($timestamp) {
    if (!empty($timestamp) && strtotime(date('Y-m-d H:i:s', $timestamp)) == $timestamp) {
        return (int) $timestamp;
    } else {
        return false;
    }

}

/**
 * 更新会员数据
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:56:40+0800
 * @param    [type]                   $user_id  [description]
 * @param    [type]                   $vip_type [description]
 * @param    [type]                   $day      [description]
 * @return   [type]                             [description]
 */
function update_user_vip_info($user_id, $vip_type, $day) {
    if (empty($user_id)) {
        return false;
    }
    $this_user_type    = _get_user_vip_type($user_id); //当前会员类型 0 31 365 3600
    $this_user_enditme = _get_user_vip_endtime($user_id); //当前会员到期时间

    if ($this_user_type == 'boosvip') {
        return false;
    }

    $the_time = time();
    $end_time = (!empty($this_user_enditme)) ? $this_user_enditme : $the_time;

    //如果不是会员 则到期时间以今天为准
    if ($this_user_type == 'nov') {
        $end_time = $the_time;
    }

    if ($end_time > $the_time) {
        $new_end_time = $end_time + $day * 24 * 3600;
        // 会员结束日期大于今天 累计增加
    } else {
        $new_end_time = $the_time + $day * 24 * 3600;
        // 会员结束日期小于今天 以今天开始加
    }

    if ($vip_type == 'vip' && $day == 9999) {
        #  如果是升级或者开通永久会员 则赋值 9999-09-09 时间戳
        $nwe_end_data = '9999-09-09';
    } else {
        $nwe_end_data = date("Y-m-d", $new_end_time);
    }

    // 更新数据
    $u = update_user_meta($user_id, 'cao_user_type', $vip_type);
    $t = update_user_meta($user_id, 'cao_vip_end_time', $nwe_end_data);
    return true;
}

/**
 * 获取当前推荐人信息
 * @Author   Dadong2g
 * @DateTime 2021-04-14T08:45:58+0800
 * @param    [type]                   $user_id [description]
 * @return   [type]                            [description]
 */
function get_current_aff_info($user_id) {
    if (!_cao('is_site_aff', true)) {
        return array();
    }
    //推荐奖励AFF
    $aff_uid   = 0;
    $aff_ratio = 0;
    if (_cao('is_site_aff')) {
        $aff_uid     = RiSession::get('current_aff_uid', 0);
        $aff_from_id = get_user_meta($user_id, 'cao_ref_from', true);
        $aff_uid     = (!empty($aff_from_id)) ? $aff_from_id : $aff_uid;
        if (!empty($aff_uid) && $aff_uid != $user_id) {
            $vip_opt      = site_vip();
            $aff_vip_type = _get_user_vip_type($aff_uid);
            $aff_ratio    = $vip_opt[$aff_vip_type]['aff_ratio'];
            return array(
                'aff_uid'   => $aff_uid,
                'aff_ratio' => $aff_ratio,
            );
        }
    }
    return array();
}

/**
 * 获取用户推广信息
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:56:30+0800
 * @param    [type]                   $user_id [description]
 * @return   [type]                            [description]
 */
function _get_user_aff_info($user_id = null) {
    global $wpdb;

    if (empty($user_id)) {
        global $current_user;
        $user_id = $current_user->ID;
    }

    $info = [];
    // 获取总人数
    $info['total'] = RiAff::get_aff_num($user_id);
    //累计佣金
    $info['leiji'] = RiAff::get_total_bonus($user_id);
    //可提现
    $info['keti'] = RiAff::get_ke_bonus($user_id);
    //已经提现
    $info['yiti'] = RiAff::get_yi_bonus($user_id);
    //提现中
    $info['tixian']           = RiAff::get_ing_bonus($user_id);
    $vip_opt                  = site_vip();
    $aff_vip_type             = _get_user_vip_type($user_id);
    $info['aff_ratio']        = $vip_opt[$aff_vip_type]['aff_ratio'];
    $info['author_aff_ratio'] = $vip_opt[$aff_vip_type]['author_aff_ratio'];
    return $info;
}

/**
 * 是否签到
 * @Author   Dadong2g
 * @DateTime 2021-06-07T20:43:23+0800
 * @param    integer                  $users_id [description]
 * @return   [type]                             [description]
 */
function is_user_today_qiandao($users_id = null) {

    if (empty($user_id)) {
        global $current_user;
        $user_id = $current_user->ID;
    }

    $_meta_key = 'cao_qiandao_time';
    // 会员当前签到时间
    $_qiandao_time = (get_user_meta($user_id, $_meta_key, true) > 0) ? get_user_meta($user_id, $_meta_key, true) : 0;

    // 自动更新时间
    $getTime  = RiPro_Time::today();
    $thenTime = time();
    // 获取用户结束时间
    if ($getTime[0] < $_qiandao_time && $getTime[1] > $_qiandao_time) {
        return true;
    }
    return false;
}

/**
 * 移动端商城定位
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:55:26+0800
 * @return   [type]                   [description]
 */
function shop_widget_wap_position() {
    if (!is_close_site_shop()) {
        echo '<div class="pt-0 d-none d-block d-xl-none d-lg-none"><aside id="header-widget-shop-down" class="widget-area"><p></p></aside></div>';
    }
}

/**
 * 添加下载记录
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:56:46+0800
 * @param    [type]                   $user_id [description]
 * @param    [type]                   $post_id [description]
 */
function add_new_down_log($user_id, $post_id) {
    global $wpdb;
    $ip      = get_client_ip();
    $_params = array(
        'user_id'     => $user_id,
        'down_id'     => $post_id,
        'ip'          => $ip,
        'note'        => '',
        'create_time' => time(),
    );
    $ins = $wpdb->insert($wpdb->cao_down_log, $_params, array('%d', '%d', '%s', '%s', '%s'));
    return $ins ? true : false;
}

/**
 * 获取当前文章总下载次数
 * @Author   Dadong2g
 * @DateTime 2021-10-16T10:02:16+0800
 * @return   [type]                   [description]
 */
function _get_post_down_num($post_ID = null){
    if (empty($post_ID)) {
        global $post;
        $post_ID = $post->ID;
    }
    global $wpdb;
    $num = $wpdb->get_var($wpdb->prepare("select count(id) from {$wpdb->cao_down_log} where down_id=%d", $post_ID) );
    return (int) $num;

}


/**
 * 获取用户下载次数信息
 * @Author   Dadong2g
 * @DateTime 2021-04-15T14:08:27+0800
 * @param    [type]                   $user_id [description]
 * @return   [type]                            [description]
 */
function _get_user_today_down($user_id = null) {
    if (empty($user_id)) {
        global $current_user;
        $user_id = $current_user->ID;
    }
    $vip_options = site_vip();
    $user_type   = _get_user_vip_type($user_id);
    // 今日总共可以可下载次数
    $zong = $vip_options[$user_type]['down_num'];
    //今日已经下载次数
    $yi = _get_user_today_downum($user_id);
    //剩余次数
    $ke = ($zong - $yi);
    $ke = ($ke > 0) ? $ke : 0;
    return array('zong' => $zong, 'yi' => $yi, 'ke' => $ke);
}

/**
 * 获取用户下载总次数 如果是单独购买的文章资源 不会被限制下载
 * @Author   Dadong2g
 * @DateTime 2021-04-15T14:08:34+0800
 * @param    [type]                   $user_id [description]
 * @return   [type]                            [description]
 */
function _get_user_today_downum($user_id) {
    if (empty($user_id) || $user_id == 0) {
        return 0;
    }
    global $wpdb;
    $today = RiPro_Time::today();
    $num   = $wpdb->query($wpdb->prepare("select a.down_id,b.status,count(a.down_id) from {$wpdb->cao_down_log} as a left join {$wpdb->cao_order} as b on (a.user_id = b.user_id and a.down_id=b.post_id) where a.user_id=%d and a.create_time>%s and a.create_time<%s group by a.down_id having isnull(b.status)", $user_id, $today[0], $today[1]));
    return (int) $num;
}

/**
 * 今日是否下载过次资源
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:57:10+0800
 * @param    [type]                   $user_id [description]
 * @param    [type]                   $post_id [description]
 * @return   boolean                           [description]
 */
function is_today_down_posot($user_id, $post_id) {
    global $wpdb;
    $today = RiPro_Time::today();
    $num   = $wpdb->query($wpdb->prepare(
        "select count(down_id) from {$wpdb->cao_down_log} where user_id=%d and down_id=%d and create_time>%s and create_time<%s group by down_id", $user_id, $post_id, $today[0], $today[1]));
    return (int) $num;
}

/**
 * 获取支付方式
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:57:19+0800
 * @return   [type]                   [description]
 */
function _ripro_get_pay_type_html() {
    global $ri_pay_type_options;
    $is_alipay = $is_weixinpay = $is_iconpay = $is_cdkpay = $is_paypal = false;
    $alipay_type = $wxpay_type = $iconpay_type = $cdkpay_type = $paypal_type = 0;

    $is_login_user_no_pay = _cao('is_login_user_no_pay',false);

    foreach ($ri_pay_type_options as $k => $v) {

        $_type = $k % 10;

        //登录用户仅限站内余额购买资源
        if ( $is_login_user_no_pay && is_user_logged_in() && $_type !== 9 && is_singular('post') ) {
            continue;
        }

        //充值仅限在线充值
        if ( is_user_logged_in() && get_query_var('action') == 'coin' && $_type == 9 ) {
            continue;
        }

        //开通站内会员仅限在线支付
        if ( _cao('is_pay_vip_no_coin',false) && get_query_var('action') == 'vip' && $_type == 9 ) {
            continue;
        }

        //未登录用户不允许余额支付
        if ( !is_user_logged_in() && $_type == 9 ) {
            continue;
        }
        

        if (($is_alipay && $_type == 1) || ($is_weixinpay && $_type == 2) || ($is_paypal && $_type == 5) ) {
            continue;
        }


        if ( !_cao('is_' . $v['sulg']) ){
            continue;
        }

        switch ($_type) {
            case 1:
                $is_alipay   = true;
                $alipay_type = $k;
                break;
            case 2:
                $is_weixinpay = true;
                $wxpay_type   = $k;
                break;
            case 5:
                $is_paypal   = true;
                $paypal_type = 55;
                break;
            case 9:
                $is_iconpay   = true;
                $iconpay_type = 99;
                break;
            default:
                # no...
                break;
        }

        if ($is_alipay && $is_weixinpay && $is_iconpay && $is_paypal) {
            break;
        }

    }

    
    $html = '<div class="pay-button-box">';

    if ($is_paypal) {
         $html .= '<div class="pay-item" id="paypal" data-type="'.$paypal_type.'"><i class="paypal"></i><span>' . esc_html__('PayPal', 'ripro-v2') . '</span></div>';
    }


    if ($is_alipay) {
        $html .= '<div class="pay-item" id="alipay" data-type="' . $alipay_type . '"><i class="alipay"></i><span>' . esc_html__('支付宝', 'ripro-v2') . '</span></div>';
    }

    if ($is_weixinpay) {
        $html .= '<div class="pay-item" id="weixinpay" data-type="' . $wxpay_type . '"><i class="weixinpay"></i><span>' . esc_html__('微信支付', 'ripro-v2') . '</span></div>';
    }


    if ($is_iconpay) {
        $html .= '<div class="pay-item" id="iconpay" data-type="' . $iconpay_type . '"><i class="iconpay"></i><span>' . esc_html__('余额支付', 'ripro-v2') . '</span></div>';
    }

    $html .= '</div>';
    return array('html' => $html, 'alipay' => $alipay_type, 'weixinpay' => $wxpay_type, 'paypal' => $paypal_type, 'iconpay' => $iconpay_type);
}



/**
 * 获取支付方式html字符串
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:57:23+0800
 * @param    [type]                   $type        [description]
 * @param    [type]                   $order_price [description]
 * @param    [type]                   $qrimg       [description]
 * @return   [type]                                [description]
 */
function get_ajax_payqr_html($type, $order_price, $qrimg) {
    switch ($type) {
    case 'alipay':
        $iconstr  = '<img src="' . get_template_directory_uri() . '/assets/img/alipay.png" class="qr-pay">';
        $html_str = '<div class="qrcon"> <h5> ' . $iconstr . ' </h5> <div class="title">支付宝扫码支付 ' . $order_price . ' 元</div> <div align="center" class="qrcode"> <img src="' . $qrimg . '"/> </div> <div class="bottom alipay"> 请使用支付宝扫一扫<br><small>扫码后等待 5 秒左右，切勿关闭扫码窗口</small></br></div> </div>';
        break;
    case 'weixinpay':
        $iconstr  = '<img src="' . get_template_directory_uri() . '/assets/img/weixin.png" class="qr-pay">';
        $html_str = '<div class="qrcon"> <h5> ' . $iconstr . ' </h5> <div class="title">微信扫码支付 ' . $order_price . ' 元</div> <div align="center" class="qrcode"> <img src="' . $qrimg . '"/> </div> <div class="bottom weixinpay"> 请使用微信扫一扫<br><small>扫码后等待 5 秒左右，切勿关闭扫码窗口</small></br></div> </div>';
        break;
    default:
        break;
    }

    return $html_str;
}

/**
 * 统一支付
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:57:40+0800
 * @param    [type]                   $pay_type   [description]
 * @param    [type]                   $order_data [description]
 * @return   [type]                               [description]
 */
function get_pay_snyc_data($pay_type, $order_data) {
    global $ri_pay_type_options;

    //储存当前订单号
    RiSession::set('current_pay_ordernum', $order_data['order_trade_no']);
    
    //订单IP地址
    $order_info = maybe_unserialize($order_data['order_info']);
    $order_data['ip'] = $order_info['ip'];
    
    
    // 判断支付方式 1 支付宝 START
    $RiProPay  = new RiProPay();
    $_the_type = (string) $ri_pay_type_options[$pay_type]['sulg'];

    //卡密支付
    if ('cdk_pay' == $_the_type) {
        global $current_user;
        $user_id = $current_user->ID;

        if (!_cao('is_cdk_pay', true)) {
            echo json_encode(array('status' => '0', 'msg' => '卡密通道暂未开启'));exit;
        }

        $cdk_money = RiCdk::get_cdk($order_data['cdk_code'], true);
        if (empty($cdk_money) || $cdk_money <= 0) {
            echo json_encode(array('status' => '0', 'msg' => '无效卡密，请输入有效卡密'));exit;
        }

        // 销毁卡密
        if (RiCdk::update_cdk($order_data['cdk_code'])) {
            $trade_no = $order_data['cdk_code']; // 时间戳和消费前余额
            $RiClass  = new RiClass;
            if (!$RiClass->send_order_trade_notify($order_data['order_trade_no'], $trade_no)) {
                echo json_encode(array('status' => '0', 'msg' => '订单处理状态异常'));exit;
            }
        } else {
            echo json_encode(array('status' => '0', 'msg' => '卡密异常，请刷新重试'));exit;
        }

        //延迟处理 增强交互体验
        usleep(500000);
        //返回前段json数据
        echo json_encode(array('status' => '1', 'type' => '4', 'msg' => '卡密充值成功，+ ' . $cdk_money . ' ' . site_mycoin('name') . '，余额：' . get_user_mycoin($user_id), 'num' => $order_data['order_trade_no']));exit;

    }
    //余额支付
    if ('mycoin_pay' == $_the_type) {
        global $current_user;
        $user_id = $current_user->ID;

        if (!site_mycoin('is') || $order_data['order_type'] == 'charge') {
            echo json_encode(array('status' => '0', 'msg' => '余额支付通道未开启'));exit;
        }

        $coin_price  = convert_site_mycoin($order_data['order_price'], 'coin');
        $user_mycoin = get_user_mycoin($user_id);
        if ($user_mycoin < $coin_price) {
            echo json_encode(array('status' => '0', 'msg' => '<a target="_blank" href="' . get_user_page_url('coin') . '" class="btn btn-sm btn-danger ml-2">' . site_mycoin('name') . '余额不足，点击充值</a>'));exit;
        }
    
        $coin_price = abs( $coin_price ) * -1;
        
        if (!update_user_mycoin($user_id, $coin_price)) {
            echo json_encode(array('status' => '0', 'msg' => '余额消费异常'));exit;
        }

        //扣费成功 添加消费记录和订单状态
        $trade_no = '99-' . time(); // 时间戳和消费前余额
        $RiClass  = new RiClass;
        if (!$RiClass->send_order_trade_notify($order_data['order_trade_no'], $trade_no)) {
            echo json_encode(array('status' => '0', 'msg' => '订单处理状态异常'));exit;
        }

        //延迟处理 增强交互体验
        usleep(500000);

        //返回前段json数据
        echo json_encode(array('status' => '1', 'type' => '4', 'msg' => '支付成功，扣除 ' . $coin_price . ' ' . site_mycoin('name') . '，余额：' . get_user_mycoin($user_id), 'num' => $order_data['order_trade_no']));exit;

    }

    //后台支付
    if ('admin_pay' == $_the_type) {
        echo json_encode(array('status' => '0', 'msg' => '仅限管理员操作'));exit;
    }

    // 支付宝官方 
    if ($_the_type == 'alipay') {
        $config = _cao('alipay');
        if (empty($config['appid']) || empty($config['privateKey']) || empty($config['api_type'])) {
            echo json_encode(array('status' => '0', 'msg' => '请设置支付宝应用配置秘钥'));exit;
        }

        switch ($config['api_type']) {
            case 'web':
                if (wp_is_mobile() && $config['is_mobile']) {
                    //APP H5支付
                    $pay_url = $RiProPay->alipay_app_wap_pay($order_data);
                    
                }else{
                    //APP电脑网站支付 支付宝即时到账
                    $pay_url = $RiProPay->alipay_app_web_pay($order_data);
                }
                echo json_encode(array('status' => '1', 'type' => '2', 'msg' => $pay_url, 'qrcode' => '', 'num' => $order_data['order_trade_no']));exit;
                break;
            case 'qr':
                // APP 当面付
                $pay_url = $RiProPay->alipay_app_qr_pay($order_data);
                echo json_encode(array('status' => '1', 'type' => '1', 'msg' => get_ajax_payqr_html('alipay', $order_data['order_price'], getQrcodeApi($pay_url)), 'num' => $order_data['order_trade_no']));exit;
                break;
            default:
                echo json_encode(array('status' => '0', 'msg' => '请设置支付宝接口配置参数'));exit;
                break;
        }

    }
    //微信官方支付
    if ($_the_type == 'weixinpay') {
        $config = _cao('weixinpay');

        if (wp_is_mobile() && $config['is_mobile'] && !is_weixin_visit()) {
            # 手机端h5跳转支付
            $pay_url = $RiProPay->weixin_h5_pay($order_data);
            echo json_encode(array('status' => '1', 'type' => '2', 'msg' => $pay_url, 'num' => $order_data['order_trade_no']));
            exit;
        } elseif (is_weixin_visit() && $config['is_jsapi']) {
            # 微信内jsapi支付
            if (_cao('sns_weixin')['sns_weixin_mod'] != 'mp') {
                echo json_encode(array('status' => '0', 'msg' => '微信内jsapi支付请在登录设置里设置公众号登录配置'));
                exit;
            }

            $order_data['openid'] = RiSession::get('current_weixin_openid', 0);

            $pay_url = $RiProPay->weixin_jsapi_pay($order_data);
            echo json_encode(array('status' => '1', 'type' => '3', 'msg' => $pay_url, 'num' => $order_data['order_trade_no']));
            exit;
        } else {
            #navtie扫码支付
            $pay_url = $RiProPay->weixin_qr_pay($order_data);
            echo json_encode(array('status' => '1', 'type' => '1', 'msg' => get_ajax_payqr_html('weixinpay', $order_data['order_price'], getQrcodeApi($pay_url)), 'num' => $order_data['order_trade_no']));
            exit;
        }
    }
    //虎皮支付宝 hpj
    if ($_the_type == 'hupijiao_alipay') {
        $pay_url = $RiProPay->hpj_alipay_pay($order_data);

        if (wp_is_mobile()) {
            echo json_encode(array('status' => '1', 'type' => '2', 'msg' => $pay_url['url'], 'num' => $order_data['order_trade_no']));
        } else {
            echo json_encode(array('status' => '1', 'type' => '1', 'msg' => get_ajax_payqr_html('alipay', $order_data['order_price'], $pay_url['url_qrcode']), 'num' => $order_data['order_trade_no']));
        }
        exit;
    }
    //虎皮椒微信 hpj
    if ($_the_type == 'hupijiao_weixin') {
        $pay_url = $RiProPay->hpj_weixin_pay($order_data);
        if (wp_is_mobile()) {
            echo json_encode(array('status' => '1', 'type' => '2', 'msg' => $pay_url['url'], 'num' => $order_data['order_trade_no']));
        } else {
            echo json_encode(array('status' => '1', 'type' => '1', 'msg' => get_ajax_payqr_html('weixinpay', $order_data['order_price'], $pay_url['url_qrcode']), 'num' => $order_data['order_trade_no']));
        }
        exit;
    }
    //讯虎支付宝
    if ($_the_type == 'xunhupay_alipay') {
        $date = $RiProPay->new_xunhu_pay($order_data, 'alipay');
        if (!empty($date['h5'])) {
            echo json_encode(array('status' => '1', 'type' => '2', 'msg' => $date['h5'], 'num' => $order_data['order_trade_no']));
        } elseif (!empty($date['qrcode'])) {
            echo json_encode(array('status' => '1', 'type' => '1', 'msg' => get_ajax_payqr_html('alipay', $order_data['order_price'], getQrcodeApi($date['qrcode'])), 'num' => $order_data['order_trade_no']));
        }
        exit;
    }
    //讯虎h5微信
    if ($_the_type == 'xunhupay_weixin') {
        $date = $RiProPay->new_xunhu_pay($order_data, 'wechat');
        if (!empty($date['h5'])) {

            if (!is_weixin_visit()) {
                $url = $date['h5'] . '&redirect_url=' . urlencode($order_data['callback_url']);
            } else {
                $url = $date['h5'];
            }

            echo json_encode(array('status' => '1', 'type' => '2', 'msg' => $url, 'num' => $order_data['order_trade_no']));
        } elseif (!empty($date['qrcode'])) {
            echo json_encode(array('status' => '1', 'type' => '1', 'msg' => get_ajax_payqr_html('weixinpay', $order_data['order_price'], getQrcodeApi($date['qrcode'])), 'num' => $order_data['order_trade_no']));
        }
        exit;
    }

    //PAYJS微信 OR 支付宝
    if ($_the_type == 'payjs_alipay' || $_the_type == 'payjs_weixin') {

        if ($_the_type == 'payjs_alipay') {
            $html_type  = 'alipay';
            $payjs_type = 'alipay';
        } else {
            $html_type  = 'weixinpay';
            $payjs_type = '';
        }

        if (wp_is_mobile()) {
            //收银台模式支付
            $date = $RiProPay->payjs_pay($order_data, 'cashier', $payjs_type);
            echo json_encode(array('status' => '1', 'type' => '2', 'msg' => $date, 'num' => $order_data['order_trade_no']));
        } else {
            //扫码支付
            $date = $RiProPay->payjs_pay($order_data, 'native', $payjs_type);
            echo json_encode(array('status' => '1', 'type' => '1', 'msg' => get_ajax_payqr_html($html_type, $order_data['order_price'], $date['qrcode']), 'num' => $order_data['order_trade_no']));
        }

        exit;
    }

    //易支付 
    if ($_the_type == 'epay_alipay' || $_the_type == 'epay_weixin') {

        if ( $_the_type == 'epay_alipay' ) {
            $yzfConfig = _cao($_the_type);
            $type      = 'alipay';
        }elseif ($_the_type == 'epay_weixin') {
            $yzfConfig = _cao($_the_type);
            $type      = 'wxpay';
        }
        //签名方式 不需修改
        $yzfConfig['sign_type']    = strtoupper('MD5');
        //字符编码格式 目前支持 gbk 或 utf-8
        $yzfConfig['input_charset']= strtolower('utf-8');

        //构造参数
        $params = array(
            "pid"          => trim($yzfConfig['partner']),
            "out_trade_no" => $order_data['order_trade_no'], //唯一标识
            "notify_url"   => get_template_directory_uri() . '/inc/shop/epay/notify.php',
            "return_url"   => get_template_directory_uri() . '/inc/shop/epay/return.php', // 支付后跳转返回地址
            "name"         => $order_data['order_name'],
            "sitename"     => get_bloginfo('name'),
            "type"         => $type, //alipay:支付宝,wxpay:微信支付
            "money"        => $order_data['order_price'], //金额
            "sign_type"    => strtoupper('MD5'),
        );
        //建立请求
        require_once get_template_directory() . '/inc/class/pay.epay.class.php';
        $EpaySubmit = new EpaySubmit($yzfConfig);
        $urls = $EpaySubmit->buildRequestForm($params);
        echo json_encode(array('status' => '1', 'type' => '2', 'msg' => $urls, 'num' => $order_data['order_trade_no']));
        exit;
    }

    // paypal
    if ($_the_type == 'paypal') {

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

        $currency = (empty($opt['currency'])) ? 'USD' : $opt['currency'];
        $rates = (empty($opt['rates'])) ? 1 : (float)$opt['rates'];

        $amount = sprintf("%.2f",$order_data['order_price'] / $rates);
        $desc = $order_data['order_name'];
        $invoice = $order_data['order_trade_no'];
        $config['return'] = get_template_directory_uri() . '/inc/shop/paypal/ppreturn.php';
        $config['cancel'] = $order_data['callback_url'];

        $PayPal = new PayPal($config);

        $ret = ($PayPal->doExpressCheckout($amount,$desc, $invoice, $currency));

        if (is_array($ret)) {
            var_dump($ret);die;
        }else{
            echo json_encode(array('status' => '1', 'type' => '2', 'msg' => $ret, 'num' => $order_data['order_trade_no']));die;
        }
        
    }

    //预留钩子 方便第三方插件或者子主题添加其他支付接口方式 add_action('ri_get_pay_snyc_data', '_fun_', 10, 2);
    do_action("ri_get_pay_snyc_data", $_the_type, $order_data);

}

///////////////////////////////////////////////////

/**
 * 获取订单类型文本
 * @Author   Dadong2g
 * @DateTime 2021-04-23T13:28:14+0800
 * @param    [type]                   $order [description]
 * @return   [type]                          [description]
 */
function get_order_type_text($order) {

    if ($order->order_type == 'charge') {
        $name = site_mycoin('name') . esc_html__('充值', 'ripro-v2');
    } elseif ($order->order_type == 'other') {
        $order_info = maybe_unserialize($order->order_info);
        # 购买会员...
        if (!empty($order_info['vip_day']) && $order_info['vip_day'] > 0) {
            $day  = ($order_info['vip_day'] == 9999) ? esc_html__('永久', 'ripro-v2') : $order_info['vip_day'] . esc_html__('天', 'ripro-v2');
            $name = esc_html__('开通会员', 'ripro-v2') . '-' . $day;
        } elseif (get_post_type($order->post_id) == 'page' && $order->user_id > 0) {
            $name = esc_html__('开通会员', 'ripro-v2');
        } elseif (!get_permalink($order->post_id)) {
            $name = esc_html__('其他订单', 'ripro-v2') . $order->post_id;
        } else {
            $name = get_the_title($order->post_id);
        }
    } else {
        $name = esc_html__('其他订单', 'ripro-v2');
    }

    return $name;

}

/**
 * 根据支付方式id获取支付方式文本
 * @Author   Dadong2g
 * @DateTime 2021-05-22T17:07:11+0800
 * @param    [type]                   $pay_type [description]
 * @return   [type]                             [description]
 */
function get_order_pay_type_text($pay_type, $is_name = false) {
    global $ri_pay_type_options;
    $text = $ri_pay_type_options[$pay_type]['name'];
    if (!$is_name) {
        $text = explode('-', $text);
        $text = (isset($text[1])) ? $text[1] : $text[0];
    }
    return $text;
}

///////////////////////////////////////////////////

/**
 * 付款成功后信息处理
 * @Author   Dadong2g
 * @DateTime 2021-04-14T10:19:38+0800
 * @param    [type]                   $order [description]
 * @return   [type]                          [description]
 */
function site_shop_pay_succ_callback($order) {
    global $ri_pay_type_options;

    if (empty($order) || empty($order->status)) {
        return false;
    }

    // 订单其他信息
    $order_info = maybe_unserialize($order->order_info);

    // 佣金处理方法 后台充值 卡密充值 不计算佣金 余额支付也不计算
    $is_aff = ( !empty($order_info['aff_uid']) && !empty($order_info['aff_ratio']) ) ? true : false;
    $is_aff = ($order->pay_type == '77' || $order->pay_type == '88' || $order->pay_type == '99') ? false : $is_aff ;

    if ($is_aff) {
        $amount = sprintf('%0.2f', $order_info['aff_ratio'] * $order->order_price);
        RiAff::add_total_bonus($order_info['aff_uid'], $amount);

        //发送消息到网站动态
        RiDynamic::add(array(
            'info' => sprintf(__('通过推广链接获得佣金奖励：￥%s', 'ripro-v2'),$amount), 
            'uid' => $order_info['aff_uid'], 
            'href' => get_user_page_url('aff'),
            'time' => time(),
        ));


    }

    //订单类型
    if ($order->order_type == 'charge') {
        # 充值...
        $coin_num_new = convert_site_mycoin($order->order_price, 'coin');

    
        //发送消息到网站动态
        RiDynamic::add(array(
            'info' => sprintf(__('使用%s成功充值了%s%s', 'ripro-v2'),get_order_pay_type_text($order->pay_type),$coin_num_new,site_mycoin('name')), 
            'uid' => $order->user_id, 
            'href' => get_user_page_url('coin'),
            'time' => time(),
        ));

        return update_user_mycoin($order->user_id, $coin_num_new);

    } elseif ($order->order_type == 'other') {

        if (!empty($order_info['vip_day']) && $order_info['vip_day'] > 0) {
            # 购买会员...
            $pay_daynum = (int) $order_info['vip_day'];

            //发送消息到网站动态
            RiDynamic::add(array(
                'info' => sprintf( __('使用%s成功开通了本站VIP会员', 'ripro-v2'),get_order_pay_type_text($order->pay_type) ), 
                'uid' => $order->user_id, 
                'href' => get_user_page_url('vip'),
                'time' => time(),
            ));

            return update_user_vip_info($order->user_id, 'vip', $pay_daynum);
        } else {
            
            // 更新销售数量
            $cao_paynum = (int)get_post_meta($order->post_id, 'cao_paynum', true);
            update_post_meta( $order->post_id, 'cao_paynum', $cao_paynum+1);


            //发送消息到网站动态
            RiDynamic::add(array(
                'info' => sprintf( __('成功购买了%s', 'ripro-v2'),get_the_title( $order->post_id ) ), 
                'uid' => $order->user_id, 
                'href' => get_the_permalink( $order->post_id ),
                'time' => time(),
            ));

            #购买文章 发放作者佣金
            $author_id       = (int) get_post($order->post_id)->post_author;
            $author_aff_info = _get_user_aff_info($author_id);
            if ($author_id > 0 && $order->user_id != $author_id && $author_aff_info['author_aff_ratio'] > 0) {
                $amount = sprintf('%0.2f', $author_aff_info['author_aff_ratio'] * $order->order_price);
                RiAff::add_total_bonus($author_id, $amount);
            }

        }

    }

}
add_action('ripro_v2_pay_order_success', 'site_shop_pay_succ_callback', 10, 1);

///////////////////////////////////////////////////////////

/**
 *获取网站动态记录
 */
class RiDynamic {

    public static $max_num       = 10; //最大缓存动态数量
    public static $expire_time   = 43200; //缓存时间秒 十二小时
    public static $transient_key = 'ripri_site_dynamic'; //data keyex

    public static function get($key = null) {

        $data = get_transient(self::$transient_key);

        $arr = maybe_unserialize($data); //解密数据

        if ($arr === false || empty($arr) || !is_array($arr)) {
            return array();
        } else {
            // rsort($arr); //序列数组 sort
            return $arr;
        }

    }

    public static function add($data = array()) {

        $arr = self::get();

        array_push($arr, $data);

        array_multisort(array_column($arr, 'time'), SORT_DESC, $arr);

        if (count($arr) > self::$max_num) {
            array_pop($arr);
        }

        $data_arr = maybe_serialize($arr); //格式化数据

        return set_transient(self::$transient_key, $data_arr, self::$expire_time);
    }

    public static function delete() {
        return delete_transient(self::$transient_key);
    }

}



/**
 * 卡密系统
 * wpdb->cao_coupon
 */
class RiCdk {

    public static $cdk_status = array('0' => '未使用', '1' => '已使用', '2' => '失效');
    public static $cdk_type   = array('0' => '无', '1' => '充值卡', '2' => '会员月卡', '3' => '会员年卡', '4' => '永久会员卡', '5' => '立减卡', '5' => '邀请码');

    //获取卡密信息
    public static function get_cdk($code, $ischeck = false) {
        global $wpdb;
        $cdk_money = 0;
        $code      = sanitize_text_field(wp_unslash($code));
        $cdk       = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->cao_coupon} WHERE code = %s", $code));

        if (empty($cdk)) {
            return 0;
        }
        //是否检测卡密 返回卡密面值
        if ($ischeck && $cdk->status == 0 && $cdk->end_time > time() && $cdk->apply_time == 0) {
            return (float) $cdk->sale_money;
        } else {
            return 0;
        }
        return $cdk;
    }

    //添加卡密
    public static function add_cdk($money, $day, $num) {
        global $wpdb;
        $money = (float) $money;
        $day   = (int) $day;
        $num   = (int) $num;
        for ($i = 0; $i < $num; $i++) {
            // 字符串
            $length     = 12;
            $chars      = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $chars      = str_shuffle($chars);
            $length_num = $length < strlen($chars) - 1 ? $length : str_len($chars) - 1;
            $cdk_code   = substr($chars, 0, $length_num);

            $create_time = time();
            $end_time    = $create_time + $day * 24 * 60 * 60;
            $params      = array(
                'code'        => $cdk_code,
                'code_type'   => 1,
                'create_time' => $create_time,
                'end_time'    => $end_time,
                'apply_time'  => 0,
                'sale_money'  => sprintf('%0.2f', $money), //保留两个百分点
                'status'      => 0,
            );
            $ins = $wpdb->insert($wpdb->cao_coupon, $params, array('%s', '%d', '%s', '%s', '%s', '%s', '%d'));
        }
        return $ins ? true : false;
    }

    //更新卡密
    public static function update_cdk($code) {
        global $wpdb;
        $update = $wpdb->update(
            $wpdb->cao_coupon,
            array('apply_time' => time(), 'status' => 1),
            array('code' => $code),
            array('%s', '%d'),
            array('%s')
        );
        return $update ? true : false;
    }

}

/**
 * 佣金系统
 */
class RiAff {

    //获取推荐人数下级
    public static function get_aff_num($user_id = 0) {
        global $wpdb;
        $ref_num = $wpdb->get_var($wpdb->prepare("SELECT COUNT(user_id) FROM {$wpdb->usermeta} WHERE meta_key=%s AND meta_value=%s", 'cao_ref_from', $user_id));
        return (float) $ref_num;
    }

    //累计佣金
    public static function get_total_bonus($user_id = 0) {
        return sprintf('%0.2f', get_user_meta($user_id, 'cao_total_bonus', true));
    }

    //提现中
    public static function get_ing_bonus($user_id = 0) {
        global $wpdb;
        $res = $wpdb->get_var($wpdb->prepare("SELECT SUM(money) FROM {$wpdb->cao_ref_log} WHERE user_id=%d AND status=0", $user_id));
        return sprintf('%0.2f', $res);
    }

    //已提现
    public static function get_yi_bonus($user_id = 0) {
        global $wpdb;
        $res = $wpdb->get_var($wpdb->prepare("SELECT SUM(money) FROM {$wpdb->cao_ref_log} WHERE user_id=%d AND status=1", $user_id));
        return sprintf('%0.2f', $res);
    }

    //可提现
    public static function get_ke_bonus($user_id = 0) {
        $get_total_bonus = self::get_total_bonus($user_id); //总佣金
        $get_ing_bonus   = self::get_ing_bonus($user_id); //提现中
        $get_yi_bonus    = self::get_yi_bonus($user_id); //已提现
        $ke              = sprintf('%0.2f', $get_total_bonus - $get_ing_bonus - $get_yi_bonus);
        if ($ke <= 0) {
            $ke = 0;
        }
        return $ke;

    }

    //给用户添加佣金
    public static function add_total_bonus($user_id, $amount) {
        $amount = sprintf('%0.2f', $amount);
        // 更新前
        $get_total_bonus = self::get_total_bonus($user_id);
        // 普通更新
        if ($amount > 0) {
            update_user_meta($user_id, 'cao_total_bonus', sprintf('%0.2f', $get_total_bonus + $amount));
        } else {
            return false;
        }
        return true;
    }

    //添加提现申请表
    public static function add_aff_log($user_id = 0, $money = 0, $note = '', $status = 0) {
        global $wpdb;
        $money  = (int) $money; //仅限整数
        $params = array(
            'user_id'     => $user_id,
            'money'       => $money,
            'create_time' => time(),
            'status'      => $status,
            'note'        => $note, //保留两个百分点
        );
        return $wpdb->insert($wpdb->cao_ref_log, $params, array('%d', '%s', '%s', '%d', '%s'));
    }

}

///////////////////////////// RITHEME.COM END ///////////////////////////