<?php if (!defined('ABSPATH')) {die;} // Cannot access directly.

// Control core classes for avoid errors
if (class_exists('CSF')) {
    $prefix = '_ripro_v2_shortcodes';

    if (true && !is_close_site_shop()) {
        CSF::createShortcoder($prefix, array(
            'button_title'   => '添加付费隐藏内容',
            'select_title'   => '选择添加的内容块',
            'insert_title'   => '插入到文章',
            'show_in_editor' => true,
            'gutenberg'      => array(
                'title'       => 'Ri简码组件',
                'description' => 'Ri简码组件',
                'icon'        => 'screenoptions',
                'category'    => 'widgets',
                'keywords'    => array('shortcode', 'csf', 'insert'),
                'placeholder' => '在此处编写Ri简码...',
            ),
        ));

        CSF::createSection($prefix, array(
            'title'     => '隐藏部分付费内容[rihide]',
            'view'      => 'normal',
            'shortcode' => 'rihide',
            'fields'    => array(

                array(
                    'id'    => 'content',
                    'type'  => 'wp_editor',
                    'title' => '',
                    'desc'  => '[rihide]隐藏部分付费内容[/rihide] <br/> 注意：添加隐藏内容后，因为公用价格和折扣字段，所有资源类型优先为付费查看内容模式，侧边栏下载资源小工具将不显示',
                ),

            ),
        ));

    }
}

/**
 * 付费查看部分内容
 * @Author   Dadong2g
 * @DateTime 2021-01-16T14:14:41+0800
 * @param    [type]                   $atts    [description]
 * @param    string                   $content [description]
 * @return   [type]                            [description]
 */
function rizhuti_v2_hide_shortcode($atts, $content = '') {
    // 付费资源信息
    if (is_close_site_shop()) {
        return '';
    }
    global $post, $current_user;
    $user_id     = $current_user->ID; //用户ID
    $post_id     = $post->ID; //文章ID
    $click_nonce = wp_create_nonce('rizhuti_click_' . $post_id);
    // 付费资源信息 //是否购买
    $RiClass        = new RiClass($post_id, $user_id);
    $IS_PAID        = $RiClass->is_pay_post();
    $the_user_type  = _get_user_vip_type($user_id);
    $the_post_price = get_post_price($post_id, $the_user_type);

    //业务逻辑
    // 显示原始价格

    $_content = '<div class="ripay-content card mb-4">';
    $_content .= '<div class="card-body">';
    $_content .= '<span class="badge badge-info-lighten"><i class="fas fa-lock mr-1"></i> ' . esc_html__('隐藏内容', 'ripro-v2') . '</span>';

    if ($IS_PAID == 0) {
        $_content .= '<div class="d-flex justify-content-center">';
        $_content .= '<div class="text-center mb-4">';
        # 未购买..
        ob_start();
        echo '<p class="text-muted m-0">' . esc_html__('此处内容需要权限查看', 'ripro-v2') . '</p>';
        the_post_shop_priceo_options($post_id);
        $_content .= ob_get_clean();
        if ($the_user_type == 'nov' && $the_post_price == -1) {
            $_content .= '<button type="button" class="btn btn-danger btn-sm" disabled>' . esc_html__('暂无购买权限', 'ripro-v2') . '</button>';
        } elseif (empty($user_id) && !is_site_nologin_pay()) {
            $_content .= '<button type="button" class="btn btn-dark btn-sm login-btn">' . esc_html__('登录后购买', 'ripro-v2') . '</button>';
        } else {
            $_content .= '<button type="button" class="btn btn-dark btn-sm click-pay-post" data-postid="' . $post_id . '" data-nonce="' . $click_nonce . '" data-price="' . $the_post_price . '"><i class="' . site_mycoin('icon') . '"></i> ' . esc_html__('购买本内容', 'ripro-v2') . '</button>';
            if (get_post_vip_rate($post_id, 'vip') == 0 && _get_user_vip_type($user_id) == 'nov') {
                $site_vip = site_vip();
                $_content .= '<a href="' . get_user_page_url('vip') . '" rel="nofollow noopener noreferrer" class="btn btn-primary btn-sm ml-2"><i class="fa fa-diamond"></i> ' . $site_vip['vip']['name'] . esc_html__('免费查看', 'ripro-v2') . '</a>';
            }
        }

        $_content .= '</div>';
        $_content .= '</div>';
    } elseif ($IS_PAID == 3) {
        # 免费资源...
        if (empty($user_id) && !is_site_nologin_pay()) {
            $_content .= '<button type="button" class="btn btn-light btn-sm login-btn">' . esc_html__('登录后免费查看', 'ripro-v2') . '</button>';
        } else {
            $_content .= '<div>' . do_shortcode($content) . '</div>';
        }
    } elseif ($IS_PAID > 0) {
        # 已购买...
        $_content .= '<div>' . do_shortcode($content) . '</div>';
    }

    $_content .= '</div>';
    $_content .= '</div>';

    // END

    return do_shortcode($_content);

}
add_shortcode('rihide', 'rizhuti_v2_hide_shortcode');

