<?php

///////////////////////////// RITHEME.COM END ///////////////////////////

defined('ABSPATH') || exit;

/**
 * 关闭wordpress的后台地址 wp-admin 和 /wp-login.php/登录地址
 * @Author   Dadong2g
 * @DateTime 2021-01-16T14:16:41+0800
 * @return   [type]                   [description]
 */
function site_login_protection() {
    $is  = apply_filters('is_site_login_protection', true);
    $key = apply_filters('site_login_protection_key', 'wordpress');
    if ($is || !isset($_GET['admin']) || $_GET['admin'] != $key) {
        wp_redirect(wp_login_url());exit;
    }
}
// add_action('login_enqueue_scripts','site_login_protection');

/**
 * 后台菜单
 * @Author   Dadong2g
 * @DateTime 2021-01-16T14:16:58+0800
 * @return   [type]                   [description]
 */
function ripro_v2_add_admin_menu() {
    if (is_close_site_shop() || !current_user_can('manage_options')) {
        return;
    }
    $index_page = 'ripro_v2_shop_index_page';


    // 数值统计
    $num_0 = $num_1 = $num_2 = $num_3 = $num_4 = 0;

    if (true) {
        global $wpdb;
        $startime = mktime(0, 0, 0, wp_date('m'), wp_date('d'), wp_date('Y')); //今天开始时间戳
        $endtime  = mktime(0, 0, 0, wp_date('m'), wp_date('d') + 1, wp_date('Y')) - 1; //今天结束时间戳

        $num_1 = $wpdb->get_var(
            $wpdb->prepare("SELECT COUNT(id) FROM {$wpdb->cao_order} WHERE order_type='other' AND status = 1 AND create_time BETWEEN %s AND %s", $startime, $endtime)
        );

        $num_2 = $wpdb->get_var(
            $wpdb->prepare("SELECT COUNT(id) FROM {$wpdb->cao_order} WHERE order_type='charge' AND status=1 AND create_time BETWEEN %s AND %s", $startime, $endtime)
        );

        $num_3 = 0;

        $num_4 = 0;

    }

    $num_0 = $num_1 + $num_2 + $num_3 + $num_4;


    $title_0 = $num_0 ? sprintf(__('商城管理 <span class="awaiting-mod">+%d</span>', 'ripro-v2'), $num_0) : '商城管理';
    $title_1 = $num_1 ? sprintf(__('订单总览 <span class="awaiting-mod">+%d</span>', 'ripro-v2'), $num_1) : '订单总览';
    $title_2 = $num_2 ? sprintf(__('充值记录 <span class="awaiting-mod">+%d</span>', 'ripro-v2'), $num_2) : '充值记录';
    $title_3 = $num_3 ? sprintf(__('下载记录 <span class="awaiting-mod">+%d</span>', 'ripro-v2'), $num_3) : '下载记录';
    $title_4 = $num_4 ? sprintf(__('佣金管理 <span class="awaiting-mod">+%d</span>', 'ripro-v2'), $num_4) : '佣金管理';


    add_menu_page(esc_html__('商城管理', 'ripro-v2'), $title_0, 'administrator', $index_page, $index_page, 'dashicons-cart', 100);
    $menu = [
        ['menu' => 'ripro_v2_shop_index_page', 'page' => 'shop_index', 'name' => esc_html__('商城总览', 'ripro-v2')],
        ['menu' => 'ripro_v2_pay_order_page', 'page' => 'pay_order', 'name' => $title_1],
        ['menu' => 'ripro_v2_coin_order_page', 'page' => 'pay_order', 'name' => $title_2],
        ['menu' => 'ripro_v2_down_order_page', 'page' => 'down_order', 'name' => $title_3],
        ['menu' => 'ripro_v2_aff_order_page', 'page' => 'aff_order', 'name' => $title_4],
        ['menu' => 'ripro_v2_aff_log_page', 'page' => 'aff_log', 'name' => esc_html__('提现管理', 'ripro-v2')],
        ['menu' => 'ripro_v2_cdk_order_page', 'page' => 'aff_order', 'name' => esc_html__('卡密管理', 'ripro-v2')],
        ['menu' => 'ripro_v2_admin_pay_page', 'page' => 'aff_order', 'name' => esc_html__('后台充值', 'ripro-v2')],
        ['menu' => 'ripro_v2_edit_price_page', 'page' => 'aff_order', 'name' => esc_html__('批量修改', 'ripro-v2')],
    ];

    foreach ($menu as $k => $v) {
        add_submenu_page($index_page, $v['name'], $v['name'], 'administrator', $v['menu'], $v['menu']);
    }
}
add_action('admin_menu', 'ripro_v2_add_admin_menu');

