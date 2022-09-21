<?php

namespace DgoraWcas\Admin;

use  DgoraWcas\Helpers ;
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * Adapted from https://github.com/tareq1988/wordpress-settings-api-class
 *
 */
/**
 * weDevs Settings API wrapper class
 *
 * @version 1.1
 *
 * @author Tareq Hasan <tareq@weDevs.com>
 * @link http://tareq.weDevs.com Tareq's Planet
 * @example src/settings-api.php How to use the class
 */
class SettingsAPI
{
    /**
     * settings sections array
     *
     * @var array
     */
    private  $settings_sections = array() ;
    /**
     * Settings fields array
     *
     * @var array
     */
    private  $settingsFields = array() ;
    /**
     * Singleton instance
     *
     * @var object
     */
    private static  $_instance ;
    /*
     * Name
     */
    private  $name ;
    /*
     * Prefix
     */
    private  $prefix ;
    /*
     * Constructor
     *
     * @param string $prefix - unique prefix for CSS classes and other names
     */
    public function __construct( $name = '' )
    {
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
        $this->name = sanitize_title( $name );
        $this->prefix = sanitize_title( $name ) . '-';
    }
    
    /**
     * Enqueue scripts and styles
     */
    function admin_enqueue_scripts()
    {
        
        if ( Helpers::isSettingsPage() ) {
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_media();
            wp_enqueue_script( 'wp-color-picker' );
            wp_enqueue_script( 'jquery' );
        }
    
    }
    
    /**
     * Set settings sections
     *
     * @param array $sections setting sections array
     */
    function set_sections( $sections )
    {
        $this->settings_sections = $sections;
        return $this;
    }
    
    /**
     * Add a single section
     *
     * @param array $section
     */
    function add_section( $section )
    {
        $this->settings_sections[] = $section;
        return $this;
    }
    
    /**
     * Set settings fields
     *
     * @param array $fields settings fields array
     */
    function set_fields( $fields )
    {
        $this->settingsFields = $fields;
        return $this;
    }
    
    function add_field( $section, $field )
    {
        $defaults = array(
            'name'  => '',
            'label' => '',
            'desc'  => '',
            'type'  => 'text',
        );
        $arg = wp_parse_args( $field, $defaults );
        $this->settingsFields[$section][] = $arg;
        return $this;
    }
    
    /**
     * Initialize and registers the settings sections and fileds to WordPress
     *
     * Usually this should be called at `admin_init` hook.
     *
     * This function gets the initiated settings sections and fields. Then
     * registers them to WordPress and ready for use.
     */
    public function settings_init()
    {
        if ( false == get_option( $this->name ) ) {
            add_option( $this->name );
        }
        //Register settings sections
        foreach ( $this->settings_sections as $section ) {
            
            if ( isset( $section['desc'] ) && !empty($section['desc']) ) {
                $section['desc'] = '<div class="inside">' . $section['desc'] . '</div>';
                $callback = function () use( $section ) {
                    echo  $section['desc'] ;
                };
            } elseif ( isset( $section['callback'] ) ) {
                $callback = $section['callback'];
            } else {
                $callback = null;
            }
            
            add_settings_section(
                $section['id'],
                $section['title'],
                $callback,
                $section['id']
            );
        }
        //Register settings fields
        foreach ( $this->settingsFields as $section => $field ) {
            foreach ( $field as $option ) {
                $type = ( isset( $option['type'] ) ? $option['type'] : 'text' );
                $args = array(
                    'id'                => $option['name'],
                    'label_for'         => $args['label_for'] = "{$this->name}[{$option['name']}]",
                    'desc'              => ( isset( $option['desc'] ) ? $option['desc'] : '' ),
                    'name'              => $option['label'],
                    'size'              => ( isset( $option['size'] ) ? $option['size'] : null ),
                    'options'           => ( isset( $option['options'] ) ? $option['options'] : '' ),
                    'std'               => ( isset( $option['default'] ) ? $option['default'] : '' ),
                    'class'             => ( isset( $option['class'] ) ? $option['class'] : '' ),
                    'sanitize_callback' => ( isset( $option['sanitize_callback'] ) ? $option['sanitize_callback'] : '' ),
                    'type'              => $type,
                    'move_dest'         => ( isset( $option['move_dest'] ) ? $option['move_dest'] : '' ),
                    'input_data'        => ( isset( $option['input_data'] ) ? $option['input_data'] : '' ),
                    'disabled'          => ( isset( $option['disabled'] ) ? $option['disabled'] : false ),
                );
                add_settings_field(
                    "{$this->name}[" . $option['name'] . ']',
                    $option['label'],
                    array( $this, 'callback_' . $type ),
                    $section,
                    $section,
                    $args
                );
            }
        }
        // Creates our settings in the options table
        foreach ( $this->settings_sections as $section ) {
            register_setting( $section['id'], $this->name, array( $this, 'sanitize_options' ) );
        }
    }
    
