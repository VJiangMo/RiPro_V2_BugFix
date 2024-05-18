<?php


///////////////////////////// RITHEME.COM END ///////////////////////////


defined('ABSPATH') || exit;

/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package ripro-v2
 */


new RiProSEO;

/**
 * 初始化主题自带SEO配置
 */
class RiProSEO{
    
    public $is_seo = false;
    public $site_seo = array();
    public $separator = '-';

    public function __construct() {
        $this->is_seo = _cao('is_ripro_v2_seo',false);
        $this->site_seo = _cao('site_seo');
        $this->separator = (isset($this->site_seo['separator'])) ? $this->site_seo['separator'] : '-' ;
        add_filter( 'excerpt_more', array($this, 'new_excerpt_more') );
        add_action('wp_head', array($this, 'custom_head_favicon') , 6);
        if ( $this->is_seo && is_array($this->site_seo) ) {
            add_filter( 'document_title_separator', array($this, 'custom_title_separator_to_line') );
            add_filter( 'document_title_parts', array($this, 'custom_post_document_parts') );
            add_filter( 'pre_get_document_title', array($this, '_wp_get_document_title') );
            add_filter('excerpt_length', array($this, 'excerpt_length'));
            add_action('wp_head', array($this, 'custom_head') , 5);
        }
    }
        
    public function custom_head_favicon() {
        if ( $site_favicon=_cao('site_favicon') ) {
           echo "<link href=\"$site_favicon\" rel=\"icon\">\n";
        }
    }

    //修饰更多字符
    public function new_excerpt_more($more) {
        return '...';
    }

    //摘要长度
    function excerpt_length($length) {
        return 120;
    }
    
    
    //标题分隔符修改成 “-”
    public function custom_title_separator_to_line(){
        return $this->separator; //自定义标题分隔符
    }

    //标题修正优化
    public function _wp_get_document_title() {

        global $page, $paged;



        $title = array(
            'title' => '',
        );

        // If it's a 404 page, use a "Page not found" title.
        if (is_404()) {
            $title['title'] = __('Page not found');

            // If it's a search, use a dynamic search results title.
        } elseif (is_search()) {
            /* translators: %s: Search query. */
            $title['title'] = sprintf(__('Search Results for &#8220;%s&#8221;'), get_search_query());

            // If on the front page, use the site title.
        } elseif (is_front_page()) {
            $title['title'] = get_bloginfo('name', 'display');

            // If on a post type archive, use the post type archive title.
        } elseif (is_post_type_archive()) {
            $title['title'] = post_type_archive_title('', false);

            // If on a taxonomy archive, use the term title.
        } elseif (is_tax()) {
            $title['title'] = single_term_title('', false);

            /*
         * If we're on the blog page that is not the homepage
         * or a single post of any post type, use the post title.
         */
        } elseif (is_home() || is_singular()) {
            $title['title'] = single_post_title('', false);

            // If on a category or tag archive, use the term title.
        } elseif (is_category() || is_tag()) {
            $title['title'] = single_term_title('', false);

            // If on an author archive, use the author's display name.
        } elseif (is_author() && get_queried_object()) {
            $author         = get_queried_object();
            $title['title'] = $author->display_name;

            // If it's a date archive, use the date as the title.
        } elseif (is_year()) {
            $title['title'] = get_the_date(_x('Y', 'yearly archives date format'));

        } elseif (is_month()) {
            $title['title'] = get_the_date(_x('F Y', 'monthly archives date format'));

        } elseif (is_day()) {
            $title['title'] = get_the_date();
        }

        // Add a page number if necessary.
        if (($paged >= 2 || $page >= 2) && !is_404()) {
            /* translators: %s: Page number. */
            $title['page'] = sprintf(__('Page %s'), max($paged, $page));
        }

        if (is_front_page()) {
            $title['tagline'] = get_bloginfo('description', 'display');
        } else {
            $title['site'] = get_bloginfo('name', 'display');
        }

        $sep = apply_filters('document_title_separator', '-');

        $title = apply_filters('document_title_parts', $title);

        $title = implode("$sep", array_filter($title));

        $title = wptexturize($title);

        $title = convert_chars($title);
        $title = esc_html($title);
        $title = capital_P_dangit($title);

        return $title;
    }


    //自定义SEO标题 custom_title
    public function custom_post_document_parts( $parts ){

        if ( is_singular() && $custom = get_post_meta( get_the_ID(), 'post_titie', true ) ) {
            $parts['title'] = $custom;
        }elseif (is_category() || is_tag() || (is_archive() && taxonomy_exists('series')) ) {
            # 分类页
            $termObj = get_queried_object();
            if ( $custom = get_term_meta($termObj->term_id, 'seo-title', true) ) {
                $parts['title'] = $custom;
            }
        }
        return $parts;
    }



