<?php

// Exit if accessed directly.
defined('ABSPATH') || exit;
global $wpdb;
// Authentication
if ( ! current_user_can( 'manage_options' ) ) {
	return;
}
$RiPlusTable = new RiPlus_List_Table();
$RiPlusTable->prepare_items();


?>

<!-- 主页面 -->
<div class="wrap">
	<h2 style=" margin-bottom: 20px; ">用户提现申请处理记录</h2>

	<hr class="wp-header-end">
	
	
	<p>提现方法：</p>
	<p>1，用户联系管理员后，管理在此页面更具用户id信息搜索改用户的所有提现申请订单。</p>
	<p>2，筛选出来该用户的推广订单后，计算核对提现金额。然后手动给用户转账。</p>
	<p>3，转账后选中提现的订单，批量操作-更改状态为已提现即可。</p>
	<p>4，如果用户没有上传收款码，请手动联系用户</p>

	<div id="post-body-content">
		<div class="meta-box-sortables ui-sortable">
			<form method="get">
				<?php $RiPlusTable->search_box('根据推荐人ID搜索', 'user_id'); ?>
				<input type="hidden" name="page" value="<?php echo $_GET['page']?>">
				<?php $RiPlusTable->display(); ?>
			</form>
		</div>
	</div>
	<br class="clear">
</div>

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
        $this->process_bulk_action();
        $this->items = $this->table_data($per_page,$current_page);
    }

    public function get_columns(){
       $columns = [
			'cb'      => '<input type="checkbox" />',
			'user_id'    => __( '用户ID', 'ripro-v2' ),
            'money'    => __( '提现金额', 'ripro-v2' ),
            'create_time'    => __( '申请时间', 'ripro-v2' ),
            'up_time'    => __( '审核时间', 'ripro-v2' ),
            'note'    => __( '备注', 'ripro-v2' ),
            'status'    => __( '提现状态', 'ripro-v2' ),
            'wxqr'    => __( '微信收款码', 'ripro-v2' ),
            'aliqr'    => __( '支付宝收款码', 'ripro-v2' ),
            'qq'    => __( '联系QQ', 'ripro-v2' ),
            'phone'    => __( '联系电话', 'ripro-v2' ),
		];
		return $columns;
    }

    public function column_default( $item, $column_name ){
        switch ( $column_name ) {
        	case 'phone':
        		$phone = get_user_meta($item['user_id'], 'phone', true);
        		if (!empty($phone)) {
        			return $phone;
        		}else{
        			return '无';
        		}
        	case 'qq':
        		$qq = get_user_meta($item['user_id'], 'qq', true);
        		if (!empty($qq)) {
        			return $qq;
        		}else{
        			return '无';
        		}
        	case 'wxqr':
        		$qr = get_user_meta($item['user_id'], 'qr_weixin', true);
        		if (!empty($qr)) {
        			return '<a href="'.getQrcodeApi($qr).'?keepThis=true&TB_iframe=true&height=300&width=300" class="thickbox" >查看</a>';
        		}else{
        			return '无';
        		}
        	case 'aliqr':
        		$qr = get_user_meta($item['user_id'], 'qr_weixin', true);
        		if (!empty($qr)) {
        			return '<a href="'.getQrcodeApi($qr).'?keepThis=true&TB_iframe=true&height=300&width=300" class="thickbox" >查看</a>';
        		}else{
        			return '无';
        		}
			case 'user_id':
				if ($author_obj = get_user_by('ID', $item['user_id'])) {
                    $u_name =$author_obj->user_login;
                }else{
                    $u_name = '游客';
                }
                return get_avatar($item['user_id'], 30).'<strong>'.$u_name.'<strong>';
            case 'status':
            	$arr = array('提现中','已提现');
            	if (empty($item[$column_name])) {
            		$item[$column_name] = 0;
            	}
            	return @$arr[$item[$column_name]];
            case 'money':
            	return '<span class="badge badge-hollow">￥'.$item[$column_name].'</span>';
            case 'create_time':
            	return date('Y-m-d H:i:s',$item[$column_name]);
            case 'up_time':
            	return date('Y-m-d H:i:s',$item[$column_name]);
            
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
			'up_time' => array( 'up_time', true ),
			'money' => array( 'money', true ),
			'status' => array( 'status', true ),
		);

		return $sortable_columns;
    }

    public function display_tablenav( $which ){
	    
	    ?>
	    
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
	    global $wpdb, $testiURL, $tablename, $tablet;
	    if ( $which == "top" ){
	        ?>
	        <div class="alignleft actions bulkactions">
	        <?php
	        $filter = [
	        	['title'=>'提现中','id'=>'0'],
	        	['title'=>'已提现','id'=>'1'],

	        ];
	        if( $filter ){
	            ?>
	            <select name="status" class="ewc-filter-status">
	            	<option selected="selected" value="">提现状态</option>
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
	            <?php
	        }
	        ?>  
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
            '0'    => '更新为提现中',
            '1'    => '更新为已提现',
        );
        return $actions;
    }

	public function process_bulk_action() {
		global $wpdb;
		$action = $this->current_action();
		if ( isset($action) ) {
			$update_ids = (!empty($_REQUEST['wp_list_event'])) ? esc_sql( $_REQUEST['wp_list_event'] ) : null ; 
			if ($update_ids) {
				foreach ($_REQUEST['wp_list_event'] as $id) {
					$wpdb->update($wpdb->cao_ref_log,array('status' => $this->current_action(),'up_time' => time()),array('id' => $id),array('%d','%d'),array('%d'));
            	}
            	$_url = remove_query_arg(['action','wp_list_event','action2']);
            	echo "<script type='text/javascript'>window.location.href='$_url';</script>";
			}
            
        }

	}

    
    private function table_data($per_page = 5, $page_number = 1 ){
        global $wpdb;
		$sql = "SELECT * FROM {$wpdb->cao_ref_log} WHERE 1=1";
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
			$sql .= ' AND status=' . $status;
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

        $sql = "SELECT COUNT(*) FROM {$wpdb->cao_ref_log} WHERE 1=1";
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
			$sql .= ' AND status=' . $status;
		}
		return $wpdb->get_var( $sql );
	}

	

}
?>


<!-- 主页面END -->

<?php //add_thickbox(); ?>
<script type="text/javascript">
jQuery(document).ready(function($){
	jQuery('input#doaction').click(function(e) {
        return confirm('确实要对所选条目执行此批量操作吗?');
    });
});
</script>