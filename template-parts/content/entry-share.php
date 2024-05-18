<?php 
global $post,$current_user;
$post_id = $post->ID; //文章ID
$author_id = get_the_author_meta( 'ID' ); ?>

<div class="entry-share">
	<div class="row">
		<div class="col d-none d-lg-block">
            
            <?php if ( _cao('is_single_footer_author',true) ) { ?>
                <a class="share-author" href="<?php echo esc_url( get_author_posts_url($author_id,get_the_author_meta( 'display_name', $author_id ) ));?>">
                    <?php
                    echo get_avatar( $author_id, 50 );
                    echo get_the_author_meta( 'display_name', $author_id ) ._get_user_vip_type_badge($author_id,false);
                    ?>
                </a>
            <?php } ?>
			
		</div>
		<div class="col-auto mb-3 mb-lg-0">

            <?php if ( _cao('is_single_reward',true) ) {
                $img_html = '<span class="reward-qrcode"><span> <img src="'._cao('single_reward_alipay',true).'"> '.esc_html__('支付宝扫一扫','ripro-v2').' </span><span> <img src="'._cao('single_reward_weixin',true).'"> '.esc_html__('微信扫一扫','ripro-v2').' </span></span>';
                echo '<button class="btn btn-sm btn-white" data-toggle="tooltip" data-html="true" data-placement="top" title="'.esc_html($img_html).'"><i class="fa fa-qrcode"></i> '.esc_html__('打赏','ripro-v2').'</button>';
            }?>

			<?php if (!is_close_site_shop()) {

                if (is_fav_post($post_id)) {
                    $arr = array(1=>esc_html__('取消收藏','ripro-v2'),2=>' ok');
                }else{
                    $arr = array(1=>esc_html__('收藏','ripro-v2'),'ripro-v2',2=>'');
                }
                echo '<button class="go-star-btn btn btn-sm btn-white'.$arr[2].'" data-id="'.$post_id.'"><i class="far fa-star"></i> '.$arr[1].'</button>';
            }?>
            
			<?php if (_cao('is_single_share_poser',true)) : ?>
                <button class="share-poster btn btn-sm btn-white" data-id="<?php echo $post_id;?>" title="<?php echo esc_html__('文章封面图', 'ripro-v2');?>"><i class="fa fa-share-alt"></i> <?php echo esc_html__('海报','ripro-v2');?></button>
            <?php endif;?>
            
            <?php if (true) {
                wp_enqueue_script('clipboard');
                if ($current_user->ID>0) {
                    // 生出带参数的推广文章链接
                    $afflink = add_query_arg(array('aff' => $current_user->ID), get_the_permalink($post_id));
                } else {
                    $afflink = get_the_permalink($post_id);
                }

                echo '<button class="go-copy btn btn-sm btn-white" data-toggle="tooltip" data-placement="top" title="'.esc_html__('点击复制链接', 'ripro-v2').'" data-clipboard-text="' . $afflink . '"><i class="fas fa-link"></i> '.esc_html__('链接','ripro-v2').'</button>';
            }?>

		</div>
	</div>
</div>
