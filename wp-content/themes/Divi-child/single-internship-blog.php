<?php
//for single blog with sidebar
get_header();

?>

<?php    
  $title_check = get_post_meta( get_the_ID(), 'wpcf-disable-title', true );   
  if ( !$title_check ) :     
    get_template_part('template-title');   
  endif
?>

<?php get_template_part('template-blog-single');?>

<?php get_footer(); ?>