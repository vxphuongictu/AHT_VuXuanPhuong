<?php
function wp_dummy_content_generatorPosts(){
    include( WP_PLUGIN_DIR.'/'.plugin_dir_path(wp_dummy_content_generator_PLUGIN_BASE_URL) . 'admin/template/wp_dummy_content_generator-posts.php');
}
function wp_dummy_content_generatorGetPostTypes(){
    $args=array(
        'public'                => true,
        'exclude_from_search'   => false,
        '_builtin'              => false
    ); 
    $output = 'names'; // names or objects, note names is the default
    $operator = 'and'; // 'and' or 'or'
    $regPostTypes = get_post_types($args,$output,$operator);
    $posttypes_array = array();
    $posttypes_array['post'] = 'Posts';
    foreach ($regPostTypes  as $post_type ) {
        $wp_dummy_content_generator_pt = get_post_type_object( $post_type );
        $wp_dummy_content_generator_pt_name = $wp_dummy_content_generator_pt->labels->name;
        $posttypes_array[$post_type] = $wp_dummy_content_generator_pt_name;
    }
    unset($posttypes_array['product']); //exclude 'product' post type as we are providing separate section for products 
    return $posttypes_array;
}

function wp_dummy_content_generatorGeneratePosts(
    $posttype='post',
    $wp_dummy_content_generatorIsThumbnail='off',
    $postDateFrom='',
    $postDateTo=''
){


    if($postDateFrom == ''){
        $postDateFrom = date("Y-m-d");
    }

    if($postDateTo == ''){
        $postDateTo = date("Y-m-d");
    }
    include( WP_PLUGIN_DIR.'/'.plugin_dir_path(wp_dummy_content_generator_PLUGIN_BASE_URL) . 'Faker-main/vendor/autoload.php');
    // use the factory to create a Faker\Generator instance
    // $wp_dummy_content_generatorFaker = Faker\Factory::create();
    $wp_dummy_content_generatorFaker = Faker\Factory::create();
    $wp_dummy_content_generatorPostTitle = $wp_dummy_content_generatorFaker->text($maxNbChars = 40);
    $wp_dummy_content_generatorPostDescription = $wp_dummy_content_generatorFaker->text($maxNbChars = 700);
    $rand_num = rand(1,15);
    $wp_dummy_content_generatorPostThumb = WP_PLUGIN_DIR.'/'.plugin_dir_path(wp_dummy_content_generator_PLUGIN_BASE_URL) . 'images/posts/'.$rand_num.".jpg";
    // create post

    $postDate = wp_dummy_content_generatorRandomDate($postDateFrom,$postDateTo);

    $wp_dummy_content_generatorPostArray = array(
      'post_title'    => wp_strip_all_tags( $wp_dummy_content_generatorPostTitle ),
      'post_content'  => $wp_dummy_content_generatorPostDescription,
      'post_status'   => 'publish',
      'post_author'   => 1,
      'post_date'   => $postDate,
      'post_type' => $posttype
    );
    // Insert the post into the database
    $wp_dummy_content_generatorPostID = wp_insert_post( $wp_dummy_content_generatorPostArray );
    if($wp_dummy_content_generatorPostID){
        update_post_meta($wp_dummy_content_generatorPostID,'wp_dummy_content_generator_post','true');
        if($wp_dummy_content_generatorIsThumbnail=='on')
        wp_dummy_content_generator_Generate_Featured_Image( $wp_dummy_content_generatorPostThumb,$wp_dummy_content_generatorPostID);
        return 'success';
    }else{
        return 'error';
    }

}
function wp_dummy_content_generator_Generate_Featured_Image( $image_url, $post_id ){
    $upload_dir = wp_upload_dir();
    $image_data = file_get_contents($image_url);
    $filename = "wp_dummy_content_generator_".$post_id.".jpg";
    if(wp_mkdir_p($upload_dir['path'])){
        $file = $upload_dir['path'] . '/' . $filename;
    }
    else{
        $file = $upload_dir['basedir'] . '/' . $filename;
    }
    file_put_contents($file, $image_data);
    $wp_filetype = wp_check_filetype($filename, null ); 
    $attachment = array(
        'post_mime_type' => 'image/jpg',
        'post_title' => sanitize_file_name($filename),
        'post_content' => '',
        'post_status' => 'inherit'
    );
    $attach_id = wp_insert_attachment( $attachment, $file, $post_id );
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
    $res1= wp_update_attachment_metadata( $attach_id, $attach_data );
    update_post_meta($attach_id, 'wp_dummy_content_generator_attachment','true');
    $res2= set_post_thumbnail( $post_id, $attach_id );
}


