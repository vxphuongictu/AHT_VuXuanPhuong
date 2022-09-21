$(function () {
	loader();
});

function loader()
{
	$('.loader').css('display', 'block');
	$('.loader').width(100+'%');
	$('.loader').width('101%').delay(800).fadeOut('400', function() {
		// $(this).remove();
		$(this).css('display', 'none');
	});
}