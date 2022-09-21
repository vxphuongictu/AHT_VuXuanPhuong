<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package monsterrat
 */
?>
<!-- some hidden data -->
<input type="hidden" id="wp_admin" value="<?php echo admin_url('admin-ajax.php'); ?>">
<input type="hidden" id="page_uri" value="<?php echo get_template_directory_uri(); ?>">
<!-- some hidden data end -->

<footer id="footer" class="container-fluid">
	<article class="footer-content container">
        <section class="logo"><?php echo the_custom_logo(); ?></section>

        <section class="footer-body">
            <div class="contact-group">
                <h3><?php echo empty(get_theme_mod( 'footer-first-menu-location' )) === false ? get_theme_mod( 'footer-first-menu-location' ) : ''; ?></h3>
                <div class="content">
                    <div class="item-groups">
                        <div class="title"><?php echo empty(get_theme_mod( 'title-footer-address' )) === false ?  get_theme_mod( 'title-footer-address' ) : ''; ?></div>
                        <div class="content">
                            <a href="#"><?php echo empty(get_theme_mod( 'content-footer-address' )) === false ? get_theme_mod( 'content-footer-address' ) : ''; ?></a>
                        </div>
                    </div>

                    <div class="item-groups">
                        <div class="title"><?php echo empty(get_theme_mod( 'title-footer-hotline' )) === false ? get_theme_mod( 'title-footer-hotline' ) : ''; ?></div>

                        <?php $hotline = empty(get_theme_mod( 'content-footer-hotline' )) === false ? get_theme_mod( 'content-footer-hotline' ) : ''; ?>
                        <div class="content">
                            <a href="tel: <?php echo $hotline; ?>"><?php echo $hotline; ?></a>
                        </div>
                    </div>

                    <div class="item-groups">
                        <div class="title"><?php echo empty(get_theme_mod( 'title-footer-email' )) === false ? get_theme_mod( 'title-footer-email' ) : ''; ?></div>

                        <?php $mail = get_theme_mod( 'content-footer-email' ); ?>
                        <div class="content">
                            <a href="mailto: <?php echo $mail; ?>"><?php echo $mail; ?></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="about-us-group">
                <h3><?php echo empty(get_theme_mod( 'footer-second-menu-location' )) === false ? get_theme_mod( 'footer-second-menu-location' ) : ''; ?></h3>
                <div class="content">
                    <?php
                    wp_nav_menu( array(
                        'theme_location'  => 'second-footer-menu'
                    ) );
                    ?>
                </div>
            </div>
            <div class="useful-links-group">
                <h3><?php echo empty(get_theme_mod( 'footer-third-menu-location' )) === false ? get_theme_mod( 'footer-third-menu-location' ) : ''; ?></h3>
                <div class="content">
                    <?php
                    wp_nav_menu( array(
                        'theme_location'  => 'third-footer-menu'
                    ) );
                    ?>
                </div>
            </div>
        </section>

        <div class="line"></div>

        <section class="footer-bottom">
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
            <div class="copyright">
                <?php echo '©'.date('Y').' '.(empty(get_theme_mod('copyright')) === false ? get_theme_mod('copyright') : ''); ?>
            </div>
            <a class="design" href="<?php echo empty(get_theme_mod('design-href')) === false ? get_theme_mod('design-href') : ''; ?>" target="_blank">
                <span>
                    <?php echo empty(get_theme_mod('design')) === false ? get_theme_mod('design') : ''; ?>
                </span>
                <img src="<?php echo empty(get_theme_mod('design-logo')) === false ? get_theme_mod('design-logo') : ''; ?>" alt="design-logo">
            </a>
        </section>
	</article>
</footer>
<?php wp_footer(); ?>
</body>
</html>
