<?php
/**
 * Template Name: VIP介绍单页
 */

get_header();
// UI参考来自暖岛子主题

global $current_user;

$site_vip_pay_opt = site_vip_pay_opt();
$vip_type = _get_user_vip_type($current_user->ID);
$vip_endtime = date('Y-m-d',_get_user_vip_endtime($current_user->ID));
$col_count = count($site_vip_pay_opt);
$col_classes = 'col-lg-4';
if ($col_count<=3) {
    $col_classes = 'col-lg-'.(12/$col_count);
}

$instance = array(
    'image' => get_template_directory_uri() . '/assets/img/login-bg.jpg',
    'text'  => '文字描述介绍',
);


?>


<style type="text/css">
.page-template-page-vipinfo .site-content {
    padding-bottom: 0;
    padding-top: 0;
}

.vipinfo-page {
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    background-image: linear-gradient(180deg, #206aff, #3989f5);
    position: relative;
}

.pay-vip-item .vip-title {
    font-size: 15px;
}

.pay-vip-item .vip-title>i{
	font-size: 1.875rem;
	display: block;
}
.pay-vip-item .vip-price {
    font-size: .875rem;
    font-weight: 500;
}

</style>


<div class="vipinfo-page lazyload" data-bg="<?php echo esc_url( $instance['image'] ); ?>">

	<div class="container">
		<div class="text-center p-5">
			<h1 class="h3 text-white">加入本站VIP会员，海量资源免费下载查看</h1>
			<p class="text-white mb-5">会员特权/折扣/免费资源一网打尽</p>

			<div class="row">
				<?php foreach ($site_vip_pay_opt as $key => $opt) {?>
	            <div class="<?php echo $col_classes;?> mb-4 pay-vip-col">
	                <div class="pay-vip-item card shadow" data-type="<?php echo $opt['daynum'];?>" data-price="<?php echo $opt['price'];?>">
	                  <div class="vip-body">
	                    <h5 class="vip-title" style="color:<?php echo $opt['color'];?>;"><i class="fa fa-diamond mb-2"></i><?php echo $opt['title'];?></h5>
	                    <p class="vip-price py-2"><i class="<?php echo site_mycoin('icon');?>"></i> <?php echo $opt['price'].site_mycoin('name');?></p>
	                    <p class="vip-text small text-muted"><?php echo $opt['desc'];?></p>
	                  </div>
	                </div>
	            </div>
		        <?php } ?>
		        <div class="col-12">
		        	<?php if ($current_user->ID > 0) {
		        		echo '<a class="btn btn-dark" href="'.get_user_page_url('vip').'">前往个人中心立即加入</a>';
		        	}else{
		        		echo '<a class="btn btn-dark login-btn" href="#">登录后操作</a>';
		        	}?>
		        	
		        </div>
			</div>


		</div>

	</div>

</div>





<?php get_footer();?>

