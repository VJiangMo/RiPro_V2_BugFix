<?php
defined('ABSPATH') || exit;
global $current_user;

$site_mycoin_pay_arr = site_mycoin('pay_arr');
$user_aff_info =_get_user_aff_info($current_user->ID);
$coin_card = array(
    array(
        'name'  => esc_html__('当前余额', 'ripro-v2'),
        'value' => get_user_mycoin($current_user->ID) . ' ' . site_mycoin('name'),
        'icon'  => site_mycoin('icon'),
        'bg'    => 'bg-warning',
    ), array(
        'name'  => '累计消费',
        'value' => (float) get_user_meta($current_user->ID, 'cao_consumed_balance', 1) . ' ' . site_mycoin('name'),
        'icon'  => site_mycoin('icon'),
        'bg'    => 'bg-primary',
    ), array(
        'name'  => '累计佣金',
        'value' => $user_aff_info['leiji'],
        'icon'  => 'fas fa-yen-sign',
        'bg'    => 'bg-danger',
    ),
);

?>


<div class="card">
    <div class="card-header">
        <h5 class="card-title">
          <i class="<?php echo site_mycoin('icon');?>"></i> <?php echo esc_html__('余额充值中心','ripro-v2');?>
          <div class="float-lg-right float-none mt-lg-0 mt-2">
            <?php if (_cao('is_cdk_pay',true)) { ?>
              <button type="button" class="btn btn-sm btn-outline-danger go_cdkpay_coin" data-nonce="<?php echo wp_create_nonce('rizhuti-v2_click_' . $current_user->ID); ?>">使用卡密充值</button>
              <a class="btn btn-sm btn-outline-primary" target="_blank" href="<?php echo esc_url(_cao('cdk_pay_pay_link','#'));?>" >去购买卡密</a>
            <?php } ?>
          </div>
        </h5>
    </div>
    
    <!-- Body -->
    <div class="card-body">
      <!-- Body -->
      <div class="user-coin-card mb-4">
          <div class="row">
          <?php foreach ($coin_card as $key => $opt) {?>
              <div class="col-12 col-sm-4 d-flex">
                <div class="card flex-fill mb-2 <?php echo $opt['bg'];?>">
                  <div class="card-body py-4">
                    <div class="media">
                      <div class="media-body">
                        <h3 class="mb-1"><?php echo $opt['value'];?></h3>
                        <p class="mb-0"><?php echo $opt['name'];?></p>
                      </div>
                      <div class="d-inline-block ml-3">
                        <div class="stat d-flex justify-content-center align-items-center">
                          <i class="<?php echo $opt['icon'];?>"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
          <?php } ?>
          </div>
      </div>
      <!-- End Body -->

      <p>充值项目（充值比例：1元=<?php echo convert_site_mycoin(1,'coin').site_mycoin('name');?>）</p>
      <div class="row">
        <?php if (!empty($site_mycoin_pay_arr[0])) : 
        foreach ($site_mycoin_pay_arr as $key => $num) { ?>
          <div class="col-xl-3 col-md-4 col-6">
            <div class="card pay_coin_box mb-lg-4 mb-2 card-hover text-center p-2" data-num="<?php echo $num;?>" data-price="<?php echo convert_site_mycoin($num,'rmb');?>">
              <h6 class="mb-1 text-warning"><?php echo $num.site_mycoin('name');?></h6>
              <p class="m-0 text-muted">￥<?php echo convert_site_mycoin($num,'rmb');?></p>
            </div>
          </div>
        <?php }  
        else : 
          echo '<p class="p-4 text-danger">请在后台商城设置设置充值套餐</p>';
        endif; ?>

        <div class="col-12">
          <div class="row">
            <div class="col-12">
              <div class="card pay_coin_box no-border mb-lg-4 mb-2 p-2 px-4" data-num="0" data-price="0">
                 <p class="text-muted">充值其他数量</p>
                <input id="othernum" type="number" class="form-control mb-3" name="othernum" placeholder="输入整数" data-num="1" data-price="<?php echo convert_site_mycoin(1,'rmb');?>">
              </div>
            </div>
          </div>
        </div>

      </div>
      <div class="row">
        <div class="col">
          <div class="d-flex align-content-center mt-2"><span>支付金额：</span><b id="pay_price_note" class="text-danger m-0">0.0元</b></div>
        </div>
        <div class="col-auto">
          <?php 
          $is_opt_pay = _ripro_get_pay_type_html();
          if ($is_opt_pay['alipay'] || $is_opt_pay['weixinpay'] || $is_opt_pay['paypal']) {
            echo '<button id="go-pay-coin" type="button" class="btn btn-dark go-pay-coin" disabled data-nonce="'.wp_create_nonce('rizhuti-v2_click_' . $current_user->ID).'">在线充值</button>';
          }else{
            if (_cao('is_cdk_pay',true)) {
              echo '<button type="button" class="btn btn-dark go_cdkpay_coin" data-nonce="'.wp_create_nonce('rizhuti-v2_click_' . $current_user->ID).'">使用卡密充值</button>';
            }else{
              echo '<button type="button" class="btn btn-danger" disabled >充值未开启</button>';
            }
          }
          ?>
        </div>
      </div>
      <hr>
      <ul class="small text-muted mx-2">
        <li>充值说明：</li>
        <li>充值最低额度为<?php echo site_mycoin('min_pay').site_mycoin('name');?></li>
        <li>充值汇率为1元=<?php echo convert_site_mycoin(1,'coin').site_mycoin('name');?></li>
        <li>人民币和<?php echo site_mycoin('name');?>不能互相转换</li>
        <li>余额永久有效，无时间限制</li>
      </ul>
  
    </div>
    <!-- End Body -->
   
