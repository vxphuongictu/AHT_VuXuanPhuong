$(function () {
	var isOpen 	= false;

	$('body').on('click', '.btn-options', function () {
		if(isOpen === false)
		{
			$('.panel-menu').css('animation', 'menuOpen 1s forwards');
			$('.btn-options .fa-bars').css('display', 'none');
			$('.btn-options .fa-times').css('display', 'flex');
			$('.bg-body').css('display', 'block');
			$('.text-link-none-user').html('LOGIN');
			$('#header').css({
				'position': 'fixed',
				'top':0
			});
			isOpen 	= true;
		} else {
			$('.panel-menu').css('animation', 'panelClose 1s forwards');
			$('.btn-options .fa-bars').css('display', 'flex');
			$('.btn-options .fa-times').css('display', 'none');
			$('.bg-body').css('display', 'none');
			$('#header').css('position', 'relative');
			isOpen 	= false;
		}
	});

	$('body').on('click', function(e){
		var target 	= e.target;
		if (isOpen == true)
		{
			if(!$(target).is('.btn-options i, .btn-options, .panel-menu *'))
			{
				$('.panel-menu').css('animation', 'panelClose 1s forwards');
				$('.btn-options .fa-bars').css('display', 'flex');
				$('.btn-options .fa-times').css('display', 'none');
				$('.bg-body').css('display', 'none');
				$('#header').css('position', 'relative');
				isOpen 	= false;
			}
		}
	});
});