<?php

		

// Exit if accessed directly.
defined('ABSPATH') || exit;
global $wpdb;
// Authentication
if ( ! current_user_can( 'manage_options' ) ) {
	return;
}

$RiPlusTable = new RiPlus_List_Table();
$del_url = add_query_arg(array('page' => $_GET['page'],'ri_page_action' => 'delete_log'), admin_url('admin.php'));


$message = '';
if ('delete' === $RiPlusTable->current_action()) {
	$delete_ids = (!empty($_REQUEST['wp_list_event'])) ? esc_sql( $_REQUEST['wp_list_event'] ) : null ; 

	if ($delete_ids) {
		foreach ($_REQUEST['wp_list_event'] as $event) {
			$wpdb->delete( $wpdb->cao_order, array('id' => $event));
    	}
    	$message = '<div class="updated notice notice-success is-dismissible"><p>' . sprintf(__('成功删除: %d 条记录', 'ripro-v2'), count($_REQUEST['wp_list_event'])) . '</p></div>';
	}
    unset($_REQUEST['wp_list_event']);
}

$page_action = (!empty($_GET['ri_page_action'])) ? trim($_GET['ri_page_action']) : false;
if ($page_action=='delete_log' && $wpdb->delete( "$wpdb->cao_order", array('order_type' => 'other','status' => 0))) {
     $message = '<div class="updated notice notice-success is-dismissible"><p>' . __('成功清理未支付记录', 'ripro-v2') . '</p></div>';
}

$RiPlusTable->prepare_items();

?>

<!-- 主页面 -->
<div class="wrap">
	<h1 class="wp-heading-inline">订单记录</h1>
    <a href="<?php echo $del_url ?>" class="page-title-action" onclick="return confirm(\'确定清理未支付订单?\')">一键清理未支付订单</a>
	<p>如需清理订单，直接筛选出未付款订单，选择删除，应用即可，已付款订单删除后，用户需要重新下单支付</p>
	<?php echo $message; ?>
	<hr class="wp-header-end">

	<div id="post-body-content">
		<div class="meta-box-sortables ui-sortable">
			<form method="get">
				<?php $RiPlusTable->search_box('可根据用户ID，订单号，支付单号搜索', 'user_id'); ?>
				<input type="hidden" name="page" value="<?php echo $_GET['page']?>">
				<?php $RiPlusTable->display(); ?>
			</form>
		</div>
	</div>
	<br class="clear">
</div>
<script type="text/javascript">
jQuery(document).ready(function($){
	jQuery('input#doaction').click(function(e) {
        return confirm('确实要对所选条目执行此批量操作吗?');
    });
});
</script>
<?php
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Create a new table class that will extend the WP_List_Table
 */
class RiPlus_List_Table extends WP_List_Table{

