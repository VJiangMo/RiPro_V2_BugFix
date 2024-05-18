<?php

global $wpdb;

?>


<div class="widget question-search">
	<h5 class="widget-title mb-3"><?php echo esc_html__('搜索问题','ripro-v2');?></h5>
	
	<form method="get" class="search-form" action="<?php echo esc_url( get_post_type_archive_link( 'question' ) ); ?>">
		<input type="text" class="form-control" placeholder="<?php echo esc_html__( '输入关键词 回车...', 'ripro-v2' ); ?>" autocomplete="off" value="<?php echo (isset($_GET['search'])) ? $_GET['search'] : '';?>" name="search" required="required">
	</form>
</div>

<div class="widget question-post-title">
	<h5 class="widget-title mb-3"><?php echo esc_html__('随机问题','ripro-v2');?></h5>
	<ul class="question-title">
	<?php
	
	// 查询
	$_args = array(
	    'post_type' => 'question',
	    'order' => 'DESC',
	    'orderby' => 'rand',
	    'posts_per_page' => 5,
	);

	$PostData = new WP_Query( $_args ); 
	if ( $PostData->have_posts() ) {
		while ( $PostData->have_posts() ) : $PostData->the_post();
			rizhuti_v2_entry_title(array( 'tag' => 'li' ,'link' => true ));
		endwhile;
	}
	?>
	</ul>
	
</div>


<div class="widget question-users">
	<h5 class="widget-title"><?php echo esc_html__('回答排行','ripro-v2');?></h5>
	<ul>
	<?php
	$result = $wpdb->get_results("select a.user_id,count(a.comment_ID) as comment_num from {$wpdb->comments} as a left join {$wpdb->posts} as b on (a.comment_post_ID = b.ID) where a.comment_parent=0 and b.post_type='question' group by a.user_id order by count(a.comment_ID) desc limit 5");
	foreach ($result as $key => $item) {
		echo '<li class="d-flex align-items-center">';
		echo get_avatar($item->user_id);
		echo '<div class="ml-3">';
		echo '<b>'.get_the_author_meta( 'display_name', $item->user_id ).'</b>';
		echo '<p class="small text-muted m-0">排名：'.($key+1).' | 回答：'.$item->comment_num.'</p>';
        echo '</div>';
		echo '</li>';
	}?>
	</ul>
</div>



<div class="widget question-tags">
	<h5 class="widget-title mb-3"><?php echo esc_html__('热门话题','ripro-v2');?></h5>
	<div class="tagcloud">
	<?php
	
	$tags = get_tags(array(
	  'taxonomy' => 'question_tag',
	  'orderby' => 'count',
	  'number' => 16, //显示
	  'hide_empty' => true // for development
	));
	foreach ($tags as $tag) {
		echo '<a href="'.get_tag_link( $tag->term_id ).'" class="tag-cloud-link" rel="tag" title="'.$tag->name.'">'.$tag->name.' ('.$tag->count.')</a>';
	}?>
	</div>
	
</div>
