<?php if (!defined('ABSPATH')) {die;} // Cannot access directly.



/**
 * 按钮小工具
 */
CSF::createWidget('ripro_v2_widget_btns', array(
    'title'       => 'Ri-通用模块：按钮链接',
    'classname'   => 'ripro-v2-widget-btns',
    'description' => 'Ri主题的小工具',
    'fields'      => array(
        array(
            'id'      => 'title',
            'type'    => 'text',
            'title'   => '标题',
            'default' => '外部推荐',
        ),
        array(
            'id'                     => 'btns',
            'type'                   => 'group',
            'title'                  => '按钮-链接',
            'desc'                   => '支持多个',
            'accordion_title_number' => true,
            'fields'                 => array(
                array(
                    'id'      => 'name',
                    'type'    => 'textarea',
                    'title'   => '标题',
                    'default' => '按钮',
                ),
                array(
                    'id'      => 'size',
                    'type'    => 'select',
                    'title'   => '按钮大小',
                    'options' => array(
                        'btn-no' => '常规',
                        'btn-sm' => '小',
                        'btn-lg' => '大',
                    ),
                    'default' => 'btn-sm',
                ),
                array(
                    'id'      => 'type',
                    'type'    => 'select',
                    'title'   => '按钮颜色',
                    'options' => array(
                        'btn-primary'   => '蓝色',
                        'btn-success'   => '绿色',
                        'btn-danger'    => '红色',
                        'btn-warning'   => '黄色',
                        'btn-secondary' => '灰色',
                        'btn-dark'      => '黑色',
                        'btn-light'      => '亮色',
                    ),
                ),
                array(
                    'id'      => 'href',
                    'type'    => 'text',
                    'title'   => '按钮-链接',
                    'default' => '#',
                ),
                
            ),
            'default'                => array(
                array(
                    'type' => 'btn-light',
                    'href' => 'https://ritheme.com/',
                    'name' => 'WordPresss主题推荐',
                    'size' => 'btn-sm',
                ),
                array(
                    'type' => 'btn-light',
                    'href' => 'https://www.aliyun.com/minisite/goods?userCode=u4kxbrjo',
                    'name' => '阿里云服务器推荐',
                    'size' => 'btn-sm',
                ),
                array(
                    'type' => 'btn-light',
                    'href' => '#',
                    'name' => '关于本站',
                    'size' => 'btn-sm',
                ),
                
            ),
        ),

    ),
));
if (!function_exists('ripro_v2_widget_btns')) {
    function ripro_v2_widget_btns($args, $instance) {

        echo $args['before_widget'];
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

        $instance = array_merge( array( 
            'btns' => array(),
        ), $instance);


        // start
        $btns  = $instance['btns'];
        foreach ($btns as $key => $btn) {
            echo '<a target="_blank" class="btn ' . $btn['type'] . ' btn-block ' . $btn['size'] . '" href="' . $btn['href'] . '" rel="nofollow noopener noreferrer">' . $btn['name'] . '</a>';
        }
        // end
        echo $args['after_widget'];
    }
}


/**
 * 网站动态条
 */
CSF::createWidget('rizhuti_v2_module_dynamic', array(
    'title'       => 'RI-首页模块 : 网站动态条',
    'classname'   => 'rizhuti_v2-widget-dynamic',
    'description' => '用户评论 下载 购买 充值 签到 佣金奖励 都会触发动态展示 ',
    'fields'      => array(

        array(
            'id'    => 'title',
            'type'  => 'Text',
            'title' => '标题',
            'default' => '网站动态',
        ),

        array(
            'id'      => 'bg',
            'type'    => 'select',
            'title'   => '背景颜色',
            'options' => array(
                'primary'   => '蓝色',
                'success'   => '绿色',
                'danger'    => '红色',
                'warning'   => '黄色',
                'secondary' => '灰色',
                'dark'      => '黑色',
                // 'light'     => '亮色',
            ),
        ),

        array(
            'id'      => 'is_tongji',
            'type'    => 'switcher',
            'title'   => '是否显示网站统计',
            'default' => true,
        ),
        

    ),
));
if (!function_exists('rizhuti_v2_module_dynamic')) {
    function rizhuti_v2_module_dynamic($args, $instance) {
        if (!is_page_template_modular()) {return false;} //非模块页面不显示

        $instance = array_merge( array( 
            'title' => '网站动态',
            'is_tongji' => true,
            'bg' => 'primary',

        ), $instance);

        $data = RiDynamic::get();
        if (empty($data)) {
            $data = array( 0 => array('info' => esc_html__('暂无动态','ripro-v2'),'time' => time(),'uid' => 0,'href' => '#' ) );
        }

        echo $args['before_widget'];

        ob_start();

        echo '<div class="module owl-dynamic alert-'.$instance['bg'].'">';

        echo '<span class="d-none d-lg-block float-left"><span class="badge badge-danger mr-2"><i class="fa fa-volume-up"></i> '.$instance['title'].'<sup class="spinner-grow spinner-grow-sm ml-1 small" role="status" aria-hidden="true" style=" width: .5rem; height: .5rem; "></sup></span></span>';
        echo '<div class="scroll-dynamic"><ul>';
        foreach ($data as $key => $value) {

            $href = (empty($value['href']) || $value['href']=='#') ? 'javascript:void(0);' : esc_url($value['href']) ;

            if (empty($value['href']) || $value['href']=='#') {
                $href = 'javascript:void(0);';
                $target = '';
            }else{
                $href = esc_url($value['href']);
                $target = ' target="_blank"';
            }

            if ($u_obj = get_user_by('ID', intval($value['uid']))) {
                $u_name =$u_obj->display_name;
            }else{
                $u_name = esc_html__('游客','ripro-v2');
            }

            if (mb_strlen($u_name) > 2) {
                $u_name = ri_substr_cut($u_name);
            } else {
                $u_name = ri_substr_cut($u_name);
            }

            $time  = sprintf( __( '%s前','ripro-v2' ), human_time_diff( $value['time'], time() ) );


            echo '<li class=""><a'.$target.' rel="bookmark" href="'.$href.'"><span>'.$u_name.'</span> <b>'.$value['info'].'</b> <span class="badge badge-secondary-lighten ml-1">'.$time.'</span></a></li>';
        }
        echo '</ul></div>';

        if ( !empty($instance['is_tongji']) ) {
            $info_arr = [
                array('title' => esc_html__('今日发布','ripro-v2'),'value' => ripro_v2_get_today_posts_count() ),
                array('title' => esc_html__('本周','ripro-v2'),'value' => ripro_v2_get_week_post_count() ),
                array('title' => esc_html__('总数','ripro-v2'),'value' => wp_count_posts()->publish ),
            ];
            echo '<span class="float-right d-none d-lg-block">';
            foreach ($info_arr as $value) {
                echo '<small class="mr-2">'.$value['title'].'<span class="badge badge-'.$instance['bg'].'-lighten ml-1">'.$value['value'].'</span></small>';
            }
            echo '</span>';
        }
        

        echo '</div>'; ?>

        <script type="text/javascript">
            jQuery(function() {
                'use strict';
                setInterval('AutoScroll(".scroll-dynamic")', 3000);
            });
        </script>

        <?php echo ob_get_clean();

        echo $args['after_widget'];

    }
}



/**
 * 视差背景
 */
CSF::createWidget('rizhuti_v2_module_parallax', array(
    'title'       => 'RI-首页模块 : 视差背景',
    'classname'   => 'rizhuti_v2-widget-parallax',
    'description' => '炫酷的图片背景搭配文字介绍和按钮',
    'fields'      => array(

        array(
            'id'    => 'image',
            'type'  => 'upload',
            'title' => '背景图',
            'default'     => get_template_directory_uri() . '/assets/img/bg.jpg',
        ),
        array(
            'id'       => 'text',
            'type'     => 'text',
            'title'    => '文字描述介绍',
            'default'  => '文字描述介绍',
            'sanitize' => false,
        ),

        array(
            'id'    => 'link',
            'type'  => 'Text',
            'title' => '主链接',
        ),

        array(
            'id'    => 'new_tab',
            'type'  => 'switcher',
            'title' => '新窗口打开链接？',
        ),

        array(
            'id'      => 'primary_text',
            'type'    => 'Text',
            'title'   => '按钮1文字',
            'default' => '关于我们',
            'sanitize' => false,
        ),

        array(
            'id'    => 'primary_link',
            'type'  => 'Text',
            'title' => '按钮1链接',
        ),

        array(
            'id'    => 'primary_new_tab',
            'type'  => 'switcher',
            'title' => '按钮1新窗口打开链接？',
        ),

        array(
            'id'      => 'secondary_text',
            'type'    => 'Text',
            'title'   => '按钮2文本',
            'default' => '更多介绍',
            'sanitize' => false,
        ),

        array(
            'id'    => 'secondary_link',
            'type'  => 'Text',
            'title' => '按钮2链接',
        ),

        array(
            'id'    => 'secondary_new_tab',
            'type'  => 'switcher',
            'title' => '按钮2新窗口打开链接？',
        ),

    ),
));
if (!function_exists('rizhuti_v2_module_parallax')) {
    function rizhuti_v2_module_parallax($args, $instance) {
        
        if (!is_page_template_modular()) {return false;} //非模块页面不显示

        $instance = array_merge( array( 
            'image' => get_template_directory_uri() . '/assets/img/top-bg.jpg',
            'text' => '文字描述介绍',
        ), $instance);


        echo $args['before_widget'];

        ob_start();?>
        <div class="module parallax">
        <?php if (!empty($instance['image'])): ?>
          <img class="jarallax-img lazyload" data-src="<?php echo esc_url($instance['image']); ?>" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" alt="<?php echo esc_attr($instance['text']); ?>">
        <?php endif;

        if ($instance['text'] != ''): ?>
          <div class="container">
            <h4 class="entry-title"><?php echo $instance['text']; ?></h4>
            <?php if ($instance['primary_text'] != ''): ?>
              <a class="btn btn-warning btn-sm" href="<?php echo esc_url($instance['primary_link']); ?>"<?php echo esc_attr($instance['primary_new_tab'] ? ' target="_blank"' : ''); ?>><?php echo $instance['primary_text']; ?></a>
            <?php endif;?>
            <?php if ($instance['secondary_text'] != ''): ?>
              <a class="btn btn-light btn-sm" href="<?php echo esc_url($instance['secondary_link']); ?>"<?php echo esc_attr($instance['secondary_new_tab'] ? ' target="_blank"' : ''); ?>><?php echo $instance['secondary_text']; ?></a>
            <?php endif;?>
          </div>
        <?php endif;

        if (!empty($instance['link'])): ?>
          <a class="u-permalink" href="<?php echo esc_url($instance['link']); ?>"<?php echo esc_attr($instance['new_tab'] ? ' target="_blank"' : ''); ?>></a>
        <?php endif;?>
        </div> <?php

        echo ob_get_clean();

        echo $args['after_widget'];

    }
}



