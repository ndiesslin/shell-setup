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
			<div class="row">
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
					<div class="col-lg-6">
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
			<div class="row">
				<?php 
					$args = array( 'post_type' => 'focus-area', 'posts_per_page' => 4);
					$loop = new WP_Query( $args );
					?>
					<?php
					while ( $loop->have_posts() ) : $loop->the_post(); 
				?>
				<div class="col-lg-3">
					<a href="<?php the_permalink(); ?>">
						<?php 
						// check if the post has a Post Thumbnail assigned to it.
						if ( has_post_thumbnail() ) {
							the_post_thumbnail('full');
						} 
						?>
					</a>	
					<span><a href="<?php the_permalink(); ?>"> <?php the_title();?></a></span>
				</div> <!-- /.col-lg-3 -->				
				<?php endwhile; ?>
			</div>
			<div class="row"><p class="view-all"><a href="#">View All Areas of Focus</a></p></div>
		</div> <!-- /.focus-area -->
		<div class="current-studies">
			<h2><i class="fa fa-file-text"></i> Current Studies</h2>
			<div class="row">
				<?php 
					$args = array( 'post_type' => 'current-study', 'posts_per_page' => 4);
					$loop = new WP_Query( $args );
					?>
					<?php
					while ( $loop->have_posts() ) : $loop->the_post(); 
				?>
				<div class="col-lg-3">
					<a href="<?php the_permalink(); ?>">
						<?php 
						// check if the post has a Post Thumbnail assigned to it.
						if ( has_post_thumbnail() ) {
							the_post_thumbnail('full');
						} 
						?>
					</a>	
					<span><a href="<?php the_permalink(); ?>"> <?php the_title();?></a></span>
				</div> <!-- /.col-lg-3 -->				
				<?php endwhile; ?>
			</div>
			<div class="row"><p class="view-all"><a href="#">View All Current Studies</a></p></div>
		</div> <!-- /.current-studies -->
		<div class="oval-thumb">
			<div class="row">
				
				<div class="col-sm-4">
					<a href="#">
						<img src="<?php echo get_stylesheet_directory_uri();?>/images/oval-img1.jpg" alt="" class="img-circle">
						<div><span class="icon"><i class="fa fa-heart"></i></span>Heart Healthy Living</div>
					</a>
				</div><!-- /.col-sm-4 -->

				<div class="col-sm-4">
					<a href="#">
						<img src="<?php echo get_stylesheet_directory_uri();?>/images/oval-img1.jpg" alt="" class="img-circle">
						<div><span class="icon"><i class="fa fa-stethoscope"></i></span>Conditions</div>
					</a>
				</div><!-- /.col-sm-4 -->

				<div class="col-sm-4">
					<a href="#">
						<img src="<?php echo get_stylesheet_directory_uri();?>/images/oval-img1.jpg" alt="" class="img-circle">
						<div><span class="icon"><i class="fa fa-clipboard"></i></span>Risk Factors</div>
					</a>
				</div><!-- /.col-sm-4 -->
			</div> <!-- /.row -->
		</div><!-- /.oval-thumb -->
		<div class="support-us">
			<div class="row">	
				<div class="col-xs-12">
					<h2>Why Support Us</h2>
					<p>Heart Health Today More than 2400 people in the United States lose their lives each day to cardiovascular disease (CVD). CVD remains the leading cause of death in every state, except Minnesota, where CVD is second to cancer. This is great news, and we are thrilled to have played a role in this success.</p>
					<p>For over thirty years, the Minneapolis Heart Institute Foundation has been fighting CVD by helping people achieve better heart health. How? By providing valuable tools, training and support for the prevention of cardiovascular disease; conducting clinical trials research that is the foundation for life-saving treatments; and becoming a national leader in advocating for patient safety. <a href="#">Read More</a></p>	
				</div>
			</div> <!-- /.row -->
		</div> <!-- /.support-us -->
		<div class="full-width-button">
			<a href="#">JOIN the CAUSE TODAY!</a>		
		</div> <!-- /.full-width-button -->
		<div class="event-intro">
			<h2><i class="fa fa-calendar"></i> Events</h2>
			<div class="row">
				<div class="col-sm-6">
					
				</div><!-- /.col-sm-6 -->
			</div> <!-- /.row -->
		</div> <!-- /.event-intro -->
	</div> <!-- /.container -->


</div> <!-- #main-content -->

<?php get_footer(); ?>