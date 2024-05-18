<div class="rollbar">
	<?php $rollbar = _cao('site_footer_rollbar');
	if ( !empty($rollbar) && is_array($rollbar) ) : ?>
	<ul class="actions">
	<?php foreach ($rollbar as $item) : ?>
		<li>
			<?php $target = (empty($item['is_blank'])) ? '' : ' target="_blank"' ;?>
			<a<?php echo $target;?> href="<?php echo $item['href'];?>" rel="nofollow noopener noreferrer" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo esc_html($item['title']);?>"><i class="<?php echo $item['icon'];?>"></i></a>
		</li>
		<?php endforeach;?>
	</ul>
	<?php endif;?>
	<div class="rollbar-item back-to-top">
		<i class="fas fa-chevron-up"></i>
	</div>
</div>
