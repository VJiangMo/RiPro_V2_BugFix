<?php
defined('ABSPATH') || exit;
global $current_user;
$_nonce = wp_create_nonce('rizhuti-v2-click-' . $current_user->ID);

?>


<div class="card mb-3 mb-lg-5">
    <div class="card-header">
        <h5 class="card-title"><?php echo esc_html__('个人基本信息','ripro-v2');?></h5>
    </div>
    <!-- Body -->
    <div class="card-body">
        <form id="post-form">
    
            <div class="row">
                <!-- 默认 上传 -->

                <div class="col-12">
                    <span class="btn avatarinfo">
                        <label for="addPic">
                        <img src="<?php echo get_avatar_url($current_user->ID);?>" height="50" class="mr-2">
                        <?php if (!_cao('disabled_up_ava')) { ?>
                            <a class="upload" data-toggle="tooltip" data-placement="right" title="<?php echo esc_html__('请上传80x80尺寸的JPG/PNG头像，最大可上传80KB','ripro-v2');?>"><i class="fa fa-camera"></i><input type="file" name="addPic" id="addPic" accept=".jpg, .gif, .png" resetonclick="true" data-nonce="<?php echo $_nonce; ?>">
                            </a>
                        <?php } ?>
                        </label>
                    </span>

                </div>

                <div class="col-xs-12 col-md-6">
                    <div class="form-group">
                        <label><?php echo esc_html__('账号ID','ripro-v2');?></label>
                        <input class="form-control" name="loginID" type="text" value="<?php echo $current_user->user_login;?>" disabled></input>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6">
                    <div class="form-group">
                        <label><?php echo esc_html__('昵称','ripro-v2');?></label>
                        <input class="form-control" name="nickname" type="text" value="<?php echo $current_user->display_name;?>"></input>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6">
                    <div class="form-group">
                        <label><?php echo esc_html__('联系QQ','ripro-v2');?></label>
                        <input class="form-control" name="qq" type="text" value="<?php echo get_user_meta($current_user->ID, 'qq',1 );?>" ></input>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6">
                    <div class="form-group">
                        <label><?php echo esc_html__('电话','ripro-v2');?></label>
                        <input type="text" class="form-control" name="phone" value="<?php echo get_user_meta($current_user->ID, 'phone',true )?>">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label><?php echo esc_html__('介绍','ripro-v2');?></label>
                        <textarea name="description" rows="4" class="form-control pl-3" placeholder="请输入个人介绍 :"><?php echo get_user_meta($current_user->ID, 'description',true )?></textarea>
                    </div>
                </div>

                
            </div>
            
        </form>
    </div>
    <!-- End Body -->
    <!-- Footer -->
    <div class="card-footer d-flex justify-content-end">
        <button id="save-userinfo" type="button" class="btn btn-dark" data-nonce="<?php echo $_nonce; ?>">保存个人信息</button>
    </div>
    <!-- End Footer -->
</div>


<!-- JS脚本 -->
<script type="text/javascript">
jQuery(function() {
    'use strict';
    // addPic
    $("#addPic").change(function(e) {

        var formData = new FormData();

        formData.append("nonce",$(this).data("nonce"));
        formData.append("action", "update_avatar_photo");
        formData.append("file", e.currentTarget.files[0]);

        $.ajax({
            url:riprov2.admin_url,
            dataType:'json',
            type:'POST',
            async: false,
            data: formData,
            processData : false, // 使数据不做处理
            contentType : false, // 不要设置Content-Type请求头
            success: function(result){
                if (result.status == 1) {
                    ripro_v2_toast_msg('success',result.msg,function(){location.reload()})
                }else{
                    ripro_v2_toast_msg('info',result.msg)
                }
            },
            error:function(response){
                ripro_v2_toast_msg('info','error')
            }
        });

    })


    //保存个人信息
    $("#save-userinfo").on("click", function(event) {
        event.preventDefault();
        var site_js_text = {
            'txt1': '<?php echo esc_html__('保存中...','ripro-v2')?>',
        };
        var _this = $(this);
        var deft = _this.html();
        var toast_type = "success";
        var d = {};
        var t = $('#post-form').serializeArray();
        $.each(t, function() {
          d[this.name] = this.value;
        });
        d['action'] = "seav_userinfo";
        d['nonce'] = _this.data("nonce");
        rizhuti_v2_ajax(d,function(before) {
            _this.html(iconspin+site_js_text.txt1)
        },function(result) {
            if (result.status == 0) {
                toast_type = "info";
            }
            ripro_v2_toast_msg(toast_type,result.msg,function(){location.reload()})
        },function(complete) {
            _this.html(deft)
        });

    });
    
});
</script>
