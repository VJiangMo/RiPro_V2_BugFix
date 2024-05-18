<?php
defined('ABSPATH') || exit;
date_default_timezone_set(get_option('timezone_string')); //初始化本地时间

?>


<div class="card">
    <div class="card-header">
        <h5 class="card-title"><?php echo esc_html__('订单记录','ripro-v2');?></h5>
    </div>

    <!-- Body -->
    <div class="card-body">
	    <?php
        global $wpdb,$current_user;
        //** custom_pagination start **//
		$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1; //第几页
		$limit = 10; //每页显示数量
		$offset = ( $pagenum - 1 ) * $limit; //偏移量
		$total = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(id) FROM {$wpdb->cao_order} WHERE user_id=%d AND status=1", $current_user->ID) );//总数
		$max_num_pages = ceil( $total / $limit ); //多少页
		//** custom_pagination end ripro_v2_custom_pagination($pagenum,$max_num_pages) **//

		$result = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->cao_order} WHERE user_id=%d AND status=1 ORDER BY create_time DESC LIMIT %d,%d", $current_user->ID,$offset,$limit));
        ?>
        <?php if (empty($result)) : ?>
        <!-- Empty State -->
        <div class="text-center space-1">
            <img class="avatar avatar-xl mb-3" src="<?php echo get_template_directory_uri();?>/assets/img/empty-state-no-data.svg">
                <p class="card-text"><?php echo esc_html__('暂无记录','ripro-v2');?></p>
            </img>
        </div>
        <!-- End Empty State -->
        <?php else: ?>
        <!-- End Select Group -->
        <div class="list-group">
		<?php foreach ($result as $key => $card) : ?>
			<a target="_blank" href="<?php echo get_the_permalink($card->post_id);?>" class="list-group-item list-group-item-action">
			    <div class="d-flex w-100 justify-content-between">
			      <b class="mb-1"><?php echo get_order_type_text($card);?></b>
			      <small style="min-width: 50px;text-align:right;">￥<?php echo $card->order_price;?></small>
			    </div>
			    <small class="text-muted"><?php echo date('Y-m-d H:i:s',$card->create_time);?> | <?php echo get_order_pay_type_text($card->pay_type);?> | <?php echo convert_site_mycoin($card->order_price,'coin').site_mycoin('name');?></small>
			</a>
		<?php endforeach;?>
		</div>
		<?php ripro_v2_custom_pagination($pagenum,$max_num_pages);?>

    	<?php endif; ?>

    </div>
    <!-- End Body -->
   
</div>

