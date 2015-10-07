$=jQuery;


function home_slide_img_width() {
	var vpw = $(document).width();//window
	//console.log(vpw);
	if(vpw >= 769) {//769 and above
		$('.elem-large-hover .lh-background').css({'width':2*vpw/3+'px'})
	}
	else {
		$('.elem-large-hover .lh-background').css({'width':'100%'})
	}
}

$(document).ready(function() {
	//blog 
	$('body.archive article').addClass('clearfix');
	//blog comment reply
	$('.comment-reply-link').removeClass('et_pb_button');
	$('.comment-reply-link').parent().removeClass('reply');
	$('.comment-reply-link').removeAttr('onclick')
	$('.comment-reply-link').attr({'target':'_blank'});

	//$('.top-stories h2').append(year);
	
	//team page
	$('.list-team-member').parent().parent().addClass('full-width-row team-template');
	//jcarousel
	$('.jcarousel ul > div').removeAttr('class'); //removing slideshow class
	//medium thumb parent div in jcarousel
	$('.medium-thumb').parent().parent().css({'width': '100%'});//a and then div

	//team page
	$('article.team').addClass('clearfix');
	$('article.team .more-link').text('View Profile');

	//homepage-slider
	if($(window).width() >= 768) {
		//$('.jssor-slider').css({'display':'block !important'});	
	}
	$('.homepage-slider').show();	
	home_slide_img_width();
	/*$('.lh-container').click(function() {
		$('.lh-click').css({'opacity':'0'});
	});
	$('.large-hover-img').hover(function() {
	}, function() {
		$('.lh-click').css({'opacity':'1'});
	}
	);*/

	//professional dashboard
	// var top_intro_box_width = $('.top-intro h4').innerWidth();
	// $('.top-intro h4').css({
	// 	'margin-left': '-'+top_intro_box_width/2+'px',
	// 	'display': 'block'
	// });

	//stories of gratitude
	$('.et_pb_video_play').html('<span class="et-pb-icon et-waypoint et_pb_animation_top et-animated"></span>');

	//list-news
	$('.list-news article').addClass('clearfix');
});
$(window).resize(function() {
	//console.log(vph);
	home_slide_img_width();
});

// For equal height
function eq_height_div(){
	$listItem = $('.eq-height div article');
	maxValue = findMaxValue($listItem);
	$listItemImg = $('img',$listItem);
	maxImgHeight = findMaxValue($listItemImg);
	$listItem.each(function(){
		$(this).css("height",maxValue);
		var imageHeight = $('img',this).height();
		var imageMargin = maxImgHeight - imageHeight;
		$('img',this).css("margin-bottom",imageMargin);
	});
}
jQuery(document).ready(function (){
	eq_height_div();
});
jQuery(window).resize(function (){
	eq_height_div();
});
function findMaxValue(element){
	var maxValue = undefined;
	$(element).each(function(){
		var val = parseInt($(this).height());
		if (maxValue === undefined || maxValue < val) {
			maxValue = val;
		}
	});
	return maxValue;
}

// parent height
function parentHeight(){
	var parentHeight = $('#img-wrap-has-parent-heigh .news-event').height();
	var childHeight = $('.list-news-thumb').height();
	if (parentHeight > childHeight ) {
		$('.list-news-thumb').css({
			'min-height': parentHeight
		})
		// height(parentHeight);
	};
}
$(document).ready(function(){
	parentHeight();
	windowHeight();
});
$(window).resize(function(){
	parentHeight();
	windowHeight();
});

// calculate window height for banner
function windowHeight(){
	// var windowHeight = $(window).height();

	// if (windowHeight > 1000) {
	// 	$('.homepage-slider').height(windowHeight/100*70);
	// 	$('.elem-large-hover .large-hover-img').height(windowHeight/100*70);
	// }
	// else if(windowHeight < 700){
	// 	$('.homepage-slider').height(500);
	// 	$('.elem-large-hover .large-hover-img').height(500);
	// }

}

$('#homepage-slider').on('click', function(e) {
    e.stopPropagation();
});

$(document).on('click', function (e) {
    // $(e.target).children('.large-hover-img').removeClass('active');
});	
