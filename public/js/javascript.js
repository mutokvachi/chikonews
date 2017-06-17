
function responsibility(){
	if($(window).width() < 768){
		$('.products header').hide();
		$('.product div:nth-child(1)').prepend('<b>ბრალდებული: </b>');
		$('.product div:nth-child(2)').prepend('<b>მოწმე: </b>');
		$('.product div:nth-child(3)').prepend('<b>აღწერა: </b>');
		$('.product div:nth-child(4)').prepend('<b>ქულა: </b>');
		$('.product div:nth-child(5)').prepend('<b>დრო: </b>');
	}
}
$('.add_product').click(function(){
	$('.products .my_modal').fadeIn();
	$(window).scrollTop(0);
});
$('.my_modal .fa-close').click(function(){
	$('.my_modal').fadeOut();
});

$('.my_modal #send').click(function(){

	var serial = $('#form').serialize();
	$.ajax({
		url:'/home',
		method: 'POST',
		data:serial,
		success:function(data){
			
			$('.my_modal input').css('border', 'none');
			$('.my_modal textarea').css('border', 'none');

			if(data.length != 0){
				var status = JSON.parse(data);
				status['errors'].select ? $('.my_modal #select').css('border', '1px solid red') : ''; 
				status['errors'].person ? $('.my_modal #person').css('border', '1px solid red') : '';
				status['errors'].text ? $('.my_modal #text').css('border', '1px solid red') : '';
				status['errors'].points ? $('.my_modal #points').css('border', '1px solid red') : '';
			}
			if(data.length == 0){
				$('.my_modal').fadeOut(1000);
				$('.my_modal input').slice(0, 2).val('');
				$('.my_modal textarea').val('');
			}
			showPosts();
		}
	});	
});
var showPostsTiming = false;
var serial = 0;
var QTY = 4;


$('.products .load_more').click(function(){
	var serial = $('.product:last()').attr('product_id');
	var loader = true;

	serial = {'last_post': serial, 'QTY': QTY, 'loader': loader};
	
	$.ajax({
		url:'home/ajax',
		method: 'GET',
		data: serial,
		success:function(data){

			$('.products_list').append(data);
			serial = $('.product:eq(0)').attr('product_id');
			if(showPostsTiming == false){
				showPostsTiming = true;
				responsibility();
			}
		}
	});
});

function showPosts(pos){
	serial = {'last_post': serial, 'QTY': QTY};
	$.ajax({
		url:'home/ajax',
		method: 'GET',
		data: serial,
		success:function(data){
			
			$('.products_list').prepend(data);
			serial = $('.product:eq(0)').attr('product_id');
			if(showPostsTiming == false){
				showPostsTiming = true;
				responsibility();
			}
		}
	});
}

showPosts();
var showPostsInterval = setInterval(showPosts, 5000);