/**
 * 横条小块介绍模块
 */
CSF::createWidget('rizhuti_v2_module_division', array(
    'title'       => 'RI-首页模块 : 横条小块介绍模块',
    'classname'   => 'rizhuti_v2-widget-division',
    'description' => '配合幻灯片或者搜索模块展示自定义链接',
    'fields'      => array(

        array(
            'id'      => 'is_rounded',
            'type'    => 'switcher',
            'title'   => '是否圆形图标',
            'default' => true,
        ),
        array(
            'id'         => 'div_data',
            'type'       => 'group',
            'title'      => '新建',
            'fields'     => array(
                array(
                    'id'      => 'title',
                    'type'    => 'text',
                    'title'   => '标题文字',
                    'default' => '标题文字',
                ),
                array(
                    'id'         => 'icon',
                    'type'       => 'icon',
                    'title'      => '图标',
                    'desc'       => '设置站内币图标，部分页面展示需要',
                    'default'    => 'fab fa-buffer',
                ),
                array(
                    'id'      => 'desc',
                    'type'    => 'text',
                    'title'   => '描述内容',
                    'default' => '这里是描述内容介绍',
                ),
                array(
                    'id'      => 'link',
                    'type'    => 'text',
                    'title'   => '链接',
                    'desc'   => '不填写则不启用链接',
                    'default' => '',
                ),

            ),
        ),

    ),
));
if (!function_exists('rizhuti_v2_module_division')) {
    function rizhuti_v2_module_division($args, $instance) {
        if (!is_page_template_modular()) {return false;} //非模块页面不显示

        $instance = array_merge( array( 
            'div_data' => array(),
            'is_rounded' => true,
        ), $instance);
        
        echo $args['before_widget'];

        $col_classes = ( count($instance['div_data'])>3 ) ? 'col-xl-3' : 'col-xl-4' ;
        $rounded_classes = ( empty($instance['is_rounded']) ) ? '' : ' rounded-circle' ;
        ob_start();?>

        <div class="module division">
            <div class="container">
                <div class="row align-items-center no-gutters">
        
                <?php foreach ( $instance['div_data'] as $item ){ ?>
                   <div class="<?php echo $col_classes;?> col-lg-4 col-6 mb-4">
                    <?php if ( !empty($item['link']) ) { echo '<a href="'.$item['link'].'" title="'.$item['title'].'">'; }?>
                      <div class="d-flex align-items-center hover-rounded">
                        <span class="icon-sahpe text-center<?php echo $rounded_classes;?>"> <i class="<?php echo $item['icon'];?>"> </i></span>
                        <div class="ml-3 overflow">
                          <h4 class="title mb-0"><?php echo $item['title'];?></h4>
                          <p class="desc mb-0"><?php echo $item['desc'];?></p>
                        </div>
                      </div>
                    <?php if ( !empty($item['link']) ) { echo '</a>'; }?>
                    </div>
                <?php } ?>

                </div>
            </div>
        </div>

    <?php echo ob_get_clean(); echo $args['after_widget'];

    }
}




/**
 * 首页搜索模块+背景图片 视频
 */
CSF::createWidget('rizhuti_v2_module_search', array(
    'title'       => 'RI-首页模块 : 高级搜索模块',
    'classname'   => 'rizhuti_v2-widget-search-bg',
    'description' => '高级搜索模块，分类搜索',
    'fields'      => array(

        
        array(
            'id'       => 'title',
            'type'     => 'text',
            'title'    => '搜索介绍标题',
            'default'  => '这是一个优雅的搜索框',
        ),
        array(
            'id'       => 'desc',
            'type'     => 'text',
            'title'    => '搜索描述介绍',
            'default'  => '支持图片背景，视频背景，支持分类高级筛选搜索，支持自定义搜索热门词展示',
        ),

        array(
            'id'       => 'placeholder',
            'type'     => 'text',
            'title'    => '输入框提示文字',
            'default'  => '优质内容等你来搜',
        ),

        array(
            'id'          => 'bg_type',
            'type'        => 'radio',
            'title'       => '背景类型',
            'placeholder' => '',
            'options'     => array(
                'img' => '图片背景',
                'video' => 'MP4视频背景',
            ),
            'inline'     => true,
            'default'     => 'img',
        ),

        array(
            'id'    => 'bg',
            'type'  => 'upload',
            'title' => '背景图或背景视频',
            'default'     => get_template_directory_uri() . '/assets/img/bg.jpg',
        ),

        array(
            'id'    => 'is_cat',
            'type'  => 'switcher',
            'title' => '高级分类搜索',
            'default' => true,
        ),

        // array(
        //     'id'          => 'cats_id',
        //     'type'        => 'select',
        //     'title'       => '要展示的分类',
        //     'desc'        => '按顺序选择可以排序',
        //     'placeholder' => '选择分类',
        //     'inline'      => true,
        //     'chosen'      => true,
        //     'multiple'    => true,
        //     'options'     => 'categories',
        //     'dependency' => array('is_cat', '==', 'true'),
        // ),

        array(
            'id'    => 'search_hot',
            'type'  => 'textarea',
            'title' => '搜索热词',
            'desc' => '每个搜索词用英文逗号隔开',
            'default' => 'wordpress,sss,测试,下载,素材,作品,主题,插件,你好',
        ),

    ),
));
if (!function_exists('rizhuti_v2_module_search')) {
    function rizhuti_v2_module_search($args, $instance) {

        if (!is_page_template_modular()) {return false;} //非模块页面不显示

        $instance = array_merge( array( 
            'title' => '搜索介绍标题',
            'desc' => '文字描述介绍',
            'bg_type' => 'img', //video
            'is_cat' => true,
            'cats_id' => array(),
        ), $instance);

        if (empty($instance['bg_type']) || $instance['bg_type']=='img') {
            $data_type = 'data-bg="'.$instance['bg'].'"';
        }else{
            $data_type = 'data-jarallax-video="mp4:'.$instance['bg'].'"';
        }

        if (wp_is_mobile() && $instance['bg_type'] == 'video') {
            $data_type = 'data-bg="'.get_template_directory_uri() .'/assets/img/bg.jpg"';
        }

        echo $args['before_widget'];


        ob_start();?>
        <div class="module lazyload search-bg jarallax-sarch <?php echo $instance['bg_type'];?>" <?php echo $data_type;?>>
            <div class="search-bg-overlay"></div>
            <div class="container">
                <h2 class="search-title"><?php echo $instance['title'];?></h2>
                <p class="search-desc"><?php echo $instance['desc'];?></p>
                
                
                <div class="search-form">
                    <form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <?php if (!empty($instance['is_cat'])) : ?>
                        <div class="search-select">
                        <?php 
                        
                        wp_dropdown_categories( array(
                            'hide_empty'       => true,
                            'show_option_none' => esc_html__('全站','ripro-v2'),
                            'option_none_value' => '',
                            'orderby'          => 'name',
                            'hierarchical'     => true,
                            'depth'     => 1,
                            'id'     => 'modulesearch-cat',
                            'class'     => 'selectpicker',
                        ) );?>
                        </div>
                        <?php endif; ?>

                        <div class="search-fields<?php echo empty($instance['is_cat']) ? ' radius-30' : '';?>">
                          <input type="text" class="" placeholder="<?php echo $instance['placeholder'];?>" autocomplete="off" value="<?php echo esc_attr( get_search_query() ) ?>" name="s" required="required">
                          <button class="" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </form>
                </div>

                <?php if ( !empty($instance['search_hot']) ) {
                    echo '<div class="popula-search-key">';
                    echo '<span>'.esc_html__('热门搜索：','ripro-v2').'</span>';
                    $item_exp = explode(",", trim($instance['search_hot']));
                    foreach ($item_exp as $v) {
                        echo '<a href="'.get_search_link($v).'">'.$v.'</a>，';
                    }
                    echo '</div>';

                }?>
            </div>
        </div>

        <?php
        echo ob_get_clean();

        echo $args['after_widget'];

    }
}


/**
 * 自定义幻灯片全屏
 */
