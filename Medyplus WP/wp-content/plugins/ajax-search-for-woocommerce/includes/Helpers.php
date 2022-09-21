<?php

namespace DgoraWcas;

use  DgoraWcas\Engines\TNTSearchMySQL\SearchQuery\SearchResultsPageQuery ;
use  DgoraWcas\Integrations\Solver ;
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
class Helpers
{
    /**
     * Logger instance
     *
     * @var \WC_Logger
     */
    public static  $log = false ;
    /**
     * Prepare short description based on raw string including HTML
     *
     * @param string $string
     * @param int $numWords
     * @param string $allowableTags
     * @param bool $removeBreaks
     *
     * @return string
     */
    public static function makeShortDescription(
        $string,
        $numWords = 20,
        $allowableTags = '',
        $removeBreaks = true
    )
    {
        if ( empty($string) ) {
            return '';
        }
        $numWords = apply_filters( 'dgwt/wcas/description/words_limit', $numWords );
        //Remove headings
        $string = str_replace( array( '<h1><h2><h3><h4><h5><h6>' ), '<h3>', $string );
        $string = str_replace( array( '</h1></h2></h3></h4></h5></h6>' ), '</h3>', $string );
        $string = preg_replace( '/(<h3*?>).*?(<\\/h3>)/', '$1$2', $string );
        $string = self::stripAllTags( $string, $allowableTags, $removeBreaks );
        $hasHtml = strpos( $string, '<' ) !== false;
        
        if ( $hasHtml ) {
            // Remove attributes
            $string = preg_replace( "/<([a-z][a-z0-9]*)[^>]*?(\\/?)>/i", '<$1$2>', $string );
            $string = ( strpos( $allowableTags, '<p>' ) !== false ? wpautop( $string ) : $string );
            $string = force_balance_tags( html_entity_decode( wp_trim_words( htmlentities( $string ), $numWords ) ) );
        } else {
            $string = html_entity_decode( wp_trim_words( htmlentities( $string ), $numWords ) );
        }
        
        return $string;
    }
    
    /**
     * Add CSS classes to autocomplete wrapper
     *
     * @param array $args
     *
     * @return string
     */
    public static function searchWrappClasses( $args = array() )
    {
        $classes = array();
        if ( DGWT_WCAS()->settings->getOption( 'show_details_box' ) === 'on' ) {
            $classes[] = 'dgwt-wcas-is-detail-box';
        }
        $hasSubmit = ( isset( $args['submit_btn'] ) ? $args['submit_btn'] : DGWT_WCAS()->settings->getOption( 'show_submit_button' ) );
        
        if ( $hasSubmit === 'on' ) {
            $classes[] = 'dgwt-wcas-has-submit';
        } else {
            $classes[] = 'dgwt-wcas-no-submit';
        }
        
        if ( !empty($args['class']) ) {
            $classes[] = esc_html( $args['class'] );
        }
        
        if ( !empty($args['style']) ) {
            $type = esc_html( $args['style'] );
            $classes[] = 'dgwt-wcas-style-' . $type;
        }
        
        
        if ( !empty($args['layout']) ) {
            $type = esc_html( $args['layout'] );
            $classes[] = 'js-dgwt-wcas-layout-' . $type . ' dgwt-wcas-layout-' . $type;
        }
        
        
        if ( !empty($args['mobile_overlay']) ) {
            $classes[] = 'js-dgwt-wcas-mobile-overlay-enabled';
        } else {
            $classes[] = 'js-dgwt-wcas-mobile-overlay-disabled';
        }
        
        
        if ( !empty($args['darken_bg']) ) {
            $classes[] = 'dgwt-wcas-search-darkoverl-mounted';
            $classes[] = 'js-dgwt-wcas-search-darkoverl-mounted';
        }
        
        return implode( ' ', $classes );
    }
    
    /**
     * Get magnifier SVG ico
     *
     * @param string $class
     * @param string $type
     *
     * @return string
     */
    public static function getMagnifierIco( $class = 'dgwt-wcas-ico-magnifier', $type = 'magnifier-thin' )
    {
        return apply_filters( 'dgwt/wcas/form/magnifier_ico', self::getIcon( $type, $class ), $class );
    }
    
