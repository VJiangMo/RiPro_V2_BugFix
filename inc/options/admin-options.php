<?php if (!defined('ABSPATH')) {die;} // Cannot access directly.


if (!is_admin() || !current_user_can('manage_options')) {
    return;
}

$prefix     = _OPTIONS_PRE;
$assets_dir = get_template_directory_uri() . '/assets/img';

CSF::createOptions($prefix, array(
    'menu_title' => 'RiPro-v2设置',
    'menu_slug'  => 'ripro-v2',
));

CSF::createSection($prefix, array(
    'title'  => '基本设置',
    'icon'   => 'fa fa-circle',
    'fields' => array(

        

        array(
            'id'          => 'font_awesome_mod',
            'type'        => 'radio',
            'inline'      => true,
            'title'       => '网站font-awesome图标加载模式',
            'desc'       => '推荐使用CDN加载图标文件，速度较快，不占用网站请求压力，优化效果明显，如果某个CDN节点不稳定或者慢，更换即可',
            'placeholder' => '',
            'options'     => array(
                'theme' => '本地加载',
                'jsdelivr' => 'jsdelivr源 CDN加载',
                'bootcdn' => 'bootcdn源 CDN加载',
            ),
            'default'     => 'theme',
        ),

        array(
            'id'      => 'is_site_notify',
            'type'    => 'switcher',
            'title'   => '全站弹窗公告',
            'desc'   => '开启网站顶部显示公告图标，点击弹出公告',
            'default' => true,
        ),

        array(
            'id'      => 'is_site_auto_notify',
            'type'    => 'switcher',
            'title'   => '首次打开网站自动弹出公告',
            'desc'   => '用户第一次打开网址自动弹出公告，如果用户主动打开关闭过弹出，后续不会一直重复弹出，提升用户体验',
            'default' => true,
            'dependency' => array('is_site_notify', '==', 'true'),
        ),


        array(
            'id'         => 'site_notify_title',
            'type'       => 'text',
            'title'      => '全站弹窗公告-标题',
            'desc'       => '例如：RiPro-v2最新版本更新日志',
            'attributes' => array(
                'style' => 'width: 100%;',
            ),
            'default'    => 'RiPro-v2最新版本更新日志',
            'dependency' => array('is_site_notify', '==', 'true'),
        ),
        array(
            'id'         => 'site_notify_desc',
            'type'       => 'textarea',
            'title'      => '全站弹窗公告-内容',
            'desc'       => '全站弹窗公告，通知，纯文本通知弹窗,支持html代码',
            'attributes' => array(
                'style' => 'width: 100%;',
            ),
            'sanitize' => false,
            'default'    => '这是一条网站公告，可在后台开启或关闭，可自定义背景颜色，标题，内容，此处可使用html标签...',
            'dependency' => array('is_site_notify', '==', 'true'),
        ),
        array(
            'id'         => 'site_notify_color',
            'type'       => 'color',
            'title'      => '全站弹窗公告-背景颜色',
            'default'    => '#5b5b5b',
            'dependency' => array('is_site_notify', '==', 'true'),
        ),


        array(
            'id'      => 'site_main_target_blank',
            'type'    => 'switcher',
            'title'   => '网站主链接新窗口打开文章',
            'desc'   => '主要链接包括列表展示盒网格展示一些文章都新窗口打开',
            'default' => false,
        ),


        
        array(
            'id'      => 'is_site_question',
            'type'    => 'switcher',
            'title'   => '问答社区',
            'desc'   => '开启后网站支持问答，网站问答中心地址为：'.home_url('/question'),
            'default' => false,
        ),

        array(
            'id'      => 'is_site_comments',
            'type'    => 'switcher',
            'title'   => '网站评论功能',
            'desc'    => '开启后显示评论功能',
            'default' => true,
        ),

        array(
            'id'      => 'is_site_tougao',
            'type'    => 'switcher',
            'title'   => '投稿功能',
            'desc'    => '用户在个人中心可以快速简单投稿，一定程度规避版权风险',
            'default' => false,
        ),
        array(
            'id'         => 'is_site_tougao_wp',
            'type'       => 'switcher',
            'title'      => '使用WordPress后端投稿',
            'desc'       => '使用WordPress后端自带文章页面投稿，功能完备，注意，请在wordpress后台设置-常规中，将新用户默认角色-设置为-贡献者，则新注册用户均可以使用后端投稿',
            'default'    => false,
            'dependency' => array('is_site_tougao', '==', 'true'),
        ),

        array(
            'id'      => 'md5_file_udpate',
            'type'    => 'switcher',
            'title'   => '上传文件MD5加密重命名',
            'desc'    => '建议开启，可以有效解决中文字符无法上传图片问题，防止付费图片被抓包等',
            'default' => false,
        ),

    ),
));


CSF::createSection($prefix, array(
    'title'  => 'SEO设置',
    'icon'   => 'fa fa-circle',
    'fields' => array(

        array(
            'type'       => 'content',
            'content'    => '主题自带SEO优化功能说明：
            <br>1，自带SEO功能包含了自定义文章，首页，页面的TDK功能，自动抓取网站摘要，关键词，自动添加OG协议描述信息等
            <br>2，支持自定义替换wordpress默认的标题链接符号',
        ),

        array(
            'id'      => 'no_categoty',
            'type'    => 'switcher',
            'title'   => '网站文章分类链接去除category/前缀',
            'label'   => '',
            'default' => true,
        ),


        array(
            'id'      => 'is_ripro_v2_seo',
            'type'    => 'switcher',
            'title'   => '主题内置的SEO功能',
            'label'   => '有部分用户在用插件做SEO，可以在此关闭主题自带SEO功能',
            'default' => true,
        ),

        array(
            'id'         => 'site_seo',
            'type'       => 'fieldset',
            'title'      => '内置SEO设置',
            'fields'     => array(
                array(
                    'id'      => 'separator',
                    'type'    => 'text',
                    'title'   => '全站链接符',
                    'desc'    => '一经选择，切勿中途更改，对SEO不友好，一般为“-”或“_”',
                    'default' => '_',
                ),
                array(
                    'id'         => 'keywords',
                    'type'       => 'text',
                    'title'      => '网站关键词',
                    'desc'       => '3-5个关键词，用英文逗号隔开',
                    'attributes' => array(
                        'style' => 'width: 100%;',
                    ),
                    'default'    => 'RiPro,日主题,RiPro-V2,Rizhuti,RizhutiV2,Riplus,rizhuti.com,rizhuti,Ritheme',
                ),
                array(
                    'id'       => 'description',
                    'type'     => 'textarea',
                    'sanitize' => false,
                    'title'    => '网站描述',
                    'default'  => 'RiPro-v2是一款全新架构的Wordpress主题，兼容老款RiPro，支持会员商城 ，前后台界面均支持html5响应式布局，夜间模式一键切换。喜欢你就日一下。',
                ),

            ),
            'dependency' => array('is_ripro_v2_seo', '==', 'true'),
        ),

    ),
));

//
// Create a section
//
CSF::createSection($prefix, array(
    'title'  => '顶部设置',
    'icon'   => 'fa fa-circle',
    'fields' => array(
        array(
            'id'      => 'site_logo',
            'type'    => 'upload',
            'title'   => '网站LOGO',
            'default' => get_template_directory_uri() . '/assets/img/logo.png',
        ),

        array(
            'id'      => 'site_favicon',
            'type'    => 'upload',
            'title'   => 'favicon',
            'default' => get_template_directory_uri() . '/assets/img/favicon.png',
        ),

        array(
            'id'      => 'navbar_omnisearch_search',
            'type'    => 'switcher',
            'title'   => '顶部显示搜索按钮',
            'default' => true,
        ),
        array(
            'id'      => 'navbar_user_hover',
            'type'    => 'switcher',
            'title'   => '顶部用户头像鼠标滑过展开效果',
            'default' => true,
        ),

        array(
            'id'          => 'navbar_style',
            'type'        => 'select',
            'title'       => '顶部菜单风格',
            'placeholder' => '',
            'options'     => array(
                'regular'    => '常规默认',
                'sticky'     => '自适应滚动',
                // 'transition' => '自适应透明',
            ),
            'default'     => 'sticky',
        ),

        array(
            'id'      => 'navbar_home_transition',
            'type'    => 'switcher',
            'title'   => '顶部菜单首页透明',
            'desc'    => '启用后首页透明菜单，搭配幻灯片，搜索模块美观',
            'default' => false,
        ),


        array(
            'id'       => 'web_css',
            'type'     => 'textarea',
            'title'    => '自定义CSS样式代码',
            'before'   => '<p class="csf-text-muted"><strong>位于顶部，自定义修改CSS</strong>不用添加<strong>&lt;style></strong>标签</p>',
            'settings' => array(
                'theme' => 'mbo',
                'mode'  => 'css',
            ),
            'sanitize' => false,
            'default'  => '',
        ),


    ),
));

//
// Create a section
//
CSF::createSection($prefix, array(
    'title'  => '布局设置',
    'icon'   => 'fa fa-circle',
    'fields' => array(

        // 新建模块化页面绑定
        array(
            'id'         => 'custom_modular_pages',
            'type'       => 'group',
            'title'      => '新增自定义模块化页面',
            'desc'      => '新增自定义模块化页面绑定，可以DIY无限个模块化页面，不仅限于首页模块化',
            'max'        => '100',
            'fields'     => array(
                array(
                    'id'      => 'widget_name',
                    'type'    => 'text',
                    'title'   => '绑定小工具的英文标识',
                    'desc'   => '注意用英文标识，绑定后会创建一个标识，创建小工具后关联这个页面，中途删除页面或者小工具则作废',
                    'default' => 'modular_1',
                ),
                array(
                    'id'          => 'page_id',
                    'type'        => 'select',
                    'title'       => '选择模块化页面',
                    'placeholder' => '选择页面',
                    'desc' => '请先去页面-创建页面，模板选择为“模块化布局页面”',
                    'options'     => 'pages',
                    'query_args'  => array(
                        'meta_key' => '_wp_page_template',
                        'meta_value' => 'pages/page-modular.php',
                    )
                ),
            ),
        ),

        array(
            'id'      => 'is_site_wide_screen',
            'type'    => 'switcher',
            'title'   => '全站宽屏模式',
            'desc'    => '宽屏模式仅你设备实际尺寸超过1200px才有效，宽屏模式内容宽度为1440PX',
            'default' => false,
        ),


        array(
            'id'      => 'is_site_dark_light',
            'type'    => 'switcher',
            'title'   => '夜间模式切换功能',
            'desc'    => '',
            'default' => true,
        ),

        array(
            'id'      => 'is_site_default_dark',
            'type'    => 'switcher',
            'title'   => '默认打开夜间模式',
            'desc'    => '网站默认打开夜间模式',
            'default' => false,
            'dependency'  => array('is_site_dark_light', '==', 'true'),
        ),



        array(
            'id'      => 'is_compare_options_to_global',
            'type'    => 'switcher',
            'title'   => '强制所有内页，文章页布局风格全局统一',
            'default' => false,
        ),

        array(
            'id'      => 'is_post_data_diff',
            'type'    => 'switcher',
            'title'   => '文章时间显示为几小时前',
            'default' => true,
        ),

        array(
            'id'          => 'hero_single_style',
            'type'        => 'select',
            'title'       => '文章内页顶部风格',
            'placeholder' => '',
            'options'     => array(
                'none' => '默认常规',
                'wide' => '顶部半高背景',
                'full' => '顶部全屏背景',
            ),
            'default'     => 'wide',
        ),

        array(
            'id'          => 'sidebar_single_style',
            'type'        => 'select',
            'title'       => '文章内页侧边栏',
            'placeholder' => '',
            'options'     => array(
                'none'  => '无',
                'right' => '右侧',
                'left'  => '左侧',
            ),
            'default'     => 'right',
        ),

        //////////////
        array(
            'id'          => 'archive_single_style',
            'type'        => 'select',
            'title'       => '分类页侧边栏',
            'placeholder' => '',
            'options'     => array(
                'none'  => '无',
                'right' => '右侧',
                'left'  => '左侧',
            ),
            'default'     => 'none',
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
            'default'     => 'grid',
        ),


        //列表设置

        array(
            'id'      => 'is_post_list_author',
            'type'    => 'switcher',
            'title'   => '列表-显示作者和头像',
            'default' => true,
        ),
        array(
            'id'      => 'is_post_list_category',
            'type'    => 'switcher',
            'title'   => '列表-显示分类信息',
            'default' => true,
        ),
        array(
            'id'      => 'is_post_list_date',
            'type'    => 'switcher',
            'title'   => '列表-显示发布时间',
            'default' => true,
        ),

        array(
            'id'      => 'is_post_list_comment',
            'type'    => 'switcher',
            'title'   => '列表-显示评论信息',
            'default' => false,
        ),

        array(
            'id'      => 'is_post_list_favnum',
            'type'    => 'switcher',
            'title'   => '列表-显示收藏数量',
            'default' => false,
        ),
        array(
            'id'      => 'is_post_list_views',
            'type'    => 'switcher',
            'title'   => '列表-显示阅读数量',
            'default' => true,
        ),

        array(
            'id'      => 'is_post_list_shop',
            'type'    => 'switcher',
            'title'   => '列表-显示资源价格',
            'default' => true,
        ),
        array(
            'id'      => 'is_post_list_type',
            'type'    => 'switcher',
            'title'   => '列表-显示文章类型',
            'default' => true,
        ),

        ////////////////////////////////////////
        array(
            'id'      => 'is_post_grid_excerpt',
            'type'    => 'switcher',
            'title'   => '网格-显示文章摘要',
            'default' => true,
        ),
        array(
            'id'      => 'is_post_grid_author',
            'type'    => 'switcher',
            'title'   => '网格-显示作者和头像',
            'default' => true,
        ),
        array(
            'id'      => 'is_post_grid_category',
            'type'    => 'switcher',
            'title'   => '网格-显示分类信息',
            'default' => true,
        ),
        array(
            'id'      => 'is_post_grid_date',
            'type'    => 'switcher',
            'title'   => '网格-显示发布时间',
            'default' => true,
        ),

        array(
            'id'      => 'is_post_grid_comment',
            'type'    => 'switcher',
            'title'   => '网格-显示评论信息',
            'default' => false,
        ),

        array(
            'id'      => 'is_post_grid_favnum',
            'type'    => 'switcher',
            'title'   => '网格-显示收藏数量',
            'default' => false,
        ),
        array(
            'id'      => 'is_post_grid_views',
            'type'    => 'switcher',
            'title'   => '网格-显示阅读数量',
            'default' => true,
        ),
        array(
            'id'      => 'is_post_grid_shop',
            'type'    => 'switcher',
            'title'   => '网格-显示资源价格',
            'default' => true,
        ),
        array(
            'id'      => 'is_post_grid_type',
            'type'    => 'switcher',
            'title'   => '网格-显示文章类型',
            'default' => true,
        ),

        // 分页布局
        array(
            'id'          => 'site_pagination',
            'type'        => 'select',
            'title'       => '分页风格',
            'placeholder' => '',
            'options'     => array(
                'navigation'      => '上下页',
                'numeric'         => '数字',
                'infinite_button' => '按钮加载下一页',
                'infinite_scroll' => '下拉自动翻页',
            ),
            'default'     => 'numeric',
        ),

    ),
));

