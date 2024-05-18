<?php
///////////////////////////// RITHEME.COM END ///////////////////////////
defined('ABSPATH') || exit;

/**
 * 获取主题内置meta字段一览
 * @Author   Dadong2g
 * @DateTime 2021-05-28T16:45:28+0800
 * @param    string                   $type [description]
 */
function get_ripro_v2_meta_options() {

    $meta             = [];
    //用户自定义字段类解释
    $meta['usermeta'] = array(
        'cao_balance'          => '钱包余额',
        'cao_consumed_balance' => '用户已消费余额',
        'cao_ref_from'         => '推荐人ID',
        'cao_user_type'        => '用户等级',
        'cao_vip_end_time'     => '用户到期时间',
        'cao_banned'           => '封号该用户',
        'cao_banned_reason'    => '封号原因',
        'register_ip'          => '注册ip',
        'last_login_ip'        => '最近登录ip',
        'last_login_time'      => '最近登录时间',
    );
    //文章自定义字段类解释
    $meta['postmeta'] = array(
        'hero_single_style'    => '文章顶部风格',
        'sidebar_single_style' => '文章侧边栏',
        'thumb_video_src'      => '视频封面地址',
        'cao_price'            => '价格',
        'cao_vip_rate'         => '会员折扣',
        'cao_close_novip_pay'  => '普通用户禁止购买',
        'cao_is_boosvip'       => '永久会员免费',
        'cao_expire_day'       => '购买有效期天数',
        'cao_status'           => '启用付费下载模块',
        'cao_downurl_new'      => '下载资源地址',
        'cao_demourl'          => '演示地址',
        'cao_info'             => '下载资源其他信息',
        'cao_paynum'           => '已售数量',
        'cao_video'            => '启用视频模块',
        'cao_is_video_free'    => '免费视频',
        'video_url'            => '视频播放地址',
        'post_titie'           => '自定义SEO标题',
        'keywords'             => '自定义SEO关键词',
        'description'          => '自定义SEO描述',
        'menu_icon'            => '菜单图标',
        'is_catmenu'           => '启用高级菜单文章',
    );

    $custom_meta_opt = _cao('custom_post_meta_opt', '0');

    if (!empty($custom_meta_opt) && is_array($custom_meta_opt)) {
        foreach ($custom_meta_opt as $k => $v) {
            $meta['postmeta'][$v['meta_ua']] = $v['meta_name'];
        }
    }

    //分类自定义字段类解释
    $meta['termmeta'] = array(
        'bg-image'             => '分类特色图片',
        'archive_single_style' => '分类侧边栏',
        'archive_item_style'   => '分类页列表风格',
        'seo-title'            => '分类自定义SEO标题',
        'seo-keywords'         => '分类SEO关键词',
        'seo-description'      => '分类SEO描述',
    );
    
    //评论自定义字段类解释
    $meta['commentmeta'] = array(
        'liek_num'   => '赞同数量',
        'liek_users' => '点赞用户',
    );

    return $meta;

}

/**
 * 清理删除主题自带的meta字段和key [清空数据]
 * @Author   Dadong2g
 * @DateTime 2021-05-28T17:19:51+0800
 * @param    [type]                   $metatype [description]
 * @return   [type]                             [description]
 */
function removes_ripro_meta_options($metatype = null) {
    global $wpdb;
    $i       = 0;
    $metaopt = get_ripro_v2_meta_options();
    if ($metatype == 'all') {
        foreach ($metaopt as $metatype => $arr) {
            foreach ($arr as $key => $name) {
                $wpdb->delete($wpdb->$metatype, array('meta_key' => $key));
                $i++;
            }
        }
    } else {
        foreach ($metaopt[$metatype] as $key => $name) {
            $wpdb->delete($wpdb->$metatype, array('meta_key' => $key));
            $i++;
        }
    }

    return $i;
}

/**
 * 删除清空主题自建的数据表 [清空数据]
 * @Author   Dadong2g
 * @DateTime 2021-05-28T17:36:03+0800
 * @param    string                   $value [description]
 * @return   [type]                          [description]
 */
function removes_ripro_db_table() {
    global $wpdb;
    $i        = 0;
    $ripro_db = array(
        'cao_order', //订单表
        'cao_paylog', //旧版本购买记录表
        'cao_coupon', //卡密表名称
        'cao_ref_log', //推广记录表
        'cao_down_log', //下载记录表
        'cao_mpwx_log', //微信公众号登录记录表
    );

    foreach ($ripro_db as $name) {
        $wpdb->query("DROP TABLE {$wpdb->$name}");
        $i++;
    }
    return $i;
}





/**
 * 集成主题文档
 * @Author   Dadong2g
 * @DateTime 2021-05-26T22:11:38+0800
 * @return   [type]                   [description]
 */
function ripro_v2_doc_callback(){
    $docUrl = 'https://www.kancloud.cn/rizhuti/ripro-v2/content/';
    echo '<div style="max-width:640px;margin: 20px auto;text-align: center;">';
    // echo '<iframe src="'.$docUrl.'" id="iframepage" width="100%" height="100%" frameborder="0" scrolling="yes" ></iframe>';
    echo '<a target="_blank" class="button button-primary" href="'.$docUrl.'" style=" text-align: center; width: 100%; height: 80px; line-height: 80px; font-size: 22px; "><i class="fas fa-file-word"></i> 点击查看在线文档</a>';
    echo '<br>';
    echo '<div style=" margin-top: 20px; "><a href="https://ritheme.com/" target="_blank" rel="nofollow noopener noreferrer"><img src="'.get_template_directory_uri().'/assets/img/ads.jpg" style=" width: 100%; border-radius: 8px; "></a></div>';
    echo '</div>';
}



/**
 * RiSession
 */
class RiSession {

    /**
     * start the session, after this call the PHP $_SESSION super global is available PHP session_status() !== PHP_SESSION_NONE
     */
    public static function start() {

        if ( session_status() !== PHP_SESSION_DISABLED && !session_id() ) {
            return @session_start();
        }else{
            return false;
        }
    }

    /**
     * destroy the session, this removes any data saved in the session over logout-login
     * @Author   Dadong2g
     * @DateTime 2021-05-21T10:34:36+0800
     * @return   [type]                   [description]
     */
    public static function destroy() {
        session_destroy();
    }

    /**
     * get a value from the session array
     * @Author   Dadong2g
     * @DateTime 2021-05-21T10:33:36+0800
     * @param    [type]                   $key     [description]
     * @param    string                   $default [description]
     * @return   [type]                            [description]
     */
    public static function get($key, $default = '') {
        $is_start = self::start();
        if (isset($_SESSION[$key])) {
            // session_write_close();
            return $_SESSION[$key];
        } else {
            return $default;
        }
    }

    /**
     * set a value in the session array
     * @Author   Dadong2g
     * @DateTime 2021-05-21T10:34:29+0800
     * @param    [type]                   $key   [description]
     * @param    [type]                   $value [description]
     */
    public static function set($key, $value) {
        $is_start = self::start();
        $_SESSION[$key] = $value;
        // session_write_close();
    }

}




/**
 * 是否首页模块化页面
 * @Author   Dadong2g
 * @DateTime 2021-05-16T15:49:28+0800
 * @return   boolean                  [description]
 */
function is_page_template_modular(){
    if ( is_home() ) {
        return true;
    }
    
    if (get_post_meta(get_queried_object_id(),'_wp_page_template',1) == 'pages/page-modular.php') {
        return true;
    }
    
    return false;
}


/**
 * 是否异步获取动态数据
 * @Author   Dadong2g
 * @DateTime 2021-06-22T00:38:19+0800
 * @return   boolean                  [description]
 */
function is_site_async_cache(){
    return !empty(_cao('is_site_async_cache',false));
}


/**
 * js控制台输出php调试信息
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:46:10+0800
 * @param    [type]                   $data [description]
 * @return   [type]                         [description]
 */
function php_logger($data) {
    $output = $data;
    if (is_array($output)) {
        $output = implode(',', $output);
    }
    // print the result into the JavaScript console
    echo "<script>console.log( 'PHP LOG: " . $output . "' );</script>";
}



/**
 * 注册 wp_body_open
 * @Author   Dadong2g
 * @DateTime 2021-01-30T21:15:47+0800
 * @return   [type]                   [description]
 */
if ( ! function_exists( 'wp_body_open' ) ) {
    function wp_body_open() {
        /**
         * Triggered after the opening <body> tag.
         */
        do_action( 'wp_body_open' );
    }
}


/**
 * 是否启用评论功能
 * @Author   Dadong2g
 * @DateTime 2021-04-02T20:02:21+0800
 * @return   boolean                  [description]
 */
function is_site_comments(){
    return _cao('is_site_comments');
}


/**
 * 是否启用问答社区
 * @Author   Dadong2g
 * @DateTime 2021-05-15T10:10:38+0800
 * @return   boolean                  [description]
 */
function is_site_question(){
    return _cao('is_site_question');
}