    /**
     * Get icon (SVG)
     *
     * @return string
     */
    public static function getIcon( $name, $class = '', $color = '' )
    {
        $svg = '';
        ob_start();
        switch ( $name ) {
            case 'magnifier-thin':
                $color = ( empty($color) ? '#444' : $color );
                ?>
				<svg version="1.1" class="<?php 
                echo  $class ;
                ?>" xmlns="http://www.w3.org/2000/svg"
					 xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
					 viewBox="0 0 51.539 51.361" enable-background="new 0 0 51.539 51.361" xml:space="preserve">
		             <path fill="<?php 
                echo  $color ;
                ?>"
						   d="M51.539,49.356L37.247,35.065c3.273-3.74,5.272-8.623,5.272-13.983c0-11.742-9.518-21.26-21.26-21.26 S0,9.339,0,21.082s9.518,21.26,21.26,21.26c5.361,0,10.244-1.999,13.983-5.272l14.292,14.292L51.539,49.356z M2.835,21.082 c0-10.176,8.249-18.425,18.425-18.425s18.425,8.249,18.425,18.425S31.436,39.507,21.26,39.507S2.835,31.258,2.835,21.082z"/>
				</svg>
				<?php 
                break;
            case 'magnifier-md':
                $color = ( empty($color) ? '#444' : $color );
                ?>
				<svg class="<?php 
                echo  $class ;
                ?>" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"
					 width="24">
					<path fill="<?php 
                echo  $color ;
                ?>"
						  d="M15.5 14h-.79l-.28-.27c1.2-1.4 1.82-3.31 1.48-5.34-.47-2.78-2.79-5-5.59-5.34-4.23-.52-7.79 3.04-7.27 7.27.34 2.8 2.56 5.12 5.34 5.59 2.03.34 3.94-.28 5.34-1.48l.27.28v.79l4.25 4.25c.41.41 1.08.41 1.49 0 .41-.41.41-1.08 0-1.49L15.5 14zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
				</svg>
				<?php 
                break;
            case 'magnifier-pirx':
                $color = ( empty($color) ? '#111111' : $color );
                ?>
				<svg class="<?php 
                echo  $class ;
                ?>" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
					<path fill="<?php 
                echo  $color ;
                ?>" d=" M 16.722523,17.901412 C 16.572585,17.825208 15.36088,16.670476 14.029846,15.33534 L 11.609782,12.907819 11.01926,13.29667 C 8.7613237,14.783493 5.6172703,14.768302 3.332423,13.259528 -0.07366363,11.010358 -1.0146502,6.5989684 1.1898146,3.2148776
						  1.5505179,2.6611594 2.4056498,1.7447266 2.9644271,1.3130497 3.4423015,0.94387379 4.3921825,0.48568469 5.1732652,0.2475835 5.886299,0.03022609 6.1341883,0 7.2037391,0 8.2732897,0 8.521179,0.03022609 9.234213,0.2475835 c 0.781083,0.23810119 1.730962,0.69629029 2.208837,1.0654662
						  0.532501,0.4113763 1.39922,1.3400096 1.760153,1.8858877 1.520655,2.2998531 1.599025,5.3023778 0.199549,7.6451086 -0.208076,0.348322 -0.393306,0.668209 -0.411622,0.710863 -0.01831,0.04265 1.065556,1.18264 2.408603,2.533307 1.343046,1.350666 2.486621,2.574792 2.541278,2.720279 0.282475,0.7519
						  -0.503089,1.456506 -1.218488,1.092917 z M 8.4027892,12.475062 C 9.434946,12.25579 10.131043,11.855461 10.99416,10.984753 11.554519,10.419467 11.842507,10.042366 12.062078,9.5863882 12.794223,8.0659672 12.793657,6.2652398 12.060578,4.756293 11.680383,3.9737304 10.453587,2.7178427
						  9.730569,2.3710306 8.6921295,1.8729196 8.3992147,1.807606 7.2037567,1.807606 6.0082984,1.807606 5.7153841,1.87292 4.6769446,2.3710306 3.9539263,2.7178427 2.7271301,3.9737304 2.3469352,4.756293 1.6138384,6.2652398 1.6132726,8.0659672 2.3454252,9.5863882 c 0.4167354,0.8654208 1.5978784,2.0575608
						  2.4443766,2.4671358 1.0971012,0.530827 2.3890403,0.681561 3.6130134,0.421538 z
					"/>
				</svg>
				<?php 
                break;
            case 'arrow-left':
                $color = ( empty($color) ? '#fff' : $color );
                ?>
				<svg class="<?php 
                echo  $class ;
                ?>" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
					<path fill="<?php 
                echo  $color ;
                ?>"
						  d="M14 6.125H3.351l4.891-4.891L7 0 0 7l7 7 1.234-1.234L3.35 7.875H14z" fill-rule="evenodd"/>
				</svg>
				<?php 
                break;
            case 'close':
                $color = ( empty($color) ? '#ccc' : $color );
                ?>
				<svg class="<?php 
                echo  $class ;
                ?>" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"
					 width="24">
					<path fill="<?php 
                echo  $color ;
                ?>"
						  d="M18.3 5.71c-.39-.39-1.02-.39-1.41 0L12 10.59 7.11 5.7c-.39-.39-1.02-.39-1.41 0-.39.39-.39 1.02 0 1.41L10.59 12 5.7 16.89c-.39.39-.39 1.02 0 1.41.39.39 1.02.39 1.41 0L12 13.41l4.89 4.89c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41L13.41 12l4.89-4.89c.38-.38.38-1.02 0-1.4z"/>
				</svg>
				<?php 
                break;
            case 'preloader':
                $color = ( empty($color) ? '#ddd' : $color );
                ?>
				<svg class="dgwt-wcas-loader-circular <?php 
                echo  $class ;
                ?>" viewBox="25 25 50 50">
					<circle class="dgwt-wcas-loader-circular-path" cx="50" cy="50" r="20" fill="none"
							stroke="<?php 
                echo  $color ;
                ?>" stroke-miterlimit="10"/>
				</svg>
				<?php 
                break;
            case 'face-smile':
                $color = ( empty($color) ? '#ddd' : $color );
                ?>
				<svg class="<?php 
                echo  $class ;
                ?>" width="64" height="54" viewBox="0 0 64 54" xmlns="http://www.w3.org/2000/svg">
					<g transform="translate(-34.294922,-62.985674)">
						<path fill="<?php 
                echo  $color ;
                ?>"
							  d="m 60.814237,116.23604 c -9.048223,-1.66914 -16.519379,-6.20497 -21.793789,-13.23128 -1.60071,-2.1324 -4.314629,-7.202619 -4.669151,-8.723059 -0.160775,-0.68952 -0.10638,-0.72795 1.948599,-1.37712 2.642805,-0.83486 2.824539,-0.83179 3.160818,0.0535 2.303833,6.06532 7.117271,11.515849 13.090786,14.823419 3.461115,1.91644 6.665367,2.90424 10.975589,3.38351 8.531032,0.94862 17.134659,-2.15367 23.386899,-8.4328 3.02499,-3.037969 4.6729,-5.555849 6.38356,-9.753479 l 0.39246,-0.963 2.31721,0.75094 c 2.22899,0.72234 2.31594,0.77987 2.28317,1.51079 -0.042,0.93936 -2.04226,5.11147 -3.54876,7.402399 -1.51073,2.29734 -5.78521,6.66064 -8.29613,8.46852 -4.24115,3.05365 -9.37348,5.21483 -14.417657,6.07116 -2.90299,0.49283 -8.586032,0.50118 -11.213604,0.0164 z M 47.412846,73.573941 c -0.309888,-0.59465 -0.464319,-1.51592 -0.477161,-2.84652 -0.02483,-2.57365 0.873951,-4.54095 2.753263,-6.02646 1.633788,-1.29143 2.83173,-1.69831 4.961024,-1.685 2.909938,0.0182 5.40834,1.54992 6.76366,4.14667 0.581876,1.11485 0.698121,1.68141 0.704505,3.43363 0.0045,1.23792 -0.144736,2.45984 -0.363942,2.97966 -0.361143,0.85641 -0.401692,0.87525 -1.4427,0.67016 -1.441299,-0.28395 -9.681541,-0.29597 -11.215046,-0.0164 -1.208977,0.22044 -1.231574,0.21163 -1.683603,-0.65577 z m 23.590775,-0.1224 c -0.24773,-0.57773 -0.44716,-1.76886 -0.46047,-2.75021 -0.0439,-3.23955 2.24441,-6.50245 5.168157,-7.3692 3.62299,-1.07405 7.38202,0.40563 9.28658,3.6555 0.92458,1.57769 1.14637,4.5061 0.47452,6.26533 l -0.46168,1.20889 -1.21243,-0.22321 c -1.58287,-0.29141 -9.51286,-0.28827 -11.113147,0.004 l -1.24453,0.22755 z"
							  id="path21"/>
					</g>
				</svg>
				<?php 
                break;
            case 'face-sad':
                $color = ( empty($color) ? '#ddd' : $color );
                ?>
				<svg class="<?php 
                echo  $class ;
                ?>" width="64" height="54" viewBox="0 0 64 54" xmlns="http://www.w3.org/2000/svg">
					<g
						transform="translate(-34.294922,-62.985674)">
						<path
							fill="<?php 
                echo  $color ;
                ?>"
							d="m 65.333527,90.188647 c -4.021671,0.04374 -7.952038,1.143031 -11.366869,2.831872 -2.463508,1.202323 -4.481746,2.907174 -6.347127,4.661802 -1.281094,1.28132 -2.179231,2.786709 -2.971747,4.298239 -0.224234,0.44934 -0.524822,1.14105 0.121782,1.45463 1.051756,0.40354 2.200055,0.61503 3.294735,0.93066 0.910618,-1.93591 2.051059,-3.84127 3.823337,-5.359309 2.631922,-2.416592 6.216388,-4.201746 10.051876,-4.937105 3.649681,-0.714791 7.581941,-0.473293 11.128238,0.561988 5.123487,1.585728 9.378549,4.981727 11.316726,9.159886 0.309445,0.53176 1.133677,0.34172 1.670314,0.20167 0.749446,-0.21997 1.601188,-0.3033 2.249216,-0.69551 0.392685,-0.41377 -0.04361,-0.941 -0.217903,-1.36088 -1.187297,-2.097179 -2.607848,-4.146079 -4.601341,-5.811643 -3.684753,-3.211163 -8.802941,-5.255991 -14.137691,-5.844622 -1.333029,-0.105798 -2.675274,-0.117509 -4.013546,-0.09168 z"/>
						<path
							fill="<?php 
                echo  $color ;
                ?>"
							d="m 98.621511,94.193314 c -42.884393,-20.805093 -21.442196,-10.402547 0,0 z M 47.743964,73.489793 c -0.309888,-0.59465 -0.464319,-1.51592 -0.477161,-2.84652 -0.02483,-2.57365 0.873951,-4.54095 2.753263,-6.02646 1.633788,-1.29143 2.83173,-1.69831 4.961024,-1.685 2.909938,0.0182 5.40834,1.54992 6.76366,4.14667 0.581876,1.11485 0.698121,1.68141 0.704505,3.43363 0.0045,1.23792 -0.144736,2.45984 -0.363942,2.97966 -0.361143,0.85641 -0.401692,0.87525 -1.4427,0.67016 -1.441299,-0.28395 -9.681541,-0.29597 -11.215046,-0.0164 -1.208977,0.22044 -1.231574,0.21163 -1.683603,-0.65577 z m 23.590775,-0.1224 c -0.24773,-0.57773 -0.44716,-1.76886 -0.46047,-2.75021 -0.0439,-3.23955 2.24441,-6.50245 5.168157,-7.3692 3.62299,-1.07405 7.38202,0.40563 9.28658,3.6555 0.92458,1.57769 1.14637,4.5061 0.47452,6.26533 l -0.46168,1.20889 -1.21243,-0.22321 c -1.58287,-0.29141 -9.51286,-0.28827 -11.113147,0.004 l -1.24453,0.22755 z"/>
					</g>
				</svg>
				<?php 
                break;
            case 'voice-search-inactive':
                $color = ( empty($color) ? '#444' : $color );
                ?>
				<svg class="<?php 
                echo  $class ;
                ?>" xmlns="http://www.w3.org/2000/svg" height="24"
					 width="24">
					<path fill="<?php 
                echo  $color ;
                ?>"
						  d="M12 13Q11.15 13 10.575 12.425Q10 11.85 10 11V5Q10 4.15 10.575 3.575Q11.15 3 12 3Q12.85 3 13.425 3.575Q14 4.15 14 5V11Q14 11.85 13.425 12.425Q12.85 13 12 13ZM12 8Q12 8 12 8Q12 8 12 8Q12 8 12 8Q12 8 12 8Q12 8 12 8Q12 8 12 8Q12 8 12 8Q12 8 12 8ZM11.5 20.5V16.975Q9.15 16.775 7.575 15.062Q6 13.35 6 11H7Q7 13.075 8.463 14.537Q9.925 16 12 16Q14.075 16 15.538 14.537Q17 13.075 17 11H18Q18 13.35 16.425 15.062Q14.85 16.775 12.5 16.975V20.5ZM12 12Q12.425 12 12.713 11.712Q13 11.425 13 11V5Q13 4.575 12.713 4.287Q12.425 4 12 4Q11.575 4 11.288 4.287Q11 4.575 11 5V11Q11 11.425 11.288 11.712Q11.575 12 12 12Z"/>
				</svg>
				<?php 
                break;
            case 'voice-search-inactive-pirx':
                // https://fonts.google.com/icons Icon: Mic Fill: 0 Weight: 400 Grade: 0 Optical size: 24
                $color = ( empty($color) ? '#444' : $color );
                ?>
				<svg class="<?php 
                echo  $class ;
                ?>" xmlns="http://www.w3.org/2000/svg" height="24" width="24">
					<path fill="<?php 
                echo  $color ;
                ?>"
						  d="M12 14q-1.25 0-2.125-.875T9 11V5q0-1.25.875-2.125T12 2q1.25 0 2.125.875T15 5v6q0 1.25-.875 2.125T12 14Zm0-6Zm-1 13v-3.075q-2.6-.35-4.3-2.325Q5 13.625 5 11h2q0 2.075 1.463 3.537Q9.925 16 12 16t3.538-1.463Q17 13.075 17 11h2q0 2.625-1.7 4.6-1.7 1.975-4.3 2.325V21Zm1-9q.425 0 .713-.288Q13 11.425 13 11V5q0-.425-.287-.713Q12.425 4 12 4t-.712.287Q11 4.575 11 5v6q0 .425.288.712.287.288.712.288Z"/>
				</svg>
				<?php 
                break;
            case 'voice-search-active':
                $color = ( empty($color) ? '#444' : $color );
                ?>
				<svg class="<?php 
                echo  $class ;
                ?>" xmlns="http://www.w3.org/2000/svg" height="24"
					 width="24">
					<path fill="<?php 
                echo  $color ;
                ?>"
						  d="M12 13Q11.15 13 10.575 12.425Q10 11.85 10 11V5Q10 4.15 10.575 3.575Q11.15 3 12 3Q12.85 3 13.425 3.575Q14 4.15 14 5V11Q14 11.85 13.425 12.425Q12.85 13 12 13ZM11.5 20.5V16.975Q9.15 16.775 7.575 15.062Q6 13.35 6 11H7Q7 13.075 8.463 14.537Q9.925 16 12 16Q14.075 16 15.538 14.537Q17 13.075 17 11H18Q18 13.35 16.425 15.062Q14.85 16.775 12.5 16.975V20.5Z"/>
				</svg>
				<?php 
                break;
            case 'voice-search-active-pirx':
                // https://fonts.google.com/icons Icon: Mic Fill: 1 Weight: 400 Grade: 0 Optical size: 24
                $color = ( empty($color) ? '#444' : $color );
                ?>
				<svg class="<?php 
                echo  $class ;
                ?>" xmlns="http://www.w3.org/2000/svg" height="24"
					 width="24">
					<path fill="<?php 
                echo  $color ;
                ?>"
						  d="M12 14q-1.25 0-2.125-.875T9 11V5q0-1.25.875-2.125T12 2q1.25 0 2.125.875T15 5v6q0 1.25-.875 2.125T12 14Zm-1 7v-3.075q-2.6-.35-4.3-2.325Q5 13.625 5 11h2q0 2.075 1.463 3.537Q9.925 16 12 16t3.538-1.463Q17 13.075 17 11h2q0 2.625-1.7 4.6-1.7 1.975-4.3 2.325V21Z"/>
				</svg>
				<?php 
                break;
            case 'voice-search-disabled':
                $color = ( empty($color) ? '#444' : $color );
                ?>
				<svg class="<?php 
                echo  $class ;
                ?>" xmlns="http://www.w3.org/2000/svg" height="24" width="24">
					<path fill="<?php 
                echo  $color ;
                ?>"
						  d="M16.725 13.4 15.975 12.625Q16.1 12.325 16.2 11.9Q16.3 11.475 16.3 11H17.3Q17.3 11.75 17.138 12.337Q16.975 12.925 16.725 13.4ZM13.25 9.9 9.3 5.925V5Q9.3 4.15 9.875 3.575Q10.45 3 11.3 3Q12.125 3 12.713 3.575Q13.3 4.15 13.3 5V9.7Q13.3 9.75 13.275 9.8Q13.25 9.85 13.25 9.9ZM10.8 20.5V17.025Q8.45 16.775 6.875 15.062Q5.3 13.35 5.3 11H6.3Q6.3 13.075 7.763 14.537Q9.225 16 11.3 16Q12.375 16 13.312 15.575Q14.25 15.15 14.925 14.4L15.625 15.125Q14.9 15.9 13.913 16.4Q12.925 16.9 11.8 17.025V20.5ZM19.925 20.825 1.95 2.85 2.675 2.15 20.65 20.125Z"/>
				</svg>
				<?php 
                break;
            case 'voice-search-disabled-pirx':
                // https://fonts.google.com/icons Icon: Mic Off Fill: 1 Weight: 400 Grade: 0 Optical size: 24
                $color = ( empty($color) ? '#444' : $color );
                ?>
				<svg class="<?php 
                echo  $class ;
                ?>" xmlns="http://www.w3.org/2000/svg" height="24" width="24">
					<path fill="<?php 
                echo  $color ;
                ?>"
						  d="M17.75 14.95 16.3 13.5q.35-.575.525-1.2Q17 11.675 17 11h2q0 1.1-.325 2.087-.325.988-.925 1.863Zm-2.95-3L9 6.15V5q0-1.25.875-2.125T12 2q1.25 0 2.125.875T15 5v6q0 .275-.062.5-.063.225-.138.45ZM11 21v-3.1q-2.6-.35-4.3-2.312Q5 13.625 5 11h2q0 2.075 1.463 3.537Q9.925 16 12 16q.85 0 1.613-.262.762-.263 1.387-.738l1.425 1.425q-.725.575-1.587.962-.863.388-1.838.513V21Zm8.8 1.6L1.4 4.2l1.4-1.4 18.4 18.4Z"/>
				</svg>
				<?php 
                break;
        }
        $svg .= ob_get_clean();
        return apply_filters(
            'dgwt/wcas/icon',
            $svg,
            $name,
            $class,
            $color
        );
    }
    