</div>

<!-- JS脚本 -->
<script type="text/javascript">
jQuery(function() {
    'use strict';
    //卡密充值
    $(".go_cdkpay_coin").on("click", function(event) {
        event.preventDefault();
        var _this = $(this);
        var deft = _this.html();
        var cdk_code;
        Swal.fire({
          title: '使用卡密充值',
          input: 'text',
          inputPlaceholder: '请输入卡密代码',
          confirmButtonText: '使用卡密',
          width: 350,
          padding: 30,
          showCloseButton: true,
          inputValidator: (value) => {
            if (!value) {
              return '请输入卡密代码'
            }else{
              cdk_code = value;
            }
          }

        }).then((result) => {
          if (result.isConfirmed && cdk_code) {
            var postDate={
                "action": "go_cdkpay_coin",
                "nonce": _this.data("nonce"),
                "cdk_code": cdk_code,
                "pay_type": 88,
            }
            to_pay_data(postDate)
            // console.log(cdk_code)
          }
        })

        return;
    });

    // 在线充值
    $(".go-pay-coin").on("click", function(event) {
        event.preventDefault();
        var _this = $(this)
        var deft = _this.html()
        var postDate={
            "action": "go_coin_pay",
            "coin_num": _this.data("num"),
            "nonce": _this.data("nonce")
        }
        select_pay_mode(postDate);
        return;
    });

    //pay_coin_box
    $(".pay_coin_box").on("click", function() {
        var _this = $(this);
        var num = _this.data('num');
        var price = _this.data('price');
        var pay_btn = $("#go-pay-coin");
        pay_btn.removeAttr("disabled")
        _this.parents(".row").find('.col-6 .pay_coin_box').removeClass("ok")
        _this.addClass('ok');
        $("#pay_price_note").text('￥'+price)
        pay_btn.data('price', price);
        pay_btn.data('num', num);

    });

    // 输入金额充值
    $("#othernum").bind("input propertychange",function(event){
       var inputnum = $(this).val();
       var rate = $(this).data("price") / $(this).data("num");
       var price = inputnum * rate;
       price = price.toFixed(2);
       var pay_btn = $("#go-pay-coin");
       pay_btn.data('price', price);
       pay_btn.data('num', inputnum);
       $("#pay_price_note").text('￥'+price);
    });

    $(".col-6 .pay_coin_box").eq(0).click();
    
});
</script>
