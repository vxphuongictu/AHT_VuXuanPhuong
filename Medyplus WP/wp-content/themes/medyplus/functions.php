<?php
/* Custom Medyplus style by Vu Xuan Phuong */
define('SITE_URL', get_stylesheet_directory_uri());

function medyplus_custom_style()
{
	wp_enqueue_style('medyplus-style-css', get_template_directory_uri() . '/assets/css/style.css');
	wp_enqueue_style('medyplus-product-style-css', get_template_directory_uri() . '/assets/css/product.css');
	wp_enqueue_style('medyplus-themify-icons', get_template_directory_uri() . '/assets/css/themify-icons/themify-icons.css');
	wp_enqueue_style('medyplus-poppins-font', 'https://fonts.googleapis.com/css?family=Poppins');

	wp_enqueue_script('medyplus-pooper-js', 'https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js');
	wp_enqueue_script('medyplus-jquery-min-js', 'https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js');
	wp_enqueue_script('medyplus-use-fontawesome-js', 'https://use.fontawesome.com/8f7cc3041a.js');
	wp_enqueue_script('medyplus-kit-fontawesome-js', 'https://kit.fontawesome.com/f1af41e397.js');
	// wp_enqueue_script('medyplus-slim-min-js', 'https://code.jquery.com/jquery-3.3.1.slim.min.js');
	wp_enqueue_script('medyplus-fontawesome-js', get_stylesheet_directory_uri() . '/assets/js/custom.js');
	wp_enqueue_script('medyplus-caulate-product-price', get_stylesheet_directory_uri() . '/assets/js/product-price.js');

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

add_action('wp_enqueue_scripts', 'medyplus_custom_style');


/* Add Menu to wp */
function wp_register_menu()
{
	register_nav_menu('header-menu', __('First Top Menu'));
	register_nav_menu('header-menu-options', __('Second Top Menu'));
}

add_action('init', 'wp_register_menu');


/* footer copy right */
function copy_right()
{
	$current_year 		= date('Y');
	$design_name 		= get_theme_mod('footer_design_text_link');
	$design_link 		= '<a href="'.get_theme_mod('footer_design_link').'">'.$design_name.'</a>';
	$footer_design 		= '<div class="design"><span>@'.$current_year.' Design by '.$design_link.'. All Rights Reserved. </span></div>';
	echo $footer_design;
};
add_action('footer_copy_right', 'copy_right');

/* short code */

function header_search()
{
	get_template_part('WooCommerceCustom');
	$categories_class	= new WooCommerceCustom();
?>
<div class="search">
	<form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="POST">
		<div class="search-group">
			<div class="category">
				<?php
				if (isset($_REQUEST['product_cat']) && !empty($_REQUEST['product_cat'])) {
				  $optsetlect 	= $_REQUEST['product_cat'];
				} else {
				  $optsetlect 	= 0;
				}
				$args 				= array(
				      'show_option_all' => esc_html__('All Categories', 'woocommerce'),
				      'hierarchical' 	=> 1,
				      'class' 			=> 'cat',
				      'echo' 			=> 1,
				      'value_field' 	=> 'slug',
				      'selected' 		=> $optsetlect,
				  );
				$args['taxonomy'] 	= 'product_cat';
				$args['name'] 		= 'search_option';
				$args['class'] 		= 'cate-dropdown hidden-xs';
				wp_dropdown_categories($args);
				?>
			</div>
			<i class="fa-solid fa-caret-down"></i>
			<div class="line"></div>

			<div class="input-search">
				<input type="search" placeholder="<?php _e('Search for Product...','woocommerce'); ?>" value="<?php the_search_query(); ?>"name="s" id="<?php echo esc_attr( uniqid( 'search-form-' ) ); ?>">
				<input type="hidden" id="form_located" value="<?php echo get_template_directory_uri(); ?>">
			</div>
			<?php wp_nonce_field('search_products_nonce', 'nonce_security'); ?>
		</div>
		<button aria-label="Search" class="btn-search" title="<?php esc_attr_e('Search', 'woocommerce');?>">
			<i class="fa fa-search white" aria-hidden="true"></i>
		</button>
	</form>
</div>
<?php
}
add_shortcode('header_search', 'header_search');

/* customizer */
function customizer_medyplus($wp_customize)
{

	/* create top header section */
	$wp_customize->add_section(
		'section_location_header',
		[
			'title' 		=> __('Setting header'),
			'description' 	=> 'Top Header Setting',
			'priority' 		=> 20
		]
	);
	/* create top header section end */

	/* create fields */

	/* Location */
	$wp_customize->add_setting('content_location');
	$wp_customize->add_control(
		'content_location',
		[
			'label' 		=> 'Location',
			'section'		=> 'section_location_header',
			'type' 			=> 'text',
			'option' 		=> 'content_location'
		]
	);

	/* Email */
	$wp_customize->add_setting('content_email_header');
	$wp_customize->add_control(
		'content_email_header',
		[
			'label' 		=> 'Admin Email',
			'section'		=> 'section_location_header',
			'type' 			=> 'text',
			'option' 		=> 'content_email_header'
		]
	);

	/* Phone */
	$wp_customize->add_setting('content_phone_contact');
	$wp_customize->add_control(
		'content_phone_contact',
		[
			'label' 		=> 'Phone Contact',
			'section' 		=> 'section_location_header',
			'type' 			=> 'text',
			'option' 		=> 'content_phone_contact'
		]
	);
	/* end create fields */

	/* create section slider */
	$wp_customize->add_section(
		'section_location_slider',
		[
			'title' 		=> __('Setting Slider'),
			'description' 	=> 'Slider Setting',
			'priority' 		=> 20
		]
	);
	/* create section slider end */

	/* create otpion text in slider */
	$wp_customize->add_setting('first_text_title');
	$wp_customize->add_control(
		'first_text_title',
		[
			'label' 		=> 'Line 1',
			'section' 		=> 'section_location_slider',
			'type' 			=> 'text',
			'option' 		=> 'first_text_title'
		]
	);


	$wp_customize->add_setting('second_text_title');
	$wp_customize->add_control(
		'second_text_title',
		[
			'label' 		=> 'Line 2',
			'section' 		=> 'section_location_slider',
			'type' 			=> 'text',
			'option' 		=> 'second_text_title'
		]
	);

	$wp_customize->add_setting('content_text_title');
	$wp_customize->add_control(
		'content_text_title',
		[
			'label' 		=> 'Content',
			'section' 		=> 'section_location_slider',
			'type' 			=> 'text',
			'option' 		=> 'content_text_title'
		]
	);

	$wp_customize->add_setting('link_text_title');
	$wp_customize->add_control(
		'link_text_title',
		[
			'label' 		=> 'Link Shop',
			'section' 		=> 'section_location_slider',
			'type' 			=> 'text',
			'option' 		=> 'link_text_title'
		]
	);
	/* create otpion text in slider end */

	/* create section footer designer */
	$wp_customize->add_section(
		'section_footer', 
		[
			'title' 		=> __('Footer Section'),
			'description' 	=> 'Edit designer footer',
			'priority' 		=> 20
		]
	);

	$wp_customize->add_setting('footer_design_link');
	$wp_customize->add_control(
		'footer_design_link',
		[
			'label' 		=> 'Footer Setting Link',
			'section' 		=> 'section_footer',
			'type' 			=> 'text',
			'option' 		=> 'footer_design_link'
		]
	);

	$wp_customize->add_setting('footer_design_text_link');
	$wp_customize->add_control(
		'footer_design_text_link',
		[
			'label' 		=> 'Footer Setting Text Link',
			'section' 		=> 'section_footer',
			'type' 			=> 'text',
			'option' 		=> 'footer_design_text_link'
		]
	);
}

add_action('customize_register', 'customizer_medyplus');

/* Woocommerce */
add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
   add_theme_support( 'woocommerce' );
}

add_action( 'woocommerce_single_product_add_to_cart_custom', 'woocommerce_template_single_add_to_cart' );
add_action( 'woocommerce_template_single_meta_custom' , 'woocommerce_template_single_meta');
add_action( 'woocommerce_upsell_display_upsell', 'woocommerce_upsell_display');
add_action( 'woocommerce_output_related_products_custom', 'woocommerce_output_related_products');