/**
 * 全站弹窗提示信息
 * @Author   Dadong2g
 * @DateTime 2021-04-15T12:12:53+0800
 * @param    string                   $title     [description]
 * @param    string                   $msg       [description]
 * @param    string                   $back_link [description]
 * @return   [type]                              [description]
 */
function ripro_wp_die($title = '', $msg = '', $back_link = '') {
    ob_start();?>
    <!doctype html>
    <html <?php language_attributes();?>>
    <head><meta charset="<?php bloginfo('charset');?>"><meta name="viewport" content="width=device-width, initial-scale=1"><link rel="profile" href="https://gmpg.org/xfn/11"><?php wp_head();?></head>
    <body <?php body_class();?>>
    <script type="text/javascript">
    jQuery(function() {
        Swal.fire({
            title: '<?php echo $title; ?>',
            html: '<?php echo $msg; ?>',
            icon: "warning",
            allowOutsideClick: !1,
            showCancelButton: true,
            confirmButtonText: '<?php echo esc_html__('首页','ripro-v2');?>',
            cancelButtonText: '<?php echo esc_html__('返回','ripro-v2');?>',
        }).then(e => {
            if (e.isConfirmed) {
                window.location.href = '<?php echo esc_url(home_url()) ?>';
            } else if (e.dismiss === Swal.DismissReason.cancel) {
                window.location.href = document.referrer;
            }

        })
    });
    </script>
    <?php wp_footer();?>
    </body></html>
    <?php echo ob_get_clean();exit;
}




/**
 * timthumb.php 裁剪
 */
if (file_exists( $timthumb_php = get_template_directory() . '/timthumb.php') ) {

    define ('IS_TIMTHUMB_PHP', true);

    function timthumb_php_src($src, $size = null, $q = 80){

        return get_template_directory_uri() . '/timthumb.php?src=' . $src . '&w=' . $size["width"] . '&h=' . $size['height'] . '&zc=1&a=c&q='.$q;

    }

}



/**
 * 链接新窗口打开
 * @Author   Dadong2g
 * @DateTime 2021-09-13T20:47:01+0800
 * @return   [type]                   [description]
 */
function _target_blank() {
    return _cao('site_main_target_blank',false) ? ' target="_blank"' : '';
}


/**
 * 是否GIF格式
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:46:27+0800
 * @param    [type]                   $url [description]
 * @return   [type]                        [description]
 */
function img_is_gif($url) {
    if (!empty($url)) {
        $path_parts = pathinfo($url);
        $extension  = $path_parts['extension'];
        return $extension == 'gif' ? true : false;
    }
    return false;
}

/**
 * 默认缩略图 随机
 * @Author   Dadong2g
 * @DateTime 2021-05-29T10:52:35+0800
 * @return   [type]                   [description]
 */
function _the_theme_thumb() {
    //=随机缩略图
    global $post;
    $rand_gallery = _cao('rand_default_thumb', '');
    $gallery_ids  = explode(',', $rand_gallery);
    $default      = _cao('default_thumb') ? _cao('default_thumb') : get_template_directory_uri() . '/assets/img/thumb.jpg';
    if (!empty($rand_gallery) && !empty($gallery_ids)) {
        $gallery_count = count($gallery_ids);
        $iv            = intval(substr($post->ID, -1));

        if ( !isset($gallery_ids[$iv]) ) {
            $iv = mt_rand(0, $gallery_count-1);
        }
        
        if ($_thum = wp_get_attachment_image_src($gallery_ids[$iv], 'thumbnail')) {
            $thum = $_thum;
        } else {
            $thum = wp_get_attachment_image_src($gallery_ids[$iv], 'full');
        }
        if (!empty($thum[0])) {
            return $thum[0];
        }
    }
    return $default;
}



/**
 * 默认头像
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:46:40+0800
 * @return   [type]                   [description]
 */
function _the_theme_avatar() {
    return get_template_directory_uri() . '/assets/img/avatar.png';
}

/**
 * 默认主题名称
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:46:57+0800
 * @return   [type]                   [description]
 */
function _the_theme_name(){
    $current_theme = wp_get_theme();
    return $current_theme->get('Name');
}

/**
 * 默认主题版本
 * @Author   Dadong2g
 * @DateTime 2021-06-07T11:55:45+0800
 * @return   [type]                   [description]
 */
function _the_theme_version(){

    $current_theme = wp_get_theme();
    if ($current_theme->parent_theme) {
        $current_theme = wp_get_theme($current_theme->parent_theme);
    }
    return $current_theme->get('Version');

}


/**
 * 主题地址
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:47:20+0800
 * @return   [type]                   [description]
 */
function _the_theme_aurl(){
    $current_theme = wp_get_theme();
    if ($current_theme->parent_theme) {
        $current_theme = wp_get_theme($current_theme->parent_theme);
    }
    return $current_theme->get('ThemeURI');
}




/**
 * 输出缩略图地址
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:47:20+0800
 * @return   [type]                   [description]
 */
if (!function_exists('_get_post_thumbnail_url')) {
    function _get_post_thumbnail_url($post = null, $size = 'thumbnail') {
        if (empty($post)) {
            global $post;
        }

        if (is_numeric($post)) {
            $post = get_post($post);
        }

        if (empty($post)) {
            return _the_theme_thumb();
        }

        if ($size == 'thumbnail' || is_array($size)) {
            $_p_size = 'thumbnail';
        }else{
            $_p_size = $size;
        }


        if (has_post_thumbnail($post)) {
            $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');

            if (empty($thumbnail_src) || empty($thumbnail_src[0])) {
                return _the_theme_thumb();
            }

            if (!img_is_gif($thumbnail_src[0])) {
                $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), $_p_size);
            }

            $post_thumbnail_src = $thumbnail_src[0];

        } elseif ( _cao('is_post_one_thumbnail',true) && !empty($post->post_content) ) {
            // 自动抓取文章第一张图片
            ob_start();
            ob_end_clean();
            $post_thumbnail_src = _the_theme_thumb();
            $output             = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
            if (!empty($matches[1][0])) {
                $post_thumbnail_src = $matches[1][0]; //获取该图片 src
            }

        } else {
            $post_thumbnail_src = _the_theme_thumb(); //如果日志中没有图片，则显示默认图片
        }

        //缩略图大小
        if (is_array($size)) {
            $_size_px = $size;
        }else{
            $_size_px = _get_post_thumbnail_size();
        }


        //云储存裁剪适配
        if ( $size=='thumbnail' && _cao('is_img_cloud_storage',false) ) {
            // 判断裁剪模式
            $storage_domain = _cao('img_cloud_storage_domain');
            $storage_param = _cao('img_cloud_storage_param');
            $storage_param = preg_replace('/%height%/i',$_size_px['height'],$storage_param);
            $storage_param = preg_replace('/%width%/i',$_size_px['width'],$storage_param);

            if (strpos($post_thumbnail_src,$storage_domain) !== false) {
                return $post_thumbnail_src.$storage_param;
            }
        }

        //TIMTHUMB裁剪
        if ( defined('IS_TIMTHUMB_PHP') && IS_TIMTHUMB_PHP && ( $size == 'thumbnail' || is_array($size) ) ) {
            $post_thumbnail_src = timthumb_php_src($post_thumbnail_src, $_size_px,90);
        }


        return $post_thumbnail_src;
    }
}


/**
 * 获取文章缩略图尺寸
 * @Author   Dadong2g
 * @DateTime 2021-05-10T11:45:42+0800
 * @return   [type]                   [description]
 */
function _get_post_thumbnail_size(){
    $_size_px = _cao('post_thumbnail_size');
    if (empty($_size_px)) {
        $_size_px['width']  = 300;
        $_size_px['height'] = 200;
    }
    return $_size_px;
}

/**
 * 获取分类缩略图尺寸
 * @Author   Dadong2g
 * @DateTime 2021-05-11T13:12:50+0800
 * @return   [type]                   [description]
 */
function _get_cat_post_thumbnail_size( $cat=0 ){
    if (get_term_meta($cat, 'is_thumb_px', true)) {
        $cat_thumb_px = get_term_meta($cat, 'thumb_px', true); //缩略图高度
    }else{
        $cat_thumb_px = _get_post_thumbnail_size();
    }

    return $cat_thumb_px;
}





/**
 * 获取商品类型文章缩略图
 * @Author   Dadong2g
 * @DateTime 2021-04-05T18:43:11+0800
 * @param    [type]                   $post [description]
 * @param    string                   $size [description]
 * @return   [type]                         [description]
 */
