<?php
function wtfdivi099_add_post_types($post_types) {
	foreach(get_post_types() as $pt) {
		if (!in_array($pt, $post_types) and post_type_supports($pt, 'editor')) {
			$post_types[] = $pt;
		}
	} 
	return $post_types;
}
add_filter('et_builder_post_types', 'wtfdivi099_add_post_types');

function wtfdivi099_add_meta_boxes() {
	foreach(get_post_types() as $pt) {
		if (post_type_supports($pt, 'editor')) {
			add_meta_box('et_settings_meta_box', __('Divi Custom Post Settings', 'Divi'), 'et_single_settings_meta_box', $pt, 'side', 'high');
		}
	} 
	return $post_types;
}
add_action('add_meta_boxes', 'wtfdivi099_add_meta_boxes');


?>