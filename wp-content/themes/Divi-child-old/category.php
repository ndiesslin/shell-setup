<?php
/* 
Template for category listing
*/
get_header();

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );

?>

<?php
//title
//$title = get_the_title();
$title = 'Page Not Found';
$title_icon = do_shortcode(types_render_field('title-icon', array()));
$team_title = types_render_field('team-title', array());
page_title($title, $title_icon, $team_title);
?>
<div class="et_pb_row">Sorry, the page you requested is not found.</div>

<div id="main-content" class="hidden">

	<?php 
	//title
	$category = get_the_category();
	$title = $category[0]->name;
	page_title($title, 'fa-file-text');

	//category slug
	$category_slug = $category[0]->slug;
	$all_posts_name = get_all_posts_name();
	?>

	<div class="container">
		<div class="row-spaced">
			<?php $args = array(
			'posts_per_page'   => 5,
			'offset'           => 0,
			'category'         => '',
			'category_name'    => $category_slug,
			'orderby'          => 'date',
			'order'            => 'DESC',
			'include'          => '',
			'exclude'          => '',
			'meta_key'         => '',
			'meta_value'       => '',
			'post_type'        => $all_posts_name,
			'post_mime_type'   => '',
			'post_parent'      => '',
			'author'	   => '',
			'post_status'      => 'publish',
			'suppress_filters' => true 
			);
			$posts_array = get_posts( $args ); 
			foreach ($posts_array as $posts) :
			?>
			<div class="">
		    <a href="<?php echo $page_url;?><?php echo $page->post_name;?>/">
		      <?php echo $posts->post_title;?>        
		    </a>
		    <p>
		    <?php
		    $content = $posts->post_content;
        $content = apply_filters('the_content', $content);
        $content = strip_tags($content);
        //$content = nl2br($content);
        echo $content;
		    ?>
		  	</p>
		    <br>
		  </div>
			<?php
			endforeach;
			?>
		</div>
	</div>
</div> <!-- #main-content -->

<?php get_footer(); ?>
