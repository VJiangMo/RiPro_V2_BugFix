<?php
/**
 * Template Name: 模块化布局页面
 */

get_header();?>



<?php

if (get_post_meta(get_the_ID(),'hero_single_style',1)!='none') {
	update_post_meta(get_the_ID(),'hero_single_style','none');
}

if (get_post_meta(get_the_ID(),'sidebar_single_style',1)!='none') {
	update_post_meta(get_the_ID(),'sidebar_single_style','none');
}


$custom_modular_pages = _cao('custom_modular_pages', array());
if (!empty($custom_modular_pages)) {
    foreach ($custom_modular_pages as $value) {
        if ($value['page_id'] == get_the_ID()) {
            dynamic_sidebar($value['widget_name']);
            break;
        }
    }
}else{
	echo "请在主题设置-布局设置-配置-新增自定义模块化页面";
}

?>

<?php get_footer();?>