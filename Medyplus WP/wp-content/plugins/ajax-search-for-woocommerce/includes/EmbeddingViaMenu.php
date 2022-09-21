<?php

namespace DgoraWcas;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EmbeddingViaMenu {
	const SEARCH_PLACEHOLDER = 'dgwt_wcas_search_box';

	public function init() {

		if ( is_admin() ) {
			add_action( 'admin_head-nav-menus.php', array( $this, 'addNavMenuMetaBoxes' ) );
			add_action( 'wp_nav_menu_item_custom_fields', array( $this, 'addNavMenuItemCustomFields' ), 10, 2 );
			add_action( 'wp_update_nav_menu_item', array( $this, 'updateNavMenuItem' ), 10, 3 );

			add_action( 'admin_head', array( $this, 'navMenuStyle' ) );
			add_action( 'admin_footer', array( $this, 'navMenuScripts' ) );

		} else {

			add_filter( 'walker_nav_menu_start_el', array( $this, 'processMenuItem' ), 50, 2 );
			add_filter( 'megamenu_walker_nav_menu_start_el', array( $this, 'processMenuItem' ), 50, 2 );

		}
	}

	/**
	 * Check if nav-menus screen is active
	 *
	 * @return bool
	 */
	private function isNavMenuScreen() {
		$isNav  = false;
		$screen = get_current_screen();

		if ( ! empty( $screen->id ) && ( $screen->id === 'nav-menus' ) ) {
			$isNav = true;
		}

		return $isNav;
	}

	/**
	 * Add custom nav meta box.
	 *
	 * Adapted from http://www.johnmorrisonline.com/how-to-add-a-fully-functional-custom-meta-box-to-wordpress-navigation-menus/.
	 *
	 * @return void
	 */
	public function addNavMenuMetaBoxes() {
		add_meta_box( 'dgwt_wcas_endpoints_nav_link', __( 'FiboSearch bar', 'ajax-search-for-woocommerce' ), array( $this, 'navMenuLinks' ), 'nav-menus', 'side',
			'low' );
	}

	/**
	 * Add custom fields to own menu item
	 */
	public function addNavMenuItemCustomFields( $item_id, $item ) {
		if ( $item->post_title !== self::SEARCH_PLACEHOLDER ) {
			return;
		}
		$layout = get_post_meta( $item_id, '_menu_item_dgwt_wcas_layout', true );
		if ( empty( $layout ) ) {
			$layout = 'default';
		}
		$searchIconColor = get_post_meta( $item_id, '_menu_item_dgwt_wcas_search_icon_color', true );
		?>
		<p class="description description-wide dgwt-wcas-description">
			<label for="edit-menu-item-dgwt-wcas-layout-<?php echo $item_id; ?>">
				<?php _e( 'Layout', 'ajax-search-for-woocommerce' ); ?>
				<select id="edit-menu-item-dgwt-wcas-layout-<?php echo $item_id; ?>" name="menu-item-dgwt-wcas-layout[<?php echo $item_id; ?>]" class="dgwt-wcas-layout-select">
					<?php
					foreach ( $this->getLayoutOptions() as $value => $name ) {
						$selected = selected( $value, $layout, false );
						printf( '<option %s value="%s">%s</option>', $selected, $value, $name );
					}
					?>
				</select>
			</label>
		</p>
		<p class="description description-wide dgwt-wcas-description">
			<?php _e( 'Search icon color', 'ajax-search-for-woocommerce' ); ?><br/>
			<input type="text" class="widefat wp-color-picker-field dwgt-wcas-color-picker"
				   name="menu-item-dgwt-wcas-search-icon-color[<?php echo $item_id; ?>]"
				   value="<?php echo esc_attr( $searchIconColor ); ?>"/>
		</p>
		<?php
	}

