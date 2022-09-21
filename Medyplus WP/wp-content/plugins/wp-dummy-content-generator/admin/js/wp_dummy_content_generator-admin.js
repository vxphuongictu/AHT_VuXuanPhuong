(function( $ ) {
	'use strict';

	$(function() {

		// manage data from adminbar links
		$(document).on('click','.wp_dummy_content_generatorDataCleaner',function(event){
			event.preventDefault();
			var wp_dummy_content_generatorEventID = $(this).attr('id');
			console.log(wp_dummy_content_generatorEventID);
			var wp_dummy_content_generatorAction = '';
			switch(wp_dummy_content_generatorEventID) {
			    case 'wp-admin-bar-wp_dummy_content_generatorDeleteUsers':
			        wp_dummy_content_generatorAction = 'wp_dummy_content_generatorDeleteUsers';
			        break;
			    case 'wp-admin-bar-wp_dummy_content_generatorDeletePosts':
			        wp_dummy_content_generatorAction = 'wp_dummy_content_generatorDeletePosts';
			        break;
			    case 'wp-admin-bar-wp_dummy_content_generatorDeleteProducts':
			        wp_dummy_content_generatorAction = 'wp_dummy_content_generatorDeleteProducts';
			        break;
			    case 'wp-admin-bar-wp_dummy_content_generatorDeleteThumbnails':
			        wp_dummy_content_generatorAction = 'wp_dummy_content_generatorDeleteThumbnails';
			        break;			    
			    default:

			}
			wp_dummy_content_generatorAjaxManageData(wp_dummy_content_generatorAction);
			console.log(wp_dummy_content_generatorAction);
		});

		function wp_dummy_content_generatorAjaxManageData(wp_dummy_content_generatorAction){
			var url = wp_dummy_content_generator_backend_ajax_object.wp_dummy_content_generator_ajax_url;
			$.ajax({
				url: url,
				type: 'post',
				dataType: 'JSON', // Set this so we don't need to decode the response...
				data:  ({ action: wp_dummy_content_generatorAction}), // One-liner form data prep...
				beforeSend: function () {
					$('#wpfooter').append('<div class="wp_dummy_content_generatorLoading">Loading&#8230;</div>');
					$('#wpfooter').show();
					// You could do an animation here...
				},
				error: handleFormError,
				success: function (data) {
					$('.wp_dummy_content_generatorLoading').remove();
					if (data.status === 'success') {
						console.log('success');
					}else {
						handleFormError(); // If we don't get the expected response, it's an error...
						//is_sending = false;
					}
				}
			});
		}
		// manage data from adminbar links


		//var data_val = $('#wp_dummy_content_generatorGenPostForm').serialize();
		$('#wp_dummy_content_generatorListPostsTbl').DataTable();
		$('#wp_dummy_content_generatorListProductsTbl').DataTable();
		var is_sending = false,
		failure_message = 'Whoops, looks like there was a problem. Please try again later.';
		$('#wp_dummy_content_generatorGenPostForm').submit(function (e) {
			if (is_sending) {
				return false; // Don't let someone submit the form while it is in-progress...
			}
			e.preventDefault(); // Prevent the default form submit
			$('.remaining_posts').val($('.wp_dummy_content_generator-post_count').val());
			var $this = $(this); // Cache this
			// call ajax here
			$('.dcsLoader').show();
			//$('.remaining_notification').html('').html('<p>Post generator initialized. Waiting for the first response...</p>');
			wp_dummy_content_generator_generatePostsLoop($this);
		});

		$('#wp_dummy_content_generatorGenProductForm').submit(function (e) {
			if (is_sending) {
				return false; // Don't let someone submit the form while it is in-progress...
			}
			e.preventDefault(); // Prevent the default form submit
			$('.remaining_products').val($('.wp_dummy_content_generator-product_count').val());
			var $this = $(this); // Cache this
			// call ajax here
			//$('.remaining_notification').html('').html('<p>Products generator initialized. Waiting for the first response...</p>');
			$('.dcsLoader').show();
			wp_dummy_content_generator_generateProductsLoop($this);
		});

		$('#wp_dummy_content_generatorGenUserForm').submit(function (e) {
			var url = wp_dummy_content_generator_backend_ajax_object.wp_dummy_content_generator_ajax_url;
			if (is_sending) {
				return false; // Don't let someone submit the form while it is in-progress...
			}
			e.preventDefault(); // Prevent the default form submit
			$('.remaining_users').val($('.wp_dummy_content_generator-user_count').val());
			$('.dcsLoader').show();
			//$('.remaining_notification').html('').html('<p>User generator initialized. Waiting for the first response...</p>');
			var $this = $(this); // Cache this
			wp_dummy_content_generator_generateUsersLoop($this)
		});

		function handleFormError () {
			is_sending = false; // Reset the is_sending var so they can try again...
			$('.wp_dummy_content_generator-error-msg').html('Something went wrong. Please try again').fadeIn('fast').delay(1000).fadeOut('slow');
			//alert(failure_message);
		}

		function wp_dummy_content_generator_generatePostsLoop($that){
			var $this = $that;
			var url = wp_dummy_content_generator_backend_ajax_object.wp_dummy_content_generator_ajax_url;
			$.ajax({
				url: url,
				type: 'post',
				dataType: 'JSON', // Set this so we don't need to decode the response...
				data: $this.serialize(), // One-liner form data prep...
				beforeSend: function () {
					is_sending = true;
					$('.wp_dummy_content_generatorGeneratePosts').val('Generating posts.');
					// You could do an animation here...
				},
				error: handleFormError,
				success: function (data) {
					$('.wp_dummy_content_generatorGeneratePosts').val('Generate posts.');
					if (data.status === 'success' && data.remaining_posts>0) {
						$('.remaining_posts').val(data.remaining_posts);
						var totalOfPosts = $('.wp_dummy_content_generator-post_count').val();
						
						// loader
						var wp_dummy_content_generatorcompletedPer = Math.round(( (totalOfPosts - data.remaining_posts ) * 100 ) /totalOfPosts);
						$('.wp_dummy_content_generatorLoaderPer').text(wp_dummy_content_generatorcompletedPer+'%');
						var addedClass = 'p'+wp_dummy_content_generatorcompletedPer;
						$('.wp_dummy_content_generatorLoaderWrpper').attr('class','wp_dummy_content_generatorLoaderWrpper c100 blue');
						$('.wp_dummy_content_generatorLoaderWrpper').addClass(addedClass);
						// loader

						//$('.remaining_notification').html('').html('<p>'+data.remaining_posts+' posts are remaining out of '+totalOfPosts+'</p>');
						wp_dummy_content_generator_generatePostsLoop($this);
					}else if (data.status === 'success' && data.remaining_posts==0){
						$('.wp_dummy_content_generator-success-msg').html('Posts generated successfully.').fadeIn('fast').delay(1000).fadeOut('slow');
						// loader
						$('.wp_dummy_content_generatorLoaderPer').text('100%');
						$('.wp_dummy_content_generatorLoaderWrpper').attr('class','wp_dummy_content_generatorLoaderWrpper c100 blue');
						$('.wp_dummy_content_generatorLoaderWrpper').addClass('p100');
						$('.dcsLoader').hide();
						$('.wp_dummy_content_generatorLoaderPer').text('0%');
						$('.wp_dummy_content_generatorLoaderWrpper').attr('class','wp_dummy_content_generatorLoaderWrpper c100 blue');
						$('.wp_dummy_content_generatorLoaderWrpper').addClass('p0');
						// loader
						//$('.remaining_notification').html('');
						is_sending = false;
					}else {
						handleFormError(); // If we don't get the expected response, it's an error...
						is_sending = false;
					}
				}
			});
		}


		function wp_dummy_content_generator_generateProductsLoop($that){
			var $this = $that;
			var url = wp_dummy_content_generator_backend_ajax_object.wp_dummy_content_generator_ajax_url;
			$.ajax({
				url: url,
				type: 'post',
				dataType: 'JSON', // Set this so we don't need to decode the response...
				data: $this.serialize(), // One-liner form data prep...
				beforeSend: function () {
					is_sending = true;
					$('.wp_dummy_content_generatorGenerateProducts').val('Generating products.');
					// You could do an animation here...
				},
				error: handleFormError,
				success: function (data) {
					$('.wp_dummy_content_generatorGenerateProducts').val('Generate products.');
					if (data.status === 'success' && data.remaining_products>0) {
						$('.remaining_products').val(data.remaining_products);
						var totalOfProducts = $('.wp_dummy_content_generator-product_count').val();
						
						// loader
						var wp_dummy_content_generatorcompletedPer = Math.round(( (totalOfProducts - data.remaining_products ) * 100 ) /totalOfProducts);
						$('.wp_dummy_content_generatorLoaderPer').text(wp_dummy_content_generatorcompletedPer+'%');
						var addedClass = 'p'+wp_dummy_content_generatorcompletedPer;
						$('.wp_dummy_content_generatorLoaderWrpper').attr('class','wp_dummy_content_generatorLoaderWrpper c100 blue');
						$('.wp_dummy_content_generatorLoaderWrpper').addClass(addedClass);
						// loader

						//$('.remaining_notification').html('').html('<p>'+data.remaining_products+' products are remaining out of '+totalOfProducts+'</p>');
						wp_dummy_content_generator_generateProductsLoop($this);
					}else if (data.status === 'success' && data.remaining_products==0){
						
						// loader
						$('.wp_dummy_content_generatorLoaderPer').text('100%');
						$('.wp_dummy_content_generatorLoaderWrpper').attr('class','wp_dummy_content_generatorLoaderWrpper c100 blue');
						$('.wp_dummy_content_generatorLoaderWrpper').addClass('p100');
						$('.dcsLoader').hide();
						$('.wp_dummy_content_generatorLoaderPer').text('0%');
						$('.wp_dummy_content_generatorLoaderWrpper').attr('class','wp_dummy_content_generatorLoaderWrpper c100 blue');
						$('.wp_dummy_content_generatorLoaderWrpper').addClass('p0');
						// loader

						$('.wp_dummy_content_generator-success-msg').html('Products generated successfully.').fadeIn('fast').delay(1000).fadeOut('slow');
						//$('.remaining_notification').html('');
						is_sending = false;
					}else {
						handleFormError(); // If we don't get the expected response, it's an error...
						is_sending = false;
					}
					
				}
			});
		}

		function wp_dummy_content_generator_generateUsersLoop($that){
			var $this = $that;
			var url = wp_dummy_content_generator_backend_ajax_object.wp_dummy_content_generator_ajax_url;
			$.ajax({
				url: url,
				type: 'post',
				dataType: 'JSON', // Set this so we don't need to decode the response...
				data: $this.serialize(), // One-liner form data prep...
				beforeSend: function () {
					is_sending = true;
					$('.wp_dummy_content_generatorGenerateUsers').val('Generating users.');
					// You could do an animation here...
				},
				error: handleFormError,
				success: function (data) {
					if (data.status === 'success' && data.remaining_users>0) {
						$('.remaining_users').val(data.remaining_users);
						var totalOfUsers = $('.wp_dummy_content_generator-user_count').val();

						// loader
						var wp_dummy_content_generatorcompletedPer = Math.round(( (totalOfUsers - data.remaining_users ) * 100 ) /totalOfUsers);
						$('.wp_dummy_content_generatorLoaderPer').text(wp_dummy_content_generatorcompletedPer+'%');
						var addedClass = 'p'+wp_dummy_content_generatorcompletedPer;
						$('.wp_dummy_content_generatorLoaderWrpper').attr('class','wp_dummy_content_generatorLoaderWrpper c100 blue');
						$('.wp_dummy_content_generatorLoaderWrpper').addClass(addedClass);
						// loader

						//$('.remaining_notification').html('').html('<p>'+data.remaining_users+' users are remaining out of '+totalOfUsers+'</p>');
						wp_dummy_content_generator_generateUsersLoop($this);
					}else if (data.status === 'success' && data.remaining_users==0){
						$('.wp_dummy_content_generator-success-msg').html('Users generated successfully.').fadeIn('fast').delay(1000).fadeOut('slow');
						//$('.remaining_notification').html('');
						// loader
						$('.wp_dummy_content_generatorLoaderPer').text('100%');
						$('.wp_dummy_content_generatorLoaderWrpper').attr('class','wp_dummy_content_generatorLoaderWrpper c100 blue');
						$('.wp_dummy_content_generatorLoaderWrpper').addClass('p100');
						$('.dcsLoader').hide();
						$('.wp_dummy_content_generatorLoaderPer').text('0%');
						$('.wp_dummy_content_generatorLoaderWrpper').attr('class','wp_dummy_content_generatorLoaderWrpper c100 blue');
						$('.wp_dummy_content_generatorLoaderWrpper').addClass('p0');
						//loader
						$('.wp_dummy_content_generatorGenerateUsers').val('Generate users.');
						is_sending = false;
					}else {
						handleFormError(); // If we don't get the expected response, it's an error...
						is_sending = false;
					}
					
				}
			});
		}

	});

})( jQuery );
