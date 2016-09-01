<?php acf_form_head(); ?>
<script type="text/javascript">
(function($) {
	
	// setup fields
	acf.do_action('append', $('#popup-id'));
	
})(jQuery);	
</script>

<?php
/*
Template Name: My Account
*/

get_header();
$curr_user = $current_user->user_nicename; 
$updated = $_GET["updated"];
$show_form = 'show';
$editpost =  $_GET["editpost"];
$button_label = $_GET["button"];
$updated_message = 'Grand Rounds Item Updated';
$overlay = "true";
if($editpost == ''){

	if($editpost == 'new_post'){$show_form = 'show';$editpost = 'new_post';}else{$show_form = 'hide';}

	$button_label = 'Add New';
	$updated_message = 'New Grand Rounds Item Created';
}
if($updated == 'true'): $show_form = 'hide'; endif;
?>



<div id="main-content">

	<div class="container">
		<div id="content-area" class="clearfix">
			


			<?php while ( have_posts() ) : the_post(); ?>



			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<div class= "<?php echo $show_form?>" style="padding-top:40px;padding-bottom:40px;">
					<hr style="height:1px">
					<h1>Add/Edit Grand Rounds</h1>

					<?php acf_form(array(
						'post_id'    =>  $editpost,
						'new_post'		=> array(
							'post_type'		=> 'grand-rounds-lecture',
							'post_status'		=> 'publish'
							),
						'field_groups' => array(18118),
						'post_status'   => 'draft',
						'uploader' => 'wp',
						'post_title' => true,
						'post_content' => false,

           // 'return' => '%post_url%',
						'submit_value' => __($button_label, 'acf'),
						'updated_message' =>  $updated_message,
						//'return' => 'true',
						'uploader' => 'basic'
						)); ?>
					<?php endwhile; ?>
				</div>
				<hr style="height:1px">

				<h1 class="main_title"><?php the_title(); ?></h1>

						 <?php
if ( is_user_logged_in() ) { ?>
	<p><strong>Hello</strong>  <?php echo $curr_user; ?>! <a href="<?php echo wp_logout_url( home_url() ); ?>" title="Logout">Logout</a></p>
<?php } else { ?>
	<h1> Account </h1>
<?php }?>

				
				<p><strong>My Contents</strong> | <a href="/wordpress/my-account/?editpost=new_post&formshow=show&button=Add&#160;New">Add New Grand Rounds</a></p>

				<div class="entry-content">
					<?php
					//the_content();

					 // Load clinical study posts with the same mhif-section term as this single post
					if( function_exists( 'mhi_loop' ) ) {
						mhi_loop( "grand-rounds-lecture", "", "", "100" );

						//[mhi_loop cpt="grand-rounds-lecture" quantity="100"]
					} 

					?>
				</div> <!-- .entry-content -->

				<?php
				if ( ! $is_page_builder_used && comments_open() && 'on' === et_get_option( 'divi_show_pagescomments', 'false' ) ) comments_template( '', true );
				?>

			</article> <!-- .et_pb_post -->





			

			
		</div> <!-- #content-area -->
	</div> <!-- .container -->


</div> <!-- #main-content -->



<script>
$(document).ready(function(){
	$("#myBtn").click(function(){
		$("#myModal").modal();
	});
});
</script>







<?php get_footer(); ?>