	/**
	 * Handle updates of custom fileds for own menu item
	 *
	 * @see wp_update_nav_menu_item()
	 */
	public function updateNavMenuItem( $menu_id, $menu_item_db_id, $args ) {
		if ( ! isset( $args['menu-item-title'] ) || $args['menu-item-title'] !== self::SEARCH_PLACEHOLDER ) {
			return;
		}

		$layout = isset( $_POST['menu-item-dgwt-wcas-layout'][ $menu_item_db_id ] ) ? sanitize_key( $_POST['menu-item-dgwt-wcas-layout'][ $menu_item_db_id ] ) : '';
		update_post_meta( $menu_item_db_id, '_menu_item_dgwt_wcas_layout', $layout );

		$searchIconColor = isset( $_POST['menu-item-dgwt-wcas-search-icon-color'][ $menu_item_db_id ] ) ? sanitize_hex_color( $_POST['menu-item-dgwt-wcas-search-icon-color'][ $menu_item_db_id ] ) : '';
		update_post_meta( $menu_item_db_id, '_menu_item_dgwt_wcas_search_icon_color', $searchIconColor );
	}

	/**
	 * Modifies the menu item display on frontend.
	 *
	 * @param string $itemOutput
	 *
	 * @return string
	 */
	public function processMenuItem( $itemOutput, $item ) {

		if (
			! empty( $itemOutput )
			&& is_string( $itemOutput )
			&& strpos( $itemOutput, self::SEARCH_PLACEHOLDER ) !== false
		) {
			$args   = '';
			$style  = '';
			$layout = get_post_meta( $item->ID, '_menu_item_dgwt_wcas_layout', true );
			if ( in_array( $layout, array( 'classic', 'icon', 'icon-flexible', 'icon-flexible-inv' ) ) ) {
				$args .= 'layout="' . $layout . '" ';
			}
			$searchIconColor = get_post_meta( $item->ID, '_menu_item_dgwt_wcas_search_icon_color', true );
			if ( in_array( $layout, array( 'icon', 'icon-flexible', 'icon-flexible-inv' ) ) && ! empty( $searchIconColor ) ) {
				$args  .= 'class="dgwt-wcas-menu-item-' . $item->ID . ' " ';
				$style = sprintf( '<style>.dgwt-wcas-menu-item-%d .dgwt-wcas-ico-magnifier-handler path {fill: %s;}</style>', $item->ID, esc_attr( $searchIconColor ) );
			}
			$itemOutput = do_shortcode( sprintf( '[fibosearch %s]', $args ) ) . $style;
		}

		return $itemOutput;
	}

	/**
	 * Output menu links.
	 *
	 * @return void
	 */
	public function navMenuLinks() {
		?>
		<div id="posttype-dgwt-wcas-endpoints" class="posttypediv">
			<p><?php _e( 'Add FiboSearch as a menu item.', 'ajax-search-for-woocommerce' ) ?></p>
			<div id="tabs-panel-dgwt-wcas-endpoints" class="tabs-panel tabs-panel-active">
				<ul id="dgwt-wcas-endpoints-checklist" class="categorychecklist form-no-clear">
					<li>
						<label class="menu-item-title">
							<input type="checkbox" class="menu-item-checkbox" name="menu-item[-1][menu-item-object-id]"
								   value="-1"/> <?php echo __( 'FiboSearch bar', 'ajax-search-for-woocommerce' ); ?>
						</label>
						<input type="hidden" class="menu-item-type" name="menu-item[-1][menu-item-type]" value="custom"/>
						<input type="hidden" class="menu-item-title" name="menu-item[-1][menu-item-title]" value="<?php echo self::SEARCH_PLACEHOLDER; ?>"/>
						<input type="hidden" class="menu-item-classes" name="menu-item[-1][menu-item-classes]"/>
					</li>
				</ul>
			</div>
			<p class="button-controls">
                <span class="add-to-menu">
					<button type="submit" class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e( 'Add to menu', 'woocommerce' ); ?>"
							name="add-post-type-menu-item" id="submit-posttype-dgwt-wcas-endpoints"><?php esc_html_e( 'Add to menu', 'woocommerce' ); ?></button>
					<span class="spinner"></span>
				</span>
			</p>
		</div>
		<?php
	}