CSF::createWidget('rizhuti_v2_module_slideer_img', array(
    'title'       => 'RI-首页模块 : 全屏图片幻灯片',
    'classname'   => 'rizhuti_v2-widget-slideer-img',
    'description' => '图片幻灯片，支持全宽/普通',
    'fields'      => array(

        array(
            'id'      => 'style',
            'type'    => 'radio',
            'title'   => '布局风格',
            'inline'  => true,
            'options' => array(
                'full' => '全宽',
                'big'  => '普通',
            ),
            'default' => 'full',
        ),
        array(
            'id'     => 'diy_data',
            'type'   => 'group',
            'title'  => '主幻灯片',
            'fields' => array(
                array(
                    'id'          => '_img',
                    'type'        => 'upload',
                    'title'       => '上传幻灯片',
                    'default'     => get_template_directory_uri() . '/assets/img/bg.jpg',
                ),
                array(
                    'id'      => '_blank',
                    'type'    => 'switcher',
                    'title'   => '新窗口打开链接',
                    'default' => true,
                ),
                array(
                    'id'      => '_href',
                    'type'    => 'text',
                    'title'   => '链接地址',
                    'default' => '',
                ),
                array(
                    'id'      => '_desc',
                    'type'    => 'textarea',
                    'title'   => '描述内容，支持html代码',
                    'sanitize' => false,
                    'default' => '<h3 class="text-white">Hello, RiPro-V2</h3><p class="lead  text-white d-none d-lg-block">这是一个简单的内容展示，支持 <span class="badge badge-light">BootstrapV4</span> 的所有代码，您可以随意插入HTML代码任意组合显示。',
                ),

            ),
            
        ),
        array(
            'id'    => 'autoplay',
            'type'  => 'switcher',
            'title' => '自动播放',
        ),
    ),
));
if (!function_exists('rizhuti_v2_module_slideer_img')) {
    function rizhuti_v2_module_slideer_img($args, $instance) {
        if (!is_page_template_modular()) {return false;} //非模块页面不显示
        $instance = array_merge( array( 
            'diy_data' => array(),
        ), $instance);

        echo $args['before_widget'];
        $style    = isset($instance['style']) ? $instance['style'] : 'full'; //big
        $autoplay = !empty($instance['autoplay']) ? ' autoplay' : ''; //autoplay
        ob_start(); ?>
        <?php if ($style == 'big') {echo '<div class="container mt-4 big">';}?>
        <div class="module slider img-center owl<?php echo $autoplay;?>">
        <?php foreach ($instance['diy_data'] as $item) {
            echo '<div class="slider lazyload visible" data-bg="'.esc_url( $item['_img'] ).'">';
            echo '<div class="container">';
            echo $item['_desc'];
            if (!empty($item['_href'])) {
              echo '<a'.( $item['_blank'] ? ' target="_blank"' : '' ).' class="u-permalink" href="'.esc_url( $item['_href'] ).'"></a>';
            }
            echo '</div>';
            echo '</div>';
        }?>
        </div>
        <?php if ($style == 'big') {echo '</div>';}?>
        <?php echo ob_get_clean(); echo $args['after_widget'];

    }
}



/**
 * 文章幻灯片NEW
 */
CSF::createWidget('rizhuti_v2_module_slideer_post', array(
    'title'       => 'RI-首页模块 : 文章幻灯片',
    'classname'   => 'rizhuti_v2-widget-slideer-center',
    'description' => '文章幻灯片，支持全宽/普通',
    'fields'      => array(

        array(
            'id'      => 'style',
            'type'    => 'radio',
            'title'   => '布局风格',
            'inline'  => true,
            'options' => array(
                'full' => '全宽',
                'big'  => '普通',
            ),
            'default' => 'full',
        ),
        array(
            'id'      => 'count',
            'type'    => 'text',
            'title'   => '文章数量',
            'default' => 4,
        ),
        array(
            'id'      => 'offset',
            'type'    => 'text',
            'title'   => '第几页',
            'default' => 0,
        ),
        array(
            'id'          => 'category',
            'type'        => 'select',
            'title'       => '只显示分类下',
            'placeholder' => '选择分类',
            'options'     => 'categories',
        ),
        array(
            'id'      => 'orderby',
            'type'    => 'select',
            'title'   => '排序方式',
            'options' => array(
                'date'          => '日期',
                'rand'          => '随机',
                'comment_count' => '评论数',
                'modified'      => '最近编辑时间',
                'title'         => '标题',
                'ID'            => '文章ID',
            ),
        ),
       
        array(
            'id'    => 'autoplay',
            'type'  => 'switcher',
            'title' => '自动播放',
        ),

    ),
));
if (!function_exists('rizhuti_v2_module_slideer_post')) {
    function rizhuti_v2_module_slideer_post($args, $instance) {
        if (!is_page_template_modular()) {return false;} //非模块页面不显示
        $instance = array_merge( array( 
            'style' => 'full',
            'count' => 4,
            'offset' => 0,
            'category' => 0,
            'orderby' => 'date',
            'autoplay' => true,
        ), $instance);

        echo $args['before_widget'];
        // 查询
        $_args = array(
            'cat'                 => (int)$instance['category'],
            'ignore_sticky_posts' => true,
            'post_status'         => 'publish',
            'posts_per_page'      => (int)$instance['count'],
            'paged'              => (int)$instance['offset'],
            'orderby'             => $instance['orderby'],
        );
        $PostData = new WP_Query($_args);
        $style    = isset($instance['style']) ? $instance['style'] : 'full'; //big
        $autoplay = !empty($instance['autoplay']) ? ' autoplay' : ''; //big
        if ($style == 'full') {
            $classes = 'module slider center owl' . $autoplay;
        } else {
            $classes = 'module slider big owl nav-white' . $autoplay;
        }
        ob_start(); ?>
      <?php if ($style == 'big') {echo '<div class="container mt-4">';}?>
      <div class="<?php echo esc_attr($classes); ?>">
        <?php while ($PostData->have_posts()): $PostData->the_post();
            $bg_image = _get_post_thumbnail_url(null, 'full');?>
            <article <?php post_class('post lazyload visible');?> data-bg="<?php echo esc_url($bg_image); ?>">
              <div class="entry-wrapper">
                <header class="entry-header white">
                  <?php rizhuti_v2_entry_title(array('link' => false));?>
                </header>
                <div class="entry-footer">
                  <?php rizhuti_v2_entry_meta(array('category' => true, 'author' => true, 'comment' => true, 'date' => true, 'favnum' => true, 'views' => true, 'shop' => true));?>
                </div>
              </div>
              <a class="u-permalink" href="<?php echo esc_url(get_permalink()); ?>"></a>
            </article>
          <?php endwhile;?>
      </div>
      <?php if ($style == 'big') {echo '</div>';}?>

      <?php wp_reset_postdata(); echo ob_get_clean(); echo $args['after_widget'];

    }
}



/**
 * 文章滑块
 */
CSF::createWidget('rizhuti_v2_module_post_carousel', array(
    'title'       => 'RI-首页模块 : 文章滑块',
    'classname'   => 'rizhuti_v2-widget-post-carousel',
    'description' => '文章左右滑块',
    'fields'      => array(

        array(
            'id'    => 'title',
            'type'  => 'text',
            'title' => '标题',
        ),
        array(
            'id'    => 'desc',
            'type'  => 'text',
            'title' => '描述介绍内容',
        ),
        array(
            'id'      => 'count',
            'type'    => 'text',
            'title'   => '文章数量',
            'default' => 8,
        ),
        array(
            'id'      => 'is_excerpt',
            'type'    => 'switcher',
            'title'   => '显示摘要',
            'default' => true,
        ),
        array(
            'id'      => 'offset',
            'type'    => 'text',
            'title'   => '第几页',
            'default' => 0,
        ),
        array(
            'id'          => 'category',
            'type'        => 'select',
            'title'       => '只显示分类下',
            'placeholder' => '选择分类',
            'options'     => 'categories',
        ),
        array(
            'id'      => 'orderby',
            'type'    => 'select',
            'title'   => '排序方式',
            'options' => array(
                'date'          => '日期',
                'rand'          => '随机',
                'comment_count' => '评论数',
                'views'         => '阅读量',
                'modified'      => '最近编辑时间',
                'title'         => '标题',
                'ID'            => '文章ID',
            ),
        ),
        
        array(
            'id'    => 'autoplay',
            'type'  => 'switcher',
            'title' => '自动播放',
        ),

    ),
));
if (!function_exists('rizhuti_v2_module_post_carousel')) {
    function rizhuti_v2_module_post_carousel($args, $instance) {
        if (!is_page_template_modular()) {return false;} //非模块页面不显示

        $instance = array_merge( array( 
            'is_excerpt' => true,
            'desc' => '描述介绍内容',
            'count' => 8,
            'offset' => 0,
            'category' => 0,
            'orderby' => 'date',
            'autoplay' => true,
        ), $instance);


        echo $args['before_widget'];

        // 查询
        $_args = array(
            'cat'                 => (int)$instance['category'],
            'ignore_sticky_posts' => true,
            'post_status'         => 'publish',
            'posts_per_page'      => (int)$instance['count'],
            'paged'              => (int)$instance['offset'],
            'orderby'             => $instance['orderby'],
        );
        if ($instance['orderby']=='views') {
            $_args['meta_key'] = 'views';
            $_args['orderby'] = 'meta_value_num';
            $_args['order'] = 'DESC';
        }

        $autoplay = !empty($instance['autoplay']) ? ' autoplay' : '';
        $PostData = new WP_Query($_args);

        ob_start();?>
        <?php if (!empty($instance['title'])) : ?>
        <div class="row">
            <div class="col-lg col-sm-12">
                <h3 class="section-title d-flex align-items-center mb-lg-4 mb-2"><?php echo $instance['title']; ?><small><?php echo $instance['desc']; ?></small></h3>
            </div>
        </div>
        <?php endif;?>
        <div class="module carousel owl<?php echo $autoplay;?>">
        <?php while ($PostData->have_posts()): $PostData->the_post();?>
            <article id="post-<?php the_ID();?>" <?php post_class('post post-grid');?>>

              <?php if (_cao('is_post_grid_type',true)) {
                    echo get_post_type_icon();
              }?>
              <?php echo _get_post_media(null, 'thumbnail'); ?>
              <div class="entry-wrapper">
                <?php if (_cao('is_post_grid_category',1)) {
                    ripro_v2_category_dot(2);
                }?>
                <header class="entry-header">
                  <?php rizhuti_v2_entry_title(array('link' => true));?>
                </header>
                <?php if (!empty($instance['is_excerpt'])): ?>
                <div class="entry-excerpt"><?php echo ripro_v2_excerpt(); ?></div>
                <?php endif;?>
              <div class="entry-footer">
                <?php rizhuti_v2_entry_meta(array( 
                    'author' => _cao('is_post_grid_author',1), 
                    'category' => false,
                    'comment' => _cao('is_post_grid_comment',1),
                    'date' => _cao('is_post_grid_date',1),
                    'favnum' => _cao('is_post_grid_favnum',1),
                    'views' => _cao('is_post_grid_views',1),
                    'shop' => _cao('is_post_grid_shop',1),
                ));?>
              </div>
            </div>
        </article>
        <?php endwhile;?>
        </div>
        <?php wp_reset_postdata(); echo ob_get_clean(); echo $args['after_widget'];
    }
}




