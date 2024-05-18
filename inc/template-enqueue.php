<?php

///////////////////////////// RITHEME.COM END ///////////////////////////

defined('ABSPATH') || exit;

/**
 * 网站静态脚本样式统一调用
 * @Author   Dadong2g
 * @DateTime 2021-01-16T14:05:29+0800
 * @return   [type]                   [description]
 */
function ripro_v2_scripts() {
    // Get the theme data.
    $the_theme   = wp_get_theme();
    $theme_dir   = get_template_directory_uri() . '/assets';
    $css_version = $the_theme->get('Version');
    if (!is_admin()) {

        // 去掉wp自带jquery 加载新版本jquery
        wp_deregister_script('jquery');
        wp_enqueue_script('jquery', $theme_dir . '/js/jquery.min.js', array(), '3.5.1', false);

        //bootstrap  bootstrap-4.6.0
        wp_enqueue_style('bootstrap', $theme_dir . '/bootstrap/css/bootstrap.min.css', array(), '4.6.0');
        wp_enqueue_script('bootstrap', $theme_dir . '/bootstrap/js/bootstrap.min.js', array('jquery', 'popper'), '4.6.0', true);

        // Font awesome 4 and 5 loader jsdelivr加速
       
        switch (_cao('font_awesome_mod','theme')) {
            case 'theme':
                $fw_src = $theme_dir . '/font-awesome/css/all.min.css';
                $fwshims_src = $theme_dir . '/font-awesome/css/v4-shims.min.css';
                break;
            case 'jsdelivr':
                $fw_src = 'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.1/css/all.min.css';
                $fwshims_src = 'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.1/css/v4-shims.min.css';
                break;
            case 'bootcdn':
                $fw_src = 'https://cdn.bootcdn.net/ajax/libs/font-awesome/5.15.1/css/all.min.css';
                $fwshims_src = 'https://cdn.bootcdn.net/ajax/libs/font-awesome/5.15.1/css/v4-shims.min.css';
                break;
            default:
                break;
        }
        
        if (!empty($fw_src) && !empty($fwshims_src)) {
            wp_enqueue_style('csf-fa5', $fw_src, array(), '5.14.0', 'all');
            wp_enqueue_style('csf-fa5-v4-shims', $fwshims_src, array(), '5.14.0', 'all');
        }

        // wp-block-library
        if (_cao('disable_gutenberg_widgets', false) || _cao('disable_gutenberg_edit', false)) {
            wp_enqueue_style('wp-block-library');
        }

        //plugins
        wp_enqueue_style('plugins', $theme_dir . '/css/plugins.css', array('bootstrap'), '1.0.0');
        wp_enqueue_style('app', $theme_dir . '/css/app.css', array('bootstrap', 'plugins'), $css_version);
        wp_enqueue_style('dark', $theme_dir . '/css/dark.css', array('bootstrap', 'plugins', 'app'), $css_version);

        // DPlayer 按需调用
        wp_register_script('hls', $theme_dir . '/DPlayer/hls.js', array('jquery', 'app'), '', true);
        wp_register_script('dplayer', $theme_dir . '/DPlayer/DPlayer.min.js', array('hls'), '', true);

        //文章页面js
        if (is_singular() && !is_page_template_modular()) {

            wp_enqueue_script('spotlight', $theme_dir . '/spotlight/spotlight.bundle.js', array('app'), '0.7.0', true);

            // if ( has_shortcode( $post->post_content, 'gallery' ) ) {
            // }

        }

        // jarallax
        if (is_page_template_modular() || is_singular()) {
            wp_enqueue_script('jarallax', $theme_dir . '/jarallax/jarallax.min.js', array('jquery'), '1.12.5', true);
            wp_enqueue_script('jarallax-video', $theme_dir . '/jarallax/jarallax-video.min.js', array('jarallax'), '1.0.1', true);
        }

        //plugins
        wp_enqueue_script('plugins', $theme_dir . '/js/plugins.js', array('jquery'), $css_version, true);
        //popper.min
        wp_enqueue_script('popper', $theme_dir . '/js/popper.min.js', array('jquery'), $css_version, true);
        //TCaptcha 007
        if (_cao('is_qq_007_captcha')) {
            wp_enqueue_script('captcha', 'https://ssl.captcha.qq.com/TCaptcha.js', array('jquery', 'plugins'), '', true);
        }
        //site appjs
        wp_enqueue_script('app', $theme_dir . '/js/app.js', array('jquery', 'plugins'), $css_version, true);

        //clipboard
        wp_register_script('clipboard', $theme_dir . '/js/clipboard.min.js', array('jquery'), '2.0.6', true);

    }

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    $script_params = apply_filters('ripro_v2_script_params', array(
        'home_url'           => esc_url(home_url()),
        'admin_url'          => esc_url(admin_url('admin-ajax.php')),
        'is_qq_captcha'      => (int) _cao('is_qq_007_captcha', '1'),
        'is_single_gallery'  => (int) _cao('is_single_gallery', '1'),
        'comment_list_order' => get_option('comment_order'),
        'infinite_load'      => apply_filters('ripro_v2_infinite_button_load', esc_html__('加载更多', 'ripro-v2')),
        'infinite_loading'   => apply_filters('ripro_v2_infinite_button_load', esc_html__('加载中...', 'ripro-v2')),
        'site_notice'        => array('is' => _cao('is_site_notify', '0'), 'auto' => _cao('is_site_auto_notify', '1'), 'color' => _cao('site_notify_color', 'rgb(33, 150, 243)'), 'html' => '<div class="notify-content"><h3><i class="fa fa-bell-o mr-2"></i>' . _cao('site_notify_title', '') . '</h3><div>' . _cao('site_notify_desc', '') . '</div></div>'),
        'site_js_text'       => array(
            'login_txt'    => esc_html__('请点击安全验证', 'ripro-v2'),
            'reg1_txt'     => esc_html__('邮箱格式错误', 'ripro-v2'),
            'reg2_txt'     => esc_html__('请点击安全验证', 'ripro-v2'),
            'reg3_txt'    => esc_html__('用户名必须是英文', 'ripro-v2'),
            'pass_txt'     => esc_html__('请点击安全验证', 'ripro-v2'),
            'bind_txt'     => esc_html__('请点击验证按钮进行验证', 'ripro-v2'),
            'copy_txt'     => esc_html__(' 复制成功', 'ripro-v2'),
            'poster_txt'   => esc_html__('海报加载异常', 'ripro-v2'),
            'mpwx1_txt'    => esc_html__('请使用微信扫码登录', 'ripro-v2'),
            'mpwx2_txt'    => __('关注公众号即可登录</br>二维码有效期3分钟', 'ripro-v2'),
            'pay1_txt'     => esc_html__('支付完成', 'ripro-v2'),
            'pay2_txt'     => esc_html__('取消支付', 'ripro-v2'),
            'pay3_txt'     => esc_html__('支付成功', 'ripro-v2'),
            'capt_txt'     => esc_html__('验证中', 'ripro-v2'),
            'capt1_txt'    => esc_html__('验证通过', 'ripro-v2'),
            'capt2_txt'    => esc_html__('验证失败', 'ripro-v2'),
            'prompt_txt'   => esc_html__('请输入图片URL地址', 'ripro-v2'),
            'comment_txt'  => esc_html__('提交中....', 'ripro-v2'),
            'comment1_txt' => esc_html__('提交成功', 'ripro-v2'),
        ),

        // riprov2.site_js_text.key
    ));

    if (!is_close_site_shop()) {
        $script_params['pay_type_html'] = _ripro_get_pay_type_html();
    }
    if (is_singular()) {
        global $post;
        $script_params['singular_id'] = $post->ID;
    }

    wp_localize_script('app', 'riprov2', $script_params);

}
add_action('wp_enqueue_scripts', 'ripro_v2_scripts');

/**
 * 管理页面CSS
 * @Author   Dadong2g
 * @DateTime 2021-01-16T14:10:46+0800
 * @return   [type]                   [description]
 */
function caoAdminScripts() {
    if (isset($_GET['page']) && strpos($_GET['page'], 'ripro_v2') !== false) {
        wp_enqueue_style('rizhuti-v2-admin', get_template_directory_uri() . '/assets' . '/css/admin.css', array(), '1.0');
    }

    if (isset($_GET['page']) && strpos($_GET['page'], 'ripro-v2') !== false) {
        wp_enqueue_style('admin-opt', get_template_directory_uri() . '/assets/css/admin-opt.css', array(), '1.0');
        wp_enqueue_script('admin-opt', get_template_directory_uri() . '/assets/js/admin-opt.js?v=4.8', array('jquery'),'1.0', true);
        wp_localize_script('admin-opt', 'ri_opt_js', array(
            'home_url'  => home_url(),
            'admin_url' => admin_url('admin-ajax.php'),
        ));
    }

    
}
add_action('admin_enqueue_scripts', 'caoAdminScripts');

///////////////////////////// RITHEME.COM END ///////////////////////////
