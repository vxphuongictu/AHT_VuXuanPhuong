/**
 * Backend JS
 *
 * @author YITH
 * @package YITH\ZoomMagnifier\Assets\JS
 */

jQuery(function ($) {

  //Settings dependencies
  /**
   * Hide Slider options if the Thumbnails are hidden
   * */
  $(function() {
    if ($('input#ywzm_hide_thumbnails').prop('checked')) {

      $('[data-dep-target="yith_wcmg_slider_items"]').hide();
      $('[data-dep-target="yith_wcmg_slider_style_colors"]').hide();
      $('[data-dep-target="yith_wcmg_slider_style_colors_hover"]').hide();
      $('[data-dep-target="yith_wcmg_slider_sizes"]').hide();
      $('[data-dep-target="yith_wcmg_slider_radius"]').hide();
      $('[data-dep-target="ywzm_slider_arrows_display"]').hide();
      $('[data-dep-target="yith_wcmg_slider_infinite"]').hide();
      $('[data-dep-target="yith_wcmg_slider_infinite_type"]').hide();
      $('[data-dep-target="ywzm_auto_carousel"]').hide();

    }});


  $('input#ywzm_hide_thumbnails').change(function() {

    if ( ! $( this ).hasClass( 'onoffchecked') && $('input#yith_wcmg_enableslider').prop('checked') ){

      $('[data-dep-target="yith_wcmg_slider_items"]').show();
      $('[data-dep-target="yith_wcmg_slider_style_colors"]').show();
      $('[data-dep-target="yith_wcmg_slider_style_colors_hover"]').show();
      $('[data-dep-target="yith_wcmg_slider_sizes"]').show();
      $('[data-dep-target="yith_wcmg_slider_radius"]').show();
      $('[data-dep-target="ywzm_slider_arrows_display"]').show();
      $('[data-dep-target="yith_wcmg_slider_infinite"]').show();
      $('[data-dep-target="yith_wcmg_slider_infinite_type"]').show();
      $('[data-dep-target="ywzm_auto_carousel"]').show();

    }
    else{

      $('[data-dep-target="yith_wcmg_slider_items"]').hide();
      $('[data-dep-target="yith_wcmg_slider_style_colors"]').hide();
      $('[data-dep-target="yith_wcmg_slider_style_colors_hover"]').hide();
      $('[data-dep-target="yith_wcmg_slider_sizes"]').hide();
      $('[data-dep-target="yith_wcmg_slider_radius"]').hide();
      $('[data-dep-target="ywzm_slider_arrows_display"]').hide();
      $('[data-dep-target="yith_wcmg_slider_infinite"]').hide();
      $('[data-dep-target="yith_wcmg_slider_infinite_type"]').hide();
      $('[data-dep-target="ywzm_auto_carousel"]').hide();

    }
  });


  /**
   * Hide Slider type is the slider is not infinite
   * */
  $(function() {
    if ( ! $('input#yith_wcmg_slider_infinite').prop('checked')) {

      $('[data-dep-target="yith_wcmg_slider_infinite_type"]').hide();
      $('[data-dep-target="ywzm_auto_carousel"]').hide();

    }});

  $('input#yith_wcmg_slider_infinite').change(function() {

    if ( ! $( this ).hasClass( 'onoffchecked') && $('input#yith_wcmg_enableslider').prop('checked') ){

      $('[data-dep-target="yith_wcmg_slider_infinite_type"]').hide();
      $('[data-dep-target="ywzm_auto_carousel"]').hide();
    }
    else{
      $('[data-dep-target="yith_wcmg_slider_infinite_type"]').show();
      $('[data-dep-target="ywzm_auto_carousel"]').show();

    }
  });



});
