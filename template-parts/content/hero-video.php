<?php
global $post, $current_user;
$user_id     = $current_user->ID; //用户ID
$post_id     = $post->ID; //文章ID
$click_nonce = wp_create_nonce('rizhuti_click_' . $post_id);

if (!is_single() || !is_post_shop_video() || is_close_site_shop()) {
    return;
}

wp_enqueue_script('hls');
wp_enqueue_script('dplayer');

//获取视频信息
$video_data     = array();
$video_textarea = get_post_meta($post_id, 'video_url', true);
$video_arr      = explode(PHP_EOL, trim($video_textarea));

//格式化视频信息
foreach ($video_arr as $key => $item) {
    $item_exp = explode("|", trim($item));
    $_vurl    = (!empty($item_exp[0])) ? $item_exp[0] : '';
    $_vname   = (!empty($item_exp[1])) ? $item_exp[1] : '';
    $_vposer   = (!empty($item_exp[2])) ? $item_exp[2] : '';
    // 视频信息
    $video_data[$key] = array_merge(array('url' => '', 'name' => '', 'pic' => ''), array('url' => $_vurl, 'name' => $_vname, 'pic' => $_vposer));
}

// 付费资源信息 //是否购买
$RiClass        = new RiClass($post_id, $user_id);
$IS_PAID        = $RiClass->is_pay_post();
$is_free_video  = (int) get_post_meta($post_id, 'cao_is_video_free', true);
$the_user_type  = _get_user_vip_type($user_id);
$the_post_price = get_post_price($post_id, $the_user_type);

if ($is_free_video) {
    $IS_PAID = 1;
}

//业务逻辑
//
$js_video_url = '';
$js_video_pic = '';
$_content     = '<div class="content-do-video"><div class="views text-muted"><span class="badge badge-light note"><i class="fa fa-info-circle"></i> ' . esc_html__('暂无权限播放', 'ripro-v2') . '</span>';
if ($IS_PAID == 0) {
    # 未购买..
    ob_start();
    the_post_shop_priceo_options($post_id);
    $_content .= ob_get_clean();

    if ($the_user_type == 'nov' && $the_post_price == -1) {
        $_content .= '<button type="button" class="btn btn-sm btn-danger mb-4" disabled>' . esc_html__('暂无购买权限', 'ripro-v2') . '</button>';
    } elseif (empty($user_id) && !is_site_nologin_pay()) {
        $_content .= '<button type="button" class="btn btn-sm btn-light mb-4 login-btn">' . esc_html__('登录后购买', 'ripro-v2') . '</button>';
    } else {
        $_content .= '<button type="button" class="btn btn-sm btn-dark mb-4 click-pay-post" data-postid="' . $post_id . '" data-nonce="' . $click_nonce . '" data-price="' . $the_post_price . '">' . get_shop_paybtn_txt() . '</button>';
    }

} elseif ($IS_PAID == 3) {
    # 免费资源...
    if (empty($user_id) && !is_site_nologin_pay()) {
        $_content .= '<button type="button" class="btn btn-sm btn-light mb-4 login-btn">' . esc_html__('登录后播放', 'ripro-v2') . '</button>';
    } else {
        $js_video_url = $video_data[0]['url']; //输出视频地址
    }
} elseif ($IS_PAID > 0) {
    # 已购买...
    $js_video_url = $video_data[0]['url']; //输出视频地址
    $js_video_pic = (!empty($video_data[0]['pic'])) ? $video_data[0]['pic'] : ""; //输出视频缩略图
}

$_content .= '</div></div>';
// 视频选集
if (count($video_data) > 1) {
    $_content2 = '<p class="head-con text-muted"><i class="far fa-list-alt"></i> ' . esc_html__('视频选集', 'ripro-v2') . ' (' . count($video_data) . ')<b class="small text-muted">' . get_the_title() . '</b></p>';
    $_content2 .= '';
    $_content2 .= '<ul class="list-box">';
    foreach ($video_data as $key => $v) {
        $pay_note = '';
        $v_name = (!empty($v['name'])) ? $v['name'] : '第' . ($key + 1) . '集';
        $v_url  = (!empty($js_video_url)) ? $v['url'] : '';
        $v_pic = $v['pic'];
        $_content2 .= '<li>';
        $actived  = ($key == 0) ? ' active' : '';
        $disabled = (!empty($js_video_url)) ? '' : 'disabled';
        $_content2 .= '<a href="javascript:;" class="switch-video' . esc_attr($actived) . '" data-index="' . ($key + 1) . '" data-pic="' . $v_pic . '" data-url="' . $v_url . '"' . $disabled . '><span class="mr-2">P' . ($key + 1) . '</span>' . $v_name . '<i>' . $pay_note . '</i></a >';
        $_content2 .= '</li>';
    }
    $_content2 .= '</ul>';

    $classes_col = ['col-lg-9 col-12', 'col-lg-3 col-12'];
} else {
    $_content2   = '';
    $classes_col = ['col-lg-12 col-12', 'col-lg-12 col-12'];
}
?>


<div class="hero-media video">
	<div class="container-lg">
		<div class="row no-gutters">
			<div class="<?php echo esc_attr($classes_col[0]);?>">
				<div id="rizhuti-video"></div>
			</div>
			<?php if (!empty($_content2)) { ?>
			<div class="<?php echo esc_attr($classes_col[1]);?>">
				<div id="rizhuti-video-page"><?php echo $_content2;?></div>
			</div>
			<?php } ?>
		</div>
	</div>
</div>

<!-- JS脚本 -->

<script type="text/javascript">
jQuery(function(){'use strict';var js_video_url='<?php echo $js_video_url; ?>';var js_video_pic='<?php echo $js_video_pic; ?>';var js_video_content='<?php echo $_content; ?>';const dp=new DPlayer({container:document.getElementById("rizhuti-video"),theme:"#fd7e14",screenshot:!1,video:{url:js_video_url,type:"auto",pic:js_video_pic}});var video_vh="inherit";if($(".dplayer-video").bind("loadedmetadata",function(){var e=this.videoWidth||0,i=this.videoHeight||0,a=$("#rizhuti-video").width();i>e&&(video_vh=e/i*a,$(".dplayer-video").css("max-height",video_vh))}),""==js_video_url){var mask=$(".dplayer-mask");mask.show(),mask.hasClass("content-do-video")||(mask.append(js_video_content),$(".dplayer-video-wrap").addClass("video-filter"))}else{var notice=$(".dplayer-notice");notice.hasClass("dplayer-notice")&&(notice.css("opacity","0.8"),notice.append('<i class="fa fa-unlock-alt"></i> 您已获得播放权限'),setTimeout(function(){notice.css("opacity","0")},2e3)),dp.on("fullscreen",function(){$(".dplayer-video").css("max-height","unset")}),dp.on("fullscreen_cancel",function(){$(".dplayer-video").css("max-height",video_vh)})}var vpage=$("#rizhuti-video-page .switch-video");vpage.on("click",function(){var e=$(this);vpage.removeClass("active"),e.addClass("active"),dp.switchVideo({url:e.data("url"),type:"auto",pic:e.data("pic")})});});
</script>


