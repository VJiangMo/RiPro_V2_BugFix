<?php
global $post, $current_user;
$user_id     = $current_user->ID; //用户ID
$post_id     = $post->ID; //文章ID
$click_nonce = wp_create_nonce('rizhuti_click_' . $post_id);

if (!is_single() || is_close_site_shop()) {
    return;
}

//是否购买
$RiClass = new RiClass($post_id,$user_id);
$IS_PAID = $RiClass->is_pay_post();

//按钮组件
$the_user_type = _get_user_vip_type($user_id);
$the_post_price = get_post_price($post_id,$the_user_type);


$thumbnail = _get_post_thumbnail_url($post,'thumbnail');

?>



<div class="hero-shop-warp">
    <div class="container-lg">
        <div class="row">
            <div class="col-lg-4 img-box">
                <img class="lazyload" data-src="<?php echo $thumbnail;?>" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" alt="<?php echo get_the_title();?>" />
                <noscript><img src="<?php echo $thumbnail;?>" alt="<?php echo get_the_title();?>" /></noscript>
            </div>
            <div class="col-lg-8 info-box">
                <?php get_template_part( 'template-parts/content/entry-header' );?>

                <div class="row">
                    <div class="col-lg-6 col-12">
                        <?php the_post_shop_priceo_options($post_id);?>
                    </div>
                    <div class="col-lg-6 col-12">
                        <?php 
                        $cao_info = array();
                        if ( $cao_demourl = get_post_meta($post_id, 'cao_demourl', true) ) {
                            $cao_info[] = array('title'=>esc_html__('演示地址','ripro-v2'),'desc'=>'<a target="_blank" rel="nofollow noopener noreferrer" href="' . $cao_demourl . '" class="badge badge-light-lighten"><i class="fas fa-link"></i> ' . esc_html__('点击查看','ripro-v2') . '</a>');
                        }
                        if ( $cao_expire_day = get_post_shop_expire_day($post_id) ) {
                            $cao_expire_day = ($cao_expire_day==9999) ? esc_html__('购买后永久有效','ripro-v2') : sprintf(__('购买后 %s 天内有效','ripro-v2'),$cao_expire_day) ;
                            $cao_info[] = array('title'=>esc_html__('有效期','ripro-v2'),'desc'=>$cao_expire_day);
                        }
                        if ( true ) {
                            $cao_info[] = array('title'=>esc_html__('最近更新','ripro-v2'),'desc'=>get_the_modified_time('Y年m月d日'));
                        }
                        echo '<ul class="down-info">';
                        foreach ($cao_info as $key => $value) {
                            echo '<li><p class="data-label">' . $value['title'] . '：' . $value['desc'] . '</p></li>';
                        }
                        echo '</ul>';
                        ?>
                    </div>
    
                </div>
                
                <?php 
                
                

                ?>

            </div>
        </div>
    </div>
</div>