/**
 * 商城总览
 * @Author   Dadong2g
 * @DateTime 2021-01-16T14:17:08+0800
 * @return   [type]                   [description]
 */
function ripro_v2_shop_index_page() {
    date_default_timezone_set(get_option('timezone_string'));
    require_once get_template_directory() . '/inc/admin/shop_index.php';
}

/**
 * 资源订单记录
 * @Author   Dadong2g
 * @DateTime 2021-01-16T14:17:08+0800
 * @return   [type]                   [description]
 */
function ripro_v2_pay_order_page() {
    date_default_timezone_set(get_option('timezone_string'));
    require_once get_template_directory() . '/inc/admin/pay_order.php';
}

/**
 * 充值订单记录
 * @Author   Dadong2g
 * @DateTime 2021-03-12T14:47:34+0800
 * @return   [type]                   [description]
 */
function ripro_v2_coin_order_page() {
    date_default_timezone_set(get_option('timezone_string'));
    require_once get_template_directory() . '/inc/admin/coin_order.php';
}

/**
 * 下载记录
 * @Author   Dadong2g
 * @DateTime 2021-01-16T14:17:14+0800
 * @return   [type]                   [description]
 */
function ripro_v2_down_order_page() {
    date_default_timezone_set(get_option('timezone_string'));
    require_once get_template_directory() . '/inc/admin/down_order.php';
}

/**
 * 推广记录
 * @Author   Dadong2g
 * @DateTime 2021-01-16T14:17:25+0800
 * @return   [type]                   [description]
 */
function ripro_v2_aff_order_page() {
    date_default_timezone_set(get_option('timezone_string'));
    require_once get_template_directory() . '/inc/admin/aff_order.php';
}
/**
 * 提现申请
 * @Author   Dadong2g
 * @DateTime 2021-01-16T14:17:25+0800
 * @return   [type]                   [description]
 */
function ripro_v2_aff_log_page() {
    date_default_timezone_set(get_option('timezone_string'));
    require_once get_template_directory() . '/inc/admin/aff_log.php';
}

/**
 * 卡密管理
 * @Author   Dadong2g
 * @DateTime 2021-03-12T15:26:28+0800
 * @return   [type]                   [description]
 */
function ripro_v2_cdk_order_page() {
    date_default_timezone_set(get_option('timezone_string'));
    require_once get_template_directory() . '/inc/admin/cdk_order.php';
}

/**
 * 消息工单
 * @Author   Dadong2g
 * @DateTime 2021-01-24T17:45:21+0800
 * @return   [type]                   [description]
 */
function ripro_v2_msg_order_page() {
    date_default_timezone_set(get_option('timezone_string'));
    require_once get_template_directory() . '/inc/admin/msg_order.php';
}
/**
 * 批量编辑
 * @Author   Dadong2g
 * @DateTime 2021-03-13T21:51:43+0800
 * @return   [type]                   [description]
 */
function ripro_v2_edit_price_page() {
    date_default_timezone_set(get_option('timezone_string'));
    require_once get_template_directory() . '/inc/admin/edit_price.php';
}

/**
 * 后台充值
 * @Author   Dadong2g
 * @DateTime 2021-03-14T09:50:45+0800
 * @return   [type]                   [description]
 */
function ripro_v2_admin_pay_page() {
    date_default_timezone_set(get_option('timezone_string'));
    require_once get_template_directory() . '/inc/admin/admin_pay.php';
}

/**
 * 普通用户发布文章权限控制
 */
