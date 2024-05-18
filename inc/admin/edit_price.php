<?php

// Exit if accessed directly.
defined('ABSPATH') || exit;
date_default_timezone_set('Asia/Shanghai');
global $wpdb;
// Authentication
if ( ! current_user_can( 'manage_options' ) ) {
	return;
}

$RiPlusTable = new RiPlus_List_Table();
$RiPlusTable->prepare_items();
$message = '';
if ('delete' === $RiPlusTable->current_action()) {
    $message = '<div class="updated notice notice-success is-dismissible"><p>' . sprintf(__('成功删除: %d 条记录', 'ripro-v2'), count($_REQUEST['wp_list_event'])) . '</p></div>';
}

?>

<div class="wrap">
	<h1>文章价格批量修改</h1>
    <br>
    <p>注意事项：修改不可逆转，请您勾选好要修改的文章价格，设置价格和会员折扣</p>
    <p>注意事项：如果只填写价格，其他不选 则只修改价格，其他字段同理</p>
    <p>1、价格单位为站内币，可以设置为0或者其他数字</p>
	<p>4、一键修改全站所有文章价格只会修改已经有价格字段的数据</p>
    <?php echo $message; ?>
	<hr class="wp-header-end">
	<div id="post-body-content">
		<div class="meta-box-sortables ui-sortable">
			<form method="get">
				<input type="hidden" name="page" value="<?php echo $_GET['page']?>">
				<?php $RiPlusTable->display(); ?>
			</form>
		</div>
	</div>
	<br class="clear">
</div>
<script type="text/javascript">
jQuery(document).ready(function($){
    jQuery('input#go_edit_action').click(function(e) {
        return confirm('确实要对所选条目执行此批量操作吗?');
    });
});
</script>

<?php
// WP_List_Table is not loaded automatically so we need to load it in our application
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Create a new table class that will extend the WP_List_Table
 */
class RiPlus_List_Table extends WP_List_Table
{

	public function __construct()
    {
        global $status, $page;

        parent::__construct(array(
            'singular'  => 'wp_list_event',
            'plural'    => 'wp_list_events',
            'ajax'      => false
        ));
    }



    public function no_items() {
	  _e( '没有找到相关数据' );
	}

    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $per_page     = 20;
		$current_page = $this->get_pagenum();
		$total_items  = $this->get_pagenum();


        $this->set_pagination_args( array(
            'total_items' => $this->table_data_count(),
            'per_page'    => $per_page
        ) );

        $this->_column_headers = array($columns, $hidden, $sortable);
        
        $this->process_bulk_action();

