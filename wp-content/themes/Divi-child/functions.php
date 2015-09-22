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



function mycustom_featured_width( ) { return 320; /* Custom featured post image width */ }
add_filter( 'et_pb_blog_image_width', 'mycustom_featured_width');

function mycustom_featured_height( ) { return 260; /* Custom featured post image height */ }
add_filter( 'et_pb_blog_image_height', 'mycustom_featured_height');

function mycustom_featured_size( $image_sizes ) {
$custom_size = mycustom_featured_width() . 'x' . mycustom_featured_height();
$image_sizes[$custom_size] = 'et-pb-post-main-image-thumbnail';
return $image_sizes;
}
add_filter( 'et_theme_image_sizes', 'mycustom_featured_size' );

//for admin style
add_action('admin_head', 'admin_style');

function admin_style() {
  echo '<style>
    #et_pb_toggle_builder.et_pb_builder_is_used {/* use default editor */
      display: none !important;
    }
    .control-section#add-page {
      display: block !important;
    }
  </style>';
}

//title
function page_title($title, $title_icon, $team_title) {
  echo '<div class="et_pb_section et_pb_fullwidth_section  et_pb_section_0 et_section_regular"> 
    <section class="et_pb_fullwidth_header et_pb_module et_pb_bg_layout_dark et_pb_text_align_left page-title et_pb_fullwidth_header_0">
    
      <div class="et_pb_fullwidth_header_container left">
        <div class="header-content-container center">
        <div class="header-content">
          <h1>';
          if($title_icon != '')
            echo $title_icon;
          
          echo $title;

          if($team_title != '')
            echo ', ' . $team_title;
          echo '</h1>        
        </div>
      </div>
        
      </div>
      <div class="et_pb_fullwidth_header_overlay"></div>
      <div class="et_pb_fullwidth_header_scroll"></div>
    </section>    
  </div>';
}

function get_all_posts_name() {
  $posts_name = array('post','news-events', 'team');
  return $posts_name;
}