    //自定义顶部钩子 添加描述 关键词 meta_og
    public function custom_head(){
        global $post;
        $key = '';
        $desc = '';
        $meta_og = array();
        $is_modular_home = is_page_template_modular(); //是否模块化首页
        if ( is_home() || $is_modular_home ) {
            # 首页
            $key = $this->site_seo['keywords'];
            $desc = $this->site_seo['description'];
        } elseif (is_singular() && !$is_modular_home ) {
            # 文章 页面
            if ( $meta_k = get_post_meta($post->ID, 'keywords', true) ) {
                $key = trim($meta_k);
            }else{
                if (get_the_tags($post->ID)) {
                    foreach (get_the_tags($post->ID) as $tag) {
                        $key .= $tag->name . ',';
                    }
                }
                foreach (get_the_category($post->ID) as $category) {
                    $key .= $category->cat_name . ',';
                }
            }

            if ( $meta_d = get_post_meta($post->ID, 'description', true) ) {
                $desc = trim($meta_d);
            }else{
                $excerpt = get_the_excerpt($post->ID);
                if (empty($excerpt)) {
                    $excerpt = $post->post_content;
                }
                $desc = wp_trim_words(strip_shortcodes($excerpt),120,'');
            }

            //Open Graph Protocol
            $meta_og = array(
                'title' => get_the_title($post->ID),
                'description' => $desc,
                'type' => 'article',
                'url' => esc_url(get_the_permalink($post->ID)),
                'site_name' => get_bloginfo('name'),
                'image' => _get_post_thumbnail_url($post->ID,'full'),
            );

        } elseif (is_category() || is_tag() || (is_archive() && taxonomy_exists('series')) ) {
            # 分类/标签/专题
            $termObj = get_queried_object();
            if ( $meta_k = get_term_meta(@$termObj->term_id, 'seo-keywords', true) ) {
                $key = trim($meta_k);
            }
            if ( $meta_d = get_term_meta(@$termObj->term_id, 'seo-description', true) ) {
                $desc = trim($meta_d);
            }
        }

        if (!empty($key)) {
            echo "<meta name=\"keywords\" content=\"$key\">\n";
        }
        if (!empty($desc)) {
            echo "<meta name=\"description\" content=\"$desc\">\n";
        }
        if (!empty($meta_og)) {
            foreach ($meta_og as $key => $value) {
                echo "<meta property=\"og:$key\" content=\"$value\">\n";
            }
        }

    }

}




/**
 * 全站统一 body css样式控制
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:45:03+0800
 * @param    [type]                   $classes [description]
 * @return   [type]                            [description]
 */
function ripro_v2_body_classes($classes) {
    // Adds a class of hfeed to non-singular pages.
    if ( !is_singular() ) {
        $classes[] = 'hfeed';
    }

    //是否全宽模式
    if ( apply_filters('is_site_wide_screen',false) || _cao('is_site_wide_screen',false) ) {
        $classes[] = 'wide-screen';
    }

    //是否夜间模式
    if ( !empty(_cao('is_site_dark_light',true)) && _cao('is_site_default_dark',false) ) {
        $classes[] = 'dark-open';
    }


    if ( is_home() && _cao('navbar_home_transition', true) ) {
        $classes[] = 'navbar-transition';
    }else{
        $classes[] = 'navbar-' . apply_filters('navbar_style',_cao('navbar_style', 'regular'));
    }
    
    if ( !_cao('navbar_omnisearch_search', true) ) {
        $classes[] = 'no-search';
    }
    

    if ( ripro_v2_show_hero() ) {
        $classes[] = 'with-hero';
        $classes[] = 'hero-' . ripro_v2_compare_options(_cao('hero_single_style', 'none'), get_post_meta(get_the_ID(), 'hero_single_style', 1));
        
        if (is_post_shop_video()) {
            $classes[] = 'hero-video';
        }elseif (is_post_shop_down() && !wp_is_mobile()) {
            $classes[] = 'hero-shop';
        }else{
            $classes[] = 'hero-image';
        }

    }

    $classes[] = 'pagination-' . apply_filters('site_pagination',_cao('site_pagination', 'numeric'));

    if ( get_previous_posts_link() ) {
        $classes[] = 'paged-previous';
    }

    if ( get_next_posts_link() ) {
        $classes[] = 'paged-next';
    }

    $classes[] = 'no-off-canvas';
    $classes[] = 'sidebar-' . ripro_v2_sidebar();

    if ( is_page_template_modular() ) {
        $classes[] = apply_filters('site_modular_title','modular-title-1');
    }

    return $classes;
}
add_filter('body_class', 'ripro_v2_body_classes');



/**
 * 自定义顶部CSS代码
 * @Author   Dadong2g
 * @DateTime 2021-06-21T12:37:21+0800
 * @return   [type]                   [description]
 */
function custom_head_css() {
    $css_str = _cao('web_css');
    if ($css_str) {
        echo '<style type="text/css">' . $css_str . '</style>';
    }
}
add_action('wp_head', 'custom_head_css');


/**
 * 保护后台登录，通过设置自己的登录参数隐藏WP自带登录地址 安全验证
 * @Author   Dadong2g
 * @DateTime 2021-10-31T09:34:50+0800
 * @return   [type]                   [description]
 */
function ripro_v2_login_protection(){
    $key = trim(_cao('site_login_security_key'));
    $param = trim(_cao('site_login_security_param'));

    if ( !empty($key) && !empty($param) && (empty($_GET[$key]) || $_GET[$key] != $param) ) {
        # 开启登录保护...
        wp_safe_redirect(home_url('user'));exit;
    }
}
add_action('login_enqueue_scripts','ripro_v2_login_protection'); //如果忘记自己设置的验证参数，请在这里注释这行即可临时登录


/**
 * 邮件内容过滤器 邮件模板来自：www.zibll.com 作者分享
 * @Author   Dadong2g
 * @DateTime 2021-05-20T15:48:20+0800
 * @param    [type]                   $mail [description]
 * @return   [type]                         [description]
 */
