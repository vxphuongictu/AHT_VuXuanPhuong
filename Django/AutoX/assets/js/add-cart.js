function upNumber(e, product_id, csrf_token)
{
	var target 	 	 		= $(e);
	var currentQuantity 	= target.parents('.input-quantity').find('#quantity-'+product_id).val();
	var new_quantity 		= Number(currentQuantity) + 1;
	$('#quantity-'+product_id).val(new_quantity);
	change_quantity(csrf_token, product_id);
}

function downNumber(e, product_id, csrf_token)
{
	var target 	 	 		= $(e);
	var currentQuantity 	= target.parents('.input-quantity').find('#quantity-'+product_id).val();
	if (currentQuantity >= 2)
	{
		var new_quantity 	= Number(currentQuantity) - 1;
		$('#quantity-'+product_id).val(new_quantity);
		change_quantity(csrf_token, product_id);
	}
}

function addCart (e, product_id) {
	flyToElement($(e).parents('.item-group-home-user__link').find('.image-item'), $('.header-menu-right'));
	$.ajax({
			url		: '/products/cart/'+product_id+'/',
			type	: 'GET',
			data	: {},
			beforeSend  : function(){
				loader();
			},
			success	: function (data) {}
	});
}

function addCartFromWishList (e, product_id) {
	flyToElement($(e).parents('tr').find('.product-img'), $('.header-menu-right'));
	$.ajax({
			url		: '/products/cart/'+product_id+'/',
			type	: 'GET',
			data	: {},
			beforeSend  : function(){
				loader();
			},
			success	: function (data) {}
	});
}

function delete_on_cart(product_id, csrf_token)
{
	$.ajax({
			url 	: '/products/update-cart/',
			type 	: 'POST',
			data 	: {
				csrfmiddlewaretoken : csrf_token,
				product_id 			: product_id,
				act 				: 'delete'
			},
			success : function(){
				window.location.href = "";
			}
		});
}

function change_quantity(csrf_token, product_id=false)
{
	if (product_id != false) {
		var price 		= $('#price-'+product_id).val();
		var quantity 	= $('#quantity-'+product_id).val();
		if (quantity >= 1)
		{
			var subtotal	= price * quantity;
			$('#subtotal-'+product_id).html(subtotal.toLocaleString());
		}

		$.ajax({
			url: '/products/update-cart/',
			type: 'POST',
			data: {
				csrfmiddlewaretoken	: csrf_token,
				product_id 			: product_id,
				quantity 			: quantity,
				act  				: ''
			},
			success: function (data) {}
		});
	}
	totalPrice();
	getData();
}

function getData()
{
	var data 			 	= $('.data-form');
	var quantity 			= $('.quantity');
	var dataPost 			= []
	jQuery.each(data, function(index, val) {
		var value 			= $(val);
		var product_id 		= value.find('.product-id').val();
		var product_price 	= value.find('.product-price').val();
		var product_quantity= $(quantity[index]).find('.product-quantity').val();

		var result 			= {
			'product_id' 	 	: product_id,
			'product_quantity' 	: product_quantity,
			'product_price'  	: product_price
		}
		dataPost.push(result);
	});
	$('#data').val(JSON.stringify(dataPost));
}

function totalPrice()
{
	var total 				= 0;
	var allItem 			= $('.product-item');

	jQuery.each(allItem, function(index, val) {
	  var dom_price 		= $(val).find('.price').text();
	  var price 			= dom_price.replaceAll(',', '').replace('VND', '');
	  var quantity 			= $(val).find('.input-quantity input').val();
	  var totalPrice 		= price * quantity;
	  $(val).find('.total-price').html(totalPrice.toLocaleString() + '&ensp;VND');
	});
}

function flyToElement(flyer, flyingTo) {

	// Nhân bản đối tượng(hình ảnh) sẽ bay vào giỏ hàng
	var flyerClone  	= $(flyer).clone();
	// Thiết lập đối tượng nhân bản này trùng với đối tượng thực tế 
	$(flyerClone).css({
		position 	: 'absolute',
		top 		: $(flyer).offset().top + "px",
		left 		: $(flyer).offset().left + "px",
		opacity 	: 1,
		'z-index' 	: 1000
	}).appendTo($('body'));

	// Lấy về tọa độ của giỏ hàng
	var gotoX = $(flyingTo).offset().left;
	var gotoY = $(flyingTo).offset().top;

	// Hiệu ứng bay vào giỏ hàng
	$(flyerClone).animate({
		opacity 	: 0.4,
		left 		: gotoX,
		top 		: gotoY,
		width 		: $(flyingTo).width(),
		height 		: $(flyingTo).height()
	}, 700,
	function () {
		// Hiệu ứng rung lắc khi sản phẩm đã bay vào giỏ hàng
			// Ẩn đối tượng sản phẩm bay vào giỏ hàng và delete nó
		$(flyerClone).css('transform', 'scale(0)', function(){
			$(flyerClone).remove();
		});
		$(flyingTo).effect("shake");
	});
}

$(document).ready(function(){
	totalPrice();
	getData();
	$('body').on('click', '#order-now', function(){
		var first_name 			= $('#first-name').val();
		var last_name 			= $('#last-name').val();
		var country 			= $('#country').val();
		var street_address	 	= $('#street-address').val();
		var town  				= $('#town').val();
		var postcode 			= $('#postcode').val();
		var phone 				= $('#phone').val();
		var email 				= $('#email').val();

		var arr_input_important	= [
			'first-name',
			'last-name',
			'country',
			'street-address',
			'town',
			'postcode',
			'phone',
			'email'
		]
		var empty_val 			= [];

		arr_input_important.forEach(function(element, index){
			var get_val 		= $('#'+element).val();
			$('#'+element).parents('.container-input').css('border', '1px solid #444');
			if (!get_val) {
				empty_val.push(element);
				var target 		= $('#'+element).parents('.container-input')
				target.css('border', '1px solid red');
			}
		});
		if (empty_val.length > 0)
		{
			location.href 		= "#"+empty_val[0];
			return;
		}
	});


	$( ".view-container .draggable" ).draggable({
		axis: 'x',
		drag: function( event, ui ) {
			ui.position.left 	= Math.min( 0, ui.position.left );
			ui.position.left 	= Math.max( -40, ui.position.left );
			var item 				= $('.view-container .box-item');
			jQuery.each(item, function(index, element)
			{
				$(element).find('.draggable').css('left', 0);
			});
		}
	});

	$('body').on('click', function(event){
		var item 				= $('.view-container .box-item');
		jQuery.each(item, function(index, element)
		{
			$(element).find('.draggable').css('left', 0);
		});
	});
});