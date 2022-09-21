<?php
/**
 * monsterrat functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package monsterrat
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function monsterrat_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on monsterrat, use a find and replace
		* to change 'monsterrat' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'monsterrat', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'monsterrat' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'monsterrat_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'monsterrat_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function monsterrat_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'monsterrat_content_width', 640 );
}
add_action( 'after_setup_theme', 'monsterrat_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function monsterrat_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'monsterrat' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'monsterrat' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'monsterrat_widgets_init' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}



/* Monserrat Theme */

/**
 * Enqueue scripts and styles.
 */

define('LINK_URL', get_stylesheet_directory_uri());

function monsterrat_scripts() {
	/* style */
	wp_enqueue_style( 'monsterrat-font', 'https://fonts.googleapis.com/css?family=Montserrat');
	wp_enqueue_style( 'main-style-css', LINK_URL . '/assets/style/main-style.css');
	wp_enqueue_style( 'loader-css', LINK_URL . '/assets/style/loader.css' );
	wp_enqueue_style( 'responsive-css', LINK_URL . '/assets/style/responsive.css' );
	/* style end */

	/* JS */
	wp_enqueue_script( 'add-to-wishlist-js', LINK_URL . '/assets/js/add-to-wishlist.js' );
	wp_enqueue_script( 'date-time-js', LINK_URL . '/assets/js/countdown.js' );
	wp_enqueue_script( 'fontawesome', 'https://kit.fontawesome.com/f1af41e397.js');
	wp_enqueue_script( 'main-js', LINK_URL . '/assets/js/main-js.js' );
	wp_enqueue_script( 'loader-js', LINK_URL . '/assets/js/loader.js' );
	wp_enqueue_script( 'search-js', LINK_URL . '/assets/js/search.js' );
	wp_enqueue_script( 'mail-js', LINK_URL . '/assets/js/mail.js' );
	wp_enqueue_script( 'nav-menu-js', LINK_URL . '/assets/js/nav-menu.js' );
	wp_enqueue_script( 'responsive-js', LINK_URL . '/assets/js/responsive.js' );
	wp_enqueue_script( 'slider-single-product-js', LINK_URL . '/assets/js/slider-single-product.js' );
	// wp_enqueue_script( 'notify-js', LINK_URL . '/assets/js/notify.js' );
	/* home page end */
	/* JS end */

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'monsterrat_scripts' );

/* Custom Menu */
function regsiter_menu()
{
	register_nav_menu('top-header-menu-middle', __('Top Header Menu Middle'));
	register_nav_menu('top-header-menu-right-login-in-user', __('Top Header Menu Right Login In User'));
	register_nav_menu('top-header-menu-right-none-user', __('Top Header Menu Right None User'));
	register_nav_menu('first-footer-menu', __('First Footer Menu'));
	register_nav_menu('second-footer-menu', __('Second Footer Menu'));
	register_nav_menu('third-footer-menu', __('Third Footer Menu'));
}

add_action('init', 'regsiter_menu');
/* Custom Menu End */

