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
<?php echo 'Relevanssi found ' . $wp_query->found_posts . ' hits'; ?>

<?php
  // Build query array with default options
  $query_array = array(
    'relation' => 'AND',
    array(
        'key' => 'wpcf-condition',
        'value' => 'Arrhythmia', 'Heart Failure',
        'compare' => 'LIKE',
    ),
    array(
        'key' => 'wpcf-gender',
        'value' => 'Male',
        'compare' => 'LIKE',
    ),
    array(
        'key' => 'wpcf-age-range',
        'value' => '18-20',
        'compare' => 'LIKE',
    ),
  );

  // If items are set push to array to filter by them
  // url to test filter http://mhif-staging.local/our-studies-page/?cs='test'&wpcf-status=Open
  if(isset($_GET['wpcf-status']) && !empty($_GET['wpcf-status'])){
    $status = $_GET['wpcf-status'];
    array_push($query_array, array('key' => 'wpcf-status', 'value' => $status));
  }

  // Use CS instead of s to set search
  $query->query_vars['s'] = $_GET['cs'];
  $query->query_vars['post_type'] = 'our-studies';
  $query->query_vars['posts_per_page'] = 10;
  $query->query_vars['meta_query'] = $query_array;
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
