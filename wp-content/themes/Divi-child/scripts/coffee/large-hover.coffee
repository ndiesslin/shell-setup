"use scrict"

jQuery(document).ready ->
  # jQuery('.large-hover-img').hover ->
  #   jQuery('.large-hover-img').removeClass 'active'
  #   jQuery(this).addClass 'active'
  #   return

  # jQuery('.large-hover-img').on
  #   mouseenter: ->
  #     console.log "mouseenter"
  #     jQuery('.large-hover-img').removeClass 'active'
  #     jQuery('.large-hover-img').addClass 'condense'
  #     jQuery(this).removeClass 'condense'
  #     jQuery(this).addClass 'active'
  #     # jQuery(this).prev().animate {width: '15%'}, 'fast'
  #     # jQuery(this).animate {width: '30%'}, 'fast'
  #     # jQuery(this).next().animate {width: '15%'}, 'fast'
  #     return
  #   mouseleave: ->
  #     console.log "mouseleave"
  #     jQuery(this).removeClass 'active'
  #     jQuery('.large-hover-img').removeClass 'condense'
  #     # jQuery(this).prev().animate {width: '20%'}, 'fast'
  #     # jQuery(this).animate {width: '20%'}, 'fast'
  #     # jQuery(this).next().animate {width: '20%'}, 'fast'
  #     return
  # return

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
      # logTop = jQuery('.lh-table').offset().top
      # logHidden = jQuery('.lh-reveal').height();
      # matchHeight = (logHidden - logTop - logTop)
      # console.log logTop
      # console.log logHidden
      # console.log matchHeight
      jQuery(this).children('.lh-reveal').slideDown();
      return
      ),

  jQuery('.elem-large-hover').ready ->
    largeHoverInit();

  return
