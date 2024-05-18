<?php

///////////////////////////// RITHEME.COM END ///////////////////////////

defined('ABSPATH') || exit;



/**
 * ajax获取文章
 * @Author   Dadong2g
 * @DateTime 2021-09-10T20:24:44+0800
 * @return   [type]                   [description]
 */
function ajax_get_posts() {
    header('Content-type:application/html; Charset=utf-8');
    
    $cat = !empty($_POST['cat']) ? (int)$_POST['cat'] : null;
    $number = !empty($_POST['number']) ? (int)$_POST['number'] : get_option('posts_per_page');
    $paged = !empty($_POST['paged']) ? (int)$_POST['paged'] : 1;
    $layout = !empty($_POST['layout']) ? trim($_POST['layout']) : 'grid';
    $_GET['ajax_sidebar'] = 'none';
    // 查询
    $_args = array(
        'paged' => $paged,
        'posts_per_page'      => $number,
        'ignore_sticky_posts' => true,
        'post_status'         => 'publish',
    );

    if (!empty($cat) && $cat > 0) {
        $_args['cat'] = $cat;
    }

    $PostData = new WP_Query($_args);

    ob_start(); ?>
    <div class="row posts-wrapper scroll">
        <?php if ( $PostData->have_posts() ) : 
            /* Start the Loop */
            while ( $PostData->have_posts() ) : $PostData->the_post();
                get_template_part( 'template-parts/loop/item', $layout);
            endwhile;
        else :
            get_template_part( 'template-parts/loop/item', 'none' );
        endif;?>
    </div>
    <?php 
    // ripro_v2_pagination(5);
    wp_reset_postdata(); 
    echo ob_get_clean();
    exit();
}
add_action('wp_ajax_ajax_get_posts', 'ajax_get_posts');
add_action('wp_ajax_nopriv_ajax_get_posts', 'ajax_get_posts');






/**
 * 获取登录模板
 * @Author   Dadong2g
 * @DateTime 2021-04-15T16:04:07+0800
 * @return   [type]                   [description]
 */
function get_signup_html() {
    header('Content-type:application/html; Charset=utf-8');

    $page_mod = !empty($_POST['mod']) ? $_POST['mod'] : 'login';
    if (!in_array($page_mod, array('login', 'register', 'bindemail', 'lostpassword'))) {
        $page_mod = 'login';
    }
    $_GET['mod'] = $page_mod;
    $_REQUEST["redirect_to"] = $_POST['rurl'];

    get_template_part('template-parts/global/login-form');

    exit;
}
add_action('wp_ajax_get_signup_html', 'get_signup_html');
add_action('wp_ajax_nopriv_get_signup_html', 'get_signup_html');

/**
 * 获取海报
 * @Author   Dadong2g
 * @DateTime 2021-01-16T14:25:57+0800
 * @return   [type]                   [description]
 */
function get_poster_html() {
    // header('Content-type:application/html; Charset=utf-8');
    header('Content-type:application/json; Charset=utf-8');
    global $current_user;
    $post_id = !empty($_POST['id']) ? intval($_POST['id']) : 0;
    $post    = get_post($post_id);
    if ($current_user->ID > 0) {
        // 生出带参数的推广文章链接
        $afflink = add_query_arg(array('aff' => $current_user->ID), get_the_permalink($post_id));
    } else {
        $afflink = get_the_permalink($post_id);
    }
    if (!$post) {exit('data error');}
    $img_u = _get_post_thumbnail_url($post_id, array('width' => 740, 'height' => 480));
    // if (defined('IS_TIMTHUMB_PHP') && IS_TIMTHUMB_PHP) {
    //     $img_base64 = $img_u;
    // } else {
    //     $imageInfo  = getimagesize($img_u);
    //     $b64        = base64_encode(file_get_contents($img_u));
    //     $img_base64 = 'data:' . $imageInfo['mime'] . ';base64,' . $b64;
    // }
    $categories = get_the_category($post_id);

    $data = array(
        'title'    => get_the_title($post_id),
        'excerpt'  => wp_trim_words(strip_shortcodes($post->post_content), 82, '...'),
        'head'     => $img_u,
        'ico_cat'  => '+',
        'post_cat' => $categories[0]->name . ' by ' . get_the_author_meta('display_name', $post->post_author),
        'day'      => get_the_date('d', $post_id),
        'year'     => get_the_date('m / Y', $post_id),
        'warn'     => _cao('single_share_poser_desc'),
        'logo'     => _cao('single_share_poser_logo'),
        'qrcode'   => getQrcodeApi($afflink),
        'get_name' => get_bloginfo('name'),
        'web_home' => get_bloginfo('description'),
    );

    echo json_encode($data);exit;
}
add_action('wp_ajax_get_poster_html', 'get_poster_html');
add_action('wp_ajax_nopriv_get_poster_html', 'get_poster_html');

/**
 * 用户登录
 * @Author   Dadong2g
 * @DateTime 2021-01-16T14:26:06+0800
 * @return   [type]                   [description]
 */
function user_login() {
    header('Content-type:application/json; Charset=utf-8');
    $username   = !empty($_POST['username']) ? wp_unslash($_POST['username']) : null;
    $password   = !empty($_POST['password']) ? wp_unslash($_POST['password']) : null;
    $rememberme = !empty($_POST['rememberme']) ? wp_unslash($_POST['rememberme']) : null;

    if (!_cao('is_site_user_login', '1')) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('本站已经关闭登录功能', 'ripro-v2')));exit;
    }

    $login_data = array();

    $login_data['user_login']    = $username;
    $login_data['user_password'] = $password;
    $login_data['remember']      = false;
    if (isset($rememberme) && $rememberme == '1') {
        $login_data['remember'] = true;
    }
    if (!$username || !$password) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('请输入登录账号或密码', 'ripro-v2')));exit;
    }

    //腾讯安全验证
    if (!qq_captcha_verify()) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('安全验证失败', 'ripro-v2')));exit;
    } else {
        RiSession::set('is_qq_captcha_verify', 0);
    }

    $user_verify = wp_signon($login_data, false);
    if (is_wp_error($user_verify)) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('用户名或密码错误', 'ripro-v2')));exit;
    } else {
        if (!empty(get_user_meta($user_verify->ID, 'cao_banned', true))) {
            wp_logout();
            $mesg = esc_html__('您的账号检测异常，', 'ripro-v2') . get_user_meta($user_verify->ID, 'cao_banned_reason', true);
            echo json_encode(array('status' => '0', 'msg' => $mesg));exit;
        } else {
            wp_set_current_user($user_verify->ID, $user_verify->user_login);
            wp_set_auth_cookie($user_verify->ID, $login_data['remember']);
            echo json_encode(array('status' => '1', 'msg' => esc_html__('登录成功', 'ripro-v2')));exit;
        }
    }
    exit();
}
add_action('wp_ajax_nopriv_user_login', 'user_login');

/**
 * 注册新用户
 * @Author   Dadong2g
 * @DateTime 2021-01-16T14:26:11+0800
 * @return   [type]                   [description]
 */