/* Customize */
function customize_monsterrat($wp_customize)
{
    /* footer */
    $wp_customize->add_section(
		'section_location_footer',
		[
			'title' 			=> __('Footer Setting'),
			'description'	=> 'Footer setting',
			'priority' 		=> 20
		]
	);

	$wp_customize->add_setting('footer-first-menu-location');
	$wp_customize->add_control(
		'footer-first-menu-location',
		[
			'label' 			=> 'First Menu Title',
			'section' 		=> 'section_location_footer',
			'settings' 		=> 'footer-first-menu-location'
		]
	);

	$wp_customize->add_setting('title-footer-address');
	$wp_customize->add_control(
		'title-footer-address',
		[
			'label' 			=> 'Title Footer Address',
			'section' 		=> 'section_location_footer',
			'settings' 		=> 'title-footer-address'
		]
	);

	$wp_customize->add_setting('content-footer-address');
	$wp_customize->add_control(
		'content-footer-address',
		[
			'label' 			=> 'Content Footer Address',
			'section' 		=> 'section_location_footer',
			'settings' 		=> 'content-footer-address'
		]
	);

	$wp_customize->add_setting('title-footer-hotline');
	$wp_customize->add_control(
		'title-footer-hotline',
		[
			'label' 			=> 'Title Footer Hotline',
			'section' 		=> 'section_location_footer',
			'settings' 		=> 'title-footer-hotline'
		]
	);

	$wp_customize->add_setting('content-footer-hotline');
	$wp_customize->add_control(
		'content-footer-hotline',
		[
			'label' 			=> 'Content Footer Hotline',
			'section' 		=> 'section_location_footer',
			'settings' 		=> 'content-footer-hotline'
		]
	);

	$wp_customize->add_setting('title-footer-email');
	$wp_customize->add_control(
		'title-footer-email',
		[
			'label' 			=> 'Title Footer Email',
			'section' 		=> 'section_location_footer',
			'settings' 		=> 'title-footer-email'
		]
	);

	$wp_customize->add_setting('content-footer-email');
	$wp_customize->add_control(
		'content-footer-email',
		[
			'label' 			=> 'Content Footer Email',
			'section' 		=> 'section_location_footer',
			'settings' 		=> 'content-footer-email'
		]
	);

	$wp_customize->add_setting('footer-second-menu-location');
	$wp_customize->add_control(
		'footer-second-menu-location',
		[
			'label' 			=> 'Second Menu Title',
			'section' 		=> 'section_location_footer',
			'settings' 		=> 'footer-second-menu-location'
		]
	);

	$wp_customize->add_setting('footer-third-menu-location');
	$wp_customize->add_control(
		'footer-third-menu-location',
		[
			'label' 			=> 'Third Menu Title',
			'section' 		=> 'section_location_footer',
			'settings' 		=> 'footer-third-menu-location'
		]
	);

	$wp_customize->add_setting('copyright');
	$wp_customize->add_control(
		'copyright',
		[
			'label' 			=> 'Copyright',
			'section' 		=> 'section_location_footer',
			'settings' 		=> 'copyright'
		]
	);

	$wp_customize->add_setting('design-href');
	$wp_customize->add_control(
		'design-href',
		[
			'label' 			=> 'Design Href',
			'section' 		=> 'section_location_footer',
			'settings' 		=> 'design-href'
		]
	);

	$wp_customize->add_setting('design');
	$wp_customize->add_control(
		'design',
		[
			'label' 			=> 'Design',
			'section' 		=> 'section_location_footer',
			'settings' 		=> 'design'
		]
	);

	$wp_customize->add_setting('design-logo');
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'design-logo', array(
        'label' 			=> 'Upload Design Logo',
        'priority' 		=> 20,
        'section' 		=> 'section_location_footer',
        'settings' 		=> 'design-logo',
        'button_labels' => array(// All These labels are optional
	        'select' 		=> 'Select Logo',
	        'remove' 		=> 'Remove Logo',
	        'change' 		=> 'Change Logo',
        )
    )));
    /* footer end */

    /* Single Product */
    $wp_customize->add_section(
		'section_single_product',
		[
			'title' 			=> __('Single Product Setting'),
			'description'	=> 'Single Product setting',
			'priority' 		=> 20
		]
	);

 	$wp_customize->add_setting('specification');
	$wp_customize->add_control(
		'specification',
		[
			'label' 			=> 'Specification',
			'section' 		=> 'section_single_product',
			'settings' 		=> 'specification'
		]
	);

	$wp_customize->add_setting('product_detail');
	$wp_customize->add_control(
		'product_detail',
		[
			'label' 			=> 'Product Detail',
			'section' 		=> 'section_single_product',
			'settings' 		=> 'product_detail'
		]
	);

	$wp_customize->add_setting('vehicle_summary');
	$wp_customize->add_control(
		'vehicle_summary',
		[
			'label' 			=> 'Vehicle Summary',
			'section' 		=> 'section_single_product',
			'settings' 		=> 'vehicle_summary'
		]
	);

	$wp_customize->add_setting('like_this_vehicle');
	$wp_customize->add_control(
		'like_this_vehicle',
		[
			'label'		 	=> 'Like This Vehicle Title',
			'section' 	 	=> 'section_single_product',
			'settings' 		=> 'like_this_vehicle'
		]
	);

	$wp_customize->add_setting('like_this_vehicle_column_right');
	$wp_customize->add_control(
		'like_this_vehicle_column_right',
		[
			'label'		 	=> 'Like This Vehicle Title Column Right',
			'section' 	 	=> 'section_single_product',
			'settings' 		=> 'like_this_vehicle_column_right'
		]
	);

	$wp_customize->add_setting('need_help_column_right');
	$wp_customize->add_control(
		'need_help_column_right',
		[
			'label'		 	=> 'Need Help Title Column Right',
			'section' 	 	=> 'section_single_product',
			'settings' 		=> 'need_help_column_right'
		]
	);

	$wp_customize->add_setting('location_column_right');
	$wp_customize->add_control(
		'location_column_right',
		[
			'label'		 	=> 'Location Content Column Right',
			'section' 	 	=> 'section_single_product',
			'settings' 		=> 'location_column_right'
		]
	);

	$wp_customize->add_setting('email_column_right');
	$wp_customize->add_control(
		'email_column_right',
		[
			'label'		 	=> 'Email Content Column Right',
			'section' 	 	=> 'section_single_product',
			'settings' 		=> 'email_column_right'
		]
	);

	$wp_customize->add_setting('phone_column_right');
	$wp_customize->add_control(
		'phone_column_right',
		[
			'label'		 	=> 'Phone Content Column Right',
			'section' 	 	=> 'section_single_product',
			'settings' 		=> 'phone_column_right'
		]
	);

	/* Single Product end */

	/* login page */
	$wp_customize->add_section(
		'login_page',
		[
			'title' 			=> __('Login Page Settings'),
			'description' 	=> 'Login Page Settings',
			'priority' 		=> 20
		]
	);

	$wp_customize->add_setting('background-image-login-page');
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'background-image-login-page', array(
        'label' 			=> 'Change Background Image Login Page',
        'priority' 		=> 20,
        'section' 		=> 'login_page',
        'settings' 		=> 'background-image-login-page',
        'button_labels' => array(// All These labels are optional
	        'select' 		=> 'Select Image',
	        'remove' 		=> 'Remove Image',
	        'change' 		=> 'Change Image',
        )
    )));

	$wp_customize->add_setting('login-title');
	$wp_customize->add_control(
		'login-title',
		[
			'label'		 	=> 'Login Page - Login Title',
			'section' 	 	=> 'login_page',
			'settings' 		=> 'login-title'
		]
	);

	$wp_customize->add_setting('login-content');
	$wp_customize->add_control(
		'login-content',
		[
			'label'		 	=> 'Login Page - Login Content',
			'section' 	 	=> 'login_page',
			'settings' 		=> 'login-content'
		]
	);

	$wp_customize->add_setting('login-label-remember');
	$wp_customize->add_control(
		'login-label-remember',
		[
			'label'		 	=> 'Login Page - Remember Me Label',
			'section' 	 	=> 'login_page',
			'settings' 		=> 'login-label-remember'
		]
	);

	$wp_customize->add_setting('btn-login-label');
	$wp_customize->add_control(
		'btn-login-label',
		[
			'label'		 	=> 'Login Page - Login Button Label',
			'section' 	 	=> 'login_page',
			'settings' 		=> 'btn-login-label'
		]
	);

	$wp_customize->add_setting('lost-password-label');
	$wp_customize->add_control(
		'lost-password-label',
		[
			'label'		 	=> 'Login Page - Lost Password Label',
			'section' 	 	=> 'login_page',
			'settings' 		=> 'lost-password-label'
		]
	);

	$wp_customize->add_setting('lost-password-url');
	$wp_customize->add_control(
		'lost-password-url',
		[
			'label'		 	=> 'Login Page - Lost Password Redirect Url',
			'section' 	 	=> 'login_page',
			'settings' 		=> 'lost-password-url'
		]
	);

	$wp_customize->add_setting('label-regsiter');
	$wp_customize->add_control(
		'label-regsiter',
		[
			'label'		 	=> 'Login Page - Button Regsiter Label',
			'section' 	 	=> 'login_page',
			'settings' 		=> 'label-regsiter'
		]
	);

	$wp_customize->add_setting('login-logo');
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'login-logo', array(
        'label' 			=> 'Change Login Logo',
        'priority' 		=> 20,
        'section' 		=> 'login_page',
        'settings' 		=> 'login-logo',
        'button_labels' => array(// All These labels are optional
	        'select' 		=> 'Select Logo',
	        'remove' 		=> 'Remove Logo',
	        'change' 		=> 'Change Logo',
        )
    )));

	$wp_customize->add_setting('regsiter-title');
	$wp_customize->add_control(
		'regsiter-title',
		[
			'label'		 	=> 'Register Page - Regsiter Title',
			'section' 	 	=> 'login_page',
			'settings' 		=> 'regsiter-title'
		]
	);

	$wp_customize->add_setting('regsiter-content');
	$wp_customize->add_control(
		'regsiter-content',
		[
			'label'		 	=> 'Register Page - Regsiter Content',
			'section' 	 	=> 'login_page',
			'settings' 		=> 'regsiter-content'
		]
	);

	$wp_customize->add_setting('agree-content');
	$wp_customize->add_control(
		'agree-content',
		[
			'label'		 	=> 'Register Page - Agree Content',
			'section' 	 	=> 'login_page',
			'settings' 		=> 'agree-content'
		]
	);

	$wp_customize->add_setting('label-login-btn-register-page');
	$wp_customize->add_control(
		'label-login-btn-register-page',
		[
			'label'		 	=> 'Register Page - Button Login Label',
			'section' 	 	=> 'login_page',
			'settings' 		=> 'label-login-btn-register-page'
		]
	);

	$wp_customize->add_setting('placeholder-fullname');
	$wp_customize->add_control(
		'placeholder-fullname',
		[
			'label'		 	=> 'Placeholder Full Name',
			'section' 	 	=> 'login_page',
			'settings' 		=> 'placeholder-fullname'
		]
	);

	$wp_customize->add_setting('placeholder-phonenumber');
	$wp_customize->add_control(
		'placeholder-phonenumber',
		[
			'label'		 	=> 'Placeholder Phone Number',
			'section' 	 	=> 'login_page',
			'settings' 		=> 'placeholder-phonenumber'
		]
	);

	$wp_customize->add_setting('placeholder-email');
	$wp_customize->add_control(
		'placeholder-email',
		[
			'label'		 	=> 'Placeholder Email',
			'section' 	 	=> 'login_page',
			'settings' 		=> 'placeholder-email'
		]
	);

	$wp_customize->add_setting('placeholder-username');
	$wp_customize->add_control(
		'placeholder-username',
		[
			'label'		 	=> 'Placeholder Username',
			'section' 	 	=> 'login_page',
			'settings' 		=> 'placeholder-username'
		]
	);

	$wp_customize->add_setting('placeholder-password');
	$wp_customize->add_control(
		'placeholder-password',
		[
			'label'		 	=> 'Placeholder Password',
			'section' 	 	=> 'login_page',
			'settings' 		=> 'placeholder-password'
		]
	);

	$wp_customize->add_setting('placeholder-confirmpwd');
	$wp_customize->add_control(
		'placeholder-confirmpwd',
		[
			'label'		 	=> 'Placeholder Confirm Password',
			'section' 	 	=> 'login_page',
			'settings' 		=> 'placeholder-confirmpwd'
		]
	);
	/* login page end */

	/* products */
	$wp_customize->add_section(
		'product_page',
		[
			'title' 			=> __('Product Page Settings'),
			'description' 	=> 'Product Page Settings',
			'priority' 		=> 20
		]
	);

	$wp_customize->add_setting('maximum-show-gallery');
	$wp_customize->add_control(
		'maximum-show-gallery',
		[
			'label'		 	=> 'Maximum Show Gallery',
			'section' 	 	=> 'product_page',
			'settings' 		=> 'maximum-show-gallery'
		]
	);
	/* end products */
}

