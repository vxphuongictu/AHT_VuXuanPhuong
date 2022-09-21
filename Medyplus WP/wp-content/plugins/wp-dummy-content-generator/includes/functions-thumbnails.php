<?php
function wp_dummy_content_generatorThumbnails(){
    include( WP_PLUGIN_DIR.'/'.plugin_dir_path(wp_dummy_content_generator_PLUGIN_BASE_URL) . 'admin/template/wp_dummy_content_generator-thumbnails.php');
}
function wp_dummy_content_generatorGetFakeThumbnailsList(){
    $args = array(
        'posts_per_page' => -1,
        'post_type' => 'attachment',
        'order' => 'DESC',
        'post_status' => 'inherit',
        'meta_query' => array(
            array(
                'key' => 'wp_dummy_content_generator_attachment',
                'value' => 'true',
                'compare' => '='
            ),
        )
    );
    $wp_dummy_content_generatorQueryData = new WP_Query( $args );
    return $wp_dummy_content_generatorQueryData;
}
// wp_dummy_content_generatorDeleteFakeThumbnails
function wp_dummy_content_generatorDeleteFakeThumbnails(){
    $wp_dummy_content_generatorQueryData = wp_dummy_content_generatorGetFakeThumbnailsList();
    if ($wp_dummy_content_generatorQueryData->have_posts()) {
        while ( $wp_dummy_content_generatorQueryData->have_posts() ) :
            $wp_dummy_content_generatorQueryData->the_post();
            wp_delete_post(get_the_ID());
        endwhile;
    }
    wp_reset_postdata();
}

function wp_dummy_content_generatorDeleteThumbnails () {
    wp_dummy_content_generatorDeleteFakeThumbnails();
    echo json_encode(array('status' => 'success', 'message' => 'Data deleted successfully.') );
    die();
}
add_action("wp_ajax_wp_dummy_content_generatorDeleteThumbnails", "wp_dummy_content_generatorDeleteThumbnails");
add_action("wp_ajax_nopriv_wp_dummy_content_generatorDeleteThumbnails", "wp_dummy_content_generatorDeleteThumbnails");