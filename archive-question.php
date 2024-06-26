<?php
/**
 * The template for displaying question archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package rizhuti-v2
 */


get_header();
$sidebar = 'right';
$column_classes = ripro_v2_column_classes( $sidebar );
$item_style = 'list';


$action = (isset($_GET['type'])) ? strtolower($_GET['type']) : 'date' ;
$termObj = get_queried_object();
$taxonomy = (!empty($termObj) && !empty($termObj->taxonomy)) ? $termObj->taxonomy : '';

// 查询
$_args = array(
    'post_type' => 'question',
    'paged' => get_query_var('paged', 1),
);

//搜索
if ( isset($_GET['search']) ) {
	$_args['s'] = $_GET['search'];
}


if ( !empty($taxonomy) ) {

	$_args['tax_query'] = array(
		array(
            'taxonomy' => $taxonomy,
            'terms' => $termObj->term_id
        )
    );
}

switch ($action) {
	case 'hot':
		$_args['meta_key'] = 'views';
		$_args['order'] = 'DESC';
		$_args['orderby'] = 'meta_value_num';
		break;
	case 'uncomment':
		$_args['order'] = 'ASC';
		$_args['orderby'] = 'comment_count';
		break;
	case 'comment':
		$_args['order'] = 'DESC';
		$_args['orderby'] = 'comment_count';
		break;
	default:
		break;
}



$PostData = new WP_Query( $_args ); 

?>
	<div class="archive question-archive container">


		<div class="row">
			<div class="<?php echo esc_attr( $column_classes[0] ); ?>">
				
				<?php 
				// 分类按钮 
				$q_categories = get_terms('question_cat', array('hide_empty' => 0));
				if (!empty($q_categories)) {
				  echo '<div class="question-cat">';
				  $is_all_current = (empty($termObj->term_id)) ? ' class="current"' : '' ;
				  echo '<a href="'.get_post_type_archive_link( 'question' ).'">'.esc_html__('全部分类','ripro-v2').'</a>';
				  foreach ($q_categories as $item) {
				      $is_current = (!empty($termObj->term_id) && $termObj->term_id == $item->term_id) ? ' class="current"' : '' ;
				      echo '<a'.$is_current.' href="'.get_category_link($item->term_id).'" title="'.sprintf(__('%s个文章', 'ripro-v2'),$item->count).'">'.$item->name.'<span class="badge badge-success-lighten ml-1">'.$item->count.'</span></a>';
				  }
				  echo '</div>';
				}
				//筛选按钮
				$nav_tabs = array(
					'date' => esc_html__('最新','ripro-v2'),
					'hot' => esc_html__('热门','ripro-v2'),
					'uncomment' => esc_html__('未回答','ripro-v2'),
					'comment' => esc_html__('已回答','ripro-v2'),
					'new_question' => esc_html__('发布新提问','ripro-v2'),
				);

				echo '<ul class="nav nav-tabs question-nav">';
				foreach ($nav_tabs as $key=>$name) {
					$is_active = ($action==$key) ? ' active' : '';
					echo '<a class="nav-link'.$is_active.'" href="'.add_query_arg("type",$key).'">'.$name.'</a>';
				}
				echo '</ul>';

				?>


				<div class="content-area question-area">
					
					<?php if ($action=='new_question') :?>
					<div class="posts-wrapper">
						<form id="question-form" class="question-form">
							<div class="form-group">
							    <label class="text-muted"><?php echo esc_html__('问题标题','ripro-v2');?></label>
							    <input type="text" class="form-control" name="question_title" placeholder="<?php echo esc_html__('输入问题标题','ripro-v2');?>" value="" autocomplete="off">
							    <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('rizhuti-v2-click-' . $current_user->ID);?>">
							</div>
							<div class="form-row">
		    					<div class="form-group col-md-4">
								<?php wp_dropdown_categories( array(
								'hide_empty'       => 0,
								'orderby'          => 'name',
								'name'          => 'question_cat',
								'hierarchical'     => true,
								'id'     => 'question_cat',
								'class'     => 'selectpicker',
								'taxonomy' => 'question_cat',
								'show_option_none' => esc_html__('选择分类','ripro-v2')
								) );?>
								</div>
								<div class="form-group col-md-8">
								<input type="text" class="form-control" name="question_tag" placeholder="<?php echo esc_html__('话题标签','ripro-v2');?>" value="">
								</div>
							</div>

							
						    <div class="form-group">
						    	<label class="text-muted"><?php echo esc_html__('问题描述','ripro-v2');?></label>
						    	<?php wp_editor(
									'',
									'question_content',
									array(
										'media_buttons' => false,
										'tinymce'       => false,
										'textarea_rows' => 6,
									)
								);?>
						    </div>
						    <button class="btn btn-primary my-4 go-inst-question-new"><i class="fa fa-send"></i> <?php echo esc_html__('发布问题','ripro-v2');?></button>
						</form>
						<ul class="small text-muted m-0 pl-3 pb-4">
					        <li>标题长度需大于6个字</li>
					        <li>描述内容可不填写</li>
					        <li>本站会员发布用户无需审核</li>
					        <li>切勿重复提交或恶意提交</li>
					        <li>恶意提交刷内容者封号处理</li>
					    </ul>
					</div>
					<?php else: ?>
					<div class="row posts-wrapper scroll">
						<?php if ( $PostData->have_posts() ) : ?>
							<?php
							/* Start the Loop */
							while ( $PostData->have_posts() ) : $PostData->the_post();
								get_template_part( 'template-parts/loop/item-list-question');
							endwhile;
						else :
							get_template_part( 'template-parts/loop/item', 'none' );

						endif;
						?>
					</div>
					<?php ripro_v2_pagination(5); ?>
					<?php endif;?>
					
				</div>
			</div>
			<?php if ( $sidebar != 'none' ) : ?>
				<div class="<?php echo esc_attr( $column_classes[1] ); ?>">
					<aside id="secondary" class="widget-area">
					<?php get_template_part( 'template-parts/global/widget-question'); ?>
					</aside>
				</div>
			<?php endif; ?>
		</div>
	</div>

<script type="text/javascript">
  jQuery(function() {
      'use strict';
      //提交问题
      $(document).on('click', ".go-inst-question-new", function(e) {
          e.preventDefault();
          var _this = $(this);
          var d = {};
          var t = $('#question-form').serializeArray();
          $.each(t, function() {
              d[this.name] = this.value;
          });
          d['action'] = "add_question_new";
          var _icon = _this.children("i").attr("class");
          rizhuti_v2_ajax(d, function(before) {
              _this.children("i").attr("class", "fa fa-spinner fa-spin")
          }, function(result) {
              if (result.status == 1) {
                  ripro_v2_toast_msg("success", result.msg, function() {
                      location.reload();
                  })
              } else {
                  ripro_v2_toast_msg("info", result.msg)
              }
          }, function(complete) {
              _this.children("i").attr("class", _icon)
          });
      });
  });
</script>
<?php
get_footer();
