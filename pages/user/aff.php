<?php
defined('ABSPATH') || exit;
global $current_user;
$user_aff_info =_get_user_aff_info($current_user->ID);
$infoArr = [
  ['title'=>'累计佣金','value'=>$user_aff_info['leiji'],'css'=>'bg-primary' ],
  ['title'=>'已提现','value'=>$user_aff_info['yiti'],'css'=>'bg-info' ],
  ['title'=>'提现中','value'=>$user_aff_info['tixian'],'css'=>'bg-danger' ],
  ['title'=>'可提现','value'=>$user_aff_info['keti'],'css'=>'bg-success' ],
];

if (!_cao('is_site_aff')) {
 exit();
}

?>

<div class="row">
  <?php foreach ($infoArr as $item) : ?>
  <div class="col-6 col-md-3">
    <div class="card mb-3 <?php echo $item['css'];?>">
      <div class="p-4 text-center">
        <h3 class="m-0 text-white">
          ￥<?php echo $item['value'];?>
        </h3>
        <span class="text-white"><?php echo $item['title'];?></span>
      </div>
    </div>
  </div>
  <?php endforeach;?>
</div>

<div class="card mt-0 mt-lg-3">
    <div class="card-header">
      <h5 class="card-title"><?php echo esc_html__('推广奖励','ripro-v2');?></h5>
    </div>
    <!-- Body -->
    <div class="card-body p-0">
      <div class="table-responsive border-0 overflow-y-hidden">
          <table class="table mb-0 text-nowrap">
            
            <tbody class="text-dark">
              <tr>
                <td>提现账号</td>
                <td><?php echo $current_user->user_login;?></td>
              </tr>
              <tr>
                <td>推广总数</td>
                <td><?php echo $user_aff_info['total'];?> 人</td>
              </tr>
              <tr>
                <td>推广链接</td>
                <td><?php echo esc_url(add_query_arg(array('aff' => $current_user->ID), home_url()));?></td>
              </tr>
              <tr>
                <td>推广佣金提成</td>
                <td><?php echo ($user_aff_info['aff_ratio']*100);?> %</td>
              </tr>
              <tr>
                <td>作者佣金提成</td>
                <td><?php echo ($user_aff_info['author_aff_ratio']*100);?> %</td>
              </tr>
              <tr>
                <td>累计佣金</td>
                <td><?php echo $user_aff_info['leiji'];?> 元</td>
              </tr>
              <tr>
                <td>已提现</td>
                <td><?php echo $user_aff_info['yiti'];?> 元</td>
              </tr>
              <tr>
                <td>提现中</td>
                <td><?php echo $user_aff_info['tixian'];?> 元</td>
              </tr>
              <tr>
                <td>可提现</td>
                <td><?php echo $user_aff_info['keti'];?> 元</td>
              </tr>
              
            </tbody>
          </table>
      </div>
      
      <div class="px-4 pt-4">
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text"><?php echo esc_html__('提现金额','ripro-v2');?></span>
          </div>
          <input type="number" min="<?php echo _cao('site_min_tixian_num','1') ?>" class="form-control" placeholder="最低提现申请 / <?php echo _cao('site_min_tixian_num','1') ?>元起" name="money" aria-describedby="button-addon4">
          <div class="input-group-append" id="button-addon4">
            <?php 
              $site_tixian_options = (array)_cao('site_tixian_options');
              if (in_array("rmb",$site_tixian_options)) {
                echo '<button class="btn btn-outline-secondary go_add_reflog" data-type="rmb" type="button">'.esc_html__('提现RMB','ripro-v2').'</button>';
              }
              if (in_array("coin",$site_tixian_options)) {
                echo '<button class="btn btn-outline-secondary go_add_reflog" data-type="coin" type="button"><i class="'.site_mycoin('icon').'"></i> '.esc_html__('提现到余额','ripro-v2').'</button>';
              }


             ?>
          </div>

        </div>

       
      </div>




    	<ul class="small text-muted mx-2">
    		<li>推广说明：</li>
        <li>如需提现请联系网站管理员，发送您的账号信息和收款码进行人工提现</li>
    		<li>如果用户是通过您的推广链接购买的资源或者开通会员，则按照推广佣金比列奖励到您的佣金中</li>
        <li>如果用户是通过您的链接新注册的用户，推荐人是您，该用户购买资都会给你佣金</li>
        <li>如果用户是你的下级，用户使用其他推荐人链接购买，以上下级关系为准，优先给注册推荐人而不是推荐链接</li>
        <li>推广奖励金额保留一位小数点四舍五入。0.1之类的奖励金额不计算</li>
    		<li>前台无法查看推广订单详情，如需查看详情可联系管理员截图查看详细记录和时间</li>
    	</ul>
    </div>
    <!-- End Body -->
</div>

<!-- JS脚本 -->
<script type="text/javascript">


jQuery(function() {
    'use strict';
    //提现rmb
    $(".go_add_reflog").on("click", function(event) {
        event.preventDefault();
        var site_js_text = {
            'txt1': '<?php echo esc_html__('提交中...','ripro-v2')?>',
        };
        var _this = $(this);
        var deft = _this.html();
        var type = _this.data("type");
        var money = $("input[name='money']").val();

        if (!money) {
          $("input[name='money']").focus();
          return;
        }

        rizhuti_v2_ajax({
            "action": "go_add_reflog",
            "type": type,
            "money": money,
        }, function(before) {
            _this.html(iconspin + site_js_text.txt1)
        }, function(result) {
            if (result.status == 1) {
                ripro_v2_toast_msg("success", result.msg, function() {
                    location.reload()
                })
            } else {
                ripro_v2_toast_msg("info", result.msg, function() {
                    location.reload()
                })
            }
        }, function(complete) {
            _this.html(deft)
        });
        return;
    });


});
</script>
