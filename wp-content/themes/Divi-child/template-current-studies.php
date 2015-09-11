<?php
/* 
Template Name: Current Studies Template 
*/
get_header();

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );

?>
<div id="main-content">

	<?php 
	//title
	$title = get_the_title();
	page_title($title, 'fa-file-text');
	?>

	<div class="container">
		<div class="et_pb_row row-spaced">
			<?php 
			$page_url = get_the_permalink();
			$args = array(
				'sort_order' => 'DESC',
				'sort_column' => 'post_title',
        'child_of' => $post->ID,//shows all its child
        //'number' => '1',
        'parent' => $post->ID,//disable grand children
      ); 
	    $pages = get_pages($args); 
	    $count = 1;
	    foreach ( $pages as $page ):
	    ?>
	    <div class="et_pb_column et_pb_column_1_4 <?php if(($count % 4) == 0 ) echo 'nomarginright';?>">
	    	<?php
			  $banner = wp_get_attachment_image_src( get_post_thumbnail_id($page->ID));
			  $banner_url = $banner[0];
			  if($banner_url != ''):
				?>
	    	<div class="img"><img src="<?php echo $banner_url;?>" alt="" class="img-responsive">
	    	<?php endif;?></div>

	      <a href="<?php echo $page_url;?><?php echo $page->post_name;?>/">
	        <?php echo $page->post_title;?>        
	      </a>
	      <!-- <p>
	      	Research Coordinator: Not present on inside page layout.<br>
	      	And a little description.
	      </p> -->
	      <p>
	      <?php
        //$content = substr( $page->post_content, 0, 120) ;
        /*$content = $page->post_content;

        $content = apply_filters('the_content', $content);
        $content = str_replace(']]>', ']]>', $content);
        $content = strip_tags($content);
        //$content = substr($content, 0, 120);
        echo $content;*/
        ?>
      	</p>
	      <br>
	    </div>
	    <?php
	    $count++;
	    endforeach;
	    ?>
    </div>
    <div class="clear"></div>
  </div>

	

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