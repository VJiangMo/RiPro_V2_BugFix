<?php
defined('ABSPATH') || exit;
global $current_user;


$user_id     = $current_user->ID;
$get_pay_ids = get_user_meta($user_id,'follow_post',true) ;
if (empty($get_pay_ids)) {
    $get_pay_ids = array(0);
}

//** custom_pagination start **//
$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1; //第几页
$limit = 10; //每页显示数量
$offset = ( $pagenum - 1 ) * $limit; //偏移量
$total = count($get_pay_ids); //总数
$max_num_pages = ceil( $total / $limit ); //多少页
//** custom_pagination end ripro_v2_custom_pagination($pagenum,$max_num_pages) **//


$args = array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => $limit,
    'paged' => $pagenum,
    //'showposts' => count($current_post_ids),
    'post__in' => $get_pay_ids,
    'has_password' => false,
    'ignore_sticky_posts' => 1,
    'orderby' => 'date', // modified - 如果按最新编辑时间排序
    'order' => 'DESC'
);
?>

<div class="card mb-3 mb-lg-5">
    <div class="card-header">
        <h5 class="card-title"><?php echo esc_html__('我的收藏','ripro-v2');?></h5>
    </div>
    <!-- Body -->
    <div class="card-body">
    
      <div class="user-favpage p-0 row">
       <?php query_posts($args);
        if ( have_posts() ){
          while ( have_posts() ) : the_post(); ?>
            
            <?php
              $col_classes = 'col-lg-12';
            ?>

            <div class="<?php echo esc_attr( $col_classes );?>">

              <article id="post-<?php the_ID(); ?>" <?php post_class( 'post post-list' ); ?>>

                <?php if (_cao('is_post_list_type',true)) {
                  echo get_post_type_icon();
                }?>

                  <?php echo _get_post_media(null,'thumbnail');?>

                  <div class="entry-wrapper">

                    <?php if (_cao('is_post_list_category',1)) {
                      ripro_v2_category_dot(2);
                    }?>
                    
                    <header class="entry-header">
                      <?php rizhuti_v2_entry_title(array( 'link' => true ));?>
                    </header>
                    
                  <div class="entry-excerpt"><?php echo ripro_v2_excerpt(); ?></div>

                      <div class="entry-footer">
                    <?php rizhuti_v2_entry_meta(
                     array( 
                      'author' => _cao('is_post_list_author',1), 
                      'category' => false,
                      'comment' => _cao('is_post_list_comment',1),
                      'date' => _cao('is_post_list_date',1),
                      'favnum' => _cao('is_post_list_favnum',1),
                      'views' => _cao('is_post_list_views',1),
                      'shop' => _cao('is_post_list_shop',1),
                     )
                  );?>
                  </div>
                  </div>
              </article>

            </div>


          <?php endwhile;
        }else{
          get_template_part( 'template-parts/loop/item', 'none');
        }
        ?>
      </div>
      <?php 
        global $wp_query;
        ripro_v2_custom_pagination($pagenum,$wp_query->max_num_pages);
        wp_reset_query();
      ?>
    </div>
    <!-- End Body -->
   
</div>

