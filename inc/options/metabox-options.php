<?php if (!defined('ABSPATH')) {die;} // Cannot access directly.

if (!is_admin()) {
    return;
}

//布局meta
$prefix_meta_opts = '_prefix_meta_options';
CSF::createMetabox($prefix_meta_opts, array(
    'title'     => '布局风格',
    'post_type' => array('post', 'page'),
    'context'   => 'side',
    'data_type' => 'unserialize',
));

CSF::createSection($prefix_meta_opts, array(
    'fields' => array(

        array(
            'id'          => 'hero_single_style',
            'type'        => 'radio',
            'title'       => '文章顶部风格',
            'placeholder' => '',
            'options'     => array(
                'none' => '默认常规',
                'wide' => '顶部半高背景',
                'full' => '顶部全屏背景',
            ),
            'default'     => _cao('hero_single_style'),
        ),
        array(
            'id'          => 'sidebar_single_style',
            'type'        => 'radio',
            'title'       => '侧边栏',
            'placeholder' => '',
            'inline'      => true,
            'options'     => array(
                'right' => '右侧',
                'none'  => '无',
                'left'  => '左侧',
            ),
            'default'     => _cao('sidebar_single_style'),
        ),

    ),
));

//布局meta
$prefix_meta_opts = '_prefix_meta_options1';
CSF::createMetabox($prefix_meta_opts, array(
    'title'     => '视频封面',
    'post_type' => array('post'),
    'context'   => 'side',
    'data_type' => 'unserialize',
));

CSF::createSection($prefix_meta_opts, array(
    'fields' => array(
        array(
            'id'      => 'thumb_video_src',
            'type'    => 'upload',
            'title'   => '地址',
            'desc'    => '支持mp3,m3u8地址，不支持解析，如果文章内容中有视频，自动获取第一个视频，视频封面需要将文章形式选择为视频格式',
            'default' => '',
        ),

    ),
));

if (!is_close_site_shop()):
// 付费meta
    $prefix_meta_opts = '_prefix_shop_options';
    CSF::createMetabox($prefix_meta_opts, array(
        'title'     => '文章付费资源设置',
        'post_type' => 'post',
        'data_type' => 'unserialize',
        'priority'  => 'high',
    ));

    CSF::createSection($prefix_meta_opts, array(
        'fields' => array(
            array(
                'id'          => 'cao_price',
                'type'        => 'number',
                'title'       => '价格：*',
                'desc'        => '免费请填写：0',
                'unit'        => site_mycoin('name'),
                'output'      => '.heading',
                'output_mode' => 'width',
                'default'     => _cao('cao_price'),
            ),

            array(
                'id'          => 'cao_vip_rate',
                'type'        => 'number',
                'title'       => '会员折扣：*',
                'desc'        => '0.N 等于N折；1 等于不打折；0 等于会员免费',
                'unit'        => '.N折',
                'output'      => '.heading',
                'output_mode' => 'width',
                'default'     => _cao('cao_vip_rate'),
            ),

            array(
                'id'      => 'cao_close_novip_pay',
                'type'    => 'checkbox',
                'title'   => '普通用户禁止购买',
                'default' => _cao('cao_close_novip_pay'),
                'label'   => '勾选后普通用户不能下单支付，只允许会员可以购买',
            ),

            array(
                'id'      => 'cao_is_boosvip',
                'type'    => 'checkbox',
                'title'   => '永久会员免费',
                'label'   => '勾选后永久会员免费，其他会员按折扣或者原价购买',
                'default' => _cao('cao_is_boosvip'),
            ),

            array(
                'id'          => 'cao_expire_day',
                'type'        => 'number',
                'title'       => '购买有效期天数',
                'desc'        => '0 无限期；N天后失效需要重新购买',
                'unit'        => '天',
                'output'      => '.heading',
                'output_mode' => 'width',
                'default'     => _cao('cao_expire_day'),
            ),

            array(
                'id'      => 'cao_status',
                'type'    => 'switcher',
                'title'   => '启用付费下载模块',
                'label'   => '开启后可设置付费下载专有内容',
                'default' => _cao('cao_status'),
            ),

            // 下载地址 新
            array(
                'id'                     => 'cao_downurl_new',
                'type'                   => 'group',
                'title'                  => '下载资源',
                'subtitle'               => '支持多个下载地址，支持https:,thunder:,magnet:,ed2k 开头地址',
                'accordion_title_number' => true,
                'fields'                 => array(
                    array(
                        'id'      => 'name',
                        'type'    => 'text',
                        'title'   => '资源名称',
                        'default' => '资源名称',
                    ),
                    array(
                        'id'       => 'url',
                        'type'     => 'upload',
                        'title'    => '下载地址',
                        'sanitize' => false,
                        'default'  => '#',
                    ),
                    array(
                        'id'    => 'pwd',
                        'type'  => 'text',
                        'title' => '下载密码',
                    ),
                ),
                'default'                => get_post_shop_downurl(),
                'dependency'             => array('cao_status', '==', 'true'),
            ),

            array(
                'id'         => 'cao_demourl',
                'type'       => 'text',
                'title'      => '演示地址',
                'label'      => '为空则不显示',
                'default'    => _cao('cao_demourl'),
                'dependency' => array('cao_status', '==', 'true'),
            ),

            array(
                'id'         => 'cao_diy_btn',
                'type'       => 'text',
                'title'      => '自定义按钮',
                'subtitle'   => '为空则不显示，用 | 隔开',
                'desc'       => '格式： 下载免费版|https://www.baidu.com/',
                'default'    => _cao('cao_diy_btn'),
                'dependency' => array('cao_status', '==', 'true'),
            ),

            array(
                'id'         => 'cao_info',
                'type'       => 'repeater',
                'title'      => '下载资源其他信息',
                'fields'     => array(
                    array(
                        'id'      => 'title',
                        'type'    => 'text',
                        'title'   => '标题',
                        'default' => '标题',
                    ),
                    array(
                        'id'       => 'desc',
                        'type'     => 'text',
                        'title'    => '描述内容',
                        'sanitize' => false,
                        'default'  => '这里是描述内容',
                    ),
                ),
                'default'    => _cao('cao_info'),
                'dependency' => array('cao_status', '==', 'true'),
            ),

            array(
                'id'          => 'cao_paynum',
                'type'        => 'number',
                'title'       => '已售数量',
                'desc'        => '可自定义修改数字',
                'unit'        => '个',
                'output'      => '.heading',
                'output_mode' => 'width',
                'default'    => _cao('cao_paynum'),
            ),

        ),
    ));

