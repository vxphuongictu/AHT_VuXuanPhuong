<?php

// Exit if accessed directly
if ( !defined( 'DGWT_WCAS_FILE' ) ) {
    exit;
}
$prefix = 'fibosearch-debug_page-';
?>
<div class="wrap dgwt-wcas-settings dgwt-wcas-debug">

	<h2 class="dgwt-wcas-settings__head">
		<div class="dgwt-wcas-settings__title">
			<div class="dgwt-wcas-settings__title-top">
				<div class="dgwt-wcas-settings-logo-wrapper">
					<img class="dgwt-wcas-settings-logo" src="<?php 
echo  DGWT_WCAS_URL . 'assets/img/logo-30.png' ;
?>"/>
					<span class="dgwt-wcas-settings-logo-pro">Pro</span>
				</div>
				<span class="dgwt-wcas-settings__title-core"><?php 
_e( 'Debug page', 'ajax-search-for-woocommerce' );
?></span>
			</div>
		</div>
	</h2>

	<h2 class="nav-tab-wrapper fibosearch-debug_page-nav-tab-wrapper">
		<?php 
?>
		<a href="#dgwt_wcas_deb_anatytics" class="nav-tab" id="dgwt_wcas_deb_anatytics-tab">Analytics</a>
		<a href="#dgwt_wcas_deb_maintenance" class="nav-tab" id="dgwt_wcas_deb_maintenance-tab">Maintenance</a>
	</h2>

	<?php 
$active = 'analytics';
?>

	<div class="dgwt-wcas-settings-body js-dgwt-wcas-settings-body" data-dgwt-wcas-active="<?php 
echo  esc_attr( $active ) ;
?>">
		<?php 
?>

		<div id="dgwt_wcas_deb_anatytics" class="fibosearch-debug_page-group">
			<?php 
include_once DGWT_WCAS_DIR . 'partials/admin/debug/body-analytics.php';
?>
		</div>
		<div id="dgwt_wcas_deb_maintenance" class="fibosearch-debug_page-group">
			<?php 
include_once DGWT_WCAS_DIR . 'partials/admin/debug/body-maintenance.php';
?>
		</div>
	</div>
</div>

<script>
	jQuery(document).ready(function ($) {

		function markActiveGroup($group) {
			var name = $group.attr('id').replace('dgwt_wcas_', '');

			$group.addClass('dgwt-wcas-group-active');
			$group.closest('.js-dgwt-wcas-settings-body').attr('data-dgwt-wcas-active', name)
		}

		// Switches option sections
		$('.<?php 
echo  $prefix ;
?>group').hide();
		var activetab = '';

		if (typeof (localStorage) != 'undefined') {
			maybe_active = localStorage.getItem('<?php 
echo  $prefix ;
?>settings-active-tab');

			if (maybe_active) {
				// Check if tabs exists
				$('.<?php 
echo  $prefix ;
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
echo  $prefix ;
?>group:first').fadeIn();
			markActiveGroup($('.<?php 
echo  $prefix ;
?>group:first'));
		}

		$('.<?php 
echo  $prefix ;
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
echo  $prefix ;
?>nav-tab-wrapper a:first').addClass('nav-tab-active');
		}

		$('.<?php 
echo  $prefix ;
?>nav-tab-wrapper a:not(.js-nav-tab-minor)').on('click', function (evt) {

			if (typeof (localStorage) != 'undefined') {
				localStorage.setItem('<?php 
echo  $prefix ;
?>settings-active-tab', $(this).attr('href'));
			}

			$('.<?php 
echo  $prefix ;
?>nav-tab-wrapper a').removeClass('nav-tab-active');

			$(this).addClass('nav-tab-active').blur();
			var clicked_group = $(this).attr('href');

			$('.<?php 
echo  $prefix ;
?>group').hide();
			$(clicked_group).fadeIn();
			markActiveGroup($(clicked_group));
			evt.preventDefault();
		});
	});
</script>
