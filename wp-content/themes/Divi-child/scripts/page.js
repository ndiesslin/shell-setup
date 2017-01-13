$=jQuery;

$(document).ready(function() {
	//removing first link of menu and breadcrumb
	$('#mobile_menu > li > a, #top-menu > li > a, #breadcrumbs > span > span > span > a').attr('href','#');

  // TODO: figure out if blog stuff is needed, currently it seems like there aren't comments on the blog so this might not be used.
	//blog
	$('body.archive article').addClass('clearfix');

	//blog comment reply
	$('.comment-reply-link').removeClass('et_pb_button');
	$('.comment-reply-link').parent().removeClass('reply');
	$('.comment-reply-link').removeAttr('onclick')
	$('.comment-reply-link').attr({'target':'_blank'});

	//jcarousel
  $('.jcarousel ul > div').removeAttr('class'); //removing slideshow class

	//medium thumb parent div in jcarousel
	$('.medium-thumb').parent().parent().css({'width': '100%'});//a and then div

	//list-news
	$('.list-news article').addClass('clearfix');
});

// image as background
$(document).ready(function(){
	var img_url = $('.img-as-bg .et_pb_blurb_content .et_pb_main_blurb_image img').attr('src');
	var img_url_link = 'url('+ img_url + ')';
	$('.img-as-bg .et_pb_blurb_content .et_pb_main_blurb_image').css({'background-image': img_url_link});
});
