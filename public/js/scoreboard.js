$('.btn-danger').click(function(){
	$('.posts .my_modal').fadeIn();
	$(window).scrollTop(0);
});
$('.posts .my_modal .fa-close').click(function(){
	$('.my_modal').fadeOut();
});

// Posts Page
$('.my_modal .btn-primary').click(function(){
	var serial = $('#form').serialize();
	$.ajax({
		url: 'posts',
		method: 'POST',
		data: serial,
		success:function(data){
			console.log(data);
			if(data.length == 0){
				$('.my_modal').fadeOut();
				$('.my_modal textarea').val('');
			}
			if(data.length > 0)
				$('.my_modal textarea').css('border', '1px solid red');
			showPosts();
		}
	});
});


var postsCounter = 0;
var showPostsTiming = false;
var id = null;
var form = null;
var lastMessage = 0;
var loadingPosts = false;
var loadingComments = false;

function appender(selectors, data) {
	$(selectors).append(data);
	console.log("appends");
}

function prepender(selectors, data) {
	$(selectors).prepend(data);
	console.log("prepends");
}

function showPosts(modifier){
	if(!modifier) {
		modifier = prepender
	}

	if(loadingPosts) {
		return;
	}
	loadingPosts = true;
	var serial = {'postsCounter': postsCounter};

	$.ajax({
		url: 'posts/ajax',
		method: 'GET',
		data: serial,
		success:function(data){
			modifier('.posts .all_posts', data);
			postsCounter = $('.post:first()').attr('last_post');
			
			if(data.length > 0)
				showPostsTiming = true;
			if(showPostsTiming == true){
				$('#postMessages .btn').click(function(){
					id = $(this).parents('.post').attr('last_post');
					form = $(this).parents('form');
					addMessages();
				});
				showPostsTiming = false;
			}
			loadingPosts = false;
		}
	});
}
showPosts(prepender);
var i=0;
setInterval(function() {
	if(i++%2==0) {
		showPosts(appender);
	} else {
		showPosts(prepender);
	}
}, 500);


function addMessages(){
	var serial = form.serialize()+"&last_id="+id;

	$.ajax({
		url:'posts/ajax/messages',
		method: 'POST',
		data: serial,
		success:function(data){
			if(data.length != 0)
				form.find('textarea').css({'border':'2px solid red'}).attr('placeholder', 'დაწერეთ კომენტარი !').blur();
			if(data.length == 0){
				form.find('textarea').val('').css('border','none');
				showMessages();
			}
		}
	});
}

function showMessages(){
	if(loadingComments) {
		return;
	}

	loadingComments = true;

	var serial = {'last_id': lastMessage};
	$.ajax({
		url: 'posts/ajax/messages',
		method: 'GET',
		data: serial,
		success:function(data){
			if(data.length != 0){
				data = JSON.parse(data);
				for(var i = 0;i < data.length; i += 3){
					$(data[i+2]).appendTo('.post[last_post='+data[i].post_id+']');
				}
				lastMessage = data[data.length-2];
			}

			loadingComments = false;
		}
	})
}
showMessages();
setInterval(showMessages, 4000); //ro morchebi es shecvale 1000 ze an rame scrapze upro !!!!!!!!!!!!!!!!

// Posts Page End



