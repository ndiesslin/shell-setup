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

function move_jquery_to_footer() {
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', '/wp-includes/js/jquery/jquery.js', false, NULL, true );
    wp_register_script( 'jquery-migrate', '/wp-includes/js/jquery/jquery-migrate.js', false, NULL, true );
    wp_enqueue_script( 'jquery-migrate' );
}
add_action( 'wp_enqueue_scripts', 'move_jquery_to_footer' );

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

// Set custom post image sizes for team pages
function team_featured_width( ) { return 216; /* Custom featured post image width */ }
add_filter( 'et_pb_blog_image_width', 'team_featured_width');

function team_featured_height( ) { return 216; /* Custom featured post image height */ }
add_filter( 'et_pb_blog_image_height', 'team_featured_height');

function mycustom_featured_size( $image_sizes ) {
$custom_size = team_featured_width() . 'x' . team_featured_height();
$image_sizes[$custom_size] = 'et-pb-post-team-image-thumbnail';
return $image_sizes;
}

// Set custom post image sizes for blog module pages
function blog_featured_width( ) { return 345; /* Custom featured post image width */ }
add_filter( 'et_pb_blog_image_width', 'blog_featured_width');

function blog_featured_height( ) { return 345; /* Custom featured post image height */ }
add_filter( 'et_pb_blog_image_height', 'blog_featured_height');

function blog_featured_size( $image_sizes ) {
$custom_size = blog_featured_width() . 'x' . blog_featured_height();
$image_sizes[$custom_size] = 'et-pb-post-main-image-thumbnail';
return $image_sizes;
}
add_filter( 'et_theme_image_sizes', 'blog_featured_size' );

// Default WordPress style images
add_image_size( 'team-thumbnail', 216, 216, true );

add_image_size( 'homepage-slider', 2500, 800, true );

//for admin style
add_action('admin_head', 'admin_style');

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
.themes .theme {
  display: none !important;
}
.themes .theme.active {
  display: block !important;
}
/* custom-menu */
#toplevel_page_myplugin-myplugin-admin {
  display: none;
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

// add editor the privilege to edit theme

// get the the role object
$role_object = get_role( 'editor' );

// add $cap capability to this role object
$role_object->add_cap( 'edit_theme_options' );

// add editor the privilede to edit gravity forms
function add_grav_forms(){
    $role = get_role('editor');
    $role->add_cap('gform_full_access');
}
add_action('admin_init','add_grav_forms');


//page slug
function the_slug($echo=true){
  $slug = basename(get_permalink());
  do_action('before_slug', $slug);
  $slug = apply_filters('slug_filter', $slug);
  if( $echo ) echo $slug;
  do_action('after_slug', $slug);
  return $slug;
}

//archives list for custom post type
function my_custom_post_type_archive_where($where,$args){
    $post_type  = isset($args['post_type'])  ? $args['post_type']  : 'blog';
    $where = "WHERE post_type = '$post_type' AND post_status = 'publish'";
    return $where;
}
add_filter( 'getarchives_where','my_custom_post_type_archive_where',10,2);

//archives post
/**
 * Custom post type specific rewrite rules
 * @return wp_rewrite Rewrite rules handled by WordPress
 */
function cpt_rewrite_rules($wp_rewrite)
{
    // Here we're hardcoding the CPT in, article in this case
    $rules = cpt_generate_date_archives('blog', $wp_rewrite);
    $wp_rewrite->rules = $rules + $wp_rewrite->rules;
    return $wp_rewrite;
}
add_action('generate_rewrite_rules', 'cpt_rewrite_rules');

/**
 * Generate date archive rewrite rules for a given custom post type
 * @param  string $cpt slug of the custom post type
 * @return rules       returns a set of rewrite rules for WordPress to handle
 */