function user_register() {
    if (is_close_site_shop() && !_cao('is_login_site_shop',false)) {exit;}
    header('Content-type:application/json; Charset=utf-8');
    global $wpdb;

    $user_name         = isset($_POST['user_name']) ? wp_unslash($_POST['user_name']) : null;

    $user_email        = isset($_POST['user_email']) ? $wpdb->_escape(apply_filters('user_registration_email', $_POST['user_email'])) : null;
    
    $user_pass         = isset($_POST['user_pass']) ? wp_unslash($_POST['user_pass']) : null;
    $user_pass2        = isset($_POST['user_pass2']) ? wp_unslash($_POST['user_pass2']) : null;
    $email_verify_code = isset($_POST['email_verify_code']) ? wp_unslash($_POST['email_verify_code']) : null;

    if (!_cao('is_site_user_register')) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('本站已经关闭新用户注册', 'ripro-v2')));exit;
    }

    if (!is_email($user_email)) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('邮箱地址错误', 'ripro-v2')));exit;
    }

    if (strlen($user_pass) < 6) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('密码长度不得小于6位', 'ripro-v2')));exit;
    }
    if ($user_pass != $user_pass2) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('两次输入的密码不一致', 'ripro-v2')));exit;
    }
    if (email_exists($user_email)) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('邮箱已经被注册', 'ripro-v2')));exit;
    }

    //腾讯安全验证
    if (!qq_captcha_verify()) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('安全验证失败', 'ripro-v2')));exit;
    } else {
        RiSession::set('is_qq_captcha_verify', 0);
    }

    // 是否需要邮箱验证
    if (!email_captcha_verify($user_email, $email_verify_code)) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('邮箱验证码错误', 'ripro-v2')));exit;
    }

    // 验证通过
    $nweUserData = array(
        // 'user_login'   => "mail_" . mt_rand(1000, 9999) . mt_rand(1000, 9999),
        'user_email'   => $user_email,
        'display_name' => esc_html__('新用户', 'ripro-v2'),
        'nickname'     => esc_html__('新用户', 'ripro-v2'),
        'user_pass'    => $user_pass2,
        'role'         => get_option('default_role'),
    );

    $nweUserData['user_login'] = ($user_name) ? $user_name : "mail_" . mt_rand(1000, 9999) . mt_rand(1000, 9999) ;

    $user_id = wp_insert_user($nweUserData);
    if (is_wp_error($user_id)) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('注册信息异常，请刷新页面重试', 'ripro-v2')));exit;
    } else {
        //登陆老用户
        $user = get_user_by('id', $user_id);
        wp_set_current_user($user->ID, $user->user_login);
        wp_set_auth_cookie($user->ID, true);
        do_action('wp_login', $user->user_login, $user);
        echo json_encode(array('status' => '1', 'msg' => esc_html__('注册成功', 'ripro-v2')));exit;
    }
    exit();
}
add_action('wp_ajax_nopriv_user_register', 'user_register');

/**
 * 找回密码
 * @Author   Dadong2g
 * @DateTime 2021-01-16T14:26:16+0800
 * @return   [type]                   [description]
 */
function user_lostpassword() {
    if (is_close_site_shop() && !_cao('is_login_site_shop',false)) {exit;}
    header('Content-type:application/json; Charset=utf-8');
    $user_info = isset($_POST['user_email']) ? wp_unslash($_POST['user_email']) : null;
    $user_info = esc_html__($user_info);
    if (empty($user_info)) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('请输入用户名或邮箱', 'ripro-v2')));exit;
    }

    //腾讯安全验证
    if (!qq_captcha_verify()) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('安全验证失败', 'ripro-v2')));exit;
    } else {
        RiSession::set('is_qq_captcha_verify', 0);
    }
    //处理业务逻辑
    if (strpos($user_info, '@')) {
        $user_data = get_user_by('email', $user_info);
        if (empty($user_data)) {
            echo json_encode(array('status' => '0', 'msg' => esc_html__('该邮箱账号不存在', 'ripro-v2')));exit;
        }
    } else {
        $user_data = get_user_by('login', $user_info);
        if (empty($user_data)) {
            echo json_encode(array('status' => '0', 'msg' => esc_html__('该用户名不存在', 'ripro-v2')));exit;
            exit;
        }
    }
    do_action('lostpassword_post');
    // Redefining user_login ensures we return the right case in the email.
    $user_id    = $user_data->ID;
    $user_login = $user_data->user_login;
    $user_email = $user_data->user_email;
    $key        = get_password_reset_key($user_data);
    if (is_wp_error($key)) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('账号异常，请刷新页面', 'ripro-v2')));exit;
    }
    $reset_url = esc_url_raw(
        add_query_arg(
            array(
                'riresetpass'  => 'true',
                'rifrp_action' => 'rp',
                'key'          => $key,
                'uid'          => $user_id,
            ),
            wp_lostpassword_url()
        )
    );
    $reset_link = '<a href="' . $reset_url . '">' . $reset_url . '</a>';
    $message    = '<br/>';
    $message .= esc_html__('站点名称: ', 'ripro-v2') . get_bloginfo('name');
    $message .= '<br/>';
    $message .= esc_html__('账号ID: ', 'ripro-v2') . $user_login;
    $message .= '<br/>';
    $message .= esc_html__('要重置您的密码，请打开下面的链接', 'ripro-v2');
    $message .= '<br/>';
    $message .= $reset_link;
    $message .= '<br/>';
    $message .= esc_html__('如果不是您本人发送，请忽略本邮件，不会造成任何错误', 'ripro-v2') . '<br/>';
    //发送邮箱
    if (_sendMail($user_email, esc_html__('重置密码邮件提醒', 'ripro-v2'), $message)) {

        RiSession::set('action_riresetpass_emali', 1);
        echo json_encode(array('status' => '1', 'msg' => esc_html__('密码重置链接发送成功，请前往邮箱查看继续', 'ripro-v2')));exit;
    }
    echo json_encode(array('status' => '0', 'msg' => esc_html__('电子邮件发送失败，请稍后重试', 'ripro-v2')));exit;
}
add_action('wp_ajax_nopriv_user_lostpassword', 'user_lostpassword');

/**
 * 找回密码
 * @Author   Dadong2g
 * @DateTime 2021-01-16T14:26:22+0800
 * @return   [type]                   [description]
 */