    /**
     * Get search form action URL
     *
     * @return string
     */
    public static function searchFormAction()
    {
        $url = esc_url( home_url( '/' ) );
        if ( Multilingual::isPolylang() ) {
            
            if ( PLL() instanceof \PLL_Frontend ) {
                $lang = pll_current_language();
                $url = ( empty($lang) ? home_url( '/' ) : PLL()->links->get_home_url( $lang, true ) );
                $url = esc_url( $url );
            }
        
        }
        return apply_filters( 'dgwt/wcas/form/action', $url );
    }
    
    /**
     * Get name of the search input
     *
     * @return string
     */
    public static function getSearchInputName()
    {
        return apply_filters( 'dgwt/wcas/form/search_input/name', 's' );
    }
    
    /**
     * Return HTML for the setting section "How to use?"
     *
     * @return string HTML
     */
    public static function howToUseHtml()
    {
        $html = '';
        ob_start();
        include DGWT_WCAS_DIR . 'partials/admin/how-to-use.php';
        $html .= ob_get_clean();
        return $html;
    }
    
    /**
     * Return HTML for the setting section "Embedding in theme"
     *
     * @return string HTML
     */
    public static function embeddingInThemeHtml()
    {
        $html = '';
        ob_start();
        include DGWT_WCAS_DIR . 'partials/admin/embedding-in-theme.php';
        $html .= ob_get_clean();
        return $html;
    }
    
    /**
     * Minify JS
     *
     * @see https://gist.github.com/tovic/d7b310dea3b33e4732c0
     *
     * @param string
     *
     * @return string
     */
    public static function minifyJS( $input )
    {
        if ( trim( $input ) === "" ) {
            return $input;
        }
        return preg_replace( array(
            // Remove comment(s)
            '#\\s*("(?:[^"\\\\]++|\\\\.)*+"|\'(?:[^\'\\\\]++|\\\\.)*+\')\\s*|\\s*\\/\\*(?!\\!|@cc_on)(?>[\\s\\S]*?\\*\\/)\\s*|\\s*(?<![\\:\\=])\\/\\/.*(?=[\\n\\r]|$)|^\\s*|\\s*$#',
            // Remove white-space(s) outside the string and regex
            '#("(?:[^"\\\\]++|\\\\.)*+"|\'(?:[^\'\\\\]++|\\\\.)*+\'|\\/\\*(?>.*?\\*\\/)|\\/(?!\\/)[^\\n\\r]*?\\/(?=[\\s.,;]|[gimuy]|$))|\\s*([!%&*\\(\\)\\-=+\\[\\]\\{\\}|;:,.<>?\\/])\\s*#s',
            // Remove the last semicolon
            '#;+\\}#',
            // Minify object attribute(s) except JSON attribute(s). From `{'foo':'bar'}` to `{foo:'bar'}`
            '#([\\{,])([\'])(\\d+|[a-z_]\\w*)\\2(?=\\:)#i',
            // --ibid. From `foo['bar']` to `foo.bar`
            '#([\\w\\)\\]])\\[([\'"])([a-z_]\\w*)\\2\\]#i',
            // Replace `true` with `!0`
            '#(?<=return |[=:,\\(\\[])true\\b#',
            // Replace `false` with `!1`
            '#(?<=return |[=:,\\(\\[])false\\b#',
            // Clean up ...
            '#\\s*(\\/\\*|\\*\\/)\\s*#',
        ), array(
            '$1',
            '$1$2',
            '}',
            '$1$3',
            '$1.$3',
            '!0',
            '!1',
            '$1'
        ), $input );
    }
    