if (!function_exists('_get_post_shop_thumbnail_url')) {
    
    function _get_post_shop_thumbnail_url($post = null, $size = 'thumbnail') {
        if (empty($post)) {
            global $post;
        }
        
        if (is_numeric($post)) {
            $post = get_post($post);
        }

        if (empty($post)) {
            return false;
        }

        if (has_post_thumbnail($post)) {

            $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), $size);

            if (empty($thumbnail_src) || empty($thumbnail_src[0])) {
                return false;
            }

            if (!img_is_gif($thumbnail_src[0])) {
                $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), $size);
            }else{
                $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
            }

            $post_thumbnail_src = $thumbnail_src[0];

        } elseif ( _cao('is_post_one_thumbnail',true) && !empty($post->post_content) ) {
            $post_thumbnail_src = '';
            $output             = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
            $post_thumbnail_src = (!empty($matches[1][0])) ? $matches[1][0] : null; //获取该图片 src
            if (empty($post_thumbnail_src)) {
                $post_thumbnail_src = _the_theme_thumb(); //如果日志中没有图片，则显示默认图片
            }
        } else {
            return false;
        }

        //云储存适配
        if ( $size=='thumbnail' && _cao('is_img_cloud_storage',false) ) {
            // 判断裁剪模式
            $storage_domain = _cao('img_cloud_storage_domain');
            $storage_param = _cao('img_cloud_storage_param');
            if (strpos($post_thumbnail_src,$storage_domain) !== false) {
                $post_thumbnail_src = $post_thumbnail_src.$storage_param;
            }
        }

        return $post_thumbnail_src;
    }
}


/**
 * 根据模式输出缩略图img 延迟加载html标签
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:47:20+0800
 * @return   [type]                   [description]
 */
if (!function_exists('_get_post_media')) {
    function _get_post_media($post = null, $size = 'thumbnail',$video = true) {
        if (empty($post)) {
            global $post;
        }elseif (is_numeric($post)) {
            $post = get_post($post);
        }

        $_size_px = _get_post_thumbnail_size();

        $src = _get_post_thumbnail_url($post, $size);
        
        //加载前图片 lazyload
        // $lazyload_src = 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==';
        $lazyload_src = _cao('default_thumb_lazyload');
        
        //获取比例
        $ratio = $_size_px['height'] / $_size_px['width'] * 100 . '%';

        if ( $video && !wp_is_mobile() && get_post_format($post->ID) == 'video' && $mp4 = _get_post_thumb_video_src($post) ) {
            $img   = '<div class="entry-media video-thum" data-mp4="'.$mp4.'">';
        }else{
            $img   = '<div class="entry-media">';
        }

        $img .= '<div class="placeholder" style="padding-bottom: ' . esc_attr($ratio) . '">';

        $img .= '<a'. _target_blank() .' href="' . get_permalink($post->ID) . '" title="' . get_the_title($post->ID) . '" rel="nofollow noopener noreferrer">';
        $img .= '<img class="lazyload" data-src="' . $src . '" src="'.$lazyload_src.'" alt="' . get_the_title() . '" />';
        
        $img .= '</a>';
        $img .= '</div>';
        $img .= '</div>';
        return $img;
    }
}


/**
 * 获取wp自带编辑器插入的第一个视频地址
 * @Author   Dadong2g
 * @DateTime 2021-04-20T13:51:02+0800
 * @return   [type]                   [description]
 */
function _get_post_thumb_video_src($post = null){
    if (empty($post)) {
        global $post;
    }

    $first_video = get_post_meta($post->ID, 'thumb_video_src', true);

    if ( empty($first_video) ) {
        // 获取wp自带的视频插入
        $output = preg_match_all('/\[video[^<>]*mp4=[\"]([^\"]+)[\"][^<>]*\]/im', $post->post_content, $matches);
        if (!empty($matches[1][0])) {
            $first_video = $matches[1][0];
        }
    }

    if ( empty($first_video) ) {
        $first_video = '';
    }
    
    return $first_video;
}


/**
 * 获取文章标题
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:48:23+0800
 * @param    array                    $options [description]
 * @return   [type]                            [description]
 */
if (!function_exists('rizhuti_v2_entry_title')) {
    function rizhuti_v2_entry_title($options = array()){
        $options = array_merge( array( 
            'outside_loop' => false,
            'classes' => 'entry-title', 
            'tag' => 'h2',
            'link' => true,
        ), $options);

        $post_id = $options['outside_loop'] ? get_queried_object() : get_the_ID();
        if ( $options['link'] ) {
            echo '<' . $options['tag'] . ' class="'.esc_attr( $options['classes']).'"><a'. _target_blank() .' href="' . esc_url( get_permalink( $post_id ) ) . '" title="' . get_the_title( $post_id ) . '" rel="bookmark">' . get_the_title( $post_id ) . '</a></' . $options['tag'] . '>';
        } else {
            echo '<' . $options['tag'] . ' class="'.esc_attr( $options['classes']).'">' . get_the_title( $post_id ) . '</' . $options['tag'] . '>';
        }
    }
}


/**
 * 获取meta字段
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:48:34+0800
 * @param    array                    $options [description]
 * @return   [type]                            [description]
 */
if (!function_exists('rizhuti_v2_entry_meta')) {
    function rizhuti_v2_entry_meta($opt = array()){
        $options = array_merge( array( 
            'outside_loop' => false,
            'author' => false, 
            'category' => false,
            'date' => false,
            'comment' => false,
            'favnum' => false,
            'views' => false,
            'shop' => true,
            'edit' => false,
        ), $opt);

        $post_id = $options['outside_loop'] ? get_queried_object() : get_the_ID();

        if ( in_array(true,$options) ) : ?>
          <div class="entry-meta">
            
            <?php if ( $options['author'] ) :
              $author_id = (int)get_post_field( 'post_author', $post_id );?>
              <span class="meta-author">
                <a href="<?php echo esc_url( get_author_posts_url($author_id,get_the_author_meta( 'display_name', $author_id ) )); ?>" title="<?php echo get_the_author_meta( 'display_name', $author_id );?>"><?php echo get_avatar($author_id);?>
                </a>
              </span>
            <?php endif;

            //分类信息
            if ( $options['category'] && $categories=get_the_category( $post_id ) ) : ?>
              <span class="meta-category">
                <a href="<?php echo esc_url( get_category_link( $categories[0]->term_id ) ); ?>" rel="category"><?php echo esc_html( $categories[0]->name ); ?></a>
              </span>
            <?php endif;

            //时间日期
            if ( $options['date'] ) : ?>
              <span class="meta-date">
                  <time datetime="<?php echo esc_attr( get_the_date( 'c', $post_id ) ); ?>">
                    <i class="fa fa-clock-o"></i>
                    <?php
                      if ( _cao('is_post_data_diff',true) ) {
                        echo sprintf( __( '%s前','ripro-v2' ), human_time_diff( get_the_time( 'U', $post_id ), current_time( 'timestamp' ) ) );
                      } else {
                        echo esc_html( get_the_date('Y-m-d') );
                      }
                    ?>
                  </time>
              </span>
            <?php endif;
            

            if ( $options['comment'] && ! post_password_required( $post_id ) && ( comments_open( $post_id ) || get_comments_number( $post_id ) ) ) : ?>
              <span class="meta-comment">
                <a href="<?php echo esc_url( get_the_permalink( $post_id ) . '#comments' ); ?>">
                   <i class="fa fa-comments-o"></i>
                  <?php printf( _n( '%s', esc_html( get_comments_number( $post_id ) ), 'ripro-v2' ) ); ?>
                </a>
              </span>
            <?php endif;

            if ($options['favnum']) : ?>
                <span class="meta-favnum"><i class="far fa-star"></i> <?php echo _get_post_fav($post_id); ?></span>
            <?php endif;

            if ($options['views']) : ?>
                <span class="meta-views"><i class="fa fa-eye"></i> <?php echo _get_post_views($post_id); ?></span>
            <?php endif;

            //付费文章类型
            if (  $options['shop'] && !is_close_site_shop() && is_shop_post() ) : 
                $this_price = get_post_price($post_id);

                $this_icon = site_mycoin('icon');
                if ( $this_price == 0 ) {
                    $price_meta = esc_html__('免费','ripro-v2');
                }elseif( $this_price == -1 ) {
                    $price_meta = esc_html__('专属','ripro-v2');
                    $this_icon = 'fa fa-diamond';
                }else{
                    $price_meta = $this_price;
                }
                echo '<span class="meta-shhop-icon"><i class="'.$this_icon.'"></i> '.$price_meta.'</span>';

            endif;

            //编辑按钮
            if ($options['edit']) : ?>
                <span class="meta-edit"><?php edit_post_link('[编辑]'); ?></span>
            <?php endif; ?>


          </div>
        <?php endif;
    }
}



function get_post_type_icon($post_ID = null){
    if (empty($post_ID)) {
        global $post;
        $post_ID = $post->ID;
    }

    $format = get_post_format($post_ID);
    switch ( $format ) {
        case 'video' :
          $icon = '<i class="fas fa-play-circle"></i>';
          break;
        case 'gallery' : 
          $icon = '<i class="fas fa-image"></i>';
          break;
        case 'image' : 
          $icon = '<i class="fas fa-image"></i>';
          break;
        case 'audio' :
          $icon = '<i class="fas fa-file-audio"></i>';
          break;
        default : 
          $icon = '';
    }

    if (is_post_shop_video($post_ID)) {
        $icon = '<i class="fas fa-play-circle"></i>';
    }



    if (!empty($icon)) {
        return '<span class="meta-post-type">'.$icon.'</span>';
    }
    return false;
}



