<?php
    defined('ABSPATH') || exit;
    global $current_user;
    $action_var = get_query_var('action');
    $action = (!empty($action_var)) ? strtolower($action_var) : 'index' ;
    $vip_type = _get_user_vip_type($current_user->ID);
   
    $action_opt = user_page_action_param_opt();
    $shop_nav = $action_opt['shop'];
    $info_nav = $action_opt['info'];
?>

<!-- Navbar -->

<div class="card mb-lg-4 mb-2">
  <div class="card-body">
    <h6 class="text-cap small text-muted"><?php echo site_mycoin('name') . esc_html__('钱包','ripro-v2');?></h6>

    <a class="author-fields" href="<?php echo get_user_page_url('coin');?>">
          <div class="row">
              <div class="col-6 text-center">

                  <span class="badge badge-warning-lighten"><i class="<?php echo site_mycoin('icon');?>"></i> <?php echo get_user_mycoin($current_user->ID);?></span>
                  <span class="d-block"><?php echo esc_html__('当前余额', 'ripro-v2');?></span>
              </div>
              <div class="col-6 text-center">
                  <span class="badge badge-primary-lighten"><i class="<?php echo site_mycoin('icon');?>"></i> <?php echo (float) get_user_meta($current_user->ID, 'cao_consumed_balance', 1);?></span>
                  <span class="d-block"><?php echo esc_html__('累计消费', 'ripro-v2');?></span>
              </div>
          </div>
    </a>

    <?php if ( _cao('is_site_qiandao',true) ) : 
      if ( is_user_today_qiandao($current_user->ID) ) {
          $disabled='disabled';
          $btntext = esc_html__('今日已签到', 'ripro-v2');
      }else{
          $disabled='';
          $btntext = esc_html__('每日签到', 'ripro-v2');
      }?>
      <button class="btn btn-sm btn-info w-100 mt-3 go-user-qiandao" <?php echo $disabled;?> data-nonce="<?php echo wp_create_nonce('rizhuti-v2-click-' . $current_user->ID); ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo sprintf(__('每日签到奖励: %s', 'ripro-v2'), _cao('site_qiandao_coin_num','0.5').site_mycoin('name') );?>"><i class="fa fa-check-square-o"></i> <?php echo $btntext;?></button>
    <?php endif; ?>
    
    
  </div>
</div>
<div class="navbar-expand-lg navbar-expand-lg-collapse-block navbar-light">
    <div id="sidebarNav" class="collapse navbar-collapse navbar-vertical show">
      <!-- Card -->
      <div class="card mb-5">
        <div class="card-body">
          
          <h6 class="text-cap small text-muted"><?php echo esc_html__('会员中心','ripro-v2');?></h6>
          <ul class="nav nav-sub nav-sm nav-tabs mt-0 mb-4">
            <?php foreach ($shop_nav as $key => $nav) {
              $is_active = ($action == $nav['action']) ? ' active' : '';
              $href = get_user_page_url($nav['action']);
              echo '<li class="nav-item"><a class="nav-link'.$is_active.'" href="'.$href.'"><i class="'.$nav['icon'].'"></i> '.$nav['name'].'</a></li>';
            }?>
          </ul>

          <h6 class="text-cap small text-muted"><?php echo esc_html__('账号信息','ripro-v2');?></h6>
          <ul class="nav nav-sub nav-sm nav-tabs my-2">
            <?php foreach ($info_nav as $key => $nav) {
              $is_active = ($action == $nav['action']) ? ' active' : '';
              $href = get_user_page_url($nav['action']);
              echo '<li class="nav-item"><a class="nav-link'.$is_active.'" href="'.$href.'"><i class="'.$nav['icon'].'"></i> '.$nav['name'].'</a></li>';
            }?>
          </ul>

          <div class="d-lg-none">
            <div class="dropdown-divider"></div>
            <ul class="nav nav-sub nav-sm nav-tabs my-2">
              <li class="nav-item">
                <a class="nav-link text-primary" href="<?php echo wp_logout_url(home_url()); ?>"><i class="fa fa-sign-out nav-icon"></i><?php echo esc_html__('退出登录','ripro-v2');?></a>
              </li>
            </ul>
          </div>

          
        </div>
      </div>
      <!-- End Card -->
    </div>
</div>
<!-- End Navbar -->


<!-- JS脚本 -->
<script type="text/javascript">
jQuery(function() {
    'use strict';
   
    //签到
    $(".go-user-qiandao").on("click", function(event) {
        event.preventDefault();
        var _this = $(this);
        var deft = _this.html();
        rizhuti_v2_ajax({
            "action": "user_qiandao",
            "nonce": _this.data("nonce")
        }, function(before) {
            _this.html(iconspin)
        }, function(data) {
            if (data.status == 1) {
                ripro_v2_toast_msg('success', data.msg, function() {location.reload()})
            } else {
                ripro_v2_toast_msg('info',data.msg,function(){location.reload()})
            }
        },function(complete) {
            _this.html(deft)
        });


    });
    
});
</script>