    /**
     * Minify CSS
     *
     * @see https://gist.github.com/tovic/d7b310dea3b33e4732c0
     *
     * @param string
     *
     * @return string
     */
    public static function minifyCSS( $input )
    {
        if ( trim( $input ) === "" ) {
            return $input;
        }
        // Force white-space(s) in `calc()`
        if ( strpos( $input, 'calc(' ) !== false ) {
            $input = preg_replace_callback( '#(?<=[\\s:])calc\\(\\s*(.*?)\\s*\\)#', function ( $matches ) {
                return 'calc(' . preg_replace( '#\\s+#', "\32", $matches[1] ) . ')';
            }, $input );
        }
        return preg_replace( array(
            // Remove comment(s)
            '#("(?:[^"\\\\]++|\\\\.)*+"|\'(?:[^\'\\\\]++|\\\\.)*+\')|\\/\\*(?!\\!)(?>.*?\\*\\/)|^\\s*|\\s*$#s',
            // Remove unused white-space(s)
            '#("(?:[^"\\\\]++|\\\\.)*+"|\'(?:[^\'\\\\]++|\\\\.)*+\'|\\/\\*(?>.*?\\*\\/))|\\s*+;\\s*+(})\\s*+|\\s*+([*$~^|]?+=|[{};,>~+]|\\s*+-(?![0-9\\.])|!important\\b)\\s*+|([[(:])\\s++|\\s++([])])|\\s++(:)\\s*+(?!(?>[^{}"\']++|"(?:[^"\\\\]++|\\\\.)*+"|\'(?:[^\'\\\\]++|\\\\.)*+\')*+{)|^\\s++|\\s++\\z|(\\s)\\s+#si',
            // Replace `0(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)` with `0`
            '#(?<=[\\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si',
            // Replace `:0 0 0 0` with `:0`
            '#:(0\\s+0|0\\s+0\\s+0\\s+0)(?=[;\\}]|\\!important)#i',
            // Replace `background-position:0` with `background-position:0 0`
            '#(background-position):0(?=[;\\}])#si',
            // Replace `0.6` with `.6`, but only when preceded by a white-space or `=`, `:`, `,`, `(`, `-`
            '#(?<=[\\s=:,\\(\\-]|&\\#32;)0+\\.(\\d+)#s',
            // Minify string value
            '#(\\/\\*(?>.*?\\*\\/))|(?<!content\\:)([\'"])([a-z_][-\\w]*?)\\2(?=[\\s\\{\\}\\];,])#si',
            '#(\\/\\*(?>.*?\\*\\/))|(\\burl\\()([\'"])([^\\s]+?)\\3(\\))#si',
            // Minify HEX color code
            '#(?<=[\\s=:,\\(]\\#)([a-f0-6]+)\\1([a-f0-6]+)\\2([a-f0-6]+)\\3#i',
            // Replace `(border|outline):none` with `(border|outline):0`
            '#(?<=[\\{;])(border|outline):none(?=[;\\}\\!])#',
            // Remove empty selector(s)
            '#(\\/\\*(?>.*?\\*\\/))|(^|[\\{\\}])(?:[^\\s\\{\\}]+)\\{\\}#s',
            '#\\x1A#',
        ), array(
            '$1',
            '$1$2$3$4$5$6$7',
            '$1',
            ':0',
            '$1:0 0',
            '.$1',
            '$1$3',
            '$1$2$4$5',
            '$1$2$3',
            '$1:0',
            '$1$2',
            ' '
        ), $input );
    }
    
    /**
     * Compare WooCommerce function
     *
     * @param $version
     * @param $op
     *
     * @return bool
     */
    public static function compareWcVersion( $version, $op )
    {
        if ( function_exists( 'WC' ) && version_compare( WC()->version, $version, $op ) ) {
            return true;
        }
        return false;
    }
    
    /**
     * Check if is settings page
     * @return bool
     */
    public static function isSettingsPage()
    {
        if ( is_admin() && !empty($_GET['page']) && $_GET['page'] === 'dgwt_wcas_settings' ) {
            return true;
        }
        return false;
    }
    
    /**
     * Check if is debug page
     * @return bool
     */
    public static function isDebugPage()
    {
        if ( is_admin() && !empty($_GET['page']) && $_GET['page'] === 'dgwt_wcas_debug' ) {
            return true;
        }
        return false;
    }
    
    /**
     * Check if is Freemius checkout page
     * @return bool
     */
    public static function isCheckoutPage()
    {
        if ( is_admin() && !empty($_GET['page']) && $_GET['page'] === 'dgwt_wcas_settings-pricing' ) {
            return true;
        }
        return false;
    }
    
    /**
     * Get settings URL
     *
     * @return string
     */
    public static function getSettingsUrl()
    {
        return admin_url( 'admin.php?page=dgwt_wcas_settings' );
    }
    
    /**
     * Get total products
     *
     * @return int
     */
    public static function getTotalProducts()
    {
        global  $wpdb ;
        $sql = "SELECT COUNT(ID) FROM {$wpdb->posts} WHERE  post_type = 'product' AND post_status = 'publish'";
        $total = $wpdb->get_var( $sql );
        return absint( $total );
    }
    
    /**
     * Get all products IDs
     * @return array
     */
    public static function getProductsForIndex()
    {
        global  $wpdb ;
        $sql = "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'product' AND post_status = 'publish' ORDER BY ID ASC";
        $ids = $wpdb->get_col( $sql );
        if ( !is_array( $ids ) || empty($ids[0]) || !is_numeric( $ids[0] ) ) {
            $ids = array();
        }
        return $ids;
    }
    
    /**
     * Get readable format of memory
     *
     * @param int $bytes
     *
     * @return string
     */
    public static function getReadableMemorySize( $bytes )
    {
        $unit = array(
            'b',
            'kb',
            'mb',
            'gb',
            'tb',
            'pb'
        );
        return @round( $bytes / pow( 1024, $i = floor( log( $bytes, 1024 ) ) ), 2 ) . ' ' . $unit[$i];
    }
    
    /**
     * Get pro icon/label
     *
     * @param string $label
     * @param string $type
     * @param string $headerSubtitle
     *
     * @return string
     */
    public static function getSettingsProLabel( $label, $type = 'header', $headerSubtitle = '' )
    {
        $html = '';
        switch ( $type ) {
            case 'header':
                if ( !empty($headerSubtitle) ) {
                    $label = '<span class="dgwt-wcas-pro-header__subtitle">' . $label . '</span><span class="dgwt-wcas-pro-header__subtitle--text">' . $headerSubtitle . '</span>';
                }
                $html .= '<div class="dgwt-wcas-row dgwt-wcas-pro-header"><span class="dgwt-wcas-pro-label">' . $label . '</span><span class="dgwt-wcas-pro-suffix">' . __( 'Pro', 'ajax-search-for-woocommerce' ) . '</span></div>';
                break;
            case 'option-label':
                $html .= '<div class="dgwt-wcas-row dgwt-wcas-pro-field"><span class="dgwt-wcas-pro-label">' . $label . '</span><span class="dgwt-wcas-pro-suffix">' . __( 'Pro', 'ajax-search-for-woocommerce' ) . '</span></div>';
                break;
        }
        return $html;
    }
    
    /**
     * Calc score for searched
     *
     * @param string $searched
     * @param string $string eg. product title
     * @param array $args
     *
     * @return int
     */
    public static function calcScore( $searched, $string, $args = array() )
    {
        $score = 0;
        if ( empty($searched) || empty($string) ) {
            return $score;
        }
        $default = array(
            'check_similarity' => true,
            'check_position'   => true,
            'score_containing' => 50,
        );
        $args = array_merge( $default, $args );
        $searched = mb_strtolower( $searched );
        $string = mb_strtolower( $string );
        
        if ( $args['check_similarity'] ) {
            $m = similar_text( $searched, $string, $percent );
            $score = ($score + $percent) / 3;
        }
        
        $pos = strpos( $string, $searched );
        // Add score based on substring position
        
        if ( $pos !== false ) {
            $score += $args['score_containing'];
            // Bonus for contained substring
            // Bonus for substring position
            
            if ( $args['check_position'] ) {
                $posBonus = (100 - $pos * 100 / strlen( $string )) / 2;
                $score += $posBonus;
            }
            
            // Bonus for exact match
            if ( $string === $searched ) {
                $score += $args['score_containing'] * 5;
            }
        }
        
        return $score;
    }
    