/**
 * 获取摘要描述
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:48:45+0800
 * @param    string                   $limit [description]
 * @return   [type]                          [description]
 */
if (!function_exists('ripro_v2_excerpt')) {
    function ripro_v2_excerpt( $limit='46',$post_id=null ) {
        $excerpt = get_the_excerpt($post_id);
        if (empty($excerpt)) {
            $excerpt = get_the_content();
        }
        return wp_trim_words( strip_shortcodes( $excerpt ),$limit,'...' );
    }
}


/**
 * 获取分类多个html标签
 * @Author   Dadong2g
 * @DateTime 2021-05-21T11:55:55+0800
 * @param    integer                  $num [description]
 * @return   [type]                        [description]
 */
function ripro_v2_category_dot( $num=2 ){
    $i = 0;
    $categories=get_the_category();
    echo '<span class="meta-category-dot">';

    foreach ( $categories as $k => $c ) {
        $i++;
        if ($i > $num ) break;
        echo '<a href="'.esc_url( get_category_link( $c->term_id ) ).'" rel="category"><i class="dot"></i>'.esc_html( $c->name ).'</a>';
    }

    echo '</span>';
}

/**
 * logo html
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:49:54+0800
 * @param    array                    $options [description]
 * @return   [type]                            [description]
 */
function ripro_v2_logo($options = array()) {
    $options  = array_merge(array('contrary' => true), $options);
    $logo_src = _cao('site_logo');?>
  <div class="logo-wrapper">
    <?php if (!empty($logo_src)): ?>
      <a href="<?php echo esc_url(home_url('/')); ?>">
        <img class="logo regular" src="<?php echo esc_url($logo_src); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
      </a>
    <?php else: ?>
      <a class="logo text" href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html(get_bloginfo('name')); ?></a>
    <?php endif;?>

  </div> <?php
}


/**
 * 设置字段优先级比对
 * @Author   Dadong2g
 * @DateTime 2021-05-21T11:56:53+0800
 * @param    [type]                   $global   [description]
 * @param    [type]                   $override [description]
 * @return   [type]                             [description]
 */
function ripro_v2_compare_options($global, $override) {
    if (_cao('is_compare_options_to_global',false)) {
        return $global;
    }
    if ($global == $override || empty($override)) {
        return $global;
    } else {
        return $override;
    }
}

/**
 * 顶部是否显示hero效果
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:50:20+0800
 * @return   [type]                   [description]
 */
function ripro_v2_show_hero() {
    if (!is_singular('post') || is_singular('question')) {
        return false;
    }
    
    if ( !is_close_site_shop() && _cao('is_single_shop_template',true) && is_post_shop_down() ) {
        return 'wide';
    }

    if (is_post_shop_video()) {
        if (get_post_meta(get_the_ID(), 'hero_single_style', 1) == 'none') {
            update_post_meta(get_the_ID(),'hero_single_style','wide');
        }
        return 'wide';
    }

    return (is_singular()) && ripro_v2_compare_options(_cao('hero_single_style', 'none'), get_post_meta(get_the_ID(), 'hero_single_style', 1)) != 'none';
}

/**
 * 侧边栏风格
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:50:35+0800
 * @return   [type]                   [description]
 */
function ripro_v2_sidebar() {

    if ( is_page_template_modular() || !empty($_GET['ajax_sidebar']) ) {
        return 'none';
    }

    if (is_singular('post') || ( is_page() && !is_page_template_modular() )) {
        return ripro_v2_compare_options(_cao('sidebar_single_style', 'right'), get_post_meta(get_the_ID(), 'sidebar_single_style', 1));
    } elseif ( is_category() || taxonomy_exists('series') ) {
        $term_meta = get_term_meta(get_queried_object_id(), 'archive_single_style', true);
        return ripro_v2_compare_options(_cao('archive_single_style', 'right'), $term_meta);
    } elseif (is_archive() || is_search()) {
       return 'none';
    } elseif (is_home()) {
        return 'right';
    }
    return 'none';
}


/**
 * 列表风格
 * @Author   Dadong2g
 * @DateTime 2021-05-21T12:01:11+0800
 * @return   [type]                   [description]
 */
function ripro_v2_item_style() {
    $options = _cao('archive_item_style', 'list');
    if (is_category() || taxonomy_exists('series')) {
        $term_meta = get_term_meta(get_queried_object_id(), 'archive_item_style', true);
        return ripro_v2_compare_options($options, $term_meta);
    }elseif (is_archive() || is_search()) {
       return $options;
    } elseif (is_home()) {
        return $options;
    }
    return 'list';
}


/**
 * col属性
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:50:45+0800
 * @param    [type]                   $sidebar [description]
 * @return   [type]                            [description]
 */
function ripro_v2_column_classes($sidebar) {
    $content_column_class = 'content-column col-lg-9';
    $sidebar_column_class = 'sidebar-column col-lg-3';
    if ($sidebar == 'none') {
        $content_column_class = 'col-lg-12';
    }
    return apply_filters('ripro_v2_column_classes',array($content_column_class, $sidebar_column_class));
}


/**
 * 获取当前查询
 * @Author   Dadong2g
 * @DateTime 2021-09-11T12:29:56+0800
 * @param    boolean                  $query [description]
 * @return   [type]                          [description]
 */
function ripro_v2_get_current_query( $query = false ) {

    if ( ! $query ) {
        global $wp_query;
        $query = $wp_query;
    }

   return $query;
}


/**
 * 翻页导航
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:50:49+0800
 * @param    integer                  $range [description]
 * @param    array                    $args  [description]
 * @return   [type]                          [description]
 */
function ripro_v2_pagination($range = 9, $args = array()) {
    global $paged, $wp_query, $page, $numpages, $multipage;

    $site_pagination = _cao('site_pagination','numeric');
    if ($site_pagination == 'navigation') {
        $range = 0;
    }

    if (($args && $args['numpages'] > 1) || (isset($multipage) && $multipage && is_single())) {
        if ($args) {
            $page     = $args['paged'];
            $numpages = $args['numpages'];
        }
        echo '<div class="pagination justify-content-center">';
        $prev = $page - 1;
        if ($prev > 0) {
            echo str_replace('<a', '<a class="prev"', _wp_link_page($prev) . __('<i class="fa fa-chevron-left"></i> 上一页', 'ripro-v2') . '</a>');
        }

        if ($numpages > $range) {
            if ($page < $range) {
                for ($i = 1; $i <= ($range + 1); $i++) {
                    if ($i == $page) {
                        echo str_replace('<a', '<a class="current"', _wp_link_page($i)) . $i . "</a>";
                    } else {
                        echo _wp_link_page($i) . $i . "</a>";
                    }
                }
            } elseif ($page >= ($numpages - ceil(($range / 2)))) {
                for ($i = $numpages - $range; $i <= $numpages; $i++) {
                    if ($i == $page) {
                        echo str_replace('<a', '<a class="current"', _wp_link_page($i)) . $i . "</a>";
                    } else {
                        echo _wp_link_page($i) . $i . "</a>";
                    }
                }
            } elseif ($page >= $range && $page < ($numpages - ceil(($range / 2)))) {
                for ($i = ($page - ceil($range / 2)); $i <= ($page + ceil(($range / 2))); $i++) {
                    if ($i == $page) {
                        echo str_replace('<a', '<a class="current"', _wp_link_page($i)) . $i . "</a>";
                    } else {
                        echo _wp_link_page($i) . $i . "</a>";
                    }
                }
            }
        } else {
            for ($i = 1; $i <= $numpages; $i++) {
                if ($i == $page) {
                    echo str_replace('<a', '<a class="current"', _wp_link_page($i)) . $i . "</a>";
                } else {
                    echo _wp_link_page($i) . $i . "</a>";
                }
            }
        }

        $next = $page + 1;
        if ($next <= $numpages) {
            echo str_replace('<a', '<a class="next"', _wp_link_page($next) . __('下一页 <i class="fa fa-chevron-right"></i>', 'ripro-v2') . '</a>');
        }
        echo '</div>';
    } else if (($max_page = $wp_query->max_num_pages) > 1) {
        echo ' <div class="pagination justify-content-center">';
        if (!$paged) {
            $paged = 1;
        }

        echo '<span>' . $paged . '/' . $max_page . '</span>';
        previous_posts_link(__('<i class="fa fa-chevron-left"></i> 上一页', 'ripro-v2'));
        if ($max_page > $range) {
            if ($paged < $range) {
                for ($i = 1; $i <= ($range + 1); $i++) {
                    echo "<a href='" . get_pagenum_link($i) . "'";
                    if ($i == $paged) {
                        echo " class='current'";
                    }

                    echo ">" . $i . "</a>";
                }
            } elseif ($paged >= ($max_page - ceil(($range / 2)))) {
                for ($i = $max_page - $range; $i <= $max_page; $i++) {
                    echo "<a href='" . get_pagenum_link($i) . "'";
                    if ($i == $paged) {
                        echo " class='current'";
                    }

                    echo ">" . $i . "</a>";
                }
            } elseif ($paged >= $range && $paged < ($max_page - ceil(($range / 2)))) {
                for ($i = ($paged - ceil($range / 2)); $i <= ($paged + ceil(($range / 2))); $i++) {
                    echo "<a href='" . get_pagenum_link($i) . "'";
                    if ($i == $paged) {
                        echo " class='current'";
                    }

                    echo ">" . $i . "</a>";
                }
            }
        } else {
            for ($i = 1; $i <= $max_page; $i++) {
                echo "<a href='" . get_pagenum_link($i) . "'";
                if ($i == $paged) {
                    echo " class='current'";
                }

                echo ">$i</a>";
            }
        }
        next_posts_link(__('下一页 <i class="fa fa-chevron-right"></i>', 'ripro-v2'));
        echo '</div>';
    }

    if ( !is_singular() && strpos( $site_pagination, 'infinite' ) !== false ) : ?>
      <div class="infinite-scroll-status">
        <div class="infinite-scroll-request"><i class="fa fa-spinner fa-spin " style=" font-size: 30px; "></i></div>
      </div>
      <div class="infinite-scroll-action">
        <div class="infinite-scroll-button btn btn-dark"><?php echo apply_filters( 'rizhuti_v2_infinite_button_load', esc_html__( '加载更多', 'ripro-v2' ) ); ?></div>
      </div>
    <?php endif;

}

