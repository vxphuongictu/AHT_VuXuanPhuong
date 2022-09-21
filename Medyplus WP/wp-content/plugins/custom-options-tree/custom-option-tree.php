<?php
/*
Plugin Name: Custom Options Tree
Plugin URI: https://wordpress.org/plugins/custom-options-tree/
Description: Easily changes of header logo , google analytical code , footer code , footer logo, footer copyright text, footer design and develop text and social media(facebook, twitter, linkedin, instagram & Google plus)
Version: 1.3.1
Author: Vishit Shah
Author URI: https://www.linkedin.com/in/vishit-shah-5b393383/
License: GPLv2
Text Domain : cot
*/

/* Plugin Licence

Copyright 2014 VISHIT SHAH (email : vishit99@gmail.com)
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/

// Make sure we don't expose any info if called directly
if ( basename( $_SERVER['PHP_SELF'] ) == basename( __FILE__ ) ) {
	die( 'Sorry, but you cannot access this page directly.' );
}

add_action('admin_menu', 'add_custom_option_tree');
function add_custom_option_tree() {
	add_theme_page('Custom Option Tree', 'Custom Option Tree', 'manage_options', 'edit-custom-option-tree', 'cot_display');
}
 
/**
 * Retrieves plugin options if they exist or returns default values if not
 *
 * @since Custom Options Tree 1.0
 *
 * @return array of Custom Options Trees.
 */
 function cot_getoptions() {
	 if( get_option( 'cot_options' ) === false ) {
		$cot_options['header_image_path'] = $cot_options['header_favicon_icon_path'] = $cot_options['header_global_code'] = $cot_options['header_code'] = $cot_options['footer_image_path'] = $cot_options['copy_right'] = $cot_options['design_development'] = $cot_options['footer_code'] = $cot_options['facebook'] = $cot_options['twitter'] = $cot_options['linkedin'] = $cot_options['instagram'] = $cot_options['google_plus'] = $cot_options['custom_option1'] = $cot_options['custom_option2'] = '';
	 }
	 else {
		$cot_options = get_option( 'cot_options' );
		if( ! isset( $cot_options['header_image_path'] ) ){
			$cot_options['header_image_path'] = "";
		}
		if( ! isset( $cot_options['header_favicon_icon_path'] ) ){
			$cot_options['header_favicon_icon_path'] = "";
		}
		if( ! isset( $cot_options['header_global_code'] ) ){
			$cot_options['header_global_code'] = "";
		}
		if( ! isset( $cot_options['header_code'] ) ){
			$cot_options['header_code'] = "";
		}
		if( ! isset( $cot_options['footer_image_path'] ) ){
			$cot_options['footer_image_path'] = "";
		}
		if( ! isset( $cot_options['copy_right'] ) ){
			$cot_options['copy_right'] = "";
		}
		if( ! isset( $cot_options['design_development'] ) ){
			$cot_options['design_development'] = "";
		}
		if( ! isset( $cot_options['footer_code'] ) ){
			$cot_options['footer_code'] = "";
		}
		if( ! isset( $cot_options['facebook'] ) ){
			$cot_options['facebook'] = "";
		}
		if( ! isset( $cot_options['twitter'] ) ){
			$cot_options['twitter'] = "";
		}
		if( ! isset( $cot_options['linkedin'] ) ){
			$cot_options['linkedin'] = "";
		}
		if( ! isset( $cot_options['instagram'] ) ){
			$cot_options['instagram'] = "";
		}
		if( ! isset( $cot_options['google_plus'] ) ){
			$cot_options['google_plus'] = "";
		}
		if( ! isset( $cot_options['custom_option1'] ) ){
			$cot_options['custom_option1'] = "";
		}
		if( ! isset( $cot_options['custom_option2'] ) ){
			$cot_options['custom_option2'] = "";
		}
	}
	return $cot_options;
 }

 function cot_display() {
 ?>
	<div class="wrap">
        <h1 class="main-title">Custom Theme Option Settings</h1>
		<p class="description" >Add theme options to your theme hassle free</p>
        <?php settings_errors(); ?> 
        <div class="custom_option">
			<form method="post" action="options.php"  class="customtheme"> 
				<?php
					settings_fields( 'cot_options' );
					do_settings_sections( 'cot_headersection' );
					do_settings_sections( 'cot_footersection' );
					do_settings_sections( 'cot_socialmedia' );				
					do_settings_sections( 'cot_globalsection' );					
				?>
				<input type="submit" name="submit" value="Save Setting" class="button-primary" />
			</form> 
		</div>
	</div>
 <?php }
 add_action( 'admin_init', 'cot_register_settings' );