function user_set_lostpassword() {
    if (is_close_site_shop() && !_cao('is_login_site_shop',false)) {exit;}
    header('Content-type:application/json; Charset=utf-8');
    $key        = sanitize_text_field($_POST['key']);
    $user_id    = sanitize_text_field($_POST['uid']);
    $user_pass  = isset($_POST['user_pass']) ? trim($_POST['user_pass']) : null;
    $user_pass2 = isset($_POST['user_pass2']) ? trim($_POST['user_pass2']) : null;

    if (empty($key) || empty($user_id) || empty($user_pass) || empty($user_pass2)) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('参数错误，请返回重试', 'ripro-v2')));exit;
    }
    if ($user_pass != $user_pass2) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('两次输入的密码不一致', 'ripro-v2')));exit;
    }

    //腾讯安全验证
    if (!_cao('is_site_email_captcha_verify') && !qq_captcha_verify()) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('安全验证失败，请刷新页面重试', 'ripro-v2')));exit;
    } else {
        RiSession::set('is_qq_captcha_verify', 0);
    }
    $userdata = get_userdata(absint($user_id));
    $login    = $userdata ? $userdata->user_login : '';
    $user     = check_password_reset_key($key, $login);
    if (is_wp_error($user)) {
        if ($user->get_error_code() === 'expired_key') {
            echo json_encode(array('status' => '0', 'msg' => esc_html__('重置密码链接已过期，请重新找回密码', 'ripro-v2')));exit;
        } else {
            echo json_encode(array('status' => '0', 'msg' => esc_html__('重置密码链接无效，请重新找回密码', 'ripro-v2')));exit;
        }
    }
    // 验证通过 处理业务逻辑
    reset_password($user, $user_pass);
    echo json_encode(array('status' => '1', 'msg' => esc_html__('密码重置成功，请使用新密码登录', 'ripro-v2')));exit;
}
add_action('wp_ajax_nopriv_user_set_lostpassword', 'user_set_lostpassword');

/**
 * 绑定邮箱
 * @Author   Dadong2g
 * @DateTime 2021-01-16T14:26:30+0800
 * @return   [type]                   [description]
 */
function user_bind_email() {
    if (is_close_site_shop() && !_cao('is_login_site_shop',false)) {exit;}
    header('Content-type:application/json; Charset=utf-8');
    global $wpdb, $current_user;
    $user_email        = !empty($_POST['user_email']) ? wp_unslash($_POST['user_email']) : null;
    $user_email        = apply_filters('user_registration_email', $user_email);
    $user_email        = $wpdb->_escape(trim($user_email));
    $email_verify_code = !empty($_POST['email_verify_code']) ? wp_unslash($_POST['email_verify_code']) : null;

    if (!is_email($user_email)) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('邮箱地址错误', 'ripro-v2')));exit;
    }

    //腾讯安全验证
    if (!_cao('is_site_email_captcha_verify') && !qq_captcha_verify()) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('安全验证失败，请刷新页面重试', 'ripro-v2')));exit;
    } else {
        RiSession::set('is_qq_captcha_verify', 0);
    }

    // 是否需要邮箱验证
    if (!email_captcha_verify($user_email, $email_verify_code)) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('邮箱验证码错误', 'ripro-v2')));exit;
    }

    if (email_exists($user_email)) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('邮箱已存在绑定，请更换其他邮箱', 'ripro-v2')));exit;
    }
    $userdata['ID']         = $current_user->ID;
    $userdata['user_email'] = $user_email;
    if (!wp_update_user($userdata)) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('绑定失败，请刷新重试', 'ripro-v2')));exit;
    }
    echo json_encode(array('status' => '1', 'msg' => esc_html__('绑定成功', 'ripro-v2')));exit;
}
add_action('wp_ajax_user_bind_email', 'user_bind_email');

/**
 * 发送邮箱验证码
 * @Author   Dadong2g
 * @DateTime 2021-01-16T14:26:57+0800
 * @return   [type]                   [description]
 */
function send_email_verify_code() {
    if (is_close_site_shop() && !_cao('is_login_site_shop',false)) {exit;}
    header('Content-type:application/json; Charset=utf-8');
    global $wpdb;
    $user_email = !empty($_POST['user_email']) ? wp_unslash($_POST['user_email']) : null;
    $user_email = apply_filters('user_registration_email', $user_email);
    $user_email = $wpdb->_escape(trim($user_email));

    if (email_exists($user_email)) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('邮箱已存在', 'ripro-v2')));exit;
    } else {
        if (set_verify_email_code($user_email)) {
            echo json_encode(array('status' => '1', 'msg' => esc_html__('发送成功', 'ripro-v2')));exit;
        } else {
            echo json_encode(array('status' => '0', 'msg' => esc_html__('发送失败', 'ripro-v2')));exit;
        }
    }
}
add_action('wp_ajax_send_email_verify_code', 'send_email_verify_code');
add_action('wp_ajax_nopriv_send_email_verify_code', 'send_email_verify_code');

/**
 * 保存个人信息
 * @Author   Dadong2g
 * @DateTime 2021-01-16T14:27:06+0800
 * @return   [type]                   [description]
 */
function seav_userinfo() {
    if (is_close_site_shop()) {exit;}
    header('Content-type:application/json; Charset=utf-8');
    global $current_user;
    $user_id     = $current_user->ID;
    $nonce       = !empty($_POST['nonce']) ? $_POST['nonce'] : null;
    $qq          = !empty($_POST['qq']) ? wp_unslash($_POST['qq']) : null;
    $phone       = !empty($_POST['phone']) ? wp_unslash($_POST['phone']) : null;
    $description = !empty($_POST['description']) ? sanitize_text_field($_POST['description']) : null;
    if ($nonce && !wp_verify_nonce($nonce, 'rizhuti-v2-click-' . $user_id)) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('非法请求，请刷新重试', 'ripro-v2')));exit;
    }
    $userdata                 = [];
    $userdata['ID']           = $user_id;
    $userdata['nickname']     = !empty($_POST['nickname']) ? sanitize_text_field($_POST['nickname']) : esc_html__('新用户', 'ripro-v2');
    $userdata['display_name'] = $userdata['nickname'];
    if (wp_update_user($userdata)) {
        if ($qq && is_numeric($qq)) {
            update_user_meta($user_id, 'qq', $qq);
        }
        if ($phone && is_numeric($phone)) {
            update_user_meta($user_id, 'phone', $phone);
        }
        if ($description) {
            update_user_meta($user_id, 'description', $description);
        }
        echo json_encode(array('status' => '1', 'msg' => esc_html__('保存成功', 'ripro-v2')));exit;
    }
    echo json_encode(array('status' => '0', 'msg' => esc_html__('保存失败，请刷新重试', 'ripro-v2')));exit;
}
add_action('wp_ajax_seav_userinfo', 'seav_userinfo');

/**
 * 更新用户头像
 * @Author   Dadong2g
 * @DateTime 2021-04-14T14:32:48+0800
 * @return   [type]                   [description]
 */
