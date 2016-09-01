<?php 

global $options;
$vc_is_wp_version_3_6_more = version_compare(preg_replace('/^([\d\.]+)(\-.*$)/', '$1', get_bloginfo('version')), '3.6') >= 0;

function nectar_set_vc_as_theme() {

	vc_set_as_theme($disable_updater = true);
	$template_directory = get_template_directory();

	if(defined( 'SALIENT_VC_ACTIVE')) {
	    $child_dir = $template_directory . '/nectar/nectar-vc-addons/vc_templates';
	    $parent_dir = $template_directory . '/nectar/nectar-vc-addons/vc_templates';

	    vc_set_shortcodes_templates_dir($parent_dir);
	    vc_set_shortcodes_templates_dir($child_dir);
	} else {

	    $child_dir = $template_directory . '/nectar/nectar-vc-addons/vc_templates';
	    $parent_dir = $template_directory . '/nectar/nectar-vc-addons/vc_templates';
	    vc_set_shortcodes_templates_dir($parent_dir);
	    vc_set_shortcodes_templates_dir($child_dir);
	}


	vc_disable_frontend();

}

add_action('vc_before_init', 'nectar_set_vc_as_theme');




add_filter( 'vc_load_default_templates', 'my_custom_template_modify_array' ); // Hook in
function my_custom_template_modify_array( $data ) {
    return array(); 
}

/*
vc_remove_element("vc_row");
vc_remove_element("vc_column");
vc_remove_element("vc_column_inner");*/
vc_remove_element("vc_button");
vc_remove_element("vc_button2");
vc_remove_element("vc_posts_slider");
vc_remove_element("vc_gmaps");
vc_remove_element("vc_teaser_grid");
vc_remove_element("vc_progress_bar");
vc_remove_element("vc_facebook");
vc_remove_element("vc_tweetmeme");
vc_remove_element("vc_googleplus");
vc_remove_element("vc_facebook");
vc_remove_element("vc_pinterest");
vc_remove_element("vc_message");
vc_remove_element("vc_posts_grid");
vc_remove_element("vc_carousel");
vc_remove_element("vc_flickr");
vc_remove_element("vc_tour");
vc_remove_element("vc_separator");
vc_remove_element("vc_single_image");
vc_remove_element("vc_cta_button");
vc_remove_element("vc_cta_button2");
vc_remove_element("vc_tta_tour");
vc_remove_element("vc_btn");
vc_remove_element("vc_basic_grid");
vc_remove_element("vc_round_chart");
vc_remove_element("vc_line_chart");
vc_remove_element("vc_cta");
vc_remove_element("vc_icon");
vc_remove_element("vc_media_grid");
vc_remove_element("vc_masonry_media_grid");
vc_remove_element("vc_masonry_grid");

vc_remove_element("vc_accordion");
vc_remove_element("vc_accordion_tab");
vc_remove_element("vc_toggle");
vc_remove_element("vc_tabs");
vc_remove_element("vc_tab");
vc_remove_element("vc_tta_tabs");
vc_remove_element("vc_tta_accordion");
vc_remove_element("vc_tta_pageable");

vc_remove_element("vc_empty_space");
vc_remove_element("vc_custom_heading");
vc_remove_element("vc_images_carousel");
vc_remove_element("vc_wp_archives");
vc_remove_element("vc_wp_calendar");
vc_remove_element("vc_wp_categories");
vc_remove_element("vc_wp_custommenu");
vc_remove_element("vc_wp_links");
vc_remove_element("vc_wp_meta");
vc_remove_element("vc_wp_pages");
vc_remove_element("vc_wp_posts");
vc_remove_element("vc_wp_recentcomments");
vc_remove_element("vc_wp_rss");
vc_remove_element("vc_wp_search");
vc_remove_element("vc_wp_tagcloud");
vc_remove_element("vc_wp_text");

//remove WC elements
function your_name_vc_remove_woocommerce() {
    if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
        //vc_remove_element("woocommerce_cart");
		//vc_remove_element("woocommerce_checkout");
		//vc_remove_element("woocommerce_order_tracking");
		//vc_remove_element("woocommerce_my_account");
		vc_remove_element("recent_products");
		vc_remove_element("featured_products");
		vc_remove_element("product");
		vc_remove_element("products");
		//vc_remove_element("add_to_cart");
		vc_remove_element("add_to_cart_url");
		vc_remove_element("product_page");
		//vc_remove_element("product_category");
		//vc_remove_element("product_categories");
		vc_remove_element("sale_products");
		vc_remove_element("best_selling_products");
		vc_remove_element("top_rated_products");
		vc_remove_element("product_attribute");
    }
}
// Hook for admin editor.
add_action( 'vc_build_admin_page', 'your_name_vc_remove_woocommerce', 11 );

//only load shortcode logic on front when needed
$is_admin = is_admin();


function nectar_has_shortcode( $shortcode = NULL ) {

    // false because we have to search through the post content first
    //$found = false;

    // if no short code was provided, return false
   // if ( ! $shortcode ) {
    //    return $found;
    //}
    
    // check the post content for the short code
    //if ( stripos( $post_to_check, '[' . $shortcode) !== FALSE || stripos( $portfolio_extra_content, '[' . $shortcode) !== FALSE || $is_admin) {
        // we have found the short code
   //     $found = TRUE;
   // }

    // return our final results
   // return $found;
	return true;
}

// Create multi dropdown param type
add_shortcode_param( 'dropdown_multi', 'dropdown_multi_settings_field' );
function dropdown_multi_settings_field( $param, $value ) {

	 $param_line = '';
	 $param_line .= '<select multiple name="'.$param['param_name'].'" class="wpb_vc_param_value wpb-input wpb-select '.$param['param_name'].' '.$param['type'].'">';
                foreach ( $param['value'] as $text_val => $val ) {
                    if ( is_numeric($text_val) && (is_string($val) || is_numeric($val)) ) {
                        $text_val = $val;
                    }
                    $text_val = __($text_val, "js_composer");
                    $selected = '';

                    if(!is_array($value)) {
                    	$param_value_arr = explode(',',$value);
                    } else {
                    	$param_value_arr = $value;
                    }
					
                    if ($value!=='' && in_array($val, $param_value_arr)) {
                        $selected = ' selected="selected"';
                    }
                    $param_line .= '<option class="'.$val.'" value="'.$val.'"'.$selected.'>'.$text_val.'</option>';
                }
                $param_line .= '</select>';

   return  $param_line;
}

add_shortcode_param( 'fws_image', 'fws_image_settings_field' );
function fws_image_settings_field( $param, $value ) {
		$param_line = '';
		$param_line .= '<input type="hidden" class="wpb_vc_param_value gallery_widget_attached_images_ids '.$param['param_name'].' '.$param['type'].'" name="'.$param['param_name'].'" value="'.$value.'"/>';
        //$param_line .= '<a class="button gallery_widget_add_images" href="#" use-single="true" title="'.__('Add image', "js_composer").'">'.__('Add image', "js_composer").'</a>';
        $param_line .= '<div class="gallery_widget_attached_images">';
        $param_line .= '<ul class="gallery_widget_attached_images_list">';
	
		if(strpos($value, "http://") !== false || strpos($value, "https://") !== false) {
			//$param_value = fjarrett_get_attachment_id_by_url($param_value);
			$param_line .= '<li class="added">
				<img src="'. $value .'" />
				<a href="#" class="icon-remove"></a>
			</li>';
		} else {
			$param_line .= ($value != '') ? fieldAttachedImages(explode(",", $value)) : '';
		}
		
        
        $param_line .= '</ul>';
        $param_line .= '</div>';
        $param_line .= '<div class="gallery_widget_site_images">';
        // $param_line .= siteAttachedImages(explode(",", $param_value));
        $param_line .= '</div>';
        $param_line .= '<a class="gallery_widget_add_images" href="#" use-single="true" title="'.__('Add image', "js_composer").'">'.__('Add image', "js_composer").'</a>';//class: button
        //$param_line .= '<div class="wpb_clear"></div>';

        return $param_line;
}

// VC_Row 

add_action('vc_before_init', 'nectar_custom_maps');

