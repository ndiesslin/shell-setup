<?php
// Shortcode for post items
function getPost( $atts ) {
  global $post; // set global variable for posts

  extract(shortcode_atts(array(
    'post_type' => 'post',
    'order' => 'DESC',
    'offset' => 1,
    'h2_class' => '',
    'category_name' => ''
  ), $atts));


  // Get most recent event
  $args = array(
    'post_type' => $post_type,
    'orderby' => 'date',
    'order' => 'DESC',
    'posts_per_page' => 1,
    'offset' => $offset,
    'category_name' => $category_name,
  );

  $loop = new WP_Query($args);
  $item_loop = "";
  while ( $loop->have_posts() ) : $loop->the_post();
    $item_loop.='
      <h2 class="'.$h2_class.'">
        <a href="'.get_permalink($post->ID).'">
        '.get_the_title($post->ID).'
      </a>
    </h2>';
    if (has_excerpt()) {
      $item_loop.='<p>'.get_the_excerpt().'</p>';
    }
    // If there is no excerpt, grab the content
    else {
      $item_loop.='<p>';
      // Get content and strip all shortcodes.
      $content = get_the_content(); // Get post content in $content
      $content = preg_replace("/\[(.*?)\]/i", '', $content); // strip shortcodes.
      $content = str_replace("&nbsp;", '', $content); // Strip any non breaking spaces.
      $content = trim($content); // Remove whitespace in begining and end.
      $content = wp_trim_words($content, 27, '...'); // Trim content to 27 words and end with ...
      $item_loop.= $content; // Add content to loop.
      $item_loop.= '</p>';
    }
  endwhile;

  // Return it all
  return($item_loop);

  // Restore to default loop.
  wp_reset_query();
}
add_shortcode("getPost", "getPost");