function ripro_v2_get_mail_content($mail) {

    if ( empty(_cao('is_site_mail_tpl',true)) ) {
        return $mail;
    }

    $mail        = (array) $mail;
    $message     = !empty($mail['message']) ? nl2br($mail['message']) : '';
    $blog_name   = '<a href="' . esc_url(home_url()) . '">' . get_bloginfo('name') . '</a>';
    $description = trim(get_bloginfo('description'));
    $logo        = esc_url(_cao('site_logo'));
    $con_more    = _cao('mail_more_content', '');
    $bg          = esc_url(get_template_directory_uri() . '/assets/img/mail-bg.jpg');

    $content = '
    <div style="background:#ecf1f3;padding-top:20px; min-width:820px;">
        <div style="width:801px;height:auto; margin:0px auto;">
            <div style="width:778px;height:auto;margin:0px 11px;background:#fff;box-shadow: 6px 3px 5px rgba(0,0,0,0.05);-webkit-box-shadow: 6px 3px 5px rgba(0,0,0,0.05);-moz-box-shadow: 6px 3px 5px rgba(0,0,0,0.05);-ms-box-shadow: 6px 3px 5px rgba(0,0,0,0.05);-o-box-shadow: 6px 3px 5px rgba(0,0,0,0.05);">
                <div style="width:781px;height:160px; background:#fff;">
                    <div style="width:200px;height:160px;background:url(' . $logo . ') 0px 60px no-repeat; margin:0px auto;background-size: contain;"></div>
                </div>
                <div style="width:627px;margin:0 auto; padding-left:77px; background:#fff;font-size:14px;color:#55798d;padding-right:77px;"><br>
                    <div style="overflow-wrap:break-word;line-height:30px;">
                    ' . $message . '
                    </div>
                    <br><br><br>
                </div>
            </div>
            <div style="position:relative;top:-15px;width:800px;height: 360px;background:url(' . $bg . ') 0px 0px no-repeat;">
                <div style="height:200px;color:#507383;font-size:14px;line-height: 1.4;padding: 20px 92px;">
                    <div style="font-size: 22px;font-weight: bold;">' . $blog_name . '</div>
                    <div style="margin:20px 0;color: #6a8895;min-height:4.2em;white-space: pre-wrap;">' . $description . '</div>
                    <div style="">' . $con_more . '</div>
                </div>
                <div style="clear:both;"></div>
            </div>
        </div>
    </div>
    ';
    $headers          = array('Content-Type: text/html; charset=UTF-8');
    @$mail['message'] = $content;
    @$mail['headers'] = $headers;
    return $mail;
}

add_filter('wp_mail', 'ripro_v2_get_mail_content');



/**
 * 缩略图大小控制同步wordpress自带设置
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:44:46+0800
 * @return   [type]                   [description]
 */
function ripro_v2_setup_post_thumbnail_size() {

    $thumbnail_size = apply_filters('post_thumbnail_size',_cao('post_thumbnail_size', 'numeric'));
    if (empty($thumbnail_size)) {
        $thumbnail_size['width']  = 300;
        $thumbnail_size['height'] = 200;
    }
    update_option('thumbnail_size_w', $thumbnail_size['width']);
    update_option('thumbnail_size_h', $thumbnail_size['height']);
    update_option('thumbnail_crop', _cao('post_thumbnail_crop', '1'));

    $medium_size = _cao('post_medium_size');
    if (empty($medium_size)) {
        $medium_size['width']  = 0;
        $medium_size['height'] = 0;
    }
    update_option('medium_size_w', $medium_size['width']);
    update_option('medium_size_h', $medium_size['height']);

    $large_size = _cao('post_large_size');
    if (empty($large_size)) {
        $large_size['width']  = 0;
        $large_size['height'] = 0;
    }
    update_option('large_size_w', $large_size['width']);
    update_option('large_size_h', $large_size['height']);

    return;
    
}

add_action('csf_'._OPTIONS_PRE.'_save_after', 'ripro_v2_setup_post_thumbnail_size');


/**
 * 内页标题优化
 * @Author   Dadong2g
 * @DateTime 2021-04-11T20:52:49+0800
 * @param    [type]                   $title [description]
 * @return   [type]                          [description]
 */
function ripro_v2_theme_archive_title($title) {
    if (is_category()) {
        $title = single_cat_title('', false);
    } elseif (is_tag()) {
        $title = single_tag_title('', false);
    } elseif (is_author()) {
        $title = get_the_author();
    } elseif (is_post_type_archive()) {
        $title = post_type_archive_title('', false);
    } elseif (is_tax()) {
        $title = single_term_title('', false);
    }
    return $title;
}
add_filter('get_the_archive_title', 'ripro_v2_theme_archive_title');


/**
 * 编辑器添加“下一页”按钮
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:44:13+0800
 * @param    [type]                   $mce_buttons [description]
 * @return   [type]                                [description]
 */
function wp_add_next_page_button($mce_buttons) {
    $pos = array_search('wp_more', $mce_buttons, true);
    if ($pos !== false) {
        $tmp_buttons   = array_slice($mce_buttons, 0, $pos + 1);
        $tmp_buttons[] = 'wp_page';
        $mce_buttons   = array_merge($tmp_buttons, array_slice($mce_buttons, $pos + 1));
    }
    return $mce_buttons;
}
add_filter('mce_buttons', 'wp_add_next_page_button');



