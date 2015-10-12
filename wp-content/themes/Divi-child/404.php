<?php get_header(); ?>

<?php
//title
//$title = get_the_title();
$title = 'Page Not Found';
$title_icon = do_shortcode(types_render_field('title-icon', array()));
$team_title = types_render_field('team-title', array());
page_title($title, $title_icon, $team_title);
?>
<div class="et_pb_row">Sorry, the page you requested is not found.</div>

<?php get_footer(); ?>