CSF::createSection($prefix, array(
    'title'  => '文章内页',
    'icon'   => 'fa fa-circle',
    'fields' => array(

        array(
            'id'      => 'is_single_shop_template',
            'type'    => 'switcher',
            'title'   => '资源类文章启用新布局',
            'desc'    => '开启后，如果文章是下载类或者付费类型文章，自动切换新布局模式，新布局可以设置场景问题，TAB切换按钮，直观方便，建议开启',
            'default' => true,
        ),

        array(
            'id'      => 'is_single_gallery',
            'type'    => 'switcher',
            'title'   => '文章内图片点击灯箱效果',
            'desc'    => '开启后，文章内图片单击打开图片放大效果',
            'default' => true,
        ),


        array(
            'id'         => 'single_shop_template_help',
            'type'       => 'repeater',
            'title'      => '文章内页常见问题配置',
            'fields'     => array(
                array(
                    'id'      => 'title',
                    'type'    => 'text',
                    'title'   => '标题',
                    'default' => '问题标题',
                ),
                array(
                    'id'       => 'desc',
                    'type'     => 'textarea',
                    'title'    => '描述内容',
                    'sanitize' => false,
                    'default'  => '这里是问题描述内容',
                ),
            ),
            'default'    => [
                ['title' => '免费下载或者VIP会员资源能否直接商用？', 'desc' => '本站所有资源版权均属于原作者所有，这里所提供资源均只能用于参考学习用，请勿直接商用。若由于商用引起版权纠纷，一切责任均由使用者承担。更多说明请参考 VIP介绍。'],
                ['title' => '提示下载完但解压或打开不了？', 'desc' => '最常见的情况是下载不完整: 可对比下载完压缩包的与网盘上的容量，若小于网盘提示的容量则是这个原因。这是浏览器下载的bug，建议用百度网盘软件或迅雷下载。 若排除这种情况，可在对应资源底部留言，或联络我们。'],
                ['title' => '找不到素材资源介绍文章里的示例图片？', 'desc' => '对于会员专享、整站源码、程序插件、网站模板、网页模版等类型的素材，文章内用于介绍的图片通常并不包含在对应可供下载素材包内。这些相关商业图片需另外购买，且本站不负责(也没有办法)找到出处。 同样地一些字体文件也是这种情况，但部分素材会在素材包内有一份字体下载链接清单。'],
                ['title' => '付款后无法显示下载地址或者无法查看内容？', 'desc' => '如果您已经成功付款但是网站没有弹出成功提示，请联系站长提供付款信息为您处理'],
                ['title' => '购买该资源后，可以退款吗？', 'desc' => '源码素材属于虚拟商品，具有可复制性，可传播性，一旦授予，不接受任何形式的退款、换货要求。请您在购买获取之前确认好 是您所需要的资源'],
            ],
            'dependency' => array('is_single_shop_template', '==', 'true'),
        ),

        array(
            'id'      => 'is_single_meta_favnum',
            'type'    => 'switcher',
            'title'   => '文章内容页顶部显示收藏数量',
            'default' => true,
        ),
        array(
            'id'      => 'is_single_meta_views',
            'type'    => 'switcher',
            'title'   => '文章内容页顶部显示阅读数量',
            'default' => true,
        ),
        array(
            'id'      => 'is_single_meta_author',
            'type'    => 'switcher',
            'title'   => '文章内容页顶部显示作者信息',
            'default' => true,
        ),
        array(
            'id'      => 'is_single_meta_date',
            'type'    => 'switcher',
            'title'   => '文章内容页顶部显示发布时间',
            'default' => true,
        ),

        array(
            'id'      => 'is_single_breadcrumb',
            'type'    => 'switcher',
            'title'   => '文章内容页显示面包屑导航',
            'default' => true,
        ),

        array(
            'id'       => 'single_copyright',
            'type'     => 'textarea',
            'title'    => '文章底部版权内容',
            'sanitize' => false,
            'desc'     => '不填写则不显示，如果是html标签，请注意 / 结尾标签，例如：' . esc_html('<p>内容</p>') . '，必须闭合，否则界面错乱',
            'default'  => '<small><strong>声明：</strong>本站所有文章，如无特殊说明或标注，均为本站原创发布。任何个人或组织，在未征得本站同意时，禁止复制、盗用、采集、发布本站内容到任何网站、书籍等各类媒体平台。如若本站内容侵犯了原著者的合法权益，可联系我们进行处理。</small>',
        ),

        array(
            'id'      => 'is_single_tags',
            'type'    => 'switcher',
            'title'   => '文章底部显示本文标签',
            'default' => true,
        ),

        array(
            'id'      => 'is_single_share',
            'type'    => 'switcher',
            'title'   => '文章底部显示分享组件',
            'desc'    => '关闭后不显示底部作者信息和分享信息',
            'default' => true,
        ),

        array(
            'id'      => 'is_single_entry_page',
            'type'    => 'switcher',
            'title'   => '在文章底部显示上一篇下一篇导航',
            'default' => true,
        ),



        array(
            'id'      => 'is_single_footer_author',
            'type'    => 'switcher',
            'title'   => '文章底部作者信息',
            'default' => true,
            'desc'    => '关闭后不显示作者头像和信息',
        ),


        array(
            'id'      => 'is_single_share_poser',
            'type'    => 'switcher',
            'title'   => '文章底部海报组件',
            'default' => true,
            'desc'    => '如果已经登录用户，则海报二维码中会自动携带用户的推广链接 <a target="_blank" href="https://www.kancloud.cn/rizhuti/ripro-v2/2574892#_45">海报常见错误解决办法</a>',
        ),

        array(
            'id'         => 'single_share_poser_logo',
            'type'       => 'upload',
            'title'      => '海报底部LOGO（尺寸220*58）',
            'dsec'       => '',
            'default'    => get_template_directory_uri() . '/assets/img/logo.png',
            'dependency' => array('is_single_share_poser', '==', 'true'),
        ),
        array(
            'id'         => 'single_share_poser_desc',
            'type'       => 'text',
            'title'      => '海报LOGO底部描述，最多38个字符',
            'dsec'       => '',
            'default'    => '扫码识别右侧二维码阅读全文',
            'dependency' => array('is_single_share_poser', '==', 'true'),
        ),



        array(
            'id'      => 'is_single_reward',
            'type'    => 'switcher',
            'title'   => '文章底部打赏组件',
            'default' => true,
            'desc'    => '显示支付宝和微信打赏',
        ),

        array(
            'id'         => 'single_reward_alipay',
            'type'       => 'upload',
            'title'      => '支付宝收款码',
            'dsec'       => '',
            'default'    => get_template_directory_uri() . '/assets/img/qr.png',
            'dependency' => array('is_single_reward', '==', 'true'),
        ),
        array(
            'id'         => 'single_reward_weixin',
            'type'       => 'upload',
            'title'      => '微信收款码',
            'dsec'       => '',
            'default'    => get_template_directory_uri() . '/assets/img/qr.png',
            'dependency' => array('is_single_reward', '==', 'true'),
        ),



        // 相关列表风格
        array(
            'id'          => 'related_posts_item_style',
            'type'        => 'select',
            'title'       => '相关列表风格',
            'placeholder' => '',
            'options'     => array(
                'list' => '列表',
                'grid' => '网格',
                'none' => '不显示',
            ),
            'default'     => 'list',
        ),

        array(
            'id'         => 'single_related_posts_num',
            'type'       => 'text',
            'title'      => '相关文章显示数量',
            'dsec'       => '',
            'default'    => '4',
        ),



    ),
));

//
// Create a section
//
CSF::createSection($prefix, array(
    'title'  => '缩略图设置',
    'icon'   => 'fa fa-circle',
    'fields' => array(

        array(
            'id'        => 'default_thumb',
            'type'      => 'upload',
            'title'     => '文章默认缩略图',
            'desc'      => '设置文章默认缩略图（建议和自定义文章缩略图宽高保持一致）',
            'default'   => get_template_directory_uri() . '/assets/img/thumb.jpg',
        ),

        array(
            'id'        => 'rand_default_thumb',
            'type'      => 'gallery',
            'title'     => '文章随机缩略图',
            'add_title' => '上传图片',
            'desc'      => '不设置则不启用，建议添加10张，设置随机缩略图集则自动启用，对没有缩略图的文章随机缩略图，随机缩略图会带有文章id的关联算法，优化了搜索引擎抓图，防止完全随机导致收录后地址变更问题',
        ),

        array(
            'id'        => 'default_thumb_lazyload',
            'type'      => 'upload',
            'title'     => '缩略图加载过度图',
            'desc'      => '缩略图没有通过lazyload显示出来之前显示的默认图片,支持GIF动图',
            'default'   => get_template_directory_uri() . '/assets/img/thumb-ing.gif',
        ),

        array(
            'id'      => 'is_post_one_thumbnail',
            'type'    => 'switcher',
            'title'   => '自动抓取第一张图片',
            'desc'   => '没设置特色图自动获取文章中第一张图片作为特色图，如果出现抓取的是最后一张的情况，请检查文章内容中的图片是否有回车或者空格隔开，必须隔开才能识别',
            'default' => true,
        ),

        array(
            'id'      => 'post_thumbnail_size',
            'type'    => 'dimensions',
            'title'   => '自定义文章缩略图尺寸',
            'desc'    => '宽度，高度，设置文章缩略图尺寸，特色图像',
            'units'   => array('px'),
            'default' => array(
                'width'  => '300',
                'height' => '200',
                'unit'   => 'px',
            ),
        ),
        array(
            'id'      => 'post_thumbnail_crop',
            'type'    => 'switcher',
            'title'   => '总是裁剪缩略图到这个尺寸',
            'label'   => '',
            'default' => true,
        ),

        array(
            'id'      => 'post_medium_size',
            'type'    => 'dimensions',
            'title'   => '自定义中等大小尺寸',
            'desc'    => '宽度，高度，一般关闭 设置为0 ，可以减少wordpres自带缩略图裁剪多个无用的图片',
            'units'   => array('px'),
            'default' => array(
                'width'  => '0',
                'height' => '0',
                'unit'   => 'px',
            ),
        ),
        array(
            'id'      => 'post_large_size',
            'type'    => 'dimensions',
            'title'   => '自定义大尺寸',
            'desc'    => '宽度，高度，一般关闭 设置为0 ，可以减少wordpres自带缩略图裁剪多个无用的图片',
            'units'   => array('px'),
            'default' => array(
                'width'  => '0',
                'height' => '0',
                'unit'   => 'px',
            ),
        ),

        array(
            'id'      => 'is_img_cloud_storage',
            'type'    => 'switcher',
            'title'   => '适配云储存裁剪尺寸',
            'desc'    => '如果您网站使用了OSS等云储存插件，可以在此设置缩略图自定义裁剪参数，达到完美展示缩略图和节约百分之90的带宽速度',
            'default' => true,
        ),

        array(
            'id'         => 'img_cloud_storage_domain',
            'type'       => 'text',
            'title'      => '云储存域名',
            'desc'       => '这里举例是阿里云OSS的地址,COS，qiniu等',
            'default'    => 'https://xxxxx.oss-cn-qingdao.aliyuncs.com/',
            'dependency' => array('is_img_cloud_storage', '==', 'true'),
        ),

        array(
            'id'         => 'img_cloud_storage_param',
            'type'       => 'text',
            'title'      => '云储存裁剪缩略图参数地址',
            'desc'       => '这里举例是阿里云OSS的裁剪参数方法，高度参数用%height%，宽参数用%width%拼接',
            'default'    => '?x-oss-process=image/resize,m_fill,h_%height%,w_%width%',
            'dependency' => array('is_img_cloud_storage', '==', 'true'),
        ),

        array(
            'type'       => 'content',
            'content'    => '该功能只是适配缩略图裁剪，不是插件，如果您的插件里有缩略图参数，请关闭或者禁用，再此处开启缩略图裁剪即可。<br>常见参数填写：阿里云OSS：<code>?x-oss-process=image/resize,m_fill,h_%height%,w_%width%</code><br>常见参数填写：腾讯云COS：<code>?imageView2/1/w/%width%/h/%height%/q/85</code>',
            'dependency' => array('is_img_cloud_storage', '==', 'true'),
        ),
        array(
            'type'       => 'content',
            'content'    => '使用timthumb.php万能裁剪教程及说明，如果需要开启timthumb.php万能裁剪缩略图功能，请在您主题根目录，找到<code>timthumb.php.no</code>文件，将其文件名改为<code>timthumb.php</code>，则自动启用。<br>timthumb.php裁剪模式注意事项：文件权限需要为755，如果您的缩略图有外链图片，请打开timthumb.php文件，在代码顶部添加外链域名为白名单。<br>如果您的网站图片较多，建议经常清理缩略图缓存文件，在您主题根目录 cache 文件中，删除全部文件即可完成清理。',
        ),

    ),
));