/**
 * 伪静态路由
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:43:57+0800
 * @param    [type]                   $wp_rewrite [description]
 * @return   [type]                               [description]
 */
function ripro_v2_oauth_page_rewrite_rules($wp_rewrite) {
    // 如果当前是自定义固定链接则设置
    if ( get_option('permalink_structure') ) {
        $new_rules['^oauth/([A-Za-z]+)$']          = 'index.php?oauth=$matches[1]';
        $new_rules['^oauth/([A-Za-z]+)/callback$'] = 'index.php?oauth=$matches[1]&oauth_callback=1';
        $new_rules['^goto$'] = 'index.php?goto=1';
        $new_rules['^user/([^/]*)/?'] = 'index.php?page_id='.get_page_id('user').'&action=$matches[1]';
        $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
    }
}
add_action('generate_rewrite_rules', 'ripro_v2_oauth_page_rewrite_rules');

/**
 * 伪静态路由查询字段
 * @Author   Dadong2g
 * @DateTime 2021-01-16T14:12:04+0800
 * @param    [type]                   $public_query_vars [description]
 * @return   [type]                                      [description]
 */
function ripro_v2_add_oauth_page_query_vars($public_query_vars) {
    if (!is_admin()) {
        $public_query_vars[] = 'oauth'; // 添加参数白名单oauth，代表是各种OAuth登录处理页
        $public_query_vars[] = 'oauth_callback'; // OAuth登录最后一步，整合WP账户，自定义用户名
        $public_query_vars[] = 'goto'; //下载页跳转
        $public_query_vars[] = 'action'; //user_page action
    }
    return $public_query_vars;
}
add_filter('query_vars', 'ripro_v2_add_oauth_page_query_vars');

/**
 * 伪静态路由页面模板
 * @Author   Dadong2g
 * @DateTime 2021-01-16T14:12:20+0800
 * @return   [type]                   [description]
 */
function ripro_v2_oauth_page_template() {
    $sns = strtolower(get_query_var('oauth')); //转换为小写
    $sns_callback = get_query_var('oauth_callback');
    if ($sns && in_array($sns, array('qq', 'weixin','mpweixin', 'weibo'))) {
        $template = $sns_callback ? TEMPLATEPATH . '/inc/sns/' . $sns . '/callback.php' : TEMPLATEPATH . '/inc/sns/' . $sns . '/login.php';
        load_template($template);exit;
    }

    $goto = strtolower(get_query_var('goto')); //转换为小写
    if ($goto==1) {
        $template = TEMPLATEPATH . '/inc/goto.php';
        load_template($template);exit;
    }


}

add_action('template_redirect', 'ripro_v2_oauth_page_template', 5);


/**
 * 修复自定义高级菜单缺省默认描述description BUG
 * @Author Dadong2g
 * @date   2022-08-28
 * @param  [type]     $items [description]
 * @param  [type]     $menu  [description]
 * @param  [type]     $args  [description]
 * @return [type]
 */
function ri_wp_get_nav_menu_items($items, $menu, $args) {
    foreach($items as $key => $item){
        $items[$key]->description = !empty($items[$key]->description) ? $items[$key]->description : '';
    }
    return $items;
}
add_filter('wp_get_nav_menu_items', 'ri_wp_get_nav_menu_items', 10, 3);

/**
 * 捕获是否微信内访问条件满足获取openid
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:43:45+0800
 * @return   [type]                   [description]
 */
function ripro_v2_is_template_redirect() {

    // 推荐aff捕获
    if (!empty($_GET['aff'])) {
        $aff_id = absint($_GET['aff']);
        RiSession::set('current_aff_uid',$aff_id);
    }
    
    //微信内是否跳转获取openid 开启微信jsapi才有效
    if (!empty( _cao('is_weixinpay') ) 
        && wp_is_mobile()
        && !is_close_site_shop()
        && is_weixin_visit()
        && (is_single() || is_page_template('pages/page-user.php'))
        && (!defined('DOING_AJAX') || !DOING_AJAX)
        && empty(RiSession::get('current_weixin_openid',0))
    ) {
        $opt = _cao('weixinpay'); 
        if (!empty($opt) && $opt['appid'] && $opt['is_jsapi']) {
            $current_url = urlencode(curPageURL());
            $wxurl       = home_url('/oauth/weixin?get_openid=1&rurl=' . $current_url);
            wp_safe_redirect($wxurl);exit;
        }
    }
    
}
add_action('template_redirect','ripro_v2_is_template_redirect', 5);



/**
 * 找回密码
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:43:23+0800
 * @param    [type]                   $url      [description]
 * @param    [type]                   $redirect [description]
 * @return   [type]                             [description]
 */
function ripro_v2_lostpassword_url($url, $redirect) {
    $url = home_url('login?mod=lostpassword');
    return esc_url($url);
}
add_filter('lostpassword_url', 'ripro_v2_lostpassword_url', 20, 2);

add_filter('get_avatar_url', '_get_avatar_url', 10, 3);
add_filter('pre_get_avatar', '_pre_get_avatar', 10, 3);


/**
 * 替换默认头像url
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:43:11+0800
 * @param    [type]                   $url         [description]
 * @param    [type]                   $id_or_email [description]
 * @param    [type]                   $args        [description]
 * @return   [type]                                [description]
 */
