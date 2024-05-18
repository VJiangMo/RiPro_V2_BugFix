<?php 

// 分类筛选

$filter_style = (wp_is_mobile()) ? 'dropdown' : _cao('archive_filter_style','dropdown') ;

$currentterm = get_queried_object();
$currentterm_id = (isset($currentterm->term_id)) ? $currentterm->term_id : 0 ;


//筛选


if ( !empty( get_term_meta($currentterm_id, 'is_no_archive_filter', true) ) ) {
  return;
}


$top_term_id = (is_category()) ? get_category_root_id($currentterm_id ) : 0 ;
$current_cats = array($currentterm_id);
$parent_id = (isset($currentterm->parent)) ? $currentterm->parent : 0 ;
while($parent_id){
    $current_cats[] = $parent_id;
    $parent_term = get_term($parent_id,'category');
    $parent_id = $parent_term->parent;
}

?>

<?php if ($filter_style=='dropdown') : ?>

<!-- dropdown-mod -->
<div class="archive-filter">
  <div class="container">
    <div class="filters">
      <?php 
      //一级分类
      $filter_cat_1 = _cao('archive_filter_cat_1');
      if (_cao('is_archive_filter_cat', '1') && !empty($filter_cat_1)) {
          echo '<div class="dropdown">';
          echo '<button class="btn btn-white dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-layer-group"></i> ' . esc_html__('全部分类', 'ripro-v2') . '</button>';
          echo '<div class="dropdown-menu">';
          foreach ($filter_cat_1 as $_cid) {
              $item       = get_term($_cid, 'category');
              if (!$item) {
                continue;
              }
              $is_current = (in_array($item->term_id, $current_cats)) ? ' active' : '';
              echo '<a class="dropdown-item' . $is_current . '" href="' . get_category_link($_cid) . '" title="' . sprintf(__('%s个文章', 'ripro-v2'), $item->count) . '">' . $item->name . '</a>';
          }
          echo '</div>';
          echo '</div>';
      }

      // 子分类
      if (_cao('is_archive_filter_cat_child', '1')) {
        $c_cats      = array_reverse($current_cats);
        $cat_orderby = _cao('archive_filter_cat_orderby', 'id');
        foreach ($c_cats as $key => $child_id) {
          if (empty($child_id)) continue;
          $the_child        = get_category($child_id);
          $child_categories = get_terms('category', array('hide_empty' => 0, 'parent' => $child_id, 'orderby' => $cat_orderby, 'order' => 'DESC'));
          if (!empty($child_categories)) {
              echo '<div class="dropdown">';
              echo '<button class="btn btn-white dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-long-arrow-alt-right"></i> ' . $the_child->name . '</button>';
              echo '<div class="dropdown-menu">';
              foreach ($child_categories as $item) {
                  $is_current = (in_array($item->term_id, $c_cats)) ? ' active' : '';
                  echo '<a class="dropdown-item' . $is_current . '" href="' . get_category_link($item->term_id) . '" title="' . sprintf(__('%s个文章', 'ripro-v2'), $item->count) . '">' . $item->name . '</a>';
              }
              echo '</div></div>';
          }
        }
      }

      //相关标签
      if (_cao('is_archive_filter_tag', '1') && is_category()) {
        $tags = _get_category_to_tags($currentterm_id);
        $tags = (empty($tags)) ? _get_category_to_tags($top_term_id) : $tags;
        if (!empty($tags)) {
            echo '<div class="dropdown">';
            echo '<button class="btn btn-white dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-tag"></i> ' . esc_html__('相关标签', 'ripro-v2') . '</button>';

            echo '<div class="dropdown-menu">';
            foreach ($tags as $tag) {
                echo '<li><a class="dropdown-item" href="' . get_tag_link($tag->term_id) . '">' . $tag->name . '</a></li>';
            }
            echo '</div></div>';
        }
      }

      //价格
      if (_cao('is_archive_filter_price', '1') && !is_close_site_shop()) {
        $current  = !empty($_GET['price_type']) ? $_GET['price_type'] : '';
        $site_opt = site_vip();
        $type_arr = array(
            '0' => esc_html__('价格', 'ripro-v2'),
            '1' => esc_html__('免费', 'ripro-v2'),
            '2' => esc_html__('付费', 'ripro-v2'),
            '3' => $site_opt['vip']['name'].esc_html__('免费', 'ripro-v2'),
            '4' => $site_opt['vip']['name'].esc_html__('折扣', 'ripro-v2'),
            '5' => $site_opt['boosvip']['name'].esc_html__('免费', 'ripro-v2'),
        );
        echo '<div class="dropdown">';
        echo '<button class="btn btn-white dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="' . site_mycoin('icon') . '"></i> ' . esc_html__('价格', 'ripro-v2') . '</button>';
        echo '<div class="dropdown-menu">';
        foreach ($type_arr as $key => $item) {
            $is_current = ($current == $key) ? ' active' : '';
            echo '<a class="dropdown-item' . $is_current . '" href="' . add_query_arg("price_type", $key) . '">' . $item . '</a>';
        }
        echo '</div></div>';
      }

      // 自定义筛选
      
      if ( _cao('is_custom_post_meta_opt', '0') && is_array(_cao('custom_post_meta_opt', false)) ) {
        $custom_post_meta_opt = _cao('custom_post_meta_opt', array());

        foreach ($custom_post_meta_opt as $key => $filter) {
          $opt_meta_category = (array_key_exists('meta_category',$filter)) ? $filter['meta_category'] : false ;

          if (!$opt_meta_category || in_array($currentterm_id,$opt_meta_category) ) {
            $_meta_key = $filter['meta_ua'];
            $is_on = !empty($_GET[$_meta_key]) ? $_GET[$_meta_key] : '';
            echo '<div class="dropdown">';
            echo '<button class="btn btn-white dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-filter"></i> ' . $filter['meta_name'] . '</button>';
            echo '<div class="dropdown-menu">';
            echo '<a class="dropdown-item" href="' . add_query_arg($_meta_key,'all') . '">' . esc_html__('全部','ripro-v2') . '</a>';
            foreach ($filter['meta_opt'] as $opt) {
                $is_current = (!empty($_GET[$_meta_key]) && $_GET[$_meta_key]== $opt['opt_ua']) ? ' active' : '';
                echo '<a class="dropdown-item' . $is_current . '" href="' . add_query_arg($_meta_key,$opt['opt_ua']) . '">' . $opt['opt_name'] . '</a>';
            }
            echo '</div></div>';
          }
        }
      }


      //排序
      if (_cao('is_archive_filter_order', '1') && !is_close_site_shop()) {
        $current   = !empty($_GET['order']) ? $_GET['order'] : 'date';
        $order_arr = array(
            'date'          => esc_html__('发布日期', 'ripro-v2'),
            'modified'      => esc_html__('更新日期', 'ripro-v2'),
            'comment_count' => esc_html__('评论数量', 'ripro-v2'),
            'rand'          => esc_html__('随机展示', 'ripro-v2'),
            'views'         => esc_html__('热度排行', 'ripro-v2'),
        );
        echo '<div class="dropdown">';
        echo '<button class="btn btn-white dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-sort-alpha-down"></i> ' . esc_html__('排序', 'ripro-v2') . '</button>';
        echo '<div class="dropdown-menu">';
        foreach ($order_arr as $key => $item) {
            $is_current = ($current == $key) ? ' active' : '';
            echo '<a class="dropdown-item' . $is_current . '" href="' . add_query_arg("order", $key) . '">' . $item . '</a>';
        }
        echo '</div></div>';
    }?>
    </div>
  </div>
