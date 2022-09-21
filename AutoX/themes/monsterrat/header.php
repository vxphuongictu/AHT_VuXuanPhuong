<?php
/**
 * The header for our theme
 * Template Name: header page
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package monsterrat
 */
ob_start();
global $post;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="loader">
	<div class="line">
		<span class="run"></span>
	</div>
</div>
<header id="header" class="container-fluid">
	<div class="main-header">
		<section class="header-logo">
			<?php the_custom_logo(); ?>
		</section>

		<section class="header-menu">
			<?php
			wp_nav_menu([
				'theme_location'	=> 'top-header-menu-middle',
				'container' 		=> 'div',
				'container_class' 	=> 'first-menu'
			]);
			?>
		</section>

		<section class="header-menu-right">
			<?php if (is_user_logged_in()): ?>
			<?php $current_user = wp_get_current_user(); ?>
			<?php echo get_avatar( get_option( 'admin_email' ), 40 ); ?>
			<div class="user-name">
				<span class="text-link-logged-user">
					<?php echo $current_user->display_name; ?>
				</span>
			</div>
			<i class="fa fa-angle-down" aria-hidden="true"></i>

			<div class="drop-down-menu">
				<div class="logout">
					<a href="<?php echo get_page_uri() . '/logout' ?>">
						<span>LOGOUT</span>
					</a>
				</div>
			</div>
			<?php else: ?>

			<?php
			wp_nav_menu([
				'theme_location'	=> 'top-header-menu-right-none-user',
				'container' 		=> 'div',
				'container_class' 	=> 'second-menu',
				'link_before'		=> '<span class="text-link-none-user">',
				'link_after'		=> '</span>',
				'before' 			=> '<div class="href-link-none-user">',
				'after'	 			=> '</div>'
			]);
			?>

			<?php endif; ?>

			<div class="btn-options">
				<i class="fa fa-bars" aria-hidden="true"></i>
				<i class="fa fa-times" aria-hidden="true"></i>
			</div>
		</section>
	</div>
</header>

<aside class="panel-menu <?php if( current_user_can('editor') || current_user_can('administrator') ): echo "admin-bar"; endif; ?>">
	<section class="nav-menu">
		<div class="blank-block"></div>

		<div class="avatar">
			<?php if(is_user_logged_in()): ?>
			<!-- avatar user -->
			<?php
			$current_user 	= wp_get_current_user();
			echo get_avatar( $current_user->ID, 100 );
			?>
			<span class="avatar-content">
				<?php echo $current_user->user_login; ?>
			</span>
			<?php else: ?>
				<?php echo get_avatar( get_option( 'admin_email' ), 100 ); ?>
				<span class="avatar-content">
					<p>Welcome!</p>
				</span>
			<?php endif; ?>
		</div>

		<?php if(!is_user_logged_in()): ?>
		<div class="logout">
			<a href="<?php echo get_template_directory_uri() . '/login-registration'?>">
				<i class="fa fa-sign-out" aria-hidden="true"></i>
				<span>LOGIN</span>
			</a>
		</div>

		<?php endif; ?>
		<?php
		wp_nav_menu([
			'theme_location'	=> 'top-header-menu-middle',
			'container' 		=> 'div',
			'container_class' 	=> 'first-menu'
		]);
		?>

		<?php if(is_user_logged_in()): ?>
		<div class="logout">
			<a href="<?php echo get_template_directory_uri() . '/logout'?>">
				<i class="fa fa-sign-out" aria-hidden="true"></i>
				<span>LOGOUT</span>
			</a>
		</div>
		<?php endif; ?>
		<div class="social">
            <div class="box-border">
                <a href="#">
                    <i class="fa fa-facebook" aria-hidden="true"></i>
                </a>
            </div>
            <div class="box-border">
                <a href="#">
                    <i class="fa fa-twitter" aria-hidden="true"></i>
                </a>
            </div>
            <div class="box-border">
                <a href="#">
                    <i class="fa fa-linkedin" aria-hidden="true"></i>
                </a>
            </div>
        </div>
	</section>
</aside>