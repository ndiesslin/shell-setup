<?php
/* Template Name: Iframe Page */
get_header();

?>

<?php get_template_part('template-title');?>

<iframe src="<?php echo $post->post_content;?>" style="width: 100%; height: 600px"
scrolling="yes" marginwidth="0" marginheight="0" frameborder="0" vspace="0" hspace="0">
</iframe>

<?php get_footer(); ?>