// 添加内容组件 简码组件
if (class_exists('CSF')) {

    //
    // Set a unique slug-like ID
    $prefix = '_ripro_v2_shortcodes_other';

    //
    // Create a shortcoder
    CSF::createShortcoder($prefix, array(
        'button_title'   => '添加内容组件',
        'select_title'   => '选择要添加的组件',
        'insert_title'   => '插入到文章',
        'show_in_editor' => true,
        'gutenberg'      => array(
            'title'       => 'Ri内容组件',
            'description' => 'Ri内容组件',
            'icon'        => 'screenoptions',
            'category'    => 'widgets',
            'keywords'    => array('shortcode', 'csf', 'insert'),
            'placeholder' => '在此处编写简码...',
        ),
    ));

    //评论后可见内容
    CSF::createSection($prefix, array(
        'title'     => '评论后可见内容',
        'view'      => 'normal',
        'shortcode' => 'ri-reply-hide',
        'fields'    => array(
            array(
                'id'    => 'content',
                'type'  => 'wp_editor',
                'title' => '',
                'desc'  => '插入评论后可见的隐藏内容',
            ),
        ),
    ));

    //登录后可见内容
    CSF::createSection($prefix, array(
        'title'     => '登录后可见内容',
        'view'      => 'normal',
        'shortcode' => 'ri-login-hide',
        'fields'    => array(
            array(
                'id'    => 'content',
                'type'  => 'wp_editor',
                'title' => '',
                'desc'  => '插入登录后可见的隐藏内容',
            ),
        ),
    ));

    // 站内其他文章 order-first
    CSF::createSection($prefix, array(
        'title'     => '站内其他文章',
        'view'      => 'normal',
        'shortcode' => 'ri-post',
        'fields'    => array(
            array(
                'id'          => 'id',
                'type'        => 'select',
                'title'       => '选择文章',
                'placeholder' => '输入文章标题关键词搜索插入',
                'chosen'      => true,
                'ajax'        => true,
                'multiple'    => false,
                'sortable'    => true,
                'options'     => 'posts',
            ),

            array(
                'id'      => 'thumb',
                'type'    => 'radio',
                'title'   => '缩略图',
                'options' => array(
                    'left'  => '左侧显示',
                    'none'  => '不显示',
                    'right' => '右侧显示',
                ),
                'inline'  => true,
                'default' => 'left',
            ),

        ),
    ));

    //DPlayer播放器
    CSF::createSection($prefix, array(
        'title'     => 'DPlayer视频播放器',
        'view'      => 'normal',
        'shortcode' => 'ri-video',
        'fields'    => array(

            array(
                'id'      => 'autoplay',
                'type'    => 'checkbox',
                'title'   => '视频自动播放',
                'label'   => '',
                'default' => false,
            ),
            array(
                'id'      => 'theme',
                'type'    => 'color',
                'title'   => '播放器主题色',
                'default' => '#b7daff',
            ),

            array(
                'id'      => 'logo',
                'type'    => 'checkbox',
                'title'   => '网站LOGO',
                'label'   => '在左上角展示网站logo',
                'default' => true,
            ),

            array(
                'id'      => 'url',
                'type'    => 'upload',
                'title'   => '视频地址',
                'desc'    => '内置DPlayer播放器，只支持视频真实播放地址，支付mp4,m3u8等格式',
                'default' => '',
            ),

            array(
                'id'      => 'pic',
                'type'    => 'upload',
                'title'   => '视频封面',
                'desc'    => '视频封面,不上传不显示,推荐16:9的封面图，740x420',
                'default' => '',
            ),

        ),
    ));

    //图片灯箱相册
    // CSF::createSection($prefix, array(
    //     'title'     => '图片灯箱相册',
    //     'view'      => 'normal',
    //     'shortcode' => 'ripro-gallery',
    //     'fields'    => array(

    //         array(
    //             'id'        => 'id',
    //             'type'      => 'gallery',
    //             'title'     => '选择图片',
    //             'add_title' => '上传图片',
    //             'desc'      => '',
    //         ),

    //     ),
    // ));

    

    //自定义按钮
    CSF::createSection($prefix, array(
        'title'     => '按钮',
        'view'      => 'normal',
        'shortcode' => 'ri-buttons',
        'fields'    => array(
            array(
                'id'      => 'size',
                'type'    => 'radio',
                'title'   => '大小',
                'options' => array(
                    'btn-sm' => '小',
                    ''       => '常规',
                    'btn-lg' => '大',
                ),
                'inline'  => true,
                'default' => '',
            ),
            array(
                'id'      => 'color',
                'type'    => 'radio',
                'title'   => '颜色',
                'inline'  => true,
                'options' => array(
                    'primary'   => '蓝',
                    'info'      => '浅蓝',
                    'success'   => '绿',
                    'danger'    => '红',
                    'warning'   => '黄',
                    'secondary' => '灰',
                    'light'     => '浅灰',
                    'dark'      => '黑',
                ),
                'default' => 'primary',
            ),
            array(
                'id'      => 'outline',
                'type'    => 'checkbox',
                'title'   => '边框',
                'label'   => '按钮显示风格为边框模式',
                'default' => false,
            ),
            array(
                'id'      => 'href',
                'type'    => 'text',
                'title'   => '链接',
                'default' => '#',
            ),
            array(
                'id'      => 'blank',
                'type'    => 'checkbox',
                'title'   => '新窗口打开',
                'default' => false,
            ),
            array(
                'id'      => 'content',
                'type'    => 'text',
                'title'   => '名称',
                'default' => '这是按钮',
            ),

        ),
    ));

    //  警告框（Alerts）
    CSF::createSection($prefix, array(
        'title'     => '提示框',
        'view'      => 'normal',
        'shortcode' => 'ri-alerts',
        'fields'    => array(

            array(
                'id'      => 'color',
                'type'    => 'radio',
                'title'   => '颜色',
                'inline'  => true,
                'options' => array(
                    'primary'   => '蓝',
                    'info'      => '浅蓝',
                    'success'   => '绿',
                    'danger'    => '红',
                    'warning'   => '黄',
                    'secondary' => '灰',
                    'light'     => '浅灰',
                    'dark'      => '黑',
                ),
                'default' => 'primary',
            ),
            array(
                'id'      => 'close',
                'type'    => 'checkbox',
                'title'   => '显示关闭按钮',
                'default' => false,
            ),
            array(
                'id'       => 'content',
                'type'     => 'textarea',
                'title'    => '内容',
                'sanitize' => false,
                'default'  => '这是一条醒目的提示消息',
                'desc'     => '可以插入html代码，例如：' . esc_html('<h4 class="alert-heading">hello ripro-v2!</h4>这是一条醒目的提示消息'),
            ),
        ),
    ));

    //折叠内容 accordion
    CSF::createSection($prefix, array(
        'title'           => '折叠内容',
        'view'            => 'repeater',
        'shortcode'       => 'ri-accordions',
        'fields'    => array(
            array(
                'id'       => 'title',
                'type'     => 'text',
                'title'    => '标题',
                'default'  => '点击展开',
                'desc'     => '',
            ),
            array(
                'id'      => 'show',
                'type'    => 'checkbox',
                'title'   => '默认展开内容',
                'default' => false,
            ),
            array(
                'id'       => 'content',
                'type'     => 'textarea',
                'title'    => '内容',
                'sanitize' => false,
                'default'  => '这里显示的是一条折叠内容...',
                'desc'     => '可以插入html代码',
            ),

        ),

    ));
}