/**
 * 上一页翻页钩子替换
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:50:56+0800
 * @param    [type]                   $attr [description]
 * @return   [type]                         [description]
 */
function ripro_v2_prev_posts_link_attr($attr) {
    return $attr . ' class="prev"';
}
add_filter('previous_posts_link_attributes', 'ripro_v2_prev_posts_link_attr');

/**
 * 下一页翻页钩子替换
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:51:01+0800
 * @param    [type]                   $attr [description]
 * @return   [type]                         [description]
 */
function ripro_v2_next_posts_link_attr($attr) {
    return $attr . ' class="next"';
}
add_filter('next_posts_link_attributes', 'ripro_v2_next_posts_link_attr');




/**
 * 主题自定义参数类分页导航
 * @Author   Dadong2g
 * @DateTime 2021-05-20T20:58:24+0800
 * @return   [type]                   [description]
 */
function ripro_v2_custom_pagination($pagenum=0,$max_num_pages=0){

    $page_links = paginate_links( array(
        'base' => add_query_arg( 'pagenum', '%#%' ),
        'format' => '',
        'prev_text' => __('<i class="fa fa-chevron-left"></i> 上一页', 'ripro-v2'),
        'next_text' => __('下一页 <i class="fa fa-chevron-right"></i>', 'ripro-v2'),
        'total' => intval($max_num_pages),
        'current' => intval($pagenum)
    ) );
     
    if ( $page_links ) {
        echo '<div class="pagination justify-content-center">' . $page_links . '</div>';
    }

}




/**
 * 面包屑导航
 * @Author   Dadong2g
 * @DateTime 2021-05-26T11:11:24+0800
 * @param    string                   $class [description]
 * @return   [type]                          [description]
 */
function ripro_v2_breadcrumb($class = 'breadcrumb') {
    global $post, $wp_query;
    echo '<ol class="' . $class . '">'.__('当前位置：', 'ripro-v2').'<li class="home"><i class="fa fa-home"></i> <a href="' . home_url() . '">' . __('首页', 'ripro-v2') . '</a></li>';

    if (is_category()) {
        $cat_obj   = $wp_query->get_queried_object();
        $thisCat   = $cat_obj->term_id;
        $thisCat   = get_category($thisCat);
        $parentCat = get_category($thisCat->parent);

        if ($thisCat->parent != 0) {
            echo _get_category_parents($parentCat);
        }

        echo '<li class="active">';
        single_cat_title();
        echo '</li>';
    } elseif (is_day()) {
        echo '<li><a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> </li>';
        echo '<li><a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a> </li>';
        echo '<li class="active">' . get_the_time('d') . '</li>';
    } elseif (is_month()) {
        echo '<li><a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> </li>';
        echo '<li class="active">' . get_the_time('F') . '</li>';
    } elseif (is_year()) {
        echo '<li class="active">' . get_the_time('Y') . '</li>';
    } elseif (is_attachment()) {
        echo '<li class="active">';
        the_title();
        echo '</li>';
    } elseif (is_single()) {
        $post_type = get_post_type();
        if ($post_type == 'post') {
            $cat = get_the_category();
            $cat = isset($cat[0]) ? $cat[0] : 0;
            echo _get_category_parents($cat);
            echo '<li class="active">'.__('正文', 'ripro-v2').'</li>';
        } else if ($post_type == 'question') {
            global $post;
            $taxonomy = 'question_cat';
            $terms = get_the_terms($post->ID, $taxonomy);
            $links = array();
            if ($terms && !is_wp_error($terms)) {
                foreach ($terms as $c) {
                    $parents = _get_term_parents($c->term_id, $taxonomy, true, ', ', false, array($c->term_id));
                    if ($parents != '') {
                        $parents_arr = explode(', ', $parents);
                        foreach ($parents_arr as $p) {
                            if ($p != '') {$links[] = $p;}
                        }
                    }
                }
                foreach ($links as $link) {
                    echo '<li>' . $link . '</li>';
                    echo '<li class="active">'.__('问题详情', 'ripro-v2').'</li>';
                }
            }
        } else {
            $obj = get_post_type_object($post_type);
            echo '<li class="active">';
            echo $obj->labels->singular_name;
            echo '</li>';
        }
    } elseif (is_page() && !$post->post_parent) {
        echo '<li class="active">';
        the_title();
        echo '</li>';
    } elseif (is_page() && $post->post_parent) {
        $parent_id   = $post->post_parent;
        $breadcrumbs = array();
        while ($parent_id) {
            $page          = get_post($parent_id);
            $breadcrumbs[] = '<li><a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a></li>';
            $parent_id     = $page->post_parent;
        }
        $breadcrumbs = array_reverse($breadcrumbs);
        foreach ($breadcrumbs as $crumb) {
            echo $crumb;
        }

        echo '<li class="active">';
        the_title();
        echo '</li>';
    } elseif (is_search()) {
        $kw = get_search_query();
        $kw = !empty($kw) ? $kw : __('无', 'ripro-v2');
        echo '<li class="active">' . sprintf(__('搜索: %s', 'ripro-v2'), $kw) . '</li>';
    } elseif (is_tag()) {
        echo '<li class="active">';
        single_tag_title();
        echo '</li>';
    } elseif (is_author()) {
        global $author;
        $userdata = get_userdata($author);
        echo '<li class="active">' . $userdata->display_name . '</li>';
    } elseif (is_404()) {
        echo '<li class="active">' . __('404 ERROR', 'ripro-v2') . '</li>';
    }

    if (get_query_var('paged')) {
        echo '<li class="active">';
        echo sprintf(__('第%s页', 'ripro-v2'), get_query_var('paged'));
        echo '</li>';
    }

    echo '</ol>';
}




/**
 * 生产二维码地址
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:51:21+0800
 * @param    [type]                   $text [description]
 * @return   [type]                         [description]
 */
function getQrcodeApi($text) {
    $api_url = get_template_directory_uri() . '/inc/qrcode.php?data=';
    return $api_url . $text;
}


/**
 * 判断是否微信访问
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:51:26+0800
 * @return   boolean                  [description]
 */
function is_weixin_visit() {
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
        return true;
    } else {
        return false;
    }
}


/**
 * 获取客户端IP
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:51:31+0800
 * @return   [type]                   [description]
 */
function get_client_ip() {
    if (getenv('HTTP_CLIENT_IP')) {
        $ip = getenv('HTTP_CLIENT_IP');
    }
    if (getenv('HTTP_X_REAL_IP')) {
        $ip = getenv('HTTP_X_REAL_IP');
    } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
        $ip  = getenv('HTTP_X_FORWARDED_FOR');
        $ips = explode(',', $ip);
        $ip  = $ips[0];
    } elseif (getenv('REMOTE_ADDR')) {
        $ip = getenv('REMOTE_ADDR');
    } else {
        $ip = '0.0.0.0';
    }
    return $ip;
}


/**
 * 生产邮箱验证码
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:51:36+0800
 * @param    [type]                   $email [description]
 */
function set_verify_email_code($email) {
    $originalcode = '0,1,2,3,4,5,6,7,8,9';
    $originalcode = explode(',', $originalcode);
    $countdistrub = 10;
    $_dscode      = "";
    $counts       = 6;
    for ($j = 0; $j < $counts; $j++) {
        $dscode = $originalcode[rand(0, $countdistrub - 1)];
        $_dscode .= $dscode;
    }

    RiSession::set('ripro_verify_email_code',strtolower($_dscode));
    RiSession::set('ripro_verify_email',$email);

    $message = esc_html__('验证码：','ripro-v2') . $_dscode;
    return _sendMail($email, '验证码', $message);
}


/**
 * 发送html格式邮件
 * @Author   Dadong2g
 * @DateTime 2021-05-20T15:51:37+0800
 * @param    [type]                   $email   [description]
 * @param    [type]                   $title   [description]
 * @param    [type]                   $message [description]
 * @return   [type]                            [description]
 */
