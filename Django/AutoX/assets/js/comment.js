$(document).ready(function(){
	$('body').on('click', '.border-input textarea', function() {
		$('.input-group.btn').css({
			'animation' : 'showButton 1.5s forwards',
			'margin' 	: '0 auto'
		});
		$('.comment .form-inline').fadeIn(500);
		$('.comment .form-inline').css('display', 'flex');
	});

	$('body').on('click', function (e) {
		var target = e.target;
		if (!$(target).is('.border-input textarea'))
		{
			if ($('.input-group.btn').css('animation') == "1.5s ease 0s 1 normal forwards running showButton")
			{
				$('.input-group.btn').css('width', '100%');
				$('.input-group.btn').css('animation', '');
				$('.input-group.btn').css({
					'animation' : 'hideButton 1.5s forwards',
					'margin' 	: '0 auto'
				});

				$('.form-inline').fadeOut(500);
			}
		}
	});

	$('body').on('click', '.comment .rating i', function(event) {
		var target  = event.target;
		var rate 	= $(target).parent('.star').attr('value');
		var all_star= $('.comment .rating .star');
		jQuery.each(all_star, function(index, element){
			var i 	= index + 1;
			if (i <= Number(rate))
			{
				$(element).find('.fa').removeClass('fa-star-o').addClass('fa-star');
			}
		});
		$('input[name="rating-data"]').val(rate);
	});
})