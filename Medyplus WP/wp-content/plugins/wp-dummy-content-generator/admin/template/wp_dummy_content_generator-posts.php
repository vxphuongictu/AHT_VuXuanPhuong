<div class="wp_dummy_content_generator-wrapper">
	<div class="wp_dummy_content_generator-top-header">
	    <h2><?php echo  wp_dummy_content_generator_PLUGIN_NAME ?> <span> - Generate Posts</span></h2>
	    <?php 
	    if(isset($_GET['status'])){
	    	if($_GET['status'] == 'success'){
	    		echo '<div class="wp_dummy_content_generator-success-msg">All posts deleted successfully.</div>';
	    	}else{
	    		echo '<div class="wp_dummy_content_generator-error-msg">Something went wrong.</div>';
	    	}
	    }
		if( isset( $_GET[ 'tab' ] ) ) {
		    $active_tab = $_GET[ 'tab' ];
		}else{
			$active_tab = 'generate_posts';
		}
		?>
	    <h2 class="nav-tab-wrapper">
		    <a href="?page=wp_dummy_content_generator-posts&tab=generate_posts" class="nav-tab <?php echo $active_tab == 'generate_posts' ? 'nav-tab-active' : ''; ?>">Generate Posts</a>
		    <a href="?page=wp_dummy_content_generator-posts&tab=view_posts" class="nav-tab <?php echo $active_tab == 'view_posts' ? 'nav-tab-active' : ''; ?>">View Fake Posts</a>
		</h2>
	</div>
	<div class="wp_dummy_content_generator-pagebody">
		<?php 
		if($active_tab == 'generate_posts'){
			$page_slug = 'wp_dummy_content_generator-generatePosts-form';
		}else{
			$page_slug = 'wp_dummy_content_generator-listPosts';
		}
		include(WP_PLUGIN_DIR.'/'.plugin_dir_path(wp_dummy_content_generator_PLUGIN_BASE_URL) . 'admin/partials/posts/'.$page_slug.'.php');
		?>
	</div>
	<div class="wp_dummy_content_generator-footer">
		
	</div>
</div>