function _sendMail($email, $title, $message) {
    $headers    = array('Content-Type: text/html; charset=UTF-8');
    return wp_mail($email, $title, $message, $headers);
}



/**
 * 是否一键登录密码
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:52:01+0800
 * @return   boolean                  [description]
 */
function is_oauth_password(){
    global $current_user;
    $array = array('qq','weixin','mpweixin','weibo');
    foreach ($array as $type) {
        $p2=get_user_meta($current_user->ID,'open_' . $type . '_openid',1);
        if (wp_check_password(md5($p2), $current_user->data->user_pass, $current_user->ID )) {
            return true;
        }
    }
    return false;
}


/**
 * 获取腾讯防水墙按钮
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:52:07+0800
 * @param    boolean                  $script [description]
 * @return   [type]                           [description]
 */
function qq_captcha_btn($script=true,$clsses='col-12'){
    $id = _cao('qq_007_captcha_appid');
    if (!_cao('is_qq_007_captcha')) return;
    echo '<div class="'.$clsses.'"><button type="button" class="TencentCaptchaBtn btn btn-light w-100 mb-3" id="TencentCaptchaBtn" data-appid="'.$id.'" data-cbfn="qq_aptcha_callback"><span class="spinner-grow spinner-grow-sm text-primary mr-2" role="status" aria-hidden="true"></span>'.esc_html__('点击安全验证','ripro-v2').'</button></div>';
    
}



/**
 * 验证防水墙
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:52:24+0800
 * @return   [type]                   [description]
 */
function ajax_qq_captcha_verify(){
    header('Content-type:application/json; Charset=utf-8');
    $url = 'https://ssl.captcha.qq.com/ticket/verify';
    $appid = _cao('qq_007_captcha_appid');
    $AppSecretKey = _cao('qq_007_captcha_appkey');
    $Ticket = isset($_POST['Ticket']) ? $_POST['Ticket'] : '';
    $Randstr = isset($_POST['Randstr']) ? $_POST['Randstr'] : '';
    $UserIP = get_client_ip();
    $params = array(
        "aid" => $appid,
        "AppSecretKey" => $AppSecretKey,
        "Ticket" => $Ticket,
        "Randstr" => $Randstr,
        "UserIP" => $UserIP
    );
    $data = http_build_query($params);
    $result = RiProNetwork::get($url.'?'.$data);
    $res = json_decode($result, true);
    if (isset($res) && $res['response']==1) {
        RiSession::set('is_qq_captcha_verify',1);
    }else{
        RiSession::set('is_qq_captcha_verify',0);
    }
    echo $result;exit;
}
add_action('wp_ajax_qq_captcha_verify', 'ajax_qq_captcha_verify');
add_action('wp_ajax_nopriv_qq_captcha_verify', 'ajax_qq_captcha_verify');


/**
 * 是否需要腾讯验证码验证
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:52:46+0800
 * @return   [type]                   [description]
 */
function qq_captcha_verify(){
   
    if (empty(_cao('is_qq_007_captcha'))) {
        return true;
    }

    if (RiSession::get('is_qq_captcha_verify',0) != 1) {
        return false;
    }

    return true;
}

/**
 * 是否需要邮箱验证
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:52:51+0800
 * @param    string                   $email [description]
 * @param    string                   $code  [description]
 * @return   [type]                          [description]
 */
function email_captcha_verify($email='',$code=''){
    $is_verify = _cao('is_site_email_captcha_verify');
    if (!$is_verify) {
        return true;
    }
    if (empty($email) || empty($code)) {
        return false;
    }

    if ( $code != RiSession::get('ripro_verify_email_code','') || $email!= RiSession::get('ripro_verify_email','') ) {
        return false;
    }

    return true;
}

/**
 * 当前页面url
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:53:00+0800
 * @return   [type]                   [description]
 */
