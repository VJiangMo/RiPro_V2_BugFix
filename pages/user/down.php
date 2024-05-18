<?php
defined('ABSPATH') || exit;
date_default_timezone_set(get_option('timezone_string')); //初始化本地时间

global $current_user;
$today_down = _get_user_today_down($current_user->ID);
$vip_opt    = site_vip();
$vip_type   = _get_user_vip_type($current_user->ID);
$infoArr    = [
    ['title' => '今日可下载', 'value' => $today_down['zong'], 'css' => 'bg-primary'],
    ['title' => '今日已下载', 'value' => $today_down['yi'], 'css' => 'bg-info'],
    ['title' => '剩余下载次数', 'value' => $today_down['ke'], 'css' => 'bg-danger'],
    ['title' => '下载速度/KB每秒', 'value' => $vip_opt[$vip_type]['down_rate'], 'css' => 'bg-success'],
];
?>

<div class="row">
  <?php foreach ($infoArr as $item) : ?>
  <div class="col-6 col-md-3">
    <div class="card mb-3 <?php echo $item['css'];?>">
      <div class="p-4 text-center">
        <h3 class="m-0 text-white">
          <?php echo $item['value'];?>
        </h3>
        <span class="text-white"><?php echo $item['title'];?></span>
      </div>
    </div>
  </div>
  <?php endforeach;?>
</div>

<div class="card mt-0 mt-lg-3">
    <div class="card-header">
      <h5 class="card-title"><?php echo esc_html__('下载记录','ripro-v2');?></h5>
    </div>
    <!-- Body -->
    <div class="card-body">
	     
      <?php
          global $wpdb;
          //** custom_pagination start **//
          $pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1; //第几页
          $limit = 10; //每页显示数量
          $offset = ( $pagenum - 1 ) * $limit; //偏移量
          $total = $wpdb->get_var( $wpdb->prepare("SELECT count(r.down_id) FROM (SELECT down_id FROM {$wpdb->cao_down_log} WHERE user_id=%d GROUP BY down_id) as r", $current_user->ID) );//总数
          $max_num_pages = ceil( $total / $limit ); //多少页
          //** custom_pagination end ripro_v2_custom_pagination($pagenum,$max_num_pages) **//
          $result = $wpdb->get_results( $wpdb->prepare("SELECT r.down_id, r.ip, r.create_time ,count(r.down_id) as count FROM (SELECT * FROM {$wpdb->cao_down_log} WHERE user_id=%d ORDER BY create_time DESC) AS r GROUP BY r.down_id ORDER BY r.create_time DESC LIMIT %d,%d",$current_user->ID,$offset,$limit));
         
      ?>

      <div class="list-group">
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
          <a target="_blank" href="<?php echo get_the_permalink($card->down_id);?>" class="list-group-item list-group-item-action">
              <div class="d-flex w-100 justify-content-between">
                <b class="mb-1"><?php echo get_the_title($card->down_id);?></b>
              </div>
              <small class="text-muted"><?php echo date('Y-m-d H:i:s',$card->create_time);?> | <?php printf(__('累计下载 %s 次', 'ripro-v2'), $card->count);?></small>
          </a>
        <?php endforeach;?>
      </div>
      <?php ripro_v2_custom_pagination($pagenum,$max_num_pages);?>
      <?php endif; ?>

    	<ul class="small text-muted mx-2">
    		<li>下载规则说明：</li>
    		<li>您今日最多可下载<?php echo $today_down['zong'];?>个免费的资源（包含免费资源和会员免费资源）</li>
    		<li>永久会员享有所有会员权限，可以下载包年/包月会员免费资源</li>
    		<li>包年会员享有包月会员所有权限，可以下载包月会员免费资源</li>
    		<li>包月会员仅可以下载包月会员免费的资源</li>
    		<li>全站单独购买的资源，不计算下载次数</li>
    		<li>当日已经下载过的资源，重复点击不计算下载次数</li>
    		<li>下载次数限制只针对本站免费资源，会员免费资源计次</li>
    	</ul>
    </div>
    <!-- End Body -->
   
</div>

