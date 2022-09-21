<div class="wp_dummy_content_generator-wrapper">
	<div class="wp_dummy_content_generator-top-header">
	    <h2><?php echo  wp_dummy_content_generator_PLUGIN_NAME ?> <span> - Generate Users</span></h2>
	    <?php
	    if(isset($_GET['status'])){
	    	if($_GET['status'] == 'success'){
	    		echo '<div class="wp_dummy_content_generator-success-msg">All users deleted successfully.</div>';
	    	}else{
	    		echo '<div class="wp_dummy_content_generator-error-msg">Something went wrong.</div>';
	    	}
	    }
		if( isset( $_GET[ 'tab' ] ) ) {
		    $active_tab = $_GET[ 'tab' ];
		}else{
			$active_tab = 'generate_users';
		}
		?>
	    <h2 class="nav-tab-wrapper">
		    <a href="?page=wp_dummy_content_generator-users&tab=generate_users" class="nav-tab <?php echo $active_tab == 'generate_users' ? 'nav-tab-active' : ''; ?>">Generate Users</a>
		    <a href="?page=wp_dummy_content_generator-users&tab=view_users" class="nav-tab <?php echo $active_tab == 'view_users' ? 'nav-tab-active' : ''; ?>">View Fake Users</a>
		</h2>
	</div>
	<div class="wp_dummy_content_generator-pagebody">
		<?php 
		if($active_tab == 'generate_users'){
			$page_slug = 'wp_dummy_content_generator-generateUsers-form';
		}else{
			$page_slug = 'wp_dummy_content_generator-listUsers';
		}
		include(WP_PLUGIN_DIR.'/'.plugin_dir_path(wp_dummy_content_generator_PLUGIN_BASE_URL) . 'admin/partials/users/'.$page_slug.'.php');
		?>
	</div>
	<div class="wp_dummy_content_generator-footer">
		
	</div>
</div>