    /**
     * Sorting by score
     *
     * @param $a
     * @param $b
     *
     * @return int
     */
    public static function cmpSimilarity( $a, $b )
    {
        $scoreA = 0;
        $scoreB = 0;
        
        if ( is_object( $a ) ) {
            $scoreA = $a->score;
            $scoreB = $b->score;
        }
        
        
        if ( is_array( $a ) ) {
            $scoreA = $a['score'];
            $scoreB = $b['score'];
        }
        
        if ( $scoreA == $scoreB ) {
            return 0;
        }
        return ( $scoreA < $scoreB ? 1 : -1 );
    }
    
    /**
     * Sorting by search resutls groups priority
     *
     * @param $a
     * @param $b
     *
     * @return int
     */
    public static function sortAjaxResutlsGroups( $a, $b )
    {
        if ( $a['order'] == $b['order'] ) {
            return 0;
        }
        return ( $a['order'] < $b['order'] ? -1 : 1 );
    }
    
    /**
     * Sort from the longest to the shortest
     *
     * @param $a
     * @param $b
     *
     * @return int
     */
    public static function sortFromLongest( $a, $b )
    {
        $la = mb_strlen( $a );
        $lb = mb_strlen( $b );
        if ( $la == $lb ) {
            return strcmp( $b, $a );
        }
        return $lb - $la;
    }
    
    /**
     * Get taxonomy parents
     *
     * @param int $term_id
     * @param string $taxonomy
     *
     * @return string
     */
    public static function getTermBreadcrumbs(
        $termID,
        $taxonomy,
        $visited = array(),
        $lang = '',
        $exclude = array()
    )
    {
        $chain = '';
        $separator = ' > ';
        
        if ( Multilingual::isMultilingual() ) {
            $parent = Multilingual::getTerm( $termID, $taxonomy, $lang );
        } else {
            $parent = get_term( $termID, $taxonomy );
        }
        
        if ( empty($parent) || !isset( $parent->name ) ) {
            return '';
        }
        $name = $parent->name;
        
        if ( $parent->parent && $parent->parent != $parent->term_id && !in_array( $parent->parent, $visited ) ) {
            $visited[] = $parent->parent;
            $chain .= self::getTermBreadcrumbs(
                $parent->parent,
                $taxonomy,
                $visited,
                $lang
            );
        }
        
        if ( !in_array( $parent->term_id, $exclude ) ) {
            $chain .= $name . $separator;
        }
        return $chain;
    }
    
    /**
     * Get taxonomies of products attributes
     *
     * @return array
     *
     */
    public static function getAttributesTaxonomies()
    {
        $taxonomies = array();
        $attributeTaxonomies = wc_get_attribute_taxonomies();
        if ( !empty($attributeTaxonomies) ) {
            foreach ( $attributeTaxonomies as $taxonomy ) {
                $taxonomies[] = 'pa_' . $taxonomy->attribute_name;
            }
        }
        return apply_filters( 'dgwt/wcas/attribute_taxonomies', $taxonomies );
    }
    
    /**
     *
     */
    public static function canInstallPremium()
    {
    }
    
    /**
     * Get indexer demo HTML
     *
     * @return string
     */
    public static function indexerDemoHtml()
    {
        $html = '';
        ob_start();
        include DGWT_WCAS_DIR . 'partials/admin/indexer-header-demo.php';
        $html .= ob_get_clean();
        return $html;
    }
    
    /**
     * Get features HTML
     *
     * @return string
     */
    public static function featuresHtml()
    {
        $html = '';
        ob_start();
        include DGWT_WCAS_DIR . 'partials/admin/features.php';
        $html .= ob_get_clean();
        return $html;
    }
    
    /**
     * Get searchable custom fields keys
     *
     * @return array
     */
    public static function getSearchableCustomFields()
    {
        global  $wpdb ;
        $customFields = array();
        $excludedMetaKeys = array(
            '_sku',
            '_wp_old_date',
            '_tax_status',
            '_stock_status',
            '_product_version',
            '_smooth_slider_style',
            'auctioninc_calc_method',
            'auctioninc_pack_method',
            '_thumbnail_id',
            '_product_image_gallery',
            'pdf_download',
            'slide_template',
            'cad_iframe',
            'downloads',
            'edrawings_file',
            '3d_pdf_download',
            '3d_pdf_render',
            '_original_id'
        );
        $excludedMetaKeys = apply_filters( 'dgwt/wcas/indexer/excluded_meta_keys', $excludedMetaKeys );
        $sql = "SELECT DISTINCT meta_key\n                FROM {$wpdb->postmeta} as pm\n                INNER JOIN {$wpdb->posts} as p ON p.ID = pm.post_id\n                WHERE p.post_type = 'product'\n                AND pm.meta_value NOT LIKE 'field_%'\n                AND pm.meta_value NOT LIKE 'a:%'\n                AND pm.meta_value NOT LIKE '%\\%\\%%'\n                AND pm.meta_value NOT LIKE '_oembed_%'\n                AND pm.meta_value NOT REGEXP '^1[0-9]{9}'\n                AND pm.meta_value NOT IN ('1','0','-1','no','yes','[]', '')\n               ";
        $metaKeys = $wpdb->get_col( $sql );
        if ( !empty($metaKeys) ) {
            foreach ( $metaKeys as $metaKey ) {
                
                if ( !in_array( $metaKey, $excludedMetaKeys ) && self::keyIsValid( $metaKey ) ) {
                    $label = $metaKey;
                    //@TODO Recognize labels based on meta key or public known as Yoast SEO etc.
                    $customFields[] = array(
                        'label' => $label,
                        'key'   => $label,
                    );
                }
            
            }
        }
        $customFields = array_reverse( $customFields );
        return apply_filters( 'dgwt/wcas/indexer/searchable_custom_fields', $customFields );
    }
    
    /**
     * Check if key is valid
     *
     * @param $key
     *
     * @return bool
     */
    public static function keyIsValid( $key )
    {
        return !preg_match( '/[^\\p{L}\\p{N}\\:\\.\\_\\s\\-]+/u', $key );
    }
    
    /**
     * Check if table exist
     *
     * @return bool
     */
    public static function isTableExists( $tableName )
    {
        global  $wpdb ;
        $exist = false;
        $wpdb->hide_errors();
        if ( empty($tableName) ) {
            return false;
        }
        $sql = $wpdb->prepare( "SHOW TABLES LIKE %s", $tableName );
        if ( !empty($wpdb->get_var( $sql )) ) {
            $exist = true;
        }
        return $exist;
    }
    
    /**
     * Check if the engine can search in variable products
     *
     * @return bool
     */
    public static function canSearchInVariableProducts()
    {
        global  $wpdb ;
        $allow = false;
        $el = $wpdb->get_var( "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'product_variation' LIMIT 1" );
        if ( !empty($el) && is_numeric( $el ) ) {
            $allow = true;
        }
        if ( DGWT_WCAS()->settings->getOption( 'search_in_product_content' ) !== 'on' ) {
            $allow = false;
        }
        return apply_filters( 'dgwt/wcas/search_in_variable_products', $allow );
    }
    
    /**
     * Allow to remove method for an hook when, it's a class method used and class don't have variable, but you know the class name
     *
     * @link https://github.com/herewithme/wp-filters-extras
     * @return bool
     */
    public static function removeFiltersForAnonymousClass(
        $hook_name = '',
        $class_name = '',
        $method_name = '',
        $priority = 0
    )
    {
        global  $wp_filter ;
        // Take only filters on right hook name and priority
        if ( !isset( $wp_filter[$hook_name][$priority] ) || !is_array( $wp_filter[$hook_name][$priority] ) ) {
            return false;
        }
        // Loop on filters registered
        foreach ( (array) $wp_filter[$hook_name][$priority] as $unique_id => $filter_array ) {
            // Test if filter is an array ! (always for class/method)
            if ( isset( $filter_array['function'] ) && is_array( $filter_array['function'] ) ) {
                // Test if object is a class, class and method is equal to param !
                if ( is_object( $filter_array['function'][0] ) && get_class( $filter_array['function'][0] ) && get_class( $filter_array['function'][0] ) == $class_name && $filter_array['function'][1] == $method_name ) {
                    // Test for WordPress >= 4.7 WP_Hook class (https://make.wordpress.org/core/2016/09/08/wp_hook-next-generation-actions-and-filters/)
                    
                    if ( is_a( $wp_filter[$hook_name], 'WP_Hook' ) ) {
                        unset( $wp_filter[$hook_name]->callbacks[$priority][$unique_id] );
                    } else {
                        unset( $wp_filter[$hook_name][$priority][$unique_id] );
                    }
                
                }
            }
        }
        return false;
    }
    
    /**
     * Create tooltip
     *
     * @param string $id
     * @param string $content
     * @param string $template
     * @param string $placement
     * @param string $class
     *
     * @return string
     */
    public static function createTooltip(
        $id,
        $content = '',
        $template = '',
        $placement = 'right',
        $class = ''
    )
    {
        
        if ( !empty($template) ) {
            $file = DGWT_WCAS_DIR . 'partials/admin/tooltips/' . $template . '.php';
            
            if ( file_exists( $file ) ) {
                ob_start();
                require $file;
                $content = ob_get_contents();
                ob_end_clean();
            }
        
        }
        
        $id = 'js-dgwt-wcas-tooltip-id' . sanitize_key( $id );
        $html = '<div class="js-dgwt-wcas-tooltip ' . $class . '" data-tooltip-html-el="' . $id . '" data-tooltip-placement="' . $placement . '"></div>';
        $html .= '<div class="' . $id . '" style="display:none;"><div class="dgwt-wcas-tooltip-wrapper">' . $content . '</div></div>';
        return $html;
    }
    
