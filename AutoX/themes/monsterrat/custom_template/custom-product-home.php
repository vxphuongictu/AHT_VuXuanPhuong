<?php
/*
 * Template Name: Products
 */

if (($_SERVER['REQUEST_METHOD'] == "POST") && (empty($_POST['data']) === false)):
    $data       = json_decode($_POST['data'], true);
    $page_id    = empty($_POST['page_id']) === false ? htmlspecialchars($_POST['page_id']) : '';
    $page_name  = empty($_POST['page_name']) === false ? htmlspecialchars($_POST['page_name']) : '';
    $cat_name   = empty($_POST['cat_name']) === false ? htmlspecialchars($_POST['cat_name']) : '';
    $max_item   = empty($_POST['max_item']) === false ? htmlspecialchars($_POST['max_item']) : '';
    $count      = 0;

    if (($page_id != '') && ($page_name != '') && ($cat_name != '') && ($max_item != '')):
        foreach ($data as $product):
            $count++;
            if ($count <= $max_item):
                $product_id     = $product['product_id'];
                $parse_uri      = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
                require_once( $parse_uri[0] . 'wp-load.php' );
?>
<script> makeTimer('<?php echo $product_id; ?>','<?php echo $product['ending_date_content']; ?>'); </script>
<div class="product-item <?php if ($page_name == 'home-for-none-user'): echo 'set-width-50'; endif; ?> <?php if ($count%2==0): echo "margin-left"; endif; ?>">
    <?php if(is_user_logged_in()): ?>
    <div class="item-group-home-user">
        <!-- Logged in account -->
        <div class="item-group-home-user__link">
            <a class="user-logged-in" href="<?php echo $product['product_link']; ?>">
                <div class="image-item">
                    <?php
                    echo '<img class="product-image" src="'.$product['thumbnail_url'][0].'"></img>';
                    ?>
                </div>
            </a>

            <div class="item-information">
                <a href="<?php echo $product['product_link']; ?>">
                    <div class="product-name">
                        <h2>
                            <span class="content title-name"><?php echo $product['product_name']; ?></span>
                            <span class="content title-name short-description <?php if ($page_name == 'home-for-none-user'): echo 'hidden'; endif; ?>"><?php echo $product['short_description']; ?></span>
                        </h2>
                    </div>

                    <div class="more-info">
                        <div class="group-info">
                            <div class="more-info-line-1">
                                <?php if ($product['kilometer_content']): ?>
                                <div class="kilometer">
                                    <div class="icon">
                                        <?php $image    = $product['kilometer_icon']; ?>
                                        <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
                                    </div>
                                    <span class="content"><?php echo number_format($product['kilometer_content']); ?> km</span>
                                </div>
                                <?php endif; ?>

                                <?php if ($product['fuel_content']): ?>
                                <div class="fuel <?php if ($page_name == 'home-for-none-user'): echo 'hidden'; endif; ?>">
                                    <div class="icon">
                                        <?php $image    = $product['fuel_icon']; ?>
                                        <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
                                    </div>
                                    <span class="content"><?php echo $product['fuel_content']; ?></span>
                                </div>
                                <?php endif; ?>

                                <?php if ($product['unknow_content']): ?>
                                <div class="unknow <?php if ($page_name == 'home-for-none-user'): echo 'hidden'; endif; ?>">
                                    <div class="icon">
                                        <?php $image    = $product['unknow_icon']; ?>
                                        <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
                                    </div>
                                    <span class="content"><?php echo $product['unknow_content']; ?></span>
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="more-info-line-2">
                                <?php if ($product['clock_content']): ?>
                                <div class="time">
                                    <div class="icon">
                                        <?php $image    = $product['clock_icon']; ?>
                                        <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
                                    </div>
                                    <span class="content"><?php echo $product['clock_content']; ?></span>
                                </div>
                                <?php endif; ?>

                                <?php if ($product['cheaper_content']): ?>
                                <div class="cheaper">
                                    <div class="icon">
                                        <?php $image    = $product['cheaper_icon']; ?>
                                        <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
                                    </div>
                                    <span class="content"><?php echo $product['cheaper_content']; ?> VND Cheaper</span>
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="more-info-line-3">
                                <?php if ($product['auction_price_icon']): ?>
                                <div class="price">
                                    <div class="icon">
                                        <?php $image    = $product['auction_price_icon']; ?>
                                        <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
                                    </div>
                                    <span class="content"><?php echo $product['auction_price_title']; ?></span>
                                    <span class="sub-content"><?php echo $product['auction_price_content']; ?> VND</span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="item-quote">
                <?php if($product['quick_quote_title']): ?>
                <h2>
                    <span class="title-quote"><?php echo $product['quick_quote_title']; ?></span>
                </h2>

                <div id="result-quick-quote-<?php echo $product_id; ?>" class="result-quick-quote"></div>

                <?php endif; ?>
                <div class="form-quote-groups">
                    <div class="form-quote">
                        <input type="text" id="quick-quote-message-<?php echo $product_id; ?>" placeholder="Enter quote for this vehicle">
                        <button type="submit" onclick="user_send_mail(this)" id="btn-submit-quick-quote-<?php echo $product_id; ?>" class="btn-submit-quick-quote" value="<?php echo $product_id; ?>">Submit</button>
                    </div>
                </div>

                <?php if ($product['ending_date_content']): ?>
                <div class="remaining-time">
                    <div class="icon">
                        <?php $image    = $product['remaining_time_icon']; ?>
                        <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
                    </div>
                    <span class="content"><?php echo $product['time_remaining_content']; ?></span>
                    <span class="sub-content">
                        <span id="days-<?php echo $product_id; ?>"></span>
                        <span id="hours-<?php echo $product_id; ?>"></span>
                        <span id="minutes-<?php echo $product_id; ?>"></span>
                    </span>
                </div>
                <?php endif; ?>
            </div>

            <div class="btn-favorite">
                <i class="fa <?php if ($product['is_wish_list']) { echo 'fa-heart exists'; } else { echo 'fa-heart-o'; }?>" aria-hidden="true" id="product-<?php echo $product_id; ?>" onclick="wishList('<?php echo $page_id; ?>', '<?php echo $product_id; ?>');"></i>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="item-group-none-user">
        <!-- Guest Account -->
        <div class="item-group-none-user__link">
            <a class="guest-account" href="<?php echo $product['product_link']; ?>">
                <div class="image-item">
                    <?php
                    echo '<img class="product-image" src="'.$product['thumbnail_url'][0].'"></img>';
                    ?>
                </div>
            </a>

            <div class="item-information">
                <a href="<?php echo $product['product_link']; ?>">
                    <div class="product-name">
                        <h2>
                            <span class="content title-name"><?php echo $product['product_name']; ?></span>
                            <span class="content title-name short-description <?php if ($page_name == 'home-for-none-user'): echo 'hidden'; endif; ?>"><?php echo $product['short_description']; ?></span>
                        </h2>
                    </div>

                    <div class="more-info">
                        <div class="group-info">
                            <div class="more-info-line-1">
                                <?php if ($product['clock_content']): ?>
                                <div class="time">
                                    <div class="icon">
                                        <?php $image    = $product['clock_icon']; ?>
                                        <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
                                    </div>
                                    <span class="content"><?php echo $product['clock_content']; ?></span>
                                </div>
                                <?php endif; ?>

                                <?php if ($product['kilometer_content']): ?>
                                <div class="kilometer">
                                    <div class="icon">
                                        <?php $image    = $product['kilometer_icon']; ?>
                                        <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
                                    </div>
                                    <span class="content"><?php echo number_format($product['kilometer_content']); ?> km</span>
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="more-info-line-2">
                                <?php if ($product['cheaper_content']): ?>
                                <div class="cheaper">
                                    <div class="icon">
                                        <?php $image    = $product['cheaper_icon']; ?>
                                        <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
                                    </div>
                                    <span class="content"><?php echo $product['cheaper_content']; ?> VND Cheaper</span>
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="more-info-line-3">
                                <?php if ($product['auction_price_icon']): ?>
                                <div class="price">
                                    <div class="icon">
                                        <?php $image    = $product['auction_price_icon']; ?>
                                        <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
                                    </div>
                                    <span class="content"><?php echo $product['auction_price_title']; ?></span>
                                    <span class="sub-content"><?php echo $product['auction_price_content']; ?> VND</span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>
<!-- check max item per page -->

<?php endforeach; ?>
<!-- check item on array -->

<?php else: ?>
    <div class="no-data-display">
        <span>No data to result</span>
    </div>
<?php endif ?> 
<!-- if empty data > 0 -->

<section class="<?php echo is_user_logged_in() ? 'seemore-logged-in' : 'seemore-guest-account'; ?>">
    <button id="btn-see-more">
        <?php 
        /* Condition */
        $label_button               = empty(get_field('label_button', $page_id)) === false ? get_field('label_button', $page_id) : '';
        /* End Condition */
        do_shortcode("[text_style content='".$label_button."' font_weight='600' color='#000']"); 
        ?>
    </button>
</section>

<?php  endif; ?>
<!-- REQUEST_METHOD -->