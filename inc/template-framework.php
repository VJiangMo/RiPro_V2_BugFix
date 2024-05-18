<?php

///////////////////////////// RITHEME.COM END ///////////////////////////

defined('ABSPATH') || exit;

if (!defined('_OPTIONS_PRE')) {
    // Replace the version number of the theme on each release.
    define('_OPTIONS_PRE', '_riprov2_options');
}

/**
 * Custom function for get an option
 */
if (!function_exists('_cao')) {
    function _cao($option = '', $default = null) {
        $options_meta = _OPTIONS_PRE;
        $options      = get_option($options_meta);
        return (isset($options[$option])) ? $options[$option] : $default;
    }
}

if (!function_exists('_cao_old')) {
    function _cao_old($option = '', $default = null) {
        $options = get_option('_caozhuti_options'); // 旧版本主题中数据设置
        return (isset($options[$option])) ? $options[$option] : $default;
    }
}

if (true || !class_exists('CSF')) {
    $theme_inc_file_path = get_template_directory() . '/inc';
    $options             = array(
        '/codestar-framework/codestar-framework.php', //core
        '/codestar-framework/classes/init.class.php',
        '/options/admin-options.php', //admin
        '/options/metabox-options.php', //metabox
        '/options/nav-menu-options.php', //nav
        '/options/profile-options.php', //profile
        '/options/shortcode-options.php', //shortcode
        '/options/taxonomy-options.php', //taxonomy
        '/options/widget-options.php', //widget
        '/options/shop-widget-options.php', //shop widget
    );
    foreach ($options as $option) {
        require_once $theme_inc_file_path . $option;
    }
}

/**
 * 主题设置初始化
 * @Author   Dadong2g
 * @DateTime 2021-01-16T14:29:56+0800
 * @param    [type]                   $params [description]
 * @return   [type]                           [description]
 */
function ripro_option_init($params) {
    $theme = wp_get_theme('ripro-v2');
    if ($theme->exists()) {
        $version = $theme->get('Version');
    } else {
        $version = _the_theme_version();
    }
    $params['framework_title'] = 'RiPro-V2 主题设置 <small>正式版 V' . $version . '</small>';
    $params['menu_title']      = '主题设置';
    $params['theme']           = 'light'; //  light OR dark
    $params['show_bar_menu']   = false;
    $params['enqueue_webfont'] = false;
    $params['enqueue']         = false;
    $params['show_search']     = false;
    $params['ajax_save']       = false; //关闭AJAX获取选项
    $params['footer_credit']   = '感谢使用RiPro-V2进行内容创作，本主题来自<a href="https://ritheme.com/" target="_blank">RiTheme.com</a>';
    return $params;
}
add_filter('csf_' . _OPTIONS_PRE . '_args', 'ripro_option_init');

///////////////////////////// RITHEME.COM END ///////////////////////////
