<?php 
extract(shortcode_atts(array("accordion" => 'false'), $atts));  
	
($accordion == 'true') ? $accordion_class = 'accordion': $accordion_class = null ;
echo '<div class="toggles '.$accordion_class.'">' . do_shortcode($content) . '</div>'; 

?>




















