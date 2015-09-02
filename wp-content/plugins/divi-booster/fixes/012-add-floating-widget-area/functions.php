<?php 
// Create the new widget area
function wtfdivi012_widget_area() {
   register_sidebar(array(
   'name' => 'Sticky Sidebar (Left)',
   'id' => 'wtfdivi012-widget-area',
   'before_widget' => '<div id="%1$s" class="wtfdivi012_widget %2$s">',
   'after_widget' => '</div>',
   'before_title' => '<h4 class="widgettitle">',
   'after_title' => '</h4>',
   ));
}
add_action('widgets_init', 'wtfdivi012_widget_area');

function wtfdivi012_add_widget_area_to_footer() { ?>
<div id="wtfdivi012-widget-area-wrap"><?php dynamic_sidebar('wtfdivi012-widget-area'); ?></div>
<?php
}
add_action('wp_footer', 'wtfdivi012_add_widget_area_to_footer');
?>