    /**
     * Create HTML override option tooltip
     *
     * @param string $id
     * @param string $content
     * @param string $template
     * @param string $placement
     *
     * @return string
     */
    public static function getOverrideOptionText( $theme )
    {
        $linkToShortcodesDoc = 'https://fibosearch.com/documentation/get-started/how-to-add-fibosearch-to-your-website/#add-fibosearch-with-a-shortcode';
        $content = '<p>' . sprintf( __( 'This option is <b>overridden</b> by the seamless integration with the %s theme. If you want to change the value of this option, disable the integration in <br /><b>WooCommerce -> FiboSearch -> Starting (tab)</b>.', 'ajax-search-for-woocommerce' ), $theme ) . '</p>';
        $content .= '<p>' . sprintf( __( 'Furthermore, you can override this option for a specific search bar via shortcode params. <a href="%s" target="_blank">Learn more about shortcodes parameters</a>.', 'ajax-search-for-woocommerce' ), $linkToShortcodesDoc ) . '</p>';
        return $content;
    }
    
    /**
     * Create HTML question mark with tooltip
     *
     * @param string $id
     * @param string $content
     * @param string $template
     * @param string $placement
     *
     * @return string
     */
    public static function createQuestionMark(
        $id,
        $content = '',
        $template = '',
        $placement = 'right'
    )
    {
        return self::createTooltip(
            $id,
            $content,
            $template,
            $placement,
            'dashicons dashicons-editor-help dgwt-wcas-questio-mark'
        );
    }
    
    /**
     * Create HTML option override tooltip
     *
     * @param string $id
     * @param string $content
     * @param string $template
     * @param string $placement
     *
     * @return string
     */
    public static function createOverrideTooltip(
        $id,
        $content = '',
        $template = '',
        $placement = 'right'
    )
    {
        return self::createTooltip(
            $id,
            $content,
            $template,
            $placement,
            'dashicons dashicons-lock dgwt-wcas-override-tooltip'
        );
    }
    
    /**
     * Get list of 24 hours
     */
    public static function getHours()
    {
        $hours = array();
        $cycle12 = ( get_option( 'time_format' ) === 'H:i' ? false : true );
        for ( $i = 0 ;  $i < 24 ;  $i++ ) {
            $label = ( $cycle12 ? $i . ':00 am' : $i . ':00' );
            if ( $cycle12 && $i === 0 ) {
                $label = 12 . ':00 am';
            }
            if ( $cycle12 && $i > 11 ) {
                
                if ( $i === 12 ) {
                    $label = 12 . ':00 pm';
                } else {
                    $label = $i - 12 . ':00 pm';
                }
            
            }
            $hours[$i] = $label;
        }
        return $hours;
    }
    
    /**
     * Get local date including timezone
     *
     * @param $timestamp
     * @param string $format
     *
     * @return string
     * @throws \Exception
     */
    public static function localDate( $timestamp, $format = '' )
    {
        if ( empty($timestamp) ) {
            return '';
        }
        if ( empty($format) ) {
            $format = get_option( 'date_format' ) . ' ' . get_option( 'time_format' );
        }
        $date = new \WC_DateTime( "@{$timestamp}" );
        $date->setTimezone( new \DateTimeZone( wc_timezone_string() ) );
        return $date->date_i18n( $format );
    }
    
    /**
     * Get labels
     *
     * @return array
     */
    public static function getLabels()
    {
        return apply_filters( 'dgwt/wcas/labels', array(
            'post'               => __( 'Post' ),
            'page'               => __( 'Page' ),
            'vendor'             => __( 'Vendor', 'ajax-search-for-woocommerce' ),
            'product_plu'        => __( 'Products', 'woocommerce' ),
            'post_plu'           => __( 'Posts' ),
            'page_plu'           => __( 'Pages' ),
            'vendor_plu'         => __( 'Vendors', 'ajax-search-for-woocommerce' ),
            'sku_label'          => __( 'SKU', 'woocommerce' ) . ':',
            'sale_badge'         => __( 'Sale', 'woocommerce' ),
            'vendor_sold_by'     => __( 'Sold by:', 'ajax-search-for-woocommerce' ),
            'featured_badge'     => __( 'Featured', 'woocommerce' ),
            'in'                 => _x( 'in', 'in categories fe. in Books > Crime stories', 'ajax-search-for-woocommerce' ),
            'read_more'          => __( 'continue reading', 'ajax-search-for-woocommerce' ),
            'no_results'         => DGWT_WCAS()->settings->getOption( 'search_no_results_text', __( 'No results', 'ajax-search-for-woocommerce' ) ),
            'show_more'          => DGWT_WCAS()->settings->getOption( 'search_see_all_results_text', __( 'See all products...', 'ajax-search-for-woocommerce' ) ),
            'show_more_details'  => DGWT_WCAS()->settings->getOption( 'search_see_all_results_text', __( 'See all products...', 'ajax-search-for-woocommerce' ) ),
            'search_placeholder' => DGWT_WCAS()->settings->getOption( 'search_placeholder', __( 'Search for products...', 'ajax-search-for-woocommerce' ) ),
            'submit'             => DGWT_WCAS()->settings->getOption( 'search_submit_text', '' ),
        ) );
    }
    
    /**
     * Get labels
     *
     * @param string $key
     *
     * @return string
     */
    public static function getLabel( $key )
    {
        $label = '';
        $labels = self::getLabels();
        if ( array_key_exists( $key, $labels ) ) {
            $label = $labels[$key];
        }
        return $label;
    }
    
    /**
     * Remove all HTML tags including <script> and <style> and shortcodes
     *
     * @param string $string
     * @param string $allowed
     * @param bool $removeBreaks
     *
     * @return string
     */
    public static function stripAllTags( $string, $allowableTags = '', $removeBreaks = false )
    {
        $string = strip_shortcodes( $string );
        $string = preg_replace( '@<(script|style)[^>]*?>.*?</\\1>@si', '', $string );
        $string = strip_tags( $string, $allowableTags );
        if ( $removeBreaks ) {
            $string = preg_replace( '/[\\r\\n\\t ]+/', ' ', $string );
        }
        return trim( $string );
    }
    
