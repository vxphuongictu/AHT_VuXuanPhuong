<?php
/*
 * Template Name: Home For user login in
 */
get_header();
if (!is_user_logged_in()):
    wp_redirect(home_url() . '/home-none-user/');
endif;
?>
<article id="main-body" class="container-fluid">
    <div class="bg-body"></div>
    <div class="logo-header <?php if( current_user_can('editor') || current_user_can('administrator') ): echo "admin-bar"; endif; ?>">
        <section class="logo">
            <?php $image    = get_field('banner-img'); ?>
            <img src="<?php echo empty($image) === false ? $image['url'] : ''; ?>" alt="header-logo-menu-right">
        </section>

        <section class="text-in-slide">
            <div class="text-header">
                <?php 
                /* Condition */
                $first_title_banner             = empty(get_field('first_title_banner')) === false ? get_field('first_title_banner') : '';
                /* End Condition */

                do_shortcode("[text_style content='".$first_title_banner."' font_weight='400' color='#fff']");
                ?>

                <?php 
                /* Condition */
                $second_title_banner                = empty(get_field('second_title_banner')) === false ? get_field('second_title_banner') : '';
                /* End Condition */

                do_shortcode("[text_style content='".$second_title_banner."' font_weight='400' color='#ffbe00']"); 
                ?>
            </div>

            <div class="text-conent">
                <?php
                /* Condition */
                $content_banner                = empty(get_field('content_banner')) === false ? get_field('content_banner') : '';
                /* End Condition */

                do_shortcode("[text_style content='".$content_banner."' font_weight='500' color='#fff']"); 
                ?>
            </div>
        </section>

        <section class="search-form">
            <div class="search-content">
                <input type="text" name="s" placeholder="<?php echo empty(get_field('placeholder_search_products')) == false ? get_field('placeholder_search_products') : ''; ?>" onkeyup="search();">
                <button type="submit" name="search" onclick="search();"><?php echo empty(get_field('button_search_products')) == false ? get_field('button_search_products') : ''; ?></button>
            </div>
        </section>
    </div>

    <div class="body-content container">
        <section class="tab-options">
            <button class="box-item" value="biddings">
                <div class="icon">
                    <i class="fa fa-gavel" aria-hidden="true"></i>
                </div>
                <div class="content">
                    <?php echo empty(get_field('biddings_title')) === false ? get_field('biddings_title') : ''; ?>
                    <span class="quantity" id="quantity-biddings"></span>
                </div>
            </button>

            <button class="box-item" value="favourites">
                <div class="icon">
                    <i class="fa fa-heart" aria-hidden="true"></i>
                </div>
                <div class="content">
                    <?php echo empty(get_field('favourites_title')) === false ? get_field('favourites_title') : ''; ?>
                    <span class="quantity" id="quantity-favourites"></span>
                </div>
            </button>

            <button class="box-item" value="results">
                <div class="icon">
                    <i class="fa fa-car" aria-hidden="true"></i>
                </div>
                <div class="content">
                    <?php echo empty(get_field('results_title')) === false ? get_field('results_title') : ''; ?>
                    <span class="quantity" id="quantity-results"></span>
                </div>
            </button>
        </section>
        <section class="categories"></section>
        <section id="list-item"></section>
    </div>
</article>
<?php echo do_action('home_scripts'); ?>
<script type="text/javascript">
    $(function () {
        get_data('<?php echo the_ID(); ?>', 'home');
    });
</script>
<?php get_footer(); ?>