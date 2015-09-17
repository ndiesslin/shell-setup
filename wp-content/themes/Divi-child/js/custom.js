$=jQuery;
$(document).ready(function() {
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
	
});