CSF::createSection($prefix, array(
    'title'  => '分类筛选',
    'icon'   => 'fa fa-circle',
    'fields' => array(
        array(
            'id'      => 'is_archive_top_bg',
            'type'    => 'switcher',
            'title'   => '内页筛选顶部启用背景图像模块',
            'label'   => '启用后自动获取分类下第一个文章的缩略图作为背景',
            'default' => true,
        ),
        array(
            'id'         => 'is_archive_top_bg_one',
            'type'       => 'switcher',
            'title'      => '所有分类等内页顶部背景图固定一张',
            'default'    => false,
            'dependency' => array('is_archive_top_bg', '==', 'true'),
        ),
        array(
            'id'         => 'archive_top_bg_one_img',
            'type'       => 'upload',
            'title'      => '分类等内页顶部背景图片',
            'dsec'       => '如果没有文章图片或者其他页面没有图片则用此图片显示美化',
            'default'    => get_template_directory_uri() . '/assets/img/top-bg.jpg',
            'dependency' => array('is_archive_top_bg_one', '==', 'true'),
        ),

        array(
            'id'      => 'is_archive_filter',
            'type'    => 'switcher',
            'title'   => '启用内页筛选条功能',
            'label'   => '',
            'default' => true,
        ),


        array(
            'id'         => 'archive_filter_style',
            'type'       => 'radio',
            'title'      => '筛选条布局风格',
            'desc'      => '手机端自动切换为下拉菜单模式',
            'inline'     => true,
            'options'    => array(
                'dropdown' => '下拉菜单筛选',
                'inline'  => '横排展开筛选',
            ),
            'default'    => 'dropdown',
            'dependency' => array('is_archive_filter', '==', 'true'),
        ),



        array(
            'id'         => 'is_archive_filter_cat',
            'type'       => 'switcher',
            'title'      => '一级主分类筛选',
            'label'      => '当前分类页面主分类筛选',
            'default'    => true,
            'dependency' => array('is_archive_filter', '==', 'true'),
        ),

        array(
            'id'         => 'is_archive_filter_cat_num',
            'type'       => 'switcher',
            'title'      => '分类筛选显示文章数量',
            'label'      => '',
            'default'    => true,
            'dependency' => array('is_archive_filter', '==', 'true'),
        ),

        array(
            'id'          => 'archive_filter_cat_1',
            'type'        => 'select',
            'title'       => '一级主分类筛选设置',
            'desc'        => '排序规则以设置的顺序为准',
            'placeholder' => '选择分类',
            'inline'      => true,
            'chosen'      => true,
            'multiple'    => true,
            'options'     => 'categories',
            'query_args'  => array(
                'orderby' => 'count',
                'order'   => 'DESC',
                'parent'  => 0,
            ),
            'dependency'  => array('is_archive_filter', '==', 'true'),
        ),

        array(
            'id'         => 'is_archive_filter_cat_child',
            'type'       => 'switcher',
            'title'      => '二级分类筛选',
            'label'      => '当前分类页面有二级分类的时候显示子分类,当前分类下没有子分类则显示全部分类，如果当前二级分类下有三级分类，则显示到三级',
            'default'    => true,
            'dependency' => array('is_archive_filter', '==', 'true'),
        ),

        array(
            'id'         => 'archive_filter_cat_orderby',
            'type'       => 'radio',
            'title'      => '二,三级分类筛选排序方式',
            'inline'     => true,
            'options'    => array(
                'id'    => '分类ID',
                'name'  => '分类名称',
                'count' => '文章数量',
            ),
            'default'    => 'id',
            'dependency' => array('is_archive_filter', '==', 'true'),
        ),
        array(
            'id'         => 'is_archive_filter_tag',
            'type'       => 'switcher',
            'title'      => '标签筛选',
            'label'      => '显示分类下文章相关标签20个,如果没有则显示全部其他标签，此处标签为当前分类下文章相关标签',
            'default'    => true,
            'dependency' => array('is_archive_filter', '==', 'true'),
        ),
        array(
            'id'         => 'is_archive_filter_price',
            'type'       => 'switcher',
            'title'      => '价格筛选',
            'label'      => '显示价格筛选',
            'default'    => true,
            'dependency' => array('is_archive_filter', '==', 'true'),
        ),
        array(
            'id'         => 'is_archive_filter_order',
            'type'       => 'switcher',
            'title'      => '排序筛选',
            'label'      => '显示排序筛选',
            'default'    => true,
            'dependency' => array('is_archive_filter', '==', 'true'),
        ),

        // 高级自定义文章属性-筛选 感谢会员昵称 股东·烧饼 QQ：6337766 的赞助和监督
        array(
            'id'      => 'is_custom_post_meta_opt',
            'type'    => 'switcher',
            'title'   => '高级自定义文章字段属性',
            'desc'    => '主要用于增强筛选细化资源，高级自定义文章字段属性<b><font color="red">每个字段名称，必须有两个或两个字段选项，否则会报错</font></b>',
            'default' => false,
        ),
        array(
            'id'         => 'custom_post_meta_opt',
            'type'       => 'group',
            'title'      => '自定义字段设置',
            'max'        => '50',
            'fields'     => array(
                
                array(
                    'id'      => 'meta_name',
                    'type'    => 'text',
                    'title'   => '字段名称',
                    'default' => '字段1',
                ),
                array(
                    'id'      => 'meta_ua',
                    'type'    => 'text',
                    'title'   => '字段英文标识',
                    'default' => 'my_meta_1',
                ),
                array(
                    'id'          => 'meta_category',
                    'type'        => 'select',
                    'title'       => '只在该分类下显示高级筛选属性',
                    'placeholder' => '全部显示',
                    'chosen'      => true,
                    'multiple'    => true,
                    'options'     => 'categories',
                ),
                array(
                    'id'     => 'meta_opt',
                    'type'   => 'group',
                    'title'  => '字段选项',
                    'fields' => array(
                        array(
                            'id'      => 'opt_name',
                            'type'    => 'text',
                            'title'   => '选项中文名称',
                            'default' => '字段选项1',
                        ),
                        array(
                            'id'      => 'opt_ua',
                            'type'    => 'text',
                            'title'   => '选项英文标识',
                            'default' => 'opt_1',
                        ),
                    ),
                ),

            ),
            'default' => _cao_old('custom_post_meta_opt'),
            'dependency' => array('is_custom_post_meta_opt', '==', 'true'),
        ),


    ),
));

//
// Basic 商城设置
//
CSF::createSection($prefix, array(
    'id'    => 'shop_fields',
    'title' => '商城设置',
    'icon'  => 'fa fa-plus-circle',
));

// 商城-基本设置
CSF::createSection($prefix, array(
    'parent' => 'shop_fields',
    'title'  => '基本设置',
    'icon'   => 'fa fa-circle',
    'fields' => array(
        array(
            'id'      => 'is_site_shop',
            'type'    => 'switcher',
            'title'   => '启用商城和会员中心',
            'label'   => '一键开启关闭商城和会员功能，默认开启，关闭后所有商城相关功能都关闭，这样可以直接以博客主题展示',
            'default' => true,
        ),

        array(
            'id'      => 'is_login_site_shop',
            'type'    => 'switcher',
            'title'   => '只有登录用户才能看到付费信息',
            'label'   => '开启后，只有登录用户才能看到网站有付费，商品等信息，防止部分备案商审查',
            'default' => false,
        ),

        array(
            'id'      => 'is_weixin_no_shop',
            'type'    => 'switcher',
            'title'   => '在微信内关闭商城和付费功能',
            'desc'   => '资源站一般手机用户不多，建议关闭，防止微信内被人恶意举报虚拟资源',
            'default' => false,
        ),

        array(
            'id'         => 'site_shop_name_txt',
            'type'       => 'text',
            'title'      => '自定义订单名称',
            'desc'       => '购买资源时在支付平台显示的商品名称，例如自助购买，自助充值，防止，敏感词汇风控,字数不要超过8个，防止微信支付报错',
            'default'    => '商城自助购买',
            'dependency' => array('is_site_shop', '==', 'true'),
        ),

        array(
            'id'      => 'is_site_async_cache',
            'type'    => 'switcher',
            'title'   => '异步加载购买按钮',
            'label'   => '可以解决使用静态缓存插件不刷新问题和有效防止采集地址',
            'default' => false,
        ),

        array(
            'id'      => 'is_site_simple_price_opt',
            'type'    => 'switcher',
            'title'   => '精简价格信息组件',
            'label'   => '开启后前台价格组件VIP免费和永久VIP都免费，则只显示VIP免费信息，以为永久VIP本身权限已经包含了VIP的所有权限',
            'default' => false,
        ),

        array(
            'id'         => 'site_shop_paybtn_txt',
            'type'       => 'text',
            'title'      => '自定义购买按钮名称',
            'desc'       => '例如：立即购买，立即获取，立即下载等',
            'default'    => '立即购买',
            'dependency' => array('is_site_shop', '==', 'true'),
        ),

        array(
            'id'         => 'site_mycoin_name',
            'type'       => 'text',
            'title'      => '站内币名称',
            'desc'       => '设置站内币名称，例如：金币、下载币、积分、资源币、BB币、USDT等',
            'default'    => '金币',
            'attributes' => array(
                'style' => 'width: 100px;',
            ),
        ),

        array(
            'id'         => 'site_mycoin_rate',
            'type'       => 'text',
            'title'      => '站内币充值比例',
            'default'    => '10',
            'desc'       => '默认：1元等于10个站内币(必须是正整数1~10000，建议一次设置好，后续谨慎更改，会影响后台订单的汇率)',
            'attributes' => array(
                'style' => 'width: 100px;',
            ),
        ),

        array(
            'id'      => 'site_mycoin_icon',
            'type'    => 'icon',
            'title'   => '站内币图标',
            'desc'    => '设置站内币图标，部分页面展示需要',
            'default' => 'fas fa-coins',
        ),

        array(
            'id'         => 'site_mycoin_pay_minnum',
            'type'       => 'text',
            'title'      => '站内币最小充值数量限制',
            'default'    => '1',
            'desc'       => '',
            'attributes' => array(
                'style' => 'width: 100px;',
            ),
        ),
        array(
            'id'         => 'site_mycoin_pay_maxnum',
            'type'       => 'text',
            'title'      => '站内币最大充值数量限制',
            'default'    => '9999',
            'desc'       => '',
            'attributes' => array(
                'style' => 'width: 100px;',
            ),
        ),
        array(
            'id'      => 'site_mycoin_pay_arr',
            'type'    => 'text',
            'title'   => '站内币充值套餐设置',
            'desc'    => '设置充值套餐，用英文逗号隔开，“,”',
            'default' => '1,10,50,100,300,500',
        ),



        array(
            'id'      => 'is_site_qiandao',
            'type'    => 'switcher',
            'title'   => '每日签到功能',
            'label'   => '',
            'default' => true,
        ),

        array(
            'id'         => 'site_qiandao_coin_num',
            'type'       => 'text',
            'title'      => '每日签到赠送'.site_mycoin('name').'数量',
            'desc'       => '签到赠送的站内币直接到账用户的钱包余额',
            'default'    => '0.5',
            'dependency' => array('is_site_qiandao', '==', 'true'),
        ),


        //

        array(
            'id'          => 'show_shop_widget_wap_position',
            'type'        => 'select',
            'title'       => '手机内下载组件显示位置',
            'placeholder' => '',
            'options'     => array(
                'top'    => '文章内容顶部',
                'bottom' => '文章内容底部',
            ),
            'default'     => 'top',
        ),

        array(
            'id'         => 'shop_expire_day',
            'type'       => 'text',
            'title'      => '登录用户默认购买有效期天数',
            'default'    => '0',
            'desc'       => '0 无限期；N天后失效需要重新购买，如果文章有单独设置天数则文章设置的天数优先级最高',
            'attributes' => array(
                'style' => 'width: 100px;',
            ),
        ),

        array(
            'id'      => 'is_ripro_v2_nologin_pay',
            'type'    => 'switcher',
            'title'   => '开启免登陆购买支持',
            'label'   => '',
            'default' => true,
        ),
        array(
            'id'         => 'ripro_v2_nologin_payKey',
            'type'       => 'text',
            'title'      => '免登陆购买临时密钥',
            'desc'       => '设置随机6-18位字符串，防止被人恶意请求安全验证和检测用',
            'default'    => substr(md5(uniqid()), 5, 10),
            'attributes' => array(
                'type' => 'password',
            ),
            'dependency' => array('is_ripro_v2_nologin_pay', '==', 'true'),
        ),
        array(
            'id'         => 'ripro_v2_nologin_days',
            'type'       => 'slider',
            'title'      => '免登录购买后有效期天数',
            'desc'       => '游客购买的时候会利用浏览器缓存技术记录游客购买的凭证密钥，在游客不清楚自己浏览器缓存数据的情况下，默认有效期天数',
            'default'    => 7,
            'dependency' => array('is_ripro_v2_nologin_pay', '==', 'true'),
        ),

    ),
));