/**
 * 分类展示滑块 catbox
 */
CSF::createWidget('rizhuti_v2_module_catbox_carousel', array(
    'title'       => 'RI-首页模块 : 分类BOX滑块',
    'classname'   => 'rizhuti_v2-widget-catbox-carousel',
    'description' => '分类BOX滑块展示分类背景图和链接名称',
    'fields'      => array(

        array(
            'id'    => 'title',
            'type'  => 'text',
            'title' => '标题',
        ),
        array(
            'id'    => 'desc',
            'type'  => 'text',
            'title' => '描述介绍内容',
        ),
        
        array(
            'id'          => 'category',
            'type'        => 'select',
            'title'       => '要展示的分类',
            'desc'        => '按顺序选择可以排序',
            'placeholder' => '选择分类',
            'inline'      => true,
            'chosen'      => true,
            'multiple'    => true,
            'options'     => 'categories',
        ),

        array(
            'id'    => 'is_post_num',
            'type'  => 'switcher',
            'title' => '显示文章数量',
        ),

        array(
            'id'    => 'autoplay',
            'type'  => 'switcher',
            'title' => '自动播放',
        ),

    ),
));
if (!function_exists('rizhuti_v2_module_catbox_carousel')) {
    function rizhuti_v2_module_catbox_carousel($args, $instance) {
        if (!is_page_template_modular() || empty($instance['category']) ) {return false;} //非模块页面不显示

        $instance = array_merge( array( 
            'title' => '分类BOX滑块',
            'desc' => '描述介绍内容',
            'category' => 0,
            'autoplay' => true,
            'is_post_num' => true,
        ), $instance);


        echo $args['before_widget'];

        
        $autoplay = !empty($instance['autoplay']) ? ' autoplay' : '';

        ob_start();?>
        
        <?php if (!empty($instance['title'])) : ?>
        <div class="row">
            <div class="col-lg col-sm-12">
                <h3 class="section-title d-flex align-items-center mb-lg-4 mb-2"><?php echo $instance['title']; ?><small><?php echo $instance['desc']; ?></small></h3>
            </div>
        </div>
        <?php endif;?>
        <div class="module catbox-carousel owl<?php echo $autoplay;?>">
        <?php foreach ( (array)$instance['category'] as $key => $cat_id ): 
            $category = get_term($cat_id,'category');
            
            if (empty($category)) {
                continue;
            }

            $bg_img = get_term_meta($category->term_id, 'bg-image', true);
            $bg_img = (!empty($bg_img)) ? $bg_img : get_template_directory_uri().'/assets/img/series-bg.jpg';
            $badge = array('success','info','warning','danger','light','primary','warning','danger','success','info','warning','light','primary',);
        ?>
            <div class="lazyload visible catbox-bg" data-bg="<?php echo esc_url($bg_img); ?>">
                <a href="<?php echo get_category_link($category->term_id); ?>" class="catbox-block">
                    <div class="catbox-content">
                    <?php if (!empty($instance['is_post_num'])) : ?>
                    <span class="badge badge-<?php echo $badge[$key];?>-lighten mb-1"><?php echo esc_html__('文章','ripro-v2');?> <?php echo $category->count;?>+</span>
                    <?php endif; ?>
                    <h3 class="catbox-title"><?php echo $category->name;?></h3>
                    </div>
                </a>
            </div>
        <?php endforeach;?>
        </div>

        <?php wp_reset_postdata(); echo ob_get_clean(); echo $args['after_widget'];
    }
}



/**
 * 最新文章展示
 */
CSF::createWidget('rizhuti_v2_module_lastpost_item', array(
    'title'       => 'RI-首页模块 : 最新文章展示',
    'classname'   => 'rizhuti_v2-widget-lastpost',
    'description' => '最新文章展示',
    'fields'      => array(

        array(
            'id'    => 'title',
            'type'  => 'text',
            'title' => '标题',
            'default' => '最新推荐',
        ),

        array(
            'id'    => 'desc',
            'type'  => 'text',
            'title' => '描述介绍',
            'default' => '最新文章推荐展示，精彩尽在咫尺',
        ),
        
        array(
            'id'          => 'btn_cat',
            'type'        => 'select',
            'title'       => '要展示分类按钮',
            'desc'       => '按钮为无刷新切换文章，此功能刷新出来的文章不支持加载更多按钮，支持传统分页按钮',
            'placeholder' => '选择要展示的分类',
            'chosen'      => true,
            'multiple'    => true,
            'options'     => 'categories',
        ),
        

        // 布局
        array(
            'id'          => 'item_style',
            'type'        => 'select',
            'title'       => '布局风格',
            'placeholder' => '',
            'options'     => array(
                'list' => '列表',
                'grid' => '网格',
            ),
            'default'     => 'grid',
        ),
        array(
            'id'          => 'no_cat',
            'type'        => 'select',
            'title'       => '要排除的分类',
            'placeholder' => '选择要排除的分类',
            'chosen'      => true,
            'multiple'    => true,
            'options'     => 'categories',
        ),
        array(
            'id'    => 'is_pagination',
            'type'  => 'switcher',
            'title' => '显示翻页按钮',
            'default' => true,
        ),

        array(
            'type'    => 'subheading',
            'content' => '文章数请在 WP后台-设置-阅读-博客页面至多显示调整',
        ),



    ),
));
if (!function_exists('rizhuti_v2_module_lastpost_item')) {
    function rizhuti_v2_module_lastpost_item($args, $instance) {
        if (!is_page_template_modular()) {return false;} //非模块页面不显示

        $instance = array_merge( array( 
            'title' => '最新推荐',
            'desc' => '最新文章推荐展示，精彩尽在咫尺',
            'item_style' => 'grid',
            'btn_cat' => array(),
            'no_cat' => array(),
            'is_ignore' => true,
            'is_pagination' => true,
        ), $instance);


        echo $args['before_widget'];


        // 查询
        $_args = array(
            'paged' => get_query_var('paged', 1),
            'ignore_sticky_posts' => true,
            'post_status'         => 'publish',
            'category__not_in'    => $instance['no_cat'],
        );


        $PostData = new WP_Query($_args);

        ob_start();?>

        <div class="row">
            <div class="col-lg col-sm-12">
                <h3 class="section-title d-flex align-items-center mb-lg-4 mb-2"><?php echo $instance['title']; ?><small><?php echo $instance['desc']; ?></small></h3>
            </div>
            <div class="col-lg-auto col-sm-12 home-cat-nav">
            <?php 
                echo '<a class="btn btn-sm btn-white active mr-2 mb-lg-4 mb-2 lastpost" href="javascript:void(0)">' . esc_html__('最新','ripro-v2') . '</a>';
                foreach ($instance['btn_cat'] as $_cid) {
                    $item = get_term($_cid, 'category');
                    if (!$item) { continue; }
                    echo '<a target="_blank" class="btn btn-sm btn-white mr-2 mb-lg-4 mb-2" href="' . get_category_link($_cid) . '" data-cat="'.$_cid.'" data-paged="'.$_args['paged'].'" data-layout="'.$instance['item_style'].'">' . $item->name . '</a>';
                }
            ?>
            </div>
        </div>

        <div class="module posts-wrapper <?php echo esc_attr($instance['item_style']); ?>">
            <div class="row posts-wrapper scroll">
                <?php if ( $PostData->have_posts() ) : 
                    /* Start the Loop */
                    while ( $PostData->have_posts() ) : $PostData->the_post();
                        get_template_part( 'template-parts/loop/item', $instance['item_style']);
                    endwhile;
                else :
                    get_template_part( 'template-parts/loop/item', 'none' );

                endif;?>
            </div>
            <?php if (!empty($instance['is_pagination'])) {ripro_v2_pagination(5);}?>
        </div>
      <?php wp_reset_postdata(); echo ob_get_clean(); echo $args['after_widget'];
    }
}


/**
 * 分类文章展示
 */
