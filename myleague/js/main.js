function background()
{
	$('body').on('click', '.nen-san-dau', function(){
		$('.option-nen-san-dau').toggle();
	});

	$('body').on('click', '.option-nen-san-dau .option', function(event) {
		var name 	= $(this).find('span').text();
		var sanID 	= $(this).attr('id');

		console.log(sanID);
		if (sanID == "san1")
		{
			$('.ground-img').css('filter', 'none');
		}

		if (sanID == "san2")
		{
			$('.ground-img').css('filter', 'grayscale(50%)');	
		}

		if (sanID == "san3")
		{
			$('.ground-img').css('filter', 'grayscale(100%)');	
		}

		if (sanID == "san4")
		{
			$('.ground-img').css('filter', 'sepia(50%)');	
		}

		if (sanID == "san5")
		{
			$('.ground-img').css('filter', 'sepia(100%)');	
		}

		if (sanID == "san6")
		{
			$('.ground-img').css('filter', 'invert()');	
		}

		$('.nen-san-dau #select').html(name);
		$('.option-nen-san-dau').css('display', 'none');
	});
}

function background_tshirt()
{
	$('body').on('click', '.anh-mac-dinh', function(){
		$('.option-anh-mac-dinh').toggle();
	});

	$('body').on('click', '.option-anh-mac-dinh .option', function(event) {
		var name 	= $(this).find('span').text();
		var sanID 	= $(this).attr('id');

		if (sanID == "ao1")
		{
			$('.tshirt-img').css('filter', 'none');
		}

		if (sanID == "ao2")
		{
			$('.tshirt-img').css('filter', 'invert()');
		}

		if (sanID == "ao3")
		{
			$('.tshirt-img').css('filter', 'grayscale()');	
		}

		if (sanID == "ao4")
		{
			$('.tshirt-img').css('filter', 'brightness(2)');	
		}
		$('.anh-mac-dinh #select').html(name);
		$('.option-anh-mac-dinh').css('display', 'none');
	});
}

function getMember()
{
	var list 			= [];
	$('body').find('.option-group').each(function(e){
		var checkbox	= false;
		if($(this).find("input[type='checkbox']").is(":checked"))
		{
			checkbox 	= true;
		}
		var soAo 		= $(this).find("input[type='number']").val();
		var tenAo 		= $(this).find("input[type='text']").val();
		var viTri 		= $(this).find(".vi-tri").find(':selected').val();

		if (checkbox === true)
		{
			var param 		= {
				'soAo'		: soAo,
				'tenAo'		: tenAo,
				'viTri'		: viTri
			};
			list.push(param);
		}
	});
	return list;
}

function showMember () {
	var listMember 		= getMember();
	for (let i = 0; i < listMember.length; i ++)
	{
		var item 		= listMember[i];
		$('.ground-img').append('<div class="member" id="'+item.soAo+'"><div class="tshirt-background"><img class="tshirt-img"></img></div><div class="info-member"><span class="number-tshirt">('+item.soAo+')</span><span class="name-tshirt">'+item.tenAo+'</span></div></div>');
		$('.member').draggable();
	}
}

function clearMember()
{
	$('.ground-img').html('');
}

function setSoLuongNguoi()
{
	var count 			= 0;
	var soLuongNguoi 	= $('.so-luong-nguoi option:selected').val();
	$('body').find('.option-group').each(function(e){
		count++;
		if (count <= Number(soLuongNguoi))
		{
			$(this).find("input[type='checkbox']").prop('checked', true);
		}
		else
		{
			$(this).find("input[type='checkbox']").prop('checked', false);
		}
	});
	clearMember();
	showMember();
	setPosision();
}

function setPosision()
{
	$('#1').css({
		left: '155px',
		top: '537px'
	});

	$('#2').css({
		left: '-4px',
		top: '440px'
	});

	$('#3').css({
		left: '105px',
		top: '456px'
	});

	$('#4').css({
		left: '230px',
		top: '457px'
	});

	$('#5').css({
		left: '320px',
		top: '433px'
	});

	$('#6').css({
		left: '21px',
		top: '289px'
	});

	$('#7').css({
		left: '159px',
		top: '227px'
	});

	$('#8').css({
		left: '161px',
		top: '351px'
	});

	$('#9').css({
		left: '285px',
		top: '291px'
	});

	$('#10').css({
		left: '61px',
		top: '112px'
	});

	$('#11').css({
		left: '251px',
		top: '111px'
	});
}

$(document).ready(function() {
	background();
	showMember();
	setPosision();
	background_tshirt();
});