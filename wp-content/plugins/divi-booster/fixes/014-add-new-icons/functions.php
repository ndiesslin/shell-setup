<?php 
function wtfdivi014_register_icons($icons) {
	global $wtfdivi;
	list($name, $option) = $wtfdivi->get_setting_bases(__FILE__);
	if (!isset($option['urlmax'])) { $option['urlmax']=0; }
	for($i=0; $i<=$option['urlmax']; $i++) {
		if (!empty($option["url$i"])) {
			$icons[] = "wtfdivi014-url$i";
		}
	}
	return $icons;
}
add_filter('et_pb_font_icon_symbols', 'wtfdivi014_register_icons');

// add admin CSS
function wtfdivi014_admin_css() { 
	global $wtfdivi;
	list($name, $option) = $wtfdivi->get_setting_bases(__FILE__);
	if (!isset($option['urlmax'])) { $option['urlmax']=0; }
?>
<style>
<?php 	
for($i=0; $i<=$option['urlmax']; $i++) {
	if (!empty($option["url$i"])) { ?>
[data-icon="wtfdivi014-url<?php echo $i; ?>"]::before { background: url('<?php echo htmlentities(@$option["url$i"]); ?>') no-repeat center center; -webkit-background-size: cover; -moz-background-size: cover;-o-background-size: cover;background-size:cover; content:'a' !important; width:16px !important; height:16px !important; color:rgba(0,0,0,0) !important; }
<?php 
	}
} ?>
</style>
<?php
}

add_action('admin_head', 'wtfdivi014_admin_css');
?>