	public function __construct(){
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

    public function prepare_items(){
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
        $this->items = $this->table_data($per_page,$current_page);
    }

    public function get_columns(){
       $columns = [
			'cb'      => '<input type="checkbox" />',
            'order_trade_no'    => __( '订单号', 'ripro-v2' ),
			'user_id'    => __( '用户', 'ripro-v2' ),
			'order_type'    => __( '商品', 'ripro-v2' ),
            'order_price'    => __( '订单价格', 'ripro-v2' ),
            'order_info'    => __( '其他信息', 'ripro-v2' ),
            'create_time'    => __( '下单时间', 'ripro-v2' ),
            'pay_type'    => __( '支付方式', 'ripro-v2' ),
            'pay_time'    => __( '支付时间', 'ripro-v2' ),
            'pay_trade_no'    => __( '支付单号', 'ripro-v2' ),
            'status'    => __( '支付状态', 'ripro-v2' ),
		];
		return $columns;
    }

    public function column_default( $item, $column_name ){
        switch ( $column_name ) {
			case 'user_id':
				if ($author_obj = get_user_by('ID', $item['user_id'])) {
                    $u_name =$author_obj->user_login;
                }else{
                    $u_name = '游客';
                }
                return get_avatar($item['user_id'], 30).'<strong>'.$u_name.'<strong>';
            case 'order_price':
            	if (site_mycoin('is')) {
            		return '<span class="badge badge-hollow">￥'.$item[$column_name].' | '.convert_site_mycoin($item[$column_name],'coin').site_mycoin('name').'</span>';
            	}else{
            		return '<span class="badge badge-hollow">￥'.$item[$column_name].'</span>';
            	}
            case 'order_info':
            	$order_info = maybe_unserialize(@$item[$column_name]);
            	$order_info_t = '';

			    if ( isset($order_info['vip_rate']) ) {
			    	$order_info_t .= '折扣：'.$order_info['vip_rate'];
			    }
			    // if ( isset($order_info['ip']) ) {
			    // 	$order_info_t .= ' | IP：'.$order_info['ip'];
			    // }
			    return $order_info_t;
            	
            case 'create_time':
            	return date('Y-m-d H:i:s',$item[$column_name]);
            case 'pay_type':
            	return get_order_pay_type_text($item[$column_name],true);
            case 'pay_time':
            	if (!empty($item[$column_name])) {
            		return date('Y-m-d H:i:s',$item[$column_name]);
            	}else{
            		return 'N/A';
            	}
            case 'order_type':
            	return '<a target="_blank" href='.get_permalink($item['post_id']).'>'.get_order_type_text((object)$item).'</a>';
            	
            case 'pay_num':
            	if (!empty($item[$column_name])) {
            		return $item[$column_name];
            	}else{
            		return 'N/A';
            	}
            case 'status':
            	if ($item[$column_name]==1) {
            		return '<span class="badge badge-primary"><span class="layui-badge-dot layui-bg-gray"></span> 已支付</span>';
            	}else{
            		return '<span class="badge badge-secondary">未支付</span>';
            	}
            default:
              return $item[ $column_name ];
		}
    }

    public function get_hidden_columns(){
        return array();
    }

    public function get_sortable_columns(){
        $sortable_columns = array(
			'id' => array( 'id', true ),
			'create_time' => array( 'create_time', true ),
			'pay_time' => array( 'pay_time', true ),
			'order_price' => array( 'order_price', true ),
			'status' => array( 'status', true ),
		);

		return $sortable_columns;
    }

    public function display_tablenav( $which ){ ?>
	    <div class="tablenav mb-4 <?php echo esc_attr( $which ); ?>">

	        <div class="alignleft actions">
	            <?php $this->bulk_actions(); ?>
	        </div>
	        <?php
	        $this->extra_tablenav( $which );
	        if ($which=='bottom') {
    	        $this->pagination( $which );
    	    }
	        ?>
	        <br class="clear" />
	    </div>
	<?php
	}

    public function extra_tablenav( $which ) {
	    global $wpdb, $testiURL, $tablename,$ri_pay_type_options,$tablet;
	    if ( $which == "top" ){ ?>
	        <div class="alignleft actions bulkactions">
	        <?php $filter = [
	        	['title'=>'未支付','id'=>'0'],
	        	['title'=>'已支付','id'=>'1'],
	        ];
	            ?>
	            <select name="status" class="ewc-filter-status">
	            	<option selected="selected" value="">支付状态</option>
	                <?php foreach( $filter as $item ){
	                    $selected = '';
	                    $_REQUEST['status'] = (!empty($_REQUEST['status'])) ? $_REQUEST['status'] : null ;
	                    if( $_REQUEST['status'] == $item['id'] ){
	                        $selected = ' selected = "selected"';   
	                    }
	                    echo '<option value="'.$item['id'].'"'.$selected.'>'.$item['title'].'</option>';
	                }?>
	            </select>

	            <button type="submit" id="post-query-submit" class="button">筛选</button> 
	        </div>
	        
	        <?php
	    }

	    if ( $which == "bottom" ){

	    }
	}


	function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            $this->_args['singular'],
            $item['id']
        );
    }

	public function get_bulk_actions(){
        $actions = array(
            'delete'    => '删除',
        );
        return $actions;
    }


    
    private function table_data($per_page = 5, $page_number = 1 ){
        global $wpdb;

		$sql = "SELECT * FROM {$wpdb->cao_order} WHERE order_type='other'";
		//根据用户查询other
		if ( ! empty( $_REQUEST['s'] ) ) {
			
			$search_term = trim(sanitize_text_field(wp_unslash($_REQUEST['s'])));

			$user_id = 88888888888;

			if (is_numeric($_REQUEST['s'])) {
                $user_id = absint($search_term);
            } else {
                $author_obj = get_user_by('login', $search_term);
                if (!empty($author_obj)) {
                	$user_id = $author_obj->ID;
                }
            }
			// $sql .= ' AND user_id=' . esc_sql($user_id);
			$sql .= " AND (user_id LIKE '%{$user_id}%' OR order_trade_no LIKE '%{$search_term}%' OR pay_trade_no LIKE '%{$search_term}%')";
		}


		//状态查询
		if ( isset( $_REQUEST['status'] ) && $_REQUEST['status']!='' ) {
			$status = absint($_REQUEST['status']);
			$sql .= ' AND status=' . esc_sql($status);
		}
		
		$orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'create_time' ;
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
		
        $sql = "SELECT COUNT(*) FROM {$wpdb->cao_order} WHERE order_type='other'";
		//根据用户查询
		if ( ! empty( $_REQUEST['s'] ) ) {
			$user_id = 0;
			if (is_numeric($_REQUEST['s'])) {
                $user_id = absint($_REQUEST['s']);
            } else {
                $author_obj = get_user_by('login', $_REQUEST['s']);
                if (!empty($author_obj)) {
                	$user_id    = $author_obj->ID;
                }
            }
			$sql .= ' AND user_id=' . esc_sql($user_id);
		}
		//状态查询
		if ( isset( $_REQUEST['status'] ) && $_REQUEST['status']!='' ) {
			$status = absint($_REQUEST['status']);
			$sql .= ' AND status=' . esc_sql($status);
		}

		return $wpdb->get_var( $sql );
	}



	private function delete_table_data( $id ) {
		global $wpdb;
		$wpdb->delete( $wpdb->cao_order, array('id' => $id));
	}

}
?>


<!-- 主页面END -->
