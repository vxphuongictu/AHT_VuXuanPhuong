jQuery(document).ready(function($){
	$('#upload_header_image_button').click(function(e){
		e.preventDefault();
		var cot_uploader = wp.media({
			title: 'Select or upload header a logo',
			button: { text: 'Select Header Logo' },
			multiple: false
		}).on('select', function(){
			var attachment = cot_uploader.state().get('selection').first().toJSON();
			$('#cot_header_logo_image').val(attachment.url);
			$('#cot_customtheme_admin_preview').attr("src", attachment.url);
		}).open();
		
	});

	$('#upload_favicon_icon_button').click(function(e){		
		e.preventDefault();
		var cot_uploader = wp.media({
			title: 'Select or upload favicon icon',
			button: { text: 'Select Favicon Icon' },
			multiple: false
		}).on('select', function(){
			var attachment = cot_uploader.state().get('selection').first().toJSON();
			$('#cot_favicon_icon_image').val(attachment.url);
			$('#cot_favicon_admin_preview').attr("src", attachment.url);
		}).open();
		
	});

	$('#upload_footer_image_button').click(function(e){
		e.preventDefault();
		var cot_uploader = wp.media({
			title: 'Select or upload footer a logo',
			button: { text: 'Select Footer Logo' },
			multiple: false
		}).on('select', function(){
			var attachment = cot_uploader.state().get('selection').first().toJSON();
			$('#cot_footer_logo_image').val(attachment.url);
			$('#cot_customtheme_footer_admin_preview').attr("src", attachment.url);
		}).open();
		
	});
	$( ".form-table" ).after( "<hr>" );
});
