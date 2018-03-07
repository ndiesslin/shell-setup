<?php
/*
Template Name: Team Page
*/

//only for Team Title field to show (in pages) in this template
get_header();
?>

<?php    
  $title_check = get_post_meta( get_the_ID(), 'wpcf-disable-title', true );  
  if ( !$title_check ) :     
    get_template_part('template-title');   
  endif 
?>

<?php get_template_part('template-divi-code');?>

<?php get_footer(); ?>