function nectar_custom_maps() {

	$vc_is_wp_version_3_6_more = version_compare(preg_replace('/^([\d\.]+)(\-.*$)/', '$1', get_bloginfo('version')), '3.6') >= 0;
	global $options;
	$is_admin = is_admin();

	vc_map( array(
		'name' => __( 'Row', 'js_composer' ),
		'base' => 'vc_row',
		'is_container' => true,
		'icon' => 'icon-wpb-row',
		'show_settings_on_create' => false,
		'category' => __( 'Structure', 'js_composer' ),
		'description' => __( 'Place content elements inside the row', 'js_composer' ),
		'params' => array(
			 array(
				"type" => "dropdown",
				"class" => "",
				"heading" => "Type",
				"param_name" => "type",
				'save_always' => true,
				"value" => array(
					"In Container" => "in_container",
					"Full Width Background" => "full_width_background",
					"Full Width Content" => "full_width_content"		
					)
			),
			array(
				"type" => "checkbox",
				"class" => "",
				"heading" => "Vertically Center Columns",
				"value" => array("Make all columns in this row vertically centered?" => "true" ),
				"param_name" => "vertically_center_columns",
				"description" => "",
				"dependency" => Array('element' => "type", 'value' => array('full_width_content'))
			),

			array(
				"type" => "fws_image",
				"class" => "",
				"heading" => "Background Image",
				"param_name" => "bg_image",
				"value" => "",
				"description" => "",
				"dependency" => Array('element' => "mouse_based_parallax_bg", 'is_empty' => true)
			),

			array(
				"type" => "checkbox",
				"class" => "",
				"heading" => "Background Image Mobile Hidden",
				"param_name" => "background_image_mobile_hidden",
				"value" => array("Hide Background Image on Mobile Views?" => "true" ),
				"description" => "Use this to remove your row BG image from displaying on mobile devices",
				"dependency" => Array('element' => "bg_image", 'not_empty' => true)
			),

			array(
				"type" => "dropdown",
				"class" => "",
				'save_always' => true,
				"heading" => "Background Position",
				"param_name" => "bg_position",
				"value" => array(
					 "Left Top" => "left top",
			  		 "Left Center" => "left center",
			  		 "Left Bottom" => "left bottom",
			  		 "Center Top" => "center top",
			  		 "Center Center" => "center center",
			  		 "Center Bottom" => "center bottom",
			  		 "Right Top" => "right top",
			  		 "Right Center" => "right center",
			  		 "Right Bottom" => "right bottom"
				),
				"dependency" => Array('element' => "bg_image", 'not_empty' => true)
			),


			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => "Background Repeat",
				"param_name" => "bg_repeat",
				'save_always' => true,
				"value" => array(
					"No Repeat" => "no-repeat",
					"Repeat" => "repeat"
				),
				"dependency" => Array('element' => "bg_image", 'not_empty' => true)
			),

			array(
				"type" => "checkbox",
				"class" => "",
				"heading" => "Parallax Background",
				"value" => array("Enable Parallax Background?" => "true" ),
				"param_name" => "parallax_bg",
				"description" => "",
				"dependency" => Array('element' => "bg_image", 'not_empty' => true)
			),

			array(
				"type" => "dropdown",
				"class" => "",
				"description" => "The faster you choose, the closer your BG will match the users scroll speed",
				"heading" => "Parallax Background Speed",
				"param_name" => "parallax_bg_speed",
				'save_always' => true,
				"value" => array(
					 "Slow" => "slow",
			  		 "Medium" => "medium",
			  		 "Fast" => "fast",
			  		 "Fixed" => "fixed"
				),
				"dependency" => Array('element' => "parallax_bg", 'not_empty' => true)
			),

			array(
				"type" => "colorpicker",
				"class" => "",
				"heading" => "Background Color",
				"param_name" => "bg_color",
				"value" => "",
				"description" => ""
			),
			array(
				"type" => "checkbox",
				"class" => "",
				"heading" => "Mouse Based Parallax Scene",
				"value" => array("Enable Mouse Based Parallax BG?" => "true" ),
				"param_name" => "mouse_based_parallax_bg",
				"description" => ""
			),

			 array(
				  "type" => "dropdown",
				  "heading" => __("Scene Positioning", "js_composer"),
				  "param_name" => "scene_position",
				  'save_always' => true,
				  "value" => array(
			  		 "Center" => "center",
			  		 "Top" => "top",
			  		 "Bottom" => "bottom"
					),
				  "description" => __("Select your desired scene alignment within your row", "js_composer")
			),

			 array(
				"type" => "textfield",
				"class" => "",
				"heading" => "Scene Parallax Overall Strength",
				"value" => "",
				"param_name" => "mouse_sensitivity",
				"description" => "Enter a number between 1 and 25 that will effect the overall strength of the parallax movement within the entire scene - the default is 10."
			),


			array(
				"type" => "fws_image",
				"class" => "",
				"heading" => "Scene Layer One",
				"param_name" => "layer_one_image",
				"value" => "",
				"description" => "Please upload all of your layers at the same dimensions to ensure accurate placement."
			),

			array(
				"type" => "textfield",
				"class" => "",
				"heading" => "Layer One Strength",
				"value" => "",
				"param_name" => "layer_one_strength",
				"description" => "Enter a number <strong>between 0 and 1</strong> that will determine the strength this layer responds to mouse movement. <br/><br/>By default each layer will increment by .2"
			),

			array(
				"type" => "fws_image",
				"class" => "",
				"heading" => "Scene Layer Two",
				"param_name" => "layer_two_image",
				"value" => "",
				"description" => ""
			),

			array(
				"type" => "textfield",
				"class" => "",
				"heading" => "Layer Two Strength",
				"value" => "",
				"param_name" => "layer_two_strength",
				"description" => "See the description on \"Layer One Strength\" for guidelines on this property."
			),

			array(
				"type" => "fws_image",
				"class" => "",
				"heading" => "Scene Layer Three",
				"param_name" => "layer_three_image",
				"value" => "",
				"description" => ""
			),

			array(
				"type" => "textfield",
				"class" => "",
				"heading" => "Layer Three Strength",
				"value" => "",
				"param_name" => "layer_three_strength",
				"description" => "See the description on \"Layer One Strength\" for guidelines on this property."
			),

			array(
				"type" => "fws_image",
				"class" => "",
				"heading" => "Scene Layer Four",
				"param_name" => "layer_four_image",
				"value" => "",
				"description" => ""
			),

			array(
				"type" => "textfield",
				"class" => "",
				"heading" => "Layer Four Strength",
				"value" => "",
				"param_name" => "layer_four_strength",
				"description" => "See the description on \"Layer One Strength\" for guidelines on this property."
			),

			array(
				"type" => "fws_image",
				"class" => "",
				"heading" => "Scene Layer Five",
				"param_name" => "layer_five_image",
				"value" => "",
				"description" => ""
			),

			array(
				"type" => "textfield",
				"class" => "",
				"heading" => "Layer Five Strength",
				"value" => "",
				"param_name" => "layer_five_strength",
				"description" => "See the description on \"Layer One Strength\" for guidelines on this property."
			),

			array(
				"type" => "checkbox",
				"class" => "",
				"heading" => "Video Background",
				"value" => array("Enable Video Background?" => "use_video" ),
				"param_name" => "video_bg",
				"description" => ""
			),

			array(
				"type" => "checkbox",
				"class" => "",
				"heading" => "Video Color Overlay",
				"value" => array("Enable a color overlay ontop of your video?" => "true" ),
				"param_name" => "enable_video_color_overlay",
				"description" => "",
				"dependency" => Array('element' => "video_bg", 'value' => array('use_video'))
			),

			array(
				"type" => "colorpicker",
				"class" => "",
				"heading" => "Overlay Color",
				"param_name" => "video_overlay_color",
				"value" => "",
				"description" => "",
				"dependency" => Array('element' => "enable_video_color_overlay", 'value' => array('true'))
			),

			array(
				"type" => "textfield",
				"class" => "",
				"heading" => "WebM File URL",
				"value" => "",
				"param_name" => "video_webm",
				"description" => "You must include this format & the mp4 format to render your video with cross browser compatibility. OGV is optional.
			Video must be in a 16:9 aspect ratio.",
				"dependency" => Array('element' => "video_bg", 'value' => array('use_video'))
			),

			array(
				"type" => "textfield",
				"class" => "",
				"heading" => "MP4 File URL",
				"value" => "",
				"param_name" => "video_mp4",
				"description" => "Enter the URL for your mp4 video file here",
				"dependency" => Array('element' => "video_bg", 'value' => array('use_video'))
			),

			array(
				"type" => "textfield",
				"class" => "",
				"heading" => "OGV File URL",
				"value" => "",
				"param_name" => "video_ogv",
				"description" => "Enter the URL for your ogv video file here",
				"dependency" => Array('element' => "video_bg", 'value' => array('use_video'))
			),

			array(
				"type" => "attach_image",
				"class" => "",
				"heading" => "Video Preview Image",
				"value" => "",
				"param_name" => "video_image",
				"description" => "",
				"dependency" => Array('element' => "video_bg", 'value' => array('use_video'))
			),

			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => "Text Color",
				"param_name" => "text_color",
				"value" => array(
					"Dark" => "dark",
					"Light" => "light",
					"Custom" => "custom"
				),
				'save_always' => true
			),

			array(
				"type" => "colorpicker",
				"class" => "",
				"heading" => "Custom Text Color",
				"param_name" => "custom_text_color",
				"value" => "",
				"description" => "",
				"dependency" => Array('element' => "text_color", 'value' => array('custom'))
			),

			array(
				"type" => "dropdown",
				"class" => "",
				'save_always' => true,
				"heading" => "Text Alignment",
				"param_name" => "text_align",
				"value" => array(
					"Left" => "left",
					"Center" => "center",
					"Right" => "right"
				)
			),

			array(
				"type" => "textfield",
				"class" => "",
				"heading" => "Padding Top",
				"value" => "",
				"param_name" => "top_padding",
				"description" => "Don't include \"px\" in your string. e.g \"40\" - However you can also use a percent value in which case a \"%\" would be needed at the end e.g. \"10%\""
			),

			array(
				"type" => "textfield",
				"class" => "",
				"heading" => "Padding Bottom",
				"value" => "",
				"param_name" => "bottom_padding",
				"description" => "Don't include \"px\" in your string. e.g \"40\" - However you can also use a percent value in which case a \"%\" would be needed at the end e.g. \"10%\""
			),

			array(
				"type" => "textfield",
				"class" => "",
				"heading" => "Extra Class Name",
				"param_name" => "class",
				"value" => ""
			),

			array(
				"type" => "textfield",
				"class" => "",
				"heading" => "Row ID",
				"param_name" => "id",
				"value" => "",
				"description" => "Use this to option to add an ID onto your row. This can then be used to target the row with CSS or as an anchor point to scroll to when the relevant link is clicked."
			),
			array(
				"type" => "checkbox",
				"class" => "",
				"group" => "Color Overlay",
				"heading" => "Enable Gradient?",
				"value" => array("Yes, please" => "true" ),
				"param_name" => "enable_gradient",
				"description" => ""
			),
			array(
				"type" => "colorpicker",
				"class" => "",
				"heading" => "Color Overlay",
				"param_name" => "color_overlay",
				"value" => "",
				"group" => "Color Overlay",
				"description" => ""
			),
			array(
				"type" => "colorpicker",
				"class" => "",
				"heading" => "Color Overlay 2",
				"param_name" => "color_overlay_2",
				"value" => "",
				"group" => "Color Overlay",
				"description" => "",
				"dependency" => Array('element' => "enable_gradient", 'not_empty' => true)
			),
			array(
				"type" => "dropdown",
				"class" => "",
				'save_always' => true,
				"heading" => "Gradient Direction",
				"param_name" => "gradient_direction",
				"group" => "Color Overlay",
				"value" => array(
					"Left to Right" => "left_to_right",
					"Left Top to Right Bottom" => "left_t_to_right_b",
					"Left Bottom to Right Top" => "left_b_to_right_t",
					"Bottom to Top" => 'top_to_bottom'
				),
				"dependency" => Array('element' => "enable_gradient", 'not_empty' => true)
			),
			array(
				"type" => "dropdown",
				"class" => "",
				'save_always' => true,
				"group" => "Color Overlay",
				"heading" => "Overlay Strength",
				"param_name" => "overlay_strength",
				"value" => array(
					"Light" => "0.3",
					"Medium" => "0.5",
					"Heavy" => "0.8",
					"Very Heavy" => "0.95",
					"Solid" => '1'
				)
			)
		
		),
		'js_view' => 'VcRowView'
	));




	if(!empty($options['header-inherit-row-color']) && $options['header-inherit-row-color'] == '1') {
		vc_add_param("vc_row", array(
			"type" => "checkbox",
			"class" => "",
			"heading" => "Exclude Row From Header Color Inheritance",
			"value" => array("Exclude this row from passing its background/text colors to the header" => "true" ),
			"param_name" => "exclude_row_header_color_inherit",
			"description" => ""
		));
	}


	global $vc_column_width_list;
	$vc_column_width_list = array(
		__( '1 column - 1/12', 'js_composer' ) => '1/12',
		__( '2 columns - 1/6', 'js_composer' ) => '1/6',
		__( '3 columns - 1/4', 'js_composer' ) => '1/4',
		__( '4 columns - 1/3', 'js_composer' ) => '1/3',
		__( '5 columns - 5/12', 'js_composer' ) => '5/12',
		__( '6 columns - 1/2', 'js_composer' ) => '1/2',
		__( '7 columns - 7/12', 'js_composer' ) => '7/12',
		__( '8 columns - 2/3', 'js_composer' ) => '2/3',
		__( '9 columns - 3/4', 'js_composer' ) => '3/4',
		__( '10 columns - 5/6', 'js_composer' ) => '5/6',
		__( '11 columns - 11/12', 'js_composer' ) => '11/12',
		__( '12 columns - 1/1', 'js_composer' ) => '1/1'
	);

	vc_map( array(
		'name' => __( 'Column', 'js_composer' ),
		'base' => 'vc_column',
		'is_container' => true,
		'content_element' => false,
		'params' => array(
			array(
				"type" => "checkbox",
				"class" => "",
				"heading" => "Enable Animation",
				"value" => array("Enable Column Animation?" => "true" ),
				"param_name" => "enable_animation",
				"description" => ""
			),

			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => "Animation",
				"param_name" => "animation",
				'save_always' => true,
				"value" => array(
					 "None" => "none",
				     "Fade In" => "fade-in",
			  		 "Fade In From Left" => "fade-in-from-left",
			  		 "Fade In Right" => "fade-in-from-right",
			  		 "Fade In From Bottom" => "fade-in-from-bottom",
			  		 "Grow In" => "grow-in",
			  		 "Flip In" => "flip-in",
			  		 "Reveal From Right" => "reveal-from-right",
			  		 "Reveal From Bottom" => "reveal-from-bottom",
			  		 "Reveal From Left" => "reveal-from-left",
			  		 "Reveal From Top" => "reveal-from-top"
				),
				"dependency" => Array('element' => "enable_animation", 'not_empty' => true)
			),

			array(
				"type" => "textfield",
				"class" => "",
				"heading" => "Animation Delay",
				"param_name" => "delay",
				"admin_label" => false,
				"description" => "",
				"dependency" => Array('element' => "enable_animation", 'not_empty' => true)
			),

			array(
				"type" => "checkbox",
				"class" => "",
				"heading" => "Boxed Column",
				"value" => array("Boxed Style" => "true" ),
				"param_name" => "boxed",
				"description" => ""
			),

			array(
				"type" => "checkbox",
				"class" => "",
				"heading" => "Centered Content",
				"value" => array("Centered Content Alignment" => "true" ),
				"param_name" => "centered_text",
				"description" => ""
			),

			array(
				"type" => "dropdown",
				"class" => "",
				'save_always' => true,
				"heading" => "Column Padding",
				"param_name" => "column_padding",
				"value" => array(
					"None" => "no-extra-padding",
					"1%" => "padding-1-percent",
					"2%" => "padding-2-percent",
					"3%" => "padding-3-percent",
					"4%" => "padding-4-percent",
					"5%" => "padding-5-percent",
					"6%" => "padding-6-percent",
					"7%" => "padding-7-percent",
					"8%" => "padding-8-percent",
					"9%" => "padding-9-percent",
					"10%" => "padding-10-percent",
					"11%" => "padding-11-percent",
					"12%" => "padding-12-percent",
					"13%" => "padding-13-percent",
					"14%" => "padding-14-percent",
					"15%" => "padding-15-percent"
				),
				"description" => "When using the full width content row type or providing a background color/image for the column, you have the option to define the amount of padding your column will receive."
			),

			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => "Column Padding Position",
				"param_name" => "column_padding_position",
				'save_always' => true,
				"value" => array(
					"All Sides" => 'all',
					'Top' => "top",
					'Right' => 'right',
					'Left' => 'left',
					'Bottom' => 'bottom',
					'Left & Right' => 'left-right',
					'Top & Right' => 'top-right',
					'Top & Left' => 'top-left',
					'Top & Bottom' => 'top-bottom',
					'Bottom & Right' => 'bottom-right',
					'Bottom & Left' => 'bottom-left'
				),
				"description" => "Use this to fine tune where the column padding will take effect"
			),

			array(
				"type" => "colorpicker",
				"class" => "",
				"heading" => "Background Color",
				"param_name" => "background_color",
				"value" => "",
				"description" => "",
			),

			array(
				"type" => "dropdown",
				"class" => "",
				'save_always' => true,
				"heading" => "Background Color Opacity",
				"param_name" => "background_color_opacity",
				"value" => array(
					"1" => "1",
					"0.9" => "0.9",
					"0.8" => "0.8",
					"0.7" => "0.7",
					"0.6" => "0.6",
					"0.5" => "0.5",
					"0.4" => "0.4",
					"0.3" => "0.3",
					"0.2" => "0.2",
					"0.1" => "0.1",
				)
				
			),

			array(
				"type" => "colorpicker",
				"class" => "",
				"heading" => "Background Color Hover",
				"param_name" => "background_color_hover",
				"value" => "",
				"description" => "",
			),

			array(
				"type" => "dropdown",
				"class" => "",
				'save_always' => true,
				"heading" => "Background Hover Color Opacity",
				"param_name" => "background_hover_color_opacity",
				"value" => array(
					"1" => "1",
					"0.9" => "0.9",
					"0.8" => "0.8",
					"0.7" => "0.7",
					"0.6" => "0.6",
					"0.5" => "0.5",
					"0.4" => "0.4",
					"0.3" => "0.3",
					"0.2" => "0.2",
					"0.1" => "0.1",
				)
				
			),


			array(
				"type" => "fws_image",
				"class" => "",
				"heading" => "Background Image",
				"param_name" => "background_image",
				"value" => "",
				"description" => "",
			),

			array(
				"type" => "checkbox",
				"class" => "",
				"heading" => "Scale Background Image To Column",
				"value" => array("Enable" => "true" ),
				"param_name" => "enable_bg_scale",
				"description" => "",
				"dependency" => Array('element' => "background_image", 'not_empty' => true)
			),

			array(
				"type" => "colorpicker",
				"class" => "",
				"heading" => "Font Color",
				"param_name" => "font_color",
				"value" => "",
				"description" => ""
			),

			array(
				"type" => "textfield",
				"class" => "",
				"heading" => "Column Link",
				"param_name" => "column_link",
				"admin_label" => false,
				"description" => "If you wish for this column to link somewhere, enter the URL in here",
			),

			array(
				"type" => "textfield",
				"class" => "",
				"heading" => "Margin Top",
				"value" => "",
				"param_name" => "top_margin",
				"description" => "Don't include \"px\" in your string. e.g \"40\" - However you can also use a percent value in which case a \"%\" would be needed at the end e.g. \"10%\". Negative Values are also accepted."
			),

			array(
				"type" => "textfield",
				"class" => "",
				"heading" => "Margin Bottom",
				"value" => "",
				"param_name" => "bottom_margin",
				"description" => "Don't include \"px\" in your string. e.g \"40\" - However you can also use a percent value in which case a \"%\" would be needed at the end e.g. \"10%\". Negative Values are also accepted."
			),

			array(
				"type" => "textfield",
				"class" => "",
				"heading" => "Extra Class Name",
				"param_name" => "el_class",
				"value" => ""
			),
			array(
				'type' => 'dropdown',
				'save_always' => true,
				'heading' => __( 'Width', 'js_composer' ),
				'param_name' => 'width',
				'value' => $vc_column_width_list,
				'group' => __( 'Responsive Options', 'js_composer' ),
				'description' => __( 'Select column width.', 'js_composer' ),
				'std' => '1/1'
			),
			array(
				'type' => 'column_offset',
				'heading' => __( 'Responsiveness', 'js_composer' ),
				'param_name' => 'offset',
				'group' => __( 'Responsive Options', 'js_composer' ),
				'description' => __( 'Adjust column for different screen sizes. Control width, offset and visibility settings.', 'js_composer' )
			)
		),
		'js_view' => 'VcColumnView'
	) );

	vc_map( array(
		"name" => __( "Column", "js_composer" ),
		"base" => "vc_column_inner",
		"class" => "",
		"icon" => "",
		"wrapper_class" => "",
		"controls" => "full",
		"allowed_container_element" => false,
		"content_element" => false,
		"is_container" => true,
		"params" => array(
			array(
				"type" => "checkbox",
				"class" => "",
				"heading" => "Enable Animation",
				"value" => array("Enable Column Animation?" => "true" ),
				"param_name" => "enable_animation",
				"description" => ""
			),

			array(
				"type" => "dropdown",
				"class" => "",
				'save_always' => true,
				"heading" => "Animation",
				"param_name" => "animation",
				"value" => array(
					 "None" => "none",
				     "Fade In" => "fade-in",
			  		 "Fade In From Left" => "fade-in-from-left",
			  		 "Fade In Right" => "fade-in-from-right",
			  		 "Fade In From Bottom" => "fade-in-from-bottom",
			  		 "Grow In" => "grow-in",
			  		 "Flip In" => "flip-in"			
				),
				"dependency" => Array('element' => "enable_animation", 'not_empty' => true)
			),

			array(
				"type" => "textfield",
				"class" => "",
				"heading" => "Animation Delay",
				"param_name" => "delay",
				"admin_label" => false,
				"description" => "",
				"dependency" => Array('element' => "enable_animation", 'not_empty' => true)
			),

			array(
				"type" => "checkbox",
				"class" => "",
				"heading" => "Boxed Column",
				"value" => array("Boxed Style" => "true" ),
				"param_name" => "boxed",
				"description" => ""
			),

			array(
				"type" => "fws_image",
				"class" => "",
				"heading" => "Background Image",
				"param_name" => "background_image",
				"value" => "",
				"description" => "",
			),

			array(
				"type" => "dropdown",
				"class" => "",
				'save_always' => true,
				"heading" => "Column Padding",
				"param_name" => "column_padding",
				"value" => array(
					"None" => "no-extra-padding",
					"1%" => "padding-1-percent",
					"2%" => "padding-2-percent",
					"3%" => "padding-3-percent",
					"4%" => "padding-4-percent",
					"5%" => "padding-5-percent",
					"6%" => "padding-6-percent",
					"7%" => "padding-7-percent",
					"8%" => "padding-8-percent",
					"9%" => "padding-9-percent",
					"10%" => "padding-10-percent",
				),
				"description" => "When using the full width content row type or providing a background color/image for the column, you have the option to define the amount of padding your column will receive."
			),

			array(
				"type" => "dropdown",
				"class" => "",
				'save_always' => true,
				"heading" => "Column Padding Position",
				"param_name" => "column_padding_position",
				"value" => array(
					"All Sides" => 'all',
					'Top' => "top",
					'Right' => 'right',
					'Left' => 'left',
					'Bottom' => 'bottom',
					'Left & Right' => 'left-right',
					'Top & Right' => 'top-right',
					'Top & Left' => 'top-left',
					'Top & Bottom' => 'top-bottom',
					'Bottom & Right' => 'bottom-right',
					'Bottom & Left' => 'bottom-left',
				),
				"description" => "Use this to fine tune where the column padding will take effect"
			),

			array(
				"type" => "colorpicker",
				"class" => "",
				"heading" => "Background Color",
				"param_name" => "background_color",
				"value" => "",
				"description" => "",
			),

			array(
				"type" => "dropdown",
				"class" => "",
				'save_always' => true,
				"heading" => "Background Color Opacity",
				"param_name" => "background_color_opacity",
				"value" => array(
					"1" => "1",
					"0.9" => "0.9",
					"0.8" => "0.8",
					"0.7" => "0.7",
					"0.6" => "0.6",
					"0.5" => "0.5",
					"0.4" => "0.4",
					"0.3" => "0.3",
					"0.2" => "0.2",
					"0.1" => "0.1",
				)
				
			),


			array(
				"type" => "checkbox",
				"class" => "",
				"heading" => "Centered Content",
				"value" => array("Centered Content Alignment" => "true" ),
				"param_name" => "centered_text",
				"description" => ""
			),

			array(
				"type" => "textfield",
				"class" => "",
				"heading" => "Column Link",
				"param_name" => "column_link",
				"admin_label" => false,
				"description" => "If you wish for this column to link somewhere, enter the URL in here",
			),

			array(
				"type" => "textfield",
				"class" => "",
				"heading" => "Extra Class Name",
				"param_name" => "el_class",
				"value" => ""
			),

			array(
				'type' => 'dropdown',
				'save_always' => true,
				'heading' => __( 'Width', 'js_composer' ),
				'param_name' => 'width',
				'value' => $vc_column_width_list,
				'group' => __( 'Responsive Options', 'js_composer' ),
				'description' => __( 'Select column width.', 'js_composer' ),
				'std' => '1/1'
			),
			array(
				'type' => 'column_offset',
				'heading' => __( 'Responsiveness', 'js_composer' ),
				'param_name' => 'offset',
				'group' => __( 'Responsive Options', 'js_composer' ),
				'description' => __( 'Adjust column for different screen sizes. Control width, offset and visibility settings.', 'js_composer' )
			)
		),
		"js_view" => 'VcColumnView'
	) );






	//inner row class fix
	vc_remove_param("vc_row_inner", "el_class");


	vc_add_param("vc_row_inner", array(
		"type" => "textfield",
		"class" => "",
		"heading" => "Extra Class Name",
		"param_name" => "class",
		"value" => ""
	));

	vc_add_param("vc_row_inner", array(
				"type" => "textfield",
				"class" => "",
				"heading" => "Padding Top",
				"value" => "",
				"param_name" => "top_padding",
				"description" => "Don't include \"px\" in your string. e.g \"40\" - However you can also use a percent value in which case a \"%\" would be needed at the end e.g. \"10%\""
	));

	vc_add_param("vc_row_inner", array(
				"type" => "textfield",
				"class" => "",
				"heading" => "Padding Bottom",
				"value" => "",
				"param_name" => "bottom_padding",
				"description" => "Don't include \"px\" in your string. e.g \"40\" - However you can also use a percent value in which case a \"%\" would be needed at the end e.g. \"10%\""
	));


	if(nectar_has_shortcode('full_width_section')) {  

	require_once vc_path_dir('SHORTCODES_DIR', 'vc-row.php');

	class WPBakeryShortCode_Full_Width_Section extends WPBakeryShortCode_VC_Row {
			
				
		public function contentAdmin($atts, $content = null) {
	        $width = $el_class = '';
	        extract(shortcode_atts($this->predefined_atts, $atts));

	        $output = '';

	        $column_controls = $this->getColumnControls($this->settings('controls'));

	        for ( $i=0; $i < count($width); $i++ ) {
	            $output .= '<div'.$this->customAdminBockParams().' data-element_type="vc_row" class="'.$this->settings['base'].' wpb_vc_row wpb_sortable">';
	            $output .= str_replace("%column_size%", 1, $column_controls);
	            $output .= '<div class="wpb_element_wrapper">';
	            $output .= '<div class="vc_row-fluid vc_row wpb_row_container vc_container_for_children">';
	            if($content=='' && !empty($this->settings["default_content_in_template"])) {
	                $output .= do_shortcode( shortcode_unautop($this->settings["default_content_in_template"]) );
	            } else {
	                $output .= do_shortcode( shortcode_unautop($content) );

	            }
	            $output .= '</div>';
	            if ( isset($this->settings['params']) ) {
	                $inner = '';
	                foreach ($this->settings['params'] as $param) {
	                    $param_value = isset($$param['param_name']) ? $$param['param_name'] : '';
	                    if ( is_array($param_value)) {
	                        // Get first element from the array
	                        reset($param_value);
	                        $first_key = key($param_value);
	                        $param_value = $param_value[$first_key];
	                    }
	                    $inner .= $this->singleParamHtmlHolder($param, $param_value);
	                }
	                $output .= $inner;
	            }
	            $output .= '</div>';
	            $output .= '</div>';
	        }

	        return $output;
	    }	
		
	}
		vc_map( array(
				"name" => "Full Width Section",
				"base" => "full_width_section",
				"class" => "wpb_vc_row",
				"is_container" => true,
		 		"icon" => "icon-wpb-row",
		 		"show_settings_on_create" => false,
				"category" => __('Nectar Elements', 'js_composer'),
				'js_view' => 'VcRowView',
				"content_element" => false,
			    'default_content' => '[vc_column width="1/1"]%content%[/vc_column]',
			    'params' => array( 
				    	array(
						"type" => "dropdown",
						"class" => "",
						"heading" => "Type",
						"param_name" => "type",
						"value" => array(
							"Full Width Background" => "full_width_background",
							"Full Width Content" => "full_width_content",	
							"In Container" => "in_container"
						)
					)
			    )
		));





		vc_add_param("full_width_section", array(
			"type" => "checkbox",
			"class" => "",
			"heading" => "Vetical Align Columns",
			"value" => array("Make all columns in this row vertically aligned?" => "true" ),
			"param_name" => "vertically_center_columns",
			"description" => "",
			"dependency" => Array('element' => "type", 'value' => array('full_width_content'))
		));


		vc_add_param("full_width_section", array(
			"type" => "fws_image",
			"class" => "",
			"heading" => "Background Image",
			"param_name" => "image_url",
			"value" => "",
			"description" => ""
		));

		vc_add_param("full_width_section", array(
			"type" => "dropdown",
			"class" => "",
			"heading" => "Background Position",
			"param_name" => "bg_pos",
			"value" => array(
				 "Left Top" => "Left Top",
		  		 "Left Center" => "Left Center",
		  		 "Left Bottom" => "Left Bottom",
		  		 "Center Top" => "Center Top",
		  		 "Center Center" => "Center Center",
		  		 "Center Bottom" => "Center Bottom",
		  		 "Right Top" => "Right Top",
		  		 "Right Center" => "Right Center",
		  		 "Right Bottom" => "Right Bottom"
			),
			"dependency" => Array('element' => "image_url", 'not_empty' => true)
		));

		vc_add_param("full_width_section", array(
			"type" => "dropdown",
			"class" => "",
			"heading" => "Background Repeat",
			"param_name" => "bg_repeat",
			"value" => array(
				"No Repeat" => "No-Repeat",
				"Repeat" => "Repeat"
			),
			"dependency" => Array('element' => "image_url", 'not_empty' => true)
		));

		vc_add_param("full_width_section", array(
			"type" => "checkbox",
			"class" => "",
			"heading" => "Parallax Background",
			"value" => array("Enable Parallax Background?" => "true" ),
			"param_name" => "parallax_bg",
			"description" => "",
			"dependency" => Array('element' => "image_url", 'not_empty' => true)
		));

		vc_add_param("full_width_section", array(
			"type" => "colorpicker",
			"class" => "",
			"heading" => "Background Color",
			"param_name" => "background_color",
			"value" => "",
			"description" => ""
		));


		if(!empty($options['header-inherit-row-color']) && $options['header-inherit-row-color'] == '1') {
			vc_add_param("full_width_section", array(
				"type" => "checkbox",
				"class" => "",
				"heading" => "Exclude Row From Header Color Inheritance",
				"value" => array("Exclude this row from passing its background/text colors to the header" => "true" ),
				"param_name" => "exclude_row_header_color_inherit",
				"description" => ""
			));
		}

		vc_add_param("full_width_section", array(
			"type" => "checkbox",
			"class" => "",
			"heading" => "Video Background",
			"value" => array("Enable Video Background?" => "use_video" ),
			"param_name" => "video_bg",
			"description" => ""
		));

		vc_add_param("full_width_section", array(
			"type" => "checkbox",
			"class" => "",
			"heading" => "Video Color Overlay",
			"value" => array("Enable a color overlay ontop of your video?" => "true" ),
			"param_name" => "enable_video_color_overlay",
			"description" => "",
			"dependency" => Array('element' => "video_bg", 'value' => array('use_video'))
		));

		vc_add_param("full_width_section", array(
			"type" => "colorpicker",
			"class" => "",
			"heading" => "Overlay Color",
			"param_name" => "video_overlay_color",
			"value" => "",
			"description" => "",
			"dependency" => Array('element' => "enable_video_color_overlay", 'value' => array('true'))
		));

		vc_add_param("full_width_section", array(
			"type" => "textfield",
			"class" => "",
			"heading" => "WebM File URL",
			"value" => "",
			"param_name" => "video_webm",
			"description" => "You must include this format & the mp4 format to render your video with cross browser compatibility. OGV is optional.
		Video must be in a 16:9 aspect ratio.",
			"dependency" => Array('element' => "video_bg", 'value' => array('use_video'))
		));

		vc_add_param("full_width_section", array(
			"type" => "textfield",
			"class" => "",
			"heading" => "MP4 File URL",
			"value" => "",
			"param_name" => "video_mp4",
			"description" => "Enter the URL for your mp4 video file here",
			"dependency" => Array('element' => "video_bg", 'value' => array('use_video'))
		));

		vc_add_param("full_width_section", array(
			"type" => "textfield",
			"class" => "",
			"heading" => "OGV File URL",
			"value" => "",
			"param_name" => "video_ogv",
			"description" => "Enter the URL for your ogv video file here",
			"dependency" => Array('element' => "video_bg", 'value' => array('use_video'))
		));

		vc_add_param("full_width_section", array(
			"type" => "attach_image",
			"class" => "",
			"heading" => "Video Preview Image",
			"value" => "",
			"param_name" => "video_image",
			"description" => "",
			"dependency" => Array('element' => "video_bg", 'value' => array('use_video'))
		));

		vc_add_param("full_width_section", array(
			"type" => "dropdown",
			"class" => "",
			'save_always' => true,
			"heading" => "Text Color",
			"param_name" => "text_color",
			"value" => array(
				"Light" => "light",
				"Dark" => "dark",
				"Custom" => "custom"
			)
		));

		vc_add_param("full_width_section", array(
			"type" => "colorpicker",
			"class" => "",
			"heading" => "Custom Text Color",
			"param_name" => "custom_text_color",
			"value" => "",
			"description" => "",
			"dependency" => Array('element' => "text_color", 'value' => array('custom'))
		));

		vc_add_param("full_width_section", array(
			"type" => "dropdown",
			"class" => "",
			"heading" => "Text Alignment",
			"param_name" => "text_align",
			"value" => array(
				"Left" => "left",
				"Center" => "center",
				"Right" => "right"
			)
		));

		vc_add_param("full_width_section", array(
			"type" => "textfield",
			"class" => "",
			"heading" => "Padding Top",
			"value" => "",
			"param_name" => "top_padding",
			"description" => ""
		));

		vc_add_param("full_width_section", array(
			"type" => "textfield",
			"class" => "",
			"heading" => "Padding Bottom",
			"value" => "",
			"param_name" => "bottom_padding",
			"description" => ""
		));

		vc_add_param("full_width_section", array(
			"type" => "textfield",
			"class" => "",
			"heading" => "Extra Class Name",
			"param_name" => "class",
			"value" => ""
		));

	}






	// Video
	vc_remove_param("vc_video", "title");


	// Text block
	vc_remove_param("vc_column_text", "css_animation");


	if(nectar_has_shortcode('nectar_slider')) { 

		// Nectar Slider
		$slider_locations = ($is_admin) ? get_terms('slider-locations') : array('All' => 'all');
		$locations = array();

		if($is_admin) {
			foreach ($slider_locations as $location) {
				$locations[$location->name] = $location->name;
			}
		} else {
			$locations['All'] = 'all';
		}

		if (empty($locations)) {
			$location_desc = 
		      '<div class="alert">' .
			 __('You currently don\'t have any Slider Locations setup. Please create some and add assign slides to them before using this!',NECTAR_THEME_NAME). 
			'<br/><br/>
			<a href="' . admin_url('edit.php?post_type=nectar_slider') . '">'. __('Link to Nectar Slider', NECTAR_THEME_NAME) . '</a>
			</div>';
		} else { $location_desc = ''; }

		vc_map( array(
		  "name" => __("Nectar Slider", "js_composer"),
		  "base" => "nectar_slider",
		  "icon" => "icon-wpb-nectar-slider",
		  "category" => __('Nectar Elements', 'js_composer'),
		  "description" => __('The jaw-dropping slider by ThemeNectar', 'js_composer'),
		  "weight" => 10,
		  "params" => array(
		    array(
		      "type" => "dropdown",
		      "heading" => __("Select Slider", "js_composer"),
		      "admin_label" => true,
		      "param_name" => "location",
		      "value" => $locations,
		      "description" => $location_desc,
		      'save_always' => true
		    ),
			array(
		      "type" => "textfield",
		      "heading" => __("Slider Height", "js_composer"),
		      "param_name" => "slider_height",
		      "admin_label" => true,
		      "description" => __("Don't include \"px\" in your string. e.g. 650", "js_composer")
		    ),
		    array(
		      "type" => 'checkbox',
		      "heading" => __("Flexible Slider Height", "js_composer"),
		      "param_name" => "flexible_slider_height",
		      "description" => __("Would you like the height of your slider to constantly scale in porportion to the screen size?", "js_composer"),
		      "value" => Array(__("Yes, please", "js_composer") => 'true')
		    ),
		    array(
		      "type" => "textfield",
		      "heading" => __("Minimum Slider Height", "js_composer"),
		      "param_name" => "min_slider_height",
		      "dependency" => Array('element' => "flexible_slider_height", 'not_empty' => true),
		      "description" => __("When using the flexible height option the slider can become very short on mobile devices - use this to ensure it stays tall enough for your content Don't include \"px\" in your string. e.g. 250", "js_composer")
		    ),
		    array(
		      "type" => 'checkbox',
		      "heading" => __("Display Full Width?", "js_composer"),
		      "param_name" => "full_width",
		      "description" => __("Would you like this slider to display the full width of the page?", "js_composer"),
		      "value" => Array(__("Yes, please", "js_composer") => 'true')
		    ),
		    array(
		      "type" => 'checkbox',
		      "heading" => __("Fullscreen Slider?", "js_composer"),
		      "param_name" => "fullscreen",
		      "description" => __("This will cause your slider to resize to always fill the users screen size", "js_composer"),
		      "value" => Array(__("Yes, please", "js_composer") => 'true'),
		      "dependency" => Array('element' => "full_width", 'not_empty' => true)
		    ),
		    array(
		      "type" => 'checkbox',
		      "heading" => __("Display Arrow Navigation?", "js_composer"),
		      "param_name" => "arrow_navigation",
		      "description" => __("Would you like this slider to display arrows on the right and left sides?", "js_composer"),
		      "value" => Array(__("Yes, please", "js_composer") => 'true'),
		      "dependency" => Array('element' => "overall_style", 'value' => 'classic')
		    ),
		     array(
		      "type" => "dropdown",
		      "heading" => __("Slider Next/Prev Button Styling", "js_composer"),
		      "param_name" => "slider_button_styling",
		      "dependency" => Array('element' => "arrow_navigation", 'not_empty' => true),
		      "value" => array(
					'Standard With Slide Count On Hover' => 'btn_with_count',
					'Next/Prev Slide Preview On Hover' => 'btn_with_preview'
		      ),
		      "description" => 'Please select your slider button styling here',
		    ),
		     array(
		      "type" => "dropdown",
		      "heading" => __("Overall Style", "js_composer"),
		      "param_name" => "overall_style",
		      "value" => array(
					'Classic' => 'classic',
					'Directional Based Content Movement' => 'directional'
		      ),
		      'save_always' => true,
		      "description" => 'Please select your overall style here - note that some styles will remove the possibility to control certain options.'
		    ),
		    array(
		      "type" => 'checkbox',
		      "heading" => __("Display Bullet Navigation?", "js_composer"),
		      "param_name" => "bullet_navigation",
		      "description" => __("Would you like this slider to display bullets on the bottom?", "js_composer"),
		      "value" => Array(__("Yes, please", "js_composer") => 'true'),
		      "dependency" => Array('element' => "overall_style", 'value' => 'classic')
		    ),
		     array(
		      "type" => "dropdown",
		      "heading" => __("Bullet Navigation Style", "js_composer"),
		      "param_name" => "bullet_navigation_style",
		      "value" => array(
					'See Through & Solid On Active' => 'see_through',
					'Solid & Scale On Active' => 'scale'
		      ),
		      "description" => 'Please select your overall bullet navigation style here.',
		      "dependency" => Array('element' => "bullet_navigation", 'not_empty' => true)
		    ),
		    array(
		      "type" => 'checkbox',
		      "heading" => __("Enable Swipe on Desktop?", "js_composer"),
		      "param_name" => "desktop_swipe",
		      "description" => __("Would you like this slider to have swipe interaction on desktop?", "js_composer"),
		      "value" => Array(__("Yes, please", "js_composer") => 'true'),
		      "dependency" => Array('element' => "overall_style", 'value' => 'classic')
		    ),
		    array(
		      "type" => 'checkbox',
		      "heading" => __("Parallax Slider?", "js_composer"),
		      "param_name" => "parallax",
		      "description" => __("will only activate if the slider is the <b>top level element</b> in the page", "js_composer"),
		      "value" => Array(__("Yes, please", "js_composer") => 'true')
		    ),
		    array(
		      "type" => 'checkbox',
		      "heading" => __("Loop Slider?", "js_composer"),
		      "param_name" => "loop",
		      "description" => __("Would you like your slider to loop infinitely?", "js_composer"),
		      "value" => Array(__("Yes, please", "js_composer") => 'true'),
		      "dependency" => Array('element' => "overall_style", 'value' => 'classic')
		    ),
		    array(
		      "type" => "dropdown",
		      "heading" => __("Slider Transition", "js_composer"),
		      "param_name" => "slider_transition",
		      "value" => array(
					'Slide' => 'slide',
					'Fade' => 'fade'
		      ),
		      "description" => 'Please select your slider transition here',
		      "dependency" => Array('element' => "overall_style", 'value' => 'classic'),
		      'save_always' => true
		    ),
		    array(
		      "type" => "textfield",
		      "heading" => __("Autorotate?", "js_composer"),
		      "param_name" => "autorotate",
		      "description" => __("If you would like this slider to autorotate, enter the rotation speed in miliseconds here. i.e 5000", "js_composer")
		    ),
		    array(
				"type" => "dropdown",
				"class" => "",
				"heading" => "Button Sizing",
				"param_name" => "button_sizing",
				"value" => array(
					"Regular" => "regular",
					"Large" => "large",
					"Jumbo" => "jumbo"
				),
				'save_always' => true,
				"description" => ""
			)
		  )
		));
	}


	if(nectar_has_shortcode('bar')) { 
		// Horizontal progress bar shortcode
		vc_map( array(
				"name" => "Progress Bar",
				"base" => "bar",
				"icon" => "icon-wpb-progress_bar",
				"allowed_container_element" => 'vc_row',
				"category" => __('Nectar Elements', 'js_composer'),
				"description" => __('Include a horizontal progress bar', 'js_composer'),
				"params" => array(
					array(
						"type" => "textfield",
						"holder" => "div",
						"class" => "",
						"heading" => "Title",
						"param_name" => "title",
						"description" => ""
					),
					array(
						"type" => "textfield",
						"holder" => "div",
						"class" => "",
						"heading" => "Percentage",
						"param_name" => "percent",
						"description" => "Don't include \"%\" in your string - e.g \"70\""
					),
					array(
						"type" => "dropdown",
						"holder" => "div",
						"class" => "",
						'save_always' => true,
						"heading" => "Bar Color",
						"param_name" => "color",
						"value" => array(
							"Accent-Color" => "Accent-Color",
							"Extra-Color-1" => "Extra-Color-1",
							"Extra-Color-2" => "Extra-Color-2",	
							"Extra-Color-3" => "Extra-Color-3"
						),
						"description" => ""
					)

				)
		) );
	}


	// Morphing Outline
	class WPBakeryShortCode_Morphing_Outline extends WPBakeryShortCode { }
	vc_map( array(
			"name" => "Morphing Outline",
			"base" => "morphing_outline",
			"icon" => "icon-wpb-morphing-outline",
			"allowed_container_element" => 'vc_row',
			"category" => __('Nectar Elements', 'js_composer'),
			"description" => __('Wrap some text in a unqiue way to grab attention', 'js_composer'),
			"params" => array(
				array(
			      "type" => "textarea",
			      "holder" => "div",
			      "heading" => __("Text Content", "js_composer"),
			      "param_name" => "content",
			      "value" => __("", "js_composer"),
			      "description" => __("Enter the text that will be wrapped here", "js_composer"),
			      "admin_label" => false
			    ),
				array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => "Border Thickness",
					"param_name" => "border_thickness",
					"description" => "Don't include \"px\" in your string - default is \"5\"",
					"admin_label" => false
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => "Starting Color",
					"param_name" => "starting_color",
					"value" => "",
					"description" => ""
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => "Hover Color",
					"param_name" => "hover_color",
					"value" => "",
					"description" => ""
				)

			)
	) );


	// Morphing Outline
	class WPBakeryShortCode_Split_Line_Heading extends WPBakeryShortCode { }
	vc_map( array(
			"name" => "Split Line Heading",
			"base" => "split_line_heading",
			"icon" => "icon-wpb-split-line-heading",
			"allowed_container_element" => 'vc_row',
			"category" => __('Nectar Elements', 'js_composer'),
			"description" => __('Animated multi line heading', 'js_composer'),
			"params" => array(
				array(
			      "type" => "textarea_html",
			      "holder" => "div",
			      "heading" => __("Text Content", "js_composer"),
			      "param_name" => "content",
			      "value" => __("", "js_composer"),
			      "description" => __("Each Line of this editor will be animated separately. Separate text with the Enter or Return key on your Keyboard.", "js_composer"),
			      "admin_label" => false
			    ),

			)
	) );



	// Divider
	vc_map( array(
			"name" => "Divider",
			"base" => "divider",
			"icon" => "icon-wpb-separator",
			"allowed_container_element" => 'vc_row',
			"category" => __('Nectar Elements', 'js_composer'),
			"description" => __('Create space between your content', 'js_composer'),
			"params" => array(
				array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => "Dividing Height",
					"param_name" => "custom_height",
					"description" => "If you would like to control the specifc number of pixels your divider is, enter it here. Don't enter \"px\", just the numnber e.g. \"20\""
				),
				array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => "Line Type",
					'save_always' => true,
					"param_name" => "line_type",
					"value" => array(
						"No Line" => "No Line",
						"Full Width Line" => "Full Width Line",
						"Small Line" => "Small Line"
					)
				)

			)
	));




	// Single image
	vc_map( array(
	  "name" => __("Single Image", "js_composer"),
	  "base" => "image_with_animation",
	  "icon" => "icon-wpb-single-image",
	  "category" => __('Nectar Elements', 'js_composer'),
	  "description" => __('Simple image with CSS animation', 'js_composer'),
	  "params" => array(
	    array(
	      "type" => "fws_image",
	      "heading" => __("Image", "js_composer"),
	      "param_name" => "image_url",
	      "value" => "",
	      "description" => __("Select image from media library.", "js_composer")
	    ),
	    array(
	      "type" => "dropdown",
	      "heading" => __("Image Alignment", "js_composer"),
	      'save_always' => true,
	      "param_name" => "alignment",
	      "value" => array(__("Align left", "js_composer") => "", __("Align right", "js_composer") => "right", __("Align center", "js_composer") => "center"),
	      "description" => __("Select image alignment.", "js_composer")
	    ),
	    array(
		  "type" => "dropdown",
		  "heading" => __("CSS Animation", "js_composer"),
		  "param_name" => "animation",
		  "admin_label" => true,
		  "value" => array(
			    __("Fade In", "js_composer") => "Fade In", 
			    __("Fade In From Left", "js_composer") => "Fade In From Left", 
			    __("Fade In From Right", "js_composer") => "Fade In From Right", 
			    __("Fade In From Bottom", "js_composer") => "Fade In From Bottom", 
			    __("Grow In", "js_composer") => "Grow In",
			    __("Flip In", "js_composer") => "Flip In",
			    __("None", "js_composer") => "None"
			),
		  'save_always' => true,
		  "description" => __("Select animation type if you want this element to be animated when it enters into the browsers viewport. Note: Works only in modern browsers.", "js_composer")
		),
		array(
	      "type" => "textfield",
	      "heading" => __("Animation Delay", "js_composer"),
	      "param_name" => "delay",
	      "description" => __("Enter delay (in milliseconds) if needed e.g. 150. This parameter comes in handy when creating the animate in \"one by one\" effect in horizontal columns.", "js_composer")
	    ),
	    array(
	      "type" => 'checkbox',
	      "heading" => __("Link to large image?", "js_composer"),
	      "param_name" => "img_link_large",
	      "description" => __("If selected, image will be linked to the bigger image.", "js_composer"),
	      "value" => Array(__("Yes, please", "js_composer") => 'yes')
	    ),
	    array(
	      "type" => "textfield",
	      "heading" => __("Image link", "js_composer"),
	      "param_name" => "img_link",
	      "description" => __("Enter url if you want this image to have link.", "js_composer"),
	      "dependency" => Array('element' => "img_link_large", 'is_empty' => true)
	    ),
	    array(
	      "type" => "dropdown",
	      "heading" => __("Link Target", "js_composer"),
	      "param_name" => "img_link_target",
	      "value" => array(__("Same window", "js_composer") => "_self", __("New window", "js_composer") => "_blank"),
	      "dependency" => Array('element' => "img_link", 'not_empty' => true)
	    ),
	    array(
	      "type" => "textfield",
	      "heading" => __("Extra class name", "js_composer"),
	      "param_name" => "el_class",
	      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer")
	    )
	  )
	));




	// Image Comparision
	class WPBakeryShortCode_Nectar_Image_Comparison extends WPBakeryShortCode {}

	vc_map( array(
	  "name" => __("Image Comparison", "js_composer"),
	  "base" => "nectar_image_comparison",
	  "icon" => "icon-wpb-single-image",
	  "category" => __('Nectar Elements', 'js_composer'),
	  "description" => __('Shows differences in two images', 'js_composer'),
	  "params" => array(
	    array(
	      "type" => "fws_image",
	      "heading" => __("Image One", "js_composer"),
	      "param_name" => "image_url",
	      "value" => "",
	      "description" => __("Select image from media library.", "js_composer")
	    ),
	    array(
	      "type" => "fws_image",
	      "heading" => __("Image Two", "js_composer"),
	      "param_name" => "image_2_url",
	      "value" => "",
	      "description" => __("Select image from media library.", "js_composer")
	    ),
	    array(
	      "type" => "textfield",
	      "heading" => __("Extra class name", "js_composer"),
	      "param_name" => "el_class",
	      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer")
	    )
	  )
	));



	// Portfolio

		$portfolio_types = ($is_admin) ? get_terms('project-type') : array('All' => 'all');

		$types_options = array("All" => "all");
		$types_options_2 = array("Default" => "default");

		if($is_admin) {
			foreach ($portfolio_types as $type) {
				$types_options[$type->name] = $type->slug;
				$types_options_2[$type->name] = $type->slug;
			}

		} else {
			$types_options['All'] = 'all';
			$types_options_2['All'] = 'all';
		}




		vc_map( array(
		  "name" => __("Portfolio", "js_composer"),
		  "base" => "nectar_portfolio",
		  "weight" => 8,
		  "icon" => "icon-wpb-portfolio",
		  "category" => __('Nectar Elements', 'js_composer'),
		  "description" => __('Add a portfolio element', 'js_composer'),
		  "params" => array(
			array(
			  "type" => "dropdown",
			  "heading" => __("Layout", "js_composer"),
			  "param_name" => "layout",
			  "admin_label" => true,
			  "value" => array(
				    "3 Columns" => "3",
				    "4 Columns" => "4",
				    "Fullwidth" => "fullwidth"
				),
			  "description" => __("Please select the layout you would like for your portfolio ", "js_composer"),
			  'save_always' => true
			),
			array(
		      "type" => 'checkbox',
		      "heading" => __("Constrain Max Columns to 4?", "js_composer"),
		      "param_name" => "constrain_max_cols",
		      "description" => __("This will change the max columns to 4 (default is 5 for fullwidth). Activating this will make it easier to create a grid with no empty spaces at the end of the list on all screen sizes.", "js_composer"),
		      "value" => Array(__("Yes, please", "js_composer") => 'true'),
		      "dependency" => Array('element' => "layout", 'value' => 'fullwidth')
		    ),
		    /*
		    array(
		      "type" => 'checkbox',
		      "heading" => __("Remove Column Padding?", "js_composer"),
		      "param_name" => "remove_column_padding",
		      "description" => __("This will allow your projects to sit flush against each other.", "js_composer"),
		      "value" => Array(__("Yes, please", "js_composer") => 'true'),
		      "dependency" => Array('element' => "layout", 'value' => array('3', '4'))
		    ),*/
		    array(
			  "type" => "dropdown_multi",
			  "heading" => __("Portfolio Categories", "js_composer"),
			  "param_name" => "category",
			  "admin_label" => true,
			  "value" => $types_options,
			  'save_always' => true,
			  "description" => __("Please select the categories you would like to display for your portfolio. <br/> You can select multiple categories too (ctrl + click on PC and command + click on Mac).", "js_composer")
			),
			array(
			  "type" => "dropdown",
			  "heading" => __("Starting Category", "js_composer"),
			  "param_name" => "starting_category",
			  "admin_label" => false,
			  "value" => $types_options_2,
			  'save_always' => true,
			  "description" => __("Please select the category you would like you're portfolio to start filtered on.", "js_composer"),
			  "dependency" => Array('element' => "enable_sortable", 'not_empty' => true)
			),
		    array(
			  "type" => "dropdown",
			  "heading" => __("Project Style", "js_composer"),
			  "param_name" => "project_style",
			  "admin_label" => true,
			  'save_always' => true,
			  "value" => array(
				    "Meta below thumb w/ links on hover" => "1",
				    "Meta on hover + entire thumb link" => "2",
				    "Title overlaid w/ zoom effect on hover" => "3",
				    "Title overlaid w/ zoom effect on hover alt" => "5",
				    "Meta from bottom on hover + entire thumb link" => "4",
				    "3D Parallax on hover" => "6"
				),
			  "description" => __("Please select the style you would like your projects to display in ", "js_composer")
			),
			array(
		      "type" => 'checkbox',
		      "heading" => __("Masonry Style", "js_composer"),
		      "param_name" => "masonry_style",
		      "description" => __("This will allow your portfolio items to display in a masonry layout as opposed to a fixed grid. You can define your masonry sizes in each project. <br/> If using the full width layout, will only be active with the alternative project style.", "js_composer"),
		      "value" => Array(__("Yes, please", "js_composer") => 'true')
		    ),
		    array(
		      "type" => 'checkbox',
		      "heading" => __("Enable Sortable", "js_composer"),
		      "param_name" => "enable_sortable",
		      "description" => __("Checking this box will allow your portfolio to display sortable filters", "js_composer"),
		      "value" => Array(__("Yes, please", "js_composer") => 'true')
		    ),
		    array(
		      "type" => 'checkbox',
		      "heading" => __("Horizontal Filters", "js_composer"),
		      "param_name" => "horizontal_filters",
		      "description" => __("This will allow your filters to display horizontally instead of in a dropdown. (Only used if you enable sortable above.)", "js_composer"),
		      "value" => Array(__("Yes, please", "js_composer") => 'true'),
		      "dependency" => Array('element' => "enable_sortable", 'not_empty' => true)
		    ),
		    array(
			  "type" => "dropdown",
			  "heading" => __("Filter Color Scheme", "js_composer"),
			  "param_name" => "filter_color",
			  "value" => array(
			     "Default" => "default",
				 "Accent-Color" => "accent-color",
				 "Extra-Color-1" => "extra-color-1",
				 "Extra-Color-2" => "extra-color-2",	
				 "Extra-Color-3" => "extra-color-3",
				 "Black" => "black"
			   ),
			  'save_always' => true,
			  "dependency" => Array('element' => "enable_sortable", 'not_empty' => true),
			  "description" => __("Please select the color scheme you would like for your filters. Only applies to full width inline filters and regular dropdown filters", "js_composer")
			),

		    array(
		      "type" => 'checkbox',
		      "heading" => __("Enable Pagination", "js_composer"),
		      "param_name" => "enable_pagination",
		      "description" => __("Would you like to enable pagination for this portfolio?", "js_composer"),
		      "value" => Array(__("Yes, please", "js_composer") => 'true')
		    ),
		    array(
			  "type" => "dropdown",
			  "heading" => __("Pagination Type", "js_composer"),
			  "param_name" => "pagination_type",
			  "admin_label" => true,
			  "value" => array(	
				    'Default' => 'default',
				    'Infinite Scroll' => 'infinite_scroll',
				),
			  'save_always' => true,
			  "description" => __("Please select your pagination type here.", "js_composer"),
			  "dependency" => Array('element' => "enable_pagination", 'not_empty' => true)
			),
		    array(
		      "type" => "textfield",
		      "heading" => __("Projects Per Page", "js_composer"),
		      "param_name" => "projects_per_page",
		      "description" => __("How many projects would you like to display per page? <br/> If pagination is not enabled, will simply show this number of projects <br/> Enter as a number example \"20\"", "js_composer")
		    ),
		    array(
		      "type" => 'checkbox',
		      "heading" => __("Lightbox Only", "js_composer"),
		      "param_name" => "lightbox_only",
		      "description" => __("This will remove the single project page from being accessible thus rendering your portfolio into only a gallery.", "js_composer"),
		      "value" => Array(__("Yes, please", "js_composer") => 'true')
		    ),
		    array(
			  "type" => "dropdown",
			  "heading" => __("Load In Animation", "js_composer"),
			  "param_name" => "load_in_animation",
			  'save_always' => true,
			  "value" => array(
				    "None" => "none",
				    "Fade In" => "fade_in",
				    "Fade In From Bottom" => "fade_in_from_bottom"
				),
			  "description" => __("Please select the style you would like your projects to display in ", "js_composer")
			)
		  )
		));





		vc_map( array(
		  "name" => __("Recent Projects", "js_composer"),
		  "base" => "recent_projects",
		  "weight" => 8,
		  "icon" => "icon-wpb-recent-projects",
		  "category" => __('Nectar Elements', 'js_composer'),
		  "description" => __('Show off some recent projects', 'js_composer'),
		  "params" => array(
		    array(
			  "type" => "dropdown_multi",
			  "heading" => __("Portfolio Categories", "js_composer"),
			  "param_name" => "category",
			  "admin_label" => true,
			  "value" => $types_options,
			  'save_always' => true,
			  "description" => __("Please select the categories you would like to display for your recent projects carousel. <br/> You can select multiple categories too (ctrl + click on PC and command + click on Mac).", "js_composer")
			),
		    array(
			  "type" => "dropdown",
			  "heading" => __("Project Style", "js_composer"),
			  "param_name" => "project_style",
			  "admin_label" => true,
			  "value" => array(
				    "Meta below thumb w/ links on hover" => "1",
				    "Meta on hover + entire thumb link" => "2",
				    "Title overlaid w/ zoom effect on hover" => "3",
				    "Meta from bottom on hover + entire thumb link" => "4"
				),
			  'save_always' => true,
			  "description" => __("Please select the style you would like your projects to display in ", "js_composer")
			),
			array(
		      "type" => 'checkbox',
		      "heading" => __("Full Width Carousel", "js_composer"),
		      "param_name" => "full_width",
		      "description" => __("This will make your carousel extend the full width of the page.", "js_composer"),
		      "value" => Array(__("Yes, please", "js_composer") => 'true')
		    ),
		    array(
		      "type" => "textfield",
		      "heading" => __("Heading Text", "js_composer"),
		      "param_name" => "heading",
		      "description" => __("Enter any text you would like for the heading of your carousel", "js_composer")
		    ),
			array(
		      "type" => "textfield",
		      "heading" => __("Page Link Text", "js_composer"),
		      "param_name" => "page_link_text",
		      "description" => __("This will be the text that is in a link leading users to your desired page (will be omitted for full width carousels and an icon will be used instead)", "js_composer")
		    ),
		    array(
		      "type" => "textfield",
		      "heading" => __("Page Link URL", "js_composer"),
		      "param_name" => "page_link_url",
		      "description" => __("Enter portfolio page URL you would like to link to. Remember to include \"http://\"!", "js_composer")
		    ),	
		    array(
			  "type" => "dropdown",
			  "heading" => __("Controls & Text Color", "js_composer"),
			  "param_name" => "control_text_color",
			  "value" => array(
				    "Dark" => "dark",
				    "Light" => "light",
				),
			  'save_always' => true,
			  "description" => __("Please select the color you desire for your carousel controls/heading text.", "js_composer")
			),
		    array(
		      "type" => 'checkbox',
		      "heading" => __("Hide Carousel Controls", "js_composer"),
		      "param_name" => "hide_controls",
		      "description" => __("Checking this box will remove the controls from your carousel", "js_composer"),
		      "value" => Array(__("Yes, please", "js_composer") => 'true')
		    ),
		    array(
		      "type" => "textfield",
		      "heading" => __("Number of Projects To Show", "js_composer"),
		      "param_name" => "number_to_display",
		      "description" => __("Enter as a number example \"6\"", "js_composer")
		    ),
		    array(
		      "type" => 'checkbox',
		      "heading" => __("Lightbox Only", "js_composer"),
		      "param_name" => "lightbox_only",
		      "description" => __("This will remove the single project page from being accessible thus rendering your portfolio into only a gallery.", "js_composer"),
		      "value" => Array(__("Yes, please", "js_composer") => 'true')
		    )
		  )
		));





	// Blog

		$blog_types = ($is_admin) ? get_categories() : array('All' => 'all');

		$blog_options = array("All" => "all");

		if($is_admin) {
			foreach ($blog_types as $type) {
				$blog_options[$type->name] = $type->slug;
			}
		} else {
			$blog_options['All'] = 'all';
		}





		vc_map( array(
		  "name" => __("Blog", "js_composer"),
		  "base" => "nectar_blog",
		  "weight" => 8,
		  "icon" => "icon-wpb-blog",
		  "category" => __('Nectar Elements', 'js_composer'),
		  "description" => __('Display a Blog element', 'js_composer'),
		  "params" => array(
		    array(
			  "type" => "dropdown",
			  "heading" => __("Layout", "js_composer"),
			  "param_name" => "layout",
			  "admin_label" => true,
			  "value" => array(
				    'Standard Blog W/ Sidebar' => 'std-blog-sidebar',
				    'Standard Blog No Sidebar' => 'std-blog-fullwidth',
				    'Masonry Blog W/ Sidebar' => 'masonry-blog-sidebar',
				    'Masonry Blog No Sidebar' => 'masonry-blog-fullwidth',
				    'Masonry Blog Fullwidth' => 'masonry-blog-full-screen-width'
				),
			  'save_always' => true,
			  "description" => __("Please select the layout you desire for your blog", "js_composer")
			),
			array(
			  "type" => "dropdown_multi",
			  "heading" => __("Blog Categories", "js_composer"),
			  "param_name" => "category",
			  "admin_label" => true,
			  "value" => $blog_options,
			  'save_always' => true,
			  "description" => __("Please select the categories you would like to display for your blog. <br/> You can select multiple categories too (ctrl + click on PC and command + click on Mac).", "js_composer")
			),
			array(
		      "type" => 'checkbox',
		      "heading" => __("Enable Pagination", "js_composer"),
		      "param_name" => "enable_pagination",
		      "description" => __("Would you like to enable pagination?", "js_composer"),
		      "value" => Array(__("Yes, please", "js_composer") => 'true')
		    ),
		    array(
			  "type" => "dropdown",
			  "heading" => __("Pagination Type", "js_composer"),
			  "param_name" => "pagination_type",
			  "admin_label" => true,
			  "value" => array(	
				    'Default' => 'default',
				    'Infinite Scroll' => 'infinite_scroll',
				),
			  'save_always' => true,
			  "description" => __("Please select your pagination type here.", "js_composer"),
			  "dependency" => Array('element' => "enable_pagination", 'not_empty' => true)
			),
		    array(
		      "type" => "textfield",
		      "heading" => __("Posts Per Page", "js_composer"),
		      "param_name" => "posts_per_page",
		      "description" => __("How many posts would you like to display per page? <br/> If pagination is not enabled, will simply show this number of posts <br/> Enter as a number example \"10\"", "js_composer")
		    ),
		    array(
			  "type" => "dropdown",
			  "heading" => __("Load In Animation", "js_composer"),
			  "param_name" => "load_in_animation",
			  'save_always' => true,
			  "value" => array(
				    "None" => "none",
				    "Fade In" => "fade_in",
				    "Fade In From Bottom" => "fade_in_from_bottom"
				),
			  "description" => __("Please select the loading animation you would like ", "js_composer")
			)
		  )
		));



		vc_map( array(
		  "name" => __("Recent Posts", "js_composer"),
		  "base" => "recent_posts",
		  "weight" => 8,
		  "icon" => "icon-wpb-recent-posts",
		  "category" => __('Nectar Elements', 'js_composer'),
		  "description" => __('Display your recent blog posts', 'js_composer'),
		  "params" => array(
		  	array(
			  "type" => "dropdown",
			  "heading" => __("Style", "js_composer"),
			  "param_name" => "style",
			  "admin_label" => true,
			  "value" => array(	
				    'Default' => 'default',
				    'Minimal' => 'minimal',
				    'Minimal - Title Only' => 'title_only',
				    'Slider' => 'slider'
				),
			  'save_always' => true,
			  "description" => __("Please select desired style here.", "js_composer")
			),
			array(
		      "type" => "textfield",
		      "heading" => __("Slider Height", "js_composer"),
		      "param_name" => "slider_size",
		      "admin_label" => false,
		      "dependency" => Array('element' => "style", 'value' => 'slider'),
		      "description" => __("Don't include \"px\" in your string. e.g. 650", "js_composer")
		    ),
			array(
			  "type" => "dropdown_multi",
			  "heading" => __("Blog Categories", "js_composer"),
			  "param_name" => "category",
			  "admin_label" => true,
			  "value" => $blog_options,
			  'save_always' => true,
			  "description" => __("Please select the categories you would like to display in your recent posts. <br/> You can select multiple categories too (ctrl + click on PC and command + click on Mac).", "js_composer")
			),
			array(
			  "type" => "dropdown",
			  "heading" => __("Number Of Columns", "js_composer"),
			  "param_name" => "columns",
			  "admin_label" => false,
			  "value" => array(
			  	'4' => '4',
			  	'3' => '3',
			  	'2' => '2',
			  	'1' => '1'
			  ),
			  "dependency" => Array('element' => "style", 'value' => array('default','minimal','title_only')),
			  'save_always' => true,
			  "description" => __("Please select the number of posts you would like to display.", "js_composer")
			),
			array(
		      "type" => "textfield",
		      "heading" => __("Number Of Posts", "js_composer"),
		      "param_name" => "posts_per_page",
		      "description" => __("How many posts would you like to display? <br/> Enter as a number example \"4\"", "js_composer")
		    ),
		    array(
		      "type" => "textfield",
		      "heading" => __("Post Offset", "js_composer"),
		      "param_name" => "post_offset",
		      "description" => __("Optioinally enter a number e.g. \"2\" to offset your posts by - useful for when you're using multiple styles of this element on the same page and would like them to no show duplicate posts", "js_composer")
		    ),
			array(
		      "type" => 'checkbox',
		      "heading" => __("Enable Title Labels", "js_composer"),
		      "param_name" => "title_labels",
		      "description" => __("These labels are defined by you in the \"Blog Options\" tab of your theme options panel.", "js_composer"),
		      "value" => Array(__("Yes, please", "js_composer") => 'true'),
		      "dependency" => Array('element' => "style", 'value' => 'default')
		    ),
		  )
		));








	//WooCommerce Related


	global $woocommerce;

	if($woocommerce) {

		class WPBakeryShortCode_Nectar_Woo_Products extends WPBakeryShortCode {
			
			
		}


		$woo_args = array(
			'taxonomy' => 'product_cat',
		);
		$woo_types = ($is_admin) ? get_categories($woo_args) : array('All' => 'all');
		$woo_options = array("All" => "all");

		if($is_admin) {
			foreach ($woo_types as $type) {
				$woo_options[$type->name] = $type->slug;
			}
		} else {
			$woo_options['All'] = 'all';
		}

		////recent products
		vc_map( array(
		  "name" => __("WooCommerce Products", "js_composer"),
		  "base" => "nectar_woo_products",
		  "weight" => 8,
		  "icon" => "icon-wpb-recent-products",
		  "category" => __('Nectar Elements', 'js_composer'),
		  "description" => __('Display your products', 'js_composer'),
		  "params" => array(
		  	array(
			  "type" => "dropdown",
			  "heading" => __("Product Type", "js_composer"),
			  "param_name" => "product_type",
			  "value" => array(
			  	'All' => 'all',
			  	'Sale Only' => 'sale',
			  	'Featured Only' => 'featured',
			  	'Best Selling Only' => 'best_selling'
			  ),
			  'save_always' => true,
			  "description" => __("Please select the type of products you would like to display.", "js_composer")
			),
			array(
			  "type" => "dropdown_multi",
			  "heading" => __("Product Categories", "js_composer"),
			  "param_name" => "category",
			  "admin_label" => true,
			  "value" => $woo_options,
			  'save_always' => true,
			  "description" => __("Please select the categories you would like to display in your products. <br/> You can select multiple categories too (ctrl + click on PC and command + click on Mac).", "js_composer")
			),
			array(
			  "type" => "dropdown",
			  "heading" => __("Number Of Columns", "js_composer"),
			  "param_name" => "columns",
			  "value" => array(
			  	'4' => '4',
			  	'3' => '3',
			  	'2' => '2',
			  	'1' => '1'
			  ),
			  'save_always' => true,
			  "description" => __("Please select the number of columns you would like to display.", "js_composer")
			),
			array(
		      "type" => "textfield",
		      "heading" => __("Number Of Products", "js_composer"),
		      "param_name" => "per_page",
		       "admin_label" => true,
		      "description" => __("How many posts would you like to display? <br/> Enter as a number example \"4\"", "js_composer")
		    ),
		    array(
		      "type" => 'checkbox',
		      "heading" => __("Enable Carousel Display", "js_composer"),
		      "param_name" => "carousel",
		      "description" => __("This will override your column choice", "js_composer"),
		      "value" => Array(__("Yes, please", "js_composer") => true),
		    ),
		    array(
		      "type" => 'checkbox',
		      "heading" => __("Enable Controls On Hover", "js_composer"),
		      "param_name" => "controls_on_hover",
		      "dependency" => Array('element' => "carousel", 'not_empty' => true),
		      "description" => __("This will add buttons for additional user control over your product carousel", "js_composer"),
		      "value" => Array(__("Yes, please", "js_composer") => true),
		    )
		  )
		));


	}


	// Centered Heading
	vc_map( array(
	  "name" => __("Centered Heading", "js_composer"),
	  "base" => "heading",
	  "icon" => "icon-wpb-centered-heading",
	  "category" => __('Nectar Elements', 'js_composer'),
	  "description" => __('Simple heading', 'js_composer'),
	  "params" => array(
	    array(
	      "type" => "textarea_html",
	      "holder" => "div",
	      "heading" => __("Heading", "js_composer"),
	      "param_name" => "content",
	      "value" => __("", "js_composer")
	    ), 
	    array(
	      "type" => "textfield",
	      "heading" => __("Subtitle", "js_composer"),
	      "param_name" => "subtitle",
	      "description" => __("The subtitle text under the main title", "js_composer")
	    )
	  )
	));




	// video lightbox
	class WPBakeryShortCode_Nectar_Video_Lightbox extends WPBakeryShortCode {}
	vc_map( array(
	  "name" => __("Video Lightbox", "js_composer"),
	  "base" => "nectar_video_lightbox",
	  "icon" => "icon-wpb-video-lightbox",
	  "category" => __('Nectar Elements', 'js_composer'),
	  "description" => __('Add a video lightbox link', 'js_composer'),
	  "params" => array(
	  	array(
		  "type" => "dropdown",
		  "heading" => __("Link Style", "js_composer"),
		  "param_name" => "link_style",
		  "value" => array(
		     "Play Button" => "play_button",
			 "Nectar Button" => "nectar-button"
		   ),
		  'save_always' => true,
		  "admin_label" => true,
		  "description" => __("Please select your link style", "js_composer")	  
		),
		array(
	      "type" => "textfield",
	      "heading" => __("Video URL", "js_composer"),
	      "param_name" => "video_url",
	      "admin_label" => false,
	      "description" => __("The URL to your video on Youtube or Vimeo e.g. <br/> https://vimeo.com/118023315 <br/> https://www.youtube.com/watch?v=6oTurM7gESE", "js_composer")
	    ),
	    array(
	      "type" => "textfield",
	      "heading" => __("Link Text", "js_composer"),
	      "param_name" => "link_text",
	      "admin_label" => false,
	      "dependency" => Array('element' => "link_style", 'value' => "nectar-button"),
	      "description" => __("The text that will be displayed for your link", "js_composer")
	    ),
	   

	     array(
		  "type" => "dropdown",
		  "heading" => __("Color", "js_composer"),
		  "param_name" => "nectar_button_color",
		  "value" => array(
			 "Accent-Color" => "Default-Accent-Color",
			 "Extra-Color-1" => "Default-Extra-Color-1",
			 "Extra-Color-2" => "Default-Extra-Color-2",	
			 "Extra-Color-3" => "Default-Extra-Color-3",
			 "Transparent-Accent-Color" =>  "Transparent-Accent-Color",
			 "Transparent-Extra-Color-1" => "Transparent-Extra-Color-1",
			 "Transparent-Extra-Color-2" => "Transparent-Extra-Color-2",	
			 "Transparent-Extra-Color-3" => "Transparent-Extra-Color-3"
		   ),
		  'save_always' => true,
		  "dependency" => Array('element' => "link_style", 'value' => "nectar-button"),
		  "description" => __("Please select the color you desire", "js_composer")
		),

	  )
	));



		// Milestone
		vc_map( array(
		  "name" => __("Milestone", "js_composer"),
		  "base" => "milestone",
		  "icon" => "icon-wpb-milestone",
		  "category" => __('Nectar Elements', 'js_composer'),
		  "description" => __('Add an animated milestone', 'js_composer'),
		  "params" => array(
			array(
		      "type" => "textfield",
		      "heading" => __("Milestone Number", "js_composer"),
		      "param_name" => "number",
		      "admin_label" => false,
		      "description" => __("The number/count of your milestone e.g. \"13\"", "js_composer")
		    ),
		    array(
		      "type" => "textfield",
		      "heading" => __("Milestone Symbol", "js_composer"),
		      "param_name" => "symbol",
		      "admin_label" => false,
		      "description" => __("An optional symbol to place next to the number counted to. e.g. \"%\" or \"+\"", "js_composer")
		    ),
		    array(
			  "type" => "dropdown",
			  "heading" => __("Milestone Symbol Position", "js_composer"),
			  "param_name" => "symbol_position",
			  "value" => array(
			     "After Number" => "after",
				 "Before Number" => "before",
			   ),
			  'save_always' => true,
			  "description" => __("Please select the position you would like for your symbol.", "js_composer"),
			  "dependency" => Array('element' => "symbol", 'not_empty' => true)
			),
		    array(
		      "type" => "textfield",
		      "heading" => __("Milestone Subject", "js_composer"),
		      "param_name" => "subject",
		      "admin_label" => true,
		      "description" => __("The subject of your milestones e.g. \"Projects Completed\"", "js_composer")
		    ),
		     array(
			  "type" => "dropdown",
			  "heading" => __("Color", "js_composer"),
			  "param_name" => "color",
			  "value" => array(
			     "Default" => "Default",
				 "Accent-Color" => "Accent-Color",
				 "Extra-Color-1" => "Extra-Color-1",
				 "Extra-Color-2" => "Extra-Color-2",	
				 "Extra-Color-3" => "Extra-Color-3"
			   ),
			  'save_always' => true,
			  "description" => __("Please select the color you wish for your milestone to display in.", "js_composer")
			),

		     array(
			  "type" => "dropdown",
			  "heading" => __("Animation Effect", "js_composer"),
			  "param_name" => "effect",
			  "value" => array(
				 "Count To Value" => "count",
				 "Motion Blur Slide In" => "motion_blur"
			   ),
			  'save_always' => true,
			  "description" => __("Please select the animation you would like your milestone to have", "js_composer")
			),
		     array(
		      "type" => "textfield",
		      "heading" => __("Milestone Number Font Size", "js_composer"),
		      "param_name" => "number_font_size",
		      "admin_label" => false,
		      "description" => __("Enter your size in pixels, the default is 62.", "js_composer")
		    ),
		     array(
		      "type" => "textfield",
		      "heading" => __("Milestone Symbol Font Size", "js_composer"),
		      "param_name" => "symbol_font_size",
		      "admin_label" => false,
		      "description" => __("Enter your size in pixels.", "js_composer"),
		      "dependency" => Array('element' => "symbol", 'not_empty' => true)
		    ),
		     array(
			  "type" => "dropdown",
			  "heading" => __("Milestone Symbol Alignment", "js_composer"),
			  "param_name" => "symbol_alignment",
			  "value" => array(
			     "Default" => "Default",
				 "Superscript" => "Superscript",
			   ),
			  'save_always' => true,
			  "description" => __("Please select the alignment you desire for your symbol.", "js_composer"),
			  "dependency" => Array('element' => "symbol", 'not_empty' => true)
			),

		  )
		));



		// Google Map
		class WPBakeryShortCode_Nectar_Gmap extends WPBakeryShortCode {
		}

		vc_map( array(
		  "name" => __("Google Map", "js_composer"),
		  "base" => "nectar_gmap",
		  "icon" => "icon-wpb-map",
		  "category" => __('Nectar Elements', 'js_composer'),
		  "description" => __('Flexible Google Map', 'js_composer'),
		  "params" => array(
		    array(
		      "type" => "textfield",
		      "heading" => __("Map height", "js_composer"),
		      "param_name" => "size",
		      "description" => __('Enter map height in pixels. Example: 200.', "js_composer")
		    ),
		    array(
		      "type" => "textfield",
		      "heading" => __("Map Center Point Latitude", "js_composer"),
		      "param_name" => "map_center_lat",
		      "description" => __("Please enter the latitude for the maps center point.", "js_composer")
		    ),
		    array(
		      "type" => "textfield",
		      "heading" => __("Map Center Point Longitude", "js_composer"),
		      "param_name" => "map_center_lng",
		      "description" => __("Please enter the longitude for the maps center point.", "js_composer")
		    ),
		    
		  	array(
		      "type" => "dropdown",
		      "heading" => __("Map Zoom", "js_composer"),
		      "param_name" => "zoom",
		      'save_always' => true,
		      "value" => array(__("14 - Default", "js_composer") => 14, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20)
		    ),
		    array(
		      "type" => 'checkbox',
		      "heading" => __("Eanble Zoom In/Out", "js_composer"),
		      "param_name" => "enable_zoom",
		      "description" => __("Do you want users to be able to zoom in/out on the map?", "js_composer"),
		      "value" => Array(__("Yes, please", "js_composer") => true),
		    ),
		    
		    array(
		      "type" => "attach_image",
		      "heading" => __("Marker Image", "js_composer"),
		      "param_name" => "marker_image",
		      "value" => "",
		      "description" => __("Select image from media library.", "js_composer")
		    ),
		    array(
		      "type" => 'checkbox',
		      "heading" => __("Marker Animation", "js_composer"),
		      "param_name" => "marker_animation",
		      "description" => __("This will cause your markers to do a quick bounce as they load in.", "js_composer"),
		      "value" => Array(__("Yes, please", "js_composer") => true),
		    ),
		    
		    array(
		      "type" => 'checkbox',
		      "heading" => __("Greyscale Color", "js_composer"),
		      "param_name" => "map_greyscale",
		      "description" => __("Toggle a greyscale color scheme (will also unlock further custom options)", "js_composer"),
		      "value" => Array(__("Yes, please", "js_composer") => true),
		    ),
		    array(
				"type" => "colorpicker",
				"class" => "",
				"heading" => "Map Extra Color",
				"param_name" => "map_color",
				"value" => "",
				"dependency" => Array('element' => "map_greyscale", 'not_empty' => true),
				"description" => "Use this to define a main color that will be used in combination with the greyscale option for your map"
			),
			array(
		      "type" => 'checkbox',
		      "heading" => __("Ultra Flat Map", "js_composer"),
		      "param_name" => "ultra_flat",
		      "dependency" => Array('element' => "map_greyscale", 'not_empty' => true),
		      "description" => __("This removes street/landmark text & some extra details for a clean look", "js_composer"),
		      "value" => Array(__("Yes, please", "js_composer") => true),
		    ),
		    array(
		      "type" => 'checkbox',
		      "heading" => __("Dark Color Scheme", "js_composer"),
		      "param_name" => "dark_color_scheme",
		      "dependency" => Array('element' => "map_greyscale", 'not_empty' => true),
		      "description" => __("Enable this option for a dark colored map (This will override the extra color choice)", "js_composer"),
		      "value" => Array(__("Yes, please", "js_composer") => true),
		    ),
			
		    array(
		      "type" => "textarea",
		      "heading" => __("Map Marker Locations", "js_composer"),
		      "param_name" => "map_markers",
		      "description" => __("Please enter the the list of locations you would like with a latitude|longitude|description format. <br/> Divide values with linebreaks (Enter). Example: <br/> 39.949|-75.171|Our Location <br/> 40.793|-73.954|Our Location #2", "js_composer")
		    ),
		    
		  )
		));







	// Team Member
		vc_map( array(
		  "name" => __("Team Member", "js_composer"),
		  "base" => "team_member",
		  "icon" => "icon-wpb-team-member",
		  "category" => __('Nectar Elements', 'js_composer'),
		  "description" => __('Add a team member element', 'js_composer'),
		  "params" => array(
		 	 array(
		      "type" => "fws_image",
		      "heading" => __("Image", "js_composer"),
		      "param_name" => "image_url",
		      "value" => "",
		      "description" => __("Select image from media library.", "js_composer")
		    ),
		    array(
			  "type" => "dropdown",
			  "heading" => __("Team Member Stlye", "js_composer"),
			  "param_name" => "team_memeber_style",
			  "value" => array(
				 "Meta below" => "meta_below",
				 "Meta overlaid" => "meta_overlaid",
				 "Meta overlaid alt" => "meta_overlaid_alt"
			   ),
			  'save_always' => true,
			  "description" => __("Please select the style you desire for your team member", "js_composer")
			),
		    array(
		      "type" => "textfield",
		      "heading" => __("Name", "js_composer"),
		      "param_name" => "name",
		      "admin_label" => true,
		      "description" => __("Please enter the name of your team member", "js_composer")
		    ),
			array(
		      "type" => "textfield",
		      "heading" => __("Job Position", "js_composer"),
		      "param_name" => "job_position",
		      "admin_label" => true,
		      "description" => __("Please enter the job position for your team member", "js_composer")
		    ),
		    array(
		      "type" => "textarea",
		      "heading" => __("Description", "js_composer"),
		      "param_name" => "description",
		      "description" => __("The main text portion of your team member", "js_composer"),
		      "dependency" => Array('element' => "team_memeber_style", 'value' => array('meta_below'))
		    ),
		    array(
		      "type" => "textarea",
		      "heading" => __("Social Media", "js_composer"),
		      "param_name" => "social",
		      "dependency" => Array('element' => "team_memeber_style", 'value' => array('meta_below')),
		      "description" => __("Enter any social media links with a comma separated list. e.g. Facebook,http://facebook.com, Twitter,http://twitter.com", "js_composer")
		    ),
		    array(
			  "type" => "dropdown",
			  "heading" => __("Team Member Link Type", "js_composer"),
			  "param_name" => "link_element",
			  "value" => array(
				 "None" => "none",
				 "Image" => "image",
				 "Name" => "name",	
				 "Both" => "both"
			   ),
			  'save_always' => true,
			   "dependency" => Array('element' => "team_memeber_style", 'value' => array('meta_below')),
			  "description" => __("Please select how you wish to link your team member to an arbitrary URL", "js_composer")
			),
			array(
		      "type" => "textfield",
		      "heading" => __("Team Memeber Link URL", "js_composer"),
		      "param_name" => "link_url",
		      "admin_label" => false,
		      "description" => __("Please enter the URL for your team member link", "js_composer"),
		      "dependency" => Array('element' => "link_element", 'value' => array('image', 'name', 'both'))
		    ),
		    array(
		      "type" => "textfield",
		      "heading" => __("Team Memeber Link URL", "js_composer"),
		      "param_name" => "link_url_2",
		      "admin_label" => false,
		      "description" => __("Please enter the URL for your team member link", "js_composer"),
		      "dependency" => Array('element' => "team_memeber_style", 'value' => array('meta_overlaid')),
		    ),
		     array(
			  "type" => "dropdown",
			  "heading" => __("Link Color", "js_composer"),
			  "param_name" => "color",
			  "value" => array(
				 "Accent-Color" => "Accent-Color",
				 "Extra-Color-1" => "Extra-Color-1",
				 "Extra-Color-2" => "Extra-Color-2",	
				 "Extra-Color-3" => "Extra-Color-3"
			   ),
			  'save_always' => true,
			   "dependency" => Array('element' => "team_memeber_style", 'value' => array('meta_below')),
			  "description" => __("Please select the color you wish for your social links to display in.", "js_composer")
			)
		  )
		));




	// Team Member
	class WPBakeryShortCode_Fancy_Box extends WPBakeryShortCode { }
		vc_map( array(
		  "name" => __("Fancy Box", "js_composer"),
		  "base" => "fancy_box",
		  "icon" => "icon-wpb-fancy-box",
		  "category" => __('Nectar Elements', 'js_composer'),
		  "description" => __('Add a fancy box element', 'js_composer'),
		  "params" => array(
		 	 array(
		      "type" => "fws_image",
		      "heading" => __("Image", "js_composer"),
		      "param_name" => "image_url",
		      "value" => "",
		      "description" => __("Select a background image from the media library.", "js_composer")
		    ),
		    array(
		      "type" => "textarea_html",
		      "heading" => __("Box Content", "js_composer"),
		      "param_name" => "content",
		      "admin_label" => true,
		      "description" => __("Please enter the text desired for your box", "js_composer")
		    ),
		    array(
		      "type" => "textfield",
		      "heading" => __("Link URL", "js_composer"),
		      "param_name" => "link_url",
		      "admin_label" => false,
		      "description" => __("Please enter the URL you would like for your box to link to", "js_composer")
		    ),
		    array(
		       "type" => "checkbox",
			  "class" => "",
			  "heading" => "Open Link In New Tab",
			  "value" => array("Yes, please" => "true" ),
			  "param_name" => "link_new_tab",
			  "description" => "",
		       "dependency" => Array('element' => "link_url", 'not_empty' => true)
		    ),
		     array(
		      "type" => "textfield",
		      "heading" => __("Link Text", "js_composer"),
		      "param_name" => "link_text",
		      "admin_label" => false,
		      "description" => __("Please enter the text that will be displayed for your box link", "js_composer")
		    ),
		     array(
		      "type" => "textfield",
		      "heading" => __("Min Height", "js_composer"),
		      "param_name" => "min_height",
		      "admin_label" => false,
		      "description" => __("Please enter the minimum height you would like for you box. Enter in number of pixels - Don't enter \"px\", default is \"300\"", "js_composer")
		    ),
		     array(
			  "type" => "dropdown",
			  "heading" => __("Link Color", "js_composer"),
			  "param_name" => "color",
			  "value" => array(
				 "Accent-Color" => "Accent-Color",
				 "Extra-Color-1" => "Extra-Color-1",
				 "Extra-Color-2" => "Extra-Color-2",	
				 "Extra-Color-3" => "Extra-Color-3"
			   ),
			  'save_always' => true,
			  "description" => __("Please select the accent color for your box", "js_composer")
			)
		  )
		));



	require_once vc_path_dir('SHORTCODES_DIR', 'vc-accordion.php');
	require_once vc_path_dir('SHORTCODES_DIR', 'vc-accordion-tab.php');

	/* Accordion block
	---------------------------------------------------------- */
	vc_map( array(
	  "name" => __("Toggle Panels", "js_composer"),
	  "base" => "toggles",
	  "show_settings_on_create" => false,
	  "is_container" => true,
	  "icon" => "icon-wpb-ui-accordion",
	  "category" => __('Nectar Elements', 'js_composer'),
	  "description" => __('jQuery toggles/accordion', 'js_composer'),
	  "params" => array(
	    array(
	      "type" => 'checkbox',
	      "heading" => __("Allow collapsible all", "js_composer"),
	      "param_name" => "accordion",
	      "description" => __("Select checkbox to turn the toggles in an accordion.", "js_composer"),
	      "value" => Array(__("Allow", "js_composer") => 'true')
	    )
	  ),
	  "custom_markup" => '
	  <div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
	  %content%
	  </div>
	  <div class="tab_controls">
	 <a class="add_tab" title="' . __( 'Add section', 'js_composer' ) . '"><span class="vc_icon"></span> <span class="tab-label">' . __( 'Add section', 'js_composer' ) . '</span></a>
	  </div>
	  ',
	  'default_content' => '
	  [toggle title="'.__('Section', "js_composer").'"][/toggle]
	  [toggle title="'.__('Section', "js_composer").'"][/toggle]
	  ',
	  'js_view' => 'VcAccordionView'
	));
	vc_map( array(
	  "name" => __("Section", "js_composer"),
	  "base" => "toggle",
	  "allowed_container_element" => 'vc_row',
	  "is_container" => true,
	  "content_element" => false,
	  "params" => array(
	    array(
	      "type" => "textfield",
	      "heading" => __("Title", "js_composer"),
	      "param_name" => "title",
	      "description" => __("Accordion section title.", "js_composer")
	    ),
	     array(
		  "type" => "dropdown",
		  "heading" => __("Color", "js_composer"),
		  "param_name" => "color",
		  "admin_label" => true,
		  "value" => array(
		     "Default" => "Default",
			 "Accent-Color" => "Accent-Color",
			 "Extra-Color-1" => "Extra-Color-1",
			 "Extra-Color-2" => "Extra-Color-2",	
			 "Extra-Color-3" => "Extra-Color-3"
		   ),
		  'save_always' => true,
		  "description" => __("Please select the color you wish for your toggle to display in.", "js_composer")
		)
	  ),
	  'js_view' => 'VcAccordionTabView'
	) );





	require_once vc_path_dir('SHORTCODES_DIR', 'vc-tabs.php');

	/* Tabs
	---------------------------------------------------------- */
	$tab_id_1 = time().'-1-'.rand(0, 100);
	$tab_id_2 = time().'-2-'.rand(0, 100);
	vc_map( array(
	  "name"  => __("Tabs", "js_composer"),
	  "base" => "tabbed_section",
	  "show_settings_on_create" => false,
	  "is_container" => true,
	  "icon" => "icon-wpb-ui-tab-content",
	  "category" => __('Nectar Elements', 'js_composer'),
	  "description" => __('Tabbed content', 'js_composer'),
	  "params" => array(
	  	 array(
		  "type" => "dropdown",
		  "heading" => __("Style", "js_composer"),
		  "param_name" => "style",
		  "admin_label" => true,
		  "value" => array(
			 "Default" => "default",
			 "Minimal" => "minimal",
			 "Vertical" => "vertical"
		   ),
		  'save_always' => true,
		  "description" => __("Please select the style you desire for your tabbed element.", "js_composer")
		),
	  	 array(
		  "type" => "dropdown",
		  "heading" => __("Alignment", "js_composer"),
		  "param_name" => "alignment",
		  "admin_label" => false,
		  "value" => array(
			 "Left" => "left",
			 "Center" => "center",
			 "Right" => "right"
		   ),
		  'save_always' => true,
		  "dependency" => Array('element' => "style", 'value' => array('minimal','default')),
		  "description" => __("Please select your tabbed alignment", "js_composer")
		),

	  	array(
	      "type" => "textfield",
	      "heading" => __("Optional CTA button", "js_composer"),
	      "param_name" => "cta_button_text",
	      "description" => __("If you wish to include an optional CTA button on your tabbed nav, enter the text here", "js_composer"),
	       "admin_label" => false,
	      "dependency" => Array('element' => "style", 'value' => array('minimal'))
	    ),
	    array(
	      "type" => "textfield",
	      "heading" => __("CTA button link", "js_composer"),
	      "param_name" => "cta_button_link",
	      "description" => __("Enter a URL for your button link here", "js_composer"),
	       "admin_label" => false,
	      "dependency" => Array('element' => "style", 'value' => array('minimal'))
	    ),
	     array(
		  "type" => "dropdown",
		  "heading" => __("CTA Button Color", "js_composer"),
		  "param_name" => "cta_button_style",
		  "admin_label" => false,
		  "value" => array(
			 "Accent-Color" => "accent-color",
			 "Extra-Color-1" => "extra-color-1",
			 "Extra-Color-2" => "extra-color-2",
			 "Extra-Color-3" => "extra-color-3",
		   ),
		  'save_always' => true,
		  "description" => __("Please select the style for your optional CTA button", "js_composer"),
		   "dependency" => Array('element' => "style", 'value' => array('minimal'))
		),


	    array(
	      "type" => "textfield",
	      "heading" => __("Extra class name", "js_composer"),
	      "param_name" => "el_class",
	      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer")
	    )
	  ),
	  "custom_markup" => '
	  <div class="wpb_tabs_holder wpb_holder vc_container_for_children">
	  <ul class="tabs_controls">
	  </ul>
	  %content%
	  </div>'
	  ,
	  'default_content' => '
	  [tab title="'.__('Tab','js_composer').'" id="'.$tab_id_1.'"] I am text block. Click edit button to change this text. [/tab]
	  [tab title="'.__('Tab','js_composer').'" id="'.$tab_id_2.'"] I am text block. Click edit button to change this text. [/tab]
	  ',
	  "js_view" => ($vc_is_wp_version_3_6_more ? 'VcTabsView' : 'VcTabsView35')
	));


	vc_map( array(
	  "name" => __("Tab", "js_composer"),
	  "base" => "tab",
	  "allowed_container_element" => 'vc_row',
	  "is_container" => true,
	  "content_element" => false,
	  "params" => array(
	    array(
	      "type" => "textfield",
	      "heading" => __("Title", "js_composer"),
	      "param_name" => "title",
	      "description" => __("Tab title.", "js_composer")
	    ),
	    array(
	      "type" => "id",
	      "heading" => __("Tab ID", "js_composer"),
	      "param_name" => "id"
	    )
	  ),
	  'js_view' => ($vc_is_wp_version_3_6_more ? 'VcTabView' : 'VcTabView35')
	));




	class WPBakeryShortCode_Testimonial_Slider extends WPBakeryShortCode_Tabbed_Section { }

	$tab_id_1 = time().'-1-'.rand(0, 100);
	$tab_id_2 = time().'-2-'.rand(0, 100);
	vc_map( array(
	  "name"  => __("Testiomonial Slider", "js_composer"),
	  "base" => "testimonial_slider",
	  "show_settings_on_create" => false,
	  "is_container" => true,
	  "icon" => "icon-wpb-testimonial-slider",
	  "category" => __('Nectar Elements', 'js_composer'),
	  "description" => __('An appealing testmonial slider.', 'js_composer'),
	  "params" => array(
	  	 array(
		  "type" => "dropdown",
		  "heading" => __("Style", "js_composer"),
		  "param_name" => "style",
		  "admin_label" => false,
		  "value" => array(
			 "Basic (Default)" => "default",
			 "Multiple Visible" => "multiple_visible",
		   ),
		  'save_always' => true,
		  "description" => __("Please select the style for your testimonial slider", "js_composer")
		),
	  	array(
		  "type" => "dropdown",
		  "heading" => __("Color", "js_composer"),
		  "param_name" => "color",
		  "admin_label" => false,
		  "value" => array(
			 "Inherit (Default)" => "default",
			 "Accent Color + Light Text" => "accent-color-light",
			 "Extra Color 1 + Light Text" => "extra-color-1-light",
			 "Extra Color 2 + Light Text" => "extra-color-2-light",
			 "Extra Color 3 + Light Text" => "extra-color-3-light",
			 "Accent Color + Dark Text" => "accent-color-dark",
			 "Extra Color 1 + Dark Text" => "extra-color-1-dark",
			 "Extra Color 2 + Dark Text" => "extra-color-2-dark",
			 "Extra Color 3 + Dark Text" => "extra-color-3-dark"
		   ),
		  'save_always' => true,
		  "dependency" => Array('element' => "style", 'value' => array('multiple_visible')),
		  "description" => __("Please select the color you would like for your testimonial slider. <br/> The Inherit value will react based on the row Text Color when set to light or dark.", "js_composer")
		),
	    array(
	      "type" => "textfield",
	      "heading" => __("Auto rotate?", "js_composer"),
	      "param_name" => "autorotate",
	      "value" => '',
	      "description" => __("If you would like this to autorotate, enter the rotation speed in miliseconds here. i.e 5000", "js_composer")
	    ),
	    array(
	      "type" => "checkbox",
		  "class" => "",
		  "heading" => "Disable height animation?",
		  "value" => array("Yes, please" => "true" ),
		  "param_name" => "disable_height_animation",
		   "dependency" => Array('element' => "style", 'value' => array('default')),
		  "description" => "Your testimonial slider will animate the height of itself to match the height of the testimonial being shown - this will remove that and simply set the height equal to the tallest testimonial to allow your content below to remain stagnant instead of moving up/down."
	    )
	  ),
	  "custom_markup" => '
	  <div class="wpb_tabs_holder wpb_holder vc_container_for_children">
	  <ul class="tabs_controls">
	  </ul>
	  %content%
	  </div>'
	  ,
	  'default_content' => '
	  [testimonial title="'.__('Testimonial','js_composer').'" id="'.$tab_id_1.'"] Click the edit button to add your testimonial. [/testimonial]
	  [testimonial title="'.__('Testimonial','js_composer').'" id="'.$tab_id_2.'"] Click the edit button to add your testimonial. [/testimonial]
	  ',
	  "js_view" => ($vc_is_wp_version_3_6_more ? 'VcTabsView' : 'VcTabsView35')
	));


	class WPBakeryShortCode_Testimonial extends WPBakeryShortCode {
		
		public function customAdminBlockParams() {
	        return ' id="tab-'.$this->atts['id'] .'"';
	    }
		
	}



	vc_map( array(
	  "name" => __("Testimonial", "js_composer"),
	  "base" => "testimonial",
	  "allowed_container_element" => 'vc_row',
	  "is_container" => true,
	  "content_element" => false,
	  "params" => array(
	  	array(
			"type" => "attach_image",
			"class" => "",
			"heading" => "Image",
			"value" => "",
			"param_name" => "image",
			"description" => "Add an optional image for the person/company who supplied the testimonial"
		),
	    array(
	      "type" => "textfield",
	      "heading" => __("Name", "js_composer"),
	      "param_name" => "name",
	      "admin_label" => true,
	      "description" => __("Name or source of the testimonial", "js_composer")
	    ),
	    array(
	      "type" => "textfield",
	      "heading" => __("Subtitle", "js_composer"),
	      "param_name" => "subtitle",
	      "admin_label" => false,
	      "description" => __("The optional subtitle that will follow the testimonial name", "js_composer")
	    ),
	    array(
	      "type" => "textarea",
	      "heading" => __("Quote", "js_composer"),
	      "param_name" => "quote",
	      "description" => __("The testimonial quote", "js_composer")
	    ),
	    array(
	      "type" => "id",
	      "heading" => __("Testimonial ID", "js_composer"),
	      "param_name" => "id"
	    )
	  ),
	  'js_view' => ($vc_is_wp_version_3_6_more ? 'VcTabView' : 'VcTabView35')
	));









	/* clients slider */
	class WPBakeryShortCode_Clients extends WPBakeryShortCode_Tabbed_Section { }

	$tab_id_1 = time().'-1-'.rand(0, 100);
	$tab_id_2 = time().'-2-'.rand(0, 100);
	vc_map( array(
	  "name"  => __("Clients Display", "js_composer"),
	  "base" => "clients",
	  "show_settings_on_create" => false,
	  "is_container" => true,
	  "icon" => "icon-wpb-clients",
	  "category" => __('Nectar Elements', 'js_composer'),
	  "description" => __('Show off your clients!', 'js_composer'),
	  "params" => array(
	    array(
	      "type" => "dropdown",
	      "heading" => __("Columns", "js_composer"),
	      "param_name" => "columns",
	      "value" => array(
				"Two" => "2",
				"Three" => "3",	
				"Four" => "4",
				"Five" => "5",
				"Six" => "6"
			),
	      'save_always' => true,
	      "description" => __("Please select how many columns you would like..", "js_composer")
	    ),
	    array(
	      "type" => "checkbox",
		  "class" => "",
		  "heading" => "Fade In One By One?",
		  "value" => array("Yes, please" => "true" ),
		  "param_name" => "fade_in_animation",
		  "description" => ""
	    ),
	    array(
	      "type" => "checkbox",
		  "class" => "",
		  "heading" => "Turn Into Carousel",
		  "value" => array("Yes, please" => "true" ),
		  "param_name" => "carousel",
		  "description" => ""
	    ),
	    array(
	      "type" => "checkbox",
		  "class" => "",
		  "heading" => "Disable Autorotate?",
		  "value" => array("Yes, please" => "true" ),
		  "param_name" => "disable_autorotate",
		  "dependency" => Array('element' => "carousel", 'not_empty' => true),
		  "description" => ""
	    )
	  ),
	  "custom_markup" => '
	  <div class="wpb_tabs_holder wpb_holder vc_container_for_children">
	  <ul class="tabs_controls">
	  </ul>
	  %content%
	  </div>'
	  ,
	  'default_content' => '
	  [client title="'.__('Client','js_composer').'" id="'.$tab_id_1.'"] Click the edit button to add your testimonial. [/client]
	  [client title="'.__('Client','js_composer').'" id="'.$tab_id_2.'"] Click the edit button to add your testimonial. [/client]
	  ',
	  "js_view" => ($vc_is_wp_version_3_6_more ? 'VcTabsView' : 'VcTabsView35')
	));


	class WPBakeryShortCode_Client extends WPBakeryShortCode {
		
		public function customAdminBlockParams() {
	        return ' id="tab-'.$this->atts['id'] .'"';
	    }
		
	}



	vc_map( array(
	  "name" => __("Client", "js_composer"),
	  "base" => "client",
	  "allowed_container_element" => 'vc_row',
	  "is_container" => true,
	  "content_element" => false,
	  "params" => array(
	    array(
	      "type" => "fws_image",
	      "heading" => __("Image", "js_composer"),
	      "param_name" => "image",
	      "value" => "",
	      "description" => __("Select image from media library.", "js_composer")
	    ),
	    array(
	      "type" => "textfield",
	      "heading" => __("URL", "js_composer"),
	      "param_name" => "url",
	      "description" => __("Add an optional link to your client", "js_composer")
	    ),
	    array(
	      "admin_label" => true,
	      "type" => "textfield",
	      "heading" => __("Client Name", "js_composer"),
	      "param_name" => "name",
	      "description" => __("Fill this out to keep track of which client is which in your page builder interface.", "js_composer")
	    ),
	    array(
	      "type" => "id",
	      "heading" => __("Client Item ID", "js_composer"),
	      "param_name" => "id"
	    )
	  ),
	  'js_view' => ($vc_is_wp_version_3_6_more ? 'VcTabView' : 'VcTabView35')
	));





	/* page sub menu */
	class WPBakeryShortCode_Page_Submenu extends WPBakeryShortCode_Tabbed_Section { }

	$tab_id_1 = time().'-1-'.rand(0, 100);
	$tab_id_2 = time().'-2-'.rand(0, 100);
	vc_map( array(
	  "name"  => __("Page Submenu", "js_composer"),
	  "base" => "page_submenu",
	  "show_settings_on_create" => true,
	  "is_container" => true,
	  "icon" => "icon-wpb-page-submenu",
	  "category" => __('Nectar Elements', 'js_composer'),
	  "description" => __('Great for animated anchors', 'js_composer'),
	  "params" => array( 
	  	/*array(
	      "type" => "dropdown",
	      "heading" => __("Link Alignment", "js_composer"),
	      "param_name" => "alignment",
	      "value" => array(
				"Left" => "left",
				"Center" => "center",	
				"Right" => "right"
			),
	      'save_always' => true,
	      "description" => __("Please select your desired link alignment", "js_composer")
	    ),*/
	    array(
	      "type" => "checkbox",
		  "class" => "",
		  "heading" => "Sticky?",
		  "value" => array("Yes, please" => "true" ),
		  "param_name" => "sticky",
		  "description" => "This will cause your submenu to stick to the top when scrolled by"
	    ),
		array(
			"type" => "colorpicker",
			"class" => "",
			"heading" => "Menu BG Color",
			"param_name" => "bg_color",
			"value" => "#f7f7f7",
			"description" => ""
		),
		array(
			"type" => "colorpicker",
			"class" => "",
			"heading" => "Link Color",
			"param_name" => "link_color",
			"value" => "#000000",
			"description" => ""
		),
	  ),
	  "custom_markup" => '
	  <div class="wpb_tabs_holder wpb_holder vc_container_for_children">
	  <ul class="tabs_controls">
	  </ul>
	  %content%
	  </div>'
	  ,
	  'default_content' => '
	  [page_link link_url="#" title="'.__('Link','js_composer').'" id="'.$tab_id_1.'"] [/page_link]
	  [page_link link_url="#" title="'.__('Link','js_composer').'" id="'.$tab_id_2.'"]  [/page_link]
	  ',
	  "js_view" => ($vc_is_wp_version_3_6_more ? 'VcTabsView' : 'VcTabsView35')
	));


	class WPBakeryShortCode_Page_Link extends WPBakeryShortCode {
		
		public function customAdminBlockParams() {
	        return ' id="tab-'.$this->atts['id'] .'"';
	    }
		
	}



	vc_map( array(
	  "name" => __("Menu Link", "js_composer"),
	  "base" => "page_link",
	  "allowed_container_element" => 'vc_row',
	  "is_container" => true,
	  "content_element" => false,
	  "params" => array(
	    array(
	      "admin_label" => false,
	      "type" => "textfield",
	      "heading" => __("Link Text", "js_composer"),
	      "param_name" => "title",
	      "description" => __("Enter the text that will be displayed for your link", "js_composer")
	    ),
	    array(
	      "admin_label" => true,
	      "type" => "textfield",
	      "heading" => __("Link URL", "js_composer"),
	      "param_name" => "link_url",
	      "description" => __("Enter the URL that will be used for your link", "js_composer")
	    ),
	     array(
	      "type" => "checkbox",
		  "class" => "",
		  "heading" => "Open Link In New Tab",
		  "value" => array("Yes, please" => "true" ),
		  "param_name" => "link_new_tab",
		  "description" => ""
	    ),
	    array(
	      "type" => "id",
	      "heading" => __("Page Link ID", "js_composer"),
	      "param_name" => "id"
	    )
	  ),
	  'js_view' => ($vc_is_wp_version_3_6_more ? 'VcTabView' : 'VcTabView35')
	));






	/* pricing table */
	class WPBakeryShortCode_Pricing_Table extends WPBakeryShortCode_Tabbed_Section { }

	$tab_id_1 = time().'-1-'.rand(0, 100);
	$tab_id_2 = time().'-2-'.rand(0, 100);
	vc_map( array(
	  "name"  => __("Pricing Table", "js_composer"),
	  "base" => "pricing_table",
	  "show_settings_on_create" => false,
	  "is_container" => true,
	  "icon" => "icon-wpb-pricing-table",
	  "category" => __('Nectar Elements', 'js_composer'),
	  "description" => __('Stylish pricing tables', 'js_composer'),
	  "params" => array(
	    array(
	      "type" => "textfield",
	      "heading" => __("Extra class name", "js_composer"),
	      "param_name" => "el_class",
	      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer")
		),
		array(
			"type" => "dropdown",
			"holder" => "div",
			"admin_label" => false,
			"class" => "",
			"heading" => "Style",
			"param_name" => "style",
			"value" => array(
				"Default" => "default",
				"Flat Alternative" => "flat-alternative"
			),
			'save_always' => true,
			"description" => ""
		)
	  ),
	  "custom_markup" => '
	  <div class="wpb_tabs_holder wpb_holder vc_container_for_children">
	  <ul class="tabs_controls">
	  </ul>
	  %content%
	  </div>'
	  ,
	  'default_content' => '
	  [pricing_column title="'.__('Column','js_composer').'" id="'.$tab_id_1.'"]  [/pricing_column]
	  [pricing_column title="'.__('Column','js_composer').'" id="'.$tab_id_2.'"]  [/pricing_column]
	  ',
	  "js_view" => ($vc_is_wp_version_3_6_more ? 'VcTabsView' : 'VcTabsView35')
	));


	class WPBakeryShortCode_Pricing_Column extends WPBakeryShortCode {
		
		public function customAdminBlockParams() {
	        return ' id="tab-'.$this->atts['id'] .'"';
	    }
		
	}



	vc_map( array(
	  "name" => __("Pricing Column", "js_composer"),
	  "base" => "pricing_column",
	  "allowed_container_element" => 'vc_row',
	  "is_container" => true,
	  "content_element" => false,
	  "params" => array(
	    array(
	      "type" => "textfield",
	      "heading" => __("Title", "js_composer"),
	      "param_name" => "title",
	      "admin_label" => true,
	      "description" => __("Please enter a title for your pricing column", "js_composer")
	    ),
	    array(
	      "type" => "textfield",
	      "heading" => __("Price", "js_composer"),
	      "param_name" => "price",
	      "description" => __("Enter the price for your column", "js_composer")
	    ),
	    array(
	      "type" => "textfield",
	      "heading" => __("Currency Symbol", "js_composer"),
	      "param_name" => "currency_symbol",
	      "description" => __("Enter the currency symbol that will display for your price", "js_composer")
	    ),
	    array(
	      "type" => "textfield",
	      "heading" => __("Interval", "js_composer"),
	      "param_name" => "interval",
	      "description" => __("Enter the interval for your pricing e.g. \"Per Month\" or \"Per Year\" ", "js_composer")
	    ),
	    array(
	      "type" => "checkbox",
		  "class" => "",
		  "heading" => "Highlight Column?",
		  "value" => array("Yes, please" => "true" ),
		  "param_name" => "highlight",
		  "description" => ""
	    ),
	    array(
	      "type" => "textfield",
	      "heading" => __("Highlight Reason", "js_composer"),
	      "param_name" => "highlight_reason",
	      "description" => __("Enter the reason for the column being highlighted e.g. \"Most Popular\"" , "js_composer"),
	      "dependency" => Array('element' => "highlight", 'not_empty' => true)
	    ),
	    array(
			"type" => "dropdown",
			"holder" => "div",
			"class" => "",
			"heading" => "Color",
			"param_name" => "color",
			"value" => array(
				"Accent-Color" => "Accent-Color",
				"Extra-Color-1" => "Extra-Color-1",
				"Extra-Color-2" => "Extra-Color-2",	
				"Extra-Color-3" => "Extra-Color-3"
			),
			'save_always' => true,
			"description" => ""
		),
		array(
	      "type" => "textarea_html",
	      "holder" => "div",
	      "heading" => __("Text Content", "js_composer"),
	      "param_name" => "content",
	      "value" => __("", "js_composer")
	    )
	  ),
	  'js_view' => ($vc_is_wp_version_3_6_more ? 'VcTabView' : 'VcTabView35')
	));





	/* carousel */
	class WPBakeryShortCode_Carousel extends WPBakeryShortCode_Tabbed_Section { }

	$tab_id_1 = time().'-1-'.rand(0, 100);
	$tab_id_2 = time().'-2-'.rand(0, 100);
	$tab_id_3 = time().'-3-'.rand(0, 100);

	vc_map( array(
	  "name"  => __("Carousel", "js_composer"),
	  "base" => "carousel",
	  "show_settings_on_create" => true,
	  "is_container" => true,
	  "icon" => "icon-wpb-carousel",
	  "category" => __('Nectar Elements', 'js_composer'),
	  "description" => __('A simple carousel for any content', 'js_composer'),
	  "params" => array(
	   array(
	      "type" => "textfield",
	      "heading" => __("Carousel Title", "js_composer"),
	      "param_name" => "carousel_title",
	      "description" => __("Enter the title you would like at the top of your carousel (optional)" , "js_composer")
	    ),
	    array(
	      "type" => "textfield",
	      "heading" => __("Transition Scroll Speed", "js_composer"),
	      "param_name" => "scroll_speed",
	      "description" => __("Enter in milliseconds (default is 700)" , "js_composer")
	    ),
	    array(
			"type" => "checkbox",
			"class" => "",
			"heading" => __("Autorotate?", "js_composer"),
	     	"param_name" => "autorotate",
			"value" => Array(__("Yes", "js_composer") => 'true'),
			"description" => ""
		),
	    array(
			"type" => "dropdown",
			"holder" => "div",
			"class" => "",
			"admin_label" => false,
			"heading" => "Easing",
			"param_name" => "easing",
			'save_always' => true,
			"value" => array(
				'linear'=>'linear',
				'swing'=>'swing',
				'easeInQuad'=>'easeInQuad',
				'easeOutQuad' => 'easeOutQuad',
				'easeInOutQuad'=>'easeInOutQuad',
				'easeInCubic'=>'easeInCubic',
				'easeOutCubic'=>'easeOutCubic',
				'easeInOutCubic'=>'easeInOutCubic',
				'easeInQuart'=>'easeInQuart',
				'easeOutQuart'=>'easeOutQuart',
				'easeInOutQuart'=>'easeInOutQuart',
				'easeInQuint'=>'easeInQuint',
				'easeOutQuint'=>'easeOutQuint',
				'easeInOutQuint'=>'easeInOutQuint',
				'easeInExpo'=>'easeInExpo',
				'easeOutExpo'=>'easeOutExpo',
				'easeInOutExpo'=>'easeInOutExpo',
				'easeInSine'=>'easeInSine',
				'easeOutSine'=>'easeOutSine',
				'easeInOutSine'=>'easeInOutSine',
				'easeInCirc'=>'easeInCirc',
				'easeOutCirc'=>'easeOutCirc',
				'easeInOutCirc'=>'easeInOutCirc',
				'easeInElastic'=>'easeInElastic',
				'easeOutElastic'=>'easeOutElastic',
				'easeInOutElastic'=>'easeInOutElastic',
				'easeInBack'=>'easeInBack',
				'easeOutBack'=>'easeOutBack',
				'easeInOutBack'=>'easeInOutBack',
				'easeInBounce'=>'easeInBounce',
				'easeOutBounce'=>'easeOutBounce',
				'easeInOutBounce'=>'easeInOutBounce',
			),
			"description" => "Select the animation easing you would like for slide transitions <a href=\"http://jqueryui.com/resources/demos/effect/easing.html\" target=\"_blank\"> Click here </a> to see examples of these."
		)
	  ),
	  "custom_markup" => '
	  <div class="wpb_tabs_holder wpb_holder vc_container_for_children">
	  <ul class="tabs_controls">
	  </ul>
	  %content%
	  </div>'
	  ,
	  'default_content' => '
	  [item id="'.$tab_id_1.'"] Add Content Here [/item]
	  [item id="'.$tab_id_2.'"] Add Content Here [/item]
	  [item id="'.$tab_id_3.'"] Add Content Here [/item]
	  ',
	  "js_view" => ($vc_is_wp_version_3_6_more ? 'VcTabsView' : 'VcTabsView35')
	));



	vc_map( array(
	  "name" => __("Carousel Item", "js_composer"),
	  "base" => "item",
	  "allowed_container_element" => 'vc_row',
	  "is_container" => true,
	  "content_element" => false,
	  "params" => array(
	    array(
	      "type" => "id",
	      "heading" => __("Carousel Item ID", "js_composer"),
	      "param_name" => "id"
	    )
	  ),
	  'js_view' => ($vc_is_wp_version_3_6_more ? 'VcTabView' : 'VcTabView35')
	));




	// Social Buttons
	vc_map( array(
	  "name" => __("Social Buttons", "js_composer"),
	  "base" => "social_buttons",
	  "icon" => "icon-wpb-social-buttons",
	  "category" => __('Nectar Elements', 'js_composer'),
	  "description" => __('Add social buttons to any page', 'js_composer'),
	  "params" => array(
	     array(
	      "type" => 'checkbox',
	      "heading" => __("Display full width?", "js_composer"),
	      "param_name" => "full_width_icons",
	      "description" => __("This will make your social icons expand to fit edge to edge in whatever space they're placed." , "js_composer"),
	      "value" => Array(__("Yes", "js_composer") => 'true')
	    ),
	   /* array(
	      "type" => 'checkbox',
	      "heading" => __("Hide share counts?", "js_composer"),
	      "param_name" => "hide_share_count",
	      "description" => __("This will remove your share counts from displaying to the user" , "js_composer"),
	      "value" => Array(__("Yes", "js_composer") => 'true')
	    ), */
	 	 array(
	      "type" => 'checkbox',
	      "heading" => __("Nectar Love", "js_composer"),
	      "param_name" => "nectar_love",
	      "value" => Array(__("Yes", "js_composer") => 'true')
	    ),
	    array(
	      "type" => 'checkbox',
	      "heading" => __("Facebook", "js_composer"),
	      "param_name" => "facebook",
	      "value" => Array(__("Yes", "js_composer") => 'true')
	    ),
	    array(
	      "type" => 'checkbox',
	      "heading" => __("Twitter", "js_composer"),
	      "param_name" => "twitter",
	      "value" => Array(__("Yes", "js_composer") => 'true')
	    ),
	    array(
	      "type" => 'checkbox',
	      "heading" => __("Google+", "js_composer"),
	      "param_name" => "google_plus",
	      "value" => Array(__("Yes", "js_composer") => 'true')
	    ),
	    array(
	      "type" => 'checkbox',
	      "heading" => __("LinkedIn", "js_composer"),
	      "param_name" => "linkedin",
	      "value" => Array(__("Yes", "js_composer") => 'true')
	    ),
	    array(
	      "type" => 'checkbox',
	      "heading" => __("Pinterest", "js_composer"),
	      "param_name" => "pinterest",
	      "description" => '',
	      "value" => Array(__("Yes", "js_composer") => 'true')
	    )
	  )
	));




	//gallery 
	vc_remove_param("vc_gallery", "type");
	vc_remove_param("vc_gallery", "title");
	vc_remove_param("vc_gallery", "interval");
	vc_remove_param("vc_gallery", "images");
	vc_remove_param("vc_gallery", "img_size");
	vc_remove_param("vc_gallery", "onclick");
	vc_remove_param("vc_gallery", "custom_links");
	vc_remove_param("vc_gallery", "custom_links_target");
	vc_remove_param("vc_gallery", "el_class");


	if(nectar_has_shortcode('vc_gallery')) { 
		vc_add_param("vc_gallery",array(
		      "type" => "dropdown",
		      "heading" => __("Gallery type", "js_composer"),
		      "param_name" => "type",
		      "value" => array(
		         __("Basic Slider Style", "js_composer") => "flexslider_style", 
		         __("Nectar Slider Style", "js_composer") => "nectarslider_style",
		         __("Touch Enabled & Spaced", "js_composer") => "flickity_style",
		         __("Image Grid Style", "js_composer") => "image_grid"
		       ),
		      'save_always' => true,
		      "description" => __("Select gallery type.", "js_composer")
		));
		vc_add_param("vc_gallery",array(
		      "type" => "dropdown",
		      "heading" => __("Auto rotate slides", "js_composer"),
		      "param_name" => "interval",
		      "value" => array(3, 5, 10, 15, __("Disable", "js_composer") => 0),
		      "description" => __("Auto rotate slides each X seconds.", "js_composer"),
		      'save_always' => true,
		      "dependency" => Array('element' => "type", 'value' => array('flexslider_fade', 'flexslider_slide', 'nivo'))
		));
		vc_add_param("vc_gallery",array(
		      "type" => "attach_images",
		      "heading" => __("Images", "js_composer"),
		      "param_name" => "images",
		      "value" => "",
		      "description" => __("Select images from media library.", "js_composer")
		));
		vc_add_param("vc_gallery",array(
		      "type" => "textfield",
		      "heading" => __("Image size", "js_composer"),
		      "param_name" => "img_size",
		      "description" => __("Enter image size in pixels - e.g 600x400 (Width x Height) <br/> Or use WordPress image size names such as \"full\"", "js_composer")
		));

		 vc_add_param("vc_gallery",array(
		      "type" => 'checkbox',
		      "heading" => __("Flexible Slider Height", "js_composer"),
		      "param_name" => "flexible_slider_height",
		      "description" => __("Would you like the height of your slider to constantly scale in porportion to the screen size?", "js_composer"),
		      "value" => Array(__("Yes, please", "js_composer") => 'true'),
		      "dependency" => Array('element' => "type", 'value' => array('nectarslider_style'))
		  ));
		  vc_add_param("vc_gallery",array(
		      "type" => 'checkbox',
		      "heading" => __("Hide Arrow Navigation?", "js_composer"),
		      "param_name" => "hide_arrow_navigation",
		      "description" => __("Would you like this slider to hide the arrows on the right and left sides?", "js_composer"),
		      "value" => Array(__("Yes, please", "js_composer") => 'true'),
		      "dependency" => Array('element' => "type", 'value' => array('nectarslider_style'))
		  ));
		  vc_add_param("vc_gallery",array(
		      "type" => 'checkbox',
		      "heading" => __("Display Bullet Navigation?", "js_composer"),
		      "param_name" => "bullet_navigation",
		      "description" => __("Would you like this slider to display bullets on the bottom?", "js_composer"),
		      "value" => Array(__("Yes, please", "js_composer") => 'true'),
		      "dependency" => Array('element' => "type", 'value' => array('nectarslider_style'))
		  ));
		  vc_add_param("vc_gallery",array(
		      "type" => "dropdown",
		      "heading" => __("Bullet Navigation Style", "js_composer"),
		      "param_name" => "bullet_navigation_style",
		      "value" => array(
					'See Through & Solid On Active' => 'see_through',
					'Solid & Scale On Active' => 'scale'
		      ),
		      'save_always' => true,
		      "description" => 'Please select your overall bullet navigation style here.',
		      "dependency" => Array('element' => "type", 'value' => array('nectarslider_style'))
		  ));


		vc_add_param("vc_gallery",array(
		      "type" => 'checkbox',
		      "heading" => __("Display Title/Caption?", "js_composer"),
		      "param_name" => "display_title_caption",
		      "value" => Array(__("Yes", "js_composer") => 'true'),
		      "dependency" => Array('element' => "type", 'value' => array('image_grid'))
		));

		vc_add_param("vc_gallery",array(
			  "type" => "dropdown",
			  "heading" => __("Layout", "js_composer"),
			  "param_name" => "layout",
			  "admin_label" => true,
			  "value" => array(
				    "3 Columns" => "3",
				    "4 Columns" => "4",
				    "Fullwidth" => "fullwidth"
				),
			  'save_always' => true,
			  "description" => __("Please select the layout you would like for your gallery ", "js_composer"),
			  "dependency" => Array('element' => "type", 'value' => array('image_grid'))
		));
		vc_add_param("vc_gallery",array(
		      "type" => 'checkbox',
		      "heading" => __("Constrain Max Columns to 4?", "js_composer"),
		      "param_name" => "constrain_max_cols",
		      "description" => __("This will change the max columns to 4 (default is 5 for fullwidth). Activating this will make it easier to create a grid with no empty spaces at the end of the list on all screen sizes. (Won't be used if masonry layout is active)", "js_composer"),
		      "value" => Array(__("Yes, please", "js_composer") => 'true'),
		      "dependency" => Array('element' => "layout", 'value' => 'fullwidth')
		));
		vc_add_param("vc_gallery",array(
			  "type" => "dropdown",
			  "heading" => __("Gallery Style", "js_composer"),
			  "param_name" => "gallery_style",
			  "admin_label" => true,
			  "value" => array(
				    "Meta below thumb w/ links on hover" => "1",
				    "Meta on hover + entire thumb link" => "2",
				    "Title overlaid w/ zoom effect on hover" => "3",
				    'Title overlaid w/ zoom effect on hover alt' => '5',
				    "Meta from bottom on hover + entire thumb link" => "4"
				),
			  'save_always' => true,
			  "description" => __("Please select the style you would like your gallery to display in ", "js_composer"),
			  "dependency" => Array('element' => "type", 'value' => array('image_grid'))
		));

		vc_add_param("vc_gallery",array(
		      "type" => "dropdown",
		      "heading" => __("On click", "js_composer"),
		      "param_name" => "onclick",
		      "value" => array( __("Do nothing", "js_composer") => "link_no", __("Open prettyPhoto", "js_composer") => "link_image",  __("Open custom link", "js_composer") => "custom_link"),
		      "description" => __("What to do when slide is clicked?", "js_composer"),
		      'save_always' => true,
		      "dependency" => Array('element' => "type", 'value' => array('nectarslider_style', 'flexslider_style', 'flickity_style'))
		));
		vc_add_param("vc_gallery",array(
		      "type" => "exploded_textarea",
		      "heading" => __("Custom links", "js_composer"),
		      "param_name" => "custom_links",
		      "description" => __('Enter links for each slide here. Divide links with linebreaks (Enter).', 'js_composer'),
		      "dependency" => Array('element' => "onclick", 'value' => array('custom_link'))
		));

		vc_add_param("vc_gallery",array(
		      "type" => "dropdown",
		      "heading" => __("Custom link target", "js_composer"),
		      "param_name" => "custom_links_target",
		      "description" => __('Select where to open  custom links.', 'js_composer'),
		      "dependency" => Array('element' => "onclick", 'value' => array('custom_link')),
		      'save_always' => true,
		      'value' => array(__("Same window", "js_composer") => "_self", __("New window", "js_composer") => "_blank")
		));
		vc_add_param("vc_gallery",array(
		      "type" => "textfield",
		      "heading" => __("Extra class name", "js_composer"),
		      "param_name" => "el_class",
		      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer")
		));
	}













	   $fa_icons = array(
		      'icon-glass' => 'icon-glass',
			  'icon-music' => 'icon-music',
			  'icon-search' => 'icon-search',
			  'icon-envelope-alt' => 'icon-envelope-alt',
			  'icon-heart' => 'icon-heart',
			  'icon-star' => 'icon-star',
			  'icon-star-empty' => 'icon-star-empty',
			  'icon-user' => 'icon-user',
			  'icon-film' => 'icon-film',
			  'icon-th-large' => 'icon-th-large',
			  'icon-th' => 'icon-th',
			  'icon-th-list' => 'icon-th-list',
			  'icon-ok' => 'icon-ok',
			  'icon-remove' => 'icon-remove',
			  'icon-zoom-in' => 'icon-zoom-in',
			  'icon-zoom-out' => 'icon-zoom-out',
			  'icon-off' => 'icon-off',
			  'icon-signal' => 'icon-signal',
			  'icon-cog' => 'icon-cog',
			  'icon-trash' => 'icon-trash',
			  'icon-home' => 'icon-home',
			  'icon-file-alt' => 'icon-file-alt',
			  'icon-time' => 'icon-time',
			  'icon-road' => 'icon-road',
			  'icon-download-alt' => 'icon-download-alt',
			  'icon-download' => 'icon-download',
			  'icon-upload' => 'icon-upload',
			  'icon-inbox' => 'icon-inbox',
			  'icon-play-circle' => 'icon-play-circle',
			  'icon-repeat' => 'icon-repeat',
			  'icon-refresh' => 'icon-refresh',
			  'icon-list-alt' => 'icon-list-alt',
			  'icon-lock' => 'icon-lock',
			  'icon-flag' => 'icon-flag',
			  'icon-headphones' => 'icon-headphones',
			  'icon-volume-off' => 'icon-volume-off',
			  'icon-volume-down' => 'icon-volume-down',
			  'icon-volume-up' => 'icon-volume-up',
			  'icon-qrcode' => 'icon-qrcode',
			  'icon-barcode' => 'icon-barcode',
			  'icon-tag' => 'icon-tag',
			  'icon-tags' => 'icon-tags',
			  'icon-book' => 'icon-book',
			  'icon-bookmark' => 'icon-bookmark',
			  'icon-print' => 'icon-print',
			  'icon-camera' => 'icon-camera',
			  'icon-font' => 'icon-font',
			  'icon-bold' => 'icon-bold',
			  'icon-italic' => 'icon-italic',
			  'icon-text-height' => 'icon-text-height',
			  'icon-text-width' => 'icon-text-width',
			  'icon-align-left' => 'icon-align-left',
			  'icon-align-center' => 'icon-align-center',
			  'icon-align-right' => 'icon-align-right',
			  'icon-align-justify' => 'icon-align-justify',
			  'icon-list' => 'icon-list',
			  'icon-indent-left' => 'icon-indent-left',
			  'icon-indent-right' => 'icon-indent-right',
			  'icon-facetime-video' => 'icon-facetime-video',
			  'icon-picture' => 'icon-picture',
			  'icon-pencil' => 'icon-pencil',
			  'icon-map-marker' => 'icon-map-marker',
			  'icon-adjust' => 'icon-adjust',
			  'icon-tint' => 'icon-tint',
			  'icon-edit' => 'icon-edit',
			  'icon-share' => 'icon-share',
			  'icon-check' => 'icon-check',
			  'icon-move' => 'icon-move',
			  'icon-step-backward' => 'icon-step-backward',
			  'icon-fast-backward' => 'icon-fast-backward',
			  'icon-backward' => 'icon-backward',
			  'icon-play' => 'icon-play',
			  'icon-pause' => 'icon-pause',
			  'icon-stop' => 'icon-stop',
			  'icon-forward' => 'icon-forward',
			  'icon-fast-forward' => 'icon-fast-forward',
			  'icon-step-forward' => 'icon-step-forward',
			  'icon-eject' => 'icon-eject',
			  'icon-chevron-left' => 'icon-chevron-left',
			  'icon-chevron-right' => 'icon-chevron-right',
			  'icon-plus-sign' => 'icon-plus-sign',
			  'icon-minus-sign' => 'icon-minus-sign',
			  'icon-remove-sign' => 'icon-remove-sign',
			  'icon-ok-sign' => 'icon-ok-sign',
			  'icon-question-sign' => 'icon-question-sign',
			  'icon-info-sign' => 'icon-info-sign',
			  'icon-screenshot' => 'icon-screenshot',
			  'icon-remove-circle' => 'icon-remove-circle',
			  'icon-ok-circle' => 'icon-ok-circle',
			  'icon-ban-circle' => 'icon-ban-circle',
			  'icon-arrow-left' => 'icon-arrow-left',
			  'icon-arrow-right' => 'icon-arrow-right',
			  'icon-arrow-up' => 'icon-arrow-up',
			  'icon-arrow-down' => 'icon-arrow-down',
			  'icon-share-alt' => 'icon-share-alt',
			  'icon-resize-full' => 'icon-resize-full',
			  'icon-resize-small' => 'icon-resize-small',
			  'icon-plus' => 'icon-plus',
			  'icon-minus' => 'icon-minus',
			  'icon-asterisk' => 'icon-asterisk',
			  'icon-exclamation-sign' => 'icon-exclamation-sign',
			  'icon-gift' => 'icon-gift',
			  'icon-leaf' => 'icon-leaf',
			  'icon-fire' => 'icon-fire',
			  'icon-eye-open' => 'icon-eye-open',
			  'icon-eye-close' => 'icon-eye-close',
			  'icon-warning-sign' => 'icon-warning-sign',
			  'icon-plane' => 'icon-plane',
			  'icon-calendar' => 'icon-calendar',
			  'icon-random' => 'icon-random',
			  'icon-comment' => 'icon-comment',
			  'icon-magnet' => 'icon-magnet',
			  'icon-chevron-up' => 'icon-chevron-up',
			  'icon-chevron-down' => 'icon-chevron-down',
			  'icon-retweet' => 'icon-retweet',
			  'icon-shopping-cart' => 'icon-shopping-cart',
			  'icon-folder-close' => 'icon-folder-close',
			  'icon-folder-open' => 'icon-folder-open',
			  'icon-resize-vertical' => 'icon-resize-vertical',
			  'icon-resize-horizontal' => 'icon-resize-horizontal',
			  'icon-bar-chart' => 'icon-bar-chart',
			  'icon-twitter-sign' => 'icon-twitter-sign',
			  'icon-facebook-sign' => 'icon-facebook-sign',
			  'icon-camera-retro' => 'icon-camera-retro',
			  'icon-key' => 'icon-key',
			  'icon-cogs' => 'icon-cogs',
			  'icon-comments' => 'icon-comments',
			  'icon-thumbs-up-alt' => 'icon-thumbs-up-alt',
			  'icon-thumbs-down-alt' => 'icon-thumbs-down-alt',
			  'icon-star-half' => 'icon-star-half',
			  'icon-heart-empty' => 'icon-heart-empty',
			  'icon-signout' => 'icon-signout',
			  'icon-linkedin-sign' => 'icon-linkedin-sign',
			  'icon-pushpin' => 'icon-pushpin',
			  'icon-external-link' => 'icon-external-link',
			  'icon-signin' => 'icon-signin',
			  'icon-trophy' => 'icon-trophy',
			  'icon-github-sign' => 'icon-github-sign',
			  'icon-upload-alt' => 'icon-upload-alt',
			  'icon-lemon' => 'icon-lemon',
			  'icon-phone' => 'icon-phone',
			  'icon-check-empty' => 'icon-check-empty',
			  'icon-bookmark-empty' => 'icon-bookmark-empty',
			  'icon-phone-sign' => 'icon-phone-sign',
			  'icon-twitter' => 'icon-twitter',
			  'icon-facebook' => 'icon-facebook',
			  'icon-github' => 'icon-github',
			  'icon-unlock' => 'icon-unlock',
			  'icon-credit-card' => 'icon-credit-card',
			  'icon-rss' => 'icon-rss',
			  'icon-hdd' => 'icon-hdd',
			  'icon-bullhorn' => 'icon-bullhorn',
			  'icon-bell' => 'icon-bell',
			  'icon-certificate' => 'icon-certificate',
			  'icon-hand-right' => 'icon-hand-right',
			  'icon-hand-left' => 'icon-hand-left',
			  'icon-hand-up' => 'icon-hand-up',
			  'icon-hand-down' => 'icon-hand-down',
			  'icon-circle-arrow-left' => 'icon-circle-arrow-left',
			  'icon-circle-arrow-right' => 'icon-circle-arrow-right',
			  'icon-circle-arrow-up' => 'icon-circle-arrow-up',
			  'icon-circle-arrow-down' => 'icon-circle-arrow-down',
			  'icon-globe' => 'icon-globe',
			  'icon-wrench' => 'icon-wrench',
			  'icon-tasks' => 'icon-tasks',
			  'icon-filter' => 'icon-filter',
			  'icon-briefcase' => 'icon-briefcase',
			  'icon-fullscreen' => 'icon-fullscreen',
			  'icon-group' => 'icon-group',
			  'icon-link' => 'icon-link',
			  'icon-cloud' => 'icon-cloud',
			  'icon-beaker' => 'icon-beaker',
			  'icon-cut' => 'icon-cut',
			  'icon-copy' => 'icon-copy',
			  'icon-paper-clip' => 'icon-paper-clip',
			  'icon-save' => 'icon-save',
			  'icon-sign-blank' => 'icon-sign-blank',
			  'icon-reorder' => 'icon-reorder',
			  'icon-list-ul' => 'icon-list-ul',
			  'icon-list-ol' => 'icon-list-ol',
			  'icon-strikethrough' => 'icon-strikethrough',
			  'icon-underline' => 'icon-underline',
			  'icon-table' => 'icon-table',
			  'icon-magic' => 'icon-magic',
			  'icon-truck' => 'icon-truck',
			  'icon-pinterest' => 'icon-pinterest',
			  'icon-pinterest-sign' => 'icon-pinterest-sign',
			  'icon-google-plus-sign' => 'icon-google-plus-sign',
			  'icon-google-plus' => 'icon-google-plus',
			  'icon-money' => 'icon-money',
			  'icon-caret-down' => 'icon-caret-down',
			  'icon-caret-up' => 'icon-caret-up',
			  'icon-caret-left' => 'icon-caret-left',
			  'icon-caret-right' => 'icon-caret-right',
			  'icon-columns' => 'icon-columns',
			  'icon-sort' => 'icon-sort',
			  'icon-sort-down' => 'icon-sort-down',
			  'icon-sort-up' => 'icon-sort-up',
			  'icon-envelope' => 'icon-envelope',
			  'icon-linkedin' => 'icon-linkedin',
			  'icon-undo' => 'icon-undo',
			  'icon-legal' => 'icon-legal',
			  'icon-dashboard' => 'icon-dashboard',
			  'icon-comment-alt' => 'icon-comment-alt',
			  'icon-comments-alt' => 'icon-comments-alt',
			  'icon-bolt' => 'icon-bolt',
			  'icon-sitemap' => 'icon-sitemap',
			  'icon-umbrella' => 'icon-umbrella',
			  'icon-paste' => 'icon-paste',
			  'icon-lightbulb' => 'icon-lightbulb',
			  'icon-exchange' => 'icon-exchange',
			  'icon-cloud-download' => 'icon-cloud-download',
			  'icon-cloud-upload' => 'icon-cloud-upload',
			  'icon-user-md' => 'icon-user-md',
			  'icon-stethoscope' => 'icon-stethoscope',
			  'icon-suitcase' => 'icon-suitcase',
			  'icon-bell-alt' => 'icon-bell-alt',
			  'icon-coffee' => 'icon-coffee',
			  'icon-food' => 'icon-food',
			  'icon-file-text-alt' => 'icon-file-text-alt',
			  'icon-building' => 'icon-building',
			  'icon-hospital' => 'icon-hospital',
			  'icon-ambulance' => 'icon-ambulance',
			  'icon-medkit' => 'icon-medkit',
			  'icon-fighter-jet' => 'icon-fighter-jet',
			  'icon-beer' => 'icon-beer',
			  'icon-h-sign' => 'icon-h-sign',
			  'icon-plus-sign-alt' => 'icon-plus-sign-alt',
			  'icon-double-angle-left' => 'icon-double-angle-left',
			  'icon-double-angle-right' => 'icon-double-angle-right',
			  'icon-double-angle-up' => 'icon-double-angle-up',
			  'icon-double-angle-down' => 'icon-double-angle-down',
			  'icon-angle-left' => 'icon-angle-left',
			  'icon-angle-right' => 'icon-angle-right',
			  'icon-angle-up' => 'icon-angle-up',
			  'icon-angle-down' => 'icon-angle-down',
			  'icon-desktop' => 'icon-desktop',
			  'icon-laptop' => 'icon-laptop',
			  'icon-tablet' => 'icon-tablet',
			  'icon-mobile-phone' => 'icon-mobile-phone',
			  'icon-circle-blank' => 'icon-circle-blank',
			  'icon-quote-left' => 'icon-quote-left',
			  'icon-quote-right' => 'icon-quote-right',
			  'icon-spinner' => 'icon-spinner',
			  'icon-circle' => 'icon-circle',
			  'icon-reply' => 'icon-reply',
			  'icon-github-alt' => 'icon-github-alt',
			  'icon-folder-close-alt' => 'icon-folder-close-alt',
			  'icon-folder-open-alt' => 'icon-folder-open-alt',
			  'icon-expand-alt' => 'icon-expand-alt',
			  'icon-collapse-alt' => 'icon-collapse-alt',
			  'icon-smile' => 'icon-smile',
			  'icon-frown' => 'icon-frown',
			  'icon-meh' => 'icon-meh',
			  'icon-gamepad' => 'icon-gamepad',
			  'icon-keyboard' => 'icon-keyboard',
			  'icon-flag-alt' => 'icon-flag-alt',
			  'icon-flag-checkered' => 'icon-flag-checkered',
			  'icon-terminal' => 'icon-terminal',
			  'icon-code' => 'icon-code',
			  'icon-reply-all' => 'icon-reply-all',
			  'icon-mail-reply-all' => 'icon-mail-reply-all',
			  'icon-star-half-empty' => 'icon-star-half-empty',
			  'icon-location-arrow' => 'icon-location-arrow',
			  'icon-crop' => 'icon-crop',
			  'icon-code-fork' => 'icon-code-fork',
			  'icon-unlink' => 'icon-unlink',
			  'icon-question' => 'icon-question',
			  'icon-info' => 'icon-info',
			  'icon-exclamation' => 'icon-exclamation',
			  'icon-superscript' => 'icon-superscript',
			  'icon-subscript' => 'icon-subscript',
			  'icon-eraser' => 'icon-eraser',
			  'icon-puzzle-piece' => 'icon-puzzle-piece',
			  'icon-microphone' => 'icon-microphone',
			  'icon-microphone-off' => 'icon-microphone-off',
			  'icon-shield' => 'icon-shield',
			  'icon-calendar-empty' => 'icon-calendar-empty',
			  'icon-fire-extinguisher' => 'icon-fire-extinguisher',
			  'icon-rocket' => 'icon-rocket',
			  'icon-maxcdn' => 'icon-maxcdn',
			  'icon-chevron-sign-left' => 'icon-chevron-sign-left',
			  'icon-chevron-sign-right' => 'icon-chevron-sign-right',
			  'icon-chevron-sign-up' => 'icon-chevron-sign-up',
			  'icon-chevron-sign-down' => 'icon-chevron-sign-down',
			  'icon-html5' => 'icon-html5',
			  'icon-css3' => 'icon-css3',
			  'icon-anchor' => 'icon-anchor',
			  'icon-unlock-alt' => 'icon-unlock-alt',
			  'icon-bullseye' => 'icon-bullseye',
			  'icon-ellipsis-horizontal' => 'icon-ellipsis-horizontal',
			  'icon-ellipsis-vertical' => 'icon-ellipsis-vertical',
			  'icon-rss-sign' => 'icon-rss-sign',
			  'icon-play-sign' => 'icon-play-sign',
			  'icon-ticket' => 'icon-ticket',
			  'icon-minus-sign-alt' => 'icon-minus-sign-alt',
			  'icon-check-minus' => 'icon-check-minus',
			  'icon-level-up' => 'icon-level-up',
			  'icon-level-down' => 'icon-level-down',
			  'icon-check-sign' => 'icon-check-sign',
			  'icon-edit-sign' => 'icon-edit-sign',
			  'icon-external-link-sign' => 'icon-external-link-sign',
			  'icon-share-sign' => 'icon-share-sign',
			  'icon-compass' => 'icon-compass',
			  'icon-collapse' => 'icon-collapse',
			  'icon-collapse-top' => 'icon-collapse-top',
			  'icon-expand' => 'icon-expand',
			  'icon-eur' => 'icon-eur',
			  'icon-gbp' => 'icon-gbp',
			  'icon-usd' => 'icon-usd',
			  'icon-inr' => 'icon-inr',
			  'icon-jpy' => 'icon-jpy',
			  'icon-cny' => 'icon-cny',
			  'icon-krw' => 'icon-krw',
			  'icon-btc' => 'icon-btc',
			  'icon-file' => 'icon-file',
			  'icon-file-text' => 'icon-file-text',
			  'icon-sort-by-alphabet' => 'icon-sort-by-alphabet',
			  'icon-sort-by-alphabet-alt' => 'icon-sort-by-alphabet-alt',
			  'icon-sort-by-attributes' => 'icon-sort-by-attributes',
			  'icon-sort-by-attributes-alt' => 'icon-sort-by-attributes-alt',
			  'icon-sort-by-order' => 'icon-sort-by-order',
			  'icon-sort-by-order-alt' => 'icon-sort-by-order-alt',
			  'icon-thumbs-up' => 'icon-thumbs-up',
			  'icon-thumbs-down' => 'icon-thumbs-down',
			  'icon-youtube-sign' => 'icon-youtube-sign',
			  'icon-youtube' => 'icon-youtube',
			  'icon-xing' => 'icon-xing',
			  'icon-xing-sign' => 'icon-xing-sign',
			  'icon-youtube-play' => 'icon-youtube-play',
			  'icon-dropbox' => 'icon-dropbox',
			  'icon-stackexchange' => 'icon-stackexchange',
			  'icon-instagram' => 'icon-instagram',
			  'icon-flickr' => 'icon-flickr',
			  'icon-adn' => 'icon-adn',
			  'icon-bitbucket' => 'icon-bitbucket',
			  'icon-bitbucket-sign' => 'icon-bitbucket-sign',
			  'icon-tumblr' => 'icon-tumblr',
			  'icon-tumblr-sign' => 'icon-tumblr-sign',
			  'icon-long-arrow-down' => 'icon-long-arrow-down',
			  'icon-long-arrow-up' => 'icon-long-arrow-up',
			  'icon-long-arrow-left' => 'icon-long-arrow-left',
			  'icon-long-arrow-right' => 'icon-long-arrow-right',
			  'icon-apple' => 'icon-apple',
			  'icon-windows' => 'icon-windows',
			  'icon-android' => 'icon-android',
			  'icon-linux' => 'icon-linux',
			  'icon-dribbble' => 'icon-dribbble',
			  'icon-skype' => 'icon-skype',
			  'icon-foursquare' => 'icon-foursquare',
			  'icon-trello' => 'icon-trello',
			  'icon-female' => 'icon-female',
			  'icon-male' => 'icon-male',
			  'icon-gittip' => 'icon-gittip',
			  'icon-sun' => 'icon-sun',
			  'icon-moon' => 'icon-moon',
			  'icon-archive' => 'icon-archive',
			  'icon-bug' => 'icon-bug',
			  'icon-vk' => 'icon-vk',
			  'icon-weibo' => 'icon-weibo',
			  'icon-renren' => 'icon-renren',
		);
			
	$steadysets = array(
			  'steadysets-icon-type' => 'steadysets-icon-type',
			  'steadysets-icon-box' => 'steadysets-icon-box',
			  'steadysets-icon-archive' => 'steadysets-icon-archive',
			  'steadysets-icon-envelope' => 'steadysets-icon-envelope',
			  'steadysets-icon-email' => 'steadysets-icon-email',
			  'steadysets-icon-files' => 'steadysets-icon-files',
			  'steadysets-icon-uniE606' => 'steadysets-icon-uniE606',
			  'steadysets-icon-connection-empty' => 'steadysets-icon-connection-empty',
			  'steadysets-icon-connection-25' => 'steadysets-icon-connection-25',
			  'steadysets-icon-connection-50' => 'steadysets-icon-connection-50',
			  'steadysets-icon-connection-75' => 'steadysets-icon-connection-75',
			  'steadysets-icon-connection-full' => 'steadysets-icon-connection-full',
			  'steadysets-icon-microphone' => 'steadysets-icon-microphone',
			  'steadysets-icon-microphone-off' => 'steadysets-icon-microphone-off',
			  'steadysets-icon-book' => 'steadysets-icon-book',
			  'steadysets-icon-cloud' => 'steadysets-icon-cloud',
			  'steadysets-icon-book2' => 'steadysets-icon-book2',
			  'steadysets-icon-star' => 'steadysets-icon-star',
			  'steadysets-icon-phone-portrait' => 'steadysets-icon-phone-portrait',
			  'steadysets-icon-phone-landscape' => 'steadysets-icon-phone-landscape',
			  'steadysets-icon-tablet' => 'steadysets-icon-tablet',
			  'steadysets-icon-tablet-landscape' => 'steadysets-icon-tablet-landscape',
			  'steadysets-icon-laptop' => 'steadysets-icon-laptop',
			  'steadysets-icon-uniE617' => 'steadysets-icon-uniE617',
			  'steadysets-icon-barbell' => 'steadysets-icon-barbell',
			  'steadysets-icon-stopwatch' => 'steadysets-icon-stopwatch',
			  'steadysets-icon-atom' => 'steadysets-icon-atom',
			  'steadysets-icon-syringe' => 'steadysets-icon-syringe',
			  'steadysets-icon-pencil' => 'steadysets-icon-pencil',
			  'steadysets-icon-chart' => 'steadysets-icon-chart',
			  'steadysets-icon-bars' => 'steadysets-icon-bars',
			  'steadysets-icon-cube' => 'steadysets-icon-cube',
			  'steadysets-icon-image' => 'steadysets-icon-image',
			  'steadysets-icon-crop' => 'steadysets-icon-crop',
			  'steadysets-icon-graph' => 'steadysets-icon-graph',
			  'steadysets-icon-select' => 'steadysets-icon-select',
			  'steadysets-icon-bucket' => 'steadysets-icon-bucket',
			  'steadysets-icon-mug' => 'steadysets-icon-mug',
			  'steadysets-icon-clipboard' => 'steadysets-icon-clipboard',
			  'steadysets-icon-lab' => 'steadysets-icon-lab',
			  'steadysets-icon-bones' => 'steadysets-icon-bones',
			  'steadysets-icon-pill' => 'steadysets-icon-pill',
			  'steadysets-icon-bolt' => 'steadysets-icon-bolt',
			  'steadysets-icon-health' => 'steadysets-icon-health',
			  'steadysets-icon-map-marker' => 'steadysets-icon-map-marker',
			  'steadysets-icon-stack' => 'steadysets-icon-stack',
			  'steadysets-icon-newspaper' => 'steadysets-icon-newspaper',
			  'steadysets-icon-uniE62F' => 'steadysets-icon-uniE62F',
			  'steadysets-icon-coffee' => 'steadysets-icon-coffee',
			  'steadysets-icon-bill' => 'steadysets-icon-bill',
			  'steadysets-icon-sun' => 'steadysets-icon-sun',
			  'steadysets-icon-vcard' => 'steadysets-icon-vcard',
			  'steadysets-icon-shorts' => 'steadysets-icon-shorts',
			  'steadysets-icon-drink' => 'steadysets-icon-drink',
			  'steadysets-icon-diamond' => 'steadysets-icon-diamond',
			  'steadysets-icon-bag' => 'steadysets-icon-bag',
			  'steadysets-icon-calculator' => 'steadysets-icon-calculator',
			  'steadysets-icon-credit-cards' => 'steadysets-icon-credit-cards',
			  'steadysets-icon-microwave-oven' => 'steadysets-icon-microwave-oven',
			  'steadysets-icon-camera' => 'steadysets-icon-camera',
			  'steadysets-icon-share' => 'steadysets-icon-share',
			  'steadysets-icon-bullhorn' => 'steadysets-icon-bullhorn',
			  'steadysets-icon-user' => 'steadysets-icon-user',
			  'steadysets-icon-users' => 'steadysets-icon-users',
			  'steadysets-icon-user2' => 'steadysets-icon-user2',
			  'steadysets-icon-users2' => 'steadysets-icon-users2',
			  'steadysets-icon-unlocked' => 'steadysets-icon-unlocked',
			  'steadysets-icon-unlocked2' => 'steadysets-icon-unlocked2',
			  'steadysets-icon-lock' => 'steadysets-icon-lock',
			  'steadysets-icon-forbidden' => 'steadysets-icon-forbidden',
			  'steadysets-icon-switch' => 'steadysets-icon-switch',
			  'steadysets-icon-meter' => 'steadysets-icon-meter',
			  'steadysets-icon-flag' => 'steadysets-icon-flag',
			  'steadysets-icon-home' => 'steadysets-icon-home',
			  'steadysets-icon-printer' => 'steadysets-icon-printer',
			  'steadysets-icon-clock' => 'steadysets-icon-clock',
			  'steadysets-icon-calendar' => 'steadysets-icon-calendar',
			  'steadysets-icon-comment' => 'steadysets-icon-comment',
			  'steadysets-icon-chat-3' => 'steadysets-icon-chat-3',
			  'steadysets-icon-chat-2' => 'steadysets-icon-chat-2',
			  'steadysets-icon-chat-1' => 'steadysets-icon-chat-1',
			  'steadysets-icon-chat' => 'steadysets-icon-chat',
			  'steadysets-icon-zoom-out' => 'steadysets-icon-zoom-out',
			  'steadysets-icon-zoom-in' => 'steadysets-icon-zoom-in',
			  'steadysets-icon-search' => 'steadysets-icon-search',
			  'steadysets-icon-trashcan' => 'steadysets-icon-trashcan',
			  'steadysets-icon-tag' => 'steadysets-icon-tag',
			  'steadysets-icon-download' => 'steadysets-icon-download',
			  'steadysets-icon-paperclip' => 'steadysets-icon-paperclip',
			  'steadysets-icon-checkbox' => 'steadysets-icon-checkbox',
			  'steadysets-icon-checkbox-checked' => 'steadysets-icon-checkbox-checked',
			  'steadysets-icon-checkmark' => 'steadysets-icon-checkmark',
			  'steadysets-icon-refresh' => 'steadysets-icon-refresh',
			  'steadysets-icon-reload' => 'steadysets-icon-reload',
			  'steadysets-icon-arrow-right' => 'steadysets-icon-arrow-right',
			  'steadysets-icon-arrow-down' => 'steadysets-icon-arrow-down',
			  'steadysets-icon-arrow-up' => 'steadysets-icon-arrow-up',
			  'steadysets-icon-arrow-left' => 'steadysets-icon-arrow-left',
			  'steadysets-icon-settings' => 'steadysets-icon-settings',
			  'steadysets-icon-battery-full' => 'steadysets-icon-battery-full',
			  'steadysets-icon-battery-75' => 'steadysets-icon-battery-75',
			  'steadysets-icon-battery-50' => 'steadysets-icon-battery-50',
			  'steadysets-icon-battery-25' => 'steadysets-icon-battery-25',
			  'steadysets-icon-battery-empty' => 'steadysets-icon-battery-empty',
			  'steadysets-icon-battery-charging' => 'steadysets-icon-battery-charging',
			  'steadysets-icon-uniE669' => 'steadysets-icon-uniE669',
			  'steadysets-icon-grid' => 'steadysets-icon-grid',
			  'steadysets-icon-list' => 'steadysets-icon-list',
			  'steadysets-icon-wifi-low' => 'steadysets-icon-wifi-low',
			  'steadysets-icon-folder-check' => 'steadysets-icon-folder-check',
			  'steadysets-icon-folder-settings' => 'steadysets-icon-folder-settings',
			  'steadysets-icon-folder-add' => 'steadysets-icon-folder-add',
			  'steadysets-icon-folder' => 'steadysets-icon-folder',
			  'steadysets-icon-window' => 'steadysets-icon-window',
			  'steadysets-icon-windows' => 'steadysets-icon-windows',
			  'steadysets-icon-browser' => 'steadysets-icon-browser',
			  'steadysets-icon-file-broken' => 'steadysets-icon-file-broken',
			  'steadysets-icon-align-justify' => 'steadysets-icon-align-justify',
			  'steadysets-icon-align-center' => 'steadysets-icon-align-center',
			  'steadysets-icon-align-right' => 'steadysets-icon-align-right',
			  'steadysets-icon-align-left' => 'steadysets-icon-align-left',
			  'steadysets-icon-file' => 'steadysets-icon-file',
			  'steadysets-icon-file-add' => 'steadysets-icon-file-add',
			  'steadysets-icon-file-settings' => 'steadysets-icon-file-settings',
			  'steadysets-icon-mute' => 'steadysets-icon-mute',
			  'steadysets-icon-heart' => 'steadysets-icon-heart',
			  'steadysets-icon-enter' => 'steadysets-icon-enter',
			  'steadysets-icon-volume-decrease' => 'steadysets-icon-volume-decrease',
			  'steadysets-icon-wifi-mid' => 'steadysets-icon-wifi-mid',
			  'steadysets-icon-volume' => 'steadysets-icon-volume',
			  'steadysets-icon-bookmark' => 'steadysets-icon-bookmark',
			  'steadysets-icon-screen' => 'steadysets-icon-screen',
			  'steadysets-icon-map' => 'steadysets-icon-map',
			  'steadysets-icon-measure' => 'steadysets-icon-measure',
			  'steadysets-icon-eyedropper' => 'steadysets-icon-eyedropper',
			  'steadysets-icon-support' => 'steadysets-icon-support',
			  'steadysets-icon-phone' => 'steadysets-icon-phone',
			  'steadysets-icon-email2' => 'steadysets-icon-email2',
			  'steadysets-icon-volume-increase' => 'steadysets-icon-volume-increase',
			  'steadysets-icon-wifi-full' => 'steadysets-icon-wifi-full'
		);

	$linecons = array(
			  'linecon-icon-heart' => 'linecon-icon-heart',
			  'linecon-icon-cloud' => 'linecon-icon-cloud',
			  'linecon-icon-star' => 'linecon-icon-star',
			  'linecon-icon-tv' => 'linecon-icon-tv',
			  'linecon-icon-sound' => 'linecon-icon-sound',
			  'linecon-icon-video' => 'linecon-icon-video',
			  'linecon-icon-trash' => 'linecon-icon-trash',
			  'linecon-icon-user' => 'linecon-icon-user',
			  'linecon-icon-key' => 'linecon-icon-key',
			  'linecon-icon-search' => 'linecon-icon-search',
			  'linecon-icon-eye' => 'linecon-icon-eye',
			  'linecon-icon-bubble' => 'linecon-icon-bubble',
			  'linecon-icon-stack' => 'linecon-icon-stack',
			  'linecon-icon-cup' => 'linecon-icon-cup',
			  'linecon-icon-phone' => 'linecon-icon-phone',
			  'linecon-icon-news' => 'linecon-icon-news',
			  'linecon-icon-mail' => 'linecon-icon-mail',
			  'linecon-icon-like' => 'linecon-icon-like',
			  'linecon-icon-photo' => 'linecon-icon-photo',
			  'linecon-icon-note' => 'linecon-icon-note',
			  'linecon-icon-food' => 'linecon-icon-food',
			  'linecon-icon-t-shirt' => 'linecon-icon-t-shirt',
			  'linecon-icon-fire' => 'linecon-icon-fire',
			  'linecon-icon-clip' => 'linecon-icon-clip',
			  'linecon-icon-shop' => 'linecon-icon-shop',
			  'linecon-icon-calendar' => 'linecon-icon-calendar',
			  'linecon-icon-wallet' => 'linecon-icon-wallet',
			  'linecon-icon-vynil' => 'linecon-icon-vynil',
			  'linecon-icon-truck' => 'linecon-icon-truck',
			  'linecon-icon-world' => 'linecon-icon-world',
			  'linecon-icon-clock' => 'linecon-icon-clock',
			  'linecon-icon-paperplane' => 'linecon-icon-paperplane',
			  'linecon-icon-params' => 'linecon-icon-params',
			  'linecon-icon-banknote' => 'linecon-icon-banknote',
			  'linecon-icon-data' => 'linecon-icon-data',
			  'linecon-icon-music' => 'linecon-icon-music',
			  'linecon-icon-megaphone' => 'linecon-icon-megaphone',
			  'linecon-icon-study' => 'linecon-icon-study',
			  'linecon-icon-lab' => 'linecon-icon-lab',
			  'linecon-icon-location' => 'linecon-icon-location',
			  'linecon-icon-display' => 'linecon-icon-display',
			  'linecon-icon-diamond' => 'linecon-icon-diamond',
			  'linecon-icon-pen' => 'linecon-icon-pen',
			  'linecon-icon-bulb' => 'linecon-icon-bulb',
			  'linecon-icon-lock' => 'linecon-icon-lock',
			  'linecon-icon-tag' => 'linecon-icon-tag',
			  'linecon-icon-camera' => 'linecon-icon-camera',
			  'linecon-icon-settings' => 'linecon-icon-settings'
		);
		
	// Icon list
	$icon_arr = array_merge($fa_icons, $steadysets, $linecons);

	vc_map( array(
	  "name" => __("Text With Icon", "js_composer"),
	  "base" => "text-with-icon",
	  "icon" => "icon-wpb-text-with-icon",
	  "category" => __('Nectar Elements', 'js_composer'),
	  "weight" => 1,
	  "description" => __('Add a text block with stylish icon', 'js_composer'),
	  "params" => array(
	    array(
		  "type" => "dropdown",
		  "heading" => __("Icon Type", "js_composer"),
		  "param_name" => "icon_type",
		  "admin_label" => true,
		  "value" => array(
			 "Font Icon" => "font_icon",
			 "Image Icon" => "image_icon",
		   ),
		  'save_always' => true,
		  "description" => __("Please select type of icon you would like for the text block", "js_composer")
		),
		array(
		  "type" => "dropdown",
		  "heading" => __("Icon", "js_composer"),
		  "param_name" => "icon",
		  "admin_label" => false,
		  "value" => $icon_arr,
		  'save_always' => true,
		  "description" => __("Please select the icon you wish to use", "js_composer"),
		  "dependency" => Array('element' => "icon_type", 'value' => array('font_icon'))
		),
	     array(
		  "type" => "dropdown",
		  "heading" => __("Color", "js_composer"),
		  "param_name" => "color",
		  "admin_label" => false,
		  'save_always' => true,
		  "value" => array(
			 "Accent-Color" => "Accent-Color",
			 "Extra-Color-1" => "Extra-Color-1",
			 "Extra-Color-2" => "Extra-Color-2",	
			 "Extra-Color-3" => "Extra-Color-3"
		   ),
		  "description" => __("Please select the color you wish for icon to display in", "js_composer"),
		  "dependency" => Array('element' => "icon_type", 'value' => array('font_icon'))
		),
		array(
			"type" => "attach_image",
			"class" => "",
			"heading" => "Icon Image",
			"param_name" => "icon_image",
			"value" => "",
			"description" => "",
			"dependency" => Array('element' => "icon_type", 'value' => array('image_icon'))
		),
		array(
	      "type" => "textarea_html",
	      "holder" => "div",
	      "heading" => __("Text Content", "js_composer"),
	      "param_name" => "content",
	      "value" => __("", "js_composer")
	    )
	  )
	));



	vc_map( array(
	  "name" => __("Fancy Unordered List", "js_composer"),
	  "base" => "fancy-ul",
	  "icon" => "icon-wpb-fancy-ul",
	  "category" => __('Nectar Elements', 'js_composer'),
	  "weight" => 1,
	  "description" => __('Make your lists appealing', 'js_composer'),
	  "params" => array(
	    array(
		  "type" => "dropdown",
		  "heading" => __("Icon Type", "js_composer"),
		  "param_name" => "icon_type",
		  "admin_label" => false,
		  'save_always' => true,
		  "value" => array(
			 "Standard Dash" => "standard_dash",
			 "Font Icon" => "font_icon",
		   ),
		  "description" => __("Please select type of icon you would like for your fancy list", "js_composer")
		),
		array(
		  "type" => "dropdown",
		  "heading" => __("Icon", "js_composer"),
		  "param_name" => "icon",
		  "admin_label" => false,
		  "value" => $icon_arr,
		  'save_always' => true,
		  "description" => __("Please select the icon you wish to use", "js_composer"),
		  "dependency" => Array('element' => "icon_type", 'value' => array('font_icon'))
		),
	     array(
		  "type" => "dropdown",
		  "heading" => __("Color", "js_composer"),
		  "param_name" => "color",
		  "admin_label" => false,
		  'save_always' => true,
		  "value" => array(
			 "Accent-Color" => "Accent-Color",
			 "Extra-Color-1" => "Extra-Color-1",
			 "Extra-Color-2" => "Extra-Color-2",	
			 "Extra-Color-3" => "Extra-Color-3"
		   ),
		  "description" => __("Please select the color you wish for icon to display in", "js_composer"),
		),
		
		array(
			"type" => "checkbox",
			"class" => "",
			"heading" => "Enable Animation",
			"value" => array("Enable Animation?" => "true" ),
			"param_name" => "enable_animation",
			"description" => "This will cause your list items to animate in one by one"
		),

		array(
	      "type" => "textarea_html",
	      "holder" => "div",
	      "heading" => __("Text Content", "js_composer"),
	      "param_name" => "content",
	      "value" => __("", "js_composer"),
	      "description" => __("Please use the Unordered List button <img src='".get_template_directory_uri() ."/nectar/assets/img/icons/ul.png' alt='unordered list' /> on the editor to create the points of your fancy list.", "js_composer")
	    )
	  )
	));

}

?>