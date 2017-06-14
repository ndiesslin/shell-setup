<?php
get_header();

$show_default_title = get_post_meta( get_the_ID(), '_et_pb_show_title', true );

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );

?>

<?php    
  $title_check = get_post_meta( get_the_ID(), 'wpcf-disable-title', true );   
  if ( !$title_check ) :     
    get_template_part('template-title');   
  endif 
?>

<!-- Begin Grand Rounds Entry Template Code -->

<?php  if(get_post_type() == 'grand-rounds-lecture'): ?>
<div id="main-content">

	<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-15197" class="post-15197 page type-page status-publish hentry">				
					
						<div class="et_pb_row">

							<div class="entry-content">
<ul>
								<li><strong><a href="<?php the_field('flier'); ?>" target="_blank"><?php echo the_title().' '.the_ID(); ?></a> </strong> <?php if(get_field('flier')){?>(<a href="<?php the_field('flier'); ?>" target="_blank">Flier</a>)<?php }else{echo '';} ?>  <?php if(get_field('slides')){?>(<a href="<?php the_field('slides'); ?>" target="_blank">Slides</a>)<?php }else{echo '';} ?>  <?php if(get_field('video_mp4')){?>(<a href="<?php the_field('video_mp4'); ?>" target="_blank">MP4</a>)<?php }else{echo '';} ?><br />

						<?php //if(get_field('lecturer')):
							the_field('lecturer'); //endif;?>
					
				</li></ul>
					<div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_0">



<p><a href="http://mplsheart.org/news-event/grand-rounds-listing/?grvar=time_and_date"> See All Grand Rounds Listings </a></p>
				
<p><strong>Physician:</strong> This activity has been planned and implemented in accordance with the accreditation requirements and policies of the Accreditation Council for Continuing Medical Education (ACCME) through the joint providership of Allina Health and Minneapolis Heart Institute Foundation. Allina Health is accredited by the ACCME to provide continuing medical education for physicians. Allina Health designates this live activity for a maximum of 1.0 AMA PRA Category 1 Credit(s)TM. Physicians should claim only the credit commensurate with the extent of their participation in the activity.</p>
<p><strong>Nurse:</strong> This activity has been designed to meet the Minnesota Board of Nursing continuing education requirements for 1.2 hours of credit. However, the nurse is responsible for determining whether this activity meets the requirements for acceptable continuing education.</p>
<p><strong>Planning Committee:</strong> Dr. JoEllyn Abraham, Dr. Alex Campbell, Dr. Kevin Harris, Rebecca Lindberg, Dr. Michael Miedema, Dr. Scott Sharkey, Eva Zewdie and Jolene Bell Makowesky have declared that they do not have any conflicts of interest associated with the planning of this activity. Dr. David Hurrell declares the following relationship – honoraria: Boston Scientific.</p>

			</div> 

						</div>
				
				</article> 	
				<?php endwhile; ?>			
</div> 

<?php elseif(get_the_ID() == 19505): ?>
<?php get_template_part('template-grandrounds-table');?>

<?php else:?>


<!-- End Grand Rounds Entry Template Code -->

<div id="main-content">
	
	<div class=""><!--container-->
		<div id="content-area" class="clearfix">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php if (et_get_option('divi_integration_single_top') <> '' && et_get_option('divi_integrate_singletop_enable') == 'on') echo(et_get_option('divi_integration_single_top')); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>
					<?php if ( ( 'off' !== $show_default_title && $is_page_builder_used ) || ! $is_page_builder_used ) { ?>
						<div class="et_post_meta_wrapper">

						<?php
							if ( ! post_password_required() ) :
								// Remove post meta data
								//et_divi_post_meta();

								$thumb = '';

								$width = (int) apply_filters( 'et_pb_index_blog_image_width', 1080 );

								$height = (int) apply_filters( 'et_pb_index_blog_image_height', 675 );
								$classtext = 'et_featured_image';
								$titletext = get_the_title();
								$thumbnail = get_thumbnail( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
								$thumb = $thumbnail["thumb"];

								$post_format = et_pb_post_format();

								if ( 'video' === $post_format && false !== ( $first_video = et_get_first_video() ) ) {
									printf(
										'<div class="et_main_video_container">
											%1$s
										</div>',
										$first_video
									);
								} else if ( ! in_array( $post_format, array( 'gallery', 'link', 'quote' ) ) && 'on' === et_get_option( 'divi_thumbnails', 'on' ) && '' !== $thumb ) {
									print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height );
								} else if ( 'gallery' === $post_format ) {
									et_gallery_images();
								}
							?>

							<?php
								$text_color_class = et_divi_get_post_text_color();

								$inline_style = et_divi_get_post_bg_inline_style();

								switch ( $post_format ) {
									case 'audio' :
										printf(
											'<div class="et_audio_content%1$s"%2$s>
												%3$s
											</div>',
											esc_attr( $text_color_class ),
											$inline_style,
											et_pb_get_audio_player()
										);

										break;
									case 'quote' :
										printf(
											'<div class="et_quote_content%2$s"%3$s>
												%1$s
											</div> <!-- .et_quote_content -->',
											et_get_blockquote_in_content(),
											esc_attr( $text_color_class ),
											$inline_style
										);

										break;
									case 'link' :
										printf(
											'<div class="et_link_content%3$s"%4$s>
												<a href="%1$s" class="et_link_main_url">%2$s</a>
											</div> <!-- .et_link_content -->',
											esc_url( et_get_link_url() ),
											esc_html( et_get_link_url() ),
											esc_attr( $text_color_class ),
											$inline_style
										);

										break;
								}

							endif;
						?>
					</div> <!-- .et_post_meta_wrapper -->
				<?php  } ?>

					<div class="entry-content">
					<?php
						the_content();

						wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'Divi' ), 'after' => '</div>' ) );
					?>
					</div> <!-- .entry-content -->
					<div class="et_post_meta_wrapper">
					<?php
					if ( et_get_option('divi_468_enable') == 'on' ){
						echo '<div class="et-single-post-ad">';
						if ( et_get_option('divi_468_adsense') <> '' ) echo( et_get_option('divi_468_adsense') );
						else { ?>
							<a href="<?php echo esc_url(et_get_option('divi_468_url')); ?>"><img src="<?php echo esc_attr(et_get_option('divi_468_image')); ?>" alt="468" class="foursixeight" /></a>
				<?php 	}
						echo '</div> <!-- .et-single-post-ad -->';
					}
				?>

					</div> <!-- .et_post_meta_wrapper -->
				</article> <!-- .et_pb_post -->

				<?php
					//echo get_the_category();
					// if( is_page( 'news' ) {
					// 	query_posts( array( 'category_name' => 'news' ) );
			    // }
				?>

				<?php if (et_get_option('divi_integration_single_bottom') <> '' && et_get_option('divi_integrate_singlebottom_enable') == 'on') echo(et_get_option('divi_integration_single_bottom')); ?>
			<?php endwhile; ?>

		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->

<?php endif; ?>

<?php get_footer(); ?>
