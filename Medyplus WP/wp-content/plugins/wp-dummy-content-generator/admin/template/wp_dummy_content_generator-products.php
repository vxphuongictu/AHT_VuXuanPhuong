<div class="wp_dummy_content_generator-wrapper">

	<?php if ( class_exists( 'WooCommerce' ) ) { ?>

	<div class="wp_dummy_content_generator-top-header">
	    <h2><?php echo  wp_dummy_content_generator_PLUGIN_NAME ?> <span> - Generate Woocommerce products</span></h2>
	    <?php 
	    if(isset($_GET['status'])){
	    	if($_GET['status'] == 'success'){
	    		echo '<div class="wp_dummy_content_generator-success-msg">All products deleted successfully.</div>';
	    	}else{
	    		echo '<div class="wp_dummy_content_generator-error-msg">Something went wrong.</div>';
	    	}
	    }
		if( isset( $_GET[ 'tab' ] ) ) {
		    $active_tab = $_GET[ 'tab' ];
		}else{
			$active_tab = 'generate_products';
		}
		?>
	    <h2 class="nav-tab-wrapper">
		    <a href="?page=wp_dummy_content_generator-products&tab=generate_products" class="nav-tab <?php echo $active_tab == 'generate_products' ? 'nav-tab-active' : ''; ?>">Generate Products</a>
		    <a href="?page=wp_dummy_content_generator-products&tab=view_products" class="nav-tab <?php echo $active_tab == 'view_products' ? 'nav-tab-active' : ''; ?>">View Fake Products</a>
		</h2>
	</div>
	<div class="wp_dummy_content_generator-pagebody">
		<?php 
		if($active_tab == 'generate_products'){
			$page_slug = 'wp_dummy_content_generator-generateProducts-form';
		}else{
			$page_slug = 'wp_dummy_content_generator-listProducts';
		}
		include(WP_PLUGIN_DIR.'/'.plugin_dir_path(wp_dummy_content_generator_PLUGIN_BASE_URL) . 'admin/partials/products/'.$page_slug.'.php');
		?>
	</div>
	<div class="wp_dummy_content_generator-footer">
		
	</div> 
	<?php } else{ ?>
		<div class="wp_dummy_content_generator-pagebody">
			<div class="wp_dummy_content_generator-error-msg">This section requires woocommerce plugin to be installed and active. Please activate woocommerce plugin first.</div>
		</div>
	<?php } ?>
</div>