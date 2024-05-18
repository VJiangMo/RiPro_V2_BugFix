<?php if (!defined('ABSPATH')) {die;} // Cannot access directly.

//post
$prefix = 'cat_taxonomy_options';

CSF::createTaxonomyOptions($prefix, array(
    'taxonomy'  => array('post_tag', 'category','series'),
    'data_type' => 'unserialize',
));

CSF::createSection($prefix, array(
    'fields' => array(

        array(
            'id'      => 'bg-image',
            'type'    => 'upload',
            'title'   => '特色图片',
            'desc'    => '用于展示背景图，缩略图',
            'default' => '',
        ),

        array(
            'id'      => 'is_no_archive_filter',
            'type'    => 'switcher',
            'title'   => '关闭筛选',
            'label'   => '关闭当前类目下的高级筛选功能',
            'default' => false,
        ),

        // array(
        //     'id'      => 'is_thumb_px',
        //     'type'    => 'switcher',
        //     'title'   => '自定义分类下文章缩略图宽高',
        //     'label'   => '因前台是自适应布局，具体宽高比例前台刷新观察，这里的宽高是图片裁剪真实宽高,在纯分类页面和首页单独分类模块有效',
        //     'default' => false,
        // ),
        // array(
        //     'id'         => 'thumb_px',
        //     'type'       => 'dimensions',
        //     'title'      => '缩略图宽高',
        //     'default'    => array(
        //         'width'  => '300',
        //         'height' => '200',
        //         'unit'   => 'px',
        //     ),
        //     'dependency' => array('is_thumb_px', '==', 'true'),
        // ),
        
        array(
            'id'          => 'archive_single_style',
            'type'        => 'select',
            'title'       => '侧边栏',
            'placeholder' => '',
            'options'     => array(
                'none'  => '无',
                'right' => '右侧',
                'left'  => '左侧',
            ),
            'default'     => _cao('archive_single_style'),
        ),

        // 分类页布局
        array(
            'id'          => 'archive_item_style',
            'type'        => 'select',
            'title'       => '分类页列表风格',
            'placeholder' => '',
            'options'     => array(
                'list' => '列表',
                'grid' => '网格',
            ),
            'default'     => 'list',
        ),
        array(
            'id'       => 'seo-title',
            'type'     => 'text',
            'title'    => '自定义SEO标题',
            'subtitle' => '为空则不设置',
        ),
        array(
            'id'       => 'seo-keywords',
            'type'     => 'text',
            'title'    => 'SEO关键词',
            'subtitle' => '关键词用英文逗号,隔开',
        ),
        array(
            'id'       => 'seo-description',
            'type'     => 'textarea',
            'title'    => 'SEO描述',
            'subtitle' => '字数控制到80-180最佳',
        ),

    ),
));

unset($prefix);