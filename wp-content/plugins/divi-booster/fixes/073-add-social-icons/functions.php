<?php
function wtfdivi073_add_social_icons($str) {
	global $wtfdivi;
	if (is_admin()) return $str;
	list($name, $option) = $wtfdivi->get_setting_bases(__FILE__);
	$icons = "";
	$networks = array(
		'linkedin'=>'LinkedIn',
		'youtube'=>'YouTube',
		'pinterest'=>'Pinterest',
		'tumblr'=>'Tumblr',
		'instagram'=>'Instagram',
		'skype'=>'Skype',
		'flikr'=>'Flickr',
		'myspace'=>'MySpace',
		'vimeo'=>'Vimeo'
	);
	foreach($networks as $k=>$v) {
		if (isset($option[$k]) and !empty($option[$k])) { 
			$url = $option[$k];
			if (!preg_match('#^(http:|https:|skype:|/)#', $url)) { $url = "http://$url"; }
			$icons.= '<li class="et-social-icon et-social-'.$k.'"><a href="'.htmlentities($url).'" class="icon"><span>'.htmlentities($v).'</span></a></li>';
		}
	}
    return preg_replace('#(<ul class="et-social-icons".*?)</ul>#s', '\\1 '.$icons.'</ul>', $str);
}

ob_start('wtfdivi073_add_social_icons');
?>