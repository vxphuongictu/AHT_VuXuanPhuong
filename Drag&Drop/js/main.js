$(document).ready(function(){
	$('.bg-img').draggable({
		scroll: false,
		drag: function(){
			$(this).css('border', '3px solid red');
			$(this).css('background-color', 'white');
		},
		stop: function(){
			$(this).css('border', '0');
		}
	});

	$('.box').droppable({
		accept: '.bg-img',
		activate: function ()
		{
			$(this).css('background-color', 'grey');
		},
		over: function ()
		{
			$(this).css('background-color', 'yellow');
		},
		out: function ()
		{
			$(this).css('background-color', 'green');
		},
		drop: function ()
		{
			$(this).css('background-color', 'blue');
		}
	});
});