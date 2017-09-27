<?php
/**
 * The template for displaying search results pages.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

get_header(); ?>

<?php
//title
$title = 'Search Results for ' . get_search_query();
//$title_icon = do_shortcode(types_render_field('title-icon', array()));
$title_icon = '';
$team_title = types_render_field('team-title', array());
page_title($title, $title_icon, $team_title);
?>

	<div class="et_pb_row">
		<!--<h1><?php //printf( __( 'Search Results for: %s'), get_search_query() ); ?></h1>-->
		<?php
		// Start the loop.
			global $query_string;

			$query_args = explode("&", $query_string);
			$search_query = array();

			foreach($query_args as $key => $string) {
				$query_split = explode("=", $string);
				$search_query[$query_split[0]] = urldecode($query_split[1]);
			} // foreach

			$search = new WP_Query($search_query);

		?>
		<?php
			if ( have_posts() ) :
				while ( have_posts() ) : the_post();
					// Your loop code
			?>

<?php  if(get_post_type() == 'grand-rounds-lecture'): ?>
			<?php if(get_field('status') == 'publish'):?><div class="search-items"><h3><strong>Grand Rounds Lecture:</strong> <a href="<?php the_field('flier'); ?>" target="_blank"><?php echo the_title();?></a></h3><?php if(get_field('flier')){?> (<a href="<?php the_field('flier'); ?>" target="_blank">Flier</a>)<?php }else{echo '';} ?>  <?php if(get_field('slides')){?>(<a href="<?php the_field('slides'); ?>" target="_blank">Slides</a>)<?php }else{echo '';} ?>  <?php if(get_field('video_mp4')){?>(<a href="<?php the_field('video_mp4'); ?>" target="_blank">Recording</a>)<?php }else{echo '';} ?><br />

						<?php //if(get_field('lecturer')):
							the_field('lecturer'); //endif;?>
					
				</div><?php endif; ?>


			<?php else: ?>



				<div class="search-items">
					<h3><?php search_title_highlight(); ?></h3>
					<?php search_excerpt_highlight();?>
					<a href="<?php the_permalink();?>" class="read-more-btn">Read More</a>
				</div>

<?php endif ?>







			<?php
				endwhile;
			else :?>

						<h3><?php echo wpautop( 'Sorry, no posts were found' );?></h3>

			<?php
			endif;
			?>

	<div class="search wp-pagenavi">
	<?php wp_pagenavi(); ?>
</div>
</div> <!-- /.container -->
<?php get_footer(); ?>
