<?php
/*
Template Name: GrandRound Listings
*/

get_header();

$grvar = $_GET["grvar"];
$ordering = $_GET["ord"];

if($editpost == ''){

	if($editpost == 'new_post'){$show_form = 'show';$editpost = 'new_post';}else{$show_form = 'hide';}

	$button_label = 'Add New';
	$updated_message = 'New Grand Rounds Item Created';
}
if($updated == 'true'): $show_form = 'hide'; endif;

?>

<?php    
  $title_check = get_post_meta( get_the_ID(), 'wpcf-disable-title', true );   
  if ( !$title_check ) :     
    get_template_part('template-title');   
  endif 
?>

<!-- DIVI CATCH -->

<div id="main-content">

	
			
				<article id="post-18097" class="post-18097 page type-page status-publish hentry">

				
					<div class="entry-content">
					<div class="et_pb_section  et_pb_section_0 et_section_regular">
				
				
					
					<div class=" et_pb_row et_pb_row_0">
				
				<div class="et_pb_column et_pb_column_4_4  et_pb_column_0">
				
				<div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left et_pb_row et_pb_row_1 et_pb_row_fullwidth et_pb_row_4col et_pb_text_0">
				
					 <?php  echo $grvar." ".$ordering ?> Hello My! <?php mhi_loop("grand-rounds-lecture","","","",100,$grvar, $ordering); ?>

			</div> <!-- .et_pb_text -->
			</div> <!-- .et_pb_column -->
					
			</div> <!-- .et_pb_row -->
				
			</div> <!-- .et_pb_section -->
					</div> <!-- .entry-content -->

				
				</article> <!-- .et_pb_post -->

			
			
</div> <!-- #main-content -->

<!-- DIVI CATCH -->



<?php get_footer(); ?>
