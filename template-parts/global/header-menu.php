<?php global $current_user;?>

<header class="site-header">
    <div class="container">
	    <div class="navbar">
			<?php ripro_v2_logo(); ?>
			
			<div class="sep"></div>
			
			<nav class="main-menu d-none d-lg-block">
			<?php wp_nav_menu( array(
			  'container' => false,
			  'fallback_cb' => 'Ripro_v2_Walker_Nav_Menu::fallback',
			  'menu_class' => 'nav-list u-plain-list',
			  'theme_location' => 'menu-1',
			  'walker' => new Ripro_v2_Walker_Nav_Menu( true ),
			) ); ?>
			</nav>
			
			<div class="actions">
				
				<?php if ( _cao('navbar_omnisearch_search',1) ) : ?>
				<span class="btn btn-sm search-open navbar-button ml-2" rel="nofollow noopener noreferrer" data-action="omnisearch-open" data-target="#omnisearch" title="<?php echo esc_html('搜索','rizhuti-v2');?>"><i class="fas fa-search"></i></span>
				<?php endif;?>

				<?php if ( _cao('is_site_notify',1) ) : ?>
				<span class="btn btn-sm toggle-notify navbar-button ml-2" rel="nofollow noopener noreferrer" title="<?php echo esc_html('公告','ripro-v2');?>"><i class="fa fa-bell-o"></i></span>
				<?php endif;?>

				<?php if ( !empty(_cao('is_site_dark_light',true)) ) : ?>
		        <span class="btn btn-sm toggle-dark navbar-button ml-2" rel="nofollow noopener noreferrer" title="<?php echo esc_html('夜间模式','ripro-v2');?>"><i class="fa fa-moon-o"></i></span>
                <?php endif;?>
                
				<!-- user navbar dropdown  -->
		        <?php if ( is_user_logged_in() ) : ?>

		        <li class="dropdown ml-2 d-inline-block">

					<a class="rounded-circle d-flex align-items-center" href="<?php echo get_user_page_url();?>" role="button" rel="nofollow noopener noreferrer">
					   <img class="menu-avatar-img mr-1" src="<?php echo get_avatar_url($current_user->ID);?>" width="30" alt="avatar">
					   <span class="mx-display-name"><?php echo $current_user->display_name;?><?php echo _get_user_vip_type_badge($current_user->ID); ?></span>
					</a>
					
					<?php if ( _cao('navbar_user_hover',true) ) : ?>
					<!-- dropdown-menu navbar_user_hover -->
					<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownUserProfile">
						<a class="user-logout" href="<?php echo wp_logout_url(home_url()); ?>"><i class="fa fa-sign-out"></i> <?php echo esc_html__('退出登录','ripro-v2');?></a>
						<?php if(in_array( 'administrator', $current_user->roles )): ?>
			            <a class="user-admin" target="_blank" href="<?php echo esc_url( home_url('/wp-admin/') ) ?>"><i class="fa fa-wordpress"></i> <?php echo esc_html__('后台管理','ripro-v2');?></a>
			          	<?php endif; ?>
					    <div class="dropdown-item">
						  <div class="d-flex align-items-center">
							 <div class="avatar avatar-indicators">
								<a class="" href="<?php echo get_user_page_url();?>" rel="nofollow noopener noreferrer"><img class="rounded-circle" src="<?php echo get_avatar_url($current_user->ID);?>" alt="avatar"></a>
							 </div>
							 <div class="ml-3 lh-1">
								<p class="d-flex align-items-center mb-1"><?php echo $current_user->display_name;?>
									<a href="<?php echo get_user_page_url('vip');?>"><span class="ml-2"></span><?php echo _get_user_vip_type_badge($current_user->ID,false); ?></a>
								</p>
								<small class="mb-0 text-muted"><?php echo $current_user->user_email;?></small>
							 </div>
						  </div>
					    </div>
					    <div class="dropdown-divider"></div>
					    <ul class="list-unstyled m-0">
					    	<div class="dropdown-item">
								<div class="row no-gutters">
									<div class="col-6">
										<div class="menu-card-box-1">
											<span class="small"><i class="<?php echo site_mycoin('icon');?> mr-1"></i><?php echo site_mycoin('name') . esc_html__('钱包','ripro-v2');?></span>
											<p class="small m-0"><?php echo esc_html__('当前余额：','ripro-v2') . get_user_mycoin($current_user->ID);?></p>
											<p class="small"><?php echo esc_html__('累计消费：','ripro-v2') . (float) get_user_meta($current_user->ID, 'cao_consumed_balance', 1);?></p>
											<a class="btn btn-sm btn-block btn-rounded btn-light" href="<?php echo get_user_page_url('coin');?>" rel="nofollow noopener noreferrer"><?php echo esc_html__('充值','ripro-v2');?></a>

										</div>
									</div>
									<div class="col-6">
										<?php $site_vip = site_vip();?>
										<div class="menu-card-box-2"><i class="fa fa-diamond nav-icon"></i>
											<span class="small"><?php echo esc_html__('本站','ripro-v2').$site_vip['vip']['name'];?></span><a class="float-right badge badge-light-lighten" href="<?php echo get_user_page_url('vip');?>" rel="nofollow noopener noreferrer"><?php echo esc_html__('开通','ripro-v2');?></a>
											<p class="small m-0"><?php printf(__('尊享%s优惠特权', 'ripro-v2'),$site_vip['vip']['name']);?></p>
										</div>
										<div class="menu-card-box-3">
											<span class="small"><?php printf(__('本站%s', 'ripro-v2'),$site_vip['boosvip']['name']);?></span><a class="float-right badge badge-light-lighten" href="<?php echo get_user_page_url('vip');?>" rel="nofollow noopener noreferrer"><?php echo esc_html__('升级','ripro-v2');?></a>
											<p class="small m-0"><?php echo esc_html__('限时开放，尊享永久','ripro-v2');?></p>
										</div>

									</div>
								</div>
					    	</div>

					    	<div class="dropdown-item-nicon">
					    		<?php $_action_opt = user_page_action_param_opt(true);
					    		$_ds_arr_opt = array('index','coin','vip','fav','order');
					    		foreach ($_ds_arr_opt as $nav) {
					    			$_this_menu = $_action_opt[$nav];
					    			echo '<a href="'.get_user_page_url($_this_menu['action']).'" rel="nofollow noopener noreferrer"><i class="'.$_this_menu['icon'].'"></i>'.$_this_menu['name'].'</a>';
					    		}
					    		?>
					    	</div>

					   </ul>
					</div>
					<!-- dropdown-menu end -->
					<?php endif;?>
					
				</li>
				<?php elseif ( (_cao('is_site_user_login',true) || _cao('is_site_user_register',true)) ) : ?>
				<a class="login-btn navbar-button ml-2" rel="nofollow noopener noreferrer" href="#"><i class="fa fa-user mr-1"></i><?php echo esc_html__('登录','ripro-v2');?></a>
				<?php endif;?>
				<!-- user navbar dropdown -->

                
		        <div class="burger"></div>

		        
		    </div>
		    
	    </div>
    </div>
</header>

<div class="header-gap"></div>

