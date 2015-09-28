<?php list($name, $option) = $this->get_setting_bases(__FILE__); ?>

@media only screen and ( min-width:981px ) {
	/* Set the slider height */
	.et_pb_slider, .et_pb_slider .et_pb_container { height: <?php echo intval(@$option['sliderheight']); ?>px !important; }
	.et_pb_slider, .et_pb_slider .et_pb_slide { max-height: <?php echo intval(@$option['sliderheight']); ?>px; }
	.et_pb_slider .et_pb_slide_description { position: relative; top:25%; padding-top:0 !important; padding-bottom:0 !important; height:auto !important; }
}