function cot_register_settings(){
	/**
	 * Registers Header section for Custom theme option
	 */
	register_setting( 'cot_options', 'cot_options', 'cot_validate_options' );
	
	/**
	 * Add a settings header section
	 */
	add_settings_section( 'cot_section', 'Header Section', 'cot_headertext', 'cot_headersection' );
	
	/**
	 * Adds a setting field  header logo image selection
	 */
	add_settings_field( 'cot_headerlogo', 'Header Logo', 'cot_headerlogo', 'cot_headersection', 'cot_section' );
	
	/**
	 * Adds a setting field  Favicon Icon image selection
	 */
	add_settings_field( 'cot_faviconicon', 'Favicon Icon', 'cot_faviconicon', 'cot_headersection', 'cot_section' );
	
	/**
	 * Adds a settings footer section
	 */
	add_settings_section( 'cot_section', 'Footer Section', 'cot_footertext', 'cot_footersection' );
	
	/**
	 * Adds a setting field  Footer logo image selection
	 */
	add_settings_field( 'cot_footerlogo', 'Footer Logo', 'cot_footerlogo', 'cot_footersection', 'cot_section' );
	
	/**
	 * Adds a setting field  Footer Copy right text selection	
	 */
	add_settings_field( 'cot_copyrighttext', 'Footer Copy Right Text', 'cot_copyrighttext', 'cot_footersection', 'cot_section' );
	
	/**
	 * Adds a setting field  Footer design & develop text selection
	 */
	add_settings_field( 'cot_designdevelopmenttext', 'Design & Development Text', 'cot_designdevelopmenttext', 'cot_footersection', 'cot_section' );
	
	/**
	 * Adds a settings social media section
	 */
	add_settings_section( 'cot_section', 'Social Media', 'cot_socialmediatext', 'cot_socialmedia' );
	
	/**
	 * Adds a setting field  facebook link
	 */
	add_settings_field( 'cot_facebooklink', 'Facebook', 'cot_facebooklink', 'cot_socialmedia', 'cot_section' );
	
	/**
	 * Adds a setting field  twitter link
	 */
	add_settings_field( 'cot_twitterlink', 'Twitter', 'cot_twitterlink', 'cot_socialmedia', 'cot_section' );
	
	/**
	 * Adds a setting field  linkedin link
	 */
	add_settings_field( 'cot_linkedinlink', 'Linkedin', 'cot_linkedinlink', 'cot_socialmedia', 'cot_section' );
	
	/**
	 * Adds a setting field  instagram link
	 */
	add_settings_field( 'cot_instagramlink', 'Instagram', 'cot_instagramlink', 'cot_socialmedia', 'cot_section' );
	
	/**
	 * Adds a setting field  google plus link
	 */
	add_settings_field( 'cot_googlepluslink', 'Google +', 'cot_googlepluslink', 'cot_socialmedia', 'cot_section' );
	
	/**
	 * Adds a setting field  Custom Option link
	 */
	add_settings_field( 'cot_customoption1link', 'Custom 1', 'cot_customoption1link', 'cot_socialmedia', 'cot_section' );
	
	/**
	 * Adds a setting field  Custom Option link
	 */
	add_settings_field( 'cot_customoption2link', 'Custom 2', 'cot_customoption2link', 'cot_socialmedia', 'cot_section' );
	
	/**
	 * Add a settings Global section
	 */
	add_settings_section( 'cot_section', 'Global Section', 'cot_globaltext', 'cot_globalsection' );
	
	/**
	 * Adds a setting field  header google analytical code selection
	 */
	add_settings_field( 'cot_globalheadercode', 'Google Analytical Code', 'cot_globalheadercode', 'cot_globalsection', 'cot_section' );
	
	/**
	 * Adds a setting field  other header code selection
	 */
	add_settings_field( 'cot_otherheadercode', 'Other Code <br/>add to header.php', 'cot_otherheadercode', 'cot_globalsection', 'cot_section' );
	
	/**
	 * Adds a setting field  other footer code selection
	 */
	add_settings_field( 'cot_otherfootercode', 'Other Code </br>add to footer.php', 'cot_otherfootercode', 'cot_globalsection', 'cot_section' );
}

