<?php list($name, $option) = $this->get_setting_bases(__FILE__); ?>
<?php
$rgb = array(0,0,0);
if (isset($option['bgcol'])) { $rgb = wtfdivi046_hex2rgb($option['bgcol']); }
?>
/* Set the semi-transparent background color */
.et_pb_slide_content, .et_pb_slide_description > h2, .et_pb_more_button_wrap {
	background-color: rgba(<?php echo intval($rgb[0]); ?>, <?php echo intval($rgb[1]); ?>, <?php echo intval($rgb[2]); ?>, <?php echo htmlentities(@$option['opacity']/100); ?>);	 
}

/* Hide the content area if empty (no title and no content) */
.et_pb_slide_description:first-child .et_pb_slide_content:empty { display:none; }

/* Add 30px of padding to the background */
.et_pb_slide_description > h2 {
	padding: 30px 30px 10px 30px !important;
	margin-top:-30px;
	margin-left:-30px;
	margin-right:-30px;
}
.et_pb_slide_description > div:first-child { padding-top: 30px !important; }
.et_pb_slide_description .et_pb_slide_content,
.et_pb_more_button_wrap
 {
	padding:0px 30px 30px 30px !important;
	margin-left:-30px;
	margin-right:-30px;
}
@media only screen and ( max-width: 479px ) { 
	.et_pb_slide_description > h2 {
		padding-bottom: 30px !important;
	}
	.et_pb_more_button_wrap {
		display:none;
	}
}

/* Give the background rounded corners */
.et_pb_slide_description > :first-child {
	border-top-left-radius: 15px;
	border-top-right-radius: 15px;
}
.et_pb_slide_description div:last-child {
	border-bottom-left-radius: 15px;
	border-bottom-right-radius: 15px;
}
@media only screen and ( max-width: 479px ) { 
    .et_pb_slide_description h2 {
		border-bottom-left-radius: 15px;
		border-bottom-right-radius: 15px;
	}
}
<?php
function wtfdivi046_hex2rgb( $colour ) {
	if ( $colour[0] == '#' ) {
			$colour = substr( $colour, 1 );
	}
	if ( strlen( $colour ) == 6 ) {
			list( $r, $g, $b ) = array( $colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5] );
	} elseif ( strlen( $colour ) == 3 ) {
			list( $r, $g, $b ) = array( $colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2] );
	} else {
			return false;
	}
	return array(hexdec($r), hexdec($g), hexdec($b));
}
?>

