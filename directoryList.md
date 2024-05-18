|-- ripro-v2
    |-- 404.php     404页面模板
    |-- archive-question.php     问答页面模板
    |-- archive.php     分类等页面模板
    |-- attachment.php     附件页面模板
    |-- comments-question.php     问答评论模板
    |-- comments.php     文章评论模板
    |-- footer.php     底部模板
    |-- functions.php     主题方法函数
    |-- header.php     顶部模板
    |-- index.php     首页模板
    |-- page.php     页面模板
    |-- screenshot.jpg     主题封面图
    |-- search.php     搜索页面模板
    |-- searchform.php     搜索框模板
    |-- sidebar.php     侧边栏模板
    |-- single-question.php     问答侧边栏模板
    |-- single.php     文章页面模板
    |-- style.css     主题信息
    |-- timthumb.php.no     PHP裁剪缩略图库文件
    |-- assets     静态资源路径
    |   |-- bootstrap     bootstrap资源
    |   |-- css     css文件
    |   |   |-- admin.css     后台css样式
    |   |   |-- app.css     前台主要css样式
    |   |   |-- dark.css     夜间模式css样式
    |   |   |-- plugins.css     其他组件css样式
    |   |-- DPlayer     视频播放器组件
    |   |-- font     字体文件夹
    |   |-- img     图片文件夹
    |   |-- jarallax     视差组件
    |   |-- js     JS文件夹
    |   |   |-- app.js     主题主要JS
    |   |   |-- clipboard.min.js     一键复制组件
    |   |   |-- jquery.min.js     jquery组件
    |   |   |-- jquery.mousewheel.min.js     mousewheel
    |   |   |-- option-admin.js     后台设置组件
    |   |   |-- plugins.js     其他插件
    |   |   |-- popper.min.js     弹出插件
    |   |-- justifiedGallery     图片对其插件
    |   |-- lightGallery     灯箱插件
    |-- cache     缩略图缓存文件夹
    |-- help     安装帮助文件夹
    |   |-- install.min.css     
    |   |-- swoole-compiler-loader.php     swoole-compiler安装帮助
    |-- inc     核心功能function 函数或方法
    |   |-- goto.php     站内跳转、下载地址用
    |   |-- qrcode.php     二维码插件
    |   |-- template-admin.php     后台功能函数
    |   |-- template-ajax.php     前台接口
    |   |-- template-clean.php     wordpress优化
    |   |-- template-enqueue.php     静态资源加载
    |   |-- template-filter.php     WP钩子过滤
    |   |-- template-framework.php     主题选项框架
    |   |-- template-navwalker.php     高级菜单API
    |   |-- template-question.php     问答功能函数
    |   |-- template-shop.php     商城功能函数
    |   |-- template-tags.php     主题模板函数
    |   |-- admin     后台页面相关
    |   |   |-- admin_pay.php     后台充值
    |   |   |-- aff_log.php     提现管理
    |   |   |-- aff_order.php     佣金管理
    |   |   |-- cdk_order.php     卡密管理
    |   |   |-- coin_order.php     充值记录
    |   |   |-- down_order.php     下载记录
    |   |   |-- edit_price.php     批量修改
    |   |   |-- pay_order.php     订单总览
    |   |   |-- shop_index.php     商城总览
    |   |-- class     核心class
    |   |   |-- pay.class.php     主题授权文件类
    |   |   |-- pay.xh.class.php     讯虎支付类
    |   |   |-- qrcode.class.php     二维码类
    |   |-- codestar-framework     选项框架类
    |   |-- options     主题选项配置
    |   |   |-- admin-options.php     主题设置配置
    |   |   |-- metabox-options.php     文章字段配置
    |   |   |-- nav-menu-options.php     菜单字段配置
    |   |   |-- profile-options.php     用户字段配置
    |   |   |-- shop-widget-options.php     侧边栏购买模块
    |   |   |-- shortcode-options.php     简码字段配置
    |   |   |-- taxonomy-options.php     分类字段配置
    |   |   |-- widget-options.php     模块化、小工具配置
    |   |-- shop     支付接口
    |   |   |-- alipay     支付宝官方
    |   |   |   |-- notify.php     mapi网关异步通知
    |   |   |   |-- notify_qr.php     应用模式异步通知
    |   |   |   |-- return.php     同步通知
    |   |   |-- payjs     PAYJS
    |   |   |   |-- notify.php     异步通知
    |   |   |   |-- return.php     同步通知
    |   |   |-- weixinpay     微信官方
    |   |   |   |-- notify.php     异步通知
    |   |   |   |-- return.php     同步通知
    |   |   |-- xhpay     讯虎/虎皮椒
    |   |       |-- notify_ali.php     虎皮椒支付宝异步通知
    |   |       |-- notify_wx.php     虎皮椒微信异步通知
    |   |       |-- notify_xh_ali.php     讯虎支付宝异步通知
    |   |       |-- notify_xh_wx.php     讯虎微信异步通知
    |   |       |-- return.php     同步通知
    |   |-- sns     第三方登录功能
    |       |-- mpweixin     公众号登录
    |       |   |-- callback.php     回调地址
    |       |   |-- login.php     登录地址
    |       |-- qq     QQ登录
    |       |   |-- callback.php     回调地址
    |       |   |-- login.php     登录地址
    |       |-- weibo     微博登录
    |       |   |-- callback.php     回调地址
    |       |   |-- login.php     登录地址
    |       |-- weixin     微信开放平台登录
    |           |-- callback.php     回调地址
    |           |-- login.php     登录地址
    |-- languages     主题翻译文件夹
    |-- pages     自带页面模板
    |   |-- page-container.php     空白页面模板
    |   |-- page-link.php     网址导航页面模板
    |   |-- page-login.php     找回密码模板
    |   |-- page-modular.php     模块化页面模板
    |   |-- page-tags.php     标签云模板
    |   |-- page-user.php     用户中心模板
    |   |-- user      用户中心
    |       |-- aff.php     推广中心
    |       |-- bind.php     绑定账号
    |       |-- coin.php     我的余额
    |       |-- down.php     下载记录
    |       |-- fav.php     我的收藏
    |       |-- header.php     顶部
    |       |-- index.php     我的资料
    |       |-- menu.php     左侧菜单
    |       |-- order.php     购买记录
    |       |-- password.php     修改密码
    |       |-- tou.php     文章投稿
    |       |-- vip.php     我的会员
    |-- template-parts      模板组件
    |   |-- content     内容类
    |   |   |-- entry-header.php     内容顶部
    |   |   |-- entry-navigation.php     上下篇文章
    |   |   |-- entry-share.php    底部分享
    |   |   |-- entry-tags.php     底部标签
    |   |   |-- hero-shop.php     顶部商城组件
    |   |   |-- hero-video.php     顶部视频组件
    |   |   |-- page.php     页面内容
    |   |   |-- single-question.php     问答详情
    |   |   |-- single-shop.php     商品文章详情
    |   |   |-- single.php     文章详情
    |   |-- global     全局类
    |   |   |-- archive-filter.php     内页筛选组件
    |   |   |-- footer-menu.php     手机端底部菜单
    |   |   |-- footer-rollbar.php     右侧浮动按钮
    |   |   |-- footer-widget.php     底部小工具
    |   |   |-- header-menu.php     顶部菜单
    |   |   |-- hero.php     顶部hero模块
    |   |   |-- login-form.php     登陆表单
    |   |   |-- login-sns.php     第三方登录按钮
    |   |   |-- off-canvas.php     手机端右上角菜单
    |   |   |-- omnisearch.php     弹出搜索模板
    |   |   |-- related-posts.php     相关文章模块
    |   |   |-- term-bar.php     分类顶部
    |   |   |-- widget-question.php     问答侧边栏
    |   |-- loop     循环类
    |   |   |-- item-grid.php     文章网格模式
    |   |   |-- item-list-question.php     问答列表
    |   |   |-- item-list.php     文章列表模式
    |   |   |-- item-none.php     空列表
    |   |-- page     页面类
    |-- vendor     commposer库