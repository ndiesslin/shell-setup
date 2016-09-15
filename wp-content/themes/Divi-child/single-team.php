<?php
  /**
  * Template Name: Sort Template
  */
  get_header(); 
  echo "single template";
?>

<?php get_template_part('template-title');?>

<?php the_meta(); ?>

<?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = array( 
  'post_type' => 'team',
  'meta_key' => 'wpcf-team-last-name',
  'category_name' => 'research-physicians',
  'orderby' => 'meta_value',
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
/*$maxnum = 999999999; // need an unlikely integer*/

//echo paginate_links( array(
    //'base' => str_replace( $maxnum, '%#%', esc_url( get_pagenum_link( $maxnum ) ) ),
    //'format' => '?paged=%#%',
    //'current' => max( 1, get_query_var('paged') ),
    //'total' => $wp_query->max_num_pages,
    ////'end_size' => 4,    
    //'next_or_number'=>'next'
/*) );*/

echo wp_pagenavi();
?>

<?php $Disclaimer = get_post_meta($post->ID, 'Disclaimer', true); ?>
<div id="content">
  <?php
		$args=array(
			'name' => 'specials',
			'post_type' => 'page',
			'post_status' => 'publish',
			'posts_per_page' => 1,
			'ignore_sticky_posts'=> 1
		);
		$my_query = null;
		$my_query = new WP_Query($args);
		if( $my_query->have_posts() ) {
		while ($my_query->have_posts()) : $my_query->the_post();
			the_content();
		endwhile;
		}
		wp_reset_query();  // Restore global post data stomped by the_post().
	?>
  <div class="container et_pb_section clearfix">
    <?php
      /*$post_type = 'special';*/
      //include(locate_template('includes/loops/category-loop.php'));
      /*include(locate_template('includes/loops/specials-loop.php'));*/
    ?>
    <!-- Disclaimer -->
    <p class="disclaimer"><?php echo $Disclaimer ?></p>
  </div>
</div>

<?php get_template_part('template-divi-code');?>

<?php get_footer(); ?>
