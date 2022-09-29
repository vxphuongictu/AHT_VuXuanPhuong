$(document).ready(function(){
	$('body').on('change', 'select', function(e) {
		var arr_option   = ["text", "password", "number", "date"]
		var target       = e.target;
		var id_select    = $(target).attr('id');
		var options      = $('#'+id_select).val();
		if (arr_option.indexOf(options) > 0)
		{
			var input_element 	= id_select.replace('options', 'value');
			$('#'+input_element).attr('type', options);
		}
	});
})