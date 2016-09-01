<?php 

$options = get_nectar_theme_options(); 
$external_dynamic = (!empty($options['external-dynamic-css']) && $options['external-dynamic-css'] == 1) ? 'on' : 'off';

	if($external_dynamic != 'on') {

		ob_start(); 

		//boxed css
		if(!empty($options['boxed_layout']) && $options['boxed_layout'] == '1')  {
			
			$attachment = $options["background-attachment"];
			$position = $options["background-position"];
			$repeat = $options["background-repeat"];
			$background_color = $options["background-color"];
			
			echo '<style type="text/css">
			 body {
			 	background-image: url("'.nectar_options_img($options["background_image"]).'");
				background-position: '.$position.';
				background-repeat: '.$repeat.';
				background-color: '.$background_color.'!important;
				background-attachment: '.$attachment.';';
				if(!empty($options["background-cover"]) && $options["background-cover"] == '1') {
					echo 'background-size: cover;
					-moz-background-size: cover;
					-webkit-background-size: cover;
					-o-background-size: cover;';
				}
				
			 echo '} 
			</style>';
		}
	}
	
	//top nav
	
	$logo_height = (!empty($options['use-logo']) && !empty($options['logo-height'])) ? intval($options['logo-height']) : 30;
	$mobile_logo_height = (!empty($options['use-logo']) && !empty($options['mobile-logo-height'])) ? intval($options['mobile-logo-height']) : 24;
	$header_padding = (!empty($options['header-padding'])) ? intval($options['header-padding']) : 28;
	$nav_font_size = (!empty($options['use-custom-fonts']) && $options['use-custom-fonts'] == 1 && !empty($options['navigation_font_size']) && $options['navigation_font_size'] != '-') ? intval(substr($options['navigation_font_size'],0,-2) *1.4 ) : 20;
	$dd_indicator_height = (!empty($options['use-custom-fonts']) && $options['use-custom-fonts'] == 1 && !empty($options['navigation_font_size']) && $options['navigation_font_size'] != '-') ? intval(substr($options['navigation_font_size'],0,-2)) -1 : 20;
	$headerFormat = (!empty($options['header_format'])) ? $options['header_format'] : 'default';

	$padding_top = ceil(($logo_height/2)) - ceil(($nav_font_size/2));
	$padding_bottom = (ceil(($logo_height/2)) - ceil(($nav_font_size/2))) + $header_padding;
	
	$search_padding_top = ceil(($logo_height/2)) - ceil(21/2) +1;
	$search_padding_bottom =  (ceil(($logo_height/2)) - ceil(21/2));
	
	$using_secondary = (!empty($options['header_layout'])) ? $options['header_layout'] : ' ';
	
	if($using_secondary == 'header_with_secondary'){
	 	$header_space = $logo_height + ($header_padding*2) + 34;
	}
	else {
	 	$header_space = $logo_height + ($header_padding*2);
	}
	
	$page_transition_bg = (!empty($options['transition-bg-color'])) ? $options['transition-bg-color'] : '#ffffff';

	//woo product title
	$wooSocial = ( !empty($options['woo_social']) && $options['woo_social'] == 1 ) ? '1' : '0';
	$wooSocialCount = 0;
	$wooProductTitlePadding = 0;
	
	if($wooSocial == '1') {
		if(!empty($options['woo-facebook-sharing']) && $options['woo-facebook-sharing'] == 1) $wooSocialCount++;
		if(!empty($options['woo-twitter-sharing']) && $options['woo-twitter-sharing'] == 1) $wooSocialCount++;
		if(!empty($options['woo-pinterest-sharing']) && $options['woo-pinterest-sharing'] == 1) $wooSocialCount++;
		if(!empty($options['woo-google-plus-sharing']) && $options['woo-google-plus-sharing'] == 1) $wooSocialCount++;
		if(!empty($options['woo-linkedin-sharing']) && $options['woo-linkedin-sharing'] == 1) $wooSocialCount++;

		if(empty($options['product_tab_position']) || $options['product_tab_position'] == 'in_sidebar') $wooProductTitlePadding = ($wooSocialCount*52) + 50;
	}
	
	//legacy WP header changes
	if(floatval(get_bloginfo('version')) < "3.8"){
		echo '<style>
		html .admin-bar #header-outer, html .logged-in.buddypress #header-outer { top: 28px; } html .admin-bar #header-outer[data-using-secondary="1"], html .logged-in.buddypress #header-outer[data-using-secondary="1"] { top: 60px; }
		</style>';
	}

	$custom_loading_icon = null;

	if(isset($options['loading-image']['id'])){
		$custom_loading_icon = ' .nectar-slider-loading .loading-icon, .portfolio-loading, #ajax-loading-screen .loading-icon, .loading-icon, .pp_loaderIcon { background-image: url("'.nectar_options_img($options["loading-image"]).'"); } ';
	} else {
		if (!empty($options['loading-image'])) { 
		    $custom_loading_icon = ' .nectar-slider-loading .loading-icon, .portfolio-loading, #ajax-loading-screen .loading-icon, .loading-icon, .pp_loaderIcon { background-image: url("'.$options["loading-image"].'"); } ';
		} 
	}

	
	 
	if($external_dynamic != 'on') { echo '<style type="text/css">'; }
	  
	  echo '
	  #header-outer { padding-top: '.$header_padding.'px; }
	  
	  #header-outer #logo img { height: ' . $logo_height .'px; }

	  header#top nav > ul > li > a {
	  	padding-bottom: '. $padding_bottom .'px;
		padding-top: '. $padding_top .'px;
	  }'; 

	  if($headerFormat == 'centered-menu-under-logo') { 
		echo '#header-outer .cart-menu {
	 		padding-bottom: '. intval($padding_bottom + ceil(($nav_font_size - 21)/2) + $logo_height/2 + 7) .'px;
			padding-top: '. intval($padding_top+$header_padding + ceil(($nav_font_size - 21)/2) + $logo_height/2 + 7) .'px;
		 }';
	 } else {
	 	echo '#header-outer .cart-menu {
	 		padding-bottom: '. intval($padding_bottom + ceil(($nav_font_size - 21)/2)) .'px;
			padding-top: '. intval($padding_top+$header_padding + ceil(($nav_font_size - 21)/2)) .'px;
		 }';
	}
	  
	 echo'header#top nav > ul li#search-btn, header#top nav > ul li.slide-out-widget-area-toggle {
	  	 padding-bottom: '. $search_padding_bottom .'px;
		 padding-top: '. $search_padding_top .'px;
	  }

	  header#top .sf-menu > li.sfHover > ul { top: '.$nav_font_size.'px; }

	 .sf-sub-indicator { height: '.$dd_indicator_height.'px; }

	 #header-space { height: '. $header_space .'px;}
	 
	 body[data-smooth-scrolling="1"] #full_width_portfolio .project-title.parallax-effect { top: '.$header_space.'px; }
	 
	 body.single-product div.product .product_title { padding-right:'.$wooProductTitlePadding.'px; } ';
	
	if($page_transition_bg != '#ffffff') echo '#ajax-loading-screen, #ajax-loading-screen[data-effect="center_mask_reveal"] span { background-color: '.$page_transition_bg.'} .default-loading-icon { border-color: rgba(255,255,255,0.2); } ';

	 /*perma trans fix*/
	 $perm_trans = (!empty($options['header-permanent-transparent'])) ? $options['header-permanent-transparent'] : 'false';
	 if($perm_trans == 1) {
	 	echo '#header-outer[data-permanent-transparent="1"] .midnightHeader header#top #logo img, #header-outer[data-permanent-transparent="1"] .midnightHeader header#top #logo.no-image, #header-outer[data-permanent-transparent="1"] .midnightHeader header#top ul.sf-menu > li > a { margin-top: '.$header_padding.'px; }';
	 	echo '#header-outer[data-permanent-transparent="1"][data-full-width="false"] .midnightHeader header#top nav ul.buttons li, body:not(.ascend) #header-outer[data-permanent-transparent="1"][data-full-width="true"] .midnightHeader header#top nav ul.buttons li {  margin-top: '.$header_padding.'px; }';
	 	echo '#header-outer[data-permanent-transparent="1"][data-full-width="false"][data-has-menu="false"] header#top, body:not(.ascend) #header-outer[data-permanent-transparent="1"][data-full-width="true"][data-has-menu="false"] header#top { padding-bottom: '.$header_padding.'px; }';
	 	echo '#header-outer[data-permanent-transparent="1"][data-format="centered-menu-under-logo"] .midnightHeader #logo { height: ' . $logo_height .'px; }';
	 }

	 /*fix for when non ascend skin is used and only slide out widget area exists (needs bottom padding) */
	 echo '@media only screen and (min-width: 1000px) { body:not(.ascend)[data-slide-out-widget-area="true"] #header-outer[data-has-menu="false"][data-permanent-transparent="false"] header#top, body.ascend[data-slide-out-widget-area="true"] #header-outer[data-has-menu="false"][data-permanent-transparent="false"][data-full-width="false"] header#top { padding-bottom: '.$header_padding.'px; } }';

	 /*mobile logo height*/
	 echo '@media only screen and (max-width: 1000px) { 
	 	body header#top #logo img, #header-outer[data-permanent-transparent="false"] #logo .dark-version { 
	 		height: '.$mobile_logo_height.'px!important; 
	 	} 
	 	header#top .col.span_9 {
	 		min-height: '. intval($mobile_logo_height+24) .'px; 
	 		line-height: '. intval($mobile_logo_height+4) .'px; 
	 	}
	 }';

	 /*custom header bg opacity for light/dark*/
	 if(!empty($options['header-bg-opacity']) && !empty($options['header-color'])) {
	 	if($options['header-color'] == 'light' || $options['header-color'] == 'dark') {

			 $navBGColor = ($options['header-color'] == 'light') ? 'ffffff' : '000000';
			 $colorR = hexdec( substr( $navBGColor, 0, 2 ) );
			 $colorG = hexdec( substr( $navBGColor, 2, 2 ) );
			 $colorB = hexdec( substr( $navBGColor, 4, 2 ) );
			 $colorA = ($options['header-bg-opacity'] != '100') ? '0.'.$options['header-bg-opacity'] : $options['header-bg-opacity'];

			 echo 'body #header-outer, body[data-header-color="dark"] #header-outer { background-color: rgba('.$colorR.','.$colorG.','.$colorB.','.$colorA.'); }';
		}
	}

	echo $custom_loading_icon;
	 
	 
	 //nectar slider font calcs
	 $heading_size = (!empty($options['use-custom-fonts']) && $options['use-custom-fonts'] == 1 && $options['nectar_slider_heading_font_size'] != '-') ? intval($options['nectar_slider_heading_font_size']) : 60;
	 $caption_size = (!empty($options['use-custom-fonts']) && $options['use-custom-fonts'] == 1 && $options['home_slider_caption_font_size'] != '-') ? intval($options['home_slider_caption_font_size']) : 24;
	 
	 echo '@media only screen and (min-width: 1000px) and (max-width: 1300px) {
	    .nectar-slider-wrap[data-full-width="true"] .swiper-slide .content h2, 
	    .nectar-slider-wrap[data-full-width="boxed-full-width"] .swiper-slide .content h2,
	    .full-width-content .vc_span12 .swiper-slide .content h2 {
			font-size: ' .$heading_size*0.75 . 'px!important;
			line-height: '.$heading_size*0.85 .'px!important;
		}

		.nectar-slider-wrap[data-full-width="true"] .swiper-slide .content p, 
		.nectar-slider-wrap[data-full-width="boxed-full-width"] .swiper-slide .content p, 
	    .full-width-content .vc_span12 .swiper-slide .content p {
			font-size: ' .$caption_size *0.75 . 'px!important;
			line-height: '.$caption_size *1.3 .'px!important;
		}
	}
	
	@media only screen and (min-width : 690px) and (max-width : 1000px) {
		.nectar-slider-wrap[data-full-width="true"] .swiper-slide .content h2, 
		.nectar-slider-wrap[data-full-width="boxed-full-width"] .swiper-slide .content h2,
	    .full-width-content .vc_span12 .swiper-slide .content h2 {
			font-size: ' . (($heading_size*0.55 > 20) ? $heading_size*0.55 : 20) . 'px!important;
			line-height: '. (($heading_size*0.55 > 20) ? $heading_size*0.65 : 27) .'px!important;
		}

		.nectar-slider-wrap[data-full-width="true"] .swiper-slide .content p, 
		.nectar-slider-wrap[data-full-width="boxed-full-width"] .swiper-slide .content p, 
	    .full-width-content .vc_span12 .swiper-slide .content p {
			font-size: ' . (($caption_size *0.55 > 12) ? $caption_size *0.55 : 12). 'px!important;
			line-height: '.$caption_size *1 .'px!important;
		}
	}
	
	@media only screen and (max-width : 690px) {
		.nectar-slider-wrap[data-full-width="true"][data-fullscreen="false"] .swiper-slide .content h2, 
		.nectar-slider-wrap[data-full-width="boxed-full-width"][data-fullscreen="false"] .swiper-slide .content h2,
	    .full-width-content .vc_span12 .nectar-slider-wrap[data-fullscreen="false"] .swiper-slide .content h2 {
			font-size: ' .(($heading_size*0.25 > 14) ? $heading_size*0.25 : 14) . 'px!important;
			line-height: '.(($heading_size*0.25 > 14) ? $heading_size*0.35 : 20) .'px!important;
		}

		.nectar-slider-wrap[data-full-width="true"][data-fullscreen="false"] .swiper-slide .content p, 
		.nectar-slider-wrap[data-full-width="boxed-full-width"][data-fullscreen="false"]  .swiper-slide .content p, 
	    .full-width-content .vc_span12 .nectar-slider-wrap[data-fullscreen="false"] .swiper-slide .content p {
			font-size: ' .(($caption_size *0.32 > 10) ? $caption_size *0.32 : 10) . 'px!important;
			line-height: '.(($caption_size *0.73 > 10) ? $caption_size *0.73 : 18) .'px!important;
		}
	}
	';
	 
	$removeHeaderSearch = (!empty($options['header-disable-search']) && $options['header-disable-search'] == '1') ? 'true' : 'false';
	if($removeHeaderSearch == 'true') {
		echo '#mobile-menu #mobile-search, header#top nav ul #search-btn {
			   display: none!important;
			}';
	}

	if($external_dynamic != 'on') {
		 //page header
		 global $post;
		 $font_color = get_post_meta($post->ID, '_nectar_header_font_color', true);
		 if(!empty($font_color)) {
			 echo '#page-header-bg h1, #page-header-bg .subheader, .nectar-box-roll .overlaid-content h1, .nectar-box-roll .overlaid-content .subheader, #page-header-bg #portfolio-nav a i, body .section-title #portfolio-nav a:hover i, .page-header-no-bg h1, .page-header-no-bg span, #page-header-bg #portfolio-nav a i, #page-header-bg span { color: '. $font_color .'!important; } ';
			 echo 'body #page-header-bg a.pinterest-share i, body #page-header-bg a.facebook-share i, body #page-header-bg a.linkedin-share i, body #page-header-bg .twitter-share i, body #page-header-bg .google-plus-share i, 
		 	 body #page-header-bg .icon-salient-heart, body #page-header-bg .icon-salient-heart-2 { color: '. $font_color .'; }';
		 	 echo 'body .section-title #portfolio-nav a:hover i { opacity: 0.75;}';

		 	 $font_color_no_hash =  substr($font_color,1);
		 	 $colorR = hexdec( substr( $font_color_no_hash, 0, 2 ) );
			 $colorG = hexdec( substr( $font_color_no_hash, 2, 2 ) );
			 $colorB = hexdec( substr( $font_color_no_hash, 4, 2 ) );
		 	 echo '.single #page-header-bg .blog-title #single-meta ul li > a, .single #page-header-bg .blog-title #single-meta ul .n-shortcode a { border-color: rgba('.$colorR.','.$colorG.','.$colorB.',0.4)!important; }';
		 	 echo '.single #page-header-bg .blog-title #single-meta ul li > a:hover, .single #page-header-bg .blog-title #single-meta ul .n-shortcode a:hover, .single #page-header-bg .blog-title #single-meta ul li:not(.meta-share-count):hover > a{ border-color: rgba('.$colorR.','.$colorG.','.$colorB.',1)!important; }';
		 	 echo '.single #page-header-bg #single-meta li span, .single #page-header-bg #single-meta li.meta-comment-count a, .single #page-header-bg #single-meta ul li i {  color: '. $font_color .'!important; }';
		 	 echo '.single #page-header-bg #single-meta ul li.meta-share-count .nectar-social a i { color: rgba('.$colorR.','.$colorG.','.$colorB.',0.7)!important; }';
		 	 echo '.single #page-header-bg #single-meta ul li.meta-share-count .nectar-social a:hover i { color: rgba('.$colorR.','.$colorG.','.$colorB.',1)!important; }';
		 }	

		 // header transparent option
		if(!empty($options['transparent-header']) && $options['transparent-header'] == '1') {
			
			$starting_color = (empty($options['header-starting-color'])) ? '#ffffff' : $options['header-starting-color'];
			$activate_transparency = using_page_header($post->ID);
			
			if($activate_transparency){
				
				//old IE versions
				echo '.no-rgba #header-space { display: none;  } ';
				
				echo '@media only screen and (min-width: 1000px) {
					
					 #header-space {
					 	 display: none; 
					 } 
					 .nectar-slider-wrap.first-section, .parallax_slider_outer.first-section, .full-width-content.first-section, 
					 .parallax_slider_outer.first-section .swiper-slide .content, .nectar-slider-wrap.first-section .swiper-slide .content, #page-header-bg, .nder-page-header, #page-header-wrap,
					 .full-width-section.first-section {
					 	 margin-top: 0!important;
					 }
					 
					 
					 body #page-header-bg, body #page-header-wrap {
					 	height: '.$header_space.'px;
					 }
					 
					 .swiper-container .slider-prev, .swiper-container .slider-next {
					 	top: 52%!important;	
					 }
					 
					 .first-section .nectar-slider-loading .loading-icon { opacity: 0 }
					 
					 body #search-outer { z-index: 100000; }
					 
					 #header-outer.transparent header#top #logo, #header-outer.transparent header#top #logo:hover {
					 	color: '.$starting_color.'!important;
					 }

					 #header-outer.transparent header#top nav > ul > li > a, 
					 #header-outer.transparent header#top nav ul #search-btn a span.icon-salient-search, 
					 #header-outer.transparent nav > ul > li > a > .sf-sub-indicator [class^="icon-"], 
					 #header-outer.transparent nav > ul > li > a > .sf-sub-indicator [class*=" icon-"],
					 #header-outer.transparent .cart-menu .cart-icon-wrap .icon-salient-cart,
					 .ascend #boxed #header-outer.transparent .cart-menu .cart-icon-wrap .icon-salient-cart
					  {
					 	color: '.$starting_color.'!important;
					 	opacity: 0.75!important;
						transition: opacity 0.2s linear, color 0.2s linear;
					 }
					#header-outer.transparent:not([data-lhe="animated_underline"]) header#top nav > ul > li > a:hover, #header-outer.transparent:not([data-lhe="animated_underline"]) header#top nav .sf-menu > li.sfHover > a, #header-outer.transparent:not([data-lhe="animated_underline"]) header#top nav .sf-menu > li.current_page_ancestor > a, 
					#header-outer.transparent header#top nav .sf-menu > li.current-menu-item > a, #header-outer.transparent:not([data-lhe="animated_underline"]) header#top nav .sf-menu > li.current-menu-ancestor > a, #header-outer.transparent:not([data-lhe="animated_underline"]) header#top nav .sf-menu > li.current_page_item > a,
					#header-outer.transparent header#top nav > ul > li > a:hover > .sf-sub-indicator > i, #header-outer.transparent header#top nav > ul > li.sfHover > a > span > i, #header-outer.transparent header#top nav ul #search-btn a:hover span, #header-outer.transparent header#top nav ul .slide-out-widget-area-toggle a:hover span,
					#header-outer.transparent header#top nav .sf-menu > li.current-menu-item > a i, #header-outer.transparent header#top nav .sf-menu > li.current-menu-ancestor > a i,
					#header-outer.transparent .cart-outer:hover .icon-salient-cart, .ascend #boxed #header-outer.transparent .cart-outer:hover .cart-menu .cart-icon-wrap .icon-salient-cart
					
					{
						opacity: 1!important;
						color: '.$starting_color.'!important;
					}

					#header-outer[data-lhe="animated_underline"].transparent header#top nav > ul > li > a:after, #header-outer.transparent header#top nav>ul>li.button_bordered>a:before {
						border-color: '.$starting_color.'!important;
					}


					#header-outer.transparent:not(.directional-nav-effect) > header#top nav ul .slide-out-widget-area-toggle a i.lines, 
					#header-outer.transparent:not(.directional-nav-effect) > header#top nav ul .slide-out-widget-area-toggle a i.lines:before,
					#header-outer.transparent:not(.directional-nav-effect) > header#top nav ul .slide-out-widget-area-toggle a i.lines:after,
					#header-outer.transparent.directional-nav-effect > header#top nav ul .slide-out-widget-area-toggle a span.light .lines-button i, #header-outer.transparent.directional-nav-effect > header#top nav ul .slide-out-widget-area-toggle a span.light .lines-button i:after, #header-outer.transparent.directional-nav-effect > header#top nav ul .slide-out-widget-area-toggle a span.light .lines-button i:before,
					#header-outer.transparent:not(.directional-nav-effect) .midnightHeader.nectar-slider header#top nav ul .slide-out-widget-area-toggle a i.lines, 
					#header-outer.transparent:not(.directional-nav-effect) .midnightHeader.nectar-slider header#top nav ul .slide-out-widget-area-toggle a i.lines:before,
					#header-outer.transparent:not(.directional-nav-effect) .midnightHeader.nectar-slider header#top nav ul .slide-out-widget-area-toggle a i.lines:after,
					#header-outer.transparent.directional-nav-effect .midnightHeader.nectar-slider header#top nav ul .slide-out-widget-area-toggle a span.light .lines-button i, #header-outer.transparent.directional-nav-effect .midnightHeader.nectar-slider header#top nav ul .slide-out-widget-area-toggle a span.light .lines-button i:after, #header-outer.transparent.directional-nav-effect .midnightHeader.nectar-slider header#top nav ul .slide-out-widget-area-toggle a span.light .lines-button i:before  {
						background-color: '.$starting_color.'!important;
					}
					#header-outer.transparent header#top nav ul .slide-out-widget-area-toggle a i.lines {
						opacity: 0.75!important;
					}
					#header-outer.transparent.side-widget-open header#top nav ul .slide-out-widget-area-toggle a i.lines,
					#header-outer.transparent header#top nav ul .slide-out-widget-area-toggle a:hover i.lines, 
					#header-outer.transparent header#top nav ul .slide-out-widget-area-toggle a:hover i.lines:before,
					#header-outer.transparent header#top nav ul .slide-out-widget-area-toggle a:hover i.lines:after {
						opacity: 1!important;
					}
			}';
			} else if(!empty($options['header-bg-opacity'])) {
				$header_space_bg_color = (!empty($options['overall-bg-color'])) ? $options['overall-bg-color'] : '#ffffff';
				echo '#header-space { background-color: '.$header_space_bg_color.'}';
			}

		}

	}


	
	
	 // ext responsive
	global $woocommerce;
	
	if(!empty($options['responsive']) && $options['responsive'] == 1 && !empty($options['ext_responsive']) && $options['ext_responsive'] == '1') {
		echo '@media only screen and (min-width: 1000px) {
			
			    .container, .woocommerce-tabs .full-width-content .tab-container, .nectar-recent-posts-slider .flickity-page-dots {
			      max-width: 1425px; 
				  width: 100%;
				  margin: 0 auto;
				  padding: 0px 90px; 
			    } 

			    body .container .page-submenu.stuck .container:not(.tab-container), .nectar-recent-posts-slider .flickity-page-dots {
			    	  padding: 0px 90px!important; 
			    }	
				
				.swiper-slide .content {
				  padding: 0px 90px; 
				}
				
				body .container .container:not(.tab-container):not(.recent-post-container) {
					width: 100%!important;
					padding: 0!important;
				}
				
				
				body .carousel-heading .container {
					padding: 0 10px!important;
				}
				body .carousel-heading .container .carousel-next { right: 10px; } body .carousel-heading .container .carousel-prev { right: 35px; }
				.carousel-wrap[data-full-width="true"] .carousel-heading a.portfolio-page-link { left: 90px; }
				.carousel-wrap[data-full-width="true"] .carousel-heading { margin-left: -20px; margin-right: -20px; }
				.carousel-wrap[data-full-width="true"] .carousel-next { right: 90px!important; } .carousel-wrap[data-full-width="true"] .carousel-prev { right: 115px!important; }
				.carousel-wrap[data-full-width="true"] { padding: 0!important; }
				.carousel-wrap[data-full-width="true"] .caroufredsel_wrapper { padding: 20px!important; }
				
				#search-outer #search #close a {
					right: 90px;
				}
	
	
				#boxed, #boxed #header-outer, #boxed #header-secondary-outer, #boxed #slide-out-widget-area-bg.fullscreen, #boxed #page-header-bg[data-parallax="1"], #boxed #featured, body[data-footer-reveal="1"] #boxed #footer-outer, #boxed .orbit > div, #boxed #featured article, .ascend #boxed #search-outer {
				   max-width: 1400px!important;
				   width: 90%!important;
				   min-width: 980px;
				}

				body[data-hhun="1"] #boxed #header-outer:not(.detached), body[data-hhun="1"] #boxed #header-secondary-outer {
					width: 100%!important;
				}


				#boxed #search-outer #search #close a {
					right: 0!important;
				}

				#boxed .container {
				  width: 92%;
				  padding: 0;
			    } 
				
				#boxed #footer-outer #footer-widgets, #boxed #footer-outer #copyright {
					padding-left: 0;
					padding-right: 0;
				}

				#boxed .carousel-wrap[data-full-width="true"] .carousel-heading a.portfolio-page-link { left: 35px; }
				#boxed .carousel-wrap[data-full-width="true"] .carousel-next { right: 35px!important; } #boxed .carousel-wrap[data-full-width="true"] .carousel-prev { right: 60px!important; }

				
			 }';


		if($external_dynamic != 'on') {	 

			if($woocommerce && $woocommerce->cart->cart_contents_count > 0 && !empty($options['enable-cart']) && $options['enable-cart'] == '1') {
				echo '@media only screen and (min-width: 1080px) and (max-width: 1475px) {
				    header#top nav > ul.buttons {
					  padding-right: 20px!important; 
				    } 
					#boxed header#top nav > ul.product_added.buttons {
						padding-right: 0px!important; 
					}
					#search-outer #search #close a {
						right: 110px;
					}
				 }';
			}
			elseif($woocommerce && !empty($options['enable-cart']) && $options['enable-cart'] == '1') {
				echo '@media only screen and (min-width: 1080px) and (max-width: 1475px) {
				    header#top nav > ul.product_added.buttons {
					  padding-right: 20px!important; 
				    } 
					#boxed header#top nav > ul.product_added.buttons {
						padding-right: 0px!important; 
					}
					#search-outer #search #close a.product_added {
						right: 110px;
					}
				 }';
			 }

		}
  
	} 
	
	echo '.pagination-navigation { -webkit-filter: url("'.esc_url(get_permalink()).'#goo"); filter: url("'.esc_url(get_permalink()).'#goo"); }';

	//full width header shopping cart fix
	if($external_dynamic != 'on') {	
		if($woocommerce && $woocommerce->cart->cart_contents_count > 0 && !empty($options['enable-cart']) && $options['enable-cart'] == '1' && !empty($options['header-fullwidth']) && $options['header-fullwidth'] == '1') {
			echo '@media only screen and (min-width: 1080px) {
				#header-outer[data-full-width="true"] header#top nav > ul.product_added.buttons {
			 	 padding-right: 80px!important; 
		        }
		        body:not(.ascend) #boxed #header-outer[data-full-width="true"] header#top nav > ul.product_added.buttons { padding-right: 0px!important;  }

		        body:not(.ascend) #header-outer[data-full-width="true"][data-remove-border="true"].transparent header#top nav > ul.product_added .slide-out-widget-area-toggle,
		        body:not(.ascend) #header-outer[data-full-width="true"][data-remove-border="true"].side-widget-open header#top nav > ul.product_added .slide-out-widget-area-toggle {
		          margin-right: -20px!important; 
		    	}
		    }';
		} elseif($woocommerce && !empty($options['enable-cart']) && $options['enable-cart'] == '1' && !empty($options['header-fullwidth']) && $options['header-fullwidth'] == '1') {
			echo '@media only screen and (min-width: 1080px) {
				#header-outer[data-full-width="true"] header#top nav > ul.product_added.buttons {
			 	 padding-right: 80px!important; 
		        }
		        body:not(.ascend) #header-outer[data-full-width="true"][data-remove-border="true"].transparent header#top nav > ul.product_added .slide-out-widget-area-toggle,
		        body:not(.ascend) #header-outer[data-full-width="true"][data-remove-border="true"].side-widget-open header#top nav > ul.product_added .slide-out-widget-area-toggle {
		          margin-right: -20px!important; 
		    	}
		    
		    }';
		}

	
		if($woocommerce && !empty($options['product_tab_position']) && $options['product_tab_position'] == 'fullwidth') echo '
		 .woocommerce.single-product #single-meta { position: relative!important; top: 0!important; margin: 0; left: 8px; height: auto; } 
		 .woocommerce.single-product #single-meta:after { display: block; content: " "; clear: both; height: 1px;  } 
		 .woocommerce-tabs { margin-top: 40px; clear: both; }
		 @media only screen and (min-width: 1000px) {
			 .woocommerce #reviews #comments, .woocommerce #reviews #review_form_wrapper {  float: left; width: 47%; }
			 .woocommerce #reviews #comments { margin-right: 3%; width: 50%; } 
			 .woocommerce.ascend #respond { margin-top: 0px!important; }
			 .woocommerce .woocommerce-tabs > div { margin-top: 15px!important; }
			 .woocommerce #reviews #reply-title { margin-top: 5px!important; }
		 }';
		
	}
	
	if($external_dynamic != 'on') {

		//custom css
		if(!empty($options["custom-css"])){
			echo $options["custom-css"];
		} 

		echo '</style>';
		
		
		$dynamic_css = ob_get_contents();
		ob_end_clean();
		
		echo nectar_quick_minify($dynamic_css);	


	} else {
		//custom css
		if(!empty($options["custom-css"])){
			echo $options["custom-css"];
		} 
	}
	
	
	


?>