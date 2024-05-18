<?php
/**
 * Template Name: 登录注册页面
 */

if (is_close_site_shop() && !_cao('is_login_site_shop',false)) {
    wp_safe_redirect(home_url());exit;
}

$page_mod = !empty($_GET['mod']) ? $_GET['mod'] : 'login';
if ($page_mod != 'lostpassword') {
    wp_safe_redirect(home_url());exit;
}


//已登录跳转到用户中心
if (is_user_logged_in()) {
    
  wp_safe_redirect(get_user_page_url());exit;
  
}

get_header();

?>

<div class="container">
  <div class="row justify-content-center align-items-center login-warp" style=" height: 65vh; ">
    <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5">
      
      <!-- Form -->
      <form class="js-validate card p-lg-5 p-4">

        <?php if ($page_mod=='lostpassword') : ?>
        
          <?php if (isset($_GET['uid']) && isset($_GET['key']) && isset($_GET['riresetpass']) && $_GET['riresetpass']=='true' && isset($_GET['rifrp_action'])) { ?>
            <div class="mb-4 login-page-title">
              <p><?php echo esc_html__('您正在重新设置账号密码','ripro-v2' ); ?></p>
            </div>
            <div class="row">
              <!-- 设置密码框 -->
              <div class="col-lg-12">
                  <div class="form-group position-relative">
                      <label class="text-muted"><?php echo esc_html__('新密码 *','ripro-v2' ); ?></label>
                      <input type="password" class="form-control pl-3" placeholder="<?php echo esc_html__('密码长度最低6位','ripro-v2' ); ?>" name="user_pass" required="">
                  </div>
              </div>
              <div class="col-lg-12">
                  <div class="form-group position-relative">
                      <label class="text-muted"><?php echo esc_html__('确认新密码 *','ripro-v2' ); ?></label>
                      <input type="password" class="form-control pl-3" placeholder="再次输入密码" name="user_pass2" required="">
                  </div>
              </div>

              <?php qq_captcha_btn(); ?>
              <div class="col-lg-12 mb-0">
                <button class="btn btn-primary w-100 go-set-rest-password" data-key="<?php echo $_GET['key'];?>" data-uid="<?php echo $_GET['uid'];?>"><?php echo esc_html__('立即设置新密码','ripro-v2' ); ?></button>
              </div>


            </div>
          <?php }else{ ?>
            <!-- 找回密码框 -->
            <div class="mb-4 login-page-title">
              <p><?php echo esc_html__('使用邮箱找回密码，重置密码链接会发送到您邮箱','ripro-v2' ); ?></p>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group position-relative">
                        <label class="text-muted"><?php echo esc_html__('邮箱账号 *','ripro-v2' ); ?></label>
                        <input type="email" class="form-control pl-3" placeholder="<?php echo esc_html__('请输入绑定的邮箱','ripro-v2' ); ?>" name="user_email" required="">
                    </div>
                </div>
                <?php qq_captcha_btn(); ?>
                <div class="col-lg-12 mb-0">
                    <button class="btn btn-primary w-100 go-rest-password"><?php echo esc_html__('立即找回密码','ripro-v2' ); ?></button>
                </div>
            </div>
          <?php }?>
        <?php endif; ?>
        <!-- End Button -->
      </form>
      <!-- End Form -->
    </div>

  </div>
</div>


<!-- JS脚本 -->
<script type="text/javascript">
jQuery(function() {
    'use strict';
    //绑定 go-bind-olduser 绑定
    $(document).on('click', ".go-bind-olduser", function(event) {
        event.preventDefault();

        var site_js_text = {
            'txt1': '<?php echo esc_html__('请输入用户名或密码', 'ripro-v2')?>',
            'txt2': '<?php echo esc_html__('请点击验证按钮进行验证', 'ripro-v2')?>',
            'txt3': '<?php echo esc_html__('请点击验证按钮进行验证', 'ripro-v2')?>',
        };
        var _this = $(this)
        var deft = _this.text()
        var username = $("input[name='bind_username']").val()
        var password = $("input[name='bind_password']").val()
        var deft = _this.text();
        _this.html(iconspin + deft)
        if (!username || !password) {
            // _this.html(iconwarning + '请输入用户名或密码')
            ripro_v2_toast_msg('info', site_js_text.txt1, function() {
                _this.html(deft)
            })
            return;
        }
        if (!is_qq_captcha_verify) {
            ripro_v2_toast_msg('info', site_js_text.txt2, function() {
                _this.html(deft)
            })
            return;
        }
        rizhuti_v2_ajax({
            "action": "user_bind_olduser",
            "username": username,
            "password": password
        }, null, function(data) {
            if (data.status == 1) {
                ripro_v2_toast_msg('success', data.msg, function() {
                    location.reload();
                })
            } else {
                ripro_v2_toast_msg('info', data.msg, function() {
                    location.reload();
                })
            }
        });
    });
    // 找回密码-设置新密码
    $(document).on('click', ".go-set-rest-password", function(event) {
        event.preventDefault();
        var _this = $(this);
        var deft = _this.text();
        var key = _this.data("key");
        var uid = _this.data("uid");
        var user_pass = $("input[name='user_pass']").val();
        var user_pass2 = $("input[name='user_pass2']").val();
        if (!is_qq_captcha_verify) {
            ripro_v2_toast_msg('info', site_js_text.txt3, function() {
                _this.html(deft)
            })
            return;
        }
        if (!user_pass || !user_pass2) {
            return;
        }
        rizhuti_v2_ajax({
            "action": "user_set_lostpassword",
            "key": key,
            "uid": uid,
            "user_pass": user_pass,
            "user_pass2": user_pass2
        }, function(before) {
            _this.html(iconspin + deft);
        }, function(data) {
            if (data.status == 1) {
                ripro_v2_toast_msg('success', data.msg, function() {
                    window.location.href = riprov2.home_url + '/login';
                })
            } else {
                ripro_v2_toast_msg('info', data.msg, function() {
                    location.reload();
                });
            }
        }, function(complete) {
            _this.html(deft)
        });
    });
});
</script>

<?php get_footer(); ?>



