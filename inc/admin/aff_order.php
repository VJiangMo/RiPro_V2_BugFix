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
	<h1 class="wp-heading-inline">佣金推广管理</h1>
    <a href="<?php echo add_query_arg(array('page' => 'ripro_v2_aff_log_page'), admin_url('admin.php'));?>" class="page-title-action">提现申请处理</a>
    <p>管理，查询，统计，提现处理中心,只显示当前有推广信息的用户，无推广记录不显示</p>
	<hr class="wp-header-end">
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
			'user_id'    => __( '用户ID', 'ripro-v2' ),
			'total'    => __( '推广人数', 'ripro-v2' ),
			'aff_ratio'    => __( '佣金比例', 'ripro-v2' ),
			'leiji'    => __( '累计佣金', 'ripro-v2' ),
			'keti'    => __( '可提现', 'ripro-v2' ),
			'tixian'    => __( '提现中', 'ripro-v2' ),
			'yiti'    => __( '已提现', 'ripro-v2' ),
		];
		return $columns;
    }

    public function column_default( $item, $column_name ){
    	$user_aff_info =_get_user_aff_info($item['user_id']);
        switch ( $column_name ) {
			case 'user_id':
				if ($author_obj = get_user_by('ID', $item['user_id'])) {
                    $u_name =$author_obj->user_login;
                }else{
                    $u_name = '游客';
                }
                return get_avatar($item['user_id'], 30).'<strong>'.$u_name.'<strong>';
            case 'aff_ratio':
            	return $user_aff_info[ $column_name ] *100 . '%';
            default:
              return $user_aff_info[ $column_name ];
		}
    }

    public function get_hidden_columns(){
        return array();
    }

    public function display_tablenav( $which ){
	    
	    ?>
	    
	    <div class="tablenav <?php echo esc_attr( $which ); ?>">
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
	    
	}


	public function get_bulk_actions(){
        return array();
    }

	public function process_bulk_action() {
		global $wpdb;
		$action = $this->current_action();
		if ( isset($action) ) {
			$update_ids = (!empty($_REQUEST['wp_list_event'])) ? esc_sql( $_REQUEST['wp_list_event'] ) : null ; 
			if ($update_ids) {
				foreach ($_REQUEST['wp_list_event'] as $id) {
					$wpdb->update($wpdb->cao_ref_log,array('aff_status' => $this->current_action(),'aff_time' => time()),array('id' => $id),array('%d','%d'),array('%d'));
            	}
            	$_url = remove_query_arg(['action','wp_list_event','action2']);
            	echo "<script type='text/javascript'>window.location.href='$_url';</script>";
			}
            
        }

	}

    
    private function table_data($per_page = 5, $page_number = 1 ){
        global $wpdb;
		$sql = "SELECT * FROM {$wpdb->usermeta} WHERE meta_key='cao_total_bonus' AND meta_value>0";
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
		$sql .= " ORDER BY meta_value DESC LIMIT $per_page";
		$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;

		$result = $wpdb->get_results( $sql, 'ARRAY_A' );

		return $result;
    }

    private function table_data_count() {
		global $wpdb;

        $sql = "SELECT COUNT(*) FROM {$wpdb->usermeta} WHERE meta_key='cao_total_bonus' AND meta_value>0";
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
		return $wpdb->get_var( $sql );
	}

	

}
?>


<!-- 主页面END -->

<script type="text/javascript">
jQuery(document).ready(function($){
	jQuery('input#doaction').click(function(e) {
        return confirm('确实要对所选条目执行此批量操作吗?');
    });
});
</script>