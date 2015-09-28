<?php 
// Create the new widget area
function wtfdivi003_widget_area() {
   register_sidebar(array(
   'name' => 'Header',
   'id' => 'wtfdivi003-widget-area',
   'before_widget' => '<div id="%1$s" class="et_pb_widget %2$s">',
   'after_widget' => '</div> <!-- end .et_pb_widget -->',
   'before_title' => '<h4 class="widgettitle">',
   'after_title' => '</h4>',
   ));
}
add_action('widgets_init', 'wtfdivi003_widget_area');

function wtfdivi003_add_widget_area_to_footer() { ?>
<div style="display:none"><div id="wtfdivi003-widget-area-wrap"><?php dynamic_sidebar('wtfdivi003-widget-area'); ?></div></div>
<?php
}
add_action('wp_footer', 'wtfdivi003_add_widget_area_to_footer');
?>