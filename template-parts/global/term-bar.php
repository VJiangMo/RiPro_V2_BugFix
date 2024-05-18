<?php

$image = (_cao('is_archive_top_bg_one','0')) ? _cao('archive_top_bg_one_img') : _get_post_thumbnail_url() ;

$termObj = get_queried_object();

if ( !_cao('is_archive_top_bg_one','0') && !empty($termObj->term_id) && $meta_image = get_term_meta($termObj->term_id, 'bg-image', true) ) {
    $image = esc_url($meta_image);
}


$taxonomy = (!empty($termObj->taxonomy)) ? $termObj->taxonomy : 'category';

if ($taxonomy=='question_cat' || $taxonomy=='question_tag' || is_post_type_archive('question')) {
	return;
}

?>
<div class="term-bar <?php echo $taxonomy;?>">
	<div class="term-bg lazyload visible blur scale-12" data-bg="<?php echo esc_url( $image ); ?>"></div>
	<div class="container m-auto">
	<?php if (is_archive()) {
		if ('series' == $taxonomy) {
			the_archive_title( '<h1 class="term-title"><span class="badge badge-pill badge-primary-lighten mr-2">'.esc_html__('专题','ripro-v2').'</span>', '</h1>' );
		    if (!empty($termObj->description)) {
		    	echo '<p class="term-description">'.$termObj->description.'</p>';
		    }
		}else{
			the_archive_title( '<h1 class="term-title">', '</h1>' );
		    if (!empty($termObj->description)) {
		    	echo '<p class="term-description">'.$termObj->description.'</p>';
		    }
		}
	} elseif ( is_search() ) {
	    echo '<h1 class="term-title">' . sprintf( __( '“%s”搜索到<span class="badge badge-primary-lighten mx-1">%s</span>个结果', 'ripro-v2' ), get_search_query(),$wp_query->found_posts ) . '</h1>';
	} ?>
	</div>
</div>
