<?php
defined('ABSPATH') || exit;
global $current_user;
$_nonce = wp_create_nonce('rizhuti-v2-click-' . $current_user->ID);
$post_id = (isset($_GET['post_id'])) ? (int)$_GET['post_id'] : 0 ;
$user_aff_info =_get_user_aff_info($current_user->ID);

?>

<style type="text/css">
.mce-notification.mce-in {
    opacity: 0;
    display: none;
}
</style>

<div class="card mb-2 mb-lg-4 d-none d-lg-block">
    <div class="card-header">
        <h5 class="card-title"><?php echo esc_html__('文章投稿','ripro-v2');?></h5>
    </div>
    <!-- Body -->
   
    <?php if ( _cao('is_site_tougao_wp') ) : ?>
    	<div class="card-body">
			<div class="jumbotron">
			  <h2 class="display-5">Hello, <?php echo $current_user->display_name;?>！</h2>
			  <?php if ($current_user->roles[0] == 'subscriber') : ?>
			  <p class="lead">您还没有投稿权限，请联系站长开通！</p>
		  	  <?php else : ?>
		  	  <p class="lead">恭喜您当前已经获得 <span class="badge badge-outline-secondary">后端投稿</span> <span class="badge badge-outline-secondary">管理文章</span> <span class="badge badge-outline-secondary">编辑文章</span> 付费资源功能权限。</p>
		  	  <hr class="my-4">
			  
			  <?php if ( !empty($user_aff_info['author_aff_ratio']) ) {
			  	echo '<p class="text-success">当前网站已开启作者分成奖励，您发布的资源有人购买时，成交价的'.($user_aff_info['author_aff_ratio']*100).'%收入将发放至您的佣金。</p>';
			  }?>
			  <p>如果您是投稿用户，所发布的文章需要等待站长审核后进行展示。</p>
			  
			  <a class="btn btn-outline-primary" target="_blank" href="<?php echo esc_url( admin_url('post-new.php') );?>"><i class="fa fa-file-text"></i> 发布文章</a>
			  <a class="btn btn-outline-secondary" target="_blank" href="<?php echo esc_url( admin_url('edit.php') );?>"><i class="fa fa-user"></i> 管理文章</a>
		  	  <?php endif; ?>
			  
			</div>
		</div>
    <?php else : ?>
    <div class="card-body user-tougao">

    	<form id="post-form">
		  
		  <div class="form-group">
		    <label>文章标题</label>
		    <input type="text" class="form-control" name="post_title" placeholder="输入文章标题" value="">
		    <input type="hidden" name="nonce" value="<?php echo $_nonce; ?>">
		  </div>
		  <div class="form-row">
		    <div class="form-group col-md-4">
		      <label>文章分类</label>
				<?php wp_dropdown_categories( array(
				'hide_empty'       => 0,
				'orderby'          => 'name',
				'name'          => 'post_cat',
				'hierarchical'     => true,
				'id'     => 'post_cat',
				'class'     => 'selectpicker',
				'show_option_none' => esc_html__('全部分类','ripro-v2')
				) );?>
		    </div>
		    <div class="form-group col-md-4">
		      <label>布局风格</label>
		      <select class="selectpicker" name="post_meta[hero_single_style]">
		        <option value="none" selected>常规</option>
		        <option value="wide">顶部半高背景</option>
		        <option value="full">顶部全屏背景</option>
		      </select>
		    </div>
		    <div class="form-group col-md-4">
		      <label>侧边栏风格</label>
		      <select class="selectpicker" name="post_meta[sidebar_single_style]">
		        <option value="right" selected>右侧</option>
		        <option value="none">无</option>
		        <option value="left">左侧</option>
		      </select>
		    </div>
		  </div>
		  
		  <!-- 暂不支持付费信息设置 -->
		  <label>文章内容</label>
		<?php 
        wp_editor(
			'',
			'post_content',
			array(
				'media_buttons' => false,
				'_content_editor_dfw' => true,
				'tabfocus_elements'   => 'content-html,save-post',
				'editor_height'       => 350,
				'tinymce'             => array(
					'resize'                  => false,
					'wp_autoresize_on'        => false,
					'add_unload_trigger'      => false,
				),
			)
		);
        ?>
		</form>
    </div>
    <div class="card-footer">
        <div class="row d-flex justify-content-end">
        	<?php if (_cao('is_qq_007_captcha')):?>
            <?php qq_captcha_btn(true,'col-lg-4 col-12'); ?>
            <?php endif;?>
            <div class="col-auto">
            	<button id="save-post" type="button" class="btn btn-primary">立即投稿</button>
        	</div>
        </div>
    </div>
    <?php endif; ?>

</div>

<div class="jumbotron mb-2 mb-lg-4 d-block d-lg-none">
<p class="p-4 lead">请在PC端投稿</p>
</div>
<!-- JS脚本 -->
<script type="text/javascript">
jQuery(function() {
    'use strict';
   
    $("#save-post").on("click", function(event) {
        event.preventDefault();
        var site_js_text = {
            'txt1': '<?php echo esc_html__('请点击验证按钮进行验证','ripro-v2')?>',
            'txt2': '<?php echo esc_html__('提交中...','ripro-v2')?>',
        };

        tinyMCE.triggerSave();
        var _this = $(this);
        var deft = _this.html();
	    var d = {};
	    var t = $('#post-form').serializeArray();
	    $.each(t, function() {
	      d[this.name] = this.value;
	    });
	    d['action'] = "user_tougao";

	    if (!is_qq_captcha_verify) {
            ripro_v2_toast_msg('info',site_js_text.txt1);
            return;
        }

        rizhuti_v2_ajax(d,function(before) {
          _this.html(iconspin+site_js_text.txt2);
        },function(result) {
          	if (result.status == 1) {
	            ripro_v2_toast_msg("success",result.msg,function(){location.reload()})
	        } else {
	            ripro_v2_toast_msg("info",result.msg)
	        }
        },function(complete) {
          	_this.html(deft)
        });

    });

    
});
</script>