function _get_avatar_url($url, $id_or_email, $args) {
    $user_id = 0;
    if (is_numeric($id_or_email)) {
        $user_id = absint($id_or_email);
    } elseif (is_string($id_or_email) && is_email($id_or_email)) {
        $user = get_user_by('email', $id_or_email);
        if (isset($user->ID) && $user->ID) {
            $user_id = $user->ID;
        }
    } elseif ($id_or_email instanceof WP_User) {
        $user_id = $id_or_email->ID;
    } elseif ($id_or_email instanceof WP_Post) {
        $user_id = $id_or_email->post_author;
    } elseif ($id_or_email instanceof WP_Comment) {
        $user_id = $id_or_email->user_id;
        if (!$user_id) {
            $user = get_user_by('email', $id_or_email->comment_author_email);
            if (isset($user->ID) && $user->ID) {
                $user_id = $user->ID;
            }

        }
    }

    $avatar_type = get_user_meta($user_id, 'user_avatar_type', 1);

    if (empty($avatar_type)) {
        $avatar_url = _the_theme_avatar();
    }elseif ($avatar_type=='custom') {

        $uploads = wp_upload_dir();
        
        $custom = get_user_meta($user_id,'user_custom_avatar', 1);

        if (file_exists(WP_CONTENT_DIR . '/uploads' . $custom)) {
            $uploads['baseurl'] = WP_CONTENT_URL . '/uploads';
        }

        $custom = (empty($custom)) ? _the_theme_avatar() : $uploads['baseurl'] . $custom ;
        
        $avatar_url = set_url_scheme($custom);
    }else{
        $avatar_url = set_url_scheme(get_user_meta($user_id, 'open_'.$avatar_type.'_avatar', 1));
    }
    
    $url = preg_replace('/^(http|https):/i', '', $avatar_url);

    return $url;

}

/**
 * 替换默认头像
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:42:55+0800
 * @param    [type]                   $avatar      [description]
 * @param    [type]                   $id_or_email [description]
 * @param    [type]                   $args        [description]
 * @return   [type]                                [description]
 */
function _pre_get_avatar($avatar, $id_or_email, $args) {

    $url = _get_avatar_url($avatar, $id_or_email, $args);

    $class = array('lazyload', 'avatar', 'avatar-' . (int) $args['size'], 'photo');
    if ($args['class']) {
        if (is_array($args['class'])) {
            $class = array_merge($class, $args['class']);
        } else {
            $class[] = $args['class'];
        }
    }
    if (is_admin()) {
        $lazy = '';
    } else {
        $lazy = 'data-';
    }
    $avatar = sprintf(
        "<img alt='%s' {$lazy}src='%s' class='%s' height='%d' width='%d' %s/>",
        esc_attr($args['alt']),
        esc_url($url),
        esc_attr(join(' ', $class)),
        (int) $args['height'],
        (int) $args['width'],
        $args['extra_attr']
    );
    return $avatar;
}


/**
 * 使用昵称替换链接中的用户名
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:42:36+0800
 * @param    [type]                   $link            [description]
 * @param    [type]                   $author_id       [description]
 * @param    [type]                   $author_nicename [description]
 * @return   [type]                                    [description]
 */
function ripro_v2_author_link( $link, $author_id, $author_nicename ){
    $author_nickname = get_user_meta( $author_id, 'nickname', true );
    if ( $author_nickname ) {
        $link = str_replace( $author_nicename, $author_nickname, $link );
    }
    return $link;
}
add_filter('author_link','ripro_v2_author_link', 10, 3 );

/**
 * 使用昵称替换用户名，通过用户ID进行查询
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:42:31+0800
 * @param    [type]                   $query_vars [description]
 * @return   [type]                               [description]
 */
function ripro_v2_author_request( $query_vars ){
    if ( array_key_exists( 'author_name', $query_vars ) ) {
        global $wpdb;
        $author_id = $wpdb->get_var( $wpdb->prepare( "SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key='nickname' AND meta_value = %s", urldecode($query_vars['author_name']) ) );
        if ( $author_id ) {
            $query_vars['author'] = $author_id;
            unset( $query_vars['author_name'] );    
        }
    }
    return $query_vars;
}
add_filter('request','ripro_v2_author_request');



/**
 * 用户登录时间和登录IP
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:42:23+0800
 * @param    [type]                   $login [description]
 * @return   [type]                          [description]
 */
function ripro_insert_last_login($user_login, $user) {
    //最近登录时间
    update_user_meta($user->ID, 'last_login_time', current_time('mysql'));
    //最近登录IP
    update_user_meta($user->ID, 'last_login_ip',get_client_ip());

    RiDynamic::add(array(
        'info' => sprintf(__('成功登录了本站%s', 'ripro-v2'),''), 
        'uid' => $user->ID, 
        'href' => '#',
        'time' => time(),
    ));

    if ( !empty( get_user_meta($user->ID, 'cao_banned', true) ) ) {
        wp_logout();

        if (wp_is_json_request()) {
            wp_send_json(array(
                'status' => 0,
                'msg'    => __('您的账号已冻结','ripro-v2'),
            ));
        }else{
            ripro_wp_die(__('您的账号已冻结','ripro-v2'),get_user_meta($user->ID, 'cao_banned_reason', true));
        }
    }

}
add_action('wp_login','ripro_insert_last_login', 10, 2);


/**
 * 用户注册时初始化
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:42:15+0800
 * @param    [type]                   $user_id [description]
 * @return   [type]                            [description]
 */
