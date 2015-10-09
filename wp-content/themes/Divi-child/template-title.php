<?php 
//title
$first_name = types_render_field('first-name', array());
$title = $first_name . ' ' . get_the_title();
$title_icon = do_shortcode(types_render_field('title-icon', array()));
$team_title = types_render_field('team-title', array());
page_title($title, $title_icon, $team_title);
?>