function cot_headertext() {
	
}
function cot_footertext() {
	
}

function cot_socialmediatext() {
	
}
function cot_globaltext(){

}

//Header Logo Function 
function cot_headerlogo () {
	$options = cot_getoptions();	
	$cot_headerlogo = $options['header_image_path'];
?>
	<p>
		<input id="upload_header_image_button" type="button" value="Media Library" class="button-secondary" />
		<input id="cot_header_logo_image" class="regular-text code" type="text" name="cot_options[header_image_path]" value="<?php echo esc_attr($cot_headerlogo); ?>">
		<span class="notice-shortcode">
			<?php esc_html_e('Use this shortcode for header logo','cot'); ?>
			<b>
				<?php echo htmlspecialchars("<?php cot_showheaderlogo(); ?>"); ?>
			</b>
		</span>
	</p>
	<p class="description"><?php esc_html_e('Enter an image URL or use an image from media library.', 'cot'); ?></p> 
	<?php if(empty($cot_headerlogo)) { 
		} else { ?>
	<img id="cot_customtheme_admin_preview" alt="header-logo" src="<?php echo $cot_headerlogo; ?>" /></span>
	<?php }
}

//Header Logo Function 
function cot_faviconicon () {
	$options = cot_getoptions();	
	$cot_faviconicon = $options['header_favicon_icon_path'];
?>
	<p>
		<input id="upload_favicon_icon_button" type="button" value="Media Library" class="button-secondary" />
		<input id="cot_favicon_icon_image" class="regular-text code" type="text" name="cot_options[header_favicon_icon_path]" value="<?php echo esc_attr($cot_faviconicon); ?>">
		<span class="notice-shortcode"><?php esc_html_e('Use this shortcode for favicon icon','cot'); ?> <b><?php echo htmlspecialchars("<?php cot_showfaviconicon(); ?>"); ?></b></span>
	</p>
	<p class="description"><?php esc_html_e('Enter an image URL or use an image from media library.', 'cot' ); ?></p> 
	<?php if(empty($cot_faviconicon)) { 
		} else { ?>
	<img id="cot_favicon_admin_preview" alt="favicon-icon" src="<?php echo $cot_faviconicon; ?>" /></span>
	<?php }
}

//Header Google Analytical Function
function cot_globalheadercode() {
	$options = cot_getoptions();
	$cot_headerglobalcode = $options['header_global_code']; ?>
	<textarea class="text-box" cols="10" rows="8" placeholder="Google analytical Code" name="cot_options[header_global_code]" ><?php echo htmlspecialchars($cot_headerglobalcode); ?></textarea>
	<p class="description"><?php esc_html_e('Add your Google Analytics code here.', 'cot'); ?></p>
<?php
}

//Header Another Code Function
function cot_otherheadercode() {
	$options = cot_getoptions();
	$cot_headerothercode = $options['header_code']; ?>
	<textarea class="text-box" cols="10" rows="8" name="cot_options[header_code]" ><?php echo htmlspecialchars($cot_headerothercode); ?></textarea>
	<p class="description"><?php esc_html_e('Any custom code that needs to be added to header.php', 'cot'); ?></p>
<?php
}

