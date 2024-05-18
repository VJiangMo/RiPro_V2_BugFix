<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ripro-v2
 */
?>
	</main><!-- #main -->
	
	<footer class="site-footer">
		<?php get_template_part( 'template-parts/global/footer-widget' ); ?>
		<div class="footer-copyright d-flex text-center">
			<div class="container">
				<?php if ( !empty(_cao('site_copyright_text')) || !empty(_cao('site_copyright_text')) ) :?>
			    <p class="m-0 small">
			    	<?php echo _cao('site_copyright_text')._cao('site_ipc_text')._cao('site_ipc2_text'); ?><?php do_action('get_copyright_before');?>
				</p>
				<?php endif;?>
				
				<?php if (defined('WP_DEBUG') && WP_DEBUG == true) {
				echo '<p class="m-0 small text-muted">'.sprintf(__('SQL 请求数：%s 次', 'ripro-v2'),get_num_queries()).'<span class="sep"> | </span>'.sprintf(__('页面生成耗时：%s 秒', 'ripro-v2'),timer_stop(0,5)).'</p>';
				}?>
			</div>
		</div>

	</footer><!-- #footer -->

</div><!-- #page -->

<?php 

get_template_part( 'template-parts/global/footer-rollbar' );
get_template_part( 'template-parts/global/footer-menu' );

if (_cao('navbar_omnisearch_search', true)) {
	get_template_part( 'template-parts/global/omnisearch' );
} 

?>

<div class="dimmer"></div>

<?php 
get_template_part( 'template-parts/global/off-canvas' );
?>

<?php wp_footer(); ?>

<!-- 自定义js代码 统计代码 -->
<?php if ( !empty(_cao('web_js')) ) echo _cao('web_js'); ?>
<!-- 自定义js代码 统计代码 END -->

</body>
</html>