function update_avatar_photo() {
    if (is_close_site_shop()) {exit;}
    header('Content-type:application/json; Charset=utf-8');
    global $current_user;
    $user_id = $current_user->ID;
    $nonce   = !empty($_POST['nonce']) ? $_POST['nonce'] : null;
    $file    = !empty($_FILES['file']) ? $_FILES['file'] : null;

    if ($nonce && !wp_verify_nonce($nonce, 'rizhuti-v2-click-' . $user_id)) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('非法请求，请刷新重试', 'ripro-v2')));exit;
    }

    $wp_filetype = wp_check_filetype($file['name']);
    $img_info    = getimagesize($file['tmp_name']); //读取图片信息
    $arrType     = array('image/jpg', 'image/gif', 'image/png', "image/jpeg");
    $typearr     = array(1 => 'gif', 2 => 'jpg', 3 => 'png');
    $_filesubstr = substr(strrchr($file['name'], '.'), 1);

    if (!in_array($wp_filetype['type'], $arrType) || empty($img_info) || !in_array($_filesubstr, $typearr)) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('图片类型错误', 'ripro-v2')));exit;
    }

    if ($file['size'] > 80040) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('头像最大限制80KB', 'ripro-v2')));exit;
    }

    add_filter( 'upload_dir', function($dirs){
        $dirs['baseurl'] = WP_CONTENT_URL . '/uploads';
        $dirs['basedir'] = WP_CONTENT_DIR . '/uploads';
        $dirs['path'] = $dirs['basedir'] . $dirs['subdir'];
        $dirs['url'] = $dirs['baseurl'] . $dirs['subdir'];
        return $dirs;
    } );

    $uploads = wp_upload_dir();

    $old_img = get_user_meta($user_id, 'user_custom_avatar', 1);
    if ($old_img) {
        $old_img = str_replace($uploads['baseurl'], '', $old_img);
        @unlink($uploads['basedir'] . $old_img);
    }

    $filename = 'avatar-' . $user_id . '.' . $_filesubstr;


    // wp_get_upload_dir
    $res = wp_upload_bits($filename, null, file_get_contents($file['tmp_name']), '1234/01');

    if (!$res['error']) {
        update_user_meta($user_id, 'user_custom_avatar', str_replace($uploads['baseurl'], '', $res['url']));
        update_user_meta($user_id, 'user_avatar_type', 'custom');
        echo json_encode(array('status' => '1', 'msg' => '上传成功'));exit;
    } else {
        echo json_encode(array('status' => '0', 'msg' => '上传失败'));exit;
    }

}
add_action('wp_ajax_update_avatar_photo', 'update_avatar_photo');

/**
 * 提现申请
 * @Author   Dadong2g
 * @DateTime 2021-08-15T13:13:50+0800
 * @return   [type]                   [description]
 */
function go_add_reflog() {
    if (is_close_site_shop()) {exit;}
    header('Content-type:application/json; Charset=utf-8');
    global $current_user;
    $user_id = $current_user->ID;
    $type    = !empty($_POST['type']) ? $_POST['type'] : 'rmb';
    $money = !empty($_POST['type']) ? absint($_POST['money']) : 0;
    $user_aff_info =_get_user_aff_info($user_id);
    $site_tixian_options = (array)_cao('site_tixian_options');

    if (empty($site_tixian_options) || !in_array($type,$site_tixian_options)) {
       echo json_encode(array('status' => '0', 'msg' => esc_html__('当前提现通道暂未开放', 'ripro-v2')));exit; 
    }
    
    if( $money < intval(_cao('site_min_tixian_num','1')) ){
        echo json_encode(array('status' => '0', 'msg' => esc_html__('最低提现金额限制', 'ripro-v2')));exit;
    }
    
    if ($user_aff_info['keti'] < $money) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('可提现金额不足', 'ripro-v2')));exit;
    }


    if ($type=='coin') {

        $coin_num_new = convert_site_mycoin($money, 'coin');

        RiAff::add_aff_log($user_id,$money,'提现到站内余额',1);
        update_user_mycoin($user_id, $coin_num_new);
        $msg = sprintf(__('成功提现了%s%s至钱包余额', 'ripro-v2'),$coin_num_new,site_mycoin('name'));
        //发送消息到网站动态
        RiDynamic::add(array(
            'info' => $msg, 
            'uid' => $user_id, 
            'href' => get_user_page_url('aff'),
            'time' => time(),
        ));

        echo json_encode(array('status' => '1', 'msg' => $msg ));exit;

    }

    if ($type=='rmb') {
        RiAff::add_aff_log($user_id,$money,'提现RMB',0);
        echo json_encode(array('status' => '1', 'msg' => esc_html__('提现申请成功，请联系站长发送收款信息审核', 'ripro-v2') ));exit;

    }

    echo json_encode(array('status' => '0', 'msg' => esc_html__('提现方式异常', 'ripro-v2')));exit;
}
add_action('wp_ajax_go_add_reflog', 'go_add_reflog');

/**
 * 解绑开放登录
 * @Author   Dadong2g
 * @DateTime 2021-01-16T14:27:11+0800
 * @return   [type]                   [description]
 */
function unset_open_login() {
    if (is_close_site_shop()) {exit;}
    header('Content-type:application/json; Charset=utf-8');
    global $current_user;
    $user_id = $current_user->ID;
    $type    = !empty($_POST['type']) ? $_POST['type'] : null;
    if (!$type || !in_array($type, array('qq', 'weixin', 'mpweixin', 'weibo'))) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('解绑类型错误，请刷新重试', 'ripro-v2')));exit;
    }
    if ($user_id && $type) {
        update_user_meta($user_id, 'open_' . $type . '_openid', '');
        update_user_meta($user_id, 'open_' . $type . '_unionid', '');
        update_user_meta($user_id, 'open_' . $type . '_bind', '');
        update_user_meta($user_id, 'open_' . $type . '_name', '');
        update_user_meta($user_id, 'open_' . $type . '_avatar', '');
        echo json_encode(array('status' => '1', 'msg' => esc_html__('解绑成功', 'ripro-v2')));exit;
    }
}
add_action('wp_ajax_unset_open_login', 'unset_open_login');


/**
 * 用户投稿
 * @Author   Dadong2g
 * @DateTime 2021-01-17T18:32:59+0800
 * @return   [type]                   [description]
 */
function user_tougao() {
    if (!_cao('is_site_tougao')) {exit;}
    header('Content-type:application/json; Charset=utf-8');
    global $current_user;
    $user_id = $current_user->ID;
    $nonce   = !empty($_POST['nonce']) ? $_POST['nonce'] : null;

    if ($nonce && !wp_verify_nonce($nonce, 'rizhuti-v2-click-' . $user_id)) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('非法请求，请刷新重试', 'ripro-v2')));exit;
    }

    if (empty($_POST['post_title']) || empty($_POST['post_cat']) || empty($_POST['post_content'])) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('请输入完整的文章标题/分类/内容', 'ripro-v2')));exit;
    }

    if (!_cao('is_site_tougao')) {
        echo json_encode(array('status' => '0', 'msg' => '您没有权限发布或修改文章'));exit;
    }

    //腾讯安全验证
    if (!qq_captcha_verify()) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('安全验证失败，请刷新页面重试', 'ripro-v2')));exit;
    } else {
        RiSession::set('is_qq_captcha_verify', 0);
    }

    // 插入文章
    $new_post = wp_insert_post(array(
        'post_title'    => wp_strip_all_tags($_POST['post_title']),
        'post_content'  => $_POST['post_content'],
        'post_status'   => 'pending',
        'post_author'   => $user_id,
        'post_category' => array((int) $_POST['post_cat']),
        'meta_input'    => $_POST['post_meta'],
    ));

    if ($new_post instanceof WP_Error) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('投稿失败，请刷新重试！', 'ripro-v2')));exit;
    } else {
        set_post_thumbnail($new_post, (int) $_POST['_thumbnail_id']);
        echo json_encode(array('status' => '1', 'msg' => esc_html__('投稿成功，感谢您宝贵的投稿！', 'ripro-v2')));exit;
    }

}
add_action('wp_ajax_user_tougao', 'user_tougao');

