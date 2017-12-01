<?php
/* Template Name: Iframe Page */
get_header();

?>

<style> .et_pb_section_0{background-color: white !important;} </style>

<?php    
  $title_check = get_post_meta( get_the_ID(), 'wpcf-disable-title', true );   
  if ( !$title_check ) :     
    get_template_part('template-title');   
  endif
?>

<div  class="et_pb_row">

  <script type="text/javascript" src="/wp-content/themes/Divi-child/js/iframeResizer.min.js"></script>

  <style>iframe{width:100%}</style>
  <iframe src="<?php echo $post->post_content;?>" id="iFrameResize0" width="100%"
  scrolling="yes" marginwidth="0" marginheight="0" frameborder="0" vspace="0" hspace="0">
  </iframe>
  <script>
    iFrameResize({
      //log                     : true,                  // Enable console logging
      enablePublicMethods     : true,                  // Enable methods within iframe hosted page
      heightCalculationMethod : 'lowestElement',
    });
  </script>

</div>

<?php get_footer(); ?>