CSF::createWidget('rizhuti_v2_module_post_item', array(
    'title'       => 'RI-首页模块 : 分类文章展示',
    'classname'   => 'rizhuti_v2-widget-catpost',
    'description' => '分类文章展示聚合',
    'fields'      => array(

        array(
            'id'    => 'title',
            'type'  => 'text',
            'title' => '标题',
        ),
        array(
            'id'    => 'desc',
            'type'  => 'text',
            'title' => '描述',
        ),
        array(
            'id'          => 'category',
            'type'        => 'select',
            'title'       => '只显示分类下',
            'placeholder' => '选择分类',
            'options'     => 'categories',
        ),

        array(
            'id'      => 'is_child_cat_ajax',
            'type'    => 'switcher',
            'title'   => '显示子分类文章导航按钮',
            'default' => true,
        ),

        array(
            'id'      => 'orderby',
            'type'    => 'select',
            'title'   => '排序方式',
            'options' => array(
                'date'          => '日期',
                'rand'          => '随机',
                'comment_count' => '评论数',
                'views'         => '阅读量',
                'modified'      => '最近编辑时间',
                'title'         => '标题',
                'ID'            => '文章ID',
            ),
        ),

        // 分类页布局
        array(
            'id'          => 'item_style',
            'type'        => 'select',
            'title'       => '布局风格',
            'placeholder' => '',
            'options'     => array(
                'list' => '列表',
                'grid' => '网格',
            ),
            'default'     => 'list',
        ),
        array(
            'id'      => 'count',
            'type'    => 'text',
            'title'   => '文章数量',
            'default' => 4,
        ),
        array(
            'id'      => 'offset',
            'type'    => 'text',
            'title'   => '第几页',
            'default' => 0,
        ),

    ),
));
if (!function_exists('rizhuti_v2_module_post_item')) {
    function rizhuti_v2_module_post_item($args, $instance) {
        if (!is_page_template_modular()) {return false;} //非模块页面不显示

        $instance = array_merge( array( 
            'title' => '分类文章展示',
            'desc' => '分类文章展示DESC',
            'item_style' => 'list',
            'count' => 4,
            'offset' => 0,
            'category' => 0,
            'is_child_cat_ajax' => true,
            'orderby' => 'date',
        ), $instance);


        echo $args['before_widget'];


        // 查询
        $_args = array(
            'cat'                 => (int)$instance['category'],
            'ignore_sticky_posts' => true,
            'post_status'         => 'publish',
            'posts_per_page'      => (int)$instance['count'],
            'paged'              => (int)$instance['offset'],
            'orderby'             => $instance['orderby'],
        );

        if ($instance['orderby']=='views') {
            $_args['meta_key'] = 'views';
            $_args['orderby'] = 'meta_value_num';
            $_args['order'] = 'DESC';
        }
        

        $PostData = new WP_Query($_args);
        $col_classes = ($instance['item_style'] == 'list') ? 'col-lg-6 col-12' : 'col-lg-5ths col-lg-3 col-md-4 col-6';
        ob_start();?>

        <?php if (!empty($instance['title'])) : ?>
        <div class="row">
            <div class="col-lg col-sm-12">
                <h3 class="section-title d-flex align-items-center mb-lg-4 mb-2"><?php echo $instance['title']; ?><small><?php echo $instance['desc']; ?></small></h3>
            </div>
            <div class="col-lg-auto col-sm-12">
            <?php 
            if (!empty($instance['is_child_cat_ajax']) && !empty($instance['category'])) {

                $child_categories = get_terms('category', array('hide_empty' => 0, 'parent' => $instance['category']));
                if (!empty($child_categories)) {
                  echo '<a target="_blank" class="btn btn-sm btn-white mr-2 mb-lg-4 mb-2 active" href="' . get_category_link($instance['category']) . '">' . esc_html__('全部','ripro-v2') . '</a>';
                  foreach ($child_categories as $item) {
                      echo '<a target="_blank" class="btn btn-sm mb-2 btn-white mr-2 mb-lg-4 mb-2" href="' . get_category_link($item->term_id) . '">' . $item->name . '</a>';
                  }
                }
            }
            ?>
            </div>
        </div>
        <?php endif;?>


        <div class="module posts-wrapper <?php echo esc_attr($instance['item_style']); ?>">
            <div class="row">
              <?php if ( have_posts() ) : 
                    /* Start the Loop */
                    while ( $PostData->have_posts() ) : $PostData->the_post();
                        get_template_part( 'template-parts/loop/item', $instance['item_style']);
                    endwhile;
                else :
                    get_template_part( 'template-parts/loop/item', 'none' );

                endif;?>
            </div>
            

        </div>
      <?php wp_reset_postdata(); echo ob_get_clean(); echo $args['after_widget'];
    }
}


/**
 * 分类CMS文章展示块
 */
CSF::createWidget('rizhuti_v2_module_cms_post', array(
    'title'       => 'RI-首页模块 : 分类CMS文章展示块',
    'classname'   => 'rizhuti_v2-widget-post-cms',
    'description' => '分类CMS文章展示块',
    'fields'      => array(

        array(
            'id'    => 'title',
            'type'  => 'text',
            'title' => '标题',
        ),
        array(
            'id'    => 'desc',
            'type'  => 'text',
            'title' => '描述介绍',
            'default' => '文章推荐展示，精彩尽在咫尺',
        ),
        array(
            'id'      => 'style',
            'type'    => 'select',
            'title'   => '布局风格',
            'options' => array(
                'list'          => '左大图-右列表',
                'grid'          => '左大图-右网格',
            ),
            'default' => 'list',
        ),
        array(
            'id'      => 'is_box_right',
            'type'    => 'switcher',
            'title'   => '大图右侧显示',
            'default' => false,
        ),
       
        array(
            'id'          => 'category',
            'type'        => 'select',
            'title'       => '只显示分类下',
            'placeholder' => '选择分类',
            'options'     => 'categories',
        ),
        array(
            'id'      => 'orderby',
            'type'    => 'select',
            'title'   => '排序方式',
            'options' => array(
                'date'          => '日期',
                'rand'          => '随机',
                'comment_count' => '评论数',
                'views'         => '阅读量',
                'modified'      => '最近编辑时间',
                'title'         => '标题',
                'ID'            => '文章ID',
            ),
        ),
       
        array(
            'id'      => 'offset',
            'type'    => 'text',
            'title'   => '第几页',
            'default' => 0,
        ),

    ),
));
if (!function_exists('rizhuti_v2_module_cms_post')) {
    function rizhuti_v2_module_cms_post($args, $instance) {
        if (!is_page_template_modular()) {return false;} //非模块页面不显示

        $instance = array_merge( array( 
            'title' => '分类CMS文章展示块',
            'desc' => '分类CMS文章展示块',
            'style' => 'list',
            'is_box_right' => false,
            'offset' => 0,
            'category' => 0,
            'orderby' => 'date',
        ), $instance);


        echo $args['before_widget'];

        // 查询
        $_args = array(
            'cat'                 => (int)$instance['category'],
            'ignore_sticky_posts' => true,
            'post_status'         => 'publish',
            'posts_per_page'      => 5,
            'paged'              => (int)$instance['offset'],
            'orderby'             => $instance['orderby'],
        );
        
        if ($instance['orderby']=='views') {
            $_args['meta_key'] = 'views';
            $_args['orderby'] = 'meta_value_num';
            $_args['order'] = 'DESC';
        }

        $PostData = new WP_Query($_args);
        $i = 0;
        $style = $instance['style'];
        $style_order = (!empty($instance['is_box_right'])) ? ' order-first' : '' ;

        ob_start();?>
        
        <?php if (!empty($instance['title'])) : ?>
        <div class="row">
            <div class="col-lg col-sm-12">
                <h3 class="section-title d-flex align-items-center mb-lg-4 mb-2"><?php echo $instance['title']; ?><small><?php echo $instance['desc']; ?></small></h3>
            </div>
        </div>
        <?php endif;?>

        <div class="module posts-wrapper post-cms">
            <div class="row">
              <?php while ($PostData->have_posts()): $PostData->the_post();
                $i++;?>
                  <?php if ($i == 1): ?>
                    <div class="col-lg-6 col-sm-12">
                      <div class="cms_grid_box lazyload" data-bg="<?php echo _get_post_thumbnail_url(null, 'full'); ?>">
                        <a class="u-permalink" href="<?php the_permalink();?>" rel="nofollow noopener noreferrer"></a>
                        <article id="post-<?php the_ID();?>" <?php post_class('post');?>>
                            <div class="entry-wrapper">
                              <header class="entry-header">
                                <?php rizhuti_v2_entry_title(array('link' => true));?>
                              </header>
                              <div class="entry-footer">
                                <?php rizhuti_v2_entry_meta(array('category' => true, 'author' => true, 'views' => true, 'date' => true));?>
                              </div>
                            </div>
                        </article>
                      </div>
                    </div>

                    <div class="col-lg-6 col-sm-12<?php echo esc_attr($style_order);?>">
                      <div class="cms_grid_list">
                        <?php if ($style=='grid'){echo '<div class="row">';} ?>
                  <?php else: ?>
                        <?php if ($style=='grid'){echo '<div class="col-6">';} ?>
                        <article id="post-<?php the_ID();?>" <?php post_class('post post-'.$style);?>>
                            <?php if ($style=='grid'){echo '<a class="u-permalink" href="'.get_permalink().'" rel="nofollow noopener noreferrer"></a>';} ?>
                            
                            <?php echo _get_post_media(null, 'thumbnail'); ?>
                            <div class="entry-wrapper">
                              <header class="entry-header">
                                <?php rizhuti_v2_entry_title(array('link' => true));?>
                              </header>
                              <div class="entry-footer">
                                <?php rizhuti_v2_entry_meta(array( 
                                    'author' => _cao('is_post_'.$style.'_author',1), 
                                    'category' => _cao('is_post_'.$style.'_category',1),
                                    'comment' => _cao('is_post_'.$style.'_comment',1),
                                    'date' => _cao('is_post_'.$style.'_date',1),
                                    'favnum' => _cao('is_post_'.$style.'_favnum',1),
                                    'views' => _cao('is_post_'.$style.'views',1),
                                    'shop' => _cao('is_post_'.$style.'_shop',1),
                                ));?>
                              </div>
                            </div>
                        </article>
                        <?php if ($style=='grid'){echo '</div>';} ?>
                <?php endif;?>
              <?php endwhile;?></div><?php if ($style='grid'){echo '</div>';} ?>

            </div>
        </div>
    <?php wp_reset_postdata(); echo ob_get_clean(); echo $args['after_widget'];

    }
}


/**
 * 分类图片背景文章展示块
 */