add_action('customize_register', 'customize_monsterrat');
/* Customize End */

/* Short code */
function text_style($args)
{
	echo '<font style="color: '.$args['color'].'; font-weight: '.$args['font_weight'].'">'.$args['content'].'</font>';
}
add_shortcode('text_style', 'text_style');
/* Short code end */

/* woocommerce */
add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
   add_theme_support( 'woocommerce' );
}

############### action hook ################
#
add_action('woocommerce_template_single_title_custom', 'woocommerce_template_single_title');
add_action('woocommerce_show_product_images_custom', 'woocommerce_show_product_images');

/* home page */
function home_scripts()
{
	echo '<script src="'.get_template_directory_uri().'/assets/js/home.js"></script>';
	// echo '<script></script>';
}
add_action('home_scripts', 'home_scripts'); // call action hook to load scripts at home page
/* home page end */

/* login page */
function login_scripts()
{
	echo '<script src="'.get_template_directory_uri().'/assets/js/login.js"></script>';
	// echo '<script></script>';
}
add_action('login_scripts', 'login_scripts'); // call action hook to load scripts at home page

function single_product()
{
	echo '<script src="'.get_template_directory_uri().'/assets/js/single-product.js"></script>';
}
add_action('single_product_scripts', 'single_product'); // call action hook to load scripts at product page
/* login page end */

