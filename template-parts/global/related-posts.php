<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$related_posts_item_style = _cao('related_posts_item_style');
$related_posts_item_num = (int)_cao('single_related_posts_num', 4);

$related_posts_ids = get_related_posts_ids($related_posts_item_num);

if (empty($related_posts_ids)) {
  return;
}


$args = array(
  'post_type' => 'post',
  'post_status' => 'publish',
  'posts_per_page' => $related_posts_item_num,
  'post__in'  => $related_posts_ids,
  'update_post_thumbnail_cache' => true,
  'orderby' => 'post__in',
  'no_found_rows' => true,
  'ignore_sticky_posts' => true,
);

$related_posts = new WP_Query($args);

$col_classes   = ($related_posts_item_style == 'list') ? 'col-lg-6 col-12' : 'col-lg-3 col-md-4 col-6 ';
if ($related_posts->have_posts()): ?>
    <div class="related-posts">
        <h3 class="u-border-title"><?php echo apply_filters('rizhuti_v2_related_posts_title', esc_html__('相关文章', 'ripro-v2')); ?></h3>
        <div class="row">
          <?php while ($related_posts->have_posts()): $related_posts->the_post();?>
            <div class="<?php echo esc_attr($col_classes); ?>">
              <article id="post-<?php the_ID();?>" <?php post_class('post post-' . $related_posts_item_style);?>>
                  <?php echo _get_post_media(null, 'thumbnail',false); ?>
                  <div class="entry-wrapper">
                    <header class="entry-header"><?php rizhuti_v2_entry_title(array('link' => true));?></header>
                    <?php if ($related_posts_item_style == 'list'): ?>
                    <div class="entry-footer"><?php rizhuti_v2_entry_meta(array('category' => true, 'views' => true,'date' => true));?></div>
                    <?php endif;?>
                </div>
            </article>
          </div>
          <?php endwhile;wp_reset_postdata();?>
        </div>
    </div>
<?php endif;