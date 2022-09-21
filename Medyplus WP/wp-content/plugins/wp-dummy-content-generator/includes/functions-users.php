<?php
function wp_dummy_content_generatorUsers(){
    include( WP_PLUGIN_DIR.'/'.plugin_dir_path(wp_dummy_content_generator_PLUGIN_BASE_URL) . 'admin/template/wp_dummy_content_generator-users.php');
}

function wp_dummy_content_generatorGenerateUsers($userRole='subscriber',$wp_dummy_content_generatorIsBio='off'){
	include( WP_PLUGIN_DIR.'/'.plugin_dir_path(wp_dummy_content_generator_PLUGIN_BASE_URL) . 'Faker-main/vendor/autoload.php');
    // use the factory to create a Faker\Generator instance
    $wp_dummy_content_generatorFaker = Faker\Factory::create();
    $wp_dummy_content_generatorFirstName = $wp_dummy_content_generatorFaker->firstName;
    $wp_dummy_content_generatorLastName = $wp_dummy_content_generatorFaker->lastName;
    $wp_dummy_content_generatorUserName = $wp_dummy_content_generatorFaker->userName;
    $wp_dummy_content_generatorUserEmail = $wp_dummy_content_generatorFaker->freeEmail;
    $wp_dummy_content_generatorPassword = 'wp_dummy_content_generator';
    $user_id = wp_create_user( $wp_dummy_content_generatorUserName, $wp_dummy_content_generatorPassword, $wp_dummy_content_generatorUserEmail );
    update_user_meta($user_id,'wp_dummy_content_generator_user','true');
    update_user_meta($user_id,'first_name',$wp_dummy_content_generatorFirstName);
    update_user_meta($user_id,'last_name',$wp_dummy_content_generatorLastName);
    if($wp_dummy_content_generatorIsBio == 'on'){
	    $wp_dummy_content_generatorUserBio = $wp_dummy_content_generatorFaker->text;
	    update_user_meta($user_id,'description',$wp_dummy_content_generatorUserBio);
    }
    if($userRole != 'subscriber'){
    	$wp_dummy_content_generatorUserObj = new WP_User( $user_id );
		$wp_dummy_content_generatorUserObj->remove_role( 'subscriber' );
		$wp_dummy_content_generatorUserObj->add_role( $userRole );
    }
    if( !is_wp_error( $user_id ) ){
    	return "success";
    }else{
    	return "error";
    }
}

function wp_dummy_content_generatorAjaxGenUsers () {
    $wp_dummy_content_generatorIsBio = 'off';
    $userRole = sanitize_text_field($_POST['wp_dummy_content_generator-userRole']);
    $remaining_users = sanitize_text_field($_POST['remaining_users']);
    $user_count = sanitize_text_field($_POST['wp_dummy_content_generator-user_count']);
    $wp_dummy_content_generatorIsBio = sanitize_text_field($_POST['wp_dummy_content_generator-bio']);
    if($remaining_users>=2){
        $loopLimit = 2;
    }else{
        $loopLimit = $remaining_users;
    }
    $counter = 0;
    for ($i=0; $i < $loopLimit ; $i++) { 
        $generationStatus = wp_dummy_content_generatorGenerateUsers($userRole,$wp_dummy_content_generatorIsBio);
        if($generationStatus == 'success'){
            $counter++;
        }
    }
    if($remaining_users>=2){
        $remaining_users = $remaining_users - 2;
    }else{
        $remaining_users = 0;
    }
    echo json_encode(array('status' => 'success', 'message' => 'Users generated successfully.','remaining_users' => $remaining_users) );
    die();
}
add_action("wp_ajax_wp_dummy_content_generatorAjaxGenUsers", "wp_dummy_content_generatorAjaxGenUsers");
add_action("wp_ajax_nopriv_wp_dummy_content_generatorAjaxGenUsers", "wp_dummy_content_generatorAjaxGenUsers");

function wp_dummy_content_generatorGetFakeUsers(){
    $users = get_users(array(
        'meta_key'     => 'wp_dummy_content_generator_user',
        'meta_value'   => 'true',
        'meta_compare' => '=',
    ));
    return $users;
}

function wp_dummy_content_generatorDeleteFakeUsers(){
    $users = wp_dummy_content_generatorGetFakeUsers();
    foreach ($users as $key => $userDatavalue){
        wp_delete_user($userDatavalue->ID);
    }
}

function wp_dummy_content_generatorDeleteUsers () {
    wp_dummy_content_generatorDeleteFakeUsers();
    echo json_encode(array('status' => 'success', 'message' => 'Data deleted successfully.') );
    die();
}
add_action("wp_ajax_wp_dummy_content_generatorDeleteUsers", "wp_dummy_content_generatorDeleteUsers");
add_action("wp_ajax_nopriv_wp_dummy_content_generatorDeleteUsers", "wp_dummy_content_generatorDeleteUsers");