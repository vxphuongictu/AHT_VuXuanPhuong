$(function () {
	var isOpen 	= false;

	$('body').on('click', '.btn-options', function () {
		if(isOpen === false)
		{
			$('.panel-menu').css('animation', 'menuOpen 1s forwards');
			$('.btn-options .fa-bars').css('display', 'none');
			$('.btn-options .fa-times').css('display', 'block');
			$('.bg-body').css('display', 'block');
			$('.text-link-none-user').html('LOGIN');
			isOpen 	= true;
		} else {
			$('.panel-menu').css('animation', 'panelClose 1s forwards', function(){
				$('.panel-menu').css('animation', '');
			});
			$('.btn-options .fa-bars').css('display', 'block');
			$('.btn-options .fa-times').css('display', 'none');
			$('.bg-body').css('display', 'none');
			isOpen 	= false;
		}
	});

	$('body').on('click', function(e){
		var target 	= e.target;
		if (isOpen == true)
		{
			if(!$(target).is('.btn-options i, .btn-options, .panel-menu *'))
			{
				$('.panel-menu').css('animation', 'panelClose 1s forwards', function(){
					$('.panel-menu').css('animation', '');
				});
				$('.btn-options .fa-bars').css('display', 'block');
				$('.btn-options .fa-times').css('display', 'none');
				$('.bg-body').css('display', 'none');
				isOpen 	= false;
			}
		}
	});
});