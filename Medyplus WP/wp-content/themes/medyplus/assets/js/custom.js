/* MENU */
function categoryMenuSearch()
{
	$('body').on('click', '.header .container .act-group .search .search-group .category, .header .container .act-group .search .search-group i', function(e){
		$('.category .sub-menu').slideToggle(200);
	});
}

function hiddenMenu()
{
	$(document).on('click', function(e) {
		var target 		= e.target;
		if (!$(target).is('.header .container .act-group .search .search-group .category a font, .category .sub-menu, .header .container .act-group .search .search-group .category *, .header .container .act-group .search .search-group i')){
			$('.category .sub-menu').css('display', 'none');
		}
	})
}
/* END MENU */

/* COUNTDOWN */

function countDown()
{
	var timeNow 		= new Date().getTime();
	var timeEnd 		= new Date("Jan 1, 2023 0:0:0").getTime();
	var remainingTime	= timeEnd - timeNow;
	var days = Math.floor(remainingTime / (1000 * 60 * 60 * 24));
	var hours = Math.floor((remainingTime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
	var minutes = Math.floor((remainingTime % (1000 * 60 * 60)) / (1000 * 60));
	var seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);
	$('#remaining-day').html(days);
	$('#remaining-hour').html(hours);
	$('#remaining-min').html(minutes);
	$('#remaining-sec').html(seconds);
	setTimeout(countDown, 1000);
}
/* END COUNTDOWN */

$(document).ready(function() {
	categoryMenuSearch();
	hiddenMenu();
	countDown();
});