//Footer Logo Function  
function cot_footerlogo() {
	$options = cot_getoptions();
	$cot_footerlogo = $options['footer_image_path'];
?>
	<p>
		<input id="upload_footer_image_button" type="button" value="Media Library" class="button-secondary" />
		<input id="cot_footer_logo_image" class="regular-text code" type="text" name="cot_options[footer_image_path]" value="<?php echo esc_attr($cot_footerlogo); ?>"> 
		<?php echo '<span class="notice-shortcode">Use this shortcode for footer logo <b>'. htmlspecialchars("<?php cot_showfooterlogo(); ?>") . '</b> .</span>';  ?>
	</p>
	<p class="description"><?php esc_html_e('Enter an image URL or use an image from media library.', 'cot'); ?></p> 
	<?php if(empty($cot_footerlogo)) { 
		} else { ?>
	<img id="cot_customtheme_footer_admin_preview" alt="footer-logo" src="<?php echo $cot_footerlogo; ?>" /></span>
	<?php }

}

//Footer Copyright Text Funxtion
function cot_copyrighttext() {
	$options = cot_getoptions();
	$cot_copyright = $options['copy_right'];
?>
	<p><input class="text-box" type="text" placeholder="&copy; 2021 Wordpress" name="cot_options[copy_right]" value="<?php echo esc_attr($cot_copyright); ?>">
	<span class="notice-shortcode"><?php esc_html_e( 'Use this shortcode for copy right text', 'cot' ); ?><b> <?php echo htmlspecialchars("<?php cot_copyright(); ?>"); ?></b> </span></p>
<?php
}

//Footer Design & Development Text Funxtion
function cot_designdevelopmenttext() {
$options = cot_getoptions();
	$cot_designdevelopent = $options['design_development'];
?>
<p>
	<input class="text-box" type="text" placeholder="Design & Developed By Vishit Shah" name="cot_options[design_development]" value="<?php echo esc_attr($cot_designdevelopent); ?>">
	<span class="notice-shortcode"><?php esc_html_e( 'Use this shortcode for design & development text.', 'cot' ); ?><b><?php echo htmlspecialchars("<?php cot_designdevelopent(); ?>"); ?></b></span>
</p>
<?php
}

//Footer Another Code Function
function cot_otherfootercode(){
	$options = cot_getoptions();
	$cot_footerglobalcode = $options['footer_code']; ?>
	<textarea class="text-box" cols="10" rows="8" name="cot_options[footer_code]" ><?php echo htmlspecialchars($cot_footerglobalcode); ?></textarea>
	<p class="description"><?php esc_html_e('Any custom code that needs to be added to footer.php', 'cot'); ?></p>
<?php
}

//Social Media Facebook Function
function cot_facebooklink() {
	$options = cot_getoptions();
	$cot_facebookurl = $options['facebook'];
?>
	<p>
		<input class="text-box" type="text" placeholder="http://facebook.com/yourprofileurl" name="cot_options[facebook]" value="<?php echo esc_attr($cot_facebookurl); ?>">
		<span class="notice-shortcode"><?php esc_html_e('Use this shortcode for Facebook link' ,'cot'); ?> <b><?php echo htmlspecialchars("<?php cot_facebook(); ?>"); ?></b></span>
	</p>
<?php }

//Social Media Twitter Function
function cot_twitterlink() {
	$options = cot_getoptions();
	$cot_twitterurl = $options['twitter'];
?>
	<p>
		<input class="text-box" type="text" placeholder="http://twitter.com/yourprofileurl" name="cot_options[twitter]" value="<?php echo esc_attr($cot_twitterurl); ?>">
		<span class="notice-shortcode"><?php esc_html_e('Use this shortcode for Twitter link' , 'cot'); ?> <b><?php echo htmlspecialchars("<?php cot_twitter(); ?>"); ?></b></span>
	</p>
<?php }

//Social Media Linkedin Function
function cot_linkedinlink() {
	$options = cot_getoptions();
	$cot_linkedinurl = $options['linkedin'];
?>
	<p>
		<input class="text-box" type="text" placeholder="https://www.linkedin.com/yourprofileurl" name="cot_options[linkedin]" value="<?php echo esc_attr($cot_linkedinurl); ?>">
		<span class="notice-shortcode"><?php esc_html_e('Use this shortcode for Linkedin link','cot' );?> <b><?php echo htmlspecialchars("<?php cot_linkedin(); ?>"); ?></b></span>
	</p>
<?php }

