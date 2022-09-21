<?php
$wp_dummy_content_generatorAllThumbnailsObj = wp_dummy_content_generatorGetFakeThumbnailsList();
$wp_dummy_content_generatorAllThumbnails = $wp_dummy_content_generatorAllThumbnailsObj->posts;
$wp_dummy_content_generatorActual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if(isset($_GET['action']) && $_GET['action'] == 'wp_dummy_content_generator_deletethumbnails'){
    wp_dummy_content_generatorDeleteFakeThumbnails();
    wp_redirect("admin.php?page=wp_dummy_content_generator-thumbnails&status=success");
}
?>
<!-- new code -->
<div class="wrap wp_dummy_content_generator_wrapper" id="wp-media-grid" data-search="">
    <h1 class="wp-heading-inline"><?php echo  wp_dummy_content_generator_PLUGIN_NAME ?> <span> - All thumbnails</span></h1>
    <?php if(!empty($wp_dummy_content_generatorAllThumbnails)){ ?>
        <a onclick="return confirm('Are you sure you want to delete all fake thumbnails?')" href="<?=$wp_dummy_content_generatorActual_link?>&action=wp_dummy_content_generator_deletethumbnails" class="page-title-action aria-button-if-js" role="button" aria-expanded="false">Delete dummy thumbnails</a>
    <?php } ?>
    <hr class="wp-header-end">
    <?php 
    if(isset($_GET['status'])){
        if($_GET['status'] == 'success'){
            echo '<div class="wp_dummy_content_generator-success-msg">All thumbnails deleted successfully.</div>';
        }else{
            echo '<div class="wp_dummy_content_generator-error-msg">Something went wrong.</div>';
        } ?>
        <hr class="wp-header-end">
        <?php
    } ?>
    <div class="media-frame wp-core-ui mode-grid mode-edit hide-menu">
        <div class="media-frame-content" data-columns="7">
            <div class="attachments-browser hide-sidebar sidebar-for-errors">
                <?php if(!empty($wp_dummy_content_generatorAllThumbnails)){ ?>
                    <ul tabindex="-1" class="attachments ui-sortable ui-sortable-disabled" id="__attachments-view-42">
                        <?php 
                            foreach ($wp_dummy_content_generatorAllThumbnails as $key => $wp_dummy_content_generatorAllThumbnail) { ?>
                                
                                <li tabindex="0" role="checkbox" aria-label="wp_dummy_content_generator_1866" aria-checked="false" data-id="1867" class="attachment save-ready">
                                    <div class="attachment-preview js--select-attachment type-image subtype-jpg landscape">
                                        <div class="thumbnail">
                                            <div class="centered">
                                                <img class="wp_dummy_content_generatorThumbnailsImage" src="<?=wp_get_attachment_url($wp_dummy_content_generatorAllThumbnail->ID)?>" draggable="false" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php } ?>
                    </ul>
                <?php }else{ ?>
                        <p class="no-media">No media files found.</p>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<!-- new code -->