if (!class_exists('Restrict_User_Content')):

    /**
     * Class Definition
     */
    class Restrict_User_Content {

        /**
         * @var bool Does this plugin need a settings page?
         */
        private $_has_settings_page = true;

        /**
         * @var array default settings
         */
        private $_default_settings = array(
            'additional_user_ids' => '0',
        );

        /**
         * Construct
         */
        public function __construct() {
            //Start your custom goodness
            add_action('pre_get_posts', array($this, 'ruc_pre_get_posts_media_user_only'));
            add_filter('parse_query', array($this, 'ruc_parse_query_useronly'));
            add_filter('ajax_query_attachments_args', array($this, 'ruc_ajax_attachments_useronly'));
            add_filter('views_edit-post', array($this, 'ruc_remove_other_users_posts'));
            add_filter('views_edit-page', array($this, 'ruc_remove_other_users_posts'));
            add_filter('admin_footer_text', array($this, 'my_admin_footer_text'));
            add_action('admin_menu', array($this, 'n_a_remove_menu_page'));
            add_action('wp_before_admin_bar_render', array($this, 'remove_admin_bar_links'));
            add_action('admin_init', array($this, 'no_admin_access_page'));
        }

        public function my_admin_footer_text() {
            return '<i class="fa fa-wordpress"></i> the wordpress theme by <a href="https://ritheme.com/" target="_blank">ritheme.com</a>';
        }

        //普通用户禁止修改后台个人信息 设置默认布局颜色
        public function no_admin_access_page() {
            $user_id = get_current_user_id();
            

            if (!current_user_can('manage_options') && is_admin() && (!defined('DOING_AJAX') || !DOING_AJAX)) {

                if (!_cao('is_site_tougao_wp', false)) {
                    wp_redirect(home_url());die();
                }
                if (defined('IS_PROFILE_PAGE') && IS_PROFILE_PAGE) {
                    wp_redirect(admin_url('edit.php'));die();
                }
                if (strpos($_SERVER['REQUEST_URI'], '/wp-admin/upload.php') !== false && isset($_REQUEST['item'])) {
                    wp_redirect(home_url());die();
                }

                //默认颜色
                if (get_user_meta($user_id, 'admin_color', true) != 'light') {
                    update_user_meta($user_id, 'admin_color', 'light');
                }
                // wordpress 投稿者可以上传附件
                if (current_user_can('contributor') && !current_user_can('upload_files')) {
                    $contributor = get_role('contributor');
                    $contributor->add_cap('upload_files');
                    // $contributor->remove_cap('upload_files');
                }
            }
        }

        //删除后台顶部菜单
        public function remove_admin_bar_links() {
            if (!current_user_can('manage_options') && is_admin()) {
                global $wp_admin_bar;
                $wp_admin_bar->remove_menu('ripro-v2'); // 移除链接
                $wp_admin_bar->remove_menu('my-account'); // 移除链接
                $wp_admin_bar->remove_menu('wp-logo'); // 移除链接
            }
        }

        //删除后台页面
        public function n_a_remove_menu_page() {
            if (!current_user_can('manage_options') && is_admin()) {
                remove_menu_page('index.php');
                remove_menu_page('tools.php');
                remove_menu_page('edit-comments.php');
                remove_menu_page('profile.php');
                remove_menu_page('ripro-v2');
            }
        }

        //后台媒体库文件
        public function ruc_pre_get_posts_media_user_only($query) {

            if (strpos($_SERVER['REQUEST_URI'], '/wp-admin/upload.php') !== false) {
                if (!current_user_can('update_core')) {
                    $query->set('author__in', $this->ruc_create_list_of_user_ids());
                }
            }
        }

        public function ruc_parse_query_useronly($wp_query) {
            if (strpos($_SERVER['REQUEST_URI'], '/wp-admin/edit.php') !== false) {
                if (!current_user_can('update_core')) {
                    $current_user = wp_get_current_user();
                    $wp_query->set('author', $current_user->ID);
                }
            }
        }

        public function ruc_ajax_attachments_useronly($query) {
            if (!current_user_can('update_core')) {
                $users               = $this->ruc_create_list_of_user_ids();
                $query['author__in'] = $users;
            }
            return $query;
        }

        private function ruc_create_list_of_user_ids() {
            $current_user = wp_get_current_user();
            $users        = explode(',', '');
            array_unshift($users, $current_user->ID);
            return $users;
        }

        public function ruc_remove_other_users_posts($views) {
            if (!current_user_can('manage_options')) {
                foreach ($views as $key => $data) {
                    if ('mine' !== $key) {
                        unset($views[$key]);
                    }
                }
            }
            return $views;
        }

    }

    new Restrict_User_Content();

endif;

/**
 * 时间日期查询类
 */
class RiPro_Time {

