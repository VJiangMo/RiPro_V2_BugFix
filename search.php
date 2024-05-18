<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ripro-v2
 */

get_header();
$sidebar = ripro_v2_sidebar();
$column_classes = ripro_v2_column_classes( $sidebar );
$item_style = ripro_v2_item_style();
?>
<?php if(_cao('is_archive_filter')){
	get_template_part('template-parts/global/archive-filter');
} ?>
<div class="archive container">
	<div class="row">
		<div class="<?php echo esc_attr( $column_classes[0] ); ?>">
			<div class="content-area">
				<div class="row posts-wrapper scroll">
					<?php if ( have_posts() ) : ?>
						<?php
						while ( have_posts() ) : the_post();
							get_template_part( 'template-parts/loop/item', $item_style);
						endwhile;
					else :
						get_template_part( 'template-parts/loop/item', 'none' );
					endif;?>
				</div>
				<?php ripro_v2_pagination(5); ?>
			</div>
		</div>
		<?php if ( $sidebar != 'none' ) : ?>
			<div class="<?php echo esc_attr( $column_classes[1] ); ?>">
				<?php get_sidebar(); ?>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php
get_footer();
