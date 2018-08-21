<?php
/*
Template Name: Home Page
*/
?>
<?php

get_header();

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );

?>

<!-- <div class="homepage-slider elem-large-hover" id="homepage-slider" style="">
	<?php
	  $args = array(
		'order'=> 'ASC',
		'numberposts'=> '2',
		'post_type' => 'homepage-slider',
		);
	  $postslist = get_posts( $args );
	  $count = count($postslist);
	  if($count != 0):
	  	$i = 1;
	    ?>
	    <?php foreach ($postslist as $post) :  setup_postdata($post); ?>

			<?php
			$post_id = $post->ID;
			$page_link = types_render_field('page-link');
			$banner = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'homepage-slider');
			$banner_url = $banner[0];

			?>
			<div class="large-hover-img">
				<div class="lh-table <?php echo ($i == 1 ? 'img-first': ''); ?>">
					<div class="lh-container purple">
						<p class="lh-body text-lead text-bold"><?php the_title();?></p>
						<p class="lh-body"><?php echo $post->post_content; ?> <i class="fa fa-chevron-right fa--small" aria-hidden="true"></i></p>
					</div>
					<div class="lh-background" style="background-image: url(<?php echo $banner_url; ?>);"> </div>
        </div>
        <a href="<?php echo $page_link; ?>" class="lh-link"></a>
			</div>

  		<?php
	    	$i++;
	    endforeach;
	  endif;
	?>
</div> -->

<div id="main-content" class="">

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

<!-- <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri();?>/js/jssor.js"></script>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri();?>/js/jssor.slider.js"></script>
<script src="<?php echo get_stylesheet_directory_uri();?>/js/custom-jssor.js"></script> -->

<?php get_footer(); ?>