############### action hook end ###############

/* redirect after logout */
function redirect_after_logout()
{
	wp_redirect(home_url());
}
add_action('wp_logout', 'redirect_after_logout');

############### Ajax Request ###############


/* get data on db */
add_action('wp_ajax_getData', 'fnc_getData');
add_action('wp_ajax_nopriv_getData', 'fnc_getData');

function fnc_getData()
{
	$result 	= []; // it's container data

	/* load woocommerce custom */
	get_template_part('/template-parts/WoocommerceCustom');
	$woocommerce_custom 	= new WoocommerceCustom();
	/* load woocommerce custom end */

	if (is_user_logged_in()){
		$all_wish_list 		= $woocommerce_custom->get_all_wish_list();
	}

	$result['wish_list_data'] 	= $all_wish_list;

	/* get all categories name */
	$categories 				= $woocommerce_custom->wc_get_all_categories();
	$result['categories_data'] 	= $categories;
	/* get all categories name end */

	/* get all products by category */
	$arr_product_by_category	= [];
	$arr_product 				= [];
	foreach($categories as $category)
	{
		$arr_product_by_category[]	= $woocommerce_custom->get_info_products($category['category_name']);
	}

	foreach($arr_product_by_category as $product_name)
	{
		foreach($product_name as $product)
		{
			$arr_product[] 		= $product;
		}
	}
	$result['products_data'] 	= $arr_product; // all products
	/* get all products by category end */
	wp_send_json_success($result);
	die();
}
/* get data on db end */