// 商城-网站会员设置
CSF::createSection($prefix, array(
    'parent' => 'shop_fields',
    'title'  => '会员设置',
    'icon'   => 'fa fa-user',
    'fields' => array(

        array(
            'id'    => 'site_vip_options',
            'type'  => 'tabbed',
            'title' => '会员组设置',
            'tabs'  => array(
                array(
                    'id'     => 'nov_user',
                    'title'  => '普通用户',
                    'icon'   => 'fa fa-circle',
                    'fields' => array(
                        array(
                            'id'      => 'nov_name',
                            'type'    => 'text',
                            'title'   => '等级名称',
                            'desc'    => '例如：VIP，普通，平民',
                            'default' => '普通',
                        ),
                        array(
                            'id'      => 'nov_downnum',
                            'type'    => 'text',
                            'title'   => '每日可下载次数',
                            'default' => '1',
                        ),
                        array(
                            'id'      => 'nov_download_rate',
                            'type'    => 'text',
                            'title'   => '下载速度限制(kb/s)',
                            'desc'    => '本地文件最大下载带宽速度限制，单位kb/s',
                            'default' => '100',
                        ),
                        array(
                            'id'      => 'nov_aff_ratio',
                            'type'    => 'text',
                            'title'   => '推广佣金比例',
                            'desc'    => '通过该会员推广链接购买奖励比例，0为关闭，0.05为百分之5',
                            'default' => '0.05',
                        ),
                        array(
                            'id'      => 'nov_author_aff_float',
                            'type'    => 'text',
                            'title'   => '网站作者佣金',
                            'desc'    => '如果文章是用户发布的，被购买时奖励此作者佣金比例，0为不开启,0.1等于百分之10',
                            'default' => '0',
                        ),
                    ),
                ),
                array(
                    'id'     => 'vip_user',
                    'title'  => '会员用户',
                    'icon'   => 'fa fa-diamond',
                    'fields' => array(
                        array(
                            'id'      => 'vip_name',
                            'type'    => 'text',
                            'title'   => '等级名称',
                            'desc'    => '例如：VIP，会员，皇帝',
                            'default' => '会员',
                        ),
                        array(
                            'id'      => 'vip_downnum',
                            'type'    => 'text',
                            'title'   => '每日可下载次数',
                            'default' => '10',
                        ),
                        array(
                            'id'      => 'vip_download_rate',
                            'type'    => 'text',
                            'title'   => '下载速度限制(kb/s)',
                            'desc'    => '本地文件最大下载带宽速度限制，单位kb/s',
                            'default' => '500',
                        ),
                        array(
                            'id'      => 'vip_aff_ratio',
                            'type'    => 'text',
                            'title'   => '推广佣金比例',
                            'desc'    => '通过该会员推广链接购买奖励比例，0为关闭，0.05为百分之5',
                            'default' => '0.1',
                        ),
                        array(
                            'id'      => 'vip_author_aff_float',
                            'type'    => 'text',
                            'title'   => '网站作者佣金',
                            'desc'    => '如果文章是用户发布的，被购买时奖励此作者佣金比例，0为不开启,0.1等于百分之10',
                            'default' => '0',
                        ),
                    ),
                ),
                array(
                    'id'     => 'boosvip_user',
                    'title'  => '永久会员用户',
                    'icon'   => 'fa fa-diamond',
                    'fields' => array(
                        array(
                            'id'      => 'boosvip_name',
                            'type'    => 'text',
                            'title'   => '等级名称',
                            'desc'    => '例如：永久会员',
                            'default' => '永久会员',
                        ),
                        array(
                            'id'      => 'boosvip_downnum',
                            'type'    => 'text',
                            'title'   => '每日可下载次数',
                            'default' => '100',
                        ),
                        array(
                            'id'      => 'boosvip_download_rate',
                            'type'    => 'text',
                            'title'   => '下载速度限制(kb/s)',
                            'desc'    => '本地文件最大下载带宽速度限制，单位kb/s',
                            'default' => '5000',
                        ),
                        array(
                            'id'      => 'boosvip_aff_ratio',
                            'type'    => 'text',
                            'title'   => '推广佣金比例',
                            'desc'    => '通过该会员推广链接购买奖励比例，0为关闭，0.05为百分之5',
                            'default' => '0.2',
                        ),
                        array(
                            'id'      => 'boosvip_author_aff_float',
                            'type'    => 'text',
                            'title'   => '网站作者佣金',
                            'desc'    => '如果文章是用户发布的，被购买时奖励此作者佣金比例，0为不开启,0.1等于百分之10',
                            'default' => '0',
                        ),

                    ),
                ),
            ),
        ),

        array(
            'id'      => 'is_site_aff',
            'type'    => 'switcher',
            'title'   => '会员推广奖励',
            'desc'    => '关闭后网站不涉及推广奖励功能',
            'default' => true,
        ),


        array(
            'id'      => 'site_min_tixian_num',
            'type'    => 'text',
            'title'   => '网站最低提现金额限制/元',
            'default' => '1',
        ),

        array(
            'id'         => 'site_tixian_options',
            'type'       => 'checkbox',
            'title'      => '网站提现通道',
            'options'    => array(
                'rmb' => '提现RMB现金',
                'coin' => '提现站内余额',
            ),
            'default'    => array( 'rmb', 'coin' )
        ),

        array(
            'id'      => 'vip_pay_opt',
            'type'    => 'repeater',
            'title'   => '会员开通套餐配置',
            'fields'  => array(
                array(
                    'id'      => 'title',
                    'type'    => 'text',
                    'default' => '会员',
                    'desc'    => '比如包月会员',
                    'title'   => '套餐名称',
                ),
                array(
                    'id'      => 'daynum',
                    'type'    => 'text',
                    'default' => '30',
                    'desc'    => '比如你想设置一个套餐是月费，则填写30，如果要设置终身会员套餐，填写：9999',
                    'title'   => '开通天数',
                ),
                array(
                    'id'      => 'price',
                    'type'    => 'text',
                    'default' => '20',
                    'desc'    => '此套餐所需的站内币价格',
                    'title'   => '套餐价格',
                ),
                array(
                    'id'       => 'desc',
                    'type'     => 'textarea',
                    'default'  => '会员时长：XX天<br>一年无限下载次数<br>享受资源专属折扣<br>第一时间获取优质资源',
                    'desc'     => '开通描述简介，用br标签换行',
                    'title'    => '开通描述',
                    'sanitize' => false,
                ),
                array(
                    'id'      => 'color',
                    'type'    => 'color',
                    'default' => '#ff6a6d',
                    'title'   => '颜色',
                ),
            ),
            'default' => array(
                array(
                    'title'  => '体验会员',
                    'daynum' => '1',
                    'price'  => '10',
                    'color'  => '#ff6a6d',
                    'desc'   => '会员时长：1天<br>每日1个免费下载次数<br>享受资源专属折扣<br>下载速度100KB/秒',
                ),
                array(
                    'title'  => '包月会员',
                    'daynum' => '30',
                    'price'  => '300',
                    'color'  => '#81d742',
                    'desc'   => '会员时长：30天<br>每日10个免费下载次数<br>享受资源专属折扣<br>下载速度500KB/秒',
                ),
                array(
                    'title'  => '永久会员',
                    'daynum' => '9999',
                    'price'  => '3000',
                    'color'  => '#8224e3',
                    'desc'   => '会员时长：永久<br>每日100个免费下载次数<br>享受资源专属折扣<br>高速下载不限速',
                ),
            ),
        ),

    ),
));

