"use scrict";
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
