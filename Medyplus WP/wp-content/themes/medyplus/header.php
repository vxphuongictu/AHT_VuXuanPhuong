<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Medyplus
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<title><?php bloginfo( 'title' ); ?></title>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<header>
	<div class="main-body">
		<div class="top-menu container-fluid">
			<div class="container">
				<div class="top-menu-group">
					<div class="more-info-mobile">
						<div class="container-find-out">
							<div class="icon top-menu-icon-border">
								<i class="fa fa-map-marker" aria-hidden="true"></i>
							</div>
							<div class="content">
								<span class="grey">Find Us</span>
								<a href="#" class="located white"><?php echo get_theme_mod( 'content_location' ); ?></a>
							</div>
						</div>

						<div class="container-email-us">
							<div class="icon top-menu-icon-border">
								<i class="fa fa-envelope-o" aria-hidden="true"></i>
							</div>
							<div class="content">
								<span class="grey">Email Us</span>
								<a href="#" class="located white"><?php echo get_theme_mod( 'content_email_header' ); ?></a>
							</div>
						</div>

						<div class="container-contact-us">
							<div class="icon top-menu-icon-border">
								<i class="fa fa-mobile" aria-hidden="true"></i>
							</div>
							<div class="content">
								<span class="grey">Call Us Now</span>
								<a href="#" class="located white"><?php echo get_theme_mod( 'content_phone_contact' ); ?></a>
							</div>
						</div>
					</div>
					<div class="change-language">
						<?php echo do_shortcode('[gtranslate]'); ?>
					</div>
				</div>
			</div>	
		</div>

		<div class="header container-fluid bg-white">
			<div class="container">
				<div class="container-body">
					<?php the_custom_logo(); ?>
					<div class="act-group">
						<?php  echo do_shortcode('[header_search]');  ?>
						<div class="icon-group">
							<a href="#">
								<div class="fa fa-search" aria-hidden="true"></div>
							</a>
							<a href="#">
								<div class="setting-icon"></div>
							</a>
							<a href="#">
								<div class="cart-icon">
									<div class="total-cart"></div>
								</div>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="nav-menu container-fluid bg-blue">
		<div class="container">
			<div class="container-body">
				<div class="menu-btn">
					<div class="menu-img"></div>
					<?php
					wp_nav_menu( array(
						'theme_location'  => 'header-menu',
						'container'       => 'div',
						'container_class' => 'categories'
					) );
					?>
					</a>
				</div>

				<div class="list-menu">
					<i class="fa fa-bars" aria-hidden="true"></i>
					<?php
					wp_nav_menu( array(
						'theme_location'  => 'header-menu-options',
						'container'       => false,
						'menu_class'      => 'menu-content',
						'menu_id'         => 'menu-content'
					) );
					?>
				</div>
			</div>
		</div>
	</div>
</header>