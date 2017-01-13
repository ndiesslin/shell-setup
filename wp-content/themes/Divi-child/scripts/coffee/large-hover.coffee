"use scrict"

jQuery(document).ready ->
  largeHoverInit = ->
    jQuery('.lh-reveal').hide();
    jQuery('.large-hover-img').hover (->
        jQuery('.large-hover-img').removeClass 'active'
        jQuery('.large-hover-img').addClass 'condense'
        jQuery(this).removeClass 'condense'
        jQuery(this).addClass 'active'
        return
      ),
      ->
        jQuery(this).removeClass 'active'
        jQuery('.large-hover-img').removeClass 'condense'
        jQuery(this).find('.lh-reveal').slideUp();
        return

    jQuery('.lh-container').click (->
      jQuery(this).children('.lh-reveal').slideDown();
      return
    ),

  jQuery('.elem-large-hover').ready ->
    largeHoverInit();

  return

# Homepage slider close on tapping outside
jQuery(document).on 'touchstart', (e) ->
  container = $('.elem-large-hover')
  if !container.is(e.target) and container.has(e.target).length == 0
    $('.large-hover-img').removeClass 'active'
    $('.large-hover-img').removeClass 'condense'
    $('.lh-reveal').hide()
  return

# Set image width accordingly
home_slide_img_width = ->
  vpw = window.innerWidth
  #window
  if vpw >= 769
    #769 and above
    imageWidth = 2 * vpw / 3 * 1.45
    $('.elem-large-hover .lh-background').css 'width': imageWidth + 'px'
  else
    $('.elem-large-hover .lh-background').css 'width': '100%'
  return

jQuery(document).ready ->
  home_slide_img_width();

jQuery(window).resize ->
  home_slide_img_width()
  return
