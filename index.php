<?php
/**
 * The sidebar containing the main widget area
 * 默认首页，非模块化首页，博客格式布局
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ripro-v2
 */

get_header();


if ( is_active_sidebar( 'modules' ) ){
    dynamic_sidebar('modules');
}else{

	// 默认显示项目
	rizhuti_v2_module_search(array (
	  'name' => '首页模块化布局',
	  'id' => 'modules',
	  'description' => '添加首页模块化布局',
	  'class' => '',
	  'before_widget' => '<div id="rizhuti_v2_module_search-2" class="section rizhuti_v2-widget-search-bg"><div class="container">',
	  'after_widget' => '</div></div>',
	  'before_title' => '<h3 class="section-title"><span>',
	  'after_title' => '</span></h3>',
	  'before_sidebar' => '',
	  'after_sidebar' => '',
	  'widget_id' => 'rizhuti_v2_module_search-2',
	  'widget_name' => 'RI-首页模块 : 高级搜索模块',
	), array (
	  'title' => '这是一个优雅的搜索框',
	  'desc' => '支持图片背景，视频背景，支持分类高级筛选搜索，支持自定义搜索热门词展示',
	  'placeholder' => '优质内容等你来搜',
	  'bg_type' => 'img',
	  'bg' => get_template_directory_uri() . '/assets/img/bg.jpg',
	  'is_cat' => '0',
	  'search_hot' => 'wordpress,html,作品,测试,下载,素材,作品,主题,插件',
	));


	rizhuti_v2_module_lastpost_item(array (
	  'name' => '首页模块化布局',
	  'id' => 'modules',
	  'description' => '添加首页模块化布局',
	  'class' => '',
	  'before_widget' => '<div id="rizhuti_v2_module_lastpost_item-2" class="section rizhuti_v2-widget-lastpost"><div class="container">',
	  'after_widget' => '</div></div>',
	  'before_title' => '<h3 class="section-title"><span>',
	  'after_title' => '</span></h3>',
	  'before_sidebar' => '',
	  'after_sidebar' => '',
	  'widget_id' => 'rizhuti_v2_module_lastpost_item-2',
	  'widget_name' => 'RI-首页模块 : 最新文章展示',
	), array (
	  'title' => '最新推荐',
	  'desc' => '最新文章推荐展示，精彩尽在咫尺',
	  'btn_cat' => array (),
	  'item_style' => 'grid',
	  'is_pagination' => '1',
	  'no_cat' => '',
	));

	rizhuti_v2_module_parallax(array (
	  'name' => '首页模块化布局',
	  'id' => 'modules',
	  'description' => '添加首页模块化布局',
	  'class' => '',
	  'before_widget' => '<div id="rizhuti_v2_module_parallax-6" class="section rizhuti_v2-widget-parallax mt-5"><div class="container">',
	  'after_widget' => '</div></div>',
	  'before_title' => '<h3 class="section-title"><span>',
	  'after_title' => '</span></h3>',
	  'before_sidebar' => '',
	  'after_sidebar' => '',
	  'widget_id' => 'rizhuti_v2_module_parallax-6',
	  'widget_name' => 'RI-首页模块 : 视差背景',
	), array (
	  'image' => get_template_directory_uri() . '/assets/img/bg.jpg',
	  'text' => '请在WP后台-外观-小工具-首页模块化布局块中-添加首页模块，即可显示首页内容',
	  'link' => '',
	  'new_tab' => '0',
	  'primary_text' => '<i class="fab fa-wordpress"></i> 文档教程',
	  'primary_link' => 'https://www.kancloud.cn/rizhuti/ripro-v2/2294903',
	  'primary_new_tab' => '1',
	  'secondary_text' => '<i class="fab fa-wordpress"></i> 官网介绍',
	  'secondary_link' => 'https://ritheme.com/',
	  'secondary_new_tab' => '1',
	));
}

get_footer();

?>