function curPageURL() {
    
    //使用wp自带模式获取 修复微信内端口无法访问问题
    global $wp;
    return home_url(add_query_arg(array(),$wp->request));
    
    $pageURL = 'http';
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

/**
 * 用户中心页面url
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:53:05+0800
 * @param    string                   $action [description]
 * @return   [type]                           [description]
 */
function get_user_page_url($action='') {
    $url = apply_filters( 'rizhuti_user_page_url',esc_url(home_url('/user')) );
    if (!empty($action)) {
        $url = $url.'/'.$action;
    }
    return esc_url($url);
}


/**
 * 用户中心页面菜单参数配置
 * @Author   Dadong2g
 * @DateTime 2021-01-23T09:38:44+0800
 * @return   [type]                   [description]
 */
function user_page_action_param_opt($is_array_merge=false){
    
    $param_shop = [
        'coin'=> ['action'=>'coin','name'=>esc_html__('我的余额','ripro-v2'),'icon'=>site_mycoin('icon').' nav-icon'],
        'vip'=> ['action'=>'vip','name'=>esc_html__('我的会员','ripro-v2'),'icon'=>'fa fa-diamond nav-icon'],
        'order'=> ['action'=>'order','name'=>esc_html__('购买记录','ripro-v2'),'icon'=>'fas fa-shopping-basket nav-icon'],
        'down'=> ['action'=>'down','name'=>esc_html__('下载记录','ripro-v2'),'icon'=>'fas fa-cloud-download-alt nav-icon'],
        'fav'=> ['action'=>'fav','name'=>esc_html__('我的收藏','ripro-v2'),'icon'=>'far fa-star nav-icon'],
        'aff'=> ['action'=>'aff','name'=>esc_html__('推广中心','ripro-v2'),'icon'=>'fas fa-hand-holding-usd nav-icon'],
        'tou'=> ['action'=>'tou','name'=>esc_html__('文章投稿','ripro-v2'),'icon'=>'fa fa-newspaper-o nav-icon'],
    ];
    if (!_cao('is_site_mycoin',true)) {
       unset($param_shop['coin']);
    }
    if (!_cao('is_site_tickets',true)) {
        unset($param_shop['msg']);
    }
    if (!_cao('is_site_aff')) {
      unset($param_shop['aff']);
    }
    if (!_cao('is_site_tougao')) {
      unset($param_shop['tou']);
    }

    if (is_oauth_password()) {
      $password_notfy = '<span class="badge badge-danger-lighten nav-link-badge">'.esc_html__('请设置密码','ripro-v2').'</span>';
    }else{
        $password_notfy = '';
    }
    $param_user = [
        'index'=> ['action'=>'index','name'=>esc_html__('基本资料','ripro-v2'),'icon'=>'fas fa-id-card nav-icon nav-icon'],
        'bind'=> ['action'=>'bind','name'=>esc_html__('账号绑定','ripro-v2'),'icon'=>'fas fa-mail-bulk nav-icon'],
        'password'=> ['action'=>'password','name'=>esc_html__('密码设置','ripro-v2').$password_notfy,'icon'=>'fas fa-shield-alt nav-icon'],
    ];

    if ($is_array_merge) {
        $result = array_merge($param_shop,$param_user);
    }else{
        $result = array('shop' => $param_shop,'info' => $param_user);
    }
    return apply_filters('user_page_action_param_opt',$result);
}


/**
 * 下载地址url
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:53:11+0800
 * @param    string                   $type [description]
 * @param    string                   $str  [description]
 * @return   [type]                         [description]
 */
function get_goto_url($type='',$str='') {
    $url = apply_filters('rizhuti_goto_url',home_url('/goto'));
    if (!empty($type)) {
        $url = add_query_arg(array($type => $str), $url);
    }
    return esc_url($url);
}


/**
 * 第三方登陆地址
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:53:18+0800
 * @param    [type]                   $type     [description]
 * @param    [type]                   $redirect [description]
 * @return   [type]                             [description]
 */
function get_open_oauth_url($type, $redirect) {
    $oauth = apply_filters('rizhuti_open_oauth_url','oauth');
    $url = home_url('/'.$oauth.'/'.$type.'?rurl='.$redirect);
    return esc_url($url);
}


/**
 * 通过子分类id获取父分类id
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:53:26+0800
 * @param    [type]                   $cat [description]
 * @return   [type]                        [description]
 */
function get_category_root_id($cat) {
    $this_category = get_category($cat); // 取得当前分类
    while ($this_category->category_parent) {
        $this_category = get_category($this_category->category_parent); //将当前分类设为上级分类（往上爬）
    }
    return $this_category->term_id; // 返回根分类的id号
}


/**
 * 顶级分类id
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:53:33+0800
 * @param    [type]                   $id      [description]
 * @param    array                    $visited [description]
 * @return   [type]                            [description]
 */
function _get_category_parents($id, $visited = array()) {
    if (!$id) {
        return '';
    }
    $chain  = '';
    $parent = get_term($id, 'category');
    if (is_wp_error($parent)) {
        return '';
    }
    $name = $parent->name;
    if ($parent->parent && ($parent->parent != $parent->term_id) && !in_array($parent->parent, $visited)) {
        $visited[] = $parent->parent;
        $chain .= _get_category_parents($parent->parent, $visited);
    }
    $chain .= '<li><a href="' . esc_url(get_category_link($parent->term_id)) . '">' . $name . '</a></li>';
    return $chain;
}


/**
 * 顶级分类递归
 * @Author   Dadong2g
 * @DateTime 2021-01-16T13:53:42+0800
 * @param    [type]                   $id        [description]
 * @param    [type]                   $taxonomy  [description]
 * @param    boolean                  $link      [description]
 * @param    string                   $separator [description]
 * @param    boolean                  $nicename  [description]
 * @param    array                    $visited   [description]
 * @return   [type]                              [description]
 */
function _get_term_parents($id, $taxonomy, $link = false, $separator = '', $nicename = false, $visited = array()) {
    $chain  = '';
    $parent = get_term($id, $taxonomy);
    if (is_wp_error($parent)) {
        return $parent;
    }
    if ($nicename) {
        $name = $parent->slug;
    } else {
        $name = $parent->name;
    }
    if ($parent->parent && ($parent->parent != $parent->term_id) && !in_array($parent->parent, $visited) && !in_array($parent->term_id, $visited)) {
        $visited[] = $parent->parent;
        $visited[] = $parent->term_id;
        $chain .= _get_term_parents($parent->parent, $taxonomy, $link, $separator, $nicename, $visited);
    }
    if ($link) {
        $chain .= '<a href="' . get_term_link($parent, $taxonomy) . '" title="' . esc_attr($parent->name) . '">' . $name . '</a>' . $separator;
    } else {
        $chain .= $name . $separator;
    }

    return $chain;
}

/**
 * 获取文章标签 10条
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:20:43+0800
 * @param    [type]                   $args [description]
 * @return   [type]                         [description]
 */
function _get_category_to_tags($cat_id = 0) {
    global $wpdb;
    $tags = $wpdb->get_results
        ("
        SELECT DISTINCT terms2.term_id as tag_id, terms2.name as tag_name
        FROM
            $wpdb->posts as p1
            LEFT JOIN $wpdb->term_relationships as r1 ON p1.ID = r1.object_ID
            LEFT JOIN $wpdb->term_taxonomy as t1 ON r1.term_taxonomy_id = t1.term_taxonomy_id
            LEFT JOIN $wpdb->terms as terms1 ON t1.term_id = terms1.term_id,
            $wpdb->posts as p2
            LEFT JOIN $wpdb->term_relationships as r2 ON p2.ID = r2.object_ID
            LEFT JOIN $wpdb->term_taxonomy as t2 ON r2.term_taxonomy_id = t2.term_taxonomy_id
            LEFT JOIN $wpdb->terms as terms2 ON t2.term_id = terms2.term_id
        WHERE
            t1.taxonomy = 'category' AND p1.post_status = 'publish' AND terms1.term_id IN (" . $cat_id . ") AND
            t2.taxonomy = 'post_tag' AND p2.post_status = 'publish'
            AND p1.ID = p2.ID
        ORDER by tag_name LIMIT 10
    ");
    $count = 0;

    if ($tags) {
        foreach ($tags as $tag) {
            $mytag[$count] = get_term_by('id', $tag->tag_id, 'post_tag');
            $count++;
        }
    } else {
        $mytag = null;
    }

    return $mytag;
}


/**
 * 根据页面别名（slug）获取页面id
 * @Author   Dadong2g
 * @DateTime 2021-01-23T00:50:22+0800
 * @param    [type]                   $page_name [description]
 * @return   [type]                              [description]
 */
function get_page_id($page_name){
    global $wpdb;
    $page_name = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '".$page_name."' AND post_status = 'publish' AND post_type = 'page'");
    return $page_name;
}






/**
 * 添加文章阅读数量
 * @Author   Dadong2g
 * @DateTime 2021-01-25T20:13:59+0800
 */
function add_post_views($post_id = null){
    if (empty($post_id)) {
        global $post;
        $post_id = $post->ID;
    }
    $this_num = (int)get_post_meta($post_id,'views',true);
    $new_num = $this_num+1;
    if ($new_num < 0 ) {
        $new_num = 1;
    }
    return update_post_meta( $post_id, 'views', $new_num );
}

/**
 * 获取文章查看数量
 * @Author   Dadong2g
 * @DateTime 2021-01-25T20:14:33+0800
 */
function _get_post_views($post_id = null){
    if (empty($post_id)) {
        global $post;
        $post_id = $post->ID;
    }
    $this_num = (int)get_post_meta($post_id,'views',true);

    if (1000<=$this_num) {
        $this_num = sprintf('%0.1f', $this_num/1000 ) .'K';
    }
    return $this_num;
}


// 获取文章收藏数量
function _get_post_fav($post_id = null){
    if (empty($post_id)) {
        global $post;
        $post_id = $post->ID;
    }
    return (int)get_post_meta($post_id,'follow_num',true);
}


/**
 * 收藏文章
 * @Author   Dadong2g
 * @DateTime 2021-04-14T08:14:58+0800
 * @param    string                   $user_id [description]
 * @param    string                   $to_post [description]
 */
function add_fav_post($user_id='',$to_post=''){
    $_meta_key ='follow_post';
    $to_post= (int)$to_post;
    if (get_post_status($to_post)===false) return 'false';
    
    $old_follow = get_user_meta($user_id,$_meta_key,true) ; # 获取...

    if (is_array($old_follow)) {
        $new_follow = $old_follow;
    }else{
        $new_follow = array(0);
    }
    if (!in_array($to_post, $new_follow)){
        // 新关注 开始处理
        array_push($new_follow,$to_post);
    }

    $this_favnum = (int)get_post_meta($to_post,'follow_num',true);
    $new_num = $this_favnum+1;
    if ($new_num < 0 ) {
        $new_num = 0;
    }

    update_post_meta( $to_post, 'follow_num', $new_num );

    return update_user_meta($user_id,$_meta_key,$new_follow);
}

/**
 * 是否收藏过此文章
 * @Author   Dadong2g
 * @DateTime 2021-04-14T08:14:53+0800
 * @param    [type]                   $post_id [description]
 * @return   boolean                           [description]
 */
function is_fav_post($post_id= null){
    if (empty($post_id)) {
        global $post;
        $post_id = $post->ID;
    }

    $user_id = get_current_user_id();
    if (!$user_id) {
        return false;
    }

    $old_follow = get_user_meta($user_id,'follow_post',true) ; # 获取...
    if (empty($old_follow) || !is_array($old_follow)) {
        return false;
    }

    if (in_array($post_id, $old_follow)){
        return true;
    }else{
        return false;
    }
}


/**
 * 取消收藏文章
 * @Author   Dadong2g
 * @DateTime 2021-04-14T08:14:49+0800
 * @param    string                   $user_id [description]
 * @param    string                   $to_post [description]
 * @return   [type]                            [description]
 */
function del_fav_post($user_id='',$to_post=''){
    $_meta_key ='follow_post';
    if (get_post_status($to_post)===false) return 'false';
    $follow_post = get_user_meta($user_id,$_meta_key,true) ; # 获取...

    if (!is_array($follow_post)) {
        return false;
    }

    if (!in_array($to_post, $follow_post)){
       return false;
    }

    foreach ($follow_post as $key => $post_id) {
        if ($post_id == $to_post) {
            unset($follow_post[$key]);
            break;
        }
    }
    $this_favnum = (int)get_post_meta($to_post,'follow_num',true);
    $new_num = $this_favnum-1;
    if ($new_num < 0 ) {
        $new_num = 0;
    }
    update_post_meta( $to_post, 'follow_num', $new_num );

    return update_user_meta($user_id,$_meta_key,$follow_post);
}


/**
 * 获取相关文章
 * @Author   Dadong2g
 * @DateTime 2021-08-23T20:10:01+0800
 * @param    [type]                   $posts_per_page [description]
 * @return   [type]                                   [description]
 */
function get_related_posts_ids( $posts_per_page ) {

    if (empty($posts_per_page)) {
        return false;
    }

    $post_id = get_the_ID();
    $tags_number = round( $posts_per_page * 0.7, 0 );

    if ( false === ( $related_posts_ids = get_post_meta_transient( $post_id, "related_posts_ids_{$posts_per_page}" ) ) ) {

        // Tags
        $tag_ids = wp_get_object_terms( $post_id, 'post_tag', array(
            'fields' => 'ids',
            'update_term_meta_cache' => false,
        ) );

        $tags_args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => $tags_number + 1,
            'fields' => 'ids',
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
            'suppress_filters' => false,
            'tax_query' => array(
                array(
                    'taxonomy' => 'post_tag',
                    'field' => 'term_id',
                    'terms' => $tag_ids
                ),
            ),
        );

        $tags = get_posts( $tags_args );
        $tags = array_slice( array_diff( $tags, array( $post_id ) ), 0, $tags_number );
        $count_tags = count( $tags );

        // Child categories
        $child_category_ids = wp_get_object_terms( $post_id, 'category', array(
            'fields' => 'ids',
            'update_term_meta_cache' => false,
            'childless' => true,
        ) );

        $child_categories_args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => $posts_per_page + 1,
            'fields' => 'ids',
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
            'suppress_filters' => false,
            'tax_query' => array(
                array(
                    'taxonomy' => 'category',
                    'field' => 'term_id',
                    'terms' => $child_category_ids
                ),
            ),
        );

        $child_categories = get_posts( $child_categories_args );
        $child_categories = array_diff( $child_categories, array( $post_id ) );
        $count_child_categories = count( $child_categories );

        // Parent categories
        $parent_category_ids = wp_get_object_terms( $post_id, 'category', array(
            'fields' => 'ids',
            'update_term_meta_cache' => false,
            'parent' => 0
        ) );

        $parent_categories_args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => $posts_per_page + 1,
            'fields' => 'ids',
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
            'suppress_filters' => false,
            'tax_query' => array(
                array(
                    'taxonomy' => 'category',
                    'field' => 'term_id',
                    'terms' => $parent_category_ids
                ),
            ),
        );

        $parent_categories = get_posts( $parent_categories_args );
        $parent_categories = array_diff( $parent_categories, array( $post_id ) );
        $count_parent_categories = count( $parent_categories );

        // Combine categories and tags
        $categories = array_values( array_unique( array_merge( $child_categories, $parent_categories ) ) );
        $categories_tags = array_intersect( $categories, $tags );
        $count_categories_tags = count( $categories_tags );

        $categories_slice = $posts_per_page - $count_tags + $count_categories_tags;
        $categories =  array_slice( $categories, 0, $categories_slice );

        $related_posts_ids = array_slice( array_values( array_unique( array_merge( $tags, $categories ) ) ), 0, $posts_per_page );
        $count_related = count( $related_posts_ids );

        // If there are less posts get the latests
        if ( $count_related < $posts_per_page ) {

            $latest_posts_args = array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => $posts_per_page * 2,
                'fields' => 'ids',
                'update_post_meta_cache' => false,
                'update_post_term_cache' => false,
                'suppress_filters' => false,
            );

            $latest_posts = get_posts( $latest_posts_args );
            $latest_posts = array_diff( $latest_posts, array( $post_id ) );
            $related_posts_ids = array_slice( array_values( array_unique( array_merge( $related_posts_ids, $latest_posts ) ) ), 0, $posts_per_page );
        }

        set_post_meta_transient( $post_id, "related_posts_ids_{$posts_per_page}", $related_posts_ids, 60 * MINUTE_IN_SECONDS );

    }

    return $related_posts_ids;
}


