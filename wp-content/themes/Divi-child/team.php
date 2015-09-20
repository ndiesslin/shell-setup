<?php
/*
Template Name: Team Listing 
*/
?>
<?php

get_header();

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );

?>

<?php 
//title
$title = get_the_title();
$title_icon = do_shortcode(types_render_field('title-icon', array()));
page_title($title, $title_icon);
?>
<div class="team-template">
	<div class="full-row">
		<div class="content-wrapper">
			<div class="et_pb_section">
				<div class="et_pb_row et_pb_row_0">
					<div class="profile-img">
						<img src="http://mhif.kazi270.com/wp-content/uploads/2015/09/richard.jpg" alt="">			
					</div> <!-- /.profile-img -->
					<div class="profile-intro">
						<h2><span class="name">Raed H. Abdelhadi</span> <span>MD, FACC, FHRS</span></h2> <!-- name -->
						<p>Dr. Raed H. Abdelhadi (MD, FACC) is a researcher at the Minneapolis Heart Institute Foundation® and clinical cardiologist at the Minneapolis Heart Institute® at Abbott Northwestern Hospital. Dr. Abdelhadi is also the Co-director of the Genetic Arrhythmia Clinic, and his primary topics of interest include electrophysiology and heart rhythm management.</p>
						<p><a href="#">View Profile</a></p>
					</div><!-- /.profile-intro -->
				</div> <!-- et_pb_row et_pb_row_0 -->
			</div> <!-- /.et_pb_section -->		
		</div><!-- /.content-wrapper -->	
	</div> <!-- /full-row -->
	<div class="full-row">
		<div class="content-wrapper">
			<div class="et_pb_section">
				<div class="et_pb_row et_pb_row_0">
					<div class="profile-img">
						<img src="http://mhif.kazi270.com/wp-content/uploads/2015/09/richard.jpg" alt="">			
					</div> <!-- /.profile-img -->
					<div class="profile-intro">
						<h2><span class="name">Raed H. Abdelhadi</span> <span>MD, FACC, FHRS</span></h2> <!-- name -->
						<p>Dr. Raed H. Abdelhadi (MD, FACC) is a researcher at the Minneapolis Heart Institute Foundation® and clinical cardiologist at the Minneapolis Heart Institute® at Abbott Northwestern Hospital. Dr. Abdelhadi is also the Co-director of the Genetic Arrhythmia Clinic, and his primary topics of interest include electrophysiology and heart rhythm management.</p>
						<p><a href="#">View Profile</a></p>
					</div><!-- /.profile-intro -->
				</div> <!-- et_pb_row et_pb_row_0 -->
			</div> <!-- /.et_pb_section -->
		</div><!-- /.content-wrapper -->	
	</div> <!-- /full-row -->
	<div class="full-row">
		<div class="content-wrapper">
			<div class="et_pb_section">
				<div class="et_pb_row et_pb_row_0">
					<div class="profile-img">
						<img src="http://mhif.kazi270.com/wp-content/uploads/2015/09/richard.jpg" alt="">			
					</div> <!-- /.profile-img -->
					<div class="profile-intro">
						<h2><span class="name">Raed H. Abdelhadi</span> <span>MD, FACC, FHRS</span></h2> <!-- name -->
						<p>Dr. Raed H. Abdelhadi (MD, FACC) is a researcher at the Minneapolis Heart Institute Foundation® and clinical cardiologist at the Minneapolis Heart Institute® at Abbott Northwestern Hospital. Dr. Abdelhadi is also the Co-director of the Genetic Arrhythmia Clinic, and his primary topics of interest include electrophysiology and heart rhythm management.</p>
						<p><a href="#">View Profile</a></p>
					</div><!-- /.profile-intro -->
				</div> <!-- et_pb_row et_pb_row_0 -->
			</div> <!-- /.et_pb_section -->
		</div><!-- /.content-wrapper -->	
	</div> <!-- /full-row -->
	<div class="full-row">
		<div class="content-wrapper">
			<div class="et_pb_section">
				<div class="et_pb_row et_pb_row_0">
					<div class="profile-img">
						<img src="http://mhif.kazi270.com/wp-content/uploads/2015/09/richard.jpg" alt="">			
					</div> <!-- /.profile-img -->
					<div class="profile-intro">
						<h2><span class="name">Raed H. Abdelhadi</span> <span>MD, FACC, FHRS</span></h2> <!-- name -->
						<p>Dr. Raed H. Abdelhadi (MD, FACC) is a researcher at the Minneapolis Heart Institute Foundation® and clinical cardiologist at the Minneapolis Heart Institute® at Abbott Northwestern Hospital. Dr. Abdelhadi is also the Co-director of the Genetic Arrhythmia Clinic, and his primary topics of interest include electrophysiology and heart rhythm management.</p>
						<p><a href="#">View Profile</a></p>
					</div><!-- /.profile-intro -->
				</div> <!-- et_pb_row et_pb_row_0 -->
			</div> <!-- /.et_pb_section -->
		</div><!-- /.content-wrapper -->	
	</div> <!-- /full-row -->
</div> <!-- /.team-template -->


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

</div> <!-- #main-content -->

<?php get_footer(); ?>