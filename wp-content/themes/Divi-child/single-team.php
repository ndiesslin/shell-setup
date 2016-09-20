<?php
  /**
  * Template Name: Sort Template
  */
  get_header();
?>

<?php get_template_part('template-title');?>

<?php the_meta(); ?>

<?php
  // Loop through options for template
  $checkboxes = get_post_meta( get_the_ID(), 'wpcf-list-template-options', true );
  $optionsArray = array();
  //$options = "";

  foreach ($checkboxes as $checkbox) {
    $optionsArray[] = $checkbox[0];
  }

  //$options = implode(",", $optionsArray);
  echo $optionsArray[0];

  // If use template is not checked then show default page content.
  if ( $optionsArray[0] != 'use-team-template' ) :

    get_template_part('template-divi-code');

  // If use template is checked then do listing and sorting accordingly.
  elseif( $optionsArray[0] == 'use-team-template' ) :

  // Setup loop parameters
  $categoryName = get_post_meta( get_the_ID(), 'wpcf-list-template-category', true );
  $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
  $args = array(
    'post_type' => 'team',
    'meta_key' => 'wpcf-team-last-name',
    'category_name' => $categoryName,
    'meta_query' => array(
      'relation' => 'OR',
      // TODO: figure out how to get featured query to work correctly
      // Check if featured and list.
      // array(
      //   'relation' => 'AND',
      //   array(
      //     'key' => 'wpcf-featured-posts',
      //     'compare' => '='
      //   ),
      //   'team-last-name' => array(
      //     'key' => 'wpcf-team-last-name',
      //   )
      // ),
      // //list any non featured.
      // array(
      //   'relation' => 'AND',
      //   array(
      //     'key' => 'wpcf-featured-posts',
      //     'compare' => 'NOT EXISTS'
      //   ),
      //   'team-last-name' => array(
      //     'key' => 'wpcf-team-last-name',
      //   ),
      // ),
      'team-last-name' => array(
        'key' => 'wpcf-team-last-name',
      ),
     ),
    'orderby' => array(
      'team-last-name' => 'ASC'
    ),
    'order' => 'ASC',
    'posts_per_page' => 6,
    'paged' => $paged
  );

  $wp_query = new WP_Query($args);
  while ( have_posts() ) : the_post(); ?>
  <h2><?php the_title() ?></h2>
  <?php
    // Get thumbnail
    the_post_thumbnail();

    // Get permalink
    the_permalink();
    permalink_anchor($post->ID);
    //echo strip_shortcodes(get_the_content());

    // Get content and strip all shortcodes.
    $content = get_the_content(); // Get post content in $content
    $content = preg_replace("/\[(.*?)\]/i", '', $content);
    $content = strip_tags($content);
    $content = wp_trim_words($content, 27, '...');
    echo $content;
    //wp_trim_words(the_content('test', true), 40, '...');
?>
  <?php //get_post_meta($post->ID, '', true);
    echo $post->ID;
    get_post_meta($post->ID, 'wpcf-team-last-name', true);
    get_field('team-last-name');
    the_meta();
    echo(types_render_field('team-last-name'));
  ?>
<?php endwhile; ?>

<!-- then the pagination links -->
<?php
  echo wp_pagenavi();

  // Restore to default loop.
  wp_reset_query();
?>

<?php
  // Get rest of template, this will include everything below list.
  get_template_part('template-divi-code');

  endif;
?>

<?php get_footer(); ?>
