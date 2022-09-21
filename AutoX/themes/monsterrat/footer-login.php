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
<?php wp_footer(); ?>
</body>
</html>
