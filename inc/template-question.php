<?php

///////////////////////////// RITHEME.COM END ///////////////////////////
defined('ABSPATH') || exit;

/**
 * register_post_type question Question question
 * 开发需求来自ritheme会员要求 设计逻辑灵感借鉴 https://themebetter.com/
 * 开发文档参考 https://developer.wordpress.org/reference/functions/register_post_type/
 */

function rizhuti_v2_question_init() {
    // 自定义文章类型
    $labels = array(
        'name'                  => __('问答社区', 'ripro-v2'),
        'singular_name'         => __('问答', 'ripro-v2'),
        'menu_name'             => __('问答', 'ripro-v2'),
        'name_admin_bar'        => __('问答', 'ripro-v2'),
        'add_new'               => __('新提问', 'ripro-v2'),
        'add_new_item'          => __('添加新提问', 'ripro-v2'),
        'new_item'              => __('新提问', 'ripro-v2'),
        'edit_item'             => __('编辑问题', 'ripro-v2'),
        'view_item'             => __('查看问题', 'ripro-v2'),
        'all_items'             => __('全部问题', 'ripro-v2'),
        'search_items'          => __('搜索问题', 'ripro-v2'),
        'not_found'             => __('未找到问题.', 'ripro-v2'),
        'not_found_in_trash'    => __('未找到问题.', 'ripro-v2'),
        'archives'              => __('问题存档', 'ripro-v2'),
        'insert_into_item'      => __('插入问题', 'ripro-v2'),
        'uploaded_to_this_item' => __('上传到此问题', 'ripro-v2'),
        'filter_items_list'     => __('筛选问题列表', 'ripro-v2'),
        'items_list_navigation' => __('问题列表导航', 'ripro-v2'),
        'items_list'            => __('问题列表', 'ripro-v2'),
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __('快速问答社区', 'ripro-v2'),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'question'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 20,
        'supports'           => array('title', 'editor', 'author', 'comments'),
        'taxonomies'         => array('question_cat','question_tag'),
        'show_in_rest'       => true,
        'menu_icon'          => 'dashicons-editor-help',
    );

    register_post_type('question', $args);

    //自定义分类法
    $labels = array(
        'name'                  => __('问答话题', 'ripro-v2'),
        'singular_name'         => __('问答话题', 'ripro-v2'),
        'search_items'          => __('搜索话题', 'ripro-v2'),
        'all_items'             => __('全部话题', 'ripro-v2'),
        'view_item'             => __('查看话题', 'ripro-v2'),
        'parent_item'           => null,
        'parent_item_colon'     => null,
        'edit_item'             => __('编辑话题', 'ripro-v2'),
        'update_item'           => __('更新话题', 'ripro-v2'),
        'add_new_item'          => __('添加新话题', 'ripro-v2'),
        'new_item_name'         => __('新话题名称', 'ripro-v2'),
        'not_found'             => __('没有找到话题', 'ripro-v2'),
        'back_to_items'         => __('返回话题', 'ripro-v2'),
        'menu_name'             => __('问答话题', 'ripro-v2'),
        'popular_items'         => __('热门话题', 'ripro-v2'),
        'choose_from_most_used' => __('从常用话题中选择', 'ripro-v2'),
    );

    $args = array(
        'labels'            => $labels,
        'has_archive'       => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'question_tag'),
        'show_in_rest'      => true,
    );

    register_taxonomy('question_tag', 'question', $args);

    //自定义分类法
    $labels = array(
        'name'                  => __('问答分类', 'ripro-v2'),
        'singular_name'         => __('问答分类', 'ripro-v2'),
        'search_items'          => __('搜索分类', 'ripro-v2'),
        'all_items'             => __('全部分类', 'ripro-v2'),
        'view_item'             => __('查看分类', 'ripro-v2'),
        'parent_item'           => null,
        'parent_item_colon'     => null,
        'edit_item'             => __('编辑分类', 'ripro-v2'),
        'update_item'           => __('更新分类', 'ripro-v2'),
        'add_new_item'          => __('添加新分类', 'ripro-v2'),
        'new_item_name'         => __('新分类名称', 'ripro-v2'),
        'not_found'             => __('没有找到分类', 'ripro-v2'),
        'back_to_items'         => __('返回分类', 'ripro-v2'),
        'menu_name'             => __('问答分类', 'ripro-v2'),
        'popular_items'         => __('热门分类', 'ripro-v2'),
        'choose_from_most_used' => __('从常用分类中选择', 'ripro-v2'),
    );

    $args = array(
        'labels'            => $labels,
        'hierarchical'      => true,
        'has_archive'       => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'question_cat'),
        'show_in_rest'      => true,
        // 'default_term'      => array('name' => '未分类','slug' => 'uncategorized','description' => '问答默认分类'),
    );

    register_taxonomy('question_cat', 'question', $args);


    add_rewrite_rule('^question/([0-9]+)/?', 'index.php?post_type=question&p=$matches[1]', 'top');

}
add_action('init', 'rizhuti_v2_question_init');




