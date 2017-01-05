$=jQuery;


function home_slide_img_width() {
	var vpw = window.innerWidth;//window
	if(vpw >= 769) {//769 and above
    var imageWidth = ((2*vpw/3)*1.45)
		$('.elem-large-hover .lh-background').css({'width':imageWidth+'px'})
	}
	else {
		$('.elem-large-hover .lh-background').css({'width':'100%'})
	}
}

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
 	if (Form.input_1_16_2.value != Form.input_1_16.value) {
		alert("Entered email mismatched");
		Form.input_1_16_2.focus();
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
	//removing first link of menu and breadcrumb
	$('#mobile_menu > li > a, #top-menu > li > a, #breadcrumbs > span > span > span > a').attr('href','#');

	//blog
	$('body.archive article').addClass('clearfix');
	//blog comment reply
	$('.comment-reply-link').removeClass('et_pb_button');
	$('.comment-reply-link').parent().removeClass('reply');
	$('.comment-reply-link').removeAttr('onclick')
	$('.comment-reply-link').attr({'target':'_blank'});

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
	$('.homepage-slider').show();
	home_slide_img_width();

	//stories of gratitude
	$('.et_pb_video_play').html('<span class="et-pb-icon et-waypoint et_pb_animation_top et-animated"></span>');

	//list-news
	$('.list-news article').addClass('clearfix');
});
$(window).resize(function() {
	//console.log(vph);
	home_slide_img_width();
});

$('#homepage-slider').on('click', function(e) {
    e.stopPropagation();
});

$(document).on('click', function (e) {
    // $(e.target).children('.large-hover-img').removeClass('active');
});

// image as background
$(document).ready(function(){
	var img_url = $('.img-as-bg .et_pb_blurb_content .et_pb_main_blurb_image img').attr('src');
	var img_url_link = 'url('+ img_url + ')';
	$('.img-as-bg .et_pb_blurb_content .et_pb_main_blurb_image').css({'background-image': img_url_link});
});

// Set blog link in breadcrumb
$(document).ready(function(){
	breadCrumbWrite( 'blog' );
});

function breadCrumbWrite( linkText ) {
	$( '#breadcrumbs a' ).each( function() {
		if ( this.text.toLowerCase() == linkText ) {
			this.setAttribute( 'href', '/' + linkText );
			this.setAttribute( 'style', 'text-decoration: underline !important;' );
		}
	});
};

equalheight = function(container){
  var currentTallest = 0,
       currentRowStart = 0,
       rowDivs = new Array(),
       $el,
       topPosition = 0;

  $(container).each(function() {
   $el = $(this);
   $($el).height('auto')
   topPostion = $el.position().top;
   if (currentRowStart != topPostion) {
     for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
       rowDivs[currentDiv].height(currentTallest);
     }
     rowDivs.length = 0; // empty the array
     currentRowStart = topPostion;
     currentTallest = $el.height();
     rowDivs.push($el);
   } else {
     rowDivs.push($el);
     currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
  }

  for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
   rowDivs[currentDiv].height(currentTallest);
  }
  });
}

$(window).on("load", function() {
  equalheight('.eq-height article .entry-featured-image-url');
});

$( window ).resize(function() {
  equalheight('.eq-height article .entry-featured-image-url');
});

$(document).ready(function() {
  groupToggle();
});

function groupToggle() {
  $('.group-toggle__tab').on( 'click', function() {
    var targetId = $(this).data('toggle-id');
    $('#'+targetId).slideToggle();
    $(this).parent('.group-toggle').toggleClass('group-toggle--toggled');

    // Test if local storage is available
    if (typeof(Storage) !== 'undefined') {
      // Store if the form is toggled or not
      if (localStorage.getItem('study-form-toggle') == 'closed') {
        localStorage.setItem('study-form-toggle', 'open');
      } else {
        localStorage.setItem('study-form-toggle', 'closed');
      }
    }
  });
}

$(document).ready(function() {
  loadToggled();
});

// If toggled, load toggled on next pages
function loadToggled() {
  // Test if local storage is available
  if (typeof(Storage) !== 'undefined') {
    // If user has never been on this page default to toggle open
    if (localStorage.getItem('study-form-toggle') == null) {
      $('.group-toggle__tab').each( function() {
        var targetId = $(this).data('toggle-id');
        $('#'+targetId).removeClass('group-toggle-target--toggled');
        $(this).parent('.group-toggle').removeClass('group-toggle--toggled');
      });
    }
    // If toggle is set to open add class
    if (localStorage.getItem('study-form-toggle') == 'open') {
      $('.group-toggle__tab').each( function() {
        var targetId = $(this).data('toggle-id');
        $('#'+targetId).removeClass('group-toggle-target--toggled');
        $(this).parent('.group-toggle').removeClass('group-toggle--toggled');
      });
    }
  }
}
