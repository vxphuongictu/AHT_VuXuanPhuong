<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://tutsocean.com/about-me
 * @since      1.0.0
 *
 * @package    wp_dummy_content_generator
 * @subpackage wp_dummy_content_generator/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    wp_dummy_content_generator
 * @subpackage wp_dummy_content_generator/admin
 * @author     Deepak anand <anand.deepak9988@gmail.com>
 */
class wp_dummy_content_generator_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in wp_dummy_content_generator_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The wp_dummy_content_generator_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp_dummy_content_generator-admin-min.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'dtable_'.$this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/jquery.dataTables.css', $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in wp_dummy_content_generator_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The wp_dummy_content_generator_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( 'dtable_'.$this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/jquery.dataTables.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp_dummy_content_generator-admin-min.js', array( 'jquery' ), $this->version, false );
		wp_localize_script($this->plugin_name, 'wp_dummy_content_generator_backend_ajax_object',
        array( 
            'wp_dummy_content_generator_ajax_url' => admin_url( 'admin-ajax.php' ),
        )
    );

	}

}