/* Register User */
add_action('wp_ajax_register', 'fnc_register');
add_action('wp_ajax_nopriv_register', 'fnc_register');

function fnc_register(){
	if( !wp_verify_nonce( $_POST['register_nonce'], 'register_security' ) ) {
	  $msg  			= "the security key does not valid";
	  $status 			= false;
	} else {

		$full_name 	 	 	= htmlspecialchars($_POST['full_name']);
		$phone_number 	 	= htmlspecialchars($_POST['user_register']);
		$email 				= htmlspecialchars($_POST['email_register']);
		$password 			= htmlspecialchars($_POST['new_pass']);
		$confirm_password 	= htmlspecialchars($_POST['user_confirm_pass']);
		$agree 				= htmlspecialchars($_POST['agree']);

		if (($full_name != '') && ($phone_number != '') && ($email != '') && ($password != '') && ($confirm_password != '') && ($agree == "checked"))
		{
			if ($password == $confirm_password)
			{
				$userdata  		= [
					'user_login' 	=> $phone_number,
					'user_pass' 	=> $password,
					'user_email' 	=> $email,
					'first_name' 	=> $full_name,
					'user_nicename' => $full_name,
					'role' 			=> 'customer'
				];
				$user_id 		= wp_insert_user($userdata);
				if (!is_wp_error($user_id))
				{
					$msg  			= "register successfully";
		  			$status 		= true;
				} else {
					$msg  			= "register fail";
		  			$status 		= false;
				}
			} else {
				$msg  			= "the enter confirm password does not same";
				$status 		= false;
			}
		} else {
			$msg  			= "please enter full information";
			$status 		= false;
		}
	}

	$result 			= [
		'status' 		=> $status,
		'msg' 			=> $msg
	];
	wp_send_json_success($result);
	die();
}
/* Register User End*/

