<?php
$bg_image = _get_post_thumbnail_url(null, 'full');
?>

<div class="hero lazyload visible" data-bg="<?php echo esc_url($bg_image); ?>">
<?php

if ( is_post_shop_video() ) {
    get_template_part('template-parts/content/hero-video');
}elseif ( !is_close_site_shop() && !wp_is_mobile() && _cao('is_single_shop_template',true) && is_post_shop_down() ) {
	get_template_part('template-parts/content/hero-shop');
} else {
    echo '<div class="container">';
    get_template_part('template-parts/content/entry-header');
    echo '</div>';
}
?>
</div>