// 商城-支付接口配置
CSF::createSection($prefix, array(
    'parent' => 'shop_fields',
    'title'  => '支付配置',
    'icon'   => 'fa fa-circle',
    'fields' => array(


        array(
            'id'      => 'is_login_user_no_pay',
            'type'    => 'switcher',
            'title'   => '登录用户仅限站内余额购买资源',
            'label'   => '开启后网站登录用户购买文章资源仅支持余额支付，在线支付比如支付宝等支付仅在充值开通VIP时使用',
            'default' => false,
        ),
        array(
            'id'      => 'is_pay_vip_no_coin',
            'type'    => 'switcher',
            'title'   => '开通站内会员仅限在线支付',
            'label'   => '开通站内会员仅限在线支付功能开关，开启后，用户在站内开通会员不能使用余额支付',
            'default' => false,
        ),


        // 站内币支付 mycoin
        array(
            'id'      => 'is_mycoin_pay',
            'type'    => 'switcher',
            'title'   => '站内' . _cao('site_mycoin_name', '下载币') . '-余额支付',
            'label'   => '开启后网站支持站内币充值，付款等',
            'default' => true,
        ),

        array(
            'id'      => 'is_cdk_pay',
            'type'    => 'switcher',
            'title'   => '站内卡蜜-CDK支付',
            'label'   => '开启后网站支持卡蜜CDK支付，充值余额',
            'default' => true,
        ),

        array(
            'id'         => 'cdk_pay_pay_link',
            'type'       => 'text',
            'title'      => '卡蜜购买地址',
            'desc'       => '不想用站自己支付的可以用卡蜜规避风险，自己生产充值卡蜜去第三方平台发卡，用户购买卡蜜后回来充值消费。',
            'dependency' => array('is_cdk_pay', '==', 'true'),
        ),

        // 支付宝配置
        array(
            'id'      => 'is_alipay',
            'type'    => 'switcher',
            'title'   => '支付宝（官方企业支付-新应用模式）',
            'label'   => '支付宝商户后台推荐签约电脑网站支付，当面付，手机网站支付，配置教程（https://www.kancloud.cn/rizhuti/ritheme/1961638）',
            'default' => true,
        ),
        array(
            'id'         => 'alipay',
            'type'       => 'fieldset',
            'title'      => '配置详情',
            'fields'     => array(

                array(
                    'id'         => 'appid',
                    'type'       => 'text',
                    'title'      => '开放平台-应用appid',
                    'attributes' => array(
                        'type' => 'password',
                    ),
                ),
                array(
                    'id'         => 'privateKey',
                    'type'       => 'textarea',
                    'title'      => '开放平台-应用私钥',
                    'desc'      => '请注意这里是应用的私钥，就是你用工具生成的应用私钥',
                ),
                array(
                    'id'         => 'publicKey',
                    'type'       => 'textarea',
                    'title'      => '开放平台-支付宝公钥',
                    'desc'      => '请注意这里是支付宝后台中的公钥，不是你生成的那个应用私钥，如果支付成功后，网站支付状态不刷新或者后台的订单显示未支付，请检查公钥是否支付宝公钥和https证书是否正常，一般更换https证书即可，各大支付平台对ssl证书都有一定的安全性验证，个别有时候无法通知，换一个ssl证书即可',
                ),

                array(
                    'id'         => 'api_type',
                    'type'       => 'radio',
                    'title'      => '应用接口模式',
                    'inline'     => true,
                    'options'    => array(
                        'qr'    => '当面付(需签约当面付产品)',
                        'web'  => '电脑网站支付(需签约电脑网站支付产品)',
                    ),
                    'desc'   => '自2021年初开始，支付宝官方风控系统对异地跨地区进行当面付扫码支付或者信用卡以及分期付款的异常用户，容易被风控商户，建议非必要情况下不要使用当面付模式，关闭此项，如果是个人的商户，没有电脑网站支付产品，只能硬刚当面付，没有其他办法。',
                    'default'    => 'qr',
                ),

                array(
                    'id'      => 'is_mobile',
                    'type'    => 'switcher',
                    'title'   => '手机端自动跳转H5支付',
                    'label'   => '(需签约手机网站支付产品，只支持手机浏览器打开唤醒APP支付，并不能在应用内，如QQ/微信/支付宝内部浏览器无效)',
                    'default' => false,
                ),


            ),
            'dependency' => array('is_alipay', '==', 'true'),
        ),

        // 微信支付配置
        array(
            'id'      => 'is_weixinpay',
            'type'    => 'switcher',
            'title'   => '微信支付（官方企业支付）',
            'label'   => '微信官方商户后台推荐签约native产品，JSAPI产品，h5支付产品',
            'default' => false,
        ),
        array(
            'id'         => 'weixinpay',
            'type'       => 'fieldset',
            'title'      => '配置详情',
            'fields'     => array(
                array(
                    'id'      => 'mch_id',
                    'type'    => 'text',
                    'title'   => '微信支付商户号',
                    'desc'    => '微信支付商户号 PartnerID 通过微信支付商户资料审核后邮件发送',
                    'default' => '',
                ),
                array(
                    'id'      => 'appid',
                    'type'    => 'text',
                    'title'   => '公众号或小程序APPID',
                    'desc'    => '公众号APPID 通过微信支付商户资料审核后邮件发送,开通jsapi支付和配置公众号手机内直接登录的用户注意,如果是小程序的appid,请到支付商户绑定公众号appid授权,这里填写为公众号即可',
                    'default' => '',
                ),
                array(
                    'id'         => 'key',
                    'type'       => 'text',
                    'title'      => '微信支付API密钥',
                    'desc'       => '帐户设置-安全设置-API安全-API密钥-设置API密钥',
                    'default'    => '',
                    'attributes' => array(
                        'type' => 'password',
                    ),
                ),
                array(
                    'id'      => 'is_jsapi',
                    'type'    => 'switcher',
                    'title'   => 'JSAPI支付',
                    'label'   => '微信端内打开可以直接发起支付，开启此项需要登录注册里开启公众号登录，开启后网站用户在微信内登录后可以直接支付',
                    'default' => false,
                ),
                array(
                    'id'      => 'is_mobile',
                    'type'    => 'switcher',
                    'title'   => '手机跳转H5支付',
                    'label'   => '移动端自动自动切换为跳转支付（需开通H5支付，只支持手机浏览器打开唤醒APP支付，并不能在应用内，如QQ/微信/支付宝内部浏览器无效）',
                    'default' => false,
                ),
            ),
            'dependency' => array('is_weixinpay', '==', 'true'),
        ),

        //讯虎新支付 微信
        array(
            'id'      => 'is_xunhupay_weixin',
            'type'    => 'switcher',
            'title'   => '迅虎(微信H5支付)',
            'label'   => '无需企业资质，个人用户推荐，支持电PC端扫码，移动端H5唤醒支付，微信内JSAPI支付，无资质可以用此方法完美替代*_*',
            'default' => false,
        ),
        array(
            'id'         => 'xunhupay_weixin',
            'type'       => 'fieldset',
            'title'      => '配置详情',
            'fields'     => array(
                array(
                    'type'    => 'notice',
                    'style'   => 'success',
                    'content' => '讯虎支付 <a target="_blank" href="https://admin.xunhuweb.com/register/15235553019447ebb7e54725220a7cb9">-->>注册地址</a><br/>如测试可使用测试mchid和密钥进行。但只支持0.1元付款测试<br/>',
                ),

                array(
                    'id'      => 'mchid',
                    'type'    => 'text',
                    'title'   => 'MCHID',
                    'desc'    => 'MCHID（测试：a0e5b19a8b4047c88184412997a421d1）',
                    'default' => '',
                ),
                array(
                    'id'         => 'private_key',
                    'type'       => 'text',
                    'title'      => 'Private Key',
                    'desc'       => '密钥（测试：a0c76773b8ca44ac9fa5100f5675c95f）',
                    'default'    => '',
                    'attributes' => array(
                        'type' => 'password',
                    ),
                ),
                array(
                    'id'      => 'url_do',
                    'type'    => 'text',
                    'title'   => '支付网关',
                    'desc'    => '一般不用动，如虎皮椒官方有调整手动更新即可',
                    'default' => 'https://admin.xunhuweb.com',
                ),

            ),
            'dependency' => array('is_xunhupay_weixin', '==', 'true'),
        ),

        //讯虎新支付 支付宝
        array(
            'id'      => 'is_xunhupay_alipay',
            'type'    => 'switcher',
            'title'   => '迅虎(支付宝H5支付)',
            'label'   => '无需企业资质，个人用户推荐，支持电PC端扫码，移动端H5唤醒支付，微信内JSAPI支付，无资质可以用此方法完美替代*_*',
            'default' => false,
        ),
        array(
            'id'         => 'xunhupay_alipay',
            'type'       => 'fieldset',
            'title'      => '配置详情',
            'fields'     => array(
                array(
                    'type'    => 'notice',
                    'style'   => 'success',
                    'content' => '讯虎支付 <a target="_blank" href="https://admin.xunhuweb.com/register/15235553019447ebb7e54725220a7cb9">-->>注册地址</a><br/>如测试可使用测试mchid和密钥进行。但只支持0.1元付款测试<br/>',
                ),
                array(
                    'id'      => 'mchid',
                    'type'    => 'text',
                    'title'   => 'MCHID',
                    'desc'    => 'MCHID（测试：a0e5b19a8b4047c88184412997a421d1）',
                    'default' => '',
                ),
                array(
                    'id'         => 'private_key',
                    'type'       => 'text',
                    'title'      => 'Private Key',
                    'desc'       => '密钥（测试：a0c76773b8ca44ac9fa5100f5675c95f）',
                    'default'    => '',
                    'attributes' => array(
                        'type' => 'password',
                    ),
                ),
                array(
                    'id'      => 'url_do',
                    'type'    => 'text',
                    'title'   => '支付网关',
                    'desc'    => '一般不用动，如虎皮椒官方有调整手动更新即可',
                    'default' => 'https://admin.xunhuweb.com',
                ),

            ),
            'dependency' => array('is_xunhupay_alipay', '==', 'true'),
        ),

        //虎皮椒 weixin
        array(
            'id'      => 'is_hupijiao_weixin',
            'type'    => 'switcher',
            'title'   => '虎皮椒V3(微信)',
            'label'   => '当前是最新的虎皮椒V3版，微信完美收款，无资质可以用此方法完美替代*_*',
            'default' => false,
        ),
        array(
            'id'         => 'hupijiao_weixin',
            'type'       => 'fieldset',
            'title'      => '配置详情',
            'fields'     => array(
                array(
                    'type'    => 'notice',
                    'style'   => 'success',
                    'content' => '虎皮椒V3  <a target="_blank" href="https://admin.xunhupay.com/sign-up/4123.html">注册地址</a><br/>如测试可使用测试APPID和密钥进行。但只支持0.1元付款测试<br/>',
                ),
                array(
                    'id'      => 'appid',
                    'type'    => 'text',
                    'title'   => 'APPID',
                    'desc'    => 'APPID（测试：201906130530）',
                    'default' => '',
                ),
                array(
                    'id'         => 'appsecret',
                    'type'       => 'text',
                    'title'      => 'APPSECRET',
                    'desc'       => '密钥（测试：e97a75d2ee14e353fa745f7c47d23ed0）',
                    'default'    => '',
                    'attributes' => array(
                        'type' => 'password',
                    ),
                ),
                array(
                    'id'      => 'url_do',
                    'type'    => 'text',
                    'title'   => '支付网关',
                    'desc'    => '一般不用动，如虎皮椒官方有调整手动更新即可',
                    'default' => 'https://api.xunhupay.com/payment/do.html',
                ),

            ),
            'dependency' => array('is_hupijiao_weixin', '==', 'true'),
        ),

        //虎皮椒 alpay
        array(
            'id'      => 'is_hupijiao_alipay',
            'type'    => 'switcher',
            'title'   => '虎皮椒V3(支付宝)',
            'label'   => '当前是最新的虎皮椒V3版，支付宝完美收款，无资质可以用此方法完美替代*_*',
            'default' => false,
        ),
        array(
            'id'         => 'hupijiao_alipay',
            'type'       => 'fieldset',
            'title'      => '配置详情',
            'fields'     => array(
                array(
                    'type'    => 'notice',
                    'style'   => 'success',
                    'content' => '虎皮椒（讯虎支付）V3  <a target="_blank" href="https://admin.xunhupay.com/sign-up/4123.html">注册地址</a><br/>如测试可使用测试APPID和密钥进行。但只支持0.1元付款测试<br/>',
                ),
                array(
                    'id'      => 'appid',
                    'type'    => 'text',
                    'title'   => 'APPID',
                    'desc'    => 'APPID',
                    'default' => '',
                ),
                array(
                    'id'         => 'appsecret',
                    'type'       => 'text',
                    'title'      => 'APPSECRET',
                    'desc'       => '密钥',
                    'default'    => '',
                    'attributes' => array(
                        'type' => 'password',
                    ),
                ),
                array(
                    'id'      => 'url_do',
                    'type'    => 'text',
                    'title'   => '支付网关',
                    'desc'    => '一般不用动，如虎皮椒官方有调整手动更新即可',
                    'default' => 'https://api.xunhupay.com/payment/do.html',
                ),

            ),
            'dependency' => array('is_hupijiao_alipay', '==', 'true'),
        ),

        //payjs 
        array(
            'id'      => 'is_payjs_weixin',
            'type'    => 'switcher',
            'title'   => 'PAYJS(微信)',
            'label'   => 'PAYJS 微信、支付宝支付个人接口旨在解决需要使用交易数据流的个人开发者、个人创业者、个体户等小微支付需求',
            'default' => false,
        ),
        array(
            'id'      => 'is_payjs_alipay',
            'type'    => 'switcher',
            'title'   => 'PAYJS(支付宝)',
            'label'   => '如果有支付宝通道开通，则开启此项，秘钥与微信一致，无需重复设置',
            'default' => false,
        ),
        array(
            'id'         => 'payjs_config',
            'type'       => 'fieldset',
            'title'      => '配置详情',
            'fields'     => array(
                array(
                    'type'    => 'notice',
                    'style'   => 'success',
                    'content' => 'PAYJS 只负责信息流，不接管资金流。资金无任何风险，官方网站：https://payjs.cn',
                ),
                array(
                    'id'      => 'mchid',
                    'type'    => 'text',
                    'title'   => '商户号',
                    'desc'    => 'mchid',
                    'default' => '12323412323',
                ),
                array(
                    'id'         => 'key',
                    'type'       => 'text',
                    'title'      => '通信密钥',
                    'desc'       => '密钥',
                    'default'    => 'sadfsaddsaf',
                    'attributes' => array(
                        'type' => 'password',
                    ),
                ),

            ),
            'dependency' => array( 'is_payjs_weixin', '==', 'true' ),
        ),


         // 易支付-支付宝
        array(
            'id'      => 'is_epay_alipay',
            'type'    => 'switcher',
            'title'   => '易支付(支付宝通道)',
            'label'   => '易支付(支付宝通道)',
            'default' => false,
        ),

        array(
            'id'         => 'epay_alipay',
            'type'       => 'fieldset',
            'title'      => '配置详情',
            'fields'     => array(
                array(
                    'id'      => 'partner',
                    'type'    => 'text',
                    'title'   => '商户ID',
                    'desc'    => '',
                    'default' => '',
                ),
                array(
                    'id'         => 'key',
                    'type'       => 'text',
                    'title'      => '商户KEY',
                    'desc'       => '',
                    'default'    => '',
                    'attributes' => array(
                        'type' => 'password',
                    ),
                ),
                array(
                    'id'      => 'apiurl',
                    'type'    => 'text',
                    'title'   => '支付API地址',
                    'desc'    => '请填写你的易支付-接口地址,格式为:http[s]://www.xxxxx.xx/记得协议和最后的/别少',
                    'default' => '',
                ),

            ),
            'dependency' => array('is_epay_alipay', '==', 'true'),
        ),

        // 易支付-微信
        array(
            'id'      => 'is_epay_weixin',
            'type'    => 'switcher',
            'title'   => '易支付(微信通道)',
            'label'   => '易支付(微信通道)',
            'default' => false,
        ),

        array(
            'id'         => 'epay_weixin',
            'type'       => 'fieldset',
            'title'      => '配置详情',
            'fields'     => array(
                array(
                    'id'      => 'partner',
                    'type'    => 'text',
                    'title'   => '商户ID',
                    'desc'    => '',
                    'default' => '',
                ),
                array(
                    'id'         => 'key',
                    'type'       => 'text',
                    'title'      => '商户KEY',
                    'desc'       => '',
                    'default'    => '',
                    'attributes' => array(
                        'type' => 'password',
                    ),
                ),
                array(
                    'id'      => 'apiurl',
                    'type'    => 'text',
                    'title'   => '支付API地址',
                    'desc'    => '请填写你的易支付-接口地址,格式为:http[s]://www.xxxxx.xx/记得协议和最后的/别少',
                    'default' => '',
                ),

            ),
            'dependency' => array('is_epay_weixin', '==', 'true'),
        ),

        




        //paypal
        array(
            'id'      => 'is_paypal',
            'type'    => 'switcher',
            'title'   => 'PayPal（贝宝）',
            'label'   => '国际支付',
            'default' => false,
        ),
        array(
            'id'         => 'paypal',
            'type'       => 'fieldset',
            'title'      => '配置详情',
            'fields'     => array(
                array(
                    'type'    => 'notice',
                    'style'   => 'success',
                    'content' => '查看你的paypal秘钥信息：https://www.paypal.com/businessprofile/mytools/apiaccess/firstparty/signature',
                ),
                array(
                    'id'      => 'username',
                    'type'    => 'text',
                    'title'   => 'API用户名',
                    'desc'    => '',
                    'default' => '',
                ),
                array(
                    'id'         => 'password',
                    'type'       => 'text',
                    'title'      => 'API密码',
                    'desc'       => '',
                    'default'    => '',
                    'attributes' => array(
                        'type' => 'password',
                    ),
                ),
                array(
                    'id'         => 'signature',
                    'type'       => 'text',
                    'title'      => '签名',
                    'desc'       => '',
                    'default'    => '',
                    'attributes' => array(
                        'type' => 'password',
                    ),
                ),
                array(
                    'id'         => 'currency',
                    'type'       => 'text',
                    'title'      => '结算货币',
                    'desc'       => '列如(USD, GBP, CZK 等)',
                    'default'    => 'USD',
                ),
                array(
                    'id'         => 'rates',
                    'type'       => 'text',
                    'title'      => '货币汇率',
                    'desc'       => '1RMB等于多少目标货币',
                    'default'    => '6.47',
                ),

                array(
                    'id'      => 'debug',
                    'type'    => 'switcher',
                    'title'   => '沙盒调试模式',
                    'label'   => '',
                    'default' => false,
                ),

            ),
            'dependency' => array('is_paypal', '==', 'true'),
        ),


    ),
));

