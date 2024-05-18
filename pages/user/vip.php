<?php
defined('ABSPATH') || exit;
global $current_user;

$site_vip_pay_opt = site_vip_pay_opt();
$vip_type = _get_user_vip_type($current_user->ID);
$vip_endtime = date('Y-m-d',_get_user_vip_endtime($current_user->ID));
$col_count = count($site_vip_pay_opt);
$col_classes = 'col-lg-4';
if ($col_count<=3) {
    $col_classes = 'col-lg-'.(12/$col_count);
}
?>


<div class="card mb-0">
    <div class="card-header">
        <h5 class="card-title"><i class="fa fa-diamond nav-icon"></i><?php echo esc_html__('开通VIP会员，尊享VIP优惠特权','ripro-v2');?></h5>
    </div>

    <!-- Body -->
    <div class="p-4">
        <div class="row">
        <?php foreach ($site_vip_pay_opt as $key => $opt) {?>
            <div class="<?php echo $col_classes;?> mb-4 pay-vip-col">
                <div class="pay-vip-item card shadow" data-type="<?php echo $opt['daynum'];?>" data-price="<?php echo $opt['price'];?>">
                  <div class="vip-body">
                    <h5 class="vip-title" style="color:<?php echo $opt['color'];?>;"><i class="fa fa-diamond mr-2"></i><?php echo $opt['title'];?></h5>
                    <p class="vip-price py-2" style="color:<?php echo $opt['color'];?>;"><i class="<?php echo site_mycoin('icon');?>"></i> <?php echo $opt['price'].site_mycoin('name');?></p>
                    <p class="vip-text small text-muted"><?php echo $opt['desc'];?></p>
                  </div>
                </div>
            </div>
        <?php } ?>
        </div>
    </div>
    <!-- End Body -->



    <div class="card-footer">
        <div class="row align-items-center flex-grow-1 mb-0">
            <div class="col-md col-12 mb-lg-0 mb-4">
                <div class="row justify-content-lg-between align-items-sm-center">
                    <div class="col-lg-6 col-12 text-muted">
                        <div class="d-flex align-items-center">
                            <div class="mr-2">
                              <img src="<?php echo get_avatar_url($current_user->ID); ?>" class="rounded-circle border-width-4 border-white" width="40">
                            </div>
                            <div class="lh-1">
                              <p class="mb-1" id="the-user" data-type="<?php echo $vip_type;?>"><?php echo $current_user->display_name;?> <?php echo _get_user_vip_type_badge($current_user->ID); ?>
                              </p>
                              <p class="mb-1 small d-block text-muted"><?php echo esc_html__('到期时间：','ripro-v2').$vip_endtime;?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-12">
                      <strong class="mr-2" id="vip-day-note"><span class="badge badge-success mr-1"></span></strong>
                    </div>
                </div>
                  
            </div>
            <div class="col-md-auto col-12">
                <button id="go-pay-vip" type="button" class="btn btn-dark btn-block go-pay-vip" data-nonce="<?php echo wp_create_nonce('rizhuti-v2_click_' . $current_user->ID); ?>" disabled><?php echo esc_html__('立即开通','ripro-v2');?></button>
            </div>
        </div>
    </div>


    
    <ul class="small text-muted mx-2">
    <li>开通会员说明：</li>
    <li>本站会员账号权限为虚拟数字资源，开通后不可退款</li>
    <li>开通会员后可享有对应会员特权的商品折扣，免费权限</li>
    <li>会员特权到期后不享受特权</li>
    <li>重复购买特权到期时间累计增加</li>
    </ul>

</div>

<!-- JS脚本 -->
<script type="text/javascript">
jQuery(function() {
    'use strict';
    $(".go-pay-vip").on("click", function(event) {
        event.preventDefault();
        var _this = $(this)
        var deft = _this.html()
        var postDate={
            "action": "go_vip_pay",
            "vip_day": _this.data("type"),
            "pay_type": 0,
            "nonce": _this.data("nonce")
        }
        select_pay_mode(postDate);
        return;
    });

    //会员购买NEW 支付模式
    $(".pay-vip-item").on("click", function() {
        var _this = $(this);
        var type = _this.data('type');
        var price = _this.data('price');
        var name = _this.find(".vip-title").html();
        var pay_btn = $("#go-pay-vip");
        var the_user_type = $("#the-user").data('type');
        var site_vip = [0, 31, 365,3600];
        pay_btn.removeAttr("disabled")
        if (the_user_type == 'boosvip') {
            pay_btn.html("您已开通永久VIP会员")
            pay_btn.attr("disabled", "true")
        }
        _this.parents(".row").find('.pay-vip-col .pay-vip-item').removeClass("ok")
        _this.addClass('ok');
        $("#vip-day-note").html(name + ' <span class="badge badge-light mr-1">' + _this.find(".vip-price").html() + '</span>')

        pay_btn.data('price', price)
        pay_btn.data('type', type)
    });

    $(".pay-vip-col .pay-vip-item").eq(0).click();
    
});
</script>
