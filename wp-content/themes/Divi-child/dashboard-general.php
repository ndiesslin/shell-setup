<?php
/*
Template Name: Dashboard General
*/
?>
<?php

get_header();

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );

?>
<div class="page-title">
	<div class="container">
		<?php while(have_posts()):the_post(); ?>
		<h1><?php the_title();?></h1>
		<?php endwhile;?>
	</div>
</div>
<div id="main-content">

<?php if ( ! $is_page_builder_used ) : ?>

	<div class="container">
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

					<div class="entry-content">
					<?php
						the_content();

						if ( ! $is_page_builder_used )
							wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'Divi' ), 'after' => '</div>' ) );
					?>
					</div> <!-- .entry-content -->

				<?php
					if ( ! $is_page_builder_used && comments_open() && 'on' === et_get_option( 'divi_show_pagescomments', 'false' ) ) comments_template( '', true );
				?>

				</article> <!-- .et_pb_post -->

			<?php endwhile; ?>

<?php if ( ! $is_page_builder_used ) : ?>

			</div> <!-- #left-area -->

			<?php get_sidebar(); ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->

<?php endif; ?>
	<div class="container">
		<div class="gratitude-video">
			<h2><i class="fa fa-quote-left"></i> STORIES OF GRATITUDE</h2>
			<div class="et_pb_row">
				<?php 
					$args = array( 'post_type' => 'video', 'posts_per_page' => 3, 
						'tax_query' => array(
							array(
								'taxonomy' => 'video-categories',
								'field' => 'name',
								'terms' => 'gratitude'
							)
						)
					 );
					$loop = new WP_Query( $args );
					?>
					<?php
					while ( $loop->have_posts() ) : $loop->the_post(); 
				?>
					<div class="et_pb_column et_pb_column_1_2">
						<?php 
							the_content();
						?>
						<div class="video-title"><?php the_title(); ?></div>

					</div>

				<?php endwhile;
				?>
			</div>
			<p class="view-all"><a href="#">View All Stories of Gratitude</a></p>
		</div> <!-- /.gratitude-video -->
		<div class="focus-area">
			<h2><i class="fa fa-heartbeat"></i>AREAS OF FOCUS</h2>
		</div>
	</div> <!-- /.container -->


</div> <!-- #main-content -->

<?php get_footer(); ?>