        $this->items = $this->table_data($per_page,$current_page);
    }

    public function get_columns()
    {
       $columns = [
			'cb'      => '<input type="checkbox" />',
			'id'    => __( '文章ID', 'ripro-v2' ),
            'post_title'    => __( '文章标题', 'ripro-v2' ),
            'post_date'    => __( '发布时间', 'ripro-v2' ),
            'cao_price'    => __( '售价', 'ripro-v2' ),
            'cao_vip_rate' => __( '会员折扣', 'ripro-v2' ),
            'cao_close_novip_pay' => __( '普通用户禁止购买', 'ripro-v2' ),
            'cao_is_boosvip' => __( '永久会员免费', 'ripro-v2' ),
            'cao_expire_day' => __( '有效期天数', 'ripro-v2' ),
		];

		return $columns;
    }

    public function column_default( $item, $column_name )
    {
        switch ( $column_name ) {
            case 'id':
                return $item['ID'];
            case 'post_title':
            	if (get_permalink($item['ID'])) {
            		return '<a target="_blank" href='.get_permalink($item['ID']).'>'.get_the_title($item['ID']).'</a>'; 
            	}else{
            		return '<span style="color:red;">【文章ID-'.$item['ID'].'】-已删除</span>'; 
            	}
            case 'cao_price':
                $meta = get_post_meta($item['ID'], $column_name, true);
                $meta = ($meta=='') ? '' : (float)$meta.site_mycoin('name') ;
                return $meta;
            case 'cao_vip_rate':
                $meta = get_post_meta($item['ID'], $column_name, true);
                $meta = ($meta=='') ? '' : (float)$meta ;
                return $meta;
            case 'cao_close_novip_pay':
                $meta = get_post_meta($item['ID'], $column_name, true);
                $meta = ( !empty($meta) ) ? '✔' : '✖';
                return $meta;
            case 'cao_is_boosvip':
                $meta = get_post_meta($item['ID'], $column_name, true);
                $meta = ( !empty($meta) ) ? '✔' : '✖';
                return $meta;
            case 'cao_expire_day':
                $meta = get_post_meta($item['ID'], $column_name, true);
                $meta = ($meta=='') ? '' : (float)$meta ;
                return $meta;
            default:
              return $item[ $column_name ];
		}
    }

    public function get_hidden_columns()
    {
        return array();
    }

    public function get_sortable_columns()
    {
        $sortable_columns = array(
			'id' => array( 'ID', true ),
			'post_date' => array( 'post_date', false ),
		);

		return $sortable_columns;
    }

    public function display_tablenav( $which ) 
	{
	    ?>
	    <div class="tablenav <?php echo esc_attr( $which ); ?>">
            <?php if ('top' === $which) { ?>
                <div class="alignleft actions bulkactions">
                    <select name="edit_action" id="edit_action">
                        <option value="-1">批量操作</option>
                        <option value="edit_cb">修改选中文章</option>
                        <option value="edit_all">修改全部文章</option>
                    </select>
                    <input type="text" id="cao_price" size="10" placeholder="售价" name="cao_price" value="">
                    <input type="text" id="cao_vip_rate" size="10" placeholder="会员折扣" name="cao_vip_rate" value="">
                    <input type="text" id="cao_expire_day" size="10" placeholder="有效期天数" name="cao_expire_day" value="">
                    <select name="cao_is_boosvip" id="cao_is_boosvip">
                        <option value="-1">永久会员免费</option>
                        <option value="0">不启用</option>
                        <option value="1">启用</option>
                    </select>
                    <select name="cao_close_novip_pay" id="cao_close_novip_pay">
                        <option value="-1">普通用户禁止购买</option>
                        <option value="0">不启用</option>
                        <option value="1">启用</option>
                    </select>
                    <input type="submit" id="go_edit_action" class="button action" value="应用批量修改">
                </div>
            <?php } ?>
	        
	        <?php
	        $this->extra_tablenav( $which );
	        $this->pagination( $which );
	        ?>
	        <br class="clear" />
	    </div>
	    <?php
	}


	function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            $this->_args['singular'],
            $item['ID']
        );
    }

	public function process_bulk_action() {

        global $wpdb;
        
        $edit_action = (!empty($_REQUEST['edit_action'])) ? esc_sql( $_REQUEST['edit_action'] ) : null ; 

        //修改选中
		if ('edit_cb' == $edit_action) {
			$edit_ids = (!empty($_REQUEST['wp_list_event'])) ? esc_sql( $_REQUEST['wp_list_event'] ) : null ; 
            
            if (empty($edit_ids)) {
                echo '<div class="updated notice notice-success is-dismissible"><p>请勾选要修改的文章</p></div>';
            }else{
                foreach ($edit_ids as $key => $post_id) {
                    if ( isset($_REQUEST['cao_price']) && $_REQUEST['cao_price'] != '' ) {
                        update_post_meta($post_id, 'cao_price',(float)$_REQUEST['cao_price']);
                    }

                    if ( isset($_REQUEST['cao_vip_rate']) && $_REQUEST['cao_vip_rate'] != '' ) {
                        update_post_meta($post_id, 'cao_vip_rate',(float)$_REQUEST['cao_vip_rate']);
                    }

                    if ( isset($_REQUEST['cao_expire_day']) && $_REQUEST['cao_expire_day'] != '' ) {
                        update_post_meta($post_id, 'cao_expire_day',(int)$_REQUEST['cao_expire_day']);
                    }

                    if ( isset($_REQUEST['cao_is_boosvip']) && $_REQUEST['cao_is_boosvip'] != '-1' ) {
                        update_post_meta($post_id, 'cao_is_boosvip',(int)$_REQUEST['cao_is_boosvip']);
                    }
                    if ( isset($_REQUEST['cao_close_novip_pay']) && $_REQUEST['cao_close_novip_pay'] != '-1' ) {
                        update_post_meta($post_id, 'cao_close_novip_pay',(int)$_REQUEST['cao_close_novip_pay'] );
                    }

                }
                echo '<div class="updated notice notice-success is-dismissible"><p>' . sprintf( __('成功修改: %d 个文章价格权限', 'ripro-v2'), count($edit_ids) ) . '</p></div>';
            }
        }
        //修改全部
        if ('edit_all' == $edit_action) {

            if ( isset($_REQUEST['cao_price']) && $_REQUEST['cao_price'] != '' ) {
                $wpdb->update( $wpdb->postmeta,array( 'meta_value' => floatval($_REQUEST['cao_price']) ),array('meta_key' => 'cao_price') );
            }

            if ( isset($_REQUEST['cao_vip_rate']) && $_REQUEST['cao_vip_rate'] != '' ) {
                $wpdb->update( $wpdb->postmeta,array( 'meta_value' => floatval($_REQUEST['cao_vip_rate']) ),array('meta_key' => 'cao_vip_rate') );
            }

            if ( isset($_REQUEST['cao_expire_day']) && $_REQUEST['cao_expire_day'] != '' ) {
                $wpdb->update( $wpdb->postmeta,array( 'meta_value' => intval($_REQUEST['cao_expire_day']) ),array('meta_key' => 'cao_expire_day') );
            }

            if ( isset($_REQUEST['cao_is_boosvip']) && $_REQUEST['cao_is_boosvip'] != '-1' ) {
                $wpdb->update( $wpdb->postmeta,array( 'meta_value' => intval($_REQUEST['cao_is_boosvip']) ),array('meta_key' => 'cao_is_boosvip') );
            }
            if ( isset($_REQUEST['cao_close_novip_pay']) && $_REQUEST['cao_close_novip_pay'] != '-1' ) {
                $wpdb->update( $wpdb->postmeta,array( 'meta_value' => intval($_REQUEST['cao_close_novip_pay']) ),array('meta_key' => 'cao_close_novip_pay') );
            }

            echo '<div class="updated notice notice-success is-dismissible"><p>成功修改所有文章价格和权限</p></div>';
        }

	}

    private function table_data($per_page = 5, $page_number = 1 )
    {
        global $wpdb;

		$sql = "SELECT * FROM $wpdb->posts WHERE post_type='post' AND post_status='publish'";
		
        
		//排序
		$orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'ID' ;
		if ( ! empty( $orderby ) ) {
			$sql .= ' ORDER BY ' . esc_sql( $orderby );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' DESC';
		}

		$sql .= " LIMIT $per_page";
		$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;

		$result = $wpdb->get_results( $sql, 'ARRAY_A' );
		return $result;
    }

    private function table_data_count() {
		global $wpdb;

        $sql = "SELECT COUNT(ID) FROM $wpdb->posts WHERE post_type='post' AND post_status='publish'";
		
		return $wpdb->get_var( $sql );
	}

	private function delete_table_data( $id ) {
		global $wpdb;
		$wpdb->delete(
			"$wpdb->posts",
			[ 'id' => $id ],
			[ '%d' ]
		);
	}

}