// 商城-文章付费资源设置
CSF::createSection($prefix, array(
    'parent' => 'shop_fields',
    'title'  => '发布字段默认配置',
    'icon'   => 'fa fa-user',
    'fields' => array(


        array(
            'id'          => 'cao_price',
            'type'        => 'number',
            'title'       => '价格：*',
            'desc'        => '免费请填写：0',
            'unit'        => site_mycoin('name'),
            'output'      => '.heading',
            'output_mode' => 'width',
            'default'     => 0.1,
        ),

        array(
            'id'          => 'cao_vip_rate',
            'type'        => 'number',
            'title'       => '会员折扣：*',
            'desc'        => '0.N 等于N折；1 等于不打折；0 等于会员免费',
            'unit'        => '.N折',
            'output'      => '.heading',
            'output_mode' => 'width',
            'default'     => 1,
        ),

        array(
            'id'      => 'cao_close_novip_pay',
            'type'    => 'checkbox',
            'title'   => '普通用户禁止购买',
            'default' => false,
            'label'   => '勾选后普通用户不能下单支付，只允许会员可以购买',
        ),
        array(
            'id'      => 'cao_is_boosvip',
            'type'    => 'checkbox',
            'title'   => '永久会员免费',
            'label'   => '勾选后永久会员免费，其他会员按折扣或者原价购买',
            'default' => false,
        ),
        array(
            'id'          => 'cao_expire_day',
            'type'        => 'number',
            'title'       => '购买有效期天数',
            'desc'        => '0 无限期；N天后失效需要重新购买',
            'unit'        => '天',
            'output'      => '.heading',
            'output_mode' => 'width',
            'default'     => 0,
        ),

        array(
            'id'      => 'cao_status',
            'type'    => 'switcher',
            'title'   => '启用付费下载模块',
            'label'   => '开启后可设置付费下载专有内容',
            'default' => false,
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
            'default'                => '',
            'dependency'             => array('cao_status', '==', 'true'),
        ),

        array(
            'id'         => 'cao_demourl',
            'type'       => 'text',
            'title'      => '演示地址',
            'label'      => '为空则不显示',
            'default'    => '',
            'dependency' => array('cao_status', '==', 'true'),
        ),

        array(
            'id'         => 'cao_diy_btn',
            'type'       => 'text',
            'title'      => '自定义按钮',
            'subtitle'   => '为空则不显示，用 | 隔开',
            'desc'       => '格式： 下载免费版|https://www.baidu.com/',
            'default'    => '',
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
            'default'     => 0,
        ),

       
    ),
));


CSF::createSection($prefix, array(
    'title'  => '登录注册',
    'icon'   => 'fa fa-circle',
    'fields' => array(
        array(
            'id'      => 'site_login_security_key',
            'type'    => 'text',
            'title'   => 'WP登录页面安全参数',
            'desc'   => '(英文)建议设置，安全加强，防止普通用户访问wp自带后台和登录页面，留空则不开启，设置开启后您的真实登录地址是<b style="color:red;">'.esc_url(home_url('/wp-login.php')).'?参数=秘钥</b>',
            'default' => '',
        ),
        array(
            'id'      => 'site_login_security_param',
            'type'    => 'text',
            'title'   => 'WP登录页面安全秘钥',
            'desc'   => '（英文/数字）设置开启后您的真实登录地址是<b style="color:red;">'.esc_url(home_url('/wp-login.php')).'?参数=秘钥</b>。<br>设置后请牢记自己的参数和秘钥，如果忘记需要手动去主题目录/inc/template-filter.php文件大概300行+找到'.esc_html('add_action(\'login_enqueue_scripts\',\'ripro_v2_login_protection\');').'这行代码注释或者删除才可以登录',
            'default' => '',
        ),
        
        array(
            'id'      => 'site_login_reg_href1',
            'type'    => 'text',
            'title'   => '用户协议页面地址',
            'default' => '#',
        ),
        array(
            'id'      => 'site_login_reg_href2',
            'type'    => 'text',
            'title'   => '隐私政策页面地址',
            'default' => '#',
        ),

        array(
            'id'      => 'site_profile_bg_img',
            'type'    => 'upload',
            'title'   => '个人中心页顶部背景',
            'dsec'    => '',
            'default' => get_template_directory_uri() . '/assets/img/bg.jpg',
        ),

        array(
            'id'      => 'is_site_register_bind_email',
            'type'    => 'switcher',
            'title'   => '第三方登录新用户注册必须绑定邮箱',
            'desc'    => '开启后将输入邮箱验证，第三方登录新用户注册必须绑定邮箱账号',
            'default' => false,
        ),

        array(
            'id'      => 'is_site_email_captcha_verify',
            'type'    => 'switcher',
            'title'   => '邮箱验证码',
            'desc'    => '<b style="color:red;">网站需配置smtp发信</b> 启用后绑定/更换邮箱/等敏感操作需要邮箱验证码确认，保证账号安全',
            'default' => false,
        ),

        array(
            'id'      => 'is_qq_007_captcha',
            'type'    => 'switcher',
            'title'   => '腾讯云验证码防水墙',
            'desc'    => '同时兼容原腾讯007防水墙和腾讯云验证码，启用后登录，注册，绑定等敏感操作需要安全验证，可以有效防止恶意注册登录爆破，<br>注册地址：https://console.cloud.tencent.com/captcha',
            'default' => false,
        ),
        array(
            'id'         => 'qq_007_captcha_appid',
            'type'       => 'text',
            'title'      => 'App ID',
            'desc'       => '查看地址：https://007.qq.com/captcha/?ADTAG=acces.head#/gettingStart',
            'dependency' => array('is_qq_007_captcha', '==', 'true'),
            'default'    => false,
        ),
        array(
            'id'         => 'qq_007_captcha_appkey',
            'type'       => 'text',
            'title'      => 'App Secret Key',
            'attributes' => array(
                'type' => 'password',
            ),
            'desc'       => '查看地址：https://007.qq.com/captcha/?ADTAG=acces.head#/gettingStart',
            'dependency' => array('is_qq_007_captcha', '==', 'true'),
        ),

        array(
            'id'      => 'is_site_user_login',
            'type'    => 'switcher',
            'title'   => '启用登录模块',
            'default' => true,
        ),

        array(
            'id'      => 'is_site_user_register',
            'type'    => 'switcher',
            'title'   => '启用注册模块',
            'default' => true,
        ),

        array(
            'id'      => 'is_sns_qq',
            'type'    => 'switcher',
            'title'   => 'QQ登录',
            'label'   => '申请地址：https://connect.qq.com/',
            'default' => _cao_old('is_oauth_qq'),
        ),
        array(
            'id'         => 'sns_qq',
            'type'       => 'fieldset',
            'title'      => '配置详情',
            'fields'     => array(
                array(
                    'id'      => 'app_id',
                    'type'    => 'text',
                    'title'   => 'Appid',
                    'default' => (isset(_cao_old('oauth_qq')['appid'])) ? _cao_old('oauth_qq')['appid'] : '',
                ),
                array(
                    'id'      => 'app_secret',
                    'type'    => 'text',
                    'title'   => 'Appkey',
                    'default' => (isset(_cao_old('oauth_qq')['appkey'])) ? _cao_old('oauth_qq')['appkey'] : '',
                ),
                array(
                    'type'    => 'subheading',
                    'content' => '回调地址：' . esc_url(home_url('/oauth/qq/callback')),
                ),
            ),
            'dependency' => array('is_sns_qq', '==', 'true'),
        ),
        array(
            'id'      => 'is_sns_weixin',
            'type'    => 'switcher',
            'title'   => '微信登录',
            'label'   => '申请地址：https://open.weixin.qq.com/',
            'default' => false,
        ),
        array(
            'id'         => 'sns_weixin',
            'type'       => 'fieldset',
            'title'      => '配置详情',
            'fields'     => array(
                // 微信登陆模式
                array(
                    'id'          => 'sns_weixin_mod',
                    'type'        => 'select',
                    'title'       => '微信登陆模式',
                    'placeholder' => '',
                    'options'     => array(
                        'open' => '微信开放平台',
                        'mp'   => '微信公众号（认证服务号）',
                    ),
                    'default'     => 'mp',
                    'desc'        => '推荐使用公众号模式，因微信官方openid和unionid模式错综复杂，建议不要中途更换模式',
                ),

                array(
                    'id'         => 'app_id',
                    'type'       => 'text',
                    'title'      => '开放平台 Appid',
                    'default'    => '',
                    'default' => (isset(_cao_old('oauth_weixin')['appid'])) ? _cao_old('oauth_weixin')['appid'] : '',
                    'dependency' => array('sns_weixin_mod', '==', 'open'),
                ),
                array(
                    'id'         => 'app_secret',
                    'type'       => 'text',
                    'title'      => '开放平台 AppSecret',
                    'default' => (isset(_cao_old('oauth_weixin')['appkey'])) ? _cao_old('oauth_weixin')['appkey'] : '',
                    'dependency' => array('sns_weixin_mod', '==', 'open'),
                ),
                array(
                    'type'       => 'subheading',
                    'content'    => '开放平台-应用官网：' . home_url() . '<br>开放平台-授权回调域：' . parse_url(home_url(), PHP_URL_HOST),
                    'dependency' => array('sns_weixin_mod', '==', 'open'),
                ),
                array(
                    'id'         => 'mp_app_id',
                    'type'       => 'text',
                    'title'      => '公众号 Appid',
                    'default' => (isset(_cao_old('oauth_mpweixin')['mp_appid'])) ? _cao_old('oauth_mpweixin')['mp_appid'] : '',
                    'dependency' => array('sns_weixin_mod', '==', 'mp'),
                ),
                array(
                    'id'         => 'mp_app_secret',
                    'type'       => 'text',
                    'title'      => '公众号 AppSecret',
                    'default' => (isset(_cao_old('oauth_mpweixin')['mp_appsecret'])) ? _cao_old('oauth_mpweixin')['mp_appsecret'] : '',
                    'dependency' => array('sns_weixin_mod', '==', 'mp'),
                ),
                array(
                    'id'         => 'mp_app_token',
                    'type'       => 'text',
                    'title'      => '公众号配置 token',
                    'default' => (isset(_cao_old('oauth_mpweixin')['mp_token'])) ? _cao_old('oauth_mpweixin')['mp_token'] : '',
                    'desc'       => '填写-公众号后台->基本配置->服务器配置->令牌(Token)',
                    'dependency' => array('sns_weixin_mod', '==', 'mp'),
                ),
                array(
                    'id'         => 'is_mp_bind_open',
                    'type'       => 'switcher',
                    'title'      => '公众号已经绑定开放平台',
                    'label'      => '申请地址：https://open.weixin.qq.com/',
                    'default'    => false,
                    'dependency' => array('sns_weixin_mod', '==', 'mp'),
                ),

                //自定义公众号菜单
                array(
                    'id'         => 'custom_wxmenu_opt',
                    'type'       => 'group',
                    'title'      => '自定义公众号菜单',
                    'max'        => '3',
                    'fields'     => array(

                        array(
                            'id'      => 'name',
                            'type'    => 'text',
                            'title'   => '菜单名称',
                            'default' => '首页',
                        ),
                        array(
                            'id'      => 'url',
                            'type'    => 'text',
                            'title'   => '链接',
                            'default' => '',
                        ),

                        array(
                            'id'     => 'sub_button',
                            'type'   => 'group',
                            'title'  => '二级菜单',
                            'max'    => '3',
                            'fields' => array(
                                array(
                                    'id'      => 'name',
                                    'type'    => 'text',
                                    'title'   => '菜单名称',
                                    'default' => '二级菜单',
                                ),
                                array(
                                    'id'      => 'url',
                                    'type'    => 'text',
                                    'title'   => '链接',
                                    'default' => '',
                                ),
                            ),
                        ),

                    ),
                    'dependency' => array('sns_weixin_mod', '==', 'mp'),
                ),
                array(
                    'type'       => 'subheading',
                    'content'    => '保存好后刷新公众号菜单：<button type="button" class="rest_mpweixin_menu">点击刷新公众号菜单</button>',
                    'dependency' => array('sns_weixin_mod', '==', 'mp'),
                ),

                array(
                    'type'       => 'subheading',
                    'content'    => '1，公众号设置->功能设置->网页授权域名填写：' . parse_url(home_url(), PHP_URL_HOST) . '</br> 2，基本配置->服务器配置->服务器地址(URL)填写：' . home_url('/oauth/mpweixin/callback') . '</br>3，基本配置->服务器配置->明文模式，启用配置即可',
                    'dependency' => array('sns_weixin_mod', '==', 'mp'),
                ),
            ),
            'dependency' => array('is_sns_weixin', '==', 'true'),
        ),
        array(
            'id'      => 'is_sns_weibo',
            'type'    => 'switcher',
            'title'   => '微博登录',
            'label'   => '申请地址：https://open.weibo.com/authentication/',
            'default' => false,
        ),
        array(
            'id'         => 'sns_weibo',
            'type'       => 'fieldset',
            'title'      => '配置详情',
            'fields'     => array(
                array(
                    'id'      => 'app_id',
                    'type'    => 'text',
                    'title'   => 'Appid',
                    'default' => '',
                ),
                array(
                    'id'      => 'app_secret',
                    'type'    => 'text',
                    'title'   => 'Appkey',
                    'default' => '',
                ),
                array(
                    'type'    => 'subheading',
                    'content' => '回调地址：' . esc_url(home_url('/oauth/weibo/callback')),
                ),
            ),
            'dependency' => array('is_sns_weibo', '==', 'true'),
        ),

    ),
));