//评论可见
function ripro_reply_hide_shortcode($atts, $content = '') {

    $notice = '<div class="card text-center border-danger mb-4"><div class="card-body"> <h5 class="card-title">' . __('***此处内容评论后可见***', 'ripro-v2') . '</h5> <p class="card-text"><small class="text-muted">' . esc_html__('温馨提示：此处为隐藏内容，需要评论或回复留言后可见', 'ripro-v2') . '</small></p> <a href="#respond" class="btn btn-secondary btn-sm"><i class="fa fa-comments-o"></i> ' . esc_html__('评论查看', 'ripro-v2') . '</a> </div> </div>';

    $content = '<div class="card border-shortcode mb-4"><div class="card-body">' . do_shortcode($content) . '</div></div>';

    $user_id = is_user_logged_in() ? get_current_user_id() : 0;
    $email   = null;

    if ($user_id > 0) {
        $user  = get_userdata($user_id);
        $email = $user->user_email;
        //管理员直接可见
        // if (!empty($user->roles) && in_array('administrator', $user->roles)) {
        //     return $content;
        // }
    } else if (isset($_COOKIE['comment_author_email_' . COOKIEHASH])) {
        $email = str_replace('%40', '@', $_COOKIE['comment_author_email_' . COOKIEHASH]);
    } else {
        return $notice;
    }

    if (empty($email)) {
        return $notice;
    }

    global $wpdb;
    $post_id = get_the_ID();
    $query   = "SELECT `comment_ID` FROM {$wpdb->comments} WHERE `comment_post_ID`={$post_id} and `comment_approved`='1' and `comment_author_email`='{$email}' LIMIT 1";
    if ($wpdb->get_results($query)) {
        return do_shortcode($content);
    } else {
        return $notice;
    }

}
add_shortcode('ri-reply-hide', 'ripro_reply_hide_shortcode');

