$=jQuery;
$(document).ready(function() {
	$('.jcarousel ul div').removeAttr('class'); //removing slideshow class

	//team page
	$('article.team').addClass('clearfix');
	$('article.team .more-link').text('View Profile')
});