CSF::createSection($prefix, array(
    'title'  => '底部设置',
    'icon'   => 'fa fa-circle',
    'fields' => array(

        // rollbar
        array(
            'id'      => 'site_footer_rollbar',
            'type'    => 'group',
            'title'   => 'PC端全站右下角菜单（返回顶部+）',
            'max'     => '10',
            'fields'  => array(
                array(
                    'id'      => 'title',
                    'type'    => 'text',
                    'title'   => '菜单名称',
                    'default' => '首页',
                ),
                array(
                    'id'    => 'icon',
                    'type'  => 'icon',
                    'title' => '图标',
                    'default' => 'fas fa-bars',
                ),
                array(
                    'id'      => 'is_blank',
                    'type'    => 'switcher',
                    'title'   => '新窗口打开',
                    'default' => true,
                ),
                array(
                    'id'      => 'href',
                    'type'    => 'text',
                    'title'   => '链接地址',
                    'desc'    => '比如用户中心，填写' . home_url('/user'),
                    'default' => home_url(),
                ),

            ),
            'default' => array(
                array(
                    'title' => '首页',
                    'icon'  => 'fas fa-home',
                    'href'  => home_url('/'),
                ),
                array(
                    'title' => '云服务器推荐',
                    'icon'  => 'far fa-hdd',
                    'href'  => 'https://www.aliyun.com/minisite/goods?userCode=u4kxbrjo',
                ),
                array(
                    'title' => 'VIP会员',
                    'icon'  => 'fa fa-diamond',
                    'href'  => home_url('/user?action=vip'),
                ),
                array(
                    'title' => '个人中心',
                    'icon'  => 'far fa-user',
                    'href'  => home_url('/user'),
                ),
                array(
                    'title' => '<b>在线客服</b> <u>9:00~21:00</u>',
                    'icon'  => 'fab fa-qq',
                    'href'  => 'http://wpa.qq.com/msgrd?v=3&uin=6666666&site=qq&menu=yes',
                ),
            ),
        ),

         array(
            'id'      => 'site_footer_menu',
            'type'    => 'group',
            'title'   => '手机端底部菜单栏',
            'max'     => '4',
            'fields'  => array(
                array(
                    'id'      => 'title',
                    'type'    => 'text',
                    'title'   => '菜单名称',
                    'default' => '首页',
                ),
                array(
                    'id'    => 'icon',
                    'type'  => 'icon',
                    'title' => '图标',
                ),
                array(
                    'id'      => 'is_blank',
                    'type'    => 'switcher',
                    'title'   => '新窗口打开',
                    'default' => false,
                ),
                array(
                    'id'      => 'href',
                    'type'    => 'text',
                    'title'   => '链接地址',
                    'desc'    => '比如用户中心，填写' . home_url('/user'),
                    'default' => home_url(),
                ),

            ),
            'default' => array(
                array(
                    'title' => '首页',
                    'icon'  => 'fas fa-home',
                    'href'  => home_url(),
                    'is_blank'  => false,
                ),
                array(
                    'title' => '分类',
                    'icon'  => 'fas fa-layer-group',
                    'href'  => home_url('/uncategorized'),
                    'is_blank'  => false,
                ),
                array(
                    'title' => '问答',
                    'icon'  => 'fab fa-ello',
                    'href'  => home_url('/question'),
                    'is_blank'  => false,
                ),
                array(
                    'title' => '我的',
                    'icon'  => 'fas fa-user',
                    'href'  => home_url('/user'),
                    'is_blank'  => false,
                ),
            ),
        ),

        array(
            'id'      => 'is_site_footer_widget',
            'type'    => 'switcher',
            'title'   => '是否开启底部自定义小工具模块',
            'desc'    => '在外观-小工具添加底部小工具即可',
            'default' => false,
        ),

        array(
            'id'         => 'site_footer_logo',
            'type'       => 'upload',
            'title'      => '底部LOGO',
            'default'    => get_template_directory_uri() . '/assets/img/logo.png',
            'dependency' => array('is_site_footer_widget', '==', 'true'),
        ),
        array(
            'id'         => 'site_footer_desc',
            'type'       => 'textarea',
            'sanitize'   => false,
            'title'      => '底部LOGO下文字介绍',
            'subtitle'   => '自定义文字介绍',
            'default'    => 'RiPro-V2是一款全新架构的Wordpress主题，兼容老款ripro，支持会员商城 ，前后台界面均支持html5响应式布局，夜间模式一键切换。',
            'dependency' => array('is_site_footer_widget', '==', 'true'),
        ),

        array(
            'id'          => 'site_footer_links',
            'type'        => 'select',
            'title'       => '网站底部友情链接',
            'desc'       => '请在WP后台-链接-添加链接分类，并且添加链接，则可以显示选项选择某个分类下的链接，方便管理',
            'placeholder' => '不开启',
            'options'     => 'categories',
            'query_args'  => array(
                'taxonomy' => 'link_category',
            ),
        ),

        array(
            'id'      => 'site_footer_links_ishome',
            'type'    => 'switcher',
            'title'   => '友情链接仅在首页显示',
            'desc'    => '网站打不开问题',
            'default' => true,
        ),

        array(
            'id'       => 'site_copyright_text',
            'type'     => 'textarea',
            'title'    => '底部版权信息',
            'sanitize' => false,
            'subtitle' => '自定义版权信息',
            'default'  => 'Copyright © 2021 <a href="http://ritheme.com/">RiPro-V2</a> - All rights reserved',
        ),
        array(
            'id'       => 'site_ipc_text',
            'type'     => 'textarea',
            'sanitize' => false,
            'title'    => '网站备案链接',
            'subtitle' => '',
            'default'  => '<a href="https://beian.miit.gov.cn" target="_blank" rel="noreferrer nofollow">京ICP备18888888号-1</a>',
        ),
        array(
            'id'       => 'site_ipc2_text',
            'type'     => 'textarea',
            'sanitize' => false,
            'title'    => '网站公安备案链接',
            'subtitle' => '',
            'default'  => '<a href="http://www.beian.gov.cn/portal/registerSystemInfo?recordcode=188888888" target="_blank" rel="noreferrer nofollow">京公网安备 188888888</a>',
        ),

        array(
            'id'       => 'web_js',
            'type'     => 'textarea',
            'title'    => '网站底部自定义JS代码',
            'desc'     => '位于底部，用于添加第三方流量数据统计代码，如：Google analytics、百度统计、CNZZ,例如：' . esc_attr('<script>统计代码</script>'),
            'sanitize' => false,
            'default'  => '',
        ),

    ),
));

CSF::createSection($prefix, array(
    'title'  => '搜索设置',
    'icon'   => 'fa fa-circle',
    'fields' => array(

        array(
            'id'      => 'is_search_loging',
            'type'    => 'switcher',
            'title'   => '仅登录用户可以搜索',
            'desc'    => '开启后仅登录用户可以搜索，否则不能搜索',
            'default' => false,
        ),

        array(
            'id'      => 'is_search_title_only',
            'type'    => 'switcher',
            'title'   => '站内搜索只搜索标题',
            'desc'    => '优化用户搜索关键词时只通过文章标题进行搜索查询，如果您的网站文章数量很多，搜索很卡，可以开启此功能提升百分之70的搜索性能，但搜索精准度只搜索文章标题是否有关键词，不对文章内容进行搜索，具体效果以网站实际搜索效果为准',
            'default' => false,
        ),


        array(
            'id'      => 'is_search_limit',
            'type'    => 'switcher',
            'title'   => '搜索频率限制',
            'desc'    => '特别说明：此功能需要依赖Session，php的Session储存根据环境不同速度不一样，开启后可能会导致用户在短时间内搜索卡顿，属于正常现象，因为没有超过时间，搜索频率限制功能比较实用，可以有效防止恶意cc恶意搜索导致CPU负载100%网站打不开问题',
            'default' => false,
        ),

        array(
            'id'    => 'search_limit_time',
            'type'  => 'text',
            'title' => '搜索限制时间(秒)',
            'desc' => '每间隔多少秒才可以搜索一次',
            'default' => '60',
            'dependency' => array('is_search_limit', '==', 'true'),
        ),

        array(
            'id'    => 'search_limit_msg',
            'type'  => 'text',
            'title' => '超出搜索次数后提示文字',
            'default' => '一分钟内只能搜索一次，切勿频繁搜索',
            'dependency' => array('is_search_limit', '==', 'true'),
        ),


    ),
));

//
// 广告设置
//

$ripro_ads_opt = array(
    'ad_archive_top' => '分类筛选页-顶部',
    'ad_archive_bottum' => '分类筛选页-底部',
    'ad_single_top'  => '文章内页-顶部',
    'ad_single_bottum'  => '文章内页-底部',
    'ad_omnisearch_bottum'  => '搜索弹出-底部',
);
$ripro_ads_opt_c = [];
foreach ($ripro_ads_opt as $slug => $name) {
    // 是否开启
    $ripro_ads_opt_c[] = array(
        'id'      => $slug,
        'type'    => 'switcher',
        'title'   => $name,
        'default' => false,
    );
    $ripro_ads_opt_c[] = array(
        'id'         => $slug . '_pc',
        'type'       => 'textarea',
        'title'      => '电脑端广告代码',
        'default'    => '<a href="https://ritheme.com/" target="_blank" rel="nofollow noopener noreferrer" data-toggle="tooltip" data-html="true" title="<u>广告：</u>多款wordpress正版主题打包仅需499"><img src="' . get_template_directory_uri() . '/assets/img/ads.jpg" style=" width: 100%; "></a>',
        'dependency' => array($slug, '==', 'true'),
        'sanitize' => false,
    );
    $ripro_ads_opt_c[] = array(
        'id'         => $slug . '_mobile',
        'type'       => 'textarea',
        'title'      => '手机端广告代码',
        'default'    => '<a href="https://ritheme.com/" target="_blank" rel="nofollow noopener noreferrer" data-toggle="tooltip" data-html="true" title="<u>广告：</u>多款wordpress正版主题打包仅需499"><img src="' . get_template_directory_uri() . '/assets/img/ads.jpg" style=" width: 100%; "></a>',
        'dependency' => array($slug, '==', 'true'),
        'sanitize' => false,
    );

}