</div>


<?php elseif ($filter_style=='inline') : ?>

<!-- inline-mod  2 -->
<div class="archive-filter-2">
  <div class="container">
    <div class="filters">
      <?php 
      //一级分类
      $filter_cat_1 = _cao('archive_filter_cat_1');
      if (_cao('is_archive_filter_cat', '1') && !empty($filter_cat_1)) {
          echo '<ul class="filter">';
          echo '<li><span><i class="fas fa-layer-group mr-1"></i>'.esc_html__( '分类', 'ripro-v2').'</span></li>';
          foreach ($filter_cat_1 as $_cid) {
              $item       = get_term($_cid, 'category');
              if (!$item) {continue;}
              $is_current = (in_array($item->term_id, $current_cats)) ? ' current' : '';
              $count = (_cao('is_archive_filter_cat_num',true)) ? '<span class="badge badge-pill badge-primary-lighten ml-1">'.$item->count.'</span>' : '' ;
                  echo '<li class="' . $is_current . '"><a href="'.get_category_link($item->term_id).'" title="'.sprintf(__('%s个文章', 'ripro-v2'),$item->count).'">'.$item->name.$count.'</a></li>';
          }
          echo '</ul>';
      }

      // 子分类
      if (_cao('is_archive_filter_cat_child', '1')) {
        $c_cats      = array_reverse($current_cats);
        $cat_orderby = _cao('archive_filter_cat_orderby', 'id');
        foreach ($c_cats as $key => $child_id) {
          if (empty($child_id)) continue;
          $the_child        = get_category($child_id);
          $child_categories = get_terms('category', array('hide_empty' => 0, 'parent' => $child_id, 'orderby' => $cat_orderby, 'order' => 'DESC'));
          if (!empty($child_categories)) {
              echo '<ul class="filter">';
              echo '<li><span><i class="fas fa-level-up-alt mr-1"></i>'.$the_child->name.'</span></li>';
              foreach ($child_categories as $item) {
                  $is_current = (in_array($item->term_id, $c_cats)) ? ' current' : '';
                  $count = (_cao('is_archive_filter_cat_num',true)) ? '<span class="badge badge-pill badge-danger-lighten ml-1">'.$item->count.'</span>' : '' ;
                  echo '<li class="' . $is_current . '"><a href="'.get_category_link($item->term_id).'" title="'.sprintf(__('%s个文章', 'ripro-v2'),$item->count).'">'.$item->name.$count.'</a></li>';
              }
              echo '</ul>';
          }
        }
      }

      //相关标签
      if (_cao('is_archive_filter_tag', '1') && is_category()) {
        $tags = _get_category_to_tags($currentterm_id);
        $tags = (empty($tags)) ? _get_category_to_tags($top_term_id) : $tags;
        if (!empty($tags)) {
            echo '<ul class="filter">';
            echo '<li><span><i class="fas fa-tag mr-1"></i>'.esc_html__('标签', 'ripro-v2').'</span></li>';
            foreach ($tags as $tag) {
                echo '<li><a target="_blank" href="'.get_tag_link($tag->term_id).'">'.$tag->name.'</a></li>';
            }
            echo '</ul>';
        }
      }


      // 自定义筛选
      
      if ( _cao('is_custom_post_meta_opt', '0') && is_array(_cao('custom_post_meta_opt', false)) ) {
        $custom_post_meta_opt = _cao('custom_post_meta_opt', array());

        foreach ($custom_post_meta_opt as $key => $filter) {
          $opt_meta_category = (array_key_exists('meta_category',$filter)) ? $filter['meta_category'] : false ;

          if (!$opt_meta_category || in_array($currentterm_id,$opt_meta_category) ) {
            $_meta_key = $filter['meta_ua'];
            $is_all = empty($_GET[$_meta_key]) ? 'current' : '';

            echo '<ul class="filter">';
            echo '<li><span><i class="fas fa-filter mr-1"></i>'.$filter['meta_name'].'</span></li>';
            echo '<li class="' . $is_all . '"><a href="' . add_query_arg($_meta_key,'') . '">' . esc_html__('全部','ripro-v2') . '</a></li>';
            foreach ($filter['meta_opt'] as $opt) {
                $is_current = (!empty($_GET[$_meta_key]) && $_GET[$_meta_key]== $opt['opt_ua']) ? 'current' : '';
                echo '<li class="' . $is_current . '"><a href="' . add_query_arg($_meta_key,$opt['opt_ua']) . '">' . $opt['opt_name'] . '</a></li>';
            }
            echo '</ul>';

          }

        }
      }

      //价格和排序
      if ( (_cao('is_archive_filter_price', '1') && !is_close_site_shop()) ||  _cao('is_archive_filter_order', '1') ) {
        echo '<div class="filter-tab"><div class="row">';
        echo '<div class="col-12 col-sm-7">';
        //价格
        if (_cao('is_archive_filter_price', '1') && !is_close_site_shop()) {
          $current  = !empty($_GET['price_type']) ? $_GET['price_type'] : '';
          $site_opt = site_vip();
          $type_arr = array(
              '0' => esc_html__('全部', 'ripro-v2'),
              '1' => esc_html__('免费', 'ripro-v2'),
              '2' => esc_html__('付费', 'ripro-v2'),
              '3' => $site_opt['vip']['name'].esc_html__('免费', 'ripro-v2'),
              '4' => $site_opt['vip']['name'].esc_html__('折扣', 'ripro-v2'),
              '5' => $site_opt['boosvip']['name'].esc_html__('免费', 'ripro-v2'),
          );
          echo '<ul class="filter">';
          echo '<li><span><i class="' . site_mycoin('icon') . ' mr-1"></i>'.esc_html__('价格', 'ripro-v2').'</span></li>';
          foreach ($type_arr as $key => $item) {
              $is_current = ($current == $key) ? ' current' : '';
              echo '<li class="' . $is_current . '"><a href="' . add_query_arg("price_type", $key) . '">' . $item . '</a></li>';
          }
          echo '</ul>';
        }
        
        echo '</div>';


        //排序
        echo '<div class="col-12 col-sm-5 recent">';
        if ( _cao('is_archive_filter_order', '1') ) {
          $current   = !empty($_GET['order']) ? $_GET['order'] : 'date';
          $order_arr = array(
              'date'          => '<i class="far fa-clock"></i> '.esc_html__('最新', 'ripro-v2'),
              'views'         => '<i class="far fa-eye"></i> '.esc_html__('热度', 'ripro-v2'),
              'modified'      => '<i class="far fa-clock"></i> '.esc_html__('更新', 'ripro-v2'),
              'comment_count' => '<i class="far fa-comment-dots"></i> '.esc_html__('评论', 'ripro-v2'),
              'rand'          => '<i class="fas fa-random"></i> '.esc_html__('随机', 'ripro-v2'),
          );
          echo '<ul class="filter">';
          foreach ($order_arr as $key => $item) {
            $is_current = ($current == $key) ? ' current' : '';
            echo '<li class="' . $is_current . '"><a href="' . add_query_arg("order", $key) . '">' . $item . '</a></li>';
          }
          echo '</ul>';
        }
        echo '</div>';

        echo '</div></div>';

      }
      

      ?>

    </div>
  </div>
</div>

<?php endif; ?>
