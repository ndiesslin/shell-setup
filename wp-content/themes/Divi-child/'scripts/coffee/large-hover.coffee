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
