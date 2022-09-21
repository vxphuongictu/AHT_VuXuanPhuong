<?php
/*
 * Template Name: Home for none user
*/
get_header();
if (is_user_logged_in()):
    wp_redirect(home_url(). '/home/');
endif;
?>

<article id="main-body" class="container-fluid">
    <div class="bg-body"></div>
    <div class="logo-header-none-user">
        <section class="logo">
            <?php $image    = get_field('banner_image_none_user'); ?>
            <img src="<?php echo empty($image) === false ? $image['url'] : '' ?>" alt="banner-image-none-user">
        </section>

        <section class="text-in-slide-for-none-user">
            <div class="text-header">
                <?php 
                /* condition */
                $first_title        = empty(get_field('first_title_banner_for_none_user')) === false ? get_field('first_title_banner_for_none_user') : '';
                /* end condition */

                do_shortcode("[text_style content='".$first_title."' font_weight='400' color='#fff']"); 
                ?>

                <?php 
                /* condition */
                $second_title       = empty(get_field('second_title_banner_for_none_user')) === false ? get_field('second_title_banner_for_none_user') : '';
                /* end condition */

                do_shortcode("[text_style content='".$second_title."' font_weight='600' color='#ffbe00']");
                ?>
            </div>

            <div class="text-conent">
                <?php 
                /* condition */
                $content_banner     = empty(get_field('content_banner_for_none_user')) === false ? get_field('content_banner_for_none_user') : '';
                /* end condition */

                do_shortcode("[text_style content='".$content_banner."' font_weight='500' color='#fff']");
                ?>
            </div>

            <div class="explore-button">
                <a href="<?php echo empty(get_field('explore_button_label_href')) === false ? get_field('explore_button_label_href') : '';?>">
                    <button type="submit" class="btn-explore">
                        <?php 
                        /* condition */
                        $explore_label          = empty(get_field('explore_button_label')) === false ? get_field('explore_button_label') : '';
                        /* end condition */

                        do_shortcode("[text_style content='".$explore_label."' font_weight='600' color='#000']"); 
                        ?>
                    </button>
                </a>
            </div>
        </section>
    </div>

    <div class="body-content container">
        <section class="title-header">
            <div class="title-group">
            <?php 
            /* condition */
            $first_title_header                 = empty(get_field('the_first_title_header_products')) === false ? get_field('the_first_title_header_products') : '';
            /* end condition */

            do_shortcode("[text_style content='".$first_title_header."' font_weight='600' color='#333']");
            ?>
            &nbsp;
            <?php
            /* condition */
            $second_title_header                = empty(get_field('the_second_title_header_products')) === false ? get_field('the_second_title_header_products') : '';
            /* end condition */

            do_shortcode("[text_style content='".$second_title_header."' font_weight='600' color='#ffbe00']");
            ?>
            </div>
        </section>
        <section id="list-item" class="flex-wrap"></section>
    </div>

    <div class="footer-support container-fluid">
        <div class="container">
            <section class="vehicle-form">
                <div class="title">
                <?php echo empty(get_field('vehicle_title')) === false ? get_field('vehicle_title') : ''; ?>
                </div>

                <div class="content"><?php echo empty(get_field('vehicle_content')) === false ? get_field('vehicle_content') : ''; ?></div>
                <div class="form-group">
                    <div class="input-group-1">
                        <div class="box">
                            <input class="full-name" id="full-name" placeholder="<?php echo empty(get_field('vehicle_full_name')) === false ? get_field('vehicle_full_name') : ''; ?>"></input>
                        </div>
                        <div class="box">
                            <input class="phone-number" id="phone-number" placeholder="<?php echo empty(get_field('vehicle_phone_number')) === false ? get_field('vehicle_phone_number') : ''; ?>"></input>
                        </div>
                    </div>

                    <div class="input-group-2">
                        <div class="box">
                            <input class="email" id="email" placeholder="<?php echo empty(get_field('vehicle_email')) === false ? get_field('vehicle_email') : ''; ?>"></input>
                        </div>
                        <div class="box">
                            <textarea class="details" id="details" rows="7" placeholder="<?php echo empty(get_field('vehicle_details')) === false ? get_field('vehicle_details') : ''; ?>"></textarea>
                        </div>
                    </div>

                    <div class="btn">
                        <button class="btn-submit" id="guest_send_mail" onclick="guest_send_mail();">
                            <?php echo empty(get_field('vehicle_button_send_mail')) === false ? get_field('vehicle_button_send_mail') : ''; ?>
                        </button>
                    </div>
                </div>
            </section>

            <section class="background-image">
                <?php $image = get_field('support_background_footer'); ?>
                <img src="<?php echo empty($image) === false ? $image['url'] : ''; ?>" class="background">
            </section>
        </div>
    </div>
</article>
<?php echo do_action('home_scripts'); ?>
<script type="text/javascript">
    $(function () {
        get_data('<?php echo the_ID(); ?>', 'home-for-none-user');
    });
</script>
<?php
get_footer();
?>