CSF::createWidget('ripro_v2_module_cms_post_img', array(
    'title'       => 'RI-首页模块 : 分类图片背景文章展示块',
    'classname'   => 'ripro_v2-widget-post-img',
    'description' => '大图背景文章展示块',
    'fields'      => array(

        array(
            'id'    => 'title',
            'type'  => 'text',
            'title' => '标题',
        ),
        array(
            'id'    => 'desc',
            'type'  => 'text',
            'title' => '描述介绍内容',
        ),
        array(
            'id'          => 'category',
            'type'        => 'select',
            'title'       => '只显示分类下',
            'placeholder' => '选择分类',
            'options'     => 'categories',
        ),
        array(
            'id'      => 'orderby',
            'type'    => 'select',
            'title'   => '排序方式',
            'options' => array(
                'date'          => '日期',
                'rand'          => '随机',
                'comment_count' => '评论数',
                'views'         => '阅读量',
                'modified'      => '最近编辑时间',
                'title'         => '标题',
                'ID'            => '文章ID',
            ),
        ),

        array(
            'id'      => 'count',
            'type'    => 'text',
            'title'   => '文章数量',
            'default' => 6,
        ),
       
        array(
            'id'      => 'offset',
            'type'    => 'text',
            'title'   => '第几页',
            'default' => 0,
        ),

    ),
));
if (!function_exists('ripro_v2_module_cms_post_img')) {
    function ripro_v2_module_cms_post_img($args, $instance) {
        if (!is_page_template_modular()) {return false;} //非模块页面不显示

        $instance = array_merge( array( 
            'title' => '分类图片文章展示块',
            'desc' => '描述介绍内容',
            'offset' => 0,
            'category' => 0,
            'count' => 6,
            'orderby' => 'date',
        ), $instance);


        echo $args['before_widget'];

        // 查询
        $_args = array(
            'cat'                 => (int)$instance['category'],
            'ignore_sticky_posts' => true,
            'post_status'         => 'publish',
            'posts_per_page'      => $instance['count'],
            'paged'              => (int)$instance['offset'],
            'orderby'             => $instance['orderby'],
        );
        
        if ($instance['orderby']=='views') {
            $_args['meta_key'] = 'views';
            $_args['orderby'] = 'meta_value_num';
            $_args['order'] = 'DESC';
        }

        $PostData = new WP_Query($_args);

        ob_start();?>
        <?php if (!empty($instance['title'])) : ?>
        <div class="row">
            <div class="col-lg col-sm-12">
                <h3 class="section-title d-flex align-items-center mb-lg-4 mb-2"><?php echo $instance['title']; ?><small><?php echo $instance['desc']; ?></small></h3>
            </div>
        </div>
        <?php endif;?>
        <div class="module posts-wrapper post-cms">
            <div class="row">
              <?php while ($PostData->have_posts()): $PostData->the_post();?>
                <div class="col-lg-4 col-sm-12 mb-lg-4 mb-1">
                  <div class="cms_grid_box lazyload" data-bg="<?php echo _get_post_thumbnail_url(null, 'full'); ?>">
                    <a class="u-permalink" href="<?php the_permalink();?>" rel="nofollow noopener noreferrer"></a>
                    <article id="post-<?php the_ID();?>" <?php post_class('post');?>>
                        <div class="entry-wrapper">
                          <header class="entry-header">
                            <?php rizhuti_v2_entry_title(array('link' => true));?>
                          </header>
                          <div class="entry-footer">
                            <?php rizhuti_v2_entry_meta(array('category' => true, 'author' => true, 'views' => true, 'date' => true));?>
                          </div>
                        </div>
                    </article>
                  </div>
                </div>
              <?php endwhile;?>
              </div>
            </div>
        </div>
    <?php wp_reset_postdata(); echo ob_get_clean(); echo $args['after_widget'];

    }
}




/**
 * 专题展示
 */

CSF::createWidget('rizhuti_v2_module_cms_list', array(
    'title'       => 'RI-首页模块 : 专题展示',
    'classname'   => 'rizhuti_v2-widget-list-cms',
    'description' => '专题展示',
    'fields'      => array(

        array(
            'id'    => 'title',
            'type'  => 'text',
            'title' => '标题',
        ),
        array(
            'id'    => 'desc',
            'type'  => 'text',
            'title' => '描述介绍内容',
        ),

        array(
            'id'      => 'count',
            'type'    => 'text',
            'title'   => '文章数量',
            'default' => 3,
        ),

        array(
            'id'      => 'col',
            'type'    => 'select',
            'title'   => '显示列数',
            'options' => array(
                '2'          => '2列显示',
                '3'          => '3列显示',
            ),
        ),
        array(
            'id'         => 'category_data',
            'type'       => 'group',
            'title'      => '新建',
            'fields'     => array(
                array(
                    'id'          => 'category',
                    'type'        => 'select',
                    'title'       => '选择专题',
                    'placeholder' => '选择专题',
                    'options'     => 'categories',
                    'query_args'  => array(
                        'taxonomy'  => 'series',
                    ),
                ),
                array(
                    'id'      => 'orderby',
                    'type'    => 'select',
                    'title'   => '排序方式',
                    'options' => array(
                        'date'          => '日期',
                        'rand'          => '随机',
                        'comment_count' => '评论数',
                        'modified'      => '最近编辑时间',
                        'title'         => '标题',
                        'ID'            => '文章ID',
                    ),
                ),
                array(
                    'id'      => 'offset',
                    'type'    => 'text',
                    'title'   => '第几页',
                    'default' => 0,
                ),
            ),
        ),

    ),
));

if (!function_exists('rizhuti_v2_module_cms_list')) {
    function rizhuti_v2_module_cms_list($args, $instance) {
        if (!is_page_template_modular()) {return false;} //非模块页面不显示

        $instance = array_merge( array( 
            'title' => '专题展示',
            'desc' => '描述介绍内容',
            'category_data' => array(),
            'col' => 2,
            'count' => 3,
        ), $instance);

        echo $args['before_widget'];
        
        ob_start();?>

        <?php if (!empty($instance['title'])) : ?>
        <div class="row">
            <div class="col-lg col-sm-12">
                <h3 class="section-title d-flex align-items-center mb-lg-4 mb-2"><?php echo $instance['title']; ?><small><?php echo $instance['desc']; ?></small></h3>
            </div>
        </div>
        <?php endif;?>

        <div class="module posts-wrapper post-cms-series">
            <div class="row">
            <?php foreach ( $instance['category_data'] as $item ){
                $category = get_term_by('ID', $item['category'],'series');

                if (empty($category)) {
                    continue;
                }

                if ($instance['col'] > 2) {
                    $col = 'col-lg-4 col-12';
                }else{
                    $col = 'col-lg-6 col-12';
                }

                $bg_img = get_term_meta($category->term_id, 'bg-image', true);
                $bg_img = (!empty($bg_img)) ? $bg_img : get_template_directory_uri().'/assets/img/series-bg.jpg';

                // 查询
                $tax_query = array('relation' => 'OR',array(
                    'taxonomy' => 'series',
                    'field'    => 'term_id',
                    'terms' => $item['category'],
                    'operator' => 'IN'
                ));
                $_args = array(
                    'tax_query' => $tax_query,
                    'ignore_sticky_posts' => true,
                    'post_status'         => 'publish',
                    'posts_per_page'      => (int)$instance['count'],
                    'paged'              => (int)$item['offset'],
                    'orderby'             => $item['orderby'],
                );
                $PostData = new WP_Query($_args);
                echo '<div class="'.$col.'"><div class="card mb-4">';

                echo '<div href="'.esc_url( get_term_link( $category->term_id ) ).'" class="cat-info lazyload visible" data-bg="'.$bg_img.'">';
                echo '<h3 class="p-3"><a href="'.esc_url( get_term_link( $category->term_id ) ).'" rel="category">'.$category->name.' <span class="badge badge-pill badge-primary-lighten mr-2">'.sprintf(__('专题 +%s', 'ripro-v2'),$category->count).'</span></a></h3>';
                echo '</div>';

                echo '<ul class="m-0 p-0 mt-2">';
                while ($PostData->have_posts()): $PostData->the_post();
                echo '<li class="list-group-item py-2 text-nowrap-ellipsis"><a'._target_blank().' href="' . esc_url( get_permalink() ) . '" title="' . get_the_title() . '" rel="bookmark">' . get_the_title() . '</a></li>';
                endwhile;
                wp_reset_postdata();
                echo '<li class="list-group-item py-2 text-nowrap-ellipsis"><a href="'.esc_url( get_term_link( $category->term_id ) ).'" rel="category" class="btn btn-white btn-block">'.esc_html__('进入专题','ripro-v2').'</a></li>';
                echo '</ul></div></div>';
            }?>
            </div>
        </div>
        <?php echo ob_get_clean(); echo $args['after_widget'];

    }
}


/**
 * 纯标题文章展示
 */
CSF::createWidget('rizhuti_v2_module_postlist_item', array(
    'title'       => 'RI-首页模块 : 纯标题文章展示',
    'classname'   => 'rizhuti_v2-widget-catpost-list',
    'description' => '分类纯标题文章展示',
    'fields'      => array(

        array(
            'id'    => 'title',
            'type'  => 'text',
            'title' => '标题',
        ),
        array(
            'id'    => 'desc',
            'type'  => 'text',
            'title' => '描述介绍内容',
        ),

        array(
            'id'      => 'count',
            'type'    => 'text',
            'title'   => '文章数量',
            'default' => 3,
        ),

        // array(
        //     'id'      => 'is_post_oneimg',
        //     'type'    => 'switcher',
        //     'title'   => '第一篇文章显示缩略图',
        //     'default' => false,
        // ),

        array(
            'id'      => 'col',
            'type'    => 'select',
            'title'   => '显示列数',
            'options' => array(
                '2'          => '2列显示',
                '3'          => '3列显示',
            ),
        ),
        array(
            'id'         => 'category_data',
            'type'       => 'group',
            'title'      => '新建',
            'fields'     => array(
                array(
                    'id'          => 'category',
                    'type'        => 'select',
                    'title'       => '选择分类',
                    'placeholder' => '选择分类',
                    'options'     => 'categories',
                ),
                array(
                    'id'      => 'orderby',
                    'type'    => 'select',
                    'title'   => '排序方式',
                    'options' => array(
                        'date'          => '日期',
                        'rand'          => '随机',
                        'comment_count' => '评论数',
                        'modified'      => '最近编辑时间',
                        'title'         => '标题',
                        'ID'            => '文章ID',
                    ),
                ),
                array(
                    'id'      => 'offset',
                    'type'    => 'text',
                    'title'   => '第几页',
                    'default' => 0,
                ),
            ),
        ),

    ),
));
if (!function_exists('rizhuti_v2_module_postlist_item')) {
    function rizhuti_v2_module_postlist_item($args, $instance) {
        if (!is_page_template_modular()) {return false;} //非模块页面不显示

        $instance = array_merge( array( 
            'title' => '分类纯标题文章展示',
            'desc' => '分类纯标题文章展示DESC',
            'count' => 3,
            'is_post_oneimg' => 4,
            'col' => 2,
            'category_data' => array(),
        ), $instance);


        echo $args['before_widget'];

        ob_start();?>
        <?php if (!empty($instance['title'])) : ?>
        <div class="row">
            <div class="col-lg col-sm-12">
                <h3 class="section-title d-flex align-items-center mb-lg-4 mb-2"><?php echo $instance['title']; ?><small><?php echo $instance['desc']; ?></small></h3>
            </div>
        </div>
        <?php endif;?>


        <div class="module posts-wrapper post-cms-lists">
            
            <div class="row">
            <?php foreach ( $instance['category_data'] as $item ){
                $category = get_term_by('ID', $item['category'],'category');

                if (empty($category)) {
                    continue;
                }

                if ($instance['col'] > 2) {
                    $col = 'col-lg-4 col-12';
                }else{
                    $col = 'col-lg-6 col-12';
                }

                // 查询

                $_args = array(
                    'cat'                 => (int)$item['category'],
                    'ignore_sticky_posts' => true,
                    'post_status'         => 'publish',
                    'posts_per_page'      => (int)$instance['count'],
                    'paged'              => (int)$item['offset'],
                    'orderby'             => $item['orderby'],
                );

                $bg_img = get_term_meta($category->term_id, 'bg-image', true);
                $bg_img = (!empty($bg_img)) ? $bg_img : get_template_directory_uri().'/assets/img/series-bg.jpg';

                $PostData = new WP_Query($_args);

                echo '<div class="'.$col.'"><div class="card mb-4">';


                echo '<h3 class="cat-title"><a href="'.esc_url( get_term_link( $category->term_id ) ).'" rel="category"><img class="lazyload" data-src="'.$bg_img.'">'.$category->name.' <span class="more-coin" title="'.esc_html__('查看更多','ripro-v2').'"><i class="fas fa-ellipsis-h"></i></span></a></h3>';

                echo '<ul>';
                while ($PostData->have_posts()): $PostData->the_post();
                echo '<li class="list-group-item text-nowrap-ellipsis"><a'._target_blank().' href="' . esc_url( get_permalink() ) . '" title="' . get_the_title() . '" rel="bookmark">' . get_the_title() . '</a></li>';
                endwhile;
                wp_reset_postdata();

                echo '</ul></div></div>';

            }?>
            </div> 

        </div>
      <?php wp_reset_postdata(); echo ob_get_clean(); echo $args['after_widget'];
    }
}