endif;

$prefix_post_opts_video = 'video-postmeta-box';
CSF::createMetabox($prefix_post_opts_video, array(
    'title'     => '付费视频模块',
    'post_type' => 'post',
    'data_type' => 'unserialize',
));
CSF::createSection($prefix_post_opts_video, array(
    'fields' => array(
        array(
            'id'    => 'cao_video',
            'type'  => 'switcher',
            'title' => '启用视频模块',
            'label' => '',
        ),
        array(
            'id'         => 'cao_is_video_free',
            'type'       => 'checkbox',
            'title'      => '免费视频',
            'label'      => '勾选后该视频不参与任何付费逻辑，可直接展示播放',
            'default'    => false,
            'dependency' => array('cao_video', '==', 'true'),
        ),
        array(
            'id'         => 'video_url',
            'type'       => 'textarea',
            'title'      => '视频播放地址',
            'sanitize'   => false,
            'desc'       => '格式：<code>视频地址|自定义名称|视频封面</code><br>需要文章 形式 设置为视频格式,布局风格设置为背景才显示模块<br>输入视频地址，每行一个，支持mp4/m3u8常见格式，不支持平台解析',
            'dependency' => array('cao_video', '==', 'true'),
        ),

    ),
));

// 自定义SEO TDK
if (_cao('is_ripro_v2_seo', '0')):

    $prefix_meta_opts = '_prefix_seo_options';
    CSF::createMetabox($prefix_meta_opts, array(
        'title'     => '自定义文章SEO信息',
        'post_type' => array('post', 'page'),
        'data_type' => 'unserialize',
    ));
    CSF::createSection($prefix_meta_opts, array(
        'fields' => array(
            array(
                'id'       => 'post_titie',
                'type'     => 'text',
                'title'    => '自定义SEO标题',
                'subtitle' => '为空则不设置',
            ),
            array(
                'id'       => 'keywords',
                'type'     => 'text',
                'title'    => '自定义SEO关键词',
                'subtitle' => '关键词用英文逗号,隔开',
            ),
            array(
                'id'       => 'description',
                'type'     => 'textarea',
                'title'    => '自定义SEO描述',
                'subtitle' => '字数控制到80-180最佳',
            ),

        ),
    ));

endif;

if (_cao('is_custom_post_meta_opt', '0') && _cao('custom_post_meta_opt', '0')) {
    //获取玩家配置
    $prefix_post_opts = '_custom_post_opts';
    CSF::createMetabox($prefix_post_opts, array(
        'title'     => '高级自定义文章属性',
        'post_type' => 'post',
        'data_type' => 'unserialize',
        'context'   => 'side',
    ));

    $custom_post_meta_opt = _cao('custom_post_meta_opt', '0');
    $fields_item          = array();
    foreach ($custom_post_meta_opt as $k => $v) {
        $opt = array('all' => '默认');
        foreach ($v['meta_opt'] as $value) {
            $_key       = $value['opt_ua'];
            $opt[$_key] = $value['opt_name'];
        }
        $item = array(
            'id'      => $v['meta_ua'],
            'type'    => 'select',
            'title'   => $v['meta_name'],
            'options' => $opt,
            'default' => 'option-2',
        );
        array_push($fields_item, $item);
    }
    CSF::createSection($prefix_post_opts, array(
        'fields' => $fields_item,
    ));
}
