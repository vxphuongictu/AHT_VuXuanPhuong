$(document).ready(function(){

	/* Menu Level 2 ServiSense */
	$('body').on('click', '.header-menu-right', function(){
		$('.second-menu .sub-menu').toggle('500');
	});
	/* Menu Level 2 ServiSense End */

	/* First Menu Level 2 */

	var isOpen 		= false;
	$('body').on('click', '.first-menu > ul > li > a', function(e){
		var target 	= e.target;
		if (isOpen == false)
		{
			$(target).parent('.menu-item').find('.sub-menu').toggle('500');
			$(target).css('color', '#ffbe00')
			isOpen  = true;
		} else {
			while (true) {
				$('.first-menu .sub-menu').css('display', 'none');
				$('.first-menu > ul > li > a').css('color', '')
				$(target).parent('.menu-item').find('.sub-menu').toggle('500');
				$(target).css('color', '#ffbe00')
				isOpen  = true;
				break;
			}
		}
	});
	/* Menu Level 2 ServiSense End */

	var isOpenDropdown	= false;
	/* Drop down menu */
	$('body').on('click', '.header-menu-right', function(event) {
		if (isOpenDropdown == false)
		{
			$('.drop-down-menu').css('animation', 'dropDownMenuOpen .3s forwards');
			isOpenDropdown 	= true;
		} else {
			$('.drop-down-menu').css('animation', 'dropDownMenuClose .3s forwards');
			isOpenDropdown	= false;
		}
	});
	/* Drop down menu end */

	/* Hidden Menu When Click Another Location */
	$('body').on('click', function(e){
		var target 	= e.target;

		/* Menu Level 2 ServiSense */
		if (!$(target).is('.header-menu-right *, .header-menu-right .second-menu *, .header-menu-right'))
		{
			if(isOpenDropdown == true)
			{
				$('.drop-down-menu').css('animation', 'dropDownMenuClose .3s forwards');
				isOpenDropdown	= false;
			}
		}

		/* First Menu Level 2 */
		if (!$(target).is('.first-menu .menu > li *'))
		{
			$('.first-menu .sub-menu').css('display', 'none');
			$('.first-menu > ul > li > a').css('color', '')
			isOpen 	= false;
		}
	});

	/* Hidden Menu When Click Another Location End */
});