function cpt_generate_date_archives($cpt, $wp_rewrite)
{
    $rules = array();

    $post_type = get_post_type_object($cpt);
    $slug_archive = $post_type->has_archive;
    if ($slug_archive === false) {
        return $rules;
    }
    if ($slug_archive === true) {
        // Here's my edit to the original function, let's pick up
        // custom slug from the post type object if user has
        // specified one.
        $slug_archive = $post_type->rewrite['slug'];
    }

    $dates = array(
        array(
            'rule' => "([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})",
            'vars' => array('year', 'monthnum', 'day')
        ),
        array(
            'rule' => "([0-9]{4})/([0-9]{1,2})",
            'vars' => array('year', 'monthnum')
        ),
        array(
            'rule' => "([0-9]{4})",
            'vars' => array('year')
        )
    );

    foreach ($dates as $data) {
        $query = 'index.php?post_type='.$cpt;
        $rule = $slug_archive.'/'.$data['rule'];

        $i = 1;
        foreach ($data['vars'] as $var) {
            $query.= '&'.$var.'='.$wp_rewrite->preg_index($i);
            $i++;
        }

        $rules[$rule."/?$"] = $query;
        $rules[$rule."/feed/(feed|rdf|rss|rss2|atom)/?$"] = $query."&feed=".$wp_rewrite->preg_index($i);
        $rules[$rule."/(feed|rdf|rss|rss2|atom)/?$"] = $query."&feed=".$wp_rewrite->preg_index($i);
        $rules[$rule."/page/([0-9]{1,})/?$"] = $query."&paged=".$wp_rewrite->preg_index($i);
    }
    return $rules;
}

function example_getarchives_where($where)
{
    return str_replace("WHERE post_type = 'post'", "WHERE post_type IN ('blog')", $where);
}
add_filter('getarchives_where', 'example_getarchives_where');

/**
 * Get archives to custom post type
 * @param  string $cpt The wanted custom post type
 * @return array       A list of links to date archives
 */
function cpt_wp_get_archives($cpt)
{
    // Configure the output
    $args = array(
        'format'          => 'option',//custom
        'before'          => '',
        'after'           => '',
        'echo'            => 0,
        'show_post_count' => true
    );
    // Get the post type objest
    $post_type_obj = get_post_type_object($cpt);
    // Slug might not be the cpt name, it might have custom slug, so get it
    $post_type_slug = $post_type_obj->rewrite['slug'];

    // Domain of the current site
    $host = $_SERVER['HTTP_HOST'];
    // Replace `domain.tld` with `domain.tdl/{cpt-slug}`
    //print_r(wp_get_archives($args));exit();
    $output = str_replace($host, "$host/$post_type_slug", wp_get_archives($args));

    return $output;
}

// Disable open sans from loading
if ( ! function_exists( 'et_divi_fonts_url' ) ) :
function et_divi_fonts_url() {
	$fonts_url = '';

	/* Translators: If there are characters in your language that are not
	 * supported by Open Sans, translate this to 'off'. Do not translate
	 * into your own language.
	 */
	$open_sans = _x( 'off', 'Open Sans font: on or off', 'Divi' );

	if ( 'off' !== $open_sans ) {
		$font_families = array();

		if ( 'off' !== $open_sans )
			$font_families[] = 'Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800';

		$protocol = is_ssl() ? 'https' : 'http';
		$query_args = array(
			'family' => implode( '%7C', $font_families ),
			'subset' => 'latin,latin-ext',
		);
		$fonts_url = add_query_arg( $query_args, "$protocol://fonts.googleapis.com/css" );
	}

	return $fonts_url;
}
endif;

//ACF Searchable custom fields - Added to make Grand Rounds entry Searchable//
/**
 * [list_searcheable_acf list all the custom fields we want to include in our search query]
 * @return [array] [list of custom fields]
 */