//登录可见
function ripro_login_hide_shortcode($atts, $content = '') {

    $notice = '<div class="card text-center border-danger mb-4"><div class="card-body"> <h5 class="card-title">' . __('***此处内容登录后可见***', 'ripro-v2') . '</h5> <p class="card-text"><small class="text-muted">' . esc_html__('温馨提示：此处为隐藏内容，需要登录后可见', 'ripro-v2') . '</small></p> <a href="javascript:;" class="btn btn-warning btn-sm login-btn"><i class="fa fa-user mr-1"></i> ' . esc_html__('登录查看', 'ripro-v2') . '</a> </div> </div>';

    $content = '<div class="card border-shortcode mb-4"><div class="card-body">' . do_shortcode($content) . '</div></div>';

    $user_id = is_user_logged_in() ? get_current_user_id() : 0;

    if (is_user_logged_in()) {
        return do_shortcode($content);
    } else {
        return $notice;
    }

}
add_shortcode('ri-login-hide', 'ripro_login_hide_shortcode');

//其他文章
function ripro_post_shortcode($atts) {
    $atts = shortcode_atts(array(
        'id'    => 0,
        'thumb' => 'left',
    ), $atts);

    $post_id = (int) $atts['id'];

    if (empty($post_id) || get_post_status($post_id) != 'publish') {
        return;
    }

    $html = '<div class="row no-gutters mb-4" style="border-radius: 4px;overflow: hidden;background-color: rgb(118 118 118 / 10%);">';

    if (!empty($atts['thumb']) && $atts['thumb'] != 'none') {
        $html .= '<div class="col-md-4">';
        $html .= _get_post_media($post_id, 'thumbnail');
        $html .= '</div>';
        if ($atts['thumb'] == 'right') {
            $classes = 'col-md-8 order-first';
        } else {
            $classes = 'col-md-8';
        }
    } else {
        $classes = 'col-md-12';
    }

    $html .= '<div class="' . $classes . '"><div class="card-body">';
    $html .= '<h5 class="card-title"><a' . _target_blank() . ' href="' . esc_url(get_permalink($post_id)) . '" title="' . get_the_title($post_id) . '" rel="bookmark">' . get_the_title($post_id) . '</a></h5>';
    $html .= '<p class="card-text">' . ripro_v2_excerpt(54, $post_id) . '</p>';
    $html .= '<p class="card-text"><small class="text-muted"><span class="badge badge-success"><i class="fas fa-wifi"></i> 推荐</span> ' . esc_html(get_the_date('Y-m-d', $post_id)) . '</small></p>';
    $html .= '</div></div>';

    $html .= '</div>';
    return $html;
}
add_shortcode('ri-post', 'ripro_post_shortcode');

//视频播放器
function ripro_video_shortcode($atts, $content = '') {
    wp_enqueue_script('hls');
    wp_enqueue_script('dplayer');

    $atts = shortcode_atts(array(
        'autoplay' => 0,
        'theme'    => '#b7daff',
        'logo'     => 1,
        'url'      => '',
        'pic'      => '',
    ), $atts);

    $logo_src = (!empty($atts['logo'])) ? esc_url(_cao('site_logo')) : '';
    $rand_id  = 'ri-dplayer-' . rand();
    $content .= '<div id="' . $rand_id . '" class="mb-4"><div id="ri-dplayer-warp"></div></div>';
    $content .= '<script type="text/javascript">jQuery(document).ready(function($){';
    $content .= "const dp = new DPlayer({container: document.getElementById('" . $rand_id . "'),logo: '" . $logo_src . "',theme: '" . $atts['theme'] . "',autoplay: " . (int) $atts['autoplay'] . ",video: {url: '" . $atts['url'] . "',type: 'auto',pic: '" . $atts['pic'] . "'},contextmenu: [{text: '" . get_bloginfo('name') . "',link: '" . home_url() . "',}],});";
    $content .= '});</script>';
    return do_shortcode($content);
}
add_shortcode('ri-video', 'ripro_video_shortcode');

