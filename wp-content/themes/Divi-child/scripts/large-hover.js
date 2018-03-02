"use scrict";
var home_slide_img_width;

jQuery(document).ready(function() {
  var largeHoverInit;
  largeHoverInit = function() {
    jQuery('.lh-reveal').hide();
    jQuery('.large-hover-img').hover((function() {
      jQuery('.large-hover-img').removeClass('active');
      jQuery('.large-hover-img').addClass('condense');
      jQuery(this).removeClass('condense');
      jQuery(this).addClass('active');
    }), function() {
      jQuery(this).removeClass('active');
      jQuery('.large-hover-img').removeClass('condense');
      jQuery(this).find('.lh-reveal').slideUp();
    });
    return jQuery('.lh-container').click((function() {
      jQuery(this).children('.lh-reveal').slideDown();
    }));
  };
  jQuery('.elem-large-hover').ready(function() {
    return largeHoverInit();
  });
});

// Homepage slider close on tapping outside
jQuery(document).on('touchstart', function(e) {
  var container;
  container = $('.elem-large-hover');
  if (!container.is(e.target) && container.has(e.target).length === 0) {
    $('.large-hover-img').removeClass('active');
    $('.large-hover-img').removeClass('condense');
    $('.lh-reveal').hide();
  }
});

// Set image width accordingly
home_slide_img_width = function() {
  var imageWidth, vpw;
  vpw = window.innerWidth;
  //window
  if (vpw >= 769) {
    //769 and above
    imageWidth = 2 * vpw / 3 * 1.45;
    $('.elem-large-hover .lh-background').css({
      'width': imageWidth + 'px'
    });
  } else {
    $('.elem-large-hover .lh-background').css({
      'width': '100%'
    });
  }
};

jQuery(document).ready(function() {
  return home_slide_img_width();
});

jQuery(window).resize(function() {
  home_slide_img_width();
});