    /**
     * 返回今日开始和结束的时间戳
     *
     * @return array
     */
    public static function today() {

        return [
            mktime(0, 0, 0, date('m'), date('d'), date('Y')),
            mktime(23, 59, 59, date('m'), date('d'), date('Y')),
        ];
    }

    /**
     * 返回昨日开始和结束的时间戳
     *
     * @return array
     */
    public static function yesterday() {
        $yesterday = date('d') - 1;
        return [
            mktime(0, 0, 0, date('m'), $yesterday, date('Y')),
            mktime(23, 59, 59, date('m'), $yesterday, date('Y')),
        ];
    }

    /**
     * 返回本周开始和结束的时间戳
     *
     * @return array
     */
    public static function week() {
        $timestamp = time();
        return [
            mktime(0, 0, 0, date("m"), date("d") - date("w") + 1, date("Y")),
            mktime(23, 59, 59, date("m"), date("d") - date("w") + 7, date("Y")),
        ];
    }

    /**
     * 返回上周开始和结束的时间戳
     *
     * @return array
     */
    public static function lastWeek() {
        $timestamp = time();
        return [
            mktime(0, 0, 0, date("m"), date("d") - date("w") + 1 - 7, date("Y")),
            mktime(23, 59, 59, date("m"), date("d") - date("w") + 7 - 7, date("Y")),
        ];
    }

    /**
     * 返回本月开始和结束的时间戳
     *
     * @return array
     */
    public static function month($everyDay = false) {
        return [
            mktime(0, 0, 0, date('m'), 1, date('Y')),
            mktime(23, 59, 59, date('m'), date('t'), date('Y')),
        ];
    }

    /**
     * 返回上个月开始和结束的时间戳
     *
     * @return array
     */
    public static function lastMonth() {
        $begin = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));
        $end   = mktime(23, 59, 59, date('m') - 1, date('t', $begin), date('Y'));

        return [$begin, $end];
    }

    /**
     * 返回今年开始和结束的时间戳
     *
     * @return array
     */
    public static function year() {
        return [
            mktime(0, 0, 0, 1, 1, date('Y')),
            mktime(23, 59, 59, 12, 31, date('Y')),
        ];
    }

    /**
     * 返回去年开始和结束的时间戳
     *
     * @return array
     */
    public static function lastYear() {
        $year = date('Y') - 1;
        return [
            mktime(0, 0, 0, 1, 1, $year),
            mktime(23, 59, 59, 12, 31, $year),
        ];
    }

    public static function dayOf() {

    }

    /**
     * 获取几天前零点到现在/昨日结束的时间戳
     *
     * @param int $day 天数
     * @param bool $now 返回现在或者昨天结束时间戳
     * @return array
     */
    public static function dayToNow($day = 1, $now = true) {
        $end = time();
        if (!$now) {
            list($foo, $end) = self::yesterday();
        }

        return [
            mktime(0, 0, 0, date('m'), date('d') - $day, date('Y')),
            $end,
        ];
    }

    /**
     * 返回几天前的时间戳
     *
     * @param int $day
     * @return int
     */
    public static function daysAgo($day = 1) {
        $nowTime = time();
        return $nowTime - self::daysToSecond($day);
    }

    /**
     * 返回几天后的时间戳
     *
     * @param int $day
     * @return int
     */
    public static function daysAfter($day = 1) {
        $nowTime = time();
        return $nowTime + self::daysToSecond($day);
    }

    /**
     * 天数转换成秒数
     *
     * @param int $day
     * @return int
     */
    public static function daysToSecond($day = 1) {
        return $day * 86400;
    }

    /**
     * 周数转换成秒数
     *
     * @param int $week
     * @return int
     */
    public static function weekToSecond($week = 1) {
        return self::daysToSecond() * 7 * $week;
    }

    private static function startTimeToEndTime() {

    }
}

/**
 * 自动清理公众号登录缓存
 * @Author   Dadong2g
 * @DateTime 2021-01-16T14:18:15+0800
 * @return   [type]                   [description]
 */
function remov_mpwx_log_run_cron() {
    if (isset($_GET['page']) && $_GET['page'] == 'ripro_v2_pay_order_page') {
        global $wpdb;
        $timestamp = time() - 180;
        return $wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->cao_mpwx_log} WHERE create_time < %s ", $timestamp));
    }
}
add_action('admin_init', 'remov_mpwx_log_run_cron');

