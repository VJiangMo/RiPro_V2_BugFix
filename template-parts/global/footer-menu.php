<div class="m-menubar">
	<?php $footer_menu = _cao('site_footer_menu');
	if ( !empty($footer_menu) && is_array($footer_menu) ) : ?>
	<ul>
	<?php foreach ($footer_menu as $item) : ?>
		<li>
			<?php $target = (empty($item['is_blank'])) ? '' : ' target="_blank"' ;?>
			<a<?php echo $target;?> href="<?php echo $item['href'];?>" rel="nofollow noopener noreferrer"><i class="<?php echo $item['icon'];?>"></i><?php echo $item['title'];?></a>
		</li>
		<?php endforeach;?>
		<li>
			<a href="javacript:void(0);" class="back-to-top" rel="nofollow noopener noreferrer"><i class="fas fa-chevron-up"></i><?php echo esc_html__( '顶部', 'ripro-v2' );?><span></span></a>
		</li>
	</ul>
	<?php endif;?>
</div>
