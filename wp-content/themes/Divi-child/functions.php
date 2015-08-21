<?php

function theme_enqueue_styles() {
  wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

// Default Script removed from parent since issues with mobile nav came up
function dequeue_parent_script() {
  #wp_deregister_script('divi-custom-script');
  wp_dequeue_script('divi-custom-script');
}
add_action( 'wp_enqueue_scripts', 'dequeue_parent_script', 20 );

function named_scripts() {
  wp_enqueue_script('jquery');
  wp_enqueue_script('jquery-ui-accordion');
  wp_register_script(
    'default_scripts',
    get_stylesheet_directory_uri() . '/js/all.js',
    array('jquery'),
    false,
    true
  );
  #wp_register_script('default_scripts');
  wp_enqueue_script('default_scripts');
}
add_action( 'wp_enqueue_scripts', 'named_scripts' );


function register_secondary_footer() {
  register_nav_menu('secondary-footer-menu',__( 'Secondary Footer Menu' ));
}
add_action( 'init', 'register_secondary_footer' );

include( 'shortcodes.php' );

?>