/**
 * 加载模板
 * @Author   Dadong2g
 * @DateTime 2021-05-15T11:08:28+0800
 * @param    [type]                   $template [description]
 * @return   [type]                             [description]
 */
function rizhuti_v2_question_template($template) {

    $termObj  = get_queried_object();
    $taxonomy = (!empty($termObj) && !empty($termObj->taxonomy)) ? $termObj->taxonomy : '';
    if ($taxonomy == 'question_tag' || $taxonomy == 'question_cat') {
        $new_template = locate_template(array('archive-question.php'));
        if ('' != $new_template) {
            return $new_template;
        }
    }
    return $template;
}
add_filter('template_include', 'rizhuti_v2_question_template', 99);

/**
 * 链接规则
 * @Author   Dadong2g
 * @DateTime 2021-04-03T21:44:19+0800
 * @param    [type]                   $url  [description]
 * @param    [type]                   $post [description]
 * @return   [type]                         [description]
 */
function rizhuti_v2_question_link($url, $post) {
    global $post;
    if (empty($post)) {
        return $url;
    }
    if ($post->post_type == 'question') {
        return home_url('question/' . $post->ID . '.html');
    } else {
        return $url;
    }
}
add_filter('post_type_link', 'rizhuti_v2_question_link', 10, 2);



/**
 * 评论过滤
 * @Author   Dadong2g
 * @DateTime 2021-04-05T09:16:26+0800
 * @param    [type]                   $commentdata [description]
 * @return   [type]                                [description]
 */
function rizhuti_v2_comment_preprocess($commentdata) {

    if ( get_post_type( $commentdata['comment_post_ID'] ) == 'question' ) {
        $commentdata['comment_meta'] = array('liek_num' => '0');
    }

    return $commentdata;

}
add_filter('preprocess_comment', 'rizhuti_v2_comment_preprocess');



/**
 * 获取问答文章回答数量
 * @Author   Dadong2g
 * @DateTime 2021-04-03T10:00:02+0800
 * @param    [type]                   $post_id   [description]
 * @param    integer                  $parent_id [description]
 * @return   [type]                              [description]
 */
function get_question_comment_num($post_id = 0, $parent_id = 0) {
    global $wpdb;
    if ($post_id > 0) {
        $res = $wpdb->get_var($wpdb->prepare("SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_post_ID = %d AND comment_parent = 0", $post_id));
        return (int) $res;
    }
    if ($parent_id > 0) {
        $children = get_question_children_comment($parent_id);
        return count($children);
    }

}

/**
 * 获取评论子级
 * @Author   Dadong2g
 * @DateTime 2021-04-03T20:35:37+0800
 * @param    [type]                   $comment_ID   [description]
 * @param    integer                  $data [description]
 * @return   [type]                              [description]
 */
function get_question_children_comment($comment_ID, $data = array()) {
    global $wpdb;
    $pid = $wpdb->get_col($wpdb->prepare("SELECT comment_ID FROM $wpdb->comments WHERE comment_parent = %d", $comment_ID));
    if (count($pid) > 0) {
        foreach ($pid as $v) {
            $data[] = $v;
            $data   = get_question_children_comment($v, $data); //注意写$data 返回给上级
        }
    }
    if (count($data) > 0) {
        return $data;
    }
    return array();
}

/**
 * 获取赞同数量 点赞数量
 * @Author   Dadong2g
 * @DateTime 2021-04-03T10:19:53+0800
 * @param    [type]                   $post_id   [description]
 * @param    integer                  $parent_id [description]
 * @return   [type]                              [description]
 */