function wp_dummy_content_generatorAjaxGenPosts () {
    $wp_dummy_content_generatorIsThumbnail = 'off';
    $post_type = sanitize_text_field($_POST['wp_dummy_content_generator-posttype']);
    $remaining_posts = sanitize_text_field($_POST['remaining_posts']);
    $post_count = sanitize_text_field($_POST['wp_dummy_content_generator-post_count']);

    if($remaining_posts>=2){
        $loopLimit = 2;
    }else{
        $loopLimit = $remaining_posts;
    }


    $wp_dummy_content_generatorIsThumbnail = sanitize_text_field($_POST['wp_dummy_content_generator-thumbnail']);

    $postFromDate = sanitize_text_field($_POST['wp_dummy_content_generator-post_from']);
    $postToDate = sanitize_text_field($_POST['wp_dummy_content_generator-post_to']);
    $counter = 0;
    for ($i=0; $i < $loopLimit ; $i++) { 
        $generationStatus = wp_dummy_content_generatorGeneratePosts($post_type,$wp_dummy_content_generatorIsThumbnail,$postFromDate,$postToDate);
        if($generationStatus == 'success'){
            $counter++;
        }
    }
    if($remaining_posts>=2){
        $remaining_posts = $remaining_posts - 2;
    }else{
        $remaining_posts = 0;
    }
    echo json_encode(array('status' => 'success', 'message' => 'Posts generated successfully.','remaining_posts' => $remaining_posts) );
    die();
}
add_action("wp_ajax_wp_dummy_content_generatorAjaxGenPosts", "wp_dummy_content_generatorAjaxGenPosts");
add_action("wp_ajax_nopriv_wp_dummy_content_generatorAjaxGenPosts", "wp_dummy_content_generatorAjaxGenPosts");

function wp_dummy_content_generatorGetFakePostsList(){
    $postsArr = wp_dummy_content_generatorGetPostTypes();
    $allPostTypes = array();
    foreach ($postsArr as $key => $value) {
        array_push($allPostTypes, $key);
    }
    $args = array(
        'posts_per_page' => -1,
        'post_type' => $allPostTypes,
        'order' => 'DESC',
        'meta_query' => array(
            array(
                'key' => 'wp_dummy_content_generator_post',
                'value' => 'true',
                'compare' => '='
            ),
        )
    );
    $wp_dummy_content_generatorQueryData = new WP_Query( $args );
    return $wp_dummy_content_generatorQueryData;
}

function wp_dummy_content_generatorDeleteFakePosts(){
    $wp_dummy_content_generatorQueryData = wp_dummy_content_generatorGetFakePostsList();
    if ($wp_dummy_content_generatorQueryData->have_posts()) {
        while ( $wp_dummy_content_generatorQueryData->have_posts() ) :
            $wp_dummy_content_generatorQueryData->the_post();
            wp_delete_post(get_the_ID());
        endwhile;
    }
    wp_reset_postdata();
}

function wp_dummy_content_generatorDeletePosts () {
    wp_dummy_content_generatorDeleteFakePosts();
    echo json_encode(array('status' => 'success', 'message' => 'Data deleted successfully.') );
    die();
}
add_action("wp_ajax_wp_dummy_content_generatorDeletePosts", "wp_dummy_content_generatorDeletePosts");
add_action("wp_ajax_nopriv_wp_dummy_content_generatorDeletePosts", "wp_dummy_content_generatorDeletePosts");