<?php
  /**
  * Template Name: Sort Template
  */

  get_header();

  get_template_part('template-title');

  // Loop through options for template
  $checkboxes = get_post_meta( get_the_ID(), 'wpcf-list-template-options', true );
  $optionsArray = array();

  foreach ($checkboxes as $checkbox) {
    $optionsArray[] = $checkbox[0];
  }

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
    if ( have_posts() ) :
      ?>
      <div class="et_pb_section page et_pb_section_0 et_section_regular">
        <div class="et_pb_row et_pb_row_0 full-width-row team-template">
          <div class="et_pb_column et_pb_column_4_4  et_pb_column_0">
            <div class="et_pb_posts et_pb_module et_pb_bg_layout_light list-team-member et_pb_blog_0">
              <?php
                while ( have_posts() ) : the_post(); ?>
                  <article class="et_pb_post team type-team status-publish has-post-thumbnail hentry category-research-physicians category-team clearfix">
                    <a href="<?php the_permalink(); ?>" class="entry-featured-image-url">
                      <?php the_post_thumbnail('et-pb-post-main-image-thumbnail'); ?>
                    </a>
                    <h2 class="entry-title">
                      <a href="<?php the_permalink(); ?>">
                        <?php the_title(); ?>
                      </a>
                    </h2>
                    <?php
                      // Get content and strip all shortcodes.
                      $content = get_the_content(); // Get post content in $content
                      $content = preg_replace("/\[(.*?)\]/i", '', $content);
                      $content = strip_tags($content);
                      $content = trim($content); // Remove whitespace in begining and end.
                      $content = wp_trim_words($content, 27, '...');
                      echo $content;
                    ?>
                    <a href="<?php the_permalink(); ?>" class="more-link">
                      View Profile
                    </a>
                  </article>
                <?php
                endwhile;
              ?>
            </div>
            <?php
              //Add pagination.
              echo wp_pagenavi();
            ?>
          </div>
        </div>
      </div>
    <?php endif;

    // Restore to default loop.
    wp_reset_query();

    // Get rest of template, this will include everything below list.
    get_template_part('template-divi-code');

  endif;
?>

<?php get_footer(); ?>
