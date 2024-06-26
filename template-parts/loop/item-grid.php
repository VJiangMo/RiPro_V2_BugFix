<?php
	$col_classes = (ripro_v2_sidebar() != 'none') ? 'col-6' : 'col-lg-5ths col-lg-3 col-md-4 col-6' ;

	$format = get_post_format();
	if ( 'image' == $format || 'gallery' == $format ) {
		$post_class = 'post post-grid bg-image lazyload';
		$data_bg = ' data-bg="'._get_post_thumbnail_url(null, 'full').'"';
	}else{
		$post_class = 'post post-grid';
		$data_bg = '';
	}
?>

<div class="<?php echo esc_attr( $col_classes );?>">

	<article id="post-<?php the_ID(); ?>" <?php post_class( $post_class );?><?php echo $data_bg;?>>

		<?php if (_cao('is_post_grid_type',true)) {
			echo get_post_type_icon();
		}?>

	    <?php echo _get_post_media(null,'thumbnail');?>
	    
	    <div class="entry-wrapper">
	    	
	    	<?php if (_cao('is_post_grid_category',1)) {
	    		ripro_v2_category_dot(2);
	    	}?>
	    	
	    	<header class="entry-header">
	    		<?php rizhuti_v2_entry_title(array( 'link' => true ));?>
	    	</header>
	      	
	      	<?php if (_cao('is_post_grid_excerpt',1)) {
	      		echo '<div class="entry-excerpt">'.ripro_v2_excerpt().'</div>';
	      	}?>

	      	<div class="entry-footer">
			<?php rizhuti_v2_entry_meta(
			   array( 
			   	'author' => _cao('is_post_grid_author',1), 
			   	'category' => false,
			   	'comment' => _cao('is_post_grid_comment',1),
			   	'date' => _cao('is_post_grid_date',1),
			   	'favnum' => _cao('is_post_grid_favnum',1),
			   	'views' => _cao('is_post_grid_views',1),
			   	'shop' => _cao('is_post_grid_shop',1),
			   )
			);?>
			</div>
	    </div>
	</article>

</div>
