<?php
// Use mobile detect to add more details of browser to body classes
include_once(dirname(__FILE__).'/Mobile-Detect-2.8.11/Mobile_Detect.php');

function wtfdivi011_add_body_classes($classes) {
		$detect = new Mobile_Detect_DM;
		 
		if ($detect->isTablet()) { $classes[] = 'tablet'; }
		elseif ($detect->isMobile()) { $classes[]='mobile'; }
		else { $classes[] = 'desktop'; }
		
		if($detect->isiOS()){ $classes[] = 'ios'; }
		if($detect->isAndroidOS()){ $classes[] = "android"; }
		
        return $classes;
}
add_filter('body_class', 'wtfdivi011_add_body_classes');

// Deals with html checkbox issue where unchecked values are not submitted. Uses zeros from hidden field as divider.
function wtfdivi011_html_checkbox_vals($orig) {
	$vals = array();
	while ($count = count($orig)) {
		if ($count>=2 and $orig[1]=='1') { // starts with 0,1 so enabled
			$vals[]=1; 
			$orig = array_slice($orig, 2); 
		} else { 
			$vals[]=0; 
			$orig = array_slice($orig,1); 
		}
	}
	return $vals;
}
?>