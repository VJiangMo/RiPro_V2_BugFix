<?php if (!defined('ABSPATH')) {die;} // Cannot access directly.

if (is_close_site_shop()) {
    return;
}

//付费下载小工具
CSF::createWidget('ripro_v2_shop_down', array(
    'title'       => 'RI-文章侧边栏 : 付费下载组件',
    'classname'   => 'ripro-v2-widget-shop-down',
    'description' => '必备，付费下载组件',
    'fields'      => array(
        array(
            'id'       => 'desc',
            'type'     => 'textarea',
            'sanitize' => false,
            'title'    => '小工具底部提示',
            'default'  => '下载遇到问题？可联系客服或留言反馈',
        ),

        array(
            'id'      => 'is_paynum',
            'type'    => 'switcher',
            'title'   => '显示已售数量',
            'default' => true,
        ),

        array(
            'id'      => 'is_downnum',
            'type'    => 'switcher',
            'title'   => '显示下载数量',
            'default' => true,
        ),

        array(
            'id'      => 'is_modified_time',
            'type'    => 'switcher',
            'title'   => '显示最近更新时间',
            'default' => true,
        ),

    ),
));

/**
 * 付费下载小工具
 * @Author   Dadong2g
 * @DateTime 2021-01-16T14:15:14+0800
 * @param    [type]                   $args     [description]
 * @param    [type]                   $instance [description]
 * @return   [type]                             [description]
 */