/**
 * 获取文章meta字段瞬时状态
 * @Author   Dadong2g
 * @DateTime 2021-08-23T20:31:10+0800
 * @param    [type]                   $post_id   [description]
 * @param    [type]                   $transient [description]
 * @return   [type]                              [description]
 */
function get_post_meta_transient( $post_id, $transient ) {

    $transient = 'riprov2_' . $transient;
    $value = false;

    $pre = apply_filters( "pre_post_meta_transient_{$transient}", false, $post_id, $transient );

    if ( false !== $pre ) {
        return $pre;
    }

    $transient_option = '_transient_' . $transient;
    $transient_value = get_post_meta( $post_id, $transient_option, true );

    if ( isset( $transient_value['timeout'] ) ) {

        $timeout = $transient_value['timeout'];

        if ( $timeout > time() ) {
            $value = $transient_value['value'];
        }

    }

    return apply_filters( "post_meta_transient_{$transient}", $value, $post_id, $transient );
}

/**
 * 设置文章meta字段瞬时状态
 * @Author   Dadong2g
 * @DateTime 2021-08-23T20:31:37+0800
 * @param    [type]                   $post_id    [description]
 * @param    [type]                   $transient  [description]
 * @param    [type]                   $value      [description]
 * @param    integer                  $expiration [description]
 */
function set_post_meta_transient( $post_id, $transient, $value, $expiration = 0 ) {

    $transient = 'riprov2_' . $transient;
    $expiration = (int) $expiration;

    $value = apply_filters( "pre_set_post_meta_transient_{$transient}", $value, $post_id, $expiration, $transient );

    $expiration = apply_filters( "expiration_of_post_meta_transient_{$transient}", $expiration, $post_id, $value, $transient );

    $transient_option = '_transient_' . $transient;
    $transient_value = array(
        'timeout' => time() + $expiration,
        'value' => $value
    );

    $result = update_post_meta( $post_id, $transient_option, $transient_value );

    if ( $result ) {

        do_action( "set_post_meta_transient_{$transient}", $value, $post_id, $expiration, $transient );

        do_action( 'setted_post_meta_transient', $transient, $post_id, $value, $expiration );
    }

    return $result;
}


/**
 * 广告钩子
 * @Author   Dadong2g
 * @DateTime 2021-05-26T10:05:42+0800
 * @param    [type]                   $slug [description]
 * @return   [type]                         [description]
 */
function get_ripro_v2_ads($slug) {
    if (defined('DOING_AJAX') && DOING_AJAX) {
        return false;
    }

    $position = ( strpos($slug,'bottum') !== false ) ? ' bottum' : ' top';

    $is_ads     = _cao($slug);
    $ads_pc     = _cao($slug . '_pc');
    $ads_mobile = _cao($slug . '_mobile');
    
    $html = '';
    if ( wp_is_mobile() && $is_ads && !empty($ads_mobile) ) {
        $html = '<div class="site_abc_wrap mobile'.$position.'">';
        $html .= $ads_mobile;
        $html .= '</div>';
    } else if ($is_ads && isset($ads_pc)) {
        $html = '<div class="site_abc_wrap pc'.$position.'">';
        $html .= $ads_pc;
        $html .= '</div>';
    }
    echo $html;
}
add_action('ripro_v2_ads', 'get_ripro_v2_ads', 10, 1);


/**
 * 调用今日更新文章数量
 * @Author   Dadong2g
 * @DateTime 2021-06-08T12:24:58+0800
 * @param    string                   $post_type [description]
 * @return   [type]                              [description]
 */
function ripro_v2_get_today_posts_count(){
    $today       = getdate();
    $query       = new WP_Query('year=' . $today["year"] . '&monthnum=' . $today["mon"] . '&day=' . $today["mday"]);
    $postsNumber = $query->found_posts;
    return $postsNumber;
}


function ripro_v2_get_week_post_count(){
    $date_query = array(
        array(
            'after' => '1 week ago',
        ),
    );
    $args = array(
        'post_type'        => 'post',
        'post_status'      => 'publish',
        'date_query'       => $date_query,
        'no_found_rows'    => true,
        'suppress_filters' => true,
        'fields'           => 'ids',
        'posts_per_page'   => -1,
    );
    $query = new WP_Query($args);
    return $query->post_count;
}



/**
 * 隐藏用户名
 * @Author   Dadong2g
 * @DateTime 2021-06-08T12:03:39+0800
 * @param    [type]                   $string      [description]
 * @param    [type]                   $replacement [description]
 * @param    [type]                   $start       [description]
 * @param    [type]                   $length      [description]
 * @param    [type]                   $encoding    [description]
 * @return   [type]                                [description]
 */
function mb_substr_replace($string, $replacement, $start, $length = null, $encoding = null) {
    if (extension_loaded('mbstring') === true) {
        $string_length = (is_null($encoding) === true) ? mb_strlen($string) : mb_strlen($string, $encoding);

        if ($start < 0) {
            $start = max(0, $string_length + $start);
        } else if ($start > $string_length) {
            $start = $string_length;
        }

        if ($length < 0) {
            $length = max(0, $string_length - $start + $length);
        } else if ((is_null($length) === true) || ($length > $string_length)) {
            $length = $string_length;
        }

        if (($start + $length) > $string_length) {
            $length = $string_length - $start;
        }

        if (is_null($encoding) === true) {
            return mb_substr($string, 0, $start) . $replacement . mb_substr($string, $start + $length, $string_length - $start - $length);
        }

        return mb_substr($string, 0, $start, $encoding) . $replacement . mb_substr($string, $start + $length, $string_length - $start - $length, $encoding);
    }

    return (is_null($length) === true) ? substr_replace($string, $replacement, $start) : substr_replace($string, $replacement, $start, $length);
}

//只保留字符串首尾字符，隐藏中间用*代替（两个字符时只显示第一个）
function ri_substr_cut($user_name) {
    $strlen   = mb_strlen($user_name, 'utf-8');
    $firstStr = mb_substr($user_name, 0, 1, 'utf-8');
    $lastStr  = mb_substr($user_name, -1, 1, 'utf-8');
    if ($strlen < 2) {
        return $user_name;
    } else {
        return $strlen == 2 ? $firstStr . str_repeat('*', mb_strlen($user_name, 'utf-8') - 1) : $firstStr . str_repeat("*", $strlen - 2) . $lastStr;
    }
}



/**
 * 问答库加载
 */
if ( is_site_question() ) {
    require_once get_template_directory() . '/inc/template-question.php';
}



///////////////////////////// RITHEME.COM END ///////////////////////////