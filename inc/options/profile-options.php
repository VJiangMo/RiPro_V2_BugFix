<?php if (!defined('ABSPATH')) {die;} // Cannot access directly.

if (!current_user_can('manage_options') && is_admin()) {
    return;
}

$prefix = '_prefix_profile_options';
CSF::createProfileOptions($prefix, array(
    'data_type' => 'unserialize',
));
CSF::createSection($prefix, array(
    'title'  => 'RiPro-v2-会员其他信息',
    'fields' => array(
        array(
            'id'         => 'cao_balance',
            'type'       => 'text',
            'title'      => '钱包余额',
            'attributes' => array(
                'readonly' => 'readonly',
            ),
            'default'    => '0',
        ),
        array(
            'id'         => 'cao_consumed_balance',
            'type'       => 'text',
            'title'      => '用户已消费余额',
            'attributes' => array(
                'readonly' => 'readonly',
            ),
            'default'    => '0.00',
        ),

        array(
            'id'         => 'cao_ref_from',
            'type'       => 'text',
            'title'      => '推荐人ID',
            'attributes' => array(
                'readonly' => 'readonly',
            ),
            'default'    => '0',
        ),

        array(
            'id'      => 'cao_user_type',
            'type'    => 'select',
            'title'   => '用户等级',
            'options' => array(
                'no'  => '普通',
                'vip' => 'VIP',
            ),
        ),

        array(
            'id'       => 'cao_vip_end_time',
            'type'     => 'date',
            'title'    => _cao('site_vip_name') . '用户到期时间',
            'desc'     => '如果要设置永久会员，请手动吧到期日期改为：9999-09-09',
            'settings' => array(
                'dateFormat' => 'yy-mm-dd', //date("Y-m-d");
            ),
        ),

        array(
            'id'    => 'cao_banned',
            'type'  => 'switcher',
            'title' => '封号该用户',
            'desc'  => '封号h后无法登录账号',
        ),
        array(
            'id'         => 'cao_banned_reason',
            'type'       => 'textarea',
            'title'      => '封号原因',
            'default'    => '本站检测到您存在恶意刷单，下载，采集，恶意攻击，评论，赠予封号！',
            'dependency' => array('cao_banned', '==', 'true'),
        ),

    ),

));

unset($prefix);