if (!function_exists('ripro_v2_shop_down')) {
    function ripro_v2_shop_down($args, $instance) {
        // 付费资源信息
        if (!is_single() || !is_post_shop_down() || is_close_site_shop()) {
            return;
        }

        $instance = array_merge(array(
            'desc'             => '下载遇到问题？可联系客服QQ:8888888',
            'is_paynum'        => true,
            'is_downnum'        => true,
            'is_modified_time' => true,
        ), $instance);

        global $post, $current_user;
        $user_id     = $current_user->ID; //用户ID
        $post_id     = $post->ID; //文章ID
        $click_nonce = wp_create_nonce('rizhuti_click_' . $post_id);

        //是否购买
        $RiClass = new RiClass($post_id, $user_id);
        $IS_PAID = $RiClass->is_pay_post();

        echo $args['before_widget'];

        //价格组件
        $site_vip = site_vip();
        switch ($IS_PAID) {
        case 1:
            $down_infot = esc_html__('您已购买', 'ripro-v2');
            break;
        case 2:
            $down_infot = esc_html__('您已购买', 'ripro-v2');
            break;
        case 3:
            $down_infot = esc_html__('免费下载', 'ripro-v2');
            break;
        case 4:
            $down_infot = $site_vip['vip']['name'] . esc_html__('免费下载', 'ripro-v2');
            break;
        case 5:
            $down_infot = $site_vip['boosvip']['name'] . esc_html__('免费下载', 'ripro-v2');
            break;
        default:
            $down_infot = esc_html__('资源信息', 'ripro-v2');
            break;
        }

        echo '<div class="price"><h3><i class="fas fa-cloud-download-alt mr-1"></i>' . $down_infot . '</h3></div>';

        // 购买后不显示价格组件
        if ($IS_PAID == 0) {
            the_post_shop_priceo_options($post_id);
        }

        //按钮组件 异步模式
        if ( is_site_async_cache() ) {
            wp_enqueue_script('clipboard');
            echo '<div class="async-shop-down text-center"><i class="fa fa-spinner fa-spin"></i></div>';
        }else{
            $the_user_type  = _get_user_vip_type($user_id);
            $the_post_price = get_post_price($post_id, $the_user_type);

            if ($IS_PAID == 0) {
                # 未购买... is_site_nologin_pay
                if ($the_user_type == 'nov' && $the_post_price == -1) {
                    echo '<button type="button" class="btn btn-block btn-danger mb-3" disabled>' . esc_html__('暂无购买权限', 'ripro-v2') . '</button>';
                } elseif (empty($user_id) && !is_site_nologin_pay()) {
                    echo '<button type="button" class="btn btn-block btn-primary mb-3 login-btn">' . esc_html__('登录后下载', 'ripro-v2') . '</button>';
                } else {
                    echo '<button type="button" class="btn btn-block btn-primary mb-3 click-pay-post" data-postid="' . $post_id . '" data-nonce="' . $click_nonce . '" data-price="' . $the_post_price . '">' . get_shop_paybtn_txt() . '</button>';
                }

            } elseif ($IS_PAID == 3) {
                # 免费资源...
                if (empty($user_id) && !is_site_nologin_pay()) {
                    echo '<button type="button" class="btn btn-block btn-primary mb-3 login-btn">' . esc_html__('登录后下载', 'ripro-v2') . '</button>';
                } else {
                    the_post_shop_downurl_btns($post_id, $click_nonce);
                }

            } elseif ($IS_PAID > 0) {
                # 已购买...
                the_post_shop_downurl_btns($post_id, $click_nonce);
            }
        }
        



        //// 自定义按钮
        $cao_diy_btn = get_post_meta($post_id, 'cao_diy_btn', true);
        $btn_array   = explode('|', $cao_diy_btn);
        if (!empty($cao_diy_btn)) {
            echo '<a target="_blank" rel="nofollow noopener noreferrer" href="' . trim($btn_array[1]) . '" class="btn btn-info btn-block mt-2">' . trim($btn_array[0]) . '</a>';
        }

        //// 其他信息
        $cao_info = get_post_meta($post_id, 'cao_info', true);
        if ($cao_info == '' || empty($cao_info)) {
            $cao_info = array();
        }
        if ($cao_demourl = get_post_meta($post_id, 'cao_demourl', true)) {
            $cao_info[] = array('title' => esc_html__('链接', 'ripro-v2'), 'desc' => '<a target="_blank" rel="nofollow noopener noreferrer" href="' . $cao_demourl . '" class="badge badge-secondary-lighten"><i class="fas fa-link"></i> ' . esc_html__('演示地址', 'ripro-v2') . '</a>');
        }
        if ($cao_expire_day = get_post_shop_expire_day($post_id)) {
            $cao_expire_day = ($cao_expire_day == 9999) ? esc_html__('永久有效', 'ripro-v2') : sprintf(__('%s 天内有效', 'ripro-v2'), $cao_expire_day);
            $cao_info[]     = array('title' => esc_html__('有效期', 'ripro-v2'), 'desc' => $cao_expire_day);
        }
        if ($instance['is_paynum'] && $cao_paynum = get_post_meta($post_id, 'cao_paynum', true)) {
            $cao_info[] = array('title' => esc_html__('累计销量', 'ripro-v2'), 'desc' => $cao_paynum);
        }
        //显示下载次数统计
        if ($instance['is_downnum'] && $down_num = _get_post_down_num($post_id)) {
            $cao_info[] = array('title' => esc_html__('累计下载', 'ripro-v2'), 'desc' => $down_num);
        }

        if ($instance['is_modified_time']) {
            $cao_info[] = array('title' => esc_html__('最近更新', 'ripro-v2'), 'desc' => get_the_modified_time('Y年m月d日'));
        }

        if ($cao_info) {
            echo '<div class="down-info">';
            echo '<h5>' . esc_html__('其他信息', 'ripro-v2') . '</h5>';
            echo '<ul class="infos">';
            foreach ($cao_info as $key => $value) {
                echo '<li><p class="data-label">' . $value['title'] . '</p><p class="info">' . $value['desc'] . '</p></li>';
            }
            echo '</ul>';
            echo '</div>';
        }
        ////帮助信息
        if (!empty($instance['desc'])) {
            echo '<div class="down-help mt-2 small text-muted">' . $instance['desc'] . '</div>';
        }

        echo $args['after_widget'];
    }
}