/**
 * 后台文章列表钩子定制
 */

function ripro_v2_add_sticky_column($columns) {

    $post_type = get_post_type();
    if ($post_type == 'post') {
        return array_merge($columns, array('cao_price' => '售价', 'cao_vip_rate' => '会员折扣', 'pay_info' => '销售数据'));
    } else {
        return $columns;
    }

}

function ripro_v2_display_posts_stickiness($column, $post_id) {
    switch ($column) {
    case 'cao_price':
        $meta = get_post_meta($post_id, 'cao_price', true);
        if ($meta == '0') {
            echo '免费';
        } else {
            $meta = ($meta == '') ? '' : (float) $meta . site_mycoin('name');
            echo $meta;
        }

        break;
    case 'cao_vip_rate':
        $meta = get_post_meta($post_id, 'cao_vip_rate', true);
        $meta = ($meta == '' || $meta == '1') ? '无' : ($meta * 10) . '折';
        echo $meta;
        break;
    case 'pay_info':
        global $wpdb;

        $data = $wpdb->get_row(
            $wpdb->prepare("SELECT COUNT(post_id) as count,SUM(order_price) as sum_price FROM {$wpdb->cao_order} WHERE post_id = %d AND status = 1 ", $post_id)
        );
        echo sprintf('销量：<b>%s</b><small style="display:block;color: green;">销售额：￥%s</small>',$data->count,sprintf('%0.2f', $data->sum_price));
        break;
    }

}

/**
 * 后台用户列表钩子定制
 */
function ripro_v2_add_user_column($columns) {
    $columns['registered'] = __('注册时间', 'ripro-v2');
    $columns['last_login'] = __('最近登录', 'ripro-v2');
    $columns['vip_type']   = __('会员等级', 'ripro-v2');
    $columns['mycoin']     = __('余额', 'ripro-v2');
    $columns['cao_banned'] = __('状态', 'ripro-v2');
    return $columns;
}

function ripro_v2_output_users_columns($var, $column_name, $user_id) {
    switch ($column_name) {
    case "registered":
        $user = get_userdata($user_id);
        $ip   = get_userdata($user_id);
        return get_date_from_gmt($user->user_registered) . '<br>IP：' . get_user_meta($user_id, 'register_ip', true);
        break;
    case "last_login":
        return get_user_meta($user_id, 'last_login_time', true) . '<br>IP：' . get_user_meta($user_id, 'last_login_ip', true);
        break;
    case "vip_type":
        $vip_type = _get_user_vip_type_badge($user_id, false);
        return $vip_type;
        break;
    case "mycoin":
        return site_mycoin('name') . '：' . get_user_mycoin($user_id);
    case "cao_banned":
        if (!empty(get_user_meta($user_id, 'cao_banned', true))) {
            return esc_html__('封号中', 'ripro-v2');
        } else {
            return esc_html__('正常', 'ripro-v2');
        }
        break;
    }
}

function ripro_v2_users_sortable_columns($sortable_columns) {
    $sortable_columns['registered'] = 'registered';
    return $sortable_columns;
}

function _users_pre_user_query($query) {
    if(!isset($_REQUEST['orderby']) || $_REQUEST['orderby']=='registered' ){
        $order = (isset($_REQUEST['order'])) ? $_REQUEST['order'] : '' ;
        if( !in_array($order,array('asc','desc')) ){
            $order = 'desc';
        }
        $query->query_orderby = "ORDER BY user_registered ".$order."";
    }
}


