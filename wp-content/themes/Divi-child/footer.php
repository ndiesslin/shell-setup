<?php if ( 'on' == et_get_option( 'divi_back_to_top', 'false' ) ) : ?>

	<span class="et_pb_scroll_top et-pb-icon"></span>

<?php endif;

if ( ! is_page_template( 'page-template-blank.php' ) ) : ?>

			<footer id="main-footer">
				<?php get_sidebar( 'footer' ); ?>


		<?php
			if ( has_nav_menu( 'footer-menu' ) ) : ?>

				<div id="et-footer-nav">
					<div class="container">
						<?php
							wp_nav_menu( array(
								'theme_location' => 'footer-menu',
								'depth'          => '2',
								'menu_class'     => 'bottom-nav clearfix',
								'container'      => '',
								'fallback_cb'    => '',
							) );
						?>
					</div>
				</div> <!-- #et-footer-nav -->

			<?php endif; ?>

				<div id="footer-bottom">
					<div class="container clearfix">
						<?php wp_nav_menu( array( 'theme_location' => 'secondary-footer-menu' ) ); ?>
						<?php
							if ( false !== et_get_option( 'show_footer_social_icons', true ) ) {
								get_template_part( 'includes/social_icons', 'footer' );
							}
						?>
					</div>	<!-- .container -->
					<div class="container clearfix">
						<p class="font-small">Copyright © <?php echo date("Y") ?> Minneapolis Heart Institute Foundation.</p>
					</div>	<!-- .container -->
				</div>
			</footer> <!-- #main-footer -->
		</div> <!-- #et-main-area -->

<?php endif; // ! is_page_template( 'page-template-blank.php' ) ?>

	</div> <!-- #page-container -->

	<?php wp_footer(); ?>


  <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri();?>/css/jcarousel.responsive.css">
  <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri();?>/js/jquery.jcarousel.min.js"></script>
  <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri();?>/js/jcarousel.responsive.js"></script>

  <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri();?>/js/iframeResizer.min.js"></script>
  <script type="text/javascript">
  iFrameResize({log:false});
  </script>
  
	<script src="http://localhost/nike/jquery/jquery-validation/dist/jquery.validate.js"></script>
	<!--<script src="http://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>-->
  <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri();?>/js/custom.js"></script>

<?php 
$post_type = get_post_type(get_the_ID());//news-blog

global $wp_post_types;
$obj = $wp_post_types[$post_type];
$page_title = $obj->labels->singular_name; 

/*if($post_type == 'news-blog')
	$page_title = 'Blog';
if($post_type == 'internship-blog')
	$page_title = 'Internship Blog';
if($post_type == 'heart-health-blog')
	$page_title = 'Heart Health Blog';
if($post_type == 'research-intern-blog')
	$page_title = 'Research Internship Blog';*/

$taxonomy = $post_type . 's';//just s added in post-type slug
$tax_term = $wp_query->query_vars[$taxonomy];
$details = get_term_by('slug', $tax_term, $taxonomy);

if($details->name != '')
	$page_title = $details->name;

?>
<script>
	$('body.post-type-archive-<?php echo $post_type;?> #et-main-area, body.tax-<?php echo $taxonomy;?> #et-main-area').prepend('<div class="et_pb_fullwidth_header et_pb_module et_pb_bg_layout_dark et_pb_text_align_left page-title et_pb_fullwidth_header_0"><div class="et_pb_fullwidth_header_container left"><div class="header-content-container center"><div class="header-content"><h1><?php echo $page_title;?></h1></div></div></div></div>');
</script>
</body>
</html>
