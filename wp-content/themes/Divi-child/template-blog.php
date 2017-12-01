<?php
/* Template Name: Blog Template */
get_header();

?>

<?php    
  $title_check = get_post_meta( get_the_ID(), 'wpcf-disable-title', true );   
  if ( !$title_check ) :     
    get_template_part('template-title');   
  endif 
?>

<?php
$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );
?>

<div id="main-content blog-template">

	<?php if ( ! $is_page_builder_used ) : ?>

		<div id="content-area" class="clearfix">
			<div id="left-area">

			<?php endif; ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php if ( ! $is_page_builder_used ) : ?>

					<h1 class="main_title"><?php the_title(); ?></h1>
				<?php
					$thumb = '';

					$width = (int) apply_filters( 'et_pb_index_blog_image_width', 1080 );

					$height = (int) apply_filters( 'et_pb_index_blog_image_height', 675 );
					$classtext = 'et_featured_image';
					$titletext = get_the_title();
					$thumbnail = get_thumbnail( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
					$thumb = $thumbnail["thumb"];

					if ( 'on' === et_get_option( 'divi_page_thumbnails', 'false' ) && '' !== $thumb )
						print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height );
				?>

				<?php endif; ?>

					<div class="entry-content et_pb_row">
						<div class="main-content">
							<?php
								the_content();

								if ( ! $is_page_builder_used )
									wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'Divi' ), 'after' => '</div>' ) );
							?>
						</div>
						<div class="side-content">
							<div class="et_pb_row">
								<?php get_sidebar('blog');?>
							</div>
						</div>
					</div> <!-- .entry-content -->

				<?php
					if ( ! $is_page_builder_used && comments_open() && 'on' === et_get_option( 'divi_show_pagescomments', 'false' ) ) comments_template( '', true );
				?>

				</article> <!-- .et_pb_post -->

			<?php endwhile; ?>

			<?php if ( ! $is_page_builder_used ) : ?>

			</div> <!-- #left-area -->

			<?php //get_sidebar(); ?>
		</div> <!-- #content-area -->

	<?php endif; ?>

</div> <!-- #main-content -->

<?php get_footer(); ?>