    /**
     * Get field description for display
     *
     * @param array $args settings field args
     */
    public function get_field_description( $args )
    {
        
        if ( !empty($args['desc']) ) {
            $css_class = $this->prefix . 'description-field';
            $desc = sprintf( '<p class="%s">%s</p>', $css_class, $args['desc'] );
        } else {
            $desc = '';
        }
        
        return $desc;
    }
    
    /**
     * Head
     */
    function callback_head( $args )
    {
        echo  '<span class="dgwt-wcas-settings-hr"></span>' ;
    }
    
    /**
     * Displays a text field for a settings field
     *
     * @param array $args settings field args
     */
    function callback_text( $args )
    {
        $value = apply_filters(
            'dgwt/wcas/settings/option_value',
            esc_attr( $this->get_option( $args['id'], $args['std'] ) ),
            $args['std'],
            $args
        );
        $size = ( isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular' );
        $type = ( isset( $args['type'] ) ? $args['type'] : 'text' );
        $disabled = ( !empty($args['disabled']) ? 'disabled' : '' );
        $html = '<fieldset class="dgwt-wcas-fieldset">';
        $html .= sprintf(
            '<input type="%1$s" class="%2$s-text" id="%3$s[%4$s]" name="%3$s[%4$s]" value="%5$s" %6$s/>',
            $type,
            $size,
            $this->name,
            $args['id'],
            $value,
            $disabled
        );
        $html .= $this->get_field_description( $args );
        $html .= '</fieldset>';
        echo  $html ;
    }
    
    /**
     * Displays a url field for a settings field
     *
     * @param array $args settings field args
     */
    function callback_url( $args )
    {
        $this->callback_text( $args );
    }
    
    /**
     * Displays a number field for a settings field
     *
     * @param array $args settings field args
     */
    function callback_number( $args )
    {
        $this->callback_text( $args );
    }
    
    /**
     * Displays a checkbox for a settings field
     *
     * @param array $args settings field args
     */
    function callback_checkbox( $args )
    {
        $value = apply_filters(
            'dgwt/wcas/settings/option_value',
            esc_attr( $this->get_option( $args['id'], $args['std'] ) ),
            $args['std'],
            $args
        );
        $disabled = ( !empty($args['disabled']) ? 'disabled' : '' );
        $moveDest = ( empty($args['move_dest']) ? '' : sprintf(
            'data-move-dest="%1$s[%2$s]" class="%3$s"',
            $this->name,
            $args['move_dest'],
            'js-dgwt-wcas-move-option'
        ) );
        $html = '<fieldset>';
        $html .= sprintf(
            '<label %3$s for="%1$s[%2$s]">',
            $this->name,
            $args['id'],
            $moveDest
        );
        $html .= sprintf( '<input type="hidden" name="%1$s[%2$s]" value="off" />', $this->name, $args['id'] );
        $html .= sprintf(
            '<input type="checkbox" class="checkbox" id="%1$s[%2$s]" name="%1$s[%2$s]" value="on" %3$s %4$s %5$s />',
            $this->name,
            $args['id'],
            checked( $value, 'on', false ),
            $args['input_data'],
            $disabled
        );
        $html .= sprintf( '<p class="%1$s-description-field">%2$s</p></label>', $this->name, $args['desc'] );
        $html .= '</fieldset>';
        echo  $html ;
    }
    
    /**
     * Displays a multicheckbox a settings field
     *
     * @param array $args settings field args
     */
    function callback_multicheck( $args )
    {
        $value = apply_filters(
            'dgwt/wcas/settings/option_value',
            $this->get_option( $args['id'], $args['std'] ),
            $args['std'],
            $args
        );
        $html = '<fieldset>';
        foreach ( $args['options'] as $key => $label ) {
            $checked = ( isset( $value[$key] ) ? $value[$key] : '0' );
            $html .= sprintf(
                '<label for="%1$s[%2$s][%3$s]">',
                $this->name,
                $args['id'],
                $key
            );
            $html .= sprintf(
                '<input type="checkbox" class="checkbox" id="%1$s[%2$s][%3$s]" name="%1$s[%2$s][%3$s]" value="%3$s" %4$s />',
                $this->name,
                $args['id'],
                $key,
                checked( $checked, $key, false )
            );
            $html .= sprintf( '%1$s</label><br>', $label );
        }
        $html .= $this->get_field_description( $args );
        $html .= '</fieldset>';
        echo  $html ;
    }
    
    /**
     * Displays a multicheckbox a settings field
     *
     * @param array $args settings field args
     */
    function callback_radio( $args )
    {
        $value = apply_filters(
            'dgwt/wcas/settings/option_value',
            $this->get_option( $args['id'], $args['std'], false ),
            $args['std'],
            $args
        );
        $html = '<fieldset>';
        foreach ( $args['options'] as $key => $label ) {
            $html .= sprintf(
                '<label for="%1$s%2$s[%3$s][%4$s]">',
                $this->prefix,
                $this->name,
                $args['id'],
                $key
            );
            $html .= sprintf(
                '<input type="radio" class="radio" id="%1$s%2$s[%3$s][%4$s]" name="%2$s[%3$s]" value="%4$s" %5$s />',
                $this->prefix,
                $this->name,
                $args['id'],
                $key,
                checked( $value, $key, false )
            );
            $html .= sprintf( '%1$s</label><br>', $label );
        }
        $html .= $this->get_field_description( $args );
        $html .= '</fieldset>';
        echo  $html ;
    }
    
    /**
     * Displays a selectbox for a settings field
     *
     * @param array $args settings field args
     */
    function callback_select( $args )
    {
        $value = apply_filters(
            'dgwt/wcas/settings/option_value',
            esc_attr( $this->get_option( $args['id'], $args['std'] ) ),
            $args['std'],
            $args
        );
        $size = ( isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular' );
        $html = sprintf(
            '<select class="%1$s" name="%2$s[%3$s]" id="%2$s[%3$s]">',
            $size,
            $this->name,
            $args['id']
        );
        foreach ( $args['options'] as $key => $label ) {
            $html .= sprintf(
                '<option value="%s"%s>%s</option>',
                $key,
                selected( $value, $key, false ),
                $label
            );
        }
        $html .= sprintf( '</select>' );
        $html .= $this->get_field_description( $args );
        echo  $html ;
    }
    
    /**
     * Displays a selectize multiple select for a settings field
     *
     * @param array $args settings field args
     */
    function callback_selectize( $args )
    {
        $value = apply_filters(
            'dgwt/wcas/settings/option_value',
            esc_attr( $this->get_option( $args['id'], $args['std'] ) ),
            $args['std'],
            $args
        );
        $options = ( !empty($args['options']) && is_array( $args['options'] ) ? $args['options'] : array() );
        $nonce = wp_create_nonce( 'dgwt_wcas_get_custom_fields' );
        $html = sprintf(
            '<input type="select-multiple" data-options="%4$s" class="dgwt-wcas-selectize" autocomplete="off" id="%1$s[%2$s]" name="%1$s[%2$s]" value="%3$s" data-security="%5$s"/>',
            $this->name,
            $args['id'],
            $value,
            http_build_query( $options ),
            $nonce
        );
        $html .= $this->get_field_description( $args );
        echo  $html ;
    }
    
    /**
     * Displays a textarea for a settings field
     *
     * @param array $args settings field args
     */
    function callback_textarea( $args )
    {
        $value = apply_filters(
            'dgwt/wcas/settings/option_value',
            esc_textarea( $this->get_option( $args['id'], $args['std'] ) ),
            $args['std'],
            $args
        );
        $size = ( isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular' );
        $html = sprintf(
            '<textarea rows="5" cols="55" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]">%4$s</textarea>',
            $size,
            $this->name,
            $args['id'],
            $value
        );
        $html .= $this->get_field_description( $args );
        echo  $html ;
    }
    
    /**
     * Displays a textarea for a settings field
     *
     * @param array $args settings field args
     *
     * @return void
     */
    function callback_html( $args )
    {
        
        if ( !empty($args['desc']) ) {
            $css_class = $this->prefix . 'description-row';
            $desc = sprintf( '<div class="%s">%s</div>', $css_class, $args['desc'] );
        } else {
            $desc = '';
        }
        
        echo  $desc ;
    }
    
    /**
     * Displays a rich text textarea for a settings field
     *
     * @param array $args settings field args
     */
    function callback_wysiwyg( $args )
    {
        $value = $this->get_option( $args['id'], $args['std'] );
        $size = ( isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : '500px' );
        echo  '<div style="max-width: ' . $size . ';">' ;
        $editor_settings = array(
            'teeny'         => true,
            'textarea_name' => $this->name . '[' . $args['id'] . ']',
            'textarea_rows' => 10,
        );
        if ( isset( $args['options'] ) && is_array( $args['options'] ) ) {
            $editor_settings = array_merge( $editor_settings, $args['options'] );
        }
        wp_editor( $value, $this->name . '-' . $args['id'], $editor_settings );
        echo  '</div>' ;
        echo  $this->get_field_description( $args ) ;
    }
    
    /**
     * Displays a file upload field for a settings field
     *
     * @param array $args settings field args
     */
    function callback_file( $args )
    {
        $value = esc_attr( $this->get_option( $args['id'], $args['std'] ) );
        $size = ( isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular' );
        $id = $this->name . '[' . $args['id'] . ']';
        $label = ( isset( $args['options']['button_label'] ) ? $args['options']['button_label'] : __( 'Choose File' ) );
        $html = sprintf(
            '<input type="text" class="%1$s-text %2$surl" id="%3$s[%4$s]" name="%3$s[%4$s]" value="%5$s"/>',
            $size,
            $this->prefix,
            $this->name,
            $args['id'],
            $value
        );
        $html .= '<input type="button" class="button ' . $this->prefix . 'browse" value="' . $label . '" />';
        $html .= $this->get_field_description( $args );
        echo  $html ;
    }
    
    /**
     * Displays a password field for a settings field
     *
     * @param array $args settings field args
     */
    function callback_password( $args )
    {
        $value = esc_attr( $this->get_option( $args['id'], $args['std'] ) );
        $size = ( isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular' );
        $html = sprintf(
            '<input type="password" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>',
            $size,
            $this->name,
            $args['id'],
            $value
        );
        $html .= $this->get_field_description( $args );
        echo  $html ;
    }
    
    /**
     * Displays a color picker field for a settings field
     *
     * @param array $args settings field args
     */
    function callback_color( $args )
    {
        $value = esc_attr( $this->get_option( $args['id'], $args['std'] ) );
        $size = ( isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular' );
        $html = sprintf(
            '<input type="text" class="%1$s-text wp-color-picker-field" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s" data-default-color="%5$s" />',
            $size,
            $this->name,
            $args['id'],
            $value,
            $args['std']
        );
        $html .= $this->get_field_description( $args );
        echo  $html ;
    }
    
    /**
     * Displays a color picker field for a settings field
     *
     * @param array $args settings field args
     */
    function callback_desc( $args )
    {
        $html = '';
        
        if ( isset( $args['desc'] ) && !empty($args['desc']) ) {
            $html .= '<div class="dgwt-wcas-settings-info">';
            $html .= $args['desc'];
            $html .= '</div>';
        }
        
        echo  $html ;
    }
    
    /**
     * Displays a color picker field for a settings field
     *
     * @param array $args settings field args
     */
    function callback_datepicker( $args )
    {
        $value = esc_attr( $this->get_option( $args['id'], $args['std'] ) );
        $size = ( isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular' );
        $html = sprintf(
            '<input type="text" class="%1$s-text dgwt-datepicker-field" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s" />',
            $size,
            $this->name,
            $args['id'],
            $value
        );
        $html .= $this->get_field_description( $args );
        echo  $html ;
    }
    
    /**
     * Displays filters rules in settings
     *
     * @param array $args settings field args
     */
    function callback_filters_rules_plug( $args )
    {
        ob_start();
        ?>
		<div id="dgwt-wcas-settings-filters-rules">
			<div>
				<p><?php 
        _e( 'No rules', 'ajax-search-for-woocommerce' );
        ?></p><br>
			</div>
			<button
				class="button button-secondary"><?php 
        _e( 'Add new rule', 'ajax-search-for-woocommerce' );
        ?></button>
		</div>
		<?php 
        echo  ob_get_clean() ;
    }
    
    /**
     * Sanitize callback for Settings API
     */
    function sanitize_options( $options )
    {
        if ( !isset( $options ) || empty($options) || !is_array( $options ) ) {
            return $options;
        }
        foreach ( $options as $option_slug => $option_value ) {
            $sanitize_callback = $this->get_sanitize_callback( $option_slug );
            // If callback is set, call it
            
            if ( $sanitize_callback ) {
                $options[$option_slug] = call_user_func( $sanitize_callback, $option_value );
                continue;
            }
        
        }
        return $options;
    }
    
    /**
     * Get sanitization callback for given option slug
     *
     * @param string $slug option slug
     *
     * @return mixed string or bool false
     */
    function get_sanitize_callback( $slug = '' )
    {
        if ( empty($slug) ) {
            return false;
        }
        // Iterate over registered fields and see if we can find proper callback
        foreach ( $this->settingsFields as $section => $options ) {
            foreach ( $options as $option ) {
                if ( $option['name'] != $slug ) {
                    continue;
                }
                // First check if sanitize_callback was added directly to the option
                if ( !empty($option['sanitize_callback']) && is_callable( $option['sanitize_callback'] ) ) {
                    return $option['sanitize_callback'];
                }
                // Not added? Sanitize it based on a type
                switch ( $option['type'] ) {
                    case 'checkbox':
                        $sanitize_callback = array( __CLASS__, 'sanitize_checkbox' );
                        break;
                    case 'number':
                        $sanitize_callback = 'intval';
                        break;
                    case 'text':
                    case 'textarea':
                        $sanitize_callback = 'wp_kses_post';
                        break;
                    case 'color':
                        $sanitize_callback = array( __CLASS__, 'sanitize_color' );
                        break;
                    case 'select':
                        $sanitize_callback = 'sanitize_key';
                        break;
                    case 'file':
                        $sanitize_callback = 'esc_url';
                        break;
                    default:
                        $sanitize_callback = 'wp_kses_post';
                }
                return ( !empty($sanitize_callback) && is_callable( $sanitize_callback ) ? $sanitize_callback : false );
            }
        }
        return false;
    }
    
    /**
     * Sanitize checkbox
     *
     * @param string $value
     *
     * @return string
     */
    public static function sanitize_checkbox( $value )
    {
        return ( in_array( $value, array( 'on', 'off' ) ) ? $value : '' );
    }
    
    /**
     * Sanitize color
     *
     * @param string $value
     *
     * @return string
     */
    public static function sanitize_color( $value )
    {
        return ( preg_match( '/^#[a-f0-9]{6}$/i', $value ) ? $value : '' );
    }
    
    /**
     * Get the value of a settings field
     *
     * @param string $option settings field name
     * @param string $default default text if it's not found
     * @param bool $not_empty allow empty value
     *
     * @return string
     */
    function get_option( $option, $default = '', $allow_empty = true )
    {
        $options = get_option( $this->name );
        $value = $default;
        if ( isset( $options[$option] ) ) {
            
            if ( $allow_empty ) {
                $value = $options[$option];
            } else {
                if ( !empty($options[$option]) ) {
                    $value = $options[$option];
                }
            }
        
        }
        return apply_filters( 'dgwt/wcas/settings/load_value/key=' . $option, $value );
    }
    
    /**
     * Show navigations as tab
     *
     * Shows all the settings section labels as tab
     */
    function show_navigation()
    {
        $html = '<h2 class="nav-tab-wrapper ' . $this->prefix . 'nav-tab-wrapper">';
        foreach ( $this->settings_sections as $tab ) {
            $html .= sprintf( '<a href="#%1$s" class="nav-tab" id="%1$s-tab">%2$s</a>', $tab['id'], $tab['title'] );
        }
        $html .= '<a target="_blank" href="' . dgoraAsfwFs()->contact_url() . '" class="js-nav-tab-minor nav-tab-minor nav-tab-minor-contact" >' . __( 'Contact', 'ajax-search-for-woocommerce' ) . '</a>';
        if ( !dgoraAsfwFs()->is_premium() ) {
            $html .= '<a target="_blank" href="https://fibosearch.com/showcase/?utm_source=wp-admin&utm_medium=referral&utm_campaign=settings&utm_content=showcase&utm_gen=utmdc" class="js-nav-tab-minor nav-tab-minor nav-tab-minor-showcase" >' . __( 'Showcase', 'ajax-search-for-woocommerce' ) . '</a>';
        }
        $html .= '</h2>';
        echo  $html ;
    }
    
    /**
     * Show the section settings forms
     *
     * This function displays every sections in a different form
     */
    function show_forms()
    {
        ?>
		<div class="metabox-holder">
			<form class="dgwt-eq-settings-form" method="post" action="options.php">
				<?php 
        foreach ( $this->settings_sections as $form ) {
            ?>
					<div id="<?php 
            echo  $form['id'] ;
            ?>" class="<?php 
            echo  $this->prefix ;
            ?>group"
						 style="display: none;">

						<?php 
            do_action( $this->prefix . 'form_top_' . $form['id'], $form );
            settings_fields( $form['id'] );
            do_settings_sections( $form['id'] );
            do_action( $this->prefix . 'form_bottom_' . $form['id'], $form );
            ?>
						<div style="padding-left: 10px">
							<?php 
            submit_button();
            ?>
						</div>
						<?php 
            do_action( $this->prefix . 'form_end_' . $form['id'], $form );
            ?>
					</div>
				<?php 
        }
        ?>
			</form>
		</div>
		<?php 
        $this->script();
    }
    
    /**
     * Tabbable JavaScript codes & Initiate Color Picker
     *
     * This code uses localstorage for displaying active tabs
     *
     * @return void
     */
    public function script()
    {
        ?>
		<script>
			jQuery(document).ready(function ($) {

				function markActiveGroup($group) {

					var name = $group.attr('id').replace('dgwt_wcas_', '');

					$group.addClass('dgwt-wcas-group-active');
					$group.closest('.js-dgwt-wcas-settings-body').attr('data-dgwt-wcas-active', name)

					$(document).trigger('dgwt_wcas_settings_group_active', $group);
				}

				//Initiate Color Picker
				if ($('.wp-color-picker-field').length > 0) {
					var addedClearer = false;
					$('.wp-color-picker-field').wpColorPicker({
						palettes: false,
						change: function (event, ui) {
							window.DGWT_WCAS_SEARCH_PREVIEW.onColorChangeHandler($(event.target), ui.color.toString());

							//https://github.com/Automattic/Iris/issues/57#issuecomment-899685019
							if (!addedClearer) {
								$('.wp-picker-clear').on('click', function () {
									var $input = $(this).parent('.wp-picker-input-wrap').find('input.wp-color-picker');
									if($input.length > 0){
										window.DGWT_WCAS_SEARCH_PREVIEW.onColorChangeHandler($input, '');
									}
								});
								addedClearer = true;
							}
						},
					});
				}

				// Switches option sections
				$('.<?php 
        echo  $this->prefix ;
        ?>group').hide();
				var activetab = '';
				var maybe_active = '';

				if (typeof (localStorage) != 'undefined') {
					maybe_active = localStorage.getItem('<?php 
        echo  $this->prefix ;
        ?>settings-active-tab');

					if (maybe_active) {

						// Check if tabs exists
						$('.<?php 
        echo  $this->prefix ;
        ?>nav-tab-wrapper a:not(.js-nav-tab-minor)').each(function () {

							if ($(this).attr('href') === maybe_active) {
								activetab = maybe_active;
							}
						});

					}
				}

				if (activetab != '' && $(activetab).length) {
					$(activetab).fadeIn();
					markActiveGroup($(activetab));
				} else {
					$('.<?php 
        echo  $this->prefix ;
        ?>group:first').fadeIn();
					markActiveGroup($('.<?php 
        echo  $this->prefix ;
        ?>group:first'));
				}
				$('.<?php 
        echo  $this->prefix ;
        ?>group .collapsed').each(function () {
					$(this).find('input:checked').parent().parent().parent().nextAll().each(
						function () {
							if ($(this).hasClass('last')) {
								$(this).removeClass('hidden');
								return false;
							}
							$(this).filter('.hidden').removeClass('hidden');
						});
				});

				if (activetab != '' && $(activetab + '-tab').length) {
					$(activetab + '-tab').addClass('nav-tab-active');
				} else {
					$('.<?php 
        echo  $this->prefix ;
        ?>nav-tab-wrapper a:first').addClass('nav-tab-active');
				}
				$('.<?php 
        echo  $this->prefix ;
        ?>nav-tab-wrapper a:not(.js-nav-tab-minor)').on('click', function (evt) {

					if (typeof (localStorage) != 'undefined') {
						localStorage.setItem('<?php 
        echo  $this->prefix ;
        ?>settings-active-tab', $(this).attr('href'));
					}

					$('.<?php 
        echo  $this->prefix ;
        ?>nav-tab-wrapper a').removeClass('nav-tab-active');

					$(this).addClass('nav-tab-active').blur();
					var clicked_group = $(this).attr('href');


					$('.<?php 
        echo  $this->prefix ;
        ?>group').hide();
					$(clicked_group).fadeIn();
					markActiveGroup($(clicked_group));
					evt.preventDefault();
				});

				$('.<?php 
        echo  $this->prefix ;
        ?>browse').on('click', function (event) {
					event.preventDefault();

					var self = $(this);

					// Create the media frame.
					var file_frame = wp.media.frames.file_frame = wp.media({
						title: self.data('uploader_title'),
						button: {
							text: self.data('uploader_button_text'),
						},
						multiple: false
					});

					file_frame.on('select', function () {
						attachment = file_frame.state().get('selection').first().toJSON();

						self.prev('.<?php 
        echo  $this->prefix ;
        ?>url').val(attachment.url);
					});

					// Finally, open the modal
					file_frame.open();
				});
			});
		</script>

		<style>
			/** WordPress 3.8 Fix **/
			.form-table th {
				padding: 20px 10px;
			}

			#wpbody-content .metabox-holder {
				padding-top: 5px;
			}
		</style>
		<?php 
    }

}