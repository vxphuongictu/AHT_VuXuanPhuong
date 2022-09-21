$(function () {
		var mouseDown 		= 0;
		var mouseUp 		= 0;
		var img_position 	= 0;
		var list_item 		= [];
		var slider 		= $('.btn-slide').find('.dot');

		$('.header-product .product-image > img').mousedown(function(e){
			mouseDown 		= e.pageX;
			$(this).css('cursor', 'grab');
		}).mouseup(function(e) {
			mouseUp 		= e.pageX;

			slider.each( function(e) {
				var target 	= $(this);
				list_item.push(target.find('.url-image').attr('value'));
			});

			if (mouseDown < mouseUp)
			{
				img_position--;
			} else {
				img_position++;
			}

			if (img_position > slider.length - 1)
			{
				img_position = 0;
			} else if (img_position < 0)
			{
				img_position = slider.length - 1;
			}
			$(slider).css('padding', '0');
			$(slider[img_position]).css('padding', '0 5px');

			$('#product-image').attr('src', list_item[img_position]);
			$(this).css('cursor', 'auto');
		});

		$('body').on('click', '.btn-slide .dot', function (e) {
			$(slider).css('padding', '0');
			$(this).css('padding', '0 5px');
		});
});