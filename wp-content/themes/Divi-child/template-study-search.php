<?php
/*
Template Name: Study search page
*/

  get_header();
?>

<?php get_template_part('template-title');?>
<?php get_template_part('template-divi-code');?>

<div class=" et_pb_row et_pb_row_0">
  <form method="get" action="">
    <div><label class="screen-reader-text" for="cs">Search</label>
    <input type="text" name="cs" id="cs" />
    <?php
      //wp_dropdown_categories(array('show_option_all' => 'All categories'));
      //wp_dropdown_categories(array('show_option_all' => 'All people', 'name' => 'people', 'taxonomy' => 'people'));
    ?>
    <input type="submit" id="searchsubmit" value="Search" />
    </div>
  </form>
</div>
<?php // echo 'Relevanssi found ' . $wp_query->found_posts . ' hits'; ?>

<?php
  // Default meta query options, we will be adding more items to this array for filtering
  $meta_query = array( 'relation' => 'AND' );

  // Query parameters
  function queryParameters() {
    // Get parameter list
    $params = getParameters();

    // Loop through parameter list
    foreach ($params as $key => $parameterGroup) {
      foreach ($parameterGroup as $parameter) {
        setQueryValue($key, $parameter);
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

    // Set up arguments for query
    $args = array(
      'post_type' => 'our-studies',
      'meta_query' => $meta_query,
    );

    // If items are set push to array to filter by them
    // url to test filter http://mhif-staging.local/our-studies-page/?cs='test'&wpcf-status=Open
    // if(isset($_GET['wpcf-status']) && !empty($_GET['wpcf-status'])){
    //   $status = $_GET['wpcf-status'];
    //   array_push($args, array('key' => 'wpcf-status', 'value' => $status));
    // }

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
  $query->query_vars['s'] = $_GET['cs'];

  // Only search studies
  $query->query_vars['post_type'] = 'our-studies';

  // Limit post count per page
  $query->query_vars['posts_per_page'] = 10;

  // Build relevanssi query, may not be neccesary, but will use for not incase relevanssi has some handy search features
  relevanssi_do_query($query);

  // print out list of results
  $similar = "<ul>";
  foreach ($query->posts as $r_post) {
  if ($r_post->ID == $post->ID) continue;
  $link = get_permalink($r_post->ID);
  $title = get_the_title($r_post->ID);
  $excerpt = $post->post_excerpt;
  $similar .= "<li>$title</li>";
  $similar .= "

  ".$excerpt."

  ";
  $similar .= "

  ".$r_post->ID."

  ";
  }
  $similar .= "</ul>";

  // Echo all results once here
  echo $similar;
?>

<?php get_footer(); ?>
