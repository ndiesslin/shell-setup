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
  <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri();?>/js/custom.js"></script>
</body>
</html>