/**
 * 纯图文章展示
 */
CSF::createWidget('rizhuti_v2_module_postimg_item', array(
    'title'       => 'RI-首页模块 : 纯图文章展示',
    'classname'   => 'rizhuti_v2-widget-catpost-img',
    'description' => '分类纯图文章展示',
    'fields'      => array(

        array(
            'id'    => 'title',
            'type'  => 'text',
            'title' => '标题',
        ),
        array(
            'id'    => 'desc',
            'type'  => 'text',
            'title' => '描述介绍内容',
        ),

        
        array(
            'id'          => 'category',
            'type'        => 'select',
            'title'       => '只显示分类下',
            'placeholder' => '选择分类',
            'options'     => 'categories',
        ),
        array(
            'id'      => 'orderby',
            'type'    => 'select',
            'title'   => '排序方式',
            'options' => array(
                'date'          => '日期',
                'rand'          => '随机',
                'comment_count' => '评论数',
                'views'         => '阅读量',
                'modified'      => '最近编辑时间',
                'title'         => '标题',
                'ID'            => '文章ID',
            ),
        ),
        array(
            'id'      => 'count',
            'type'    => 'text',
            'title'   => '显示数量',
            'default' => 12,
        ),
        array(
            'id'      => 'offset',
            'type'    => 'text',
            'title'   => '第几页',
            'default' => 0,
        ),


    ),
));
if (!function_exists('rizhuti_v2_module_postimg_item')) {
    function rizhuti_v2_module_postimg_item($args, $instance) {
        if (!is_page_template_modular()) {return false;} //非模块页面不显示

        $instance = array_merge( array( 
            'title' => '纯图文章展示',
            'desc' => '',
            'count' => 12,
            'category' => 0,
            'offset' => 0,
            'count' => 6,
            'orderby' => 'date',
        ), $instance);


        echo $args['before_widget'];


        // 查询
        $_args = array(
            'cat'                 => (int)$instance['category'],
            'ignore_sticky_posts' => true,
            'post_status'         => 'publish',
            'posts_per_page'      => (int)$instance['count'],
            'paged'              => (int)$instance['offset'],
            'orderby'             => $instance['orderby'],
        );

        if ($instance['orderby']=='views') {
            $_args['meta_key'] = 'views';
            $_args['orderby'] = 'meta_value_num';
            $_args['order'] = 'DESC';
        }
        
        $PostData = new WP_Query($_args);

        ob_start();?>
        <?php if (!empty($instance['title'])) : ?>
        <div class="row">
            <div class="col-lg col-sm-12">
                <h3 class="section-title d-flex align-items-center mb-lg-4 mb-2"><?php echo $instance['title']; ?><small><?php echo $instance['desc']; ?></small></h3>
            </div>
        </div>
        <?php endif;?>


        <div class="module posts-wrapper post-cms-gridimg">
            
            <div class="row no-gutters">
                <?php while ($PostData->have_posts()): $PostData->the_post();?>
                    <div class="col-lg-2 col-md-3 col-4">
                        <article class="img-item">
                            <figure class="thumbnail" data-toggle="tooltip" data-placement="top" title="<?php echo get_the_title(); ?>">
                            <?php echo _get_post_media(null, 'thumbnail',false);?>
                            </figure>
                        </article>
                    </div>
                <?php endwhile;?>
            </div> 

        </div>
      <?php wp_reset_postdata(); echo ob_get_clean(); echo $args['after_widget'];
    }
}


/**
 * 会员介绍小工具
 */
CSF::createWidget('rizhuti_v2_module_vip_price', array(
    'title'       => 'RI-首页模块 : 会员价格介绍模块',
    'classname'   => 'rizhuti-v2-module-vip-price',
    'description' => 'Ri主题的小工具',
    'fields'      => array(
        array(
            'id'      => 'title',
            'type'    => 'textarea',
            'sanitize'   => false,
            'title'   => '标题',
            'default' => '<i class="fa fa-diamond"></i> 加入本站会员，开启尊贵特权之体验',
        ),
        array(
            'id'      => 'desc',
            'type'    => 'textarea',
            'sanitize'   => false,
            'title'   => '描述介绍',
            'default' => '本站资源支持会员下载专享，普通注册会员只能原价购买资源或者限制免费下载次数，付费会员所有资源可无限下载。并可享受资源折扣或者免费下载。',
        ),
    ),
));
if (!function_exists('rizhuti_v2_module_vip_price')) {
    function rizhuti_v2_module_vip_price($args, $instance) {
        if (!is_page_template_modular() || is_close_site_shop()) {return false;} //非模块页面不显示

        $instance = array_merge( array( 
            'title' => '<i class="fa fa-diamond"></i> 加入本站会员，开启尊贵特权之体验',
            'desc' => '本站资源支持会员下载专享，普通注册会员只能原价购买资源或者限制免费下载次数，付费会员所有资源可无限下载。并可享受资源折扣或者免费下载。',
        ), $instance);
        $site_vip_pay_opt = site_vip_pay_opt();
        $classes = ( is_user_logged_in() ) ? '' : ' login-btn' ;
        echo $args['before_widget'];
        ob_start();?>
        <div class="container">
            <div class="row">
                <div class="col-lg-3 mb-4 pay-vip-col">
                    <h5 class="mt-3"><?php echo $instance['title'];?></h5>
                    <p class="text-muted small"><?php echo $instance['desc'];?></p>
                </div>
            <?php foreach ($site_vip_pay_opt as $key => $opt) {?>
                <div class="col-lg-3 mb-4 pay-vip-col">
                    <div class="pay-vip-item card shadow" data-type="<?php echo $opt['daynum'];?>" data-price="<?php echo $opt['price'];?>">
                      <div class="vip-body">
                        <h5 class="vip-title" style="color:<?php echo $opt['color'];?>;"><i class="fa fa-diamond mr-2"></i><?php echo $opt['title'];?></h5>
                        <p class="vip-price py-2" style="color:<?php echo $opt['color'];?>;"><i class="<?php echo site_mycoin('icon');?>"></i> <?php echo $opt['price'].site_mycoin('name');?></p>
                        <p class="vip-text small text-muted"><?php echo $opt['desc'];?></p>
                      </div>
                      <a rel="nofollow noopener noreferrer" href="<?php echo get_user_page_url('vip');?>" class="btn btn-sm btn-info mt-2<?php echo $classes;?>"><?php echo esc_html__('前往开通','ripro-v2');?></a>
                    </div>
                </div>
            <?php } ?>
            </div>
        </div>

        <?php echo ob_get_clean(); echo $args['after_widget'];
    }
}


/**
 * 侧边栏分类文章展示
 */
