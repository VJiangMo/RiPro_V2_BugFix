<?php

///////////////////////////// RITHEME.COM END ///////////////////////////

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * ripro-v2 functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package ripro-v2
 */

if (!defined('_RI_VERSION')) {
    // Replace the version number of the theme on each release.
    define('_RI_VERSION', '4.8.0');
}

//调试模式显示错误日志信息
if (defined('WP_DEBUG') && WP_DEBUG == true) {
    error_reporting(E_ALL);
} else {
    error_reporting(0);
}

/**
 * DB
 */
global $wpdb, $table_prefix;
$ripro_db = array(
    'cao_order', //订单表
    'cao_paylog', //旧版本购买记录表
    'cao_coupon', //卡密表名称
    'cao_ref_log', //推广记录表
    'cao_down_log', //下载记录表
    'cao_mpwx_log', //微信公众号登录记录表
);
foreach ($ripro_db as $name) {
    $wpdb->$name    = $table_prefix . $name;
    $wpdb->tables[] = $name;
}

///////////////////// init ripro theme ///////////////////////////
if (!function_exists('ripro_v2_setup')):

    function ripro_v2_setup() {

        // 第一启用主题时候插入订单表
        $the_theme_status = get_option('ripro_v2_theme_setup_status_2');
        if (empty($the_theme_status) && extension_loaded('swoole_loader')) {
            $RiClass = new RiClass();
            $RiClass->setup_db();
            ripro_v2_setup_theme_page(true);
            update_option('ripro_v2_theme_setup_status_2', '1');
        }

        //启用主题后删除主题包zip文件，防止被人恶意打包下载

        if (file_exists($theme_zip = dirname(dirname(__FILE__)) . '/ripro-v2.zip')) {
            @unlink($theme_zip);
        }

        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on ripro-v2, use a find and replace
         * to change 'ripro-v2' to the name of your theme in all the template files.
         */
        load_theme_textdomain('ripro-v2', get_template_directory() . '/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        // add link manager // 开启友情链接功能
        add_filter('pre_option_link_manager_enabled', '__return_true');

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');
        // add_theme_support('post-formats', array('video','audio','gallery'));
        add_theme_support('post-formats', array('video', 'image'));
        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */

        add_theme_support('post-thumbnails');

        // This theme uses wp_nav_menu() in one location.
        register_nav_menus(
            array(
                'menu-1' => esc_html__('顶部主菜单', 'ripro-v2'),
            )
        );

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support(
            'html5',
            array(
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
                'style',
                'script',
            )
        );

        // Add theme support for selective refresh for widgets.
        add_theme_support('customize-selective-refresh-widgets');

    }
endif;
add_action('after_setup_theme', 'ripro_v2_setup');

/**
 * 初始化主题必备的页面
 * @Author   Dadong2g
 * @DateTime 2021-04-11T20:17:23+0800
 * @return   [type]                   [description]
 */
function ripro_v2_setup_theme_page($activate = false) {

    if (!$activate && (!isset($_GET['post_type']) || $_GET['post_type'] != 'page')) {
        return;
    }

    // 仅在删除后台页面时触发
    $init_pages = array(
        'pages/page-user.php'      => array(esc_html__('个人中心', 'ripro-v2'), 'user'),
        'pages/page-tags.php'      => array(esc_html__('标签云', 'ripro-v2'), 'tags'),
        'pages/page-container.php' => array(esc_html__('空白页面', 'ripro-v2'), 'container'),
        'pages/page-links.php'     => array(esc_html__('网址导航', 'ripro-v2'), 'links'),
        'pages/page-login.php'     => array(esc_html__('找回密码', 'ripro-v2'), 'login'),
        'pages/page-modular.php'   => array(esc_html__('模块化布局页面', 'ripro-v2'), 'modular'),
        'pages/page-series.php'    => array(esc_html__('专题列表', 'ripro-v2'), 'series'),
    );
    foreach ($init_pages as $template => $item) {
        $page = array(
            'post_title'  => $item[0],
            'post_name'   => $item[1],
            'post_status' => 'publish',
            'post_type'   => 'page',
            'post_author' => 1,
        );
        $page_check = get_page_by_title($item[0]);
        if (!isset($page_check->ID)) {
            $page_id = wp_insert_post($page);
            update_post_meta($page_id, '_wp_page_template', $template);
        }
    }
    //重写固定连接规则
    flush_rewrite_rules(false);

}

add_action('admin_init', 'ripro_v2_setup_theme_page');

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function ripro_v2_widgets_init() {

    register_sidebar(array(
        'name'          => esc_html__('首页模块化布局', 'ripro-v2'),
        'id'            => 'modules',
        'description'   => esc_html__('添加首页模块化布局', 'ripro-v2'),
        'before_widget' => '<div id="%1$s" class="section %2$s"><div class="container">',
        'after_widget'  => '</div></div>',
        'before_title'  => '<h3 class="section-title"><span>',
        'after_title'   => '</span></h3>',
    ));

    // custom_modular_pages
    $custom_modular_pages = _cao('custom_modular_pages', array());
    if (!empty($custom_modular_pages)) {
        foreach ($custom_modular_pages as $value) {
            if (!empty(get_permalink($value['page_id'])) && get_post_meta($value['page_id'], '_wp_page_template', 1) == 'pages/page-modular.php') {
                register_sidebar(array(
                    'name'          => get_the_title($value['page_id']),
                    'id'            => strtolower($value['widget_name']),
                    'description'   => esc_html__('添加模块化布局', 'ripro-v2'),
                    'before_widget' => '<div id="%1$s" class="section %2$s"><div class="container">',
                    'after_widget'  => '</div></div>',
                    'before_title'  => '<h3 class="section-title"><span>',
                    'after_title'   => '</span></h3>',
                ));
            }
        }
    }

    $sidebars = array(
        'sidebar'      => esc_html__('文章页侧边栏', 'ripro-v2'),
        'cat_sidebar'  => esc_html__('分类页侧边栏', 'ripro-v2'),
        'page_sidebar' => esc_html__('页面侧边栏', 'ripro-v2'),
        'footer'       => esc_html__('网站底部边栏', 'ripro-v2'),
    );

    foreach ($sidebars as $key => $value) {

        register_sidebar(
            array(
                'name'          => $value,
                'id'            => $key,
                'description'   => esc_html__('添加小工具到这里', 'ripro-v2'),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h5 class="widget-title">',
                'after_title'   => '</h5>',
            )
        );
    }

}
add_action('widgets_init', 'ripro_v2_widgets_init');

// Require Composer's autoloading file
// if it's present in theme directory.
if (file_exists($composer = get_template_directory() . '/vendor/autoload.php')) {
    require_once $composer;
}

/**
 * 加载依赖文件库
 * @var array
 */
$ripro_v2_includes = array(
    '/inc/template-shop.php',
    '/inc/template-framework.php',
    '/inc/template-clean.php',
    '/inc/template-tags.php',
    '/inc/template-filter.php',
    '/inc/template-enqueue.php',
    '/inc/template-navwalker.php',
    '/inc/template-ajax.php',
    '/inc/template-admin.php',
    '/inc/class/pay.xh.class.php',
);

/**
 * 扩张安装帮助页面
 */
if (extension_loaded('swoole_loader')) {
    $php_v = substr(PHP_VERSION, 0, 3);

    if ($php_v >= '7.4') {
        $ripro_v2_includes[] = '/inc/class/pay.class.' . $php_v . '.php';
    } else {
        wp_die('<small>ripro-v2需要php7.4及以上版本支持，鉴于WordPress官方推荐PHP为最新7.4版本，推荐使用php7.4性能极佳，使用ripro-v2性能更佳，如您的PHP版本不支持，请去FTP或者文件管理删除 \wp-content\themes\ripro-v2\ 主题目录即可恢复网站。</small>', 'php版本过低提示');exit;
    }

} else {
    wp_redirect(get_template_directory_uri() . '/help/swoole-compiler-loader.php');exit;
}

/**
 * Include files
 * @var [type]
 */

foreach ($ripro_v2_includes as $file) {
    require_once get_template_directory() . $file;
}

///////////////////////////// RITHEME.COM END ///////////////////////////