/**
 * 修改密码
 * @Author   Dadong2g
 * @DateTime 2021-01-16T14:27:15+0800
 * @return   [type]                   [description]
 */
function updete_password() {
    if (is_close_site_shop()) {exit;}
    header('Content-type:application/json; Charset=utf-8');
    global $current_user;
    $nonce         = !empty($_POST['nonce']) ? $_POST['nonce'] : null;
    $old_password  = !empty($_POST['old_password']) ? $_POST['old_password'] : null;
    $new_password  = !empty($_POST['new_password']) ? $_POST['new_password'] : null;
    $new_password2 = !empty($_POST['new_password2']) ? $_POST['new_password2'] : null;
    if ($nonce && !wp_verify_nonce($nonce, 'rizhuti-v2-click-' . $current_user->ID)) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('非法请求，请刷新重试', 'ripro-v2')));exit;
    }
    if (empty($old_password) || empty($new_password) || empty($new_password2)) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('请输入完整密码修改信息', 'ripro-v2')));exit;
    }
    if ($old_password == $new_password) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('新密码不能与旧密码相同', 'ripro-v2')));exit;
    }
    if ($new_password != $new_password2) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('两次输入的密码不一致', 'ripro-v2')));exit;
    }

    //腾讯安全验证
    if (!qq_captcha_verify()) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('安全验证失败，请刷新页面重试', 'ripro-v2')));exit;
    } else {
        RiSession::set('is_qq_captcha_verify', 0);
    }

    //判断是否一键登录密码

    if ($current_user && wp_check_password($old_password, $current_user->data->user_pass, $current_user->ID)) {
        wp_set_password($new_password2, $current_user->ID);
        wp_logout();
        echo json_encode(array('status' => '1', 'msg' => esc_html__('密码修改成功，请使用新密码重新登录', 'ripro-v2')));exit;
    } elseif (is_oauth_password()) {
        wp_set_password($new_password2, $current_user->ID);
        wp_logout();
        echo json_encode(array('status' => '1', 'msg' => esc_html__('密码设置成功，请使用新密码登录账号', 'ripro-v2')));exit;
    } else {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('旧密码错误，请输入正确的密码', 'ripro-v2')));exit;
    }
}
add_action('wp_ajax_updete_password', 'updete_password');

/**
 * 签到
 * @Author   Dadong2g
 * @DateTime 2021-06-07T20:52:06+0800
 * @return   [type]                   [description]
 */
function user_qiandao(){
    header('Content-type:application/json; Charset=utf-8');
    global $current_user;
    $uid = ($current_user->ID) ? $current_user->ID : 0 ;
    $nonce         = !empty($_POST['nonce']) ? $_POST['nonce'] : null;

    if ($nonce && !wp_verify_nonce($nonce, 'rizhuti-v2-click-' . $uid)) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('非法请求，请刷新重试', 'ripro-v2')));exit;
    }

    if ($uid == 0) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('请登录后签到', 'ripro-v2')));exit;
    }
    if (!_cao('is_site_qiandao','1')) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('签到功能暂未开启', 'ripro-v2') ));exit;
    }
    if (is_user_today_qiandao($uid)) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('今日已签到，请明日再来', 'ripro-v2') ));exit;
    }else{
        $qiandao_money = sprintf('%0.1f', _cao('site_qiandao_coin_num','0.5'));
        if ( update_user_meta($uid, 'cao_qiandao_time',time()) && update_user_mycoin($uid, $qiandao_money)) {

            //发送消息到网站动态 
            RiDynamic::add(array(
                'info' => sprintf(__('签到打卡成功，获得奖励：%s%s', 'ripro-v2'),$qiandao_money,site_mycoin('name')), 
                'uid' => $uid, 
                'href' => get_user_page_url(),
                'time' => time(),
            ));

            echo json_encode(array('status' => '1', 'msg' => esc_html__('签到成功，奖励已到账：', 'ripro-v2').$qiandao_money.site_mycoin('name') ));exit;
        }
    }

    echo json_encode(array('status' => '0', 'msg' => esc_html__('签到异常', 'ripro-v2') ));exit;

}
add_action('wp_ajax_user_qiandao', 'user_qiandao');
add_action('wp_ajax_nopriv_user_qiandao', 'user_qiandao');


/**
 * 添加文章阅读量
 * @Author   Dadong2g
 * @DateTime 2021-01-25T20:38:13+0800
 */
function add_post_views_num() {
    header('Content-type:application/json; Charset=utf-8');
    $post_id = !empty($_POST['id']) ? (int) $_POST['id'] : 0;
    if ($post_id && add_post_views($post_id)) {
        echo json_encode(array('status' => '1', 'msg' => 'PostID：' . $post_id . ' views +1'));exit;
    } else {
        echo json_encode(array('status' => '0', 'msg' => 'post views error'));exit;
    }
}
add_action('wp_ajax_add_post_views_num', 'add_post_views_num');
add_action('wp_ajax_nopriv_add_post_views_num', 'add_post_views_num');

/**
 * 收藏文章
 * @Author   Dadong2g
 * @DateTime 2021-01-16T14:25:53+0800
 * @return   [type]                   [description]
 */
function go_fav_post() {
    header('Content-type:application/json; Charset=utf-8');
    $user_id = get_current_user_id();
    $post_id = !empty($_POST['post_id']) ? (int) $_POST['post_id'] : 0;
    if (!$user_id) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('请登录收藏', 'ripro-v2')));exit;
    }
    if (is_fav_post($post_id)) {
        // 取消收藏
        del_fav_post($user_id, $post_id);
        echo json_encode(array('status' => '1', 'msg' => esc_html__('已取消收藏', 'ripro-v2')));exit;
    } else {
        //新收藏
        add_fav_post($user_id, $post_id);
        echo json_encode(array('status' => '1', 'msg' => esc_html__('收藏成功', 'ripro-v2')));exit;
    }

    exit;
}
add_action('wp_ajax_go_fav_post', 'go_fav_post');
add_action('wp_ajax_nopriv_go_fav_post', 'go_fav_post');

/**
 * 购买文章资源
 * @Author   Dadong2g
 * @DateTime 2021-04-14T08:26:26+0800
 * @return   [type]                   [description]
 */
