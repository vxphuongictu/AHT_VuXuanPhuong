<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Frontend class
 *
 * @author YITH
 * @package YITH\ZoomMagnifier\Classes
 * @version 1.1.2
 */

if ( ! defined( 'YITH_WCMG' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WCMG_Frontend' ) ) {
	/**
	 * Admin class.
	 * The class manage all the Frontend behaviors.
	 *
	 * @since 1.0.0
	 */
	class YITH_WCMG_Frontend {


		/**
		 * Constructor
		 *
		 * @access public
		 * @since  1.0.0
		 */
		public function __construct() {

			// add the action only when the loop is initializate.
			add_action( 'template_redirect', array( $this, 'render' ) );
		}

		/**
		 * Render zoom.
		 */
		public function render() {
			if ( ! apply_filters( 'yith_wczm_featured_video_enabled', false ) ) {


				//Zoom template
				remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
				add_action( 'woocommerce_before_single_product_summary', array( $this, 'show_product_images' ), 20 );

				//Slider template
				remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );

				if ( get_option( 'ywzm_hide_thumbnails', 'no' ) !== 'yes'  )
					add_action( 'woocommerce_product_thumbnails', array( $this, 'show_product_thumbnails' ), 20 );


				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles_scripts' ) );

				// add attributes to product variations.
				add_filter( 'woocommerce_available_variation', array( $this, 'available_variation' ), 10, 3 );
			}
		}


		/**
		 * Change product-single.php template
		 *
		 * @access public
		 * @return void
		 * @since  1.0.0
		 */
		public function show_product_images() {
			wc_get_template( 'single-product/product-image-magnifier.php', array(), '', YITH_YWZM_DIR . 'templates/' );
		}


		/**
		 * Change product-thumbnails.php template
		 *
		 * @access public
		 * @return void
		 * @since  1.0.0
		 */
		public function show_product_thumbnails() {

			wc_get_template( 'single-product/product-thumbnails-magnifier.php', array(), '', YITH_YWZM_DIR . 'templates/' );
		}


		/**
		 * Enqueue styles and scripts
		 *
		 * @access public
		 * @return void
		 * @since  1.0.0
		 */
		public function enqueue_styles_scripts() {
			global $post;

			if ( is_product() || ( ! empty( $post->post_content ) && strpos($post->post_content, 'product_page') !== false ) ) {

				wp_register_script(
					'ywzm-magnifier',
					apply_filters( 'ywzm_magnifier_script_register_path', YITH_WCMG_URL . 'assets/js/' . yit_load_js_file( 'yith_magnifier.js' ) ),
					array( 'jquery' ),
					YITH_YWZM_SCRIPT_VERSION,
					true
				);

				/**
				 * Avoid the stopImmediatePropagation on gallery click if the Featured Audio and Video plugin is enabled
				 */
				$stop_immediate_propagation = function_exists( 'YITH_Featured_Audio_Video_Premium_Init') ? false : true;

				wp_localize_script(
					'ywzm-magnifier',
					'yith_wc_zoom_magnifier_storage_object',
					apply_filters(
						'yith_wc_zoom_magnifier_front_magnifier_localize',
						array(
							'ajax_url'          => admin_url( 'admin-ajax.php' ),
							'mouse_trap_width'  => apply_filters( 'yith_wczm_mouse_trap_with', '100%' ),
							'mouse_trap_height' => apply_filters( 'yith_wczm_mouse_trap_height', '100%' ),
							'stop_immediate_propagation' => $stop_immediate_propagation,
						)
					)
				);

				wp_register_script(
					'ywzm_frontend',
					YITH_WCMG_URL . 'assets/js/' . yit_load_js_file( 'ywzm_frontend.js' ),
					array(
						'jquery',
						'ywzm-magnifier',
					),
					YITH_YWZM_SCRIPT_VERSION,
					true
				);

				wp_register_style( 'ywzm-magnifier', YITH_WCMG_URL . 'assets/css/yith_magnifier.css', array(), YITH_YWZM_SCRIPT_VERSION );

				$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

				$slider_colors_default = Array(
					'background' => 'white',
					'border' => 'black',
					'arrow' => 'black',
				);

				$slider_colors_array = get_option( 'yith_wcmg_slider_style_colors', $slider_colors_default );
				$slider_colors_hover_array = get_option( 'yith_wcmg_slider_style_colors_hover', $slider_colors_default );

				wp_localize_script(
					'ywzm_frontend',
					'ywzm_data',
					array(
						'slider_colors_array' => $slider_colors_array,
						'slider_colors_hover_array' => $slider_colors_hover_array,
					)
				);

				// Enqueue PrettyPhoto style and script.
				$wc_assets_path = str_replace( array( 'http:', 'https:' ), '', WC()->plugin_url() ) . '/assets/';

				// Enqueue scripts.
				wp_enqueue_script( 'prettyPhoto', $wc_assets_path . 'js/prettyPhoto/jquery.prettyPhoto' . $suffix . '.js', array( 'jquery' ), WC()->version, true );
				wp_enqueue_script( 'ywzm-magnifier' );
				wp_enqueue_script( 'ywzm_frontend' );

				/**
				 * Add custom init PrettyPhoto
				 */

				wp_localize_script(
					'ywzm_frontend',
					'ywzm_prettyphoto_data',
					array(
					)
				);

				wp_enqueue_script( //phpcs:ignore
					'yith-ywzm-prettyPhoto-init',
					apply_filters( 'ywzm_src_prettyphoto_script', YITH_WCMG_URL . 'assets/js/init.prettyPhoto.js' ),
					array(
						'jquery',
						'prettyPhoto',
					),
					false,
					true
				);

				// Enqueue Style.
				$css = file_exists( get_stylesheet_directory() . '/woocommerce/yith_magnifier.css' ) ? get_stylesheet_directory_uri() . '/woocommerce/yith_magnifier.css' : YITH_WCMG_URL . 'assets/css/frontend.css';
				wp_enqueue_style( 'ywzm-prettyPhoto', $wc_assets_path . 'css/prettyPhoto.css', array(), YITH_YWZM_SCRIPT_VERSION );
				wp_enqueue_style( 'ywzm-magnifier' );
				wp_enqueue_style( 'ywzm_frontend', $css, array(), YITH_YWZM_SCRIPT_VERSION );

				wp_add_inline_style( 'ywzm_frontend', $this->get_custom_css() );
				wp_add_inline_style( 'ywzm-prettyPhoto', $this->get_custom_css_prettyphoto() );

			}
		}

		public function get_custom_css(){

			$custom_css         = '';

			$slider_colors_default = Array(
					'background' => 'white',
					'border' => 'black',
					'arrow' => 'black',
				);

			$sizes_default = Array(
				'dimensions' => array(
				'slider' => '25',
				'arrow' => '22',
				'border' => '2',
				));

			$colors_default = Array(
				'background' => 'white',
				'icon' => 'black',
			);

			$slider_colors_array = get_option( 'yith_wcmg_slider_style_colors', $slider_colors_default );
			$slider_colors_hover_array = get_option( 'yith_wcmg_slider_style_colors_hover', $slider_colors_default );
			$sizes = get_option( 'yith_wcmg_slider_sizes', $sizes_default );

			if ( is_array($slider_colors_array) ) {

				$custom_css .= "
                    #slider-prev, #slider-next {
                        background-color: {$slider_colors_array['background']};
                        border: {$sizes['dimensions']['border']}px solid {$slider_colors_array['border']};
                        width:{$sizes['dimensions']['slider']}px !important;
                        height:{$sizes['dimensions']['slider']}px !important;
                    }

                    .yith_slider_arrow span{
                        width:{$sizes['dimensions']['slider']}px !important;
                        height:{$sizes['dimensions']['slider']}px !important;
                    }
                    ";

				$custom_css .= "
                    #slider-prev:hover, #slider-next:hover {
                        background-color: {$slider_colors_hover_array['background']};
                        border: {$sizes['dimensions']['border']}px solid {$slider_colors_hover_array['border']};
                    }
                    ";

				$custom_css .= "
                   .thumbnails.slider path:hover {
                        fill:{$slider_colors_hover_array['arrow']};
                    }
                    ";

				$custom_css .= "
                    .thumbnails.slider path {
                        fill:{$slider_colors_array['arrow']};
                        width:{$sizes['dimensions']['slider']}px !important;
                        height:{$sizes['dimensions']['slider']}px !important;
                    }

                    .thumbnails.slider svg {
                       width: {$sizes['dimensions']['arrow']}px;
                       height: {$sizes['dimensions']['arrow']}px;
                    }

                    ";

			}

				//Lighbox expand icon
				$lighbox_colors_array = get_option( 'ywzm_lightbox_icon_colors', $colors_default );
				$lighbox_icon_size = get_option( 'ywzm_lightbox_icon_size', '25' );
				$lighbox_radius = get_option( 'yith_wcmg_lightbox_radius', '0' );
				$lighbox_icon_position = get_option( 'ywzm_lightbox_icon_position', 'top-right' );



				$arr = explode("-", $lighbox_icon_position, 2);
				$position = $arr[0];

				if ( $position == 'top' ){
					$top = '10px';
					$bottom = 'initial';
				}
				else{
					$top = 'initial';
					$bottom = '10px';
				}

				if ( $lighbox_icon_position === 'top-right' || $lighbox_icon_position === 'bottom-right' ){
					$left = 'initial';
					$right = '10px';
				}
				else{
					$left = '10px';
					$right = 'initial';
				}

				$custom_css .= "
                    div.pp_woocommerce a.yith_expand {
                     background-color: {$lighbox_colors_array['background']};
                     width: {$lighbox_icon_size}px;
                     height: {$lighbox_icon_size}px;
                     top: {$top};
                     bottom: {$bottom};
                     left: {$left};
                     right: {$right};
                     border-radius: {$lighbox_radius}%;
                    }

                    .expand-button-hidden svg{
                       width: {$lighbox_icon_size}px;
                       height: {$lighbox_icon_size}px;
					}

					.expand-button-hidden path{
                       fill: {$lighbox_colors_array['icon']};
					}
                    ";



			return apply_filters( 'yith_ywzm_custom_css', $custom_css );
		}

		public function get_custom_css_prettyphoto (){

			$colors_default = Array(
				'background' => 'white',
				'icon' => 'black',
			);

			$lighbox_colors_array = get_option( 'ywzm_lightbox_icon_colors', $colors_default );
			$lighbox_icon_size = get_option( 'ywzm_lightbox_icon_size', '25' );

			$custom_css = '';

			$custom_css .= "
                    div.pp_woocommerce a.pp_contract, div.pp_woocommerce a.pp_expand{
                        content: unset !important;
                        background-color: {$lighbox_colors_array['background']};
                        width: {$lighbox_icon_size}px;
                        height: {$lighbox_icon_size}px;
                        margin-top: 5px;
						margin-left: 5px;
                    }

                    div.pp_woocommerce a.pp_contract, div.pp_woocommerce a.pp_expand:hover{
                        background-color: {$lighbox_colors_array['background']};
                    }
                     div.pp_woocommerce a.pp_contract, div.pp_woocommerce a.pp_contract:hover{
                        background-color: {$lighbox_colors_array['background']};
                    }

                    a.pp_expand:before, a.pp_contract:before{
                    content: unset !important;
                    }

                     a.pp_expand .expand-button-hidden svg, a.pp_contract .expand-button-hidden svg{
                       width: {$lighbox_icon_size}px;
                       height: {$lighbox_icon_size}px;
                       padding: 5px;
					}

					.expand-button-hidden path{
                       fill: {$lighbox_colors_array['icon']};
					}

                    ";


			return apply_filters( 'yith_ywzm_custom_css_prettyphoto', $custom_css );
		}


		/**
		 * Add attributes to product variations
		 *
		 * @param array                $data Data.
		 * @param WC_Product_Variable  $wc_prod Variable product.
		 * @param WC_Product_Variation $variation Variation.
		 *
		 * @return mixed
		 */
		public function available_variation( $data, $wc_prod, $variation ) {

			$attachment_id = get_post_thumbnail_id( $variation->get_id() );
			$attachment    = wp_get_attachment_image_src( $attachment_id, 'shop_magnifier' );

			$data['image_magnifier'] = $attachment ? current( $attachment ) : '';

			return $data;
		}
	}
}
