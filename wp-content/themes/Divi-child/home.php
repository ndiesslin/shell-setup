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
			$post_id = $post->ID;
			$page_link = types_render_field('page-link',array());
			
			$banner = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), '');
			$banner_url = $banner[0];

			if($i <= 2):
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
					<div class="lh-background" style="background-image: url(<?php echo $banner_url;?>);"> </div></div>
			</div>
  		
  		<?php 
	    	$i++;
	    endforeach;
	  endif;
	?>
</div>

<!-- Jssor Slider Begin -->
<!-- To move inline styles to css file/block, please specify a class name for each element. --> 
<div class="jssor-slider hidden">
<div id="slider1_container" class="" style="position: relative; margin: 0 auto;
    top: 0px; left: 0px; width: 1300px; height: 500px; overflow: hidden; display:none;" class="homepage-slider">
    <!-- Loading Screen -->
    <!--<div u="loading" style="position: absolute; top: 0px; left: 0px;">
        <div style="filter: alpha(opacity=70); opacity: 0.7; position: absolute; display: block;
            top: 0px; left: 0px; width: 100%; height: 100%;">
        </div>
        <div style="position: absolute; display: block; background: url(../img/loading.gif) no-repeat center center;
            top: 0px; left: 0px; width: 100%; height: 100%;">
        </div>
    </div>-->
    <!-- Slides Container -->
    <div u="slides" style="cursor: move; position: absolute; left: 0px; top: 0px; width: 1300px;
        height: 500px; overflow: hidden;">
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
			$post_id = $post->ID;
			$page_link = types_render_field('page-link',array());
			
			$banner = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large');
			$banner_url = $banner[0];

			if($i <= 3):
				$color = 'red';
			else:
				$color = 'purple';//3 red and 2 purple
			endif;
			?>

      <div>
          <img u="image" src="<?php echo $banner_url;?>" />
          <!-- u="caption" t="T" t3="T" r3="137.5%" du3="3000" d3="500" -->
          <div u="caption" t="T" r="-75%" du="500" d="100" t2="NO" class="jcaption-head" style="">
          	<a href="<?php echo $page_link;?>"><?php the_title();?></a>
          </div>
          <div u="caption" t="B" r="-75%" du="500" d="100" t2="NO" class="jcaption">
            <a href="<?php echo $page_link;?>"><?php echo $post->post_content;?></a>
          </div>
      </div>
  		
  		<?php 
	    	$i++;
	    endforeach;
	  endif;
		?>

    </div>
            
    <!--#region Bullet Navigator Skin Begin -->
    <!-- bullet navigator container -->
    <div u="navigator" class="jssorb21" style="bottom: 26px; right: 6px;">
        <!-- bullet navigator item prototype -->
        <div u="prototype"></div>
    </div>
    <!--#endregion Bullet Navigator Skin End -->
    
    <!--#region Arrow Navigator Skin Begin -->
    <!-- Arrow Left -->
    <span u="arrowleft" class="jssora21l" style="top: 123px; left: 8px;">
    </span>
    <!-- Arrow Right -->
    <span u="arrowright" class="jssora21r" style="top: 123px; right: 8px;">
    </span>
    <!--#endregion Arrow Navigator Skin End -->
</div>
</div>
<!-- Jssor Slider End -->


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

<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri();?>/js/jssor.js"></script>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri();?>/js/jssor.slider.js"></script>
<script src="<?php echo get_stylesheet_directory_uri();?>/js/custom-jssor.js"></script>

<?php get_footer(); ?>