//Social Media Instagram Function
function cot_instagramlink() {
	$options = cot_getoptions();
	$cot_instagramurl = $options['instagram'];
?>
	<p>
		<input class="text-box" type="text" placeholder="https://www.instagram.com/yourprofileurl" name="cot_options[instagram]" value="<?php echo esc_attr($cot_instagramurl); ?>">
		<span class="notice-shortcode"><?php esc_html_e('Use this shortcode for Instagram link', 'cot'); ?> <b><?php echo htmlspecialchars("<?php cot_instagram(); ?>"); ?></b></span>
	</p>
<?php }

//Social Media Google Plus Function
function cot_googlepluslink() {
	$options = cot_getoptions();
	$cot_googleplusurl = $options['google_plus'];
?>
	<p>
		<input class="text-box" type="text" placeholder="https://plus.google.com/xxxxxxxxx/posts" name="cot_options[google_plus]" value="<?php echo esc_attr($cot_googleplusurl); ?>">
		<span class="notice-shortcode"><?php esc_html_e('Use this shortcode for Google + link','cot'); ?> <b><?php echo htmlspecialchars("<?php cot_googleplus(); ?>"); ?></b>
	</p>
<?php }

//Social Media Custom option Function
function cot_customoption1link() {
	$options = cot_getoptions();
	$cot_customoptionurl1 = $options['custom_option1'];
?>
	<p>
		<input class="text-box" type="text" placeholder="http://www.anyurl.com" name="cot_options[custom_option1]" value="<?php echo esc_attr($cot_customoptionurl1); ?>">
		<span class="notice-shortcode"><?php esc_html_e('Use this shortcode for Custom Option link','cot' ); ?> <b><?php echo htmlspecialchars("<?php cot_customoption1(); ?>"); ?></b>
	</p>
<?php }

//Social Media Custom option Function
function cot_customoption2link() {
	$options = cot_getoptions();
	$cot_customoptionurl2 = $options['custom_option2'];
?>
	<p>
		<input class="text-box" type="text" placeholder="http://www.anyurl.com" name="cot_options[custom_option2]" value="<?php echo esc_attr($cot_customoptionurl2); ?>">
		<span class="notice-shortcode"><?php esc_html_e('Use this shortcode for Custom Option link','cot'); ?> <b><?php echo htmlspecialchars("<?php cot_customoption2(); ?>"); ?></b>
	</p>
<?php }

/**
 * Validates the form submission by user
 *
 * @since Custom Options Tree 1.0
 */
function cot_section($input) {
	$input['header_image_path'] = esc_url( $input['header_image_path'] );
	$input['header_favicon_icon_path'] = esc_url( $input['header_favicon_icon_path'] );
	$input['header_global_code'] = esc_url( $input['header_global_code'] );
	$input['header_code'] = esc_url( $input['header_code'] );
	$input['footer_image_path'] = esc_url( $input['footer_image_path'] );
	$input['copy_right'] = esc_url( $input['copy_right'] );
	$input['design_development'] = esc_url( $input['design_development'] );
	$input['footer_code'] = esc_url( $input['footer_code'] );
	$input['url'] = esc_url( $input['url'] );
	$input['facebook'] = esc_url( $input['facebook'] );
	$input['twitter'] = esc_url( $input['twitter'] );
	$input['linkedin'] = esc_url( $input['linkedin'] );
	$input['instagram'] = esc_url( $input['instagram'] );
	$input['google_plus'] = esc_url( $input['google_plus'] );
	$input['custom_option1'] = esc_url( $input['custom_option1'] );
	$input['custom_option2'] = esc_url( $input['custom_option2'] );
	return $input;
}

/**
 *
 * @since Custom Options Tree 1.0
 */
 
 
function cot_showheaderlogo() {
	$options = cot_getoptions();	
	$cot_headerimage = $options['header_image_path'];
	if( $cot_headerimage === "" ) { ?>
		<h2 class="site-title"><a href="#" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h2><?php
	}
	else {
		echo $cot_headerimage;
	}
}