    /**
     * Repair HTML. Close unclosed tags.
     *
     * @param $html
     *
     * @return string
     */
    public static function closeTags( $html )
    {
        preg_match_all( '#<(?!meta|img|br|hr|input\\b)\\b([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result );
        $openedTags = $result[1];
        preg_match_all( '#</([a-z]+)>#iU', $html, $result );
        $closedtags = $result[1];
        $lenOpened = count( $openedTags );
        if ( count( $closedtags ) == $lenOpened ) {
            return $html;
        }
        $openedTags = array_reverse( $openedTags );
        for ( $i = 0 ;  $i < $lenOpened ;  $i++ ) {
            
            if ( !in_array( $openedTags[$i], $closedtags ) ) {
                $html .= '</' . $openedTags[$i] . '>';
            } else {
                unset( $closedtags[array_search( $openedTags[$i], $closedtags )] );
            }
        
        }
        return $html;
    }
    
    /**
     * Check if we should override default search query
     *
     * @param \WP_Query $query The WP_Query instance
     *
     * @return bool
     */
    public static function isSearchQuery( $query )
    {
        $enabled = true;
        if ( !(!empty($query) && is_object( $query ) && is_a( $query, 'WP_Query' )) ) {
            return false;
        }
        if ( !$query->is_main_query() || isset( $query->query_vars['s'] ) && !isset( $_GET['dgwt_wcas'] ) || !isset( $query->query_vars['s'] ) || !$query->is_search() || $query->get( 'post_type' ) && is_string( $query->get( 'post_type' ) ) && $query->get( 'post_type' ) !== 'product' ) {
            $enabled = false;
        }
        $enabled = apply_filters( 'dgwt/wcas/helpers/is_search_query', $enabled, $query );
        return $enabled;
    }
    
    /**
     * Check if this is a product search page
     *
     * @return bool
     */
    public static function isProductSearchPage()
    {
        if ( isset( $_GET['dgwt_wcas'] ) && isset( $_GET['post_type'] ) && $_GET['post_type'] === 'product' && isset( $_GET['s'] ) ) {
            return true;
        }
        return false;
    }
    
    /**
     * Check if this is a remote search request
     *
     * @return bool
     */
    public static function isRemoteSearchRequest()
    {
        if ( isset( $_GET['wc-ajax'] ) && $_GET['wc-ajax'] === DGWT_WCAS_SEARCH_ACTION && !empty($_GET['remote']) && isset( $_GET['s'] ) ) {
            return true;
        }
        return false;
    }
    
    /**
     * Restore the search phrase so that it can be used in the template.
     *
     * @param \WP_Post[] $posts Array of post objects.
     * @param \WP_Query $query The WP_Query instance (passed by reference).
     *
     * @return mixed
     */
    public static function rollbackSearchPhrase( $posts, $query )
    {
        if ( !$query->get( 'dgwt_wcas', false ) ) {
            return $posts;
        }
        $query->set( 's', wp_unslash( $query->get( 'dgwt_wcas', '' ) ) );
        return $posts;
    }
    
    /**
     * Clear default search query if our engine is active
     *
     * @param string $search Search SQL for WHERE clause.
     * @param \WP_Query $query The current WP_Query object.
     *
     * @return string
     */
    public static function clearSearchQuery( $search, $query )
    {
        if ( !$query->get( 'dgwt_wcas', false ) ) {
            return $search;
        }
        return '';
    }
    
    /**
     * Get formatted search layout settings
     *
     * @return object
     */
    public static function getLayoutSettings()
    {
        $layoutBreakpoint = DGWT_WCAS()->settings->getOption( 'mobile_breakpoint', 992 );
        $layoutBreakpoint = apply_filters( 'dgwt/wcas/scripts/mobile_breakpoint', $layoutBreakpoint );
        // deprecated
        $mobileOverlayBreakpoint = DGWT_WCAS()->settings->getOption( 'mobile_overlay_breakpoint', 992 );
        $layout = array(
            'style'                     => DGWT_WCAS()->settings->getOption( 'search_style', 'solaris' ),
            'icon'                      => 'magnifier-thin',
            'layout'                    => DGWT_WCAS()->settings->getOption( 'search_layout', 'classic' ),
            'layout_breakpoint'         => apply_filters( 'dgwt/wcas/scripts/layout_breakpoint', $layoutBreakpoint ),
            'mobile_overlay'            => ( DGWT_WCAS()->settings->getOption( 'enable_mobile_overlay' ) === 'on' ? true : false ),
            'mobile_overlay_breakpoint' => apply_filters( 'dgwt/wcas/scripts/mobile_overlay_breakpoint', $mobileOverlayBreakpoint ),
            'mobile_overlay_wrapper'    => apply_filters( 'dgwt/wcas/scripts/mobile_overlay_wrapper', 'body' ),
            'darken_background'         => ( DGWT_WCAS()->settings->getOption( 'darken_background', 'off' ) === 'on' ? true : false ),
        );
        if ( $layout['style'] === 'pirx' ) {
            $layout['icon'] = 'magnifier-pirx';
        }
        return (object) $layout;
    }
    
    /**
     * Get frontend scripts settings
     *
     * @return array
     */
    public static function getScriptsSettings()
    {
        $layout = self::getLayoutSettings();
        // Localize
        $localize = array(
            'labels'                       => self::getLabels(),
            'ajax_search_endpoint'         => self::getEndpointUrl( 'search' ),
            'ajax_details_endpoint'        => self::getEndpointUrl( 'details' ),
            'ajax_prices_endpoint'         => self::getEndpointUrl( 'prices' ),
            'action_search'                => DGWT_WCAS_SEARCH_ACTION,
            'action_result_details'        => DGWT_WCAS_RESULT_DETAILS_ACTION,
            'action_get_prices'            => DGWT_WCAS_GET_PRICES_ACTION,
            'min_chars'                    => 3,
            'width'                        => 'auto',
            'show_details_panel'           => false,
            'show_images'                  => false,
            'show_price'                   => false,
            'show_desc'                    => false,
            'show_sale_badge'              => false,
            'show_featured_badge'          => false,
            'dynamic_prices'               => false,
            'is_rtl'                       => ( is_rtl() == true ? true : false ),
            'show_preloader'               => false,
            'show_headings'                => false,
            'preloader_url'                => '',
            'taxonomy_brands'              => '',
            'img_url'                      => DGWT_WCAS_URL . 'assets/img/',
            'is_premium'                   => dgoraAsfwFs()->is_premium(),
            'layout_breakpoint'            => $layout->layout_breakpoint,
            'mobile_overlay_breakpoint'    => $layout->mobile_overlay_breakpoint,
            'mobile_overlay_wrapper'       => $layout->mobile_overlay_wrapper,
            'mobile_overlay_delay'         => apply_filters( 'dgwt/wcas/scripts/overlay_delay_ms', 0 ),
            'debounce_wait_ms'             => apply_filters( 'dgwt/wcas/scripts/debounce_wait_ms', 400 ),
            'send_ga_events'               => apply_filters( 'dgwt/wcas/scripts/send_ga_events', true ),
            'enable_ga_site_search_module' => apply_filters( 'dgwt/wcas/scripts/enable_ga_site_search_module', false ),
            'magnifier_icon'               => self::getMagnifierIco( '' ),
            'close_icon'                   => self::getIcon( 'close' ),
            'back_icon'                    => self::getIcon( 'arrow-left' ),
            'preloader_icon'               => self::getIcon( 'preloader' ),
            'voice_search_inactive_icon'   => self::getIcon( ( $layout->style === 'pirx' ? 'voice-search-inactive-pirx' : 'voice-search-inactive' ), 'dgwt-wcas-voice-search-mic-inactive' ),
            'voice_search_active_icon'     => self::getIcon( ( $layout->style === 'pirx' ? 'voice-search-active-pirx' : 'voice-search-active' ), 'dgwt-wcas-voice-search-mic-active' ),
            'voice_search_disabled_icon'   => self::getIcon( ( $layout->style === 'pirx' ? 'voice-search-disabled-pirx' : 'voice-search-disabled' ), 'dgwt-wcas-voice-search-mic-disabled' ),
            'custom_params'                => (object) apply_filters( 'dgwt/wcas/scripts/custom_params', array() ),
            'convert_html'                 => true,
            'suggestions_wrapper'          => apply_filters( 'dgwt/wcas/scripts/suggestions_wrapper', 'body' ),
            'show_product_vendor'          => dgoraAsfwFs()->is_premium() && class_exists( 'DgoraWcas\\Integrations\\Marketplace\\Marketplace' ) && DGWT_WCAS()->marketplace->showProductVendor(),
            'disable_hits'                 => apply_filters( 'dgwt/wcas/scripts/disable_hits', false ),
            'disable_submit'               => apply_filters( 'dgwt/wcas/scripts/disable_submit', false ),
            'fixer'                        => apply_filters( 'dgwt/wcas/scripts/fixer', array(
            'broken_search_ui'                  => true,
            'broken_search_ui_ajax'             => true,
            'broken_search_ui_hard'             => false,
            'broken_search_elementor_popups'    => true,
            'broken_search_browsers_back_arrow' => true,
            'force_refresh_checkout'            => true,
        ) ),
            'voice_search_enabled'         => defined( 'DGWT_WCAS_VOICE_SEARCH_ENABLE' ) && DGWT_WCAS_VOICE_SEARCH_ENABLE,
            'voice_search_lang'            => apply_filters( 'dgwt/wcas/scripts/voice_search_lang', get_bloginfo( 'language' ) ),
        );
        if ( Multilingual::isMultilingual() ) {
            $localize['current_lang'] = Multilingual::getCurrentLanguage();
        }
        // Min characters
        $min_chars = DGWT_WCAS()->settings->getOption( 'min_chars' );
        if ( !empty($min_chars) && is_numeric( $min_chars ) ) {
            $localize['min_chars'] = absint( $min_chars );
        }
        $sug_width = DGWT_WCAS()->settings->getOption( 'sug_width' );
        if ( !empty($sug_width) && is_numeric( $sug_width ) && $sug_width > 100 ) {
            $localize['sug_width'] = absint( $sug_width );
        }
        // Show/hide Details panel
        if ( DGWT_WCAS()->settings->getOption( 'show_details_box' ) === 'on' ) {
            $localize['show_details_panel'] = true;
        }
        // Show/hide images
        if ( DGWT_WCAS()->settings->getOption( 'show_product_image' ) === 'on' ) {
            $localize['show_images'] = true;
        }
        // Show/hide price
        if ( DGWT_WCAS()->settings->getOption( 'show_product_price' ) === 'on' ) {
            $localize['show_price'] = true;
        }
        // Show/hide description
        if ( DGWT_WCAS()->settings->getOption( 'show_product_desc' ) === 'on' ) {
            $localize['show_desc'] = true;
        }
        // Show/hide description
        if ( DGWT_WCAS()->settings->getOption( 'show_product_sku' ) === 'on' ) {
            $localize['show_sku'] = true;
        }
        // Show/hide sale badge
        if ( DGWT_WCAS()->settings->getOption( 'show_sale_badge' ) === 'on' ) {
            $localize['show_sale_badge'] = true;
        }
        // Show/hide featured badge
        if ( DGWT_WCAS()->settings->getOption( 'show_featured_badge' ) === 'on' ) {
            $localize['show_featured_badge'] = true;
        }
        // Set preloader
        
        if ( DGWT_WCAS()->settings->getOption( 'show_preloader' ) === 'on' ) {
            $localize['show_preloader'] = true;
            $localize['preloader_url'] = esc_url( trim( DGWT_WCAS()->settings->getOption( 'preloader_url' ) ) );
        }
        
        // Show/hide autocomplete headings
        if ( DGWT_WCAS()->settings->getOption( 'show_grouped_results' ) === 'on' ) {
            $localize['show_headings'] = true;
        }
        return apply_filters( 'dgwt/wcas/scripts/localize', $localize );
    }
    
    /**
     * Get endpoint URL
     *
     * @param string $type
     *
     * @return string
     */
    public static function getEndpointUrl( $type = '' )
    {
        $url = '';
        if ( !in_array( $type, array( 'search', 'details', 'prices' ) ) ) {
            return $url;
        }
        switch ( $type ) {
            case 'search':
                $url = \WC_AJAX::get_endpoint( DGWT_WCAS_SEARCH_ACTION );
                break;
            case 'details':
                $url = \WC_AJAX::get_endpoint( DGWT_WCAS_RESULT_DETAILS_ACTION );
                break;
            case 'prices':
                $url = \WC_AJAX::get_endpoint( DGWT_WCAS_GET_PRICES_ACTION );
                break;
            default:
                break;
        }
        return apply_filters( "dgwt/wcas/endpoint/{$type}", $url );
    }
    
    /**
     * Checking the current code is run by the object of the given class
     *
     * @param string $class_name Class name
     * @param int $backtrace_limit The number of stack frames that is tested backwards.
     *
     * @return bool
     */
    public static function is_running_inside_class( $class_name, $backtrace_limit = 10 )
    {
        if ( empty($class_name) ) {
            return false;
        }
        if ( intval( $backtrace_limit ) <= 0 ) {
            $backtrace_limit = 10;
        }
        $result = false;
        $backtrace = self::debugBacktrace( 0, $backtrace_limit );
        if ( !empty($backtrace) ) {
            foreach ( $backtrace as $item ) {
                
                if ( isset( $item['class'] ) && $item['class'] === $class_name ) {
                    $result = true;
                    break;
                }
            
            }
        }
        return $result;
    }
    
    private static function debugBacktrace( $options, $limit )
    {
        return debug_backtrace( $options, $limit );
    }
    
    /**
     * Search products with native engine
     *
     * @param $phrase
     *
     * @return int[]
     */
    public static function searchProducts( $phrase )
    {
        $postIn = array();
        $urlPhrase = str_replace( "\\'", "'", $phrase );
        $urlPhrase = str_replace( '\\"', '"', $urlPhrase );
        $args = array(
            's'      => urlencode( $urlPhrase ),
            'remote' => 1,
        );
        if ( Multilingual::isMultilingual() ) {
            $args['l'] = Multilingual::getCurrentLanguage();
        }
        $url = add_query_arg( $args, Helpers::getAjaxSearchEndpointUrl() );
        $r = wp_remote_retrieve_body( wp_remote_get( $url, array(
            'timeout' => 120,
        ) ) );
        $decR = json_decode( $r );
        if ( json_last_error() == JSON_ERROR_NONE ) {
            if ( is_object( $decR ) && property_exists( $decR, 'suggestions' ) && is_array( $decR->suggestions ) ) {
                $postIn = wp_list_pluck( $decR->suggestions, 'ID' );
            }
        }
        return $postIn;
    }
    
    /**
     * Get all post types used in search
     *
     * @param string $filter 'no-products' returns post types not related to products
     *                       'only-products' returns post types related to products
     *
     * @return array
     */
    public static function getAllowedPostTypes( $filter = '' )
    {
        $types = array();
        
        if ( $filter !== 'no-products' ) {
            $types[] = 'product';
            $types[] = 'product-variation';
        }
        
        
        if ( $filter !== 'only-products' ) {
            if ( DGWT_WCAS()->settings->getOption( 'show_matching_posts' ) === 'on' ) {
                $types[] = 'post';
            }
            if ( DGWT_WCAS()->settings->getOption( 'show_matching_pages' ) === 'on' ) {
                $types[] = 'page';
            }
        }
        
        return apply_filters( 'dgwt/wcas/allowed_post_types', $types, $filter );
    }
    
    /**
     * Get Basic Auth header from dedicated constants or from current request
     *
     * @return string
     */
    public static function getBasicAuthHeader()
    {
        $authorization = '';
        
        if ( defined( 'DGWT_WCAS_BA_USERNAME' ) && defined( 'DGWT_WCAS_BA_PASSWORD' ) ) {
            $authorization = 'Basic ' . base64_encode( wp_unslash( DGWT_WCAS_BA_USERNAME ) . ':' . wp_unslash( DGWT_WCAS_BA_PASSWORD ) );
        } elseif ( isset( $_SERVER['PHP_AUTH_USER'] ) && isset( $_SERVER['PHP_AUTH_PW'] ) ) {
            $authorization = 'Basic ' . base64_encode( wp_unslash( $_SERVER['PHP_AUTH_USER'] ) . ':' . wp_unslash( $_SERVER['PHP_AUTH_PW'] ) );
        }
        
        return $authorization;
    }
    
    /**
     * Get plugin version
     *
     * @return string
     */
    public static function getPluginVersion()
    {
        global  $wpdb ;
        $version = $wpdb->get_var( "SELECT option_value FROM {$wpdb->options} WHERE option_name = 'dgwt_wcas_version_pro'" );
        return ( empty($version) ? '' : $version );
    }
    
    /**
     * Get AJAX search endpoint URL
     *
     * @param null $scheme
     *
     * @return string
     *
     * @see \WC_AJAX::get_endpoint() - Almost the same, but you can choose a scheme
     */
    public static function getAjaxSearchEndpointUrl( $scheme = null )
    {
        return esc_url_raw( apply_filters( 'woocommerce_ajax_get_endpoint', add_query_arg( 'wc-ajax', DGWT_WCAS_SEARCH_ACTION, remove_query_arg( array(
            'remove_item',
            'add-to-cart',
            'added-to-cart',
            'order_again',
            '_wpnonce'
        ), home_url( '/', $scheme ) ) ), DGWT_WCAS_SEARCH_ACTION ) );
    }
    
    /**
     * Check that the AMP version of the page is displayed
     *
     * @return bool
     */
    public static function isAMPEndpoint()
    {
        return function_exists( 'is_amp_endpoint' ) && is_amp_endpoint();
    }
    
    /**
     * Get the path to the fibo directory in the theme or child theme
     *
     * @param string $path
     * @param array $vars
     *
     * @return void
     */
    public static function loadTemplate( $template = '', $vars = array() )
    {
        $path = '';
        // Load default partials from the plugin
        $file = DGWT_WCAS_DIR . 'partials/' . $template;
        if ( file_exists( $file ) ) {
            $path = $file;
        }
        // Load a partial if it is localized in the child-theme
        $file = get_stylesheet_directory() . '/fibosearch/' . $template;
        if ( file_exists( $file ) ) {
            $path = $file;
        }
        $path = apply_filters(
            'dgwt/wcas/template',
            $path,
            $template,
            $vars
        );
        if ( file_exists( $path ) ) {
            include $path;
        }
    }
    
    /**
     * Add "No results" if suggestions are empty
     *
     * @param array $output
     *
     * @return array
     */
    public static function noResultsSuggestion( $output )
    {
        if ( empty($output['suggestions']) ) {
            $output['suggestions'][] = array(
                'value' => '',
                'type'  => 'no-results',
            );
        }
        return $output;
    }
    
    /**
     * Get default collate
     *
     * @param string $context
     *
     * @return string
     */
    public static function getCollate( $context = '' )
    {
        global  $wpdb ;
        $sql = '';
        $collate = '';
        $charset = '';
        
        if ( $wpdb->has_cap( 'collation' ) ) {
            if ( !empty($wpdb->charset) ) {
                $charset = $wpdb->charset;
            }
            if ( !empty($wpdb->collate) ) {
                $collate = $wpdb->collate;
            }
        }
        
        $charset = apply_filters( 'dgwt/wcas/db/charset', $charset, $context );
        $collate = apply_filters( 'dgwt/wcas/db/collation', $collate, $context );
        if ( !empty($charset) ) {
            $sql .= " DEFAULT CHARACTER SET " . $charset;
        }
        if ( !empty($collate) ) {
            $sql .= " COLLATE " . $collate;
        }
        return apply_filters( 'dgwt/wcas/db/collation/sql', $sql, $context );
    }
    
    /**
     * Check if string ends with another string
     *
     * @param string $haystack
     * @param string $needle
     *
     * @return bool
     */
    public static function endsWith( $haystack, $needle )
    {
        $length = strlen( $needle );
        return ( $length > 0 ? substr( $haystack, -$length ) === $needle : true );
    }

}