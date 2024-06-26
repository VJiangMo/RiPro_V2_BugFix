<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ripro-v2
 */

$sidebar = ripro_v2_sidebar();
$column_classes = ripro_v2_column_classes( $sidebar );
get_header();
?>
<div class="container">
	<div class="row">
		<div class="<?php echo esc_attr( $column_classes[0] ); ?>">
			<div class="content-area">
				<?php while ( have_posts() ) : the_post();
					get_template_part( 'template-parts/content/page');
				endwhile; ?>
			</div>
		</div>
		<?php if ( $sidebar != 'none' ) : ?>
			<div class="<?php echo esc_attr( $column_classes[1] ); ?>">
				<aside id="secondary" class="widget-area">
				<?php dynamic_sidebar( 'page_sidebar' ); ?>
				</aside>
			</div>
		<?php endif; ?>
	</div>
</div>
<?php get_footer(); ?>