CSF::createSection($prefix, array(
    'title'  => '广告设置',
    'icon'   => 'fa fa-legal',
    'fields' => $ripro_ads_opt_c,
));






CSF::createSection($prefix, array(
    'title'  => '邮件设置',
    'icon'   => 'fa fa-circle',
    'description' => 'SMTP设置可以解决wordpress无法发送邮件问题，建议用QQ邮箱，<br>简单说一下如何开启邮箱IMAP/SMTP服务和获得第三方授权码。<br>登录你的QQ邮箱，依次点击，设置 → 账户，找到“POP3/IMAP/SMTP/Exchange/CardDAV/CalDAV服务”设置选项，开启邮箱“IMAP/SMTP服务”。<br>点击下面的“生成授权码 ”，按要求发送短信：配置邮件客户端，到指定的号码，之后点击“我已发送”，会自动生一个授权码，要记好这个授权码，因为只显示一次，没记住只能再次发送短信了，将这个授权码填写到配置信息中即可。<br>注：貌似目前所有邮箱端口都可以设置为465，都支持ssl加密',
    'fields' => array(

        array(
            'id'      => 'is_site_smtp',
            'type'    => 'switcher',
            'title'   => '启用SMTP服务',
            'default' => '该设置主题自带，不能与插件重复开启,如果自带smtp无法使用，请使用smtp插件',
            'default' => _cao_old('mail_smtps'),
        ),

        array(
            'id'       => 'smtp_mail_name',
            'type'     => 'text',
            'title'    => '发信邮箱',
            'subtitle' => '请填写发件人邮箱帐号',
            'default'  => _cao_old('mail_name',''),
            // 'validate' => 'csf_validate_email',
        ),

        array(
            'id'       => 'smtp_mail_nicname',
            'type'     => 'text',
            'title'    => '发信人昵称',
            'subtitle' => '昵称',
            'default'  => _cao_old('mail_nicname','Ripro主题'),
        ),
        array(
            'id'       => 'smtp_mail_host',
            'type'     => 'text',
            'title'    => '邮件服务器',
            'subtitle' => '请填写SMTP服务器地址',
            'default'  => _cao_old('mail_host','smtp.qq.com'),
        ),
        array(
            'id'       => 'smtp_mail_port',
            'type'     => 'text',
            'title'    => '服务器端口',
            'subtitle' => '请填写SMTP服务器端口',
            'default'  => _cao_old('mail_port','465'),
        ),
        array(
            'id'       => 'smtp_mail_passwd',
            'type'     => 'text',
            'title'    => '邮箱密码',
            'subtitle' => '请填写SMTP服务器邮箱密码，特别注意：QQ邮箱的密码在账户设置，最底下，是独立生成的授权码，而不是qq密码和邮箱密码',
            'default'  => _cao_old('mail_passwd',''),
            'attributes'  => array(
                'type'      => 'password',
                'autocomplete' => 'off',
            ),
        ),
        array(
            'id'      => 'smtp_mail_smtpauth',
            'type'    => 'switcher',
            'title'   => '启用SMTPAuth服务',
            'label'   => '启用SMTPAuth服务',
            'default' => _cao_old('mail_smtpauth',true),
        ),
        array(
            'id'       => 'smtp_mail_smtpsecure',
            'type'     => 'text',
            'title'    => 'SMTPSecure设置',
            'subtitle' => '若启用SMTPAuth服务则填写ssl，若不启用则留空',
            'default'  => _cao_old('mail_smtpsecure','ssl'),
        ),

        // 邮件模板配置
        array(
            'id'      => 'is_site_mail_tpl',
            'type'    => 'switcher',
            'title'   => '启用自带邮件美化模板',
            'default' => true,
        ),

        array(
            'id'       => 'mail_more_content',
            'type'     => 'text',
            'title'    => '邮件美化模板底部自定义内容',
            'subtitle' => '',
            'default'  => '此邮件为系统通知邮件，切勿直接回复',
            'dependency' => array('is_site_mail_tpl', '==', 'true'),
        ),

        

    ),
));



CSF::createSection($prefix, array(
    'title'  => 'WP优化',
    'icon'   => 'fa fa-wordpress',
    'fields' => array(

        array(
            'id'      => 'disable_gutenberg_edit',
            'type'    => 'switcher',
            'title'   => '古滕堡编辑器',
            'default' => false,
        ),

        array(
            'id'      => 'disable_gutenberg_widgets',
            'type'    => 'switcher',
            'title'   => '古滕堡小工具',
            'default' => false,
        ),


        array(
            'id'      => 'show_admin_bar',
            'type'    => 'switcher',
            'title'   => '移除前端顶部管理栏',
            'desc'    => '',
            'default' => true,
        ),

        array(
            'id'      => 'remove_admin_bar_menu',
            'type'    => 'switcher',
            'title'   => '移除WP后台顶部LOGO等不常用菜单链接',
            'desc'    => '',
            'default' => true,
        ),

        array(
            'id'      => 'remove_admin_foote_wp',
            'type'    => 'switcher',
            'title'   => '移除wp后台底部版本信息',
            'desc'    => '',
            'default' => true,
        ),

        array(
            'id'      => 'remove_admin_menu',
            'type'    => 'switcher',
            'title'   => '移除WP后台仪表盘菜单',
            'desc'    => '',
            'default' => false,
        ),

        array(
            'id'      => 'remove_emoji',
            'type'    => 'switcher',
            'title'   => '移除WP自带emoji表情插件',
            'desc'    => '可以大幅度精简JS和CSS',
            'default' => true,
        ),

        array(
            'id'      => 'disable_open_sans',
            'type'    => 'switcher',
            'title'   => '禁用Google Open Sans字体',
            'desc'    => '加速后台',
            'default' => true,
        ),

        array(
            'id'      => 'remove_wp_head_more',
            'type'    => 'switcher',
            'title'   => '精简优化网站前台head标签代码',
            'desc'    => '',
            'default' => true,
        ),

        array(
            'id'      => 'remove_wp_img_attributes',
            'type'    => 'switcher',
            'title'   => '精简优化网站图片代码',
            'desc'    => '移除wp自带编辑器插入图片时一堆不必要的html属性和元素',
            'default' => false,
        ),

        array(
            'id'      => 'remove_wp_rest_api',
            'type'    => 'switcher',
            'title'   => '关闭网站REST API接口',
            'desc'    => '如果你有使用小程序等功能，请不要优化此项',
            'default' => false,
        ),
        array(
            'id'      => 'remove_wp_xmlrpc',
            'type'    => 'switcher',
            'title'   => '关闭XML-RPC (pingback) 功能',
            'desc'    => 'XML-RPC 是 WordPress 用于第三方客户端，关闭后可以防止爆破攻击',
            'default' => true,
        ),

        array(
            'id'      => 'remove_site_search',
            'type'    => 'switcher',
            'title'   => '关闭网站搜索功能',
            'desc'    => '关闭后全站前台无法搜索文章，可以有效防止爆破，数据库堵塞',
            'default' => false,
        ),

    ),
));


CSF::createSection($prefix, array(
    'title'  => '插件推荐',
    'icon'   => 'fa fa-wordpress',
    'description' => '推荐一些轻量常用好用的插件，与ripri-v2完美配合，助您快速完善，部分插件直接跳转到插件页面，直接复制插件名称搜索即可',
    'fields' => array(

        array(
            'type'    => 'notice',
            'style'   => 'success',
            'content' => 'Widget Importer & Exporter 导入和导出小工具，首页模块设置 <a target="_blank" href="'.admin_url('/plugin-install.php?s=Widget%20Importer%20%26%20Exporter&tab=search&type=term').'">插件地址</a><br/>当您的模块化首页和侧边栏小工具配置比较多的时候，可以用此插件备份模块小工具，需要恢复的时候导入导出即可，推荐安装<br/>',
        ),
        array(
            'type'    => 'notice',
            'style'   => 'success',
            'content' => 'Regenerate Thumbnails 缩略图重新生成插件 <a target="_blank" href="'.admin_url('/plugin-install.php?s=Regenerate%20Thumbnails&tab=search&type=term').'">插件地址</a><br/>如果您的网站缩略图不是你媒体设置中的缩略图尺寸，为了保持缩略图清晰和一致，可以用此插件直接重新生成<br/>',
        ),
    
        array(
            'type'    => 'notice',
            'style'   => 'success',
            'content' => 'smartvideo 文章内视频插件<a target="_blank" href="'.admin_url('/plugin-install.php?s=smartideo&tab=search&type=term').'">插件地址</a><br/>smartvideo 是为 WordPress 添加对在线视频支持的一款插件（支持手机、平板等设备HTML5播放）。 目前支持优酷、搜狐视频、土豆、56、腾讯视频、新浪视频、酷6、华数、乐视、YouTube 等网站。<br/>',
        ),
        
        array(
            'type'    => 'notice',
            'style'   => 'success',
            'content' => 'WP-Sweep 数据库清理维护插件<a target="_blank" href="'.admin_url('/plugin-install.php?s=WP-Sweep&tab=search&type=term').'">插件地址</a><br/>当您的数据库文章较多或者分类较多，可以使用此插件进行优化和清理<br/>',
        ),
        array(
            'type'    => 'notice',
            'style'   => 'success',
            'content' => 'Pure Highlightjs 代码高亮插件<a target="_blank" href="https://github.com/icodechef/Pure-Highlightjs/archive/refs/heads/master.zip">插件地址 https://github.com/icodechef/Pure-Highlightjs</a><br/>文章内容中代码高亮插件，下载后上传插件压缩包启用即可。<br/>',
        ),

        array(
            'type'    => 'notice',
            'style'   => 'success',
            'content' => '其他插件推荐，如果您的图片很多，服务器带宽比较低，打开网站比较慢，可以使用云存储图片，一般常见的有阿里云的oss，和腾讯的cos两种，插件推荐wpcos，wposs，',
        ),

        array(
            'type'    => 'notice',
            'style'   => 'danger',
            'content' => '不推荐使用插件，注意，ripro运行的网站大多均开启了商城和登录，此类功能属于动态应用型功能实践，在没有特别了解wordpress的缓存机制或者CDN机制的情况下，不要使用静态缓存插件或者静态CDN缓存加速，否则会造成用户购买或者登录后，您的网站依旧保存缓存中的静态页面，无法实时刷新页面的严重问题！<br>如果您想优化网站速度，第一推荐使用云存储储存图片，第二建议给php开启opcache扩展加速，第三加大服务器配置和带宽，第四找专业的技术人员一对一优化加速，不要看了网上的怎么加速文章不懂原理就乱安插件开启，导致网站数据更乱，速度没什么提升。对于一般的的基础优化，配合一款国内插件 WPJAM BASIC 的插件即可做到。',
        ),
        

        

    ),
));

CSF::createSection($prefix, array(
    'title'  => '文档教程',
    'icon'   => 'fas fa-question-circle',
    'description' => '本主题文档教程托管至第三方，请您认真阅读使用，如有不懂可在正版会员群或者找（开发者：油条）咨询',
    'fields' => array(
        array(
          'type'     => 'callback',
          'function' => 'ripro_v2_doc_callback',
        ),
    ),
));



CSF::createSection( $prefix, array(
  'title'       => '备份设置',
  'icon'        => 'fas fa-shield-alt',
  'description' => '仅备份该页面主题设置所有选项设置数据，并不备份wp自有的文章等数据，提示说明：此处备份中保存的数据格式是字符串类型，有长度验证，切勿修改字符串导致乱码，如需修改请原封不动导入进去在设置页码输入框修改',
  'fields'      => array(

    array(
      'type' => 'backup',
    ),

  )
) );


CSF::createSection($prefix, array(
    'title'       => '主题授权',
    'icon'        => 'fa fa-handshake-o',
    'fields'      => array(
        array(
            'type'    => 'submessage',
            'style'   => 'danger',
            'content' => '<i class="fa fa-heart"></i> 感谢您使用本主题进行创作，请先填写ritheme.com个人中心您的授权ID和授权码点击保存验证。<i class="fa fa-heart"></i><br/></br><i class="fa fa-heart"></i> PS：承蒙您对本主题的喜爱，我们愿向小三一样，做大哥的女人，做大哥网站中最想日的一个。开发者不易，我们真诚的希望和感谢每一个真心建站运营的朋友能支持日系列的正版主题，更好的更用心的等你来调教。如此强大好用的日系列主题，包含支付等重要安全功能，我们希望用户一定要重视自己的数据安全，不要相信所谓破解等盗版，盗版无利不起早，一切以安全为己任。共同维护创造国内健康的WordPress使用环境。开发者油条敬上。</br><br/><b>授权错误会提示错误消息，成功则显示授权正常后刷新页面即可。</b>',
        ),
        array(
          'type'     => 'callback',
          'function' => 'ripro_v2_license_page',
        ),

    ),
));