function get_question_liek_num($comment_ID) {

    $liek_users = get_comment_meta($comment_ID, 'liek_users', true); # 获取...
    if (empty($liek_users) || !is_array($liek_users)) {
        $liek_users = array();
    }
    if (get_comment_meta($comment_ID, 'liek_num', true) != count($liek_users)) {
        update_comment_meta($comment_ID, 'liek_num', count($liek_users));
    }
    return count($liek_users);
}

function update_question_liek_num() {
    header('Content-type:application/json; Charset=utf-8');
    $comment_ID = !empty($_POST['cid']) ? (int) $_POST['cid'] : 0;
    $user_id    = get_current_user_id();

    if (!$user_id) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('请登录后点赞', 'ripro-v2')));exit;
    }

    if (!$comment_ID) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('请选择点赞条目', 'ripro-v2')));exit;
    }

    $liek_users = get_comment_meta($comment_ID, 'liek_users', true); # 获取...

    if (empty($liek_users) || !is_array($liek_users)) {
        $liek_users = array();
    }

    if (in_array($user_id, $liek_users)) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('您已投票', 'ripro-v2')));exit;
    } else {
        // 新点赞 开始处理
        array_push($liek_users, $user_id);

        if (update_comment_meta($comment_ID, 'liek_users', $liek_users)) {
            $this_num = (int) get_comment_meta($comment_ID, 'liek_num', true);
            $new_num  = $this_num + 1;
            update_comment_meta($comment_ID, 'liek_num', $new_num);
            echo json_encode(array('status' => '1', 'msg' => esc_html__('点赞成功', 'ripro-v2')));exit;
        }

    }

    echo json_encode(array('status' => '0', 'msg' => esc_html__('点赞异常', 'ripro-v2')));exit;

}
add_action('wp_ajax_go_question_like', 'update_question_liek_num');
add_action('wp_ajax_nopriv_go_question_like', 'update_question_liek_num');


/**
 * 添加新问题
 * @Author   Dadong2g
 * @DateTime 2021-04-03T15:00:15+0800
 */
function add_question_new() {
    header('Content-type:application/json; Charset=utf-8');
    $nonce = !empty($_POST['nonce']) ? $_POST['nonce'] : null;
    $user_id = get_current_user_id();

    if ($nonce && !wp_verify_nonce($nonce, 'rizhuti-v2-click-' . $user_id)) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('非法请求，请刷新重试','rizhuti-v2')));exit;
    }

    if (!$user_id) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('请登录后发布', 'ripro-v2')));exit;
    }

    if ( empty($_POST['question_title']) || empty($_POST['question_cat']) ) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('请输入完整的文章标题/分类','rizhuti-v2')));exit;
    }

    if (mb_strlen($_POST['question_title'], 'UTF-8') < 6) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('标题太短', 'ripro-v2')));exit;
    }

    $question_content = (empty($_POST['question_content'])) ? '' : $_POST['question_content'];

    //会员免审核
    if ( _get_user_vip_type($user_id) =='nov' ) {
        $post_status = 'pending';
        $msg = esc_html__('发布成功，审核后展示', 'ripro-v2');
    }else{
        $post_status = 'publish';
        $msg = esc_html__('新问题发布成功', 'ripro-v2');
    }

    // 插入文章
    $new_post = wp_insert_post(array(
        'post_title'     => wp_strip_all_tags($_POST['question_title']),
        'post_content'   => $question_content,
        'post_type'      => 'question',
        'post_status'    => $post_status,
        'comment_status' => 'open',
        'post_author'    => $user_id,
    ));

    if ($new_post instanceof WP_Error) {
        echo json_encode(array('status' => '0', 'msg' => esc_html__('发布失败', 'ripro-v2')));exit;
    } else {
        if ( !empty($_POST['question_cat']) && $_POST['question_cat']>0 ) {
            wp_set_object_terms( $new_post, intval( $_POST['question_cat'] ), 'question_cat' );
        }

        if ( !empty($_POST['question_tag'])) {
            wp_set_object_terms( $new_post, wp_strip_all_tags($_POST['question_tag']), 'question_tag' );
        }
        echo json_encode(array('status' => '1', 'msg' => $msg));exit;
    }

}
add_action('wp_ajax_add_question_new', 'add_question_new');
add_action('wp_ajax_nopriv_add_question_new', 'add_question_new');

///////////////////////////// RITHEME.COM END ///////////////////////////