function go_post_pay() {
    if (is_close_site_shop()) {exit;}
    header('Content-type:application/json; Charset=utf-8');
    $ip      = get_client_ip(); //客户端IP
    $user_id = get_current_user_id();
    $nonce   = !empty($_POST['nonce']) ? $_POST['nonce'] : null;
    $post_id = !empty($_POST['post_id']) ? $_POST['post_id'] : 0;
    // 1支付宝官方；2微信官方 ； 11讯虎支付宝 ；12讯虎微信
    $pay_type = !empty($_POST['pay_type']) ? (int) $_POST['pay_type'] : 0;

    if ($nonce && !wp_verify_nonce($nonce, 'rizhuti_click_' . $post_id)) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('非法请求，请刷新当前页面重试', 'ripro-v2')));exit;
    }
    if (!$user_id && !is_site_nologin_pay()) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('请登录后再进行购买', 'ripro-v2')));exit;
    }
    if ($pay_type == 0) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('请选择支付方式', 'ripro-v2')));exit;
    }

    if ( _cao('is_login_user_no_pay',false) && $pay_type !== 99 ) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('资源仅限站内余额支付', 'ripro-v2')));exit;
    }

    if ($post_id <= 0) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('购买ID参数错误', 'ripro-v2')));exit;
    }

    /////////商品属性START/////// RiClass
    $user_type  = _get_user_vip_type($user_id);
    $post_price = get_post_price($post_id, $user_type); //文章价格
    
    
    if ($post_price == -1) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('普通用户无权购买，请升级用户等级后购买', 'ripro-v2')));exit;
    }
    
    if ($post_price < 0) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('文章价格错误', 'ripro-v2')));exit;
    }

    if ($user_type == 'nov' && $post_price == 0) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('免费资源无需购买', 'ripro-v2')));exit;
    }

    //是否购买
    $RiClass = new RiClass($post_id, $user_id);
    $IS_PAID = $RiClass->is_pay_post();

    if ($IS_PAID > 0) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('您已获得该资源权限，无需重复购买', 'ripro-v2')));exit;
    }

    //其他信息
    $order_info = array_merge(
        array('vip_rate' => get_post_vip_rate($post_id, $user_type)),
        array('ip' => $ip),
        get_current_aff_info($user_id)
    );

    $order_data = [
        'order_price'    => sprintf('%0.2f', convert_site_mycoin($post_price, 'rmb')), // 订单价格 站内币转换为RMB单位
        'order_trade_no' => date("ymdhis") . mt_rand(100, 999) . mt_rand(100, 999) . mt_rand(100, 999), //本地订单号
        'order_type'     => 'other', //订单类型 charge
        'pay_type'       => $pay_type, //支付方式
        'order_name'     => esc_html( _cao('site_shop_name_txt','自助购买') ),
        'callback_url'   => get_permalink($post_id),
        'order_info'     => maybe_serialize($order_info),
    ];



    /////////商品属性END///////

    // 添加订单入库
    $RiClass = new RiClass($post_id, $user_id);
    if (!$RiClass->add_pay_order($order_data)) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('添加订单失败，请刷新当前页面重试', 'ripro-v2')));exit;
    }
    get_pay_snyc_data($pay_type, $order_data);exit;

}
add_action('wp_ajax_go_post_pay', 'go_post_pay');
add_action('wp_ajax_nopriv_go_post_pay', 'go_post_pay');

/**
 * 购买VIP
 * @Author   Dadong2g
 * @DateTime 2021-01-16T14:27:28+0800
 * @return   [type]                   [description]
 */
function go_vip_pay() {
    if (is_close_site_shop()) {exit;}
    header('Content-type:application/json; Charset=utf-8');
    $ip      = get_client_ip(); //客户端IP
    $user_id = get_current_user_id();
    $post_id = get_page_id('user');
    $nonce   = !empty($_POST['nonce']) ? $_POST['nonce'] : null;
    $vip_day = !empty($_POST['vip_day']) ? (int) $_POST['vip_day'] : null;
    // 1支付宝官方；2微信官方 ； 11虎皮椒支付宝 ；12虎皮椒微信
    $pay_type = !empty($_POST['pay_type']) ? (int) $_POST['pay_type'] : 0;
    if ($nonce && !wp_verify_nonce($nonce, 'rizhuti-v2_click_' . $user_id)) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('非法请求，请刷新当前页面重试', 'ripro-v2')));exit;
    }


    if (empty($pay_type)) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('请选择支付方式', 'ripro-v2')));exit;
    }

    if (_cao('is_pay_vip_no_coin',false) && $pay_type==99) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('仅支持在线支付方式开通', 'ripro-v2')));exit;
    }

    $current_vip_type = _get_user_vip_type($user_id);
    if ($current_vip_type == 'boosvip') {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('您已经获得最高特权', 'ripro-v2')));exit;
    }

    $site_vip_pay_opt = site_vip_pay_opt();
    $pay_daynum       = 0;
    $pay_price        = 0;
    foreach ($site_vip_pay_opt as $key => $opt) {
        if ($opt['daynum'] == $vip_day) {
            $pay_daynum = $opt['daynum'];
            $pay_price  = $opt['price'];
            break;
        }
    }

    if (empty($pay_daynum) || empty($pay_price)) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('请选择开通会员类型', 'ripro-v2')));exit;
    }
    
    if ($pay_price < 0) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('价格错误', 'ripro-v2')));exit;
    }
    
    /////////商品属性START///////

    //其他信息
    $order_info = array_merge(
        array('vip_day' => $pay_daynum),
        array('ip' => $ip),
        get_current_aff_info($user_id)
    );

    $order_data = [
        'order_price'    => sprintf('%0.2f', convert_site_mycoin($pay_price, 'rmb')), // 订单价格 站内币转换为RMB单位
        'order_trade_no' => date("ymdhis") . mt_rand(100, 999) . mt_rand(100, 999) . mt_rand(100, 999), //本地订单号
        'order_type'     => 'other', //订单类型 charge
        'pay_type'       => $pay_type, //支付方式
        'order_name'     => esc_html( _cao('site_shop_name_txt','自助购买') ),
        'callback_url'   => get_user_page_url(),
        'order_info'     => maybe_serialize($order_info),
    ];

    /////////商品属性END///////

    // 添加订单入库
    $RiClass = new RiClass($post_id, $user_id);
    if (!$RiClass->add_pay_order($order_data)) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('添加订单失败，请刷新当前页面重试', 'ripro-v2')));exit;
    }
    get_pay_snyc_data($pay_type, $order_data);exit;

}
add_action('wp_ajax_go_vip_pay', 'go_vip_pay');

/**
 * 在线充值接口
 * @Author   Dadong2g
 * @DateTime 2021-03-12T09:44:33+0800
 * @return   [type]                   [description]
 */