/* Login User */
add_action('wp_ajax_login', 'fnc_login');
add_action('wp_ajax_nopriv_login', 'fnc_login');

function fnc_login(){
	if( !wp_verify_nonce( $_POST['login_nonce'], 'login_security' ) ) {
	  $msg  			= "the security key does not valid";
	  $status 			= false;
	} else {

		$user_login 	 	= htmlspecialchars($_POST['user_login']);
		$user_pass 	 		= htmlspecialchars($_POST['user_pass']);
		$rememberme 		= htmlspecialchars($_POST['rememberme']);

		if (($user_login != '') && ($user_pass != ''))
		{
			$creds = array(
				'user_login'    => $user_login,
				'user_password' => $user_pass,
				'remember'      => $rememberme == 'forever' ? true : false
			);

			$user = wp_signon( $creds, false );

			if ( is_wp_error( $user ) ) {
				$msg  			= "The username is not registered on this site. If you are unsure of your username, try your email address instead.";
				$status 		= false;
			} else {
				$msg  			= "Login successfully";
				$status 		= true;
			}
		} else {
			$msg  			= "please enter full information";
			$status 		= false;
		}
	}

	$result 			= [
		'status' 		=> $status,
		'msg' 			=> $msg
	];
	wp_send_json_success($result);
	die();
}
/* Login User End */

/* User send mail */
add_action('wp_ajax_userMail', 'fnc_userMail');
add_action('wp_ajax_nopriv_userMail', 'fnc_userMail');

function fnc_userMail()
{
	$admin_email 	= get_option( 'admin_email' );
	$current_user 	= wp_get_current_user();
	$user_email 	= $current_user->user_email;
	$subject 		= "Quick Quote Of ".$current_user->user_login;
	$message 		= htmlspecialchars($_POST['message']);
	$headers 		= "";
	$attachments 	= "";

	if ($message == "")
	{
		$msg  		= "please enter your message";
		$status 		= false;
	} else if ($admin_email == "")
	{
		$msg  		= "Adminstrator hasn't set up email contact";
		$status 		= false;
	} else {
		$send 	 	= wp_mail( $admin_email, $subject, $message, $headers, $attachments );
		$msg  		= "Email is sent successfully";
		$status 		= true;
	}

	$result 			= [
		'status' 		=> $status,
		'msg' 			=> $msg
	];
	wp_send_json_success($result);
	die();
}
/* User send mail end */

/* Guest send mail */
add_action('wp_ajax_guestMail', 'fnc_guestMail');
add_action('wp_ajax_nopriv_guestMail', 'fnc_guestMail');

function fnc_guestMail()
{
	$admin_email 	= get_option( 'admin_email' );
	$email 			= htmlspecialchars($_POST['email']);
	$full_name 		= htmlspecialchars($_POST['full_name']);
	$phone_number 	= htmlspecialchars($_POST['phone_number']);
	$subject 		= "Quick Quote Of ".$full_name;
	$message 		= htmlspecialchars($_POST['message']);
	$headers 		= "";
	$attachments 	= "";

	if ($admin_email == "")
	{
		$msg  		= "Adminstrator hasn't set up email contact";
		$status 		= false;
	} else if ($email == "")
	{
		$msg  		= "please enter your email";
		$status 		= false;
	} else if ($full_name == "")
	{
		$msg  		= "please enter your name";
		$status 		= false;
	} else if ($phone_number == "")
	{
		$msg  		= "please enter your phone number";
		$status 		= false;
	} else if ($message == "")
	{
		$msg  		= "please enter your message";
		$status 		= false;
	} else  if ($email == "")
	{
		$msg  		= "You need set up your email address after do this action";
		$status 		= false;
	} else {
		$message 	= $message . "My phone number: ".$phone_number;
		$send 	 	= wp_mail( $email, $subject, $message, $headers, $attachments );
		$msg  		= "Email is sent successfully";
		$status 		= true;
	}

	$result 			= [
		'status' 		=> $status,
		'msg' 			=> $msg
	];
	wp_send_json_success($result);
	die();
}
/* Guest send mail end */
############### Ajax Request End ###############

/* Disable WordPress Admin Bar for all users */
add_filter( 'show_admin_bar', '__return_false' );