<?php
// Default meta query options, we will be adding more items to this array for filtering
$meta_query = array( 'relation' => 'AND' );

// Set page id so filterItems gets correct id to hide
$pageId = get_the_ID();

// Query parameters
function queryParameters() {
  // Get parameter list
  $params = getParameters();

  // Loop through parameter list
  foreach ($params as $key => $parameter_group) {
    // Make sure we aren't parsing the custom search CS
    if ( $key != 'cs' ) {
      // Call function to put grouped items in an array. We want each group to be seperate for the AND relation
      setQueryValue($key, $parameter_group);
    }
  }
}

// Run query function if any parameters are set
if (count($_GET)) {
  queryParameters();
}

// Send key and value for query options
function setQueryValue($key, $parameter_group) {
  // Set or relation inside of query group
  $query_group = array( 'relation' => 'OR' );

  foreach ($parameter_group as $parameter) {
    if ( $key != 'cs' ) {
      // Add item to query
      $query_group[] = array(
        'key' => $key,
        'value' => $parameter, // Print all conditions to sort by
        'compare' => 'LIKE',
      );
    }
  }

  // Set meta query as global so it can be manipulated
  global $meta_query;

  // Add query group to main query
  $meta_query[] = $query_group;
}

// Filter items that may be set in parameters
function filterItems() {
  // Set meta query as global so it can be manipulated
  global $meta_query;

  // Set page id as global so filterItems gets correct id to hide
  global $pageId;

  // Set up arguments for query
  $args = array(
    // Exclude page listing posts
    'post__not_in' => array($pageId),
    'post_type' => 'our-studies',
    'meta_query' => $meta_query,
    // TODO: figure out what to order things by
    // 'orderby' => array(
    //   'team-last-name' => 'ASC'
    // ),
    'order' => 'ASC',
  );

  return $args;
}

//Allow searching without terms https://www.relevanssi.com/knowledge-base/using-relevanssi-without-a-search-term/

// First allow searching by meta query
add_filter('relevanssi_search_ok', 'search_trigger');
function search_trigger($search_ok) {
	global $query;
	if (!empty($query->query_vars['meta_query'])) {
		$search_ok = true;
	}
	return $search_ok;
}

// Then send variables to search by
add_filter('relevanssi_hits_filter', 'rlv_hits_filter');
function rlv_hits_filter($hits) {
  if ($hits[0] == null) {
  	// no search hits, so must create new
    $args = filterItems();
  	$hits[0] = get_posts($args);
  }
  return $hits;
}
// End search without terms

// Initialize default query, Relevanssi builds off of this
$query = new WP_Query();

// Order TODO: figure out what this should be and if it's needed, WordPress defaults to post_date which should be fine
// $query->query_vars['order'] = 'ASC';
// $query->query_vars['orderby'] = 'title';

// Only search studies
$query->query_vars['post_type'] = 'our-studies';

// Limit post count per page
$query->query_vars['posts_per_page'] = 4;

// Get meta query
$meta_args = filterItems();
$query->query_vars['meta_query'] = $meta_args;

// Only use search if it is defined
if (isset($_GET['cs']) && !empty($_GET['cs'])) {
  $query->query_vars['s'] = $_GET['cs'];
}

// Enable paged for relevanssi
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$query->query_vars['paged'] = $paged;

// Build relevanssi query, may not be neccesary, but will use for not incase relevanssi has some handy search features
relevanssi_do_query($query);

// Build list of results
// Build divs before layout
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
  $excerpt = do_shortcode( get_the_excerpt($r_post->ID) );
  $status = get_post_meta( $r_post->ID, 'wpcf-status', true );
  $study_identifier = get_post_meta( $r_post->ID, 'wpcf-study-identifier', true );
  $specific_condition = get_post_meta( $r_post->ID, 'wpcf-specific-condition', true );
  $conditions_array = get_post_meta( $r_post->ID, 'wpcf-condition', true );
  $conditions = "";
  foreach ($conditions_array as $value) {
    $conditions .= $value[0].(($value === end($conditions_array)) ? '' : ', ');
  }
  $description = do_shortcode( get_post_meta( $r_post->ID, 'wpcf-description', true ) );
  $snippet_link = 'Read More <i class="fa fa-chevron-right fa--small" aria-hidden="true"></i>';

  // Build each post
  $post_list .= '<div class="margin-bottom--50">';
  // Post Title
  $post_list .= '<h3 class="text--purple margin-bottom--12 text-bold"><a href='.$link.' class="a--hover">'.$title.'</a></h3>';
  // Post identifier and status
  //$post_list .= '<p class="padding-bottom--8">'.$study_identifier.': '.$status.'</p>';
  // Condition[s]
  // Check if specific condition is specified
  if ( $specific_condition ) {
    $post_list .= '<p class="padding-bottom--8">'.$specific_condition.'</p>';
  } else {
    $post_list .= '<p class="padding-bottom--8">'.$conditions.'</p>';
  }
  // Post excerpt/ description/ or content
  if (has_excerpt($r_post->ID)) {
    $post_list .= '<p class="padding-bottom--8">'.$excerpt.'<br><a href='.$link.'>'.$snippet_link.'</a></p>';
  }
  elseif($description) {
    $post_list .= '<p class="padding-bottom--8">'.$description.'<br><a href='.$link.'>'.$snippet_link.'</a></p>';
  }
  else {
    // Get content and strip all shortcodes.
    $content = do_shortcode( get_the_content() ); // Get post content in $content
    $content = preg_replace("/\[(.*?)\]/i", '', $content); // strip shortcodes.
    $content = str_replace("&nbsp;", '', $content); // Strip any non breaking spaces.
    $content = trim($content); // Remove whitespace in begining and end.
    $content = wp_trim_words($content, 27, '...'); // Trim content to 27 words and end with ...
    $post_list .= '<p class="padding-bottom--8">'.$content.'<br><a href='.$link.'>'.$snippet_link.'</a></p>';
  }
  $post_list .= '</div>';
  //$post_list .= wp_pagenavi(array( 'query' => $query ));
}

// Post count
if ($query->found_posts == 0) {
  $post_list .= '<br><br><p>'.$query->found_posts.' results found.</p>';
}

// Close wrapper around posts
$post_list .= '</div></div></div>';

// Echo all results once here
echo $post_list;

// Pagination section
?>

<div class="et_pb_section page et_pb_section_0 et_section_regular">
  <div class="et_pb_row et_pb_row_0">
    <div class="et_pb_column et_pb_column_4_4  et_pb_column_0">
      <div class="et_pb_posts et_pb_module et_pb_bg_layout_light et_pb_blog_0">
        <?php wp_pagenavi(array( 'query' => $query )); ?>
      </div>
    </div>
  </div>
</div>

<script>
  jQuery( function() {
    correctPagination();
  });

  // Rewrite page navi urls to include all parameters, we are unable to manipulate them through wp_pagenavi's php
  function correctPagination() {
    // Check if url has parameters
    var windowUrl = window.location.href;
    if (windowUrl.includes('?')) {
      jQuery('.wp-pagenavi a').each( function() {
        var linkUrl = jQuery(this).attr('href').split('?')[0],
            parameters = '?'+windowUrl.split('?')[1],
            combinedUrl = linkUrl+parameters;

        jQuery(this).attr('href', combinedUrl);
      });
    }
  }
</script>


<?php
wp_reset_postdata(); // avoid errors further down the page