function go_coin_pay() {
    if (is_close_site_shop()) {exit;}
    header('Content-type:application/json; Charset=utf-8');
    $ip       = get_client_ip(); //客户端IP
    $user_id  = get_current_user_id();
    $post_id  = get_page_id('user');
    $nonce    = !empty($_POST['nonce']) ? $_POST['nonce'] : null;
    $coin_num = !empty($_POST['coin_num']) ? (int) $_POST['coin_num'] : 0;
    $pay_type = !empty($_POST['pay_type']) ? (int) $_POST['pay_type'] : 0;
    // 1支付宝官方；2微信官方 ； 11虎皮椒支付宝 ；12虎皮椒微信

    if ($nonce && !wp_verify_nonce($nonce, 'rizhuti-v2_click_' . $user_id)) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('非法请求，请刷新当前页面重试', 'ripro-v2')));exit;
    }
    if (empty($pay_type)) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('请选择支付方式', 'ripro-v2')));exit;
    }

    if (empty($coin_num)) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('请选择充值数量', 'ripro-v2')));exit;
    }

    if ($coin_num < site_mycoin('min_pay') || $coin_num > site_mycoin('max_pay')) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('请输入正确充值数量', 'ripro-v2')));exit;
    }

    //其他信息
    $order_info = array_merge(
        array('ip' => $ip),
        get_current_aff_info($user_id)
    );

    $order_data = [
        'order_price'    => sprintf('%0.2f', convert_site_mycoin($coin_num, 'rmb')), // 订单价格 站内币转换为RMB单位
        'order_trade_no' => date("ymdhis") . mt_rand(100, 999) . mt_rand(100, 999) . mt_rand(100, 999), //本地订单号
        'order_type'     => 'charge', //订单类型 charge
        'pay_type'       => $pay_type, //支付方式
        'order_name'     => esc_html( _cao('site_shop_name_txt','自助购买') ),
        'callback_url'   => get_user_page_url(),
        'order_info'     => maybe_serialize($order_info),
    ];

    /////////商品属性END///////

    // 添加订单入库
    $RiClass = new RiClass($post_id, $user_id);
    if (!$RiClass->add_pay_order($order_data)) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('添加订单失败，请刷新当前页面重试', 'ripro-v2')));exit;
    }
    get_pay_snyc_data($pay_type, $order_data);exit;

}
add_action('wp_ajax_go_coin_pay', 'go_coin_pay');

/**
 * 卡密充值接口
 * @Author   Dadong2g
 * @DateTime 2021-03-12T18:37:03+0800
 * @return   [type]                   [description]
 */
function go_cdkpay_coin() {
    if (is_close_site_shop()) {exit;}
    header('Content-type:application/json; Charset=utf-8');
    $ip         = get_client_ip(); //客户端IP
    $user_id    = get_current_user_id();
    $post_id    = get_page_id('user');
    $nonce      = !empty($_POST['nonce']) ? $_POST['nonce'] : null;
    $cdk_code   = !empty($_POST['cdk_code']) ? sanitize_text_field($_POST['cdk_code']) : 0;
    $order_type = 99;
    $pay_type   = 88;

    if ($nonce && !wp_verify_nonce($nonce, 'rizhuti-v2_click_' . $user_id)) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('非法请求，请刷新当前页面重试', 'ripro-v2')));exit;
    }
    if (!_cao('is_cdk_pay', true)) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('卡密通道暂未开启', 'ripro-v2')));exit;
    }
    //检查卡密
    $cdk_money = RiCdk::get_cdk($cdk_code, true);

    if (empty($cdk_money) || $cdk_money <= 0) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('请输入有效卡密', 'ripro-v2')));exit;
    }

    //其他信息
    $order_info = array_merge(
        array('ip' => $ip),
        array('cdk_code' => $cdk_code)
    );

    $order_data = [
        'order_price'    => sprintf('%0.2f', convert_site_mycoin($cdk_money, 'rmb')), // 订单价格 站内币转换为RMB单位
        'order_trade_no' => date("ymdhis") . mt_rand(100, 999) . mt_rand(100, 999) . mt_rand(100, 999), //本地订单号
        'order_type'     => 'charge', //订单类型 charge
        'pay_type'       => $pay_type, //支付方式
        'order_name'     => get_bloginfo('name') . esc_html__('-卡密充值', 'ripro-v2'),
        'callback_url'   => get_user_page_url(),
        'order_info'     => maybe_serialize($order_info),
        'cdk_code'       => $cdk_code,
    ];

    /////////商品属性END///////

    // 添加订单入库
    $RiClass = new RiClass($post_id, $user_id);
    if (!$RiClass->add_pay_order($order_data)) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('添加订单失败，请刷新当前页面重试', 'ripro-v2')));exit;
    }
    get_pay_snyc_data($pay_type, $order_data);exit;

}
add_action('wp_ajax_go_cdkpay_coin', 'go_cdkpay_coin');

/**
 * 检测支付状态
 * @Author   Dadong2g
 * @DateTime 2021-04-14T09:15:28+0800
 * @return   [type]                   [description]
 */
function check_pay() {
    if (is_close_site_shop()) {exit;}
    header('Content-type:application/json; Charset=utf-8');
    $user_id  = get_current_user_id();
    $post_id  = !empty($_POST['post_id']) ? (int) $_POST['post_id'] : 0;
    $orderNum = !empty($_POST['num']) ? $_POST['num'] : 0;
    $RiClass  = new RiClass($post_id, $user_id);

    $intstatus = 0;
    $msg       = esc_html__('有订单正在支付中', 'ripro-v2');
    $back_url  = home_url();

    $order = $RiClass->get_pay_order_info($orderNum);
    // 有订单并且已经支付
    if (!empty($order)) {

        if ($order->status == 1) {
            $msg       = esc_html__('恭喜你，支付成功', 'ripro-v2');
            $intstatus = 1;
        }

        if (empty($user_id) && $order->status == 1) {
            //免登录用户购买
            $RiClass->AddPayPostCookie($order->post_id, $order->order_trade_no);
            RiSession::set('current_pay_ordernum', 0);
        }

        if ($order->order_type=='charge') {
            #充值页面
            $back_url = get_user_page_url('coin');
        }elseif (get_post_type($order->post_id) == 'page' && $order->user_id > 0) {
            #会员页面
            $back_url = get_user_page_url('vip');
        }else{
            #文章页面
            $back_url = get_the_permalink($order->post_id);
        }

    }

    $result = array(
        'status'   => $intstatus,
        'msg'      => $msg,
        'back_url' => $back_url,
    );
    echo json_encode($result);
    exit;
}
add_action('wp_ajax_check_pay', 'check_pay');
add_action('wp_ajax_nopriv_check_pay', 'check_pay');

/**
 * 获取微信登陆二维码
 * @Author   Dadong2g
 * @DateTime 2021-01-16T14:28:21+0800
 * @return   [type]                   [description]
 */
function get_mpweixin_qr() {
    if (is_close_site_shop() && !_cao('is_login_site_shop',false)) {exit;}
    header('Content-type:application/json; Charset=utf-8');
    $wxConfig = _cao('sns_weixin');
    if ($wxConfig['sns_weixin_mod'] != 'mp') {
        echo json_encode(array('status' => 0, 'ticket_img' => '', 'scene_id' => ''));exit;
    }
    $RiProSNS = new RiProSNS();
    echo json_encode($RiProSNS->getLoginQr());exit;
}
add_action('wp_ajax_get_mpweixin_qr', 'get_mpweixin_qr');
add_action('wp_ajax_nopriv_get_mpweixin_qr', 'get_mpweixin_qr');

/**
 * 检测微信公众号状态+绑定已登录用户
 * @Author   Dadong2g
 * @DateTime 2021-01-16T14:28:33+0800
 * @return   [type]                   [description]
 */
