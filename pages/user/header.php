<?php
defined('ABSPATH') || exit;
global $current_user;
?>

<div class="user-top-header lazyload" data-bg="<?php echo _cao('site_profile_bg_img'); ?>">
  <div class="container">
    <div class="row align-items-center">      
      <div class="col-xl-12 col-lg-12 col-md-12 col-12">
          <!-- Bg -->
        <div class="d-flex align-items-center justify-content-between py-lg-4 py-3">
          <div class="d-flex align-items-center">
            <div class="mr-2 position-relative d-flex justify-content-end align-items-end ">
              <img src="<?php echo get_avatar_url($current_user->ID); ?>" class="avatar-xl rounded-circle border-width-4 border-white" alt="<?php echo $current_user->display_name;?>">
            </div>
            <div class="lh-1">
              <h5 class="mb-1 text-white">
                <?php echo $current_user->display_name;?>
                <a href="<?php echo get_user_page_url('vip');?>" title="<?php echo date('Y-m-d',_get_user_vip_endtime());?>到期"><?php echo _get_user_vip_type_badge($current_user->ID); ?></a>
              </h5>
              <p class="mb-1 d-block text-white"><?php echo $current_user->user_email;?></p>
            </div>
          </div>
          <div class="d-none d-lg-block">
            <a class="btn btn-outline-light btn-sm" href="<?php echo wp_logout_url(home_url()); ?>"><i class="fa fa-sign-out"></i> <?php echo esc_html__('退出登录','ripro-v2');?></a>
          </div>
          <!-- Responsive Toggle Button -->
          <button type="button" class="navbar-toggler btn btn-icon btn-sm rounde-circle d-lg-none" aria-label="Toggle navigation" aria-expanded="true" aria-controls="sidebarNav" data-toggle="collapse" data-target="#sidebarNav">
            <span class="navbar-toggler-default">
              <svg width="14" height="14" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
                <path fill="currentColor" d="M17.4,6.2H0.6C0.3,6.2,0,5.9,0,5.5V4.1c0-0.4,0.3-0.7,0.6-0.7h16.9c0.3,0,0.6,0.3,0.6,0.7v1.4C18,5.9,17.7,6.2,17.4,6.2z M17.4,14.1H0.6c-0.3,0-0.6-0.3-0.6-0.7V12c0-0.4,0.3-0.7,0.6-0.7h16.9c0.3,0,0.6,0.3,0.6,0.7v1.4C18,13.7,17.7,14.1,17.4,14.1z"></path>
              </svg>
            </span>
            <span class="navbar-toggler-toggled">
              <svg width="14" height="14" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
                <path fill="currentColor" d="M11.5,9.5l5-5c0.2-0.2,0.2-0.6-0.1-0.9l-1-1c-0.3-0.3-0.7-0.3-0.9-0.1l-5,5l-5-5C4.3,2.3,3.9,2.4,3.6,2.6l-1,1 C2.4,3.9,2.3,4.3,2.5,4.5l5,5l-5,5c-0.2,0.2-0.2,0.6,0.1,0.9l1,1c0.3,0.3,0.7,0.3,0.9,0.1l5-5l5,5c0.2,0.2,0.6,0.2,0.9-0.1l1-1 c0.3-0.3,0.3-0.7,0.1-0.9L11.5,9.5z"></path>
              </svg>
            </span>
          </button>
          <!-- End Responsive Toggle Button -->
          
        </div>
      </div>
    </div>
  </div>
</div>