CSF::createWidget('ripro_v2_widget_post_item', array(
    'title'       => 'RI-文章侧边栏 : 文章展示',
    'classname'   => 'ripro_v2-widget-post',
    'description' => '文章展示',
    'fields'      => array(
        array(
            'id'    => 'title',
            'type'  => 'text',
            'title' => '标题',
        ),
        array(
            'id'      => 'is_media',
            'type'    => 'switcher',
            'title'   => '显示缩略图',
            'default' => true,
        ),
        array(
            'id'      => 'is_one_maxbg',
            'type'    => 'switcher',
            'title'   => '第一篇文章大图',
            'default' => false,
        ),
        array(
            'id'          => 'category',
            'type'        => 'select',
            'title'       => '只显示分类下',
            'placeholder' => '最新文章',
            'options'     => 'categories',
            'desc'     => '不选择分类则显示为最新文章，建议没有特殊需求选择其他分类文章展示，有利于seo爬虫爬取',
        ),
        array(
            'id'      => 'orderby',
            'type'    => 'select',
            'title'   => '排序方式',
            'options' => array(
                'date'          => '日期',
                'rand'          => '随机',
                'comment_count' => '评论数',
                'views'         => '阅读量',
                'modified'      => '最近编辑时间',
                'title'         => '标题',
                'ID'            => '文章ID',
            ),
        ),
       
        array(
            'id'      => 'count',
            'type'    => 'text',
            'title'   => '文章数量',
            'default' => 4,
        ),
        array(
            'id'      => 'offset',
            'type'    => 'text',
            'title'   => '第几页',
            'default' => 0,
        ),

    ),
));
if (!function_exists('ripro_v2_widget_post_item')) {
    function ripro_v2_widget_post_item($args, $instance) {
        if (is_page_template_modular() ) {
            // Name is required, so display nothing if we don't have it.
            return;
        }

        $instance = array_merge( array( 
            'is_media' => true,
            'is_one_maxbg' => false,
            'category' => 0,
            'orderby' => 'date',
            'count' => 4,
            'offset' => 0,
        ), $instance);


        echo $args['before_widget'];

        if (!empty($instance['title'])) {
            echo $args['before_title'] . $instance['title'] . $args['after_title'];
        }
        // 查询
        $_args = array(
            'cat'                 => (int)$instance['category'],
            'ignore_sticky_posts' => true,
            'post_status'         => 'publish',
            'posts_per_page'      => (int)$instance['count'],
            'paged'              => (int)$instance['offset'],
            'orderby'             => $instance['orderby'],
        );
        if ($instance['orderby']=='views') {
            $_args['meta_key'] = 'views';
            $_args['orderby'] = 'meta_value_num';
            $_args['order'] = 'DESC';
        }
        
        $PostData = new WP_Query($_args);
        $i = 0;
        
        ob_start();?>
        <div class="list"> 
              <?php while ($PostData->have_posts()): $PostData->the_post();
                $i++; 
                $maxbg = ( $i==1 && !empty($instance['is_one_maxbg']) ) ? ' maxbg' : '' ;
              ?>
                  <article id="post-<?php the_ID();?>" <?php post_class('post post-list' . $maxbg);?>>
                      <?php if (!empty($instance['is_media'])) {
                        echo _get_post_media(null, 'thumbnail',false);
                      }?>
                      <div class="entry-wrapper">
                        <header class="entry-header">
                          <?php rizhuti_v2_entry_title(array('link' => true));?>
                        </header>
                        <div class="entry-footer"><?php rizhuti_v2_entry_meta(array('date' => true));?></div>
                    </div>
                </article>
              <?php endwhile;?>
        </div>
        <?php wp_reset_postdata(); echo ob_get_clean(); echo $args['after_widget'];
    }
}

/**
 * 侧边栏分类展示
 */
CSF::createWidget('ripro_v2_widget_cats_item', array(
    'title'       => 'RI-文章侧边栏 : 分类展示',
    'classname'   => 'ripro_v2-widget-categories',
    'description' => '分类展示',
    'fields'      => array(
        array(
            'id'    => 'title',
            'type'  => 'text',
            'title' => '标题',
        ),
        array(
            'id'      => 'is_media',
            'type'    => 'switcher',
            'title'   => '显示缩略图',
            'default' => true,
        ),
        array(
            'id'          => 'cats',
            'type'        => 'select',
            'title'       => '展示的分类',
            'desc'        => '排序规则以设置的顺序为准',
            'placeholder' => '选择分类',
            'inline'      => true,
            'chosen'      => true,
            'multiple'    => true,
            'options'     => 'categories',
        ),

    ),
));
if (!function_exists('ripro_v2_widget_cats_item')) {
    function ripro_v2_widget_cats_item($args, $instance) {

        if ( is_page_template_modular() ) {
            // Name is required, so display nothing if we don't have it.
            return;
        }

        $instance = array_merge( array( 
            'is_media' => true,
            'cats' => array(),
        ), $instance);


        echo $args['before_widget'];

        if (!empty($instance['title'])) {
            echo $args['before_title'] . $instance['title'] . $args['after_title'];
        }

        ob_start();?>

        <ul class="categories">
        <?php foreach ($instance['cats'] as $cid) : 
            $term = get_category($cid);
            if (!$term) {
                continue; 
            }
            $image = get_term_meta($term->term_id, 'bg-image', true);
            $image = (!empty($image)) ? $image : get_template_directory_uri() . '/assets/img/series-bg.jpg';
        ?>
            <li class="cat-item">
                <a class="inner" href="<?php echo get_category_link($term->term_id);?>">
                    <div class="thumbnail">
                        <img alt="<?php echo $term->name;?>" src="<?php echo $image;?>"></img>
                    </div>
                    <div class="content">
                        <h5 class="title"><?php echo $term->name;?></h5>
                    </div>
                </a>
            </li>
        <?php endforeach;?>
        </ul>
        <?php echo ob_get_clean(); echo $args['after_widget'];
    }
}


/**
 * 用户消费排行榜
 */

CSF::createWidget('ripro_v2_widget_user_top', array(
    'title'       => 'RI-文章侧边栏 : 全站用户余额消费排行榜',
    'classname'   => 'ripro_v2-widget-usertop',
    'description' => '全站用户余额消费排行榜',
    'fields'      => array(
        array(
            'id'    => 'title',
            'type'  => 'text',
            'title' => '标题',
            'default' => '站内消费排行榜',
        ),
        array(
            'id'    => 'num',
            'type'  => 'text',
            'title' => '显示前几数量？',
            'default' => '5',
        ),

    ),
));
if (!function_exists('ripro_v2_widget_user_top')) {
    function ripro_v2_widget_user_top($args, $instance) {

        if ( is_page_template_modular() ) {
            // Name is required, so display nothing if we don't have it.
            return;
        }

        $instance = array_merge( array( 
            'title' => '',
            'num' => 5,
        ), $instance);


        echo $args['before_widget'];

        if (!empty($instance['title'])) {
            echo $args['before_title'] . $instance['title'] . $args['after_title'];
        }

        global $wpdb;

        $page_number = 1;
        $limit_from = ($page_number - 1) * $instance['num'];
        $res = $wpdb->get_results("SELECT user_id,(meta_value+0) as meta_value FROM {$wpdb->usermeta} WHERE meta_key = 'cao_consumed_balance' ORDER BY (meta_value+0) DESC LIMIT {$instance['num']}");
        
        echo '<ul class="user-top small">';
        foreach ($res as $key => $value) {
            $user_name = ($user = get_user_by('ID', $value->user_id)) ? $user->display_name : '***';
            echo sprintf('<li><span class="badge badge-primary text-white">%s</span> (%s) %s<span class="ml-1">%s</span></li>',$key+1,ri_substr_cut($user_name),esc_html__('累计消费','ripro-v2'),$value->meta_value.site_mycoin('name'));
        }
        echo '</ul>';

        echo $args['after_widget'];
    }
}


/**
 * 全站下载热度排行榜
 */

CSF::createWidget('ripro_v2_widget_down_top', array(
    'title'       => 'RI-文章侧边栏 : 全站下载热度排行榜',
    'classname'   => 'ripro_v2-widget-downtop',
    'description' => '全站下载热度排行榜，为保护网站真实数据隐私，前台不显示具体销量，会按照排行榜展示文章',
    'fields'      => array(
        array(
            'id'    => 'title',
            'type'  => 'text',
            'title' => '标题',
            'default' => '下载热度排行榜',
        ),
        array(
            'id'    => 'num',
            'type'  => 'text',
            'title' => '显示前几数量？',
            'default' => '5',
        ),

    ),
));
if (!function_exists('ripro_v2_widget_down_top')) {
    function ripro_v2_widget_down_top($args, $instance) {

        if ( is_page_template_modular() ) {
            // Name is required, so display nothing if we don't have it.
            return;
        }

        $instance = array_merge( array( 
            'title' => '',
            'num' => 5,
        ), $instance);


        echo $args['before_widget'];

        if (!empty($instance['title'])) {
            echo $args['before_title'] . $instance['title'] . $args['after_title'];
        }

        global $wpdb;

        $page_number = 1;
        $limit_from = ($page_number - 1) * $instance['num'];
        $res = $wpdb->get_results("SELECT down_id,count(down_id) as down_num FROM {$wpdb->cao_down_log} WHERE 1=1 GROUP BY down_id ORDER BY down_num DESC LIMIT {$instance['num']}");
        
        echo '<ul class="user-top small">';
        $key_num = 0;
        foreach ($res as $key => $value) {
            if (get_post_type( $value->down_id ) == 'post') {
                $key_num++;
                $titles = '<a'._target_blank().' href='.get_permalink($value->down_id).'>'.get_the_title($value->down_id).'</a>';
                echo sprintf('<li><span class="badge badge-warning text-white">%s</span> %s</li>',$key_num,$titles);
            }
        }
        echo '</ul>';

        echo $args['after_widget'];
    }
}


/**
 * 销量排行榜
 */

CSF::createWidget('ripro_v2_widget_pay_top', array(
    'title'       => 'RI-文章侧边栏 : 全站销量排行榜',
    'classname'   => 'ripro_v2-widget-paytop',
    'description' => '全站销量排行榜，为保护网站真实数据隐私，前台不显示具体销量，会按照排行榜展示文章',
    'fields'      => array(
        array(
            'id'    => 'title',
            'type'  => 'text',
            'title' => '标题',
            'default' => '销量排行榜',
        ),
        array(
            'id'    => 'num',
            'type'  => 'text',
            'title' => '显示前几数量？',
            'default' => '5',
        ),

    ),
));
if (!function_exists('ripro_v2_widget_pay_top')) {
    function ripro_v2_widget_pay_top($args, $instance) {

        if ( is_page_template_modular() ) {
            // Name is required, so display nothing if we don't have it.
            return;
        }

        $instance = array_merge( array( 
            'title' => '',
            'num' => 5,
        ), $instance);


        echo $args['before_widget'];

        if (!empty($instance['title'])) {
            echo $args['before_title'] . $instance['title'] . $args['after_title'];
        }

        global $wpdb;

        $page_number = 1;
        $limit_from = ($page_number - 1) * $instance['num'];
        $res = $wpdb->get_results("SELECT post_id,count(post_id) as pay_num FROM {$wpdb->cao_order} WHERE status=1 GROUP BY post_id ORDER BY pay_num DESC LIMIT {$instance['num']}");
        // var_dump($res);die;
        echo '<ul class="user-top small">';
        $key_num = 0;
        foreach ($res as $key => $value) {
            if (get_post_type( $value->post_id ) == 'post') {
                $key_num++;
                $titles = '<a'._target_blank().' href='.get_permalink($value->post_id).'>'.get_the_title($value->post_id).'</a>';
                echo sprintf('<li><span class="badge badge-danger text-white">%s</span> %s</li>',$key_num,$titles);
            }
        }
        echo '</ul>';

        echo $args['after_widget'];
    }
}