	public function getDescription() {
		$html = '<div class="dgwt-wcas-admin-menu-item-desc js-dgwt-wcas-admin-menu-item-desc">';
		$html .= '<img class="" src="' . DGWT_WCAS_URL . 'assets/img/logo-for-review.png" width="32" height="32" />';
		$html .= '<span>' . __( 'FiboSearch bar will be displayed here.', 'ajax-search-for-woocommerce' ) . '</span>';
		$html .= '</div>';

		return $html;
	}

	public function getLayoutOptions() {
		return array(
			'default'           => __( 'Default', 'ajax-search-for-woocommerce' ),
			'classic'           => __( 'Search bar', 'ajax-search-for-woocommerce' ),
			'icon'              => __( 'Search icon', 'ajax-search-for-woocommerce' ),
			'icon-flexible'     => __( 'Icon on mobile, search bar on desktop', 'ajax-search-for-woocommerce' ),
			'icon-flexible-inv' => __( 'Icon on desktop, search bar on mobile', 'ajax-search-for-woocommerce' ),
		);
	}

	public function navMenuStyle() {

		if ( ! $this->isNavMenuScreen() ) {
			return;
		}
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );

		?>
		<style>
			.dgwt-wcas-admin-menu-item-desc {
				display: flex;
				flex-direction: row;
				justify-content: left;
				align-items: center;
			}

			.dgwt-wcas-admin-menu-item-desc img {
				display: block;
				margin-right: 15px;
				border-radius: 4px;
			}

			.dgwt-wcas-description select {
				width: 100%;
			}
		</style>

		<?php

	}

	public function navMenuScripts() {

		if ( ! $this->isNavMenuScreen() ) {
			return;
		}

		?>
		<script>
			(function ($) {

				function replaceLabels($menuItem) {

					var $menuItems = $('#menu-to-edit .menu-item-title');

					if ($menuItems.length > 0) {

						$menuItems.each(function () {
							if ($(this).text() === '<?php echo self::SEARCH_PLACEHOLDER; ?>') {

								var $menuItem = $(this).closest('.menu-item');

								$menuItem.find('.menu-item-title').text('FiboSearch bar');
								$menuItem.find('.item-type').text('<?php _e( 'Search bar', 'ajax-search-for-woocommerce' ); ?>');
								$menuItem.find('.menu-item-settings .edit-menu-item-title').closest('label').hide();
								$menuItem.find('.field-url').hide();


								if ($menuItem.find('.js-dgwt-wcas-admin-menu-item-desc').length == 0) {
									$menuItem.find('.menu-item-settings').prepend('<?php echo $this->getDescription(); ?>');
								}
							}
						});
					}
				}

				function colorPickers() {
					var $colorPickers = $('.dwgt-wcas-color-picker');
					if ($colorPickers.length > 0) {
						$colorPickers.wpColorPicker();
					}
				}

				function toggleColorPicker(el) {
					var layout = el.val();
					if (layout === 'default' || layout === 'classic') {
						el.closest('.dgwt-wcas-description').next().slideUp();
					} else {
						el.closest('.dgwt-wcas-description').next().slideDown();
					}
				}

				function syncColorPickersWithSelects() {
					var layoutSelects = $('.dgwt-wcas-layout-select');
					if (layoutSelects.length > 0) {
						layoutSelects.each(function (i, el) {
							toggleColorPicker($(el));
						});
					}
				}

				$(document).ready(function () {

					replaceLabels();
					colorPickers()
					syncColorPickersWithSelects();

				});

				$(document).on('change', '.dgwt-wcas-layout-select', function (e) {
					toggleColorPicker($(this))
				});

				$(document).ajaxComplete(function (event, request, settings) {

					if (
						typeof settings != 'undefined'
						&& typeof settings.data == 'string'
						&& settings.data.indexOf('action=add-menu-item') !== -1
						&& settings.data.indexOf('dgwt_wcas_search_box') !== -1
					) {
						replaceLabels();
						colorPickers();
						syncColorPickersWithSelects();

						setTimeout(function () {
							replaceLabels();
							colorPickers();
							syncColorPickersWithSelects();
						}, 500)

					}

				});

			}(jQuery));
		</script>

		<?php

	}

}
