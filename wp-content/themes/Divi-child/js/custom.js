$=jQuery;
$(document).ready(function() {
	//team page
	$('.list-team-member').parent().parent().addClass('full-width-row team-template');
	//jcarousel
	$('.jcarousel ul div').removeAttr('class'); //removing slideshow class

	//team page
	$('article.team').addClass('clearfix');
	$('article.team .more-link').text('View Profile');

	//homepage-slider
	
	$('.lh-container').click(function() {
		$('.lh-click').css({'opacity':'0'});
	});
	$('.large-hover-img').hover(function() {
	}, function() {
		$('.lh-click').css({'opacity':'1'});
	}
	);

	//professional dashboard
	var top_intro_box_width = $('.top-intro h4').innerWidth();
	$('.top-intro h4').css({
		'margin-left': '-'+top_intro_box_width/2+'px',
		'display': 'block'
	});
	//stories of gratitude
	$('.et_pb_video_play').html('<span class="et-pb-icon et-waypoint et_pb_animation_top et-animated"></span>');
});