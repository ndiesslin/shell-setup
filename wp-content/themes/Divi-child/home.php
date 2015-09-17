<?php
/*
Template Name: Home Page
*/
?>
<?php

get_header();

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );

?>

<div class="homepage-slider elem-large-hover" style="">
	<?php
	  $args = array(
		'order'=> 'ASC', 
		'numberposts'=> '5',
		'post_type' => 'homepage-slider',
		);
	  $postslist = get_posts( $args );
	  $count = count($postslist);
	  if($count != 0):	  
	  	$i = 1;
	    ?>
	    <?php foreach ($postslist as $post) :  setup_postdata($post); ?>

			<?php 
			$page_link = types_render_field('page-link',array());
			if($i <= 3):
				$color = 'red';
			else:
				$color = 'purple';//3 red and 2 purple
			endif;
			?>
			<div class="large-hover-img">
				<div class="lh-table <?php echo ($i == 1 ? 'img-first': '');?>">
					<div class="lh-container <?php echo $color;?>">
						<a class="caption-head" href="<?php echo $page_link;?>"><?php the_title();?></a>
						<p class="lh-click"></p>
						<p class="lh-reveal"><a href="<?php echo $page_link;?>"><?php echo $post->post_content;?></a></p>
					</div>
					<div class="lh-background"> </div></div>
			</div>
  		
  		<?php 
	    	$i++;
	    endforeach;
	  endif;
	?>
</div>

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

<?php get_footer(); ?>