function ripro_v2_views_users($views) {
    // return $views;
    global $wpdb;
    if (!current_user_can('edit_users')) {
        return $views;
    }

    $opt = site_vip();

    foreach ($opt as $k => $v) {
        if ($k == 'nov') {
            $date = date('Y-m-d', time());

            $sql = "SELECT count(a.ID) FROM $wpdb->users a INNER JOIN $wpdb->usermeta b ON ( a.ID = b.user_id ) WHERE 1=1 AND (  ( b.meta_key = 'cao_user_type' AND b.meta_value != 'vip' )   )";

        } elseif ($k == 'vip') {
            $sql = "SELECT count(a.ID) FROM $wpdb->users a INNER JOIN $wpdb->usermeta b ON ( a.ID = b.user_id ) WHERE 1=1 AND (  ( b.meta_key = 'cao_user_type' AND b.meta_value = 'vip' ) AND ( b.meta_key = 'cao_vip_end_time' AND b.meta_value > '$date' )  )";
            $sql = "SELECT count($wpdb->users.ID)
                    FROM $wpdb->users
                    INNER JOIN $wpdb->usermeta
                    ON ( $wpdb->users.ID = $wpdb->usermeta.user_id )
                    INNER JOIN $wpdb->usermeta AS mt1
                    ON ( $wpdb->users.ID = mt1.user_id )
                    INNER JOIN $wpdb->usermeta AS mt2
                    ON ( $wpdb->users.ID = mt2.user_id )
                    WHERE 1=1
                    AND ( ( $wpdb->usermeta.meta_key = 'cao_user_type'
                    AND $wpdb->usermeta.meta_value = 'vip' )
                    AND ( mt1.meta_key = 'cao_vip_end_time'
                    AND mt1.meta_value > '$date' )
                    AND ( mt2.meta_key = 'cao_vip_end_time'
                    AND mt2.meta_value != '9999-09-09' ) )";

        } elseif ($k == 'boosvip') {
            $sql = "SELECT count($wpdb->users.ID)
                    FROM $wpdb->users
                    INNER JOIN $wpdb->usermeta
                    ON ( $wpdb->users.ID = $wpdb->usermeta.user_id )
                    INNER JOIN $wpdb->usermeta AS mt1
                    ON ( $wpdb->users.ID = mt1.user_id )
                    WHERE 1=1
                    AND ( ( $wpdb->usermeta.meta_key = 'cao_user_type'
                    AND $wpdb->usermeta.meta_value = 'vip' )
                    AND ( mt1.meta_key = 'cao_vip_end_time'
                    AND mt1.meta_value = '9999-09-09' ) )";
        }

        $count              = $wpdb->get_var($sql);
        $views['vip_' . $k] = '<a href="' . admin_url('users.php') . '?vip_type=' . $k . '">' . $v['name'] . '<span class="count">（' . $count . '）</span></a>';

    }
    return $views;
}

function ripro_v2_filter_users($query) {
    global $pagenow, $wpdb;
    $vip_type = (empty($_GET['vip_type'])) ? false : trim($_GET['vip_type']);
    if (is_admin() && 'users.php' == $pagenow && $vip_type) {

        if ($vip_type == 'nov') {
            $meta_query = array(
                'relation' => 'AND',
                array(
                    'key'     => 'cao_user_type',
                    'value'   => 'vip',
                    'compare' => '!=',
                ),
            );
        } elseif ($vip_type == 'vip') {
            $meta_query = array(
                'relation' => 'AND',
                array(
                    'key'     => 'cao_user_type',
                    'value'   => 'vip',
                    'compare' => '==',
                ),
                array(
                    'key'     => 'cao_vip_end_time',
                    'value'   => date('Y-m-d', time()),
                    'compare' => '>',
                ),
                array(
                    'key'     => 'cao_vip_end_time',
                    'value'   => '9999-09-09',
                    'compare' => '!=',
                ),
            );
        } elseif ($vip_type == 'boosvip') {
            $meta_query = array(
                'relation' => 'AND',
                array(
                    'key'     => 'cao_user_type',
                    'value'   => 'vip',
                    'compare' => '==',
                ),
                array(
                    'key'     => 'cao_vip_end_time',
                    'value'   => '9999-09-09',
                    'compare' => '==',
                ),
            );
        }

        $query->set('meta_query', $meta_query);
    }
    return $query;
}

if (!is_close_site_shop()) {
    add_filter('manage_posts_columns', 'ripro_v2_add_sticky_column');
    add_action('manage_posts_custom_column', 'ripro_v2_display_posts_stickiness', 10, 2);
    add_filter('manage_users_columns', 'ripro_v2_add_user_column');
    add_action('manage_users_custom_column', 'ripro_v2_output_users_columns', 10, 3);
    add_filter("manage_users_sortable_columns", 'ripro_v2_users_sortable_columns');
    add_filter('views_users', 'ripro_v2_views_users');
    add_action('pre_get_users', 'ripro_v2_filter_users');
    add_action('pre_user_query','_users_pre_user_query');
}

///////////////////////////// RITHEME.COM END ///////////////////////////