function list_searcheable_acf(){
  $list_searcheable_acf = array("lecturer");
  return $list_searcheable_acf;
}
/**
 * [advanced_custom_search search that encompasses ACF/advanced custom fields and taxonomies and split expression before request]
 * @param  [query-part/string]      $where    [the initial "where" part of the search query]
 * @param  [object]                 $wp_query []
 * @return [query-part/string]      $where    [the "where" part of the search query as we customized]
 * see https://vzurczak.wordpress.com/2013/06/15/extend-the-default-wordpress-search/
 * credits to Vincent Zurczak for the base query structure/spliting tags section
 */
function advanced_custom_search( $where, &$wp_query ) {
    global $wpdb;

    if ( empty( $where ))
        return $where;

    // get search expression
    $terms = $wp_query->query_vars[ 's' ];

    // explode search expression to get search terms
    $exploded = explode( ' ', $terms );
    if( $exploded === FALSE || count( $exploded ) == 0 )
        $exploded = array( 0 => $terms );

    // reset search in order to rebuilt it as we whish
    $where = '';

    // get searcheable_acf, a list of advanced custom fields you want to search content in
    $list_searcheable_acf = list_searcheable_acf();
    foreach( $exploded as $tag ) :
        $where .= "
          AND (
            (wp_posts.post_title LIKE '%$tag%')
            OR (wp_posts.post_content LIKE '%$tag%')
            OR EXISTS (
              SELECT * FROM wp_postmeta
                WHERE post_id = wp_posts.ID
                  AND (";
        foreach ($list_searcheable_acf as $searcheable_acf) :
          if ($searcheable_acf == $list_searcheable_acf[0]):
            $where .= " (meta_key LIKE '%" . $searcheable_acf . "%' AND meta_value LIKE '%$tag%') ";
          else :
            $where .= " OR (meta_key LIKE '%" . $searcheable_acf . "%' AND meta_value LIKE '%$tag%') ";
          endif;
        endforeach;
          $where .= ")
            )
            OR EXISTS (
              SELECT * FROM wp_comments
              WHERE comment_post_ID = wp_posts.ID
                AND comment_content LIKE '%$tag%'
            )
            OR EXISTS (
              SELECT * FROM wp_terms
              INNER JOIN wp_term_taxonomy
                ON wp_term_taxonomy.term_id = wp_terms.term_id
              INNER JOIN wp_term_relationships
                ON wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id
              WHERE (
              taxonomy = 'post_tag'
                OR taxonomy = 'category'
                OR taxonomy = 'myCustomTax'
              )
                AND object_id = wp_posts.ID
                AND wp_terms.name LIKE '%$tag%'
            )
        )";
    endforeach;
    return $where;
}

add_filter( 'posts_search', 'advanced_custom_search', 500, 2 );

//Make WPCF_TYPE in REST CALL
add_filter( 'wpcf_type', 'add_show_in_rest_func', 510, 2);
function add_show_in_rest_func($data, $post_type) {
    if($post_type == 'grand-rounds-lecture'){
        $data['show_in_rest'] = true;
    }
    return $data;
}

// Disable redirect canoical for post types that need to use list template on single page
function custom_disable_redirect_canonical( $redirect_url ){
    global $post;
    $ptype = get_post_type( $post );
    if ( $ptype == 'team' || $ptype == 'our-studies' ) $redirect_url = false;
    return $redirect_url;
}
add_filter( 'redirect_canonical','custom_disable_redirect_canonical' );

// Fix html validations errors and yoast https://www.bybe.net/wordpress-yoastseos-breadcrumb-html-google-fix/
add_filter ('wpseo_breadcrumb_output','bybe_crumb_v_fix');
function bybe_crumb_v_fix ($link_output) {
  $link_output = preg_replace(array('#<span xmlns:v="http://rdf.data-vocabulary.org/\#">#','#<span typeof="v:Breadcrumb"><a href="(.*?)" .*?'.'>(.*?)</a></span>#','#<span typeof="v:Breadcrumb">(.*?)</span>#','# property=".*?"#','#</span>$#'), array('','<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="$1" itemprop="url"><span itemprop="title">$2</span></a></span>','<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">$1</span></span>','',''), $link_output);
  return $link_output;
}

add_filter( 'wpseo_breadcrumb_single_link', 'bybe_crumb_fix' , 10, 2 );
function bybe_crumb_fix( $output, $crumb ){
  if ( is_array( $crumb ) && $crumb !== array() ) {               
    if( strpos( $output, '<span class="breadcrumb_last"' ) !== false  ||   strpos( $output, '<strong class="breadcrumb_last"' ) !== false ) { 
      $output = '<a property="v:title" rel="v:url" href="'. $crumb['url']. '" >';
      $output.= $crumb['text'];
      $output.= '</a>';
    }
  }
  return $output;
}

// Fix for seach and yoast working together see this for more info http://www.relevanssi.com/knowledge-base/yoast-local-seo-compatibility-issues/
add_filter('relevanssi_modify_wp_query', 'rlv_meta_fix', 99);
function rlv_meta_fix($q) {
	$q->set('meta_query', '');
	return $q;
}

// Functions used for study search

// Strip shortcodes from relevanssi results
add_filter('relevanssi_excerpt_content', 'rlv_strip_shortcodes');
function rlv_strip_shortcodes($excerpt) {
    return preg_replace('~\\[[^]]*\\]~', '', $excerpt);
}

// Change ellipses at begining and end of search results
add_filter('relevanssi_ellipsis', 'excerpt_function', 10, 3);
function excerpt_function($content) {
  $content = "..."; // This can be whatever is needed for beginning and end ellipses
	return $content;
}

// Function to check for keys in array and multidimenstional arrays
// http://stackoverflow.com/questions/4128323/in-array-and-multidimensional-array
function in_array_r($keyword, $array, $strict = false, $search_key) {
  foreach ($array as $key => $item) {
    if (($strict ? $item === $keyword : $item == $keyword) || (is_array($item) && in_array_r($keyword, $item, $strict)) || (is_array($item) && in_array_r($search_key, $item, $strict))) {
      return true;
    }
  }

  return false;
}

// Function to get all parameters in url
function getParameters() {
  $query  = explode('&', $_SERVER['QUERY_STRING']);
  $params = array();

  foreach( $query as $param ) {
    list($name, $value) = explode('=', $param, 2);
    $params[urldecode($name)][] = urldecode($value);
  }

  return $params;
}

// End functions for study search

// Add app icons to homescreen
add_action('wp_head','app_icons');
function app_icons() {
  $link_beginning = '<link rel="apple-touch-icon" type="image/png" href="';

  $output = '';
  $output .= $link_beginning . get_stylesheet_directory_uri().'/images/touch-icons/logo-57.png' . '" sizes="57x57" />';
  $output .= $link_beginning . get_stylesheet_directory_uri().'/images/touch-icons/logo-76.png' . '" sizes="76x76" />';
  $output .= $link_beginning . get_stylesheet_directory_uri().'/images/touch-icons/logo-120.png' . '" sizes="120x120" />';
  $output .= $link_beginning . get_stylesheet_directory_uri().'/images/touch-icons/logo-120.png' . '" sizes="152x152" />';
  $output .= $link_beginning . get_stylesheet_directory_uri().'/images/touch-icons/logo-180.png' . '" sizes="180x180" />';
  $output .= $link_beginning . get_stylesheet_directory_uri().'/images/touch-icons/logo-192.png' . '" sizes="192x192" />';
  $output .= '<link rel="icon" sizes="192x192" href="' . get_stylesheet_directory_uri(). '/images/touch-icons/logo-192.png">';
  $output .= '<link rel="shortcut icon" type="image/png" href="' . get_stylesheet_directory_uri() . '/images/touch-icons/logo-32.png" />';

	echo $output;
}
