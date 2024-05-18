<?php
/**
 * Template Name: 专题列表
 */

get_header();?>

<div class="hero visible page-top-hero" data-bg="">
	<div class="container">
		<header class="entry-header" style=" margin: 0 auto; text-align: center; ">
		<?php rizhuti_v2_entry_title(array('link' => false, 'tag' => 'h1'));
		while ( have_posts() ) : the_post();
			the_content();
		endwhile;?>
		</header>
	</div>
</div>


<div class="container">

	<?php $terms = get_terms( 'series', array(
	    'hide_empty' => true,
	    'orderby' => 'ID',
	    'order' => 'DESC', //DESC ASC /此处可以修改专题获取排序模式
	) );?>

	<div class="row">
		<?php foreach ( $terms as $item ){
		    $category = get_term_by('ID', $item->term_id,'series');
		    $bg_img = get_term_meta($item->term_id, 'bg-image', true);
		    $bg_img = (!empty($bg_img)) ? $bg_img : get_template_directory_uri().'/assets/img/series-bg.jpg';
		    

		    echo '<div class="col-lg-6 col-12 series-item-warp">';
		    	echo '<div class="series-item">';

		    	echo '<div class="series-item-top">';
			    echo '<div class="series-item-thumb">';
			    echo '<a href="'.esc_url( get_term_link( $category->term_id ) ).'" target="_blank"> <img class="lazyload" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="'.$bg_img.'" alt="'.$category->name.'"></a>';
			    echo '</div>';

			    echo '<div class="series-item-title">';
			    echo '<h2><a href="'.esc_url( get_term_link( $category->term_id ) ).'" target="_blank">'.$category->name.'</a> <span class="badge badge-pill badge-primary-lighten mr-2">'.esc_html__('专题','ripro-v2').'</span></h2>';
			    echo '<p>'.$category->description.'</p>';
			    echo '</div>';

			    echo '<a class="series-item-more" href="'.esc_url( get_term_link( $category->term_id ) ).'">'.esc_html__('进入专题','ripro-v2').' <i class="fas fa-angle-double-right"></i></a>';

			    echo '</div>';


			    echo '<ul class="series-item-bottom">';
			    // 查询
			    $_args = array(
			        'tax_query' => array('relation' => 'OR',array(
				        'taxonomy' => 'series',
				        'field'    => 'term_id',
				        'terms' => $item->term_id,
				        'operator' => 'IN'
				    )),
			        'ignore_sticky_posts' => true,
			        'post_status'         => 'publish',
			        'posts_per_page'      => 3,
			    );
			    $PostData = new WP_Query($_args);
			    while ($PostData->have_posts()): $PostData->the_post(); $i++;
			    echo '<li><a href="' . esc_url( get_permalink() ) . '" title="' . get_the_title() . '" rel="bookmark" target="_blank">' . get_the_title() . '</a></li>';
			    endwhile;

			    wp_reset_postdata();
			    echo '</ul>';

		    	echo '</div>';
		    echo '</div>';

		    
		    
		}?>


	</div>

</div>
<?php get_footer();?>