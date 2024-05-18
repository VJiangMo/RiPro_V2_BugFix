<?php


$page_mod = !empty($_GET['mod']) ? $_GET['mod'] : 'login';
if (!in_array($page_mod, array('login', 'register','lostpassword'))) {
  $page_mod = 'login';
}

?>

<!-- Form -->
<form class="ajax-signup-form card">
    <div class="d-flex flex-center mb-2">
    <?php ripro_v2_logo(); ?>
    </div>
    <?php if ( $page_mod=='login' && _cao('is_site_user_login',true) ) : ?>
      <!-- Title -->
      <div class="mb-4 login-page-title">
        <p><?php echo esc_html__('登录您的账户','ripro-v2' ); ?></p>
      </div>
      <!-- End Title -->
      <div class="row">
        <div class="col-lg-12">
            <div class="form-group position-relative">
                <label class="text-muted"><?php echo esc_html__('账号 *','ripro-v2' ); ?></label>
                <input type="email" class="form-control pl-3" placeholder="请输入电子邮箱/用户名" name="username" required="">
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group position-relative">
                <label class="text-muted"><?php echo esc_html__('密码 *','ripro-v2' ); ?><a class="ml-2 btn-link small switch-mod-btn" data-mod="lostpassword" href="javascript:;"><?php echo esc_html__('忘记密码？','ripro-v2' ); ?></a></label>
                <input type="password" class="form-control pl-3" placeholder="请输入密码" name="password" required="">
            </div>
        </div>
        <?php qq_captcha_btn(); ?>
        <div class="col-lg-12 mb-0"><button class="btn btn-dark w-100 go-login"><?php echo esc_html__('立即登录','ripro-v2' ); ?></button></div>
        <?php get_template_part('template-parts/login-sns');?>
        <?php if ( _cao('is_site_user_register',true) ) : ?>
        <div class="col-12 text-center">
            <p class="mb-0 mt-3"><small class="text-dark mr-2"><?php echo esc_html__('还没有账号，现在注册?','ripro-v2' ); ?></small> <a href="javascript:;" class="btn-link switch-mod-btn" data-mod="register"><?php echo esc_html__('注册新用户','ripro-v2' ); ?></a></p>
        </div>
        <?php endif; ?>
      </div>
    <?php endif; ?>
    
    <?php if ( $page_mod=='register' && _cao('is_site_user_register',true) ) : ?>
      <div class="mb-4 login-page-title">
        <p><?php echo esc_html__('注册新账户','ripro-v2' ); ?></p>
      </div>
      <div class="row">
        <div class="col-lg-12">
            <div class="form-group position-relative">
                <label class="text-muted"><?php echo esc_html__('用户名 *','ripro-v2' ); ?></label>
                <div class="input-group mb-3">
                  <input type="user_name" class="form-control pl-3" placeholder="英文字母" name="user_name" required="">
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group position-relative">
                <label class="text-muted"><?php echo esc_html__('邮箱 *','ripro-v2' ); ?></label>
                <div class="input-group mb-3">
                  <input type="email" class="form-control pl-3" placeholder="请输入注册邮箱" name="user_email" required="">
                </div>
            </div>
        </div>
        <?php if (_cao('is_site_email_captcha_verify')) : ?>
        <div class="col-lg-12">
            <div class="form-group position-relative">
                <div class="input-group mb-3">
                  <input type="text" class="form-control" placeholder="<?php echo esc_html__('邮箱验证码 *','ripro-v2' ); ?>" name="email_verify_code" aria-label="<?php echo esc_html__('请输入邮箱验证码','ripro-v2' ); ?>" aria-describedby="send-email-code" disabled="disabled">
                  <div class="input-group-append">
                    <button class="btn btn-outline-secondary go-send-email-code" type="button" id="send-email-code"><?php echo esc_html__('发送验证码','ripro-v2' ); ?></button>
                  </div>
                </div>
            </div>
        </div>
        <?php endif;?>
        <div class="col-lg-12">
            <div class="form-group position-relative">
                <label class="text-muted"><?php echo esc_html__('密码 *','ripro-v2' ); ?></label>
                <input type="password" class="form-control pl-3" placeholder="<?php echo esc_html__('密码长度最低6位','ripro-v2' ); ?>" name="user_pass" required="">
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group position-relative">
                <label class="text-muted"><?php echo esc_html__('确认密码 *','ripro-v2' ); ?></label>
                <input type="password" class="form-control pl-3" placeholder="再次输入密码" name="user_pass2" required="">
            </div>
        </div>
        <div class="col-12 mb-2">
            <small class="text-muted">
              <?php printf(__('注册登录即表示同意 ','ripro-v2' ).'<a class="btn-link" href="%s">'.__('用户协议','ripro-v2' ).'</a>'.'、<a class="btn-link" href="%s">'.__('隐私政策','ripro-v2' ).'</a>',_cao('site_login_reg_href1','#'),_cao('site_login_reg_href2','#') ); ?>
            </small>
        </div>
        <?php qq_captcha_btn(); ?>
        <div class="col-lg-12 mb-0">
            <button class="btn btn-dark w-100 go-register"><?php echo esc_html__('立即注册','ripro-v2' ); ?></button>
        </div>
        
        <?php get_template_part('template-parts/login-sns');?>
        <?php if ( _cao('is_site_user_login',true) ) : ?>
        <div class="col-12 text-center">
            <p class="mb-0 mt-3"><small class="mr-2"><?php echo esc_html__('已有账号 ?','ripro-v2' ); ?></small> <a href="javascript:;" class="btn-link switch-mod-btn" data-mod="login"><?php echo esc_html__('立即登录','ripro-v2' ); ?></a></p>
        </div>
        <?php endif; ?>
      </div>
    <?php endif; ?>
    <?php if ($page_mod=='lostpassword') : ?>
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
                <button class="btn btn-dark w-100 go-rest-password"><?php echo esc_html__('立即找回密码','ripro-v2' ); ?></button>
            </div>
            <div class="col-12 text-center">
              <p class="mb-0 mt-3"><small class="mr-2"><?php echo esc_html__('想起密码？','ripro-v2' ); ?></small> <a href="<?php echo wp_login_url();?>" class="btn-link switch-mod-btn" data-mod="login"><?php echo esc_html__('立即登录','ripro-v2' ); ?></a></p>
            </div>
        </div>
    <?php endif; ?>
  
    <?php if ($page_mod=='login' || $page_mod=='register') {
      get_template_part('template-parts/global/login-sns');
    }?>

    <!-- End Button -->
</form>
  <!-- End Form -->