//灯箱相册ripro-gallery
function ripro_gallery_shortcode($atts) {
    $atts = shortcode_atts(array(
        'id' => 0,
    ), $atts);

    $gallery_ids  = explode(',', $atts['id']);
    $gallery_arr  = [];
    foreach ($gallery_ids as $key => $id) {
        
        if ( get_post_status($id) == 'publish' ) {
            $gallery_arr[$key]['thumb'] = wp_get_attachment_image_src($id, 'thumbnail')[0];
            $gallery_arr[$key]['src'] = wp_get_attachment_image_src($id, 'full')[0];
            $gallery_arr[$key]['subHtml'] = '<div class="lightGallery-captions"><h4>'.wp_get_attachment_caption($id).'</h4></div>';
        }
        
    }
    $gallery_json = json_encode($gallery_arr);
    $div_id  = 'ripro-gallery-' . rand();
    $content = '<div id="' . $div_id . '" class="inline-gallery-container"></div>';

    $content .= '<script type="text/javascript">jQuery(document).ready(function($){';
    $content .= "";
    $content .= '});</script>';
    return do_shortcode($content);
}
// add_shortcode('ri-gallery', 'ripro_gallery_shortcode');

//按钮组件
function ripro_buttons_shortcode($atts, $content = '') {

    $atts = shortcode_atts(array(
        'size'    => '',
        'color'   => 'primary',
        'outline' => 0,
        'href'    => '#',
        'blank'   => 0,
        'content' => '这是按钮',
    ), $atts);

    $classes = (!empty($atts['outline'])) ? 'btn-outline-' . $atts['color'] : 'btn-' . $atts['color'];
    $blank   = (!empty($atts['blank'])) ? ' target="_blank"' : '';

    $content = '<a target="_blank" class="btn ' . $classes . ' ' . $atts['size'] . ' mr-2 mb-2"' . $blank . ' href="' . $atts['href'] . '" role="button" rel="noreferrer nofollow">' . $content . '</a>';
    return do_shortcode($content);
}
add_shortcode('ri-buttons', 'ripro_buttons_shortcode');

// 提示警告框（Alerts）
function ripro_alerts_shortcode($atts, $content = '') {

    $atts = shortcode_atts(array(
        'color' => 'primary',
        'close' => 0,
    ), $atts);

    if (!empty($atts['close'])) {
        $classes = 'alert-' . $atts['color'] . ' alert-dismissible fade show';
        $close   = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
    } else {
        $classes = 'alert-' . $atts['color'];
        $close   = '';
    }

    $content = '<div class="alert ' . $classes . ' mb-4" role="alert">' . $content . PHP_EOL . $close . '</div>';
    return do_shortcode($content);
}
add_shortcode('ri-alerts', 'ripro_alerts_shortcode');

// 折叠内容 accordions
function ripro_accordions_shortcode($atts, $content = '') {

    $atts = shortcode_atts(array(
        'title' => '点击展开',
        'show' => 0,
    ), $atts);

    $collapse_id  = 'collapse-' . rand();
   
    if ( !empty($atts['show']) ) {
        $show = 'show';
        $collapsed = '';
        $expanded = 'true';
    }else{
        $show = '';
        $collapsed = 'collapsed';
        $expanded = 'false';
    }

    $content = '<div class="accordion accordion-icon accordion-bg-light"> <div class="accordion-item mb-3"> <h6 class="accordion-header font-base"> <button class="accordion-button rounded d-inline-block '.$collapsed.'" type="button" data-toggle="collapse" data-target="#'.$collapse_id.'" aria-expanded="'.$expanded.'" aria-controls="'.$collapse_id.'">'.$atts['title'].'</button> </h6> <div id="'.$collapse_id.'" class="accordion-collapse collapse '.$show.'"> <div class="accordion-body mt-3">'.$content.'</div> </div> </div> </div>';
    return do_shortcode($content);
}
add_shortcode('ri-accordions', 'ripro_accordions_shortcode');

