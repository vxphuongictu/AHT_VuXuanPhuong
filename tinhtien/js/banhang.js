$(document).ready(function(){

	class Process {

		constructor(){
			this.listFood 			= [
				{name: "Thịt chó", price: "240000", quanlity: 1, totalPrice: 240000},
				{name: "Cơm rang", price: "25000", quanlity: 2, totalPrice: 50000},
				{name: "Bún bò", price: "30000", quanlity: 1, totalPrice: 30000}
			];
		}

		show_food()
		{
			$('.body-table').html('');
			for (let i = 0; i < this.listFood.length; i ++)
			{
				var item 		= this.listFood[i];
				var stt	 		= i+1;
				var name 		= item.name;
				var price 		= item.price;
				var quanlity 	= item.quanlity;
				$('.body-table').append(
					'<tr><td scope="rows" id="stt">'+stt+'</td><td id="food-name">'+name+'</td><td id="food-price">'+Number(price).toLocaleString()+' VNĐ</td><td id="food-act"><input type="button" value="-" class="subtract-item-btn"><input type="number" value="'+quanlity+'" id="'+stt+'" class="input-number"><input type="button" value="+" class="add-item-btn"></td></tr>'
				);
			}
			return 0;
		}

		add_food(data)
		{
			this.listFood.push(data);
			this.exportBill();
			return 0;
		}

		quanlityChange(id, number)
		{
			for (let i = 0; i < this.listFood.length; i ++)
			{
				if (i == id)
				{
					var item 		= this.listFood[i];
					var name 		= item.name;
					var price 		= item.price;
					item.quanlity 	= number;
					item.totalPrice = this.priceCaculator(name, price, number).totalPrice;
					break;
				}
			}
			this.exportBill();
			return 0;
		}

		priceCaculator(name, price, quanlity)
		{
			var totalPrice 	= Number(price) * Number(quanlity);
			var new_data 	= {
				'name'		: name,
				'price'		: price,
				'quanlity'	: quanlity,
				'totalPrice': totalPrice,
			};
			return new_data;
		}

		exportBill()
		{
			var resultPrice 	= 0;
			$('.show-list-item').html('');
			$('.result-total').html('');
			for (let i = 0; i < this.listFood.length; i ++)
			{
				var item 		= this.listFood[i];
				var stt 		= i + 1;
				var name 		= item.name;
				var quanlity 	= item.quanlity;
				var totalPrice	= item.totalPrice;
				resultPrice 	= resultPrice + totalPrice;
				$('.show-list-item').append(
					'<div class="item"><div class="item-stt style-list">'+stt+'. </div><div class="item-name style-list">'+name+'</div><div class="style-list">x</div><div class="item-quanlity style-list">'+quanlity+'</div><div class="style-list">=</div><div class="item-total-price style-list">'+Number(totalPrice).toLocaleString()+' VNĐ</div></div>'
				);
			}
			$('.result-total').append('<span>'+Number(resultPrice).toLocaleString()+' VNĐ</span>')
		}
	};





	const xuLy 				= new Process();
	xuLy.show_food();
	xuLy.exportBill();

	$('#btn-submit').click(function(){
		var food_name	= $('#input-food-name').val();
		var food_price	= $('#input-food-price').val();
		var new_item 	= xuLy.priceCaculator(food_name, food_price, 1);
		xuLy.add_food(new_item);
		xuLy.show_food();

		$('#input-food-name').val('');
		$('#input-food-price').val('');
		console.log(xuLy.listFood);
		xuLy.exportBill();
	});

	$('body').on('click', '.add-item-btn', function(event){
		var quanlity 	= $(this).parent('#food-act').find('.input-number').val();
		var idItem 		= $(this).parent('#food-act').find('.input-number').attr('id');
		var idDB 		= Number(idItem) - 1;
		quanlity 		= Number(quanlity) + 1;
		xuLy.quanlityChange(idDB, quanlity);
		xuLy.show_food();
	});

	$('body').on('click', '.subtract-item-btn', function(event){
		var quanlity 	= $(this).parent('#food-act').find('.input-number').val();
		if (quanlity >= 1)
		{
			var idItem 		= $(this).parent('#food-act').find('.input-number').attr('id');
			var idDB 		= Number(idItem) - 1;
			quanlity 		= Number(quanlity) - 1;
			xuLy.quanlityChange(idDB, quanlity);
			xuLy.show_food();
		}
	});

	$('body').on('change', '.input-number', function (event){
		var quanlity 	= $(this).parent('#food-act').find('.input-number').val();
		if (quanlity >= 1)
		{
			var idItem 		= $(this).parent('#food-act').find('.input-number').attr('id');
			var idDB 		= Number(idItem) - 1;
			quanlity 		= Number(quanlity) - 1;
			xuLy.quanlityChange(idDB, quanlity);
			xuLy.show_food();
		}
	});
});

