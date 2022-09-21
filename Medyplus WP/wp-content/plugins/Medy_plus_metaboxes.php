<?php
/*
Plugin Name: Medyplus Metabox
Author: Vu Xuan Phuong
 */


function add_custom_metabox()
{
	// Define the custom attachment for posts
    add_meta_box(
        'wp_custom_attachment',
        'Custom Attachment',
        'wp_custom_attachment',
        ["post", "page"]
    );
}
add_action('add_meta_boxes', 'add_custom_metabox');

function wp_custom_attachment()
{
	wp_nonce_field(plugin_basename(__FILE__), 'wp_custom_attachment_nonce');
    $html = '<p class="description">';
    $html .= 'Upload your PDF here.';
    $html .= '</p>';
    $html .= '<input type="file" id="wp_custom_attachment" name="wp_custom_attachment" value="" size="25" />';
    echo $html;
}

function save_custom_meta_data($id) {

    /* --- security verification --- */
    if(!wp_verify_nonce($_POST['wp_custom_attachment_nonce'], plugin_basename(__FILE__))) {
      return $id;
    } // end if

    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return $id;
    } // end if

    if('page' == $_POST['post_type']) {
      if(!current_user_can('edit_page', $id)) {
        return $id;
      } // end if
    } else {
        if(!current_user_can('edit_page', $id)) {
   return $id;
        } // end if
    } // end if
    /* - end security verification - */

    // Make sure the file array isn't empty
    if(!empty($_FILES['wp_custom_attachment']['name'])) {

        // Setup the array of supported file types. In this case, it's just PDF.
        $supported_types = array('image/png');

        // Get the file type of the upload
        $arr_file_type = wp_check_filetype(basename($_FILES['wp_custom_attachment']['name']));
        $uploaded_type = $arr_file_type['type'];

        // Check if the type is supported. If not, throw an error.
        if(in_array($uploaded_type, $supported_types)) {

		   // Use the WordPress API to upload the file
		   $upload = wp_upload_bits($_FILES['wp_custom_attachment']['name'], null, file_get_contents($_FILES['wp_custom_attachment']['tmp_name']));

		   if(isset($upload['error']) && $upload['error'] != 0) {
		       wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
		   } else {
		       add_post_meta($id, 'wp_custom_attachment', $upload);
		       update_post_meta($id, 'wp_custom_attachment', $upload);
		   } // end if/else

	    } else {
	   		wp_die("The file type that you've uploaded is not a PDF.");
	    } // end if/else
    } // end if

} // end save_custom_meta_data
add_action('save_post', 'save_custom_meta_data');
?>