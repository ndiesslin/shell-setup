<?php
// Default meta query options, we will be adding more items to this array for filtering
$meta_query = array( 'relation' => 'AND' );

echo get_query_var('paged');

// Query parameters
function queryParameters() {
  // Get parameter list
  $params = getParameters();

  // Loop through parameter list
  foreach ($params as $key => $parameterGroup) {
    //printf($key); //TODO: make grouped queries possible to setQueryValues for each group like gender
    foreach ($parameterGroup as $parameter) {
      // Make sure we aren't parsing the custom search CS
      if ( $key != 'cs' ) {
        setQueryValue($key, $parameter);
      }
    }
  }
}

// Run query function if any parameters are set
if(count($_GET)) {
  queryParameters();
}

// Send key and value for query options
function setQueryValue($key, $value) {
  // Set meta query as global so it can be manipulated
  global $meta_query;
  // Add item to query
  $meta_query[] = array(
    'key' => $key,
    'value' => $value, // Print all conditions to sort by
    'compare' => 'LIKE',
  );
}

// Filter items that may be set in parameters
function filterItems() {
  // Set meta query as global so it can be manipulated
  global $meta_query;

  // Check for paged
  $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

  // Set up arguments for query
  $args = array(
    'post_type' => 'our-studies',
    'meta_query' => $meta_query,
    'paged' => $paged,
  );

  return $args;
}


// Allow searching without terms https://www.relevanssi.com/knowledge-base/using-relevanssi-without-a-search-term/
add_filter('relevanssi_hits_filter', 'rlv_hits_filter');
function rlv_hits_filter($hits) {

  // Get meta fiters from filter items array
  $args = filterItems();

  //print_r($args);

  if ($hits[0] == null) {
    // no search hits, so must create new
    $hits[0] = get_posts($args);
  }
  else {
    // posts available, take only those that match the conditions
    $ok = array();
    foreach ($hits[0] as $hit) {
      array_push($ok, $hit);
    }
    $hits[0] = $ok;
  }
  return $hits;
}

// Use CS instead of s to set search
if (isset($_GET['cs']) && !empty($_GET['cs'])) {
  $query->query_vars['s'] = $_GET['cs'];
}

// Only search studies
$query->query_vars['post_type'] = 'our-studies';

// Limit post count per page
$query->query_vars['posts_per_page'] = 1;

// Build relevanssi query, may not be neccesary, but will use for not incase relevanssi has some handy search features
relevanssi_do_query($query);

// print out list of results
// Build divs befor layout
$post_list = '
<div class="et_pb_section page et_pb_section_0 et_section_regular">
  <div class="et_pb_row et_pb_row_0">
    <div class="et_pb_column et_pb_column_4_4  et_pb_column_0">
      <div class="et_pb_posts et_pb_module et_pb_bg_layout_light et_pb_blog_0">';

// Query relevanssi search, using query->posts to prevent additional query call
foreach ($query->posts as $r_post) {
  if ($r_post->ID == $post->ID) continue;
  // Set varaibles for posts
  $link = get_permalink($r_post->ID);
  $title = get_the_title($r_post->ID);
  $excerpt = get_the_excerpt($r_post->ID);
  $status = get_post_meta( $r_post->ID, 'wpcf-status', true );
  $studyIdentifier = get_post_meta( $r_post->ID, 'wpcf-study-identifier', true );
  $conditionsArray = get_post_meta( $r_post->ID, 'wpcf-condition', true );
  $conditions = "";
  foreach ($conditionsArray as $value) {
    $conditions .= $value[0].(($value === end($conditionsArray)) ? '' : ', ');
  }
  $snippetLink = 'Read More <i class="fa fa-chevron-right fa--small" aria-hidden="true"></i>';

  // Build each post
  $post_list .= '<div>';
  // Post Title
  $post_list .= '<h3 class="text--purple display-inline text-bold"><a href='.$link.'>'.$title.'</a></h3>';
  // Post identifier and status
  $post_list .= '<p>'.$studyIdentifier.': '.$status.'</p>';
  // Condition[s]
  $post_list .= '<p>'.$conditions.'</p>';
  // Post excerpt
  if (has_excerpt($r_post->ID)) {
    $post_list .= '<p>'.$excerpt.'<br><a href='.$link.'>'.$snippetLink.'</p>';
  } else {
    // Get content and strip all shortcodes.
    $content = get_the_content(); // Get post content in $content
    $content = preg_replace("/\[(.*?)\]/i", '', $content); // strip shortcodes.
    $content = str_replace("&nbsp;", '', $content); // Strip any non breaking spaces.
    $content = trim($content); // Remove whitespace in begining and end.
    $content = wp_trim_words($content, 27, '...'); // Trim content to 27 words and end with ...
    $post_list .= $content;
  }
  $post_list .= '</div>';
}

// Close wrapper around posts
$post_list .= '</div>';
$post_list .= wp_pagenavi();
// TODO: figure out page navigation
//printf(wp_pagenavi());
//print_r(wp_pagenavi( array( 'query' => $query->posts ) ));
//printf(wp_pagenavi( array( 'query' => $query ) ));
$post_list .= '</div></div></div>';

// Echo all results once here
echo $post_list;
