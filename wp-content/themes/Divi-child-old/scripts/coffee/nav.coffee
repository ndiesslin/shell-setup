jQuery(document).ready ->
  jQuery("#mobile_menu .menu-item-has-children").accordion
    animate: 0,
    autoHeight: false,
    collapsible: true,
    active: false
  return
