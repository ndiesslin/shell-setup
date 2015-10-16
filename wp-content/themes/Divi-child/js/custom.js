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
//gravity form validation
/*$.validator.setDefaults({
  submitHandler: function() {
		alert("submitted!");
	}
});*/

//homepage slider close on tapping outside 
$(document).on('touchstart', function (e) {
var container = $(".elem-large-hover");

    if (!container.is(e.target) // if the target of the click isn't the container...
    && container.has(e.target).length === 0) // ... nor a descendant of the container
        {
            //container.hide();
		$('.large-hover-img').removeClass('active');
		$('.large-hover-img').removeClass('condense');
		$('.lh-reveal').hide();
        }
});

function FormContact_Validator(Form){
 	if (Form.input_1_5.value == "") { 
		alert("Please enter your First Name"); 
		Form.input_1_5.focus(); 	
	 	//$( "#gform_submit_button_1" ).bind( "click", function() {
	  //alert( "User clicked on 'submit.'" );
		//});
		return (false); 
	}
 	if (Form.input_1_6.value == "") { 
		alert("Please enter your Last Name"); 
		Form.input_1_6.focus(); 	
		return (false); 
	}
 	if (Form.input_1_7.value == "") { 
		alert("Please enter your Address"); 
		Form.input_1_7.focus(); 	
		return (false); 
	}
 	if (Form.input_1_9.value == "") { 
		alert("Please enter your City"); 
		Form.input_1_9.focus(); 	
		return (false); 
	}
 	if (Form.input_1_14.value == "") { 
		alert("Please enter your Phone Number"); 
		Form.input_1_14.focus(); 	
		return (false); 
	}
 	if (Form.input_1_16.value == "") { 
		alert("Please enter your Email"); 
		Form.input_1_16.focus(); 	
		return (false); 
	}
 	if (Form.input_1_16_2.value == "") { 
		alert("Please enter your Email"); 
		Form.input_1_16_2.focus(); 	
		return (false); 
	}
 	if (Form.input_1_16_2.value != Form.input_1_16) { 
		alert("Entered email mismatched"); 
		Form.input_1_16_2.focus(); 	
		return (false); 
	}
 	if (Form.input_1_17.value == "" || Form.input_1_17.value != "Yes" || Form.input_1_17.value != "No") { 
		alert("Please enter Yes/No"); 
		Form.input_1_17.focus(); 	
		return (false); 
	}
 	if (Form.input_1_23_1.value == "") { 
		alert("Please enter your Credit Card Number"); 
		Form.input_1_23_1.focus(); 	
		return (false); 
	}
 	if (Form.input_1_23_3.value == "") { 
		alert("Please enter your Verification Security Number"); 
		Form.input_1_23_3.focus(); 	
		return (false); 
	}
 	if (Form.input_1_23_5.value == "") { 
		alert("Please enter your Name on Card"); 
		Form.input_1_23_5.focus(); 	
		return (false); 
	}
	
	//else {
	$('#gform_1').submit();
		//return (true);
	//}
 
}

$(document).ready(function() {
 
$("#gform_submit_button_1").click(function() {
    return FormContact_Validator(gform_1);
   });
 
});
 

$(document).ready(function() {
//alert('test');
	//removing first link of menu and breadcrumb
	$('#mobile_menu > li > a, #top-menu > li > a, #breadcrumbs > span > span > span > a').attr('href','#');
	
	//gravity form validation	
	/*$('#gform_1').validate({ // initialize the plugin
    rules: {
      input_2: {//amount
          required: true,
      },
      input_5: {//first name
          required: true,
          //minlength: 5
      },
      input_6: {//last name
          required: true,
      },
      input_7: {//address
          required: true,
      },
      input_9: {//city
          required: true,
      },
      input_14: {//phone
          required: true,
      },
      input_16: {//email
          required: true,
          email: true,
      },
      input_16_2: {//email
          required: true,
          email: true,
      },
      input_17: {//gift?
          required: true,
      },
      //form.submit();//submits the form even if no information is added.
    }
  });*/



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
// function parentHeight(){
// 	var parentHeight = $('#img-wrap-has-parent-heigh .news-event').height();
// 	var childHeight = $('.list-news-thumb').height();
// 	if (parentHeight > childHeight ) {
// 		$('.list-news-thumb').css({
// 			'min-height': parentHeight
// 		})
// 		// height(parentHeight);
// 	};
// }
$(document).ready(function(){
	// parentHeight();
	windowHeight();
});
$(window).resize(function(){
	// parentHeight();
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

// for whats-new bottom boxes equal size
$(document).ready(function(){
	var winWidth = $(window).width();
	if (winWidth > 480) {
		var blogH = $('.blog-box').parent('.et_pb_column').height();
		$('.newsletter-box').height(blogH);
		$('.box-icon-top-right .et_pb_main_blurb_image img').height(blogH + 40);
		$('.box-icon-top-right .et_pb_main_blurb_image').height(blogH + 40);
	};
});
$(window).resize(function(){
	var winWidth = $(window).width();
	if (winWidth > 480) {
		var blogH = $('.blog-box').parent('.et_pb_column').height();
		$('.newsletter-box').height(blogH);
		$('.box-icon-top-right .et_pb_main_blurb_image img').height(blogH + 40);
		$('.box-icon-top-right .et_pb_main_blurb_image').height(blogH + 40);
	};
});
// for whats-new bottom boxes equal size ends.

// image as background
$(document).ready(function(){
	var this = $('.img-as-bg');
	var url = $('.img-as-bg .et_pb_blurb_content .et_pb_main_blurb_image img').attr('src');
	$('.img-as-bg .et_pb_blurb_content .et_pb_main_blurb_image').css({'background-image': url});
	$('.img-as-bg .et_pb_blurb_content .et_pb_main_blurb_image img').css({'opacity': '0'});

});