function ripro_credit_by_user_register($user_id) {
    //链接推广人与新注册用户(注册人meta)
    $ref_from = absint(RiSession::get('current_aff_uid',0));
    //更新推荐人ID 
    update_user_meta($user_id, 'cao_ref_from', absint($ref_from));
    //注册IP
    update_user_meta($user_id, 'register_ip',get_client_ip());

    RiDynamic::add(array(
        'info' => sprintf(__('成功加入了本站新用户%s', 'ripro-v2'),''), 
        'uid' => $user_id, 
        'href' => '#',
        'time' => time(),
    ));

}
add_action('user_register','ripro_credit_by_user_register');





/**
 * 筛选条件
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:42:09+0800
 * @param    [type]                   $query [description]
 * @return   [type]                          [description]
 */
function ripro_v2_archive_filter($query) {
    //is_search判断搜索页面  !is_admin排除后台  $query->is_main_query()只影响主循环
    if (!$query->is_admin && is_archive() && $query->is_main_query()) {
        // 排序：
        $order      = !empty($_GET['order']) ? esc_sql($_GET['order']) : null;
        $price_type = !empty($_GET['price_type']) ? (int) $_GET['price_type'] : null;

        $_meta = [];

        if ($order == 'views') {
            $query->set('meta_key', 'views');
            $query->set('orderby', 'meta_value_num');
            $query->set('order', 'DESC');
        } elseif ($order == 'favnum') {
            $query->set('meta_key', 'follow_num');
            $query->set('orderby', 'meta_value_num');
            $query->set('order', 'DESC');
        } elseif (!empty($order)) {
            $query->set('orderby', $order);
        }

        // 筛选
        if ($price_type) {
            $_price               = 'cao_price';
            $_vip_rate            = 'cao_vip_rate';
            $_boosvip_free        = 'cao_is_boosvip';
            $_cao_close_novip_pay = 'cao_close_novip_pay';

            switch ($price_type) {
            case 1:
                $_meta[] = [
                    'relation' => 'AND',
                    ['key' => $_price, 'compare' => '=', 'value' => '0'],
                    ['key' => $_cao_close_novip_pay, 'compare' => '!=', 'value' => '1'],
                ];
                break;
            case 2:
                $_meta[] = [
                    ['key' => $_price, 'compare' => '>', 'value' => '0'],
                ];
                break;
            case 3:
                $_meta[] = [
                    'relation' => 'AND',
                    ['key' => $_price, 'compare' => '>', 'value' => '0'],
                    ['key' => $_vip_rate, 'compare' => '=', 'value' => '0'],
                ];
                break;
            case 4:
                $_meta[] = [
                    'relation' => 'AND',
                    ['key' => $_price, 'compare' => '>', 'value' => '0'],
                    ['key' => $_vip_rate, 'compare' => '>', 'value' => '0'],
                    ['key' => $_vip_rate, 'compare' => '<', 'value' => '1'],
                ];
                break;
            case 5:
                $_meta[] = [
                    'relation'            => 'OR',
                    'boosvip_free_clause' => ['key' => $_boosvip_free, 'compare' => '=', 'value' => '1'],
                    'vip_rate_clause'     => ['key' => $_vip_rate, 'compare' => '=', 'value' => '0'],
                ];
                break;
            default:
                break;
            }
            $query->set('meta_query', $_meta);

        }

        // 自定义字段筛选
        if (_cao('is_custom_post_meta_opt', '0') && is_array(_cao('custom_post_meta_opt', '0'))) {
            $custom_post_meta_opt = _cao('custom_post_meta_opt', '0');

            foreach ($custom_post_meta_opt as $filter) {
                $_meta_key       = $filter['meta_ua'];
                $_meta_key_value = !empty($_GET[$_meta_key]) ? esc_html($_GET[$_meta_key]) : null;

                if (!empty($_meta_key_value) && $_meta_key_value != 'all') {

                    $_meta[] = [
                        'relation'      => 'AND',
                        'custom_clause' => ['key' => $_meta_key, 'compare' => '=', 'value' => esc_html($_meta_key_value)],
                    ];

                    $query->set('meta_query', $_meta);
                }
            }
        }
    }

    //搜索优化细分安全优化
    if (!$query->is_admin && $query->is_main_query() && is_search()) {
        //仅登录用户可以搜索
        if (_cao('is_search_loging', false) && !is_user_logged_in()) {
            ripro_wp_die(__('登录提示', 'ripro-v2'), '<small>' . __('请登录后即可进行站内搜索', 'ripro-v2') . '</small>');
        }
        $query->set('post_type', 'post');
        $cat = (!isset($_GET['cat'])) ? 0 : (int) $_GET['cat'];
        if ($cat < 0) {
            $query->set('cat', '');
            unset($query->query['cat']);
        }
        //搜索频率限制
        if (_cao('is_search_limit', true)) {
            $client_ip = get_client_ip(); //客户端IP
            if (!preg_match('/google|yandex|yndx|spider|bot|slurp|msn|bing|adsbot|AdIdxBot|search|face|baidu|duck|sogou|youdao|ccbot|alexa|microsoft/i', gethostbyaddr($client_ip))) {

                $limit_time = (int) _cao('search_limit_time', 60);
                $the_time   = time(); //当前时间
                $last_time  = RiSession::get('search_last_time', 0); //上次搜索时间

                if ($the_time - $last_time < $limit_time) {
                    ripro_wp_die(__('安全搜索友情提示', 'ripro-v2'), '<small>' . _cao('search_limit_msg') . '</small>');
                } else {
                    RiSession::set('search_last_time', $the_time);
                }
            }

        }

    }

    return $query;
}

