<?php 

extract(shortcode_atts(array(
  "image_url" => '',
  "image_2_url" => ''
),
$atts));

wp_enqueue_script('twentytwenty');
wp_enqueue_style('twentytwenty');

if(!empty($image_url)) {
		
	if(!preg_match('/^\d+$/',$image_url)){
			
		$image_url = $image_url;
	
	} else {
		$image_src = wp_get_attachment_image_src($image_url, 'full');
		
		$image_url = $image_src[0];
	}
	
} else 
	$image_url = vc_asset_url( 'images/before.jpg' );

if(!empty($image_2_url)) {
		
	if(!preg_match('/^\d+$/',$image_2_url)){
			
		$image_2_url = $image_2_url;
	
	} else {
		$image_src = wp_get_attachment_image_src($image_2_url, 'full');
		$image_2_url = $image_src[0];
	}
	
} else 
	$image_2_url = vc_asset_url( 'images/after.jpg' );

echo "<div class='twentytwenty-container'>
  <img src='".$image_url."'>
  <img src='".$image_2_url."'>
</div>";

?>