function cot_showfaviconicon() {
	$options = cot_getoptions();	
	$cot_faviconimage = $options['header_favicon_icon_path'];
	if( $cot_faviconimage === "" ) { ?>
		<h2 class="site-title"><a href="#" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h2><?php
	}
	else {
		echo $cot_faviconimage;
	}
}

function cot_showfooterlogo() {
	$options = cot_getoptions();	
	$cot_footerimage = $options['footer_image_path'];
	?>
	<img src="<?php echo $cot_footerimage; ?>" alt="<?php bloginfo( 'name' ); ?>">
	<?php
}

function cot_copyright() {
	$options = cot_getoptions();	
	$cot_crtext = $options['copy_right']; ?>
	<?php echo $cot_crtext; ?><?php 
}

function cot_designdevelopent() {
	$options = cot_getoptions();	
	$cot_ddtext = $options['design_developement']; ?>
	<?php echo $cot_ddtext; ?><?php 
}

function cot_facebook() {
	$options = cot_getoptions();
	$cot_fblink = $options['facebook']; ?>
	<?php echo $cot_fblink; ?><?php 
}

function cot_twitter() {
	$options = cot_getoptions();
	$cot_twlink = $options['twitter']; ?>
	<?php echo $cot_twlink; ?><?php 
}

function cot_linkedin() {
	$options = cot_getoptions();
	$cot_ldlink = $options['linkedin']; ?>
	<?php echo $cot_ldlink; ?><?php 
}

function cot_instagram() {
	$options = cot_getoptions();
	$cot_iglink = $options['instagram']; ?>
	<?php echo $cot_iglink; ?><?php 
}

function cot_googleplus() {
	$options = cot_getoptions();
	$cot_gplink = $options['google_plus']; ?>
	<?php echo $cot_gplink; ?><?php 
}

function cot_customoption1() {
	$options = cot_getoptions();
	$cot_co1link = $options['custom_option1']; ?>
	<?php echo $cot_co1link; ?><?php 
}

function cot_customoption2() {
	$options = cot_getoptions();
	$cot_co1link = $options['custom_option2']; ?>
	<?php echo $cot_co1link; ?><?php 
}

add_action( 'wp_head', 'cot_globalheader' );
function cot_globalheader() {
$options = cot_getoptions();
	$cot_headergcode = $options['header_global_code']; ?>
	<?php echo $cot_headergcode; ?><?php 
}

add_action( 'wp_head', 'cot_headercode' );
function cot_headercode() {
$options = cot_getoptions();
	$cot_headerocode = $options['header_code']; ?>
	<?php echo $cot_headerocode; ?><?php 
}

add_action( 'wp_footer', 'cot_footercode' );
function cot_footercode() {
$options = cot_getoptions();
	$cot_footerocode = $options['footer_code']; ?>
	<?php echo $cot_footerocode; ?><?php 
}


//Add js in plugin
add_action( 'admin_print_scripts', 'cot_customthemeoption_scripts' );
/**
 * Enqueues Custom Options Tree javascript files
 *
 * @since Custom Options Tree 1.0
 */
function cot_customthemeoption_scripts() {
	wp_enqueue_media();
	wp_enqueue_script( 'cot_customthemeoption_js', plugins_url('js/customoptiontree.js' , __FILE__ ), array( 'jquery', 'media-upload', 'thickbox' ) );
}

add_action( 'admin_print_styles', 'cot_customtheme_styles_admin' );
/**
 * enqueues 'Custom Options Tree' and 'WordPress thickbox' styles in admin and front end
 *
 * Conditionaly checks and inserts hover.css if required
 *
 * @since Custom Options Tree 1.0
 */
function cot_customtheme_styles_admin() {
	wp_enqueue_style( 'thickbox' );
	wp_enqueue_style( 'stylesheet', plugins_url('css/customoptiontree.css' , __FILE__ ), array(), '', false );
}

register_deactivation_hook('__FILE__', 'cot_customtheme_uninstall');
/**
 * removes Custom Options Tree options upon deactivation
 *
 * @since Custom Options Tree 1.0
 */
function cot_customtheme_uninstall() {
	delete_option('cot_options');
}