add_filter('pre_get_posts', 'ripro_v2_archive_filter', 99);
add_filter('use_default_gallery_style', '__return_false');



//搜索标题优化
function site_search_by_title_only($search, $wp_query) {
    global $wpdb;

    if (empty($search) || empty(_cao('is_search_title_only', false))) {
        return $search; // skip processing - no search term in query
    }

    $q = $wp_query->query_vars;
    $n = !empty($q['exact']) ? '' : '%';

    $search = $searchand = '';

    foreach ((array) $q['search_terms'] as $term) {
        $term = esc_sql($wpdb->esc_like($term));
        $search .= "{$searchand}($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";
        $searchand = ' AND ';
    }

    if (!empty($search)) {
        $search = " AND ({$search}) ";
        if (!is_user_logged_in()) {
            $search .= " AND ($wpdb->posts.post_password = '') ";
        }
    }

    return $search;
}
add_filter('posts_search', 'site_search_by_title_only', 500, 2);



/**
 * 上传文件MD5重命名，新增时间戳防止重复
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:41:55+0800
 * @param    [type]                   $filename [description]
 * @return   [type]                             [description]
 */
function ripro_v2_new_filename($filename){
    if (!_cao('md5_file_udpate',true)) return $filename;
    $info = pathinfo($filename);
    $ext  = empty($info['extension']) ? '' : '.' . $info['extension'];
    $name = basename($filename, $ext);
    return time().'-'.substr(md5($name), 0, 15) . $ext;
}
add_filter('sanitize_file_name', 'ripro_v2_new_filename', 10);




/*
*评论中插入图片
*/
add_action('comment_text', 'comments_embed_img', 2);
function comments_embed_img($comment) {
    $size = 'auto';
    $comment = preg_replace(array('#(http://([^\s]*)\.(jpg|gif|png|JPG|GIF|PNG))#','#(https://([^\s]*)\.(jpg|gif|png|JPG|GIF|PNG))#'),'<img src="$1" alt="" width="'.$size.'" height="" />', $comment);
    return $comment;
}

/* 评论验证黑名单过滤 */
function refused_spam_comments($comment_data) {

    // $pattern = '/[一-龥]/u'; //验证是否存在中文
    // if ( !preg_match($pattern, $comment_data['comment_content']) ) {
    //     rizhuti_v2_ajax_comment_err(esc_html__('评论必须含中文！','ripro-v2'));die;
    // }

    if ( is_user_logged_in() ) {
        return $comment_data;
    } //登录用户不验证

    
    if ( wp_blacklist_check($comment_data['comment_author'], $comment_data['comment_author_email'], $comment_data['comment_author_url'], $comment_data['comment_content'], $comment_data['comment_author_IP'], $comment_data['comment_agent']) ) {
        rizhuti_v2_ajax_comment_err(esc_html__('你填写的某项信息或IP地址已被列入黑名单，无法进行评论，请文明评论！','ripro-v2'));die;
    } else {
        return $comment_data;
    }
}
add_filter('preprocess_comment', 'refused_spam_comments');

/**
 * 评论动态
 * @Author   Dadong2g
 * @DateTime 2021-06-08T13:55:18+0800
 * @param    [type]                   $comment_id       [description]
 * @param    [type]                   $comment_approved [description]
 * @return   [type]                                     [description]
 */
function dynamic_message_comment($comment_id, $comment_approved){

    if( 1 === $comment_approved ){
        $comment = get_comment($comment_id);
        //发送消息到网站动态 
        RiDynamic::add(array(
            'info' => sprintf( __('评论了%s', 'ripro-v2'),get_the_title($comment->comment_post_ID) ), 
            'uid' => $comment->user_id, 
            'href' => get_the_permalink($comment->comment_post_ID),
            'time' => time(),
        ));
    }
    
}

add_action( 'comment_post', 'dynamic_message_comment', 10, 2 );





/**
 * SMTP服务
 */

if ( _cao('is_site_smtp',true) ) {
    add_action('phpmailer_init', '_site_mail_smtp');
}

/**
 * SMTP中转
 * @Author   Dadong2g
 * @DateTime 2021-05-16T11:00:40+0800
 * @param    [type]                   $phpmailer [description]
 * @return   [type]                              [description]
 */
function _site_mail_smtp($phpmailer) {
    $phpmailer->FromName   = _cao('smtp_mail_nicname'); // 发件人昵称
    $phpmailer->Host       = _cao('smtp_mail_host'); // 邮箱SMTP服务器
    $phpmailer->Port       = (int) _cao('smtp_mail_port'); // SMTP端口，不需要改
    $phpmailer->Username   = _cao('smtp_mail_name'); // 邮箱账户
    $phpmailer->Password   = _cao('smtp_mail_passwd'); // 此处填写邮箱生成的授权码，不是邮箱登录密码
    $phpmailer->From       = _cao('smtp_mail_name'); // 邮箱账户同上
    $phpmailer->SMTPAuth   = !empty(_cao('smtp_mail_smtpauth'));
    $phpmailer->SMTPSecure = _cao('smtp_mail_smtpsecure'); // 端口25时 留空，465时 ssl，不需要改
    $phpmailer->IsSMTP();
}




/**
 * no category
 */
