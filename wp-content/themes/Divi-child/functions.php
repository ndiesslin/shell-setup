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
body.taxonomy-category span.view, body.taxonomy-post_tag span.view {
display: none !important;
}
/* Hiding unused divi template */
/*.et-pb-all-modules-tab, .et-pb-options-tabs-links li.et-pb-new-module {
  display: none !important;
  opacity: 0 !important;
}
.et-pb-options-tabs-links li a {
  background-color: #8F43EC;
}
.et-pb-saved-modules-tab {
  display: block !important;
  opacity: 1 !important;
  pointer-events: none !important;
}*/
.et-pb-main-settings.et-pb-all-modules-tab .et-pb-load-layouts {
display: none !important;
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

//search
function search_excerpt_highlight() {
 $excerpt = get_the_excerpt();
 //for page that used divi layouts
 if($excerpt == '') {
  echo '<p>';
  truncate_post( 250 );
  echo '</p>';
 }
 $keys = implode('|', explode(' ', get_search_query()));
 $excerpt = preg_replace('/(' . $keys .')/iu', '<strong class="search-highlight">\0</strong>', $excerpt);

 echo '<p>' . $excerpt . '</p>';
}


function search_title_highlight() {
 $title = get_the_title();
 $keys = implode('|', explode(' ', get_search_query()));
 $title = preg_replace('/(' . $keys .')/iu', '<strong class="search-highlight">\0</strong>', $title);

 echo $title;
}

/* Function which remove Plugin Update Notices – Askimet*/
function disable_plugin_updates( $value ) {
   unset( $value->response['types/wpcf.php'] );
   return $value;
}
add_filter( 'site_transient_update_plugins', 'disable_plugin_updates' );


//comments
if ( ! function_exists( 'shape_comment' ) ) :
/**
 * Template for comments and pingbacks.
 */
function shape_comment( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    switch ( $comment->comment_type ) :
        case 'pingback' :
        case 'trackback' :
    ?>
    <li class="post pingback">
        <p><?php _e( 'Pingback:', 'shape' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', 'shape' ), ' ' ); ?></p>
    <?php
            break;
        default :
    ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
        <article id="comment-<?php comment_ID(); ?>" class="comment">
            <footer>
                <div class="comment-author vcard">
                    <?php echo get_avatar( $comment, 40 ); ?>
                    <?php printf( __( '%s <span class="says">says:</span>', 'shape' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
                </div><!-- .comment-author .vcard -->
                <?php if ( $comment->comment_approved == '0' ) : ?>
                    <em><?php _e( 'Your comment is awaiting moderation.', 'shape' ); ?></em>
                    <br />
                <?php endif; ?>
 
                <div class="comment-meta commentmetadata">
                   <time pubdate datetime="<?php comment_time( 'c' ); ?>">
                    <?php
                        /* translators: 1: date, 2: time */
                        printf( __( '%1$s at %2$s', 'shape' ), get_comment_date(), get_comment_time() ); ?>
                    </time>
                    <?php edit_comment_link( __( '(Edit)', 'shape' ), ' ' );
                    ?>
                </div><!-- .comment-meta .commentmetadata -->
            </footer>
 
            <div class="comment-content"><?php comment_text(); ?></div>
 
            <div class="reply">
                <?php comment_reply_link( array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
            </div><!-- .reply -->
        </article><!-- #comment-## -->
 
    <?php
            break;
    endswitch;
}
endif; // ends check for shape_comment()