function check_mpweixin_qr() {
    if (is_close_site_shop() && !_cao('is_login_site_shop',false)) {exit;}
    header('Content-type:application/json; Charset=utf-8');
    $scene_id = !empty($_POST['scene_id']) ? wp_unslash($_POST['scene_id']) : null;
    global $wpdb, $current_user;
    $current_user_id = $current_user->ID;
    // 查询数据库
    $res = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->cao_mpwx_log WHERE scene_id = %s AND openid != '' ", wp_unslash($scene_id)));
    if (empty($res) || $res->scene_id != $scene_id) {
        echo json_encode(array('status' => 0, 'msg' => ''));exit;
    }

    if (($res->create_time + 180) < time()) {
        echo json_encode(array('status' => 0, 'msg' => esc_html__('登录超时，请刷新页面重试', 'ripro-v2')));exit;
    }

    // 查询openid
    $_prefix          = 'mpweixin';
    $_openid_meta_key = 'open_' . $_prefix . '_openid';
    $search_user      = $wpdb->get_var($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key=%s AND meta_value=%s", $_openid_meta_key, $res->openid));

    // 如果当前用户已登录，而$search_user存在，即该开放平台账号连接被其他用户占用了，不能再重复绑定了
    if ($current_user_id > 0 && $search_user > 0 && $current_user_id != $search_user) {
        echo json_encode(array('status' => 0, 'msg' => '已绑定过其他账号---当前$current_user_id：' . $current_user_id . '---搜索到的$search_user：' . $search_user . '，请先登录其他账户解绑'));exit;
    }
    // 当前已登录了本地账号, 并且微信没有被绑定 提示用手机打开绑定

    if (empty($current_user_id)) {
        $user = get_user_by('id', $search_user);
        wp_set_current_user($user->ID, $user->user_login);
        wp_set_auth_cookie($user->ID, true);
        do_action('wp_login', $user->user_login, $user);

        echo json_encode(array('status' => 1, 'msg' => esc_html__('登录成功，即将刷新页面', 'ripro-v2')));exit;
    }
}
add_action('wp_ajax_check_mpweixin_qr', 'check_mpweixin_qr');
add_action('wp_ajax_nopriv_check_mpweixin_qr', 'check_mpweixin_qr');

/**
 * 刷新公众号菜单
 * @Author   Dadong2g
 * @DateTime 2021-01-16T14:28:40+0800
 * @return   [type]                   [description]
 */
function rest_mpweixin_menu() {

    header('Content-type:application/json; Charset=utf-8');
    $sns_weixin = (!empty(_cao('sns_weixin'))) ? _cao('sns_weixin') : array();
    $menu       = array();
    $i          = 0;
    if ($sns_weixin['sns_weixin_mod'] == 'mp' && !empty($sns_weixin['custom_wxmenu_opt'])) {
        $RiProSNS = new RiProSNS;
        foreach ($sns_weixin['custom_wxmenu_opt'] as $item) {
            $menu['button'][$i]['name'] = $item['name'];
            if (!empty($item['sub_button'])) {
                $j = 0;
                foreach ($item['sub_button'] as $sub) {
                    $menu['button'][$i]['sub_button'][$j]['type'] = 'view';
                    $menu['button'][$i]['sub_button'][$j]['name'] = $sub['name'];
                    $menu['button'][$i]['sub_button'][$j]['url']  = $sub['url'];
                    $j++;
                }
            } else {
                $menu['button'][$i]['type'] = 'view';
                $menu['button'][$i]['url']  = $item['url'];
            }
            $i++;
        }
    }
    $data   = json_encode($menu, JSON_UNESCAPED_UNICODE);
    $data   = str_replace('\/', '/', $data);
    $result = $RiProSNS->CreateMenu($data);
    echo $result;exit;
}
add_action('wp_ajax_rest_mpweixin_menu', 'rest_mpweixin_menu');

/**
 * 评论表单提交
 * @Author   Dadong2g
 * @DateTime 2021-04-04T21:35:23+0800
 * @param    [type]                   $a [description]
 * @return   [type]                      [description]
 */
function rizhuti_v2_ajax_comment_err($a) {
    header('HTTP/1.0 500 Internal Server Error');
    header('Content-Type: text/plain;charset=UTF-8');
    echo $a;
    exit;
}

/**
 * 评论表单提交回调
 * @Author   Dadong2g
 * @DateTime 2021-04-04T21:35:40+0800
 * @return   [type]                   [description]
 */
function rizhuti_v2_ajax_comment_callback() {

    if (!is_site_comments()) {
        rizhuti_v2_ajax_comment_err(esc_html__('评论功能未开启', 'ripro-v2'));
    }

    $comment = wp_handle_comment_submission(wp_unslash($_POST));
    if (is_wp_error($comment)) {
        $data = $comment->get_error_data();
        if (!empty($data)) {
            rizhuti_v2_ajax_comment_err($comment->get_error_message());
        } else {
            exit;
        }
    }
    $user = wp_get_current_user();
    do_action('set_comment_cookies', $comment, $user);
    $GLOBALS['comment'] = $comment;
    $author             = get_comment_author();
    $reply              = '';
    if ($comment->user_id) {
        $author = '<a>' . $author . '</a>';
    } else if ($comment->comment_author_url) {
        $author = '<a href="' . esc_url($comment->comment_author_url) . '" target="_blank" rel="nofollow">' . $author . '</a>';
    }
    ?>

    <li <?php comment_class();?>>
        <div id="div-comment-<?php comment_ID();?>" class="comment-inner">
            <div class="comment-author vcard">
                <?php echo get_avatar($comment, 50); ?>
            </div>
            <div class="comment-body">
                <div class="nickname"><?php echo $author . $reply; ?>
                    <span class="comment-time"><?php echo get_comment_date() . ' ' . get_comment_time(); ?></span>
                </div>
                <?php if ($comment->comment_approved == '0'): ?>
                    <div class="comment-awaiting-moderation"><?php _e('您的评论正在等待审核。', 'riplus');?></div>
                <?php endif;?>
                <div class="comment-text"><?php comment_text();?></div>
            </div>

            <div class="reply">
                <?php comment_reply_link();?>
            </div>
        </div>
    </li>

    <?php die();
}
add_action('wp_ajax_nopriv_ajax_comment', 'rizhuti_v2_ajax_comment_callback');
add_action('wp_ajax_ajax_comment', 'rizhuti_v2_ajax_comment_callback');



/**
 * 异步获取购买按钮信息
 * @Author   Dadong2g
 * @DateTime 2021-06-21T23:43:34+0800
 * @return   [type]                   [description]
 */
function get_async_shop_down() {
    header('Content-type:application/html; Charset=utf-8');

    $user_id = get_current_user_id();
    $post_id = !empty($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    $click_nonce = wp_create_nonce('rizhuti_click_' . $post_id);

    // 付费资源信息
    if (!is_post_shop_down($post_id) || is_close_site_shop() || !is_site_async_cache()) {
        echo esc_html__('按钮获取异常', 'ripro-v2');exit;
    }


    //是否购买
    $RiClass = new RiClass($post_id, $user_id);
    $IS_PAID = $RiClass->is_pay_post();

    //按钮组件
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

    exit;

}
add_action('wp_ajax_get_async_shop_down', 'get_async_shop_down');
add_action('wp_ajax_nopriv_get_async_shop_down', 'get_async_shop_down');














///////////////////////////// RITHEME.COM END ///////////////////////////