if ( _cao('no_categoty') && !function_exists('no_category_base_refresh_rules')) {

    /* hooks */
    register_activation_hook(__FILE__, 'no_category_base_refresh_rules');
    register_deactivation_hook(__FILE__, 'no_category_base_deactivate');

    /* actions */
    add_action('created_category', 'no_category_base_refresh_rules');
    add_action('delete_category', 'no_category_base_refresh_rules');
    add_action('edited_category', 'no_category_base_refresh_rules');
    add_action('init', 'no_category_base_permastruct');

    /* filters */
    add_filter('category_rewrite_rules', 'no_category_base_rewrite_rules');
    add_filter('query_vars', 'no_category_base_query_vars'); // Adds 'category_redirect' query variable
    add_filter('request', 'no_category_base_request'); // Redirects if 'category_redirect' is set

    function no_category_base_refresh_rules() {
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
    }

    function no_category_base_deactivate() {
        remove_filter('category_rewrite_rules', 'no_category_base_rewrite_rules'); // We don't want to insert our custom rules again
        no_category_base_refresh_rules();
    }

    /**
     * Removes category base.
     *
     * @return void
     */
    function no_category_base_permastruct() {
        global $wp_rewrite;
        global $wp_version;

        if ($wp_version >= 3.4) {
            $wp_rewrite->extra_permastructs['category']['struct'] = '%category%';
        } else {
            $wp_rewrite->extra_permastructs['category'][0] = '%category%';
        }
    }

    /**
     * Adds our custom category rewrite rules.
     *
     * @param  array $category_rewrite Category rewrite rules.
     *
     * @return array
     */
    function no_category_base_rewrite_rules($category_rewrite) {
        global $wp_rewrite;
        $category_rewrite = array();

        /* WPML is present: temporary disable terms_clauses filter to get all categories for rewrite */
        if (class_exists('Sitepress')) {
            global $sitepress;

            remove_filter('terms_clauses', array($sitepress, 'terms_clauses'));
            $categories = get_categories(array('hide_empty' => false));
            add_filter('terms_clauses', array($sitepress, 'terms_clauses'));
        } else {
            $categories = get_categories(array('hide_empty' => false));
        }

        foreach ($categories as $category) {
            $category_nicename = $category->slug;

            if ($category->parent == $category->cat_ID) {
                $category->parent = 0;
            } elseif ($category->parent != 0) {
                $category_nicename = get_category_parents($category->parent, false, '/', true) . $category_nicename;
            }

            $category_rewrite['(' . $category_nicename . ')/(?:feed/)?(feed|rdf|rss|rss2|atom)/?$']    = 'index.php?category_name=$matches[1]&feed=$matches[2]';
            $category_rewrite["({$category_nicename})/{$wp_rewrite->pagination_base}/?([0-9]{1,})/?$"] = 'index.php?category_name=$matches[1]&paged=$matches[2]';
            $category_rewrite['(' . $category_nicename . ')/?$']                                       = 'index.php?category_name=$matches[1]';
        }

        // Redirect support from Old Category Base
        $old_category_base                               = get_option('category_base') ? get_option('category_base') : 'category';
        $old_category_base                               = trim($old_category_base, '/');
        $category_rewrite[$old_category_base . '/(.*)$'] = 'index.php?category_redirect=$matches[1]';

        return $category_rewrite;
    }

    function no_category_base_query_vars($public_query_vars) {
        $public_query_vars[] = 'category_redirect';
        return $public_query_vars;
    }

    /**
     * Handles category redirects.
     *
     * @param $query_vars Current query vars.
     *
     * @return array $query_vars, or void if category_redirect is present.
     */
    function no_category_base_request($query_vars) {
        if (isset($query_vars['category_redirect'])) {
            $catlink = trailingslashit(get_option('home')) . user_trailingslashit($query_vars['category_redirect'], 'category');
            status_header(301);
            header("Location: $catlink");
            exit();
        }

        return $query_vars;
    }

}



/**
 * 添加文章专题分类大法
 * @Author   Dadong2g
 * @DateTime 2021-08-22T12:30:10+0800
 * @return   [type]                   [description]
 */
function site_add_series_taxonomy(){
    $labels = array(
        'name'                       => __('专题','ripro-v2'),
        'singular_name'              => 'series',
        'search_items'               => __('搜索','ripro-v2'),
        'popular_items'              => __('热门','ripro-v2'),
        'all_items'                  => __('所有','ripro-v2'),
        'parent_item'                => null,
        'parent_item_colon'          => null,
        'edit_item'                  => __('编辑','ripro-v2'),
        'update_item'                => __('更新','ripro-v2'),
        'add_new_item'               => __('添加','ripro-v2'),
        'new_item_name'              => __('专题名称','ripro-v2'),
        'separate_items_with_commas' => __('按逗号分开','ripro-v2'),
        'add_or_remove_items'        => __('添加或删除','ripro-v2'),
        'choose_from_most_used'      => __('从经常使用的类型中选择','ripro-v2'),
        'menu_name'                  => __('专题','ripro-v2'),
    );

    register_taxonomy(
        'series',
        array('post'),
        array(
            'hierarchical' => true,
            'labels'       => $labels,
            'show_ui'      => true,
            'query_var'    => true,
            'rewrite'      => array('slug' => 'series'),
            'show_in_rest' => true,
        )
    );

}
add_action('init','site_add_series_taxonomy');









///////////////////////////// RITHEME.COM END ///////////////////////////

