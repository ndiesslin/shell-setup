<?php

#this is an example of a shortcode called like this [shortcode_example]
function example_shortcode_function() {
  $content_exists = false;
  query_posts(array('orderby' => 'date', 'order' => 'DESC' , 'showposts' => 1));
  if (have_posts()) :
    while (have_posts()) : the_post();
       $return_string = '<a href="'.get_permalink().'">'.get_the_title().'</a>';
    endwhile;
  endif;

  wp_reset_query();
  return $return_string;
}

function fullwidth_button_shortcode( $atts, $content = "null" ) {
  extract( shortcode_atts( array(
      'href' => 'href',
      ), $atts ) );
  $return_string = '<a class="full-width-link" href="' . $href . '">' . $content . '</a>';
  return $return_string;
}

function register_shortcodes(){
  add_shortcode( 'shortcode_example', 'example_shortcode_function' );
  add_shortcode('fullwidth_button', 'fullwidth_button_shortcode');
}
add_action( 'init', 'register_shortcodes');


add_action( 'admin_menu', 'register_my_custom_menu_page' );

function register_my_custom_menu_page() {

	add_menu_page( 'custom menu title', 'custom menu', 'manage_options', 'myplugin/myplugin-admin.php', '', '', 6 );

}
