<?php if (!defined('ABSPATH')) {die;} // Cannot access directly.

$prefix = '_prefix_menu_options';

CSF::createNavMenuOptions($prefix, array(
    'data_type' => 'unserialize',
));

CSF::createSection($prefix, array(
    'fields' => array(
        array(
            'id'    => 'menu_icon',
            'type'  => 'icon',
            'title' => '菜单图标',
        ),
        array(
            'id'    => 'is_catmenu',
            'type'  => 'switcher',
            'title' => '启用高级菜单文章',
            'label' => '只在分类或者标签菜单下有效，只支持一级菜单开启',
        ),

    ),
));

unset($prefix);
