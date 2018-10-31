<?php

/* === Add module button into page builder === */

if (!class_exists('pageBuilderModule_1_0')) {
	class pageBuilderModule_1_0 {
	
		var $id, $title, $icon;
		var $options = array();
	
		function __construct($id, $title, $icon='75') {
			$this->id = $id;
			$this->title = $title;
			$this->icon = $icon;
		}

		// Code from: http://www.elegantthemes.com/blog/resources/elegant-icon-font
		// Change &#xe031; to "\e031" (double quotes important, as are leading zeros) 
		// Uses etbuilder font by default. Change to 'ETmodules' for more icons.
		function set_module_button_icon() {
			echo "<style>.et-pb-all-modules .".htmlentities($this->id).":before{font-family: 'ETmodules';content:'\\".htmlentities($this->icon)."';}</style>";
		}
		
		function add_options_template() {
		
			// Start the template
			echo '<script type="text/template" id="et-builder-'.htmlentities($this->id).'-module-template">';
			echo '<h3 class="et-pb-settings-heading">Fullwidth Featured Posts Module Settings</h3>';
			echo '<div class="et-pb-main-settings">';
			
			// Output the individual options
			foreach($this->options as $optid=>$opt) { 
			
				if ($optid == 'admin_label') { 
					$etid = htmlentities($optid); 
				} else { 
					$etid = "et_pb_".htmlentities($optid); 
				}
			
				// Start the option
				echo '<div class="et-pb-option '.(!empty($opt['depends_on'])?'et-pb-depends':'').'"';
				if (!empty($opt['depends_on']) and !empty($opt['depends_on_val'])) {
					echo ' data-depends_show_if="'.htmlentities($opt['depends_on_val']).'"';
				}
				echo '>';
				echo '<label for="'.$etid.'">'.htmlentities($opt['title']).': </label>';
				echo '<div class="et-pb-option-container">';
				
				// Output the option input element
				if ($opt['type']=='select') { // Select boxes
					echo '<select name="'.$etid.'" id="'.$etid.'"';
					if (!empty($opt['affects'])) {
						echo ' class="et-pb-affects" data-affects="#et_pb_'.htmlentities($opt['affects']).'"';
					}
					echo '>';
					foreach($opt['select_options'] as $k=>$v) {
						echo '<option value="'.htmlentities($k).'"<%= typeof('.$etid.')!=="undefined" && "'.htmlentities($k).'"==='.$etid.' ? " selected=selected" : "" %>>'.htmlentities($v).'</option>';
					}
					echo '</select>';
					
				} elseif ($opt['type']=='text') { // text input boxes
					echo '<input id="'.$etid.'" type="text" class="regular-text" value="<%= typeof('.$etid.')!=="undefined" ? '.$etid.' : "'.htmlentities($opt['default']).'" %>"/>';
					
				} elseif ($opt['type']=='checkboxes') { // checkboxes
					echo '<% var '.$etid.'_temp = typeof '.$etid.' !== "undefined" ? '.$etid.'.split(",") : []; %>';
					foreach($opt['checkboxes'] as $k=>$v) {
						echo '<label><input type="checkbox" name="'.$etid.'" value="'.htmlentities($k).'"<%= _.contains('.$etid.'_temp, "'.htmlentities($k).'" ) ? checked="checked" : "" %>> '.htmlentities($v).'</label><br/>';
					}
				}
				
				// End the option
				echo '<p class="description">'.$opt['description_html'].'</p>';
				echo '</div>';
				echo '</div>';
			}
			
			// End the template
			echo '</div>';
			echo '</script>';
			
		}
			
		function enable() {
		
			//add_action('admin_enqueue_scripts', array($this, 'add_module_button'));
			add_action('admin_head', array($this, 'set_module_button_icon'));
			add_action('admin_footer', array($this, 'add_options_template'));
			
			// Todo - only call on pagebuilder on post pages
			add_action('admin_enqueue_scripts', array($this, 'add_to_pagebuilder_js'), 100); // run once pagebuilder admin.js has been enqueued
			
		}
		
		// modify the divi pagebuilder admin.js file to add our module into pagebuilder when loading a saved page
		function add_to_pagebuilder_js() {
			global $wp_scripts;
			if (isset($wp_scripts->registered['et_pb_admin_js'])) { // if pagebuilder is being used
				$adminjsurl = $wp_scripts->registered['et_pb_admin_js']->src;
				$adminjs = file_get_contents($adminjsurl);	// Todo - do this in an efficient, nice way (and access file directly, not via URL)
				$adminjs = str_replace('et_pb_section|et_pb_row', 'et_pb_section|et_pb_row|'.$this->id, $adminjs); // add in our module
				$adminjs = str_replace("if ( shortcode_name.indexOf( 'et_pb_' ) !== -1", 
									   "if ( shortcode_name.indexOf( 'et_pb_' ) !== -1 || shortcode_name == '".$this->id."'", 
									   $adminjs); // add in our module
									   
				
				// add to module selection box
				$adminjs.="jQuery(function($){ ET_PageBuilder_Layout = new ET_PageBuilder.Layout; ET_PageBuilder_Layout.get('modules').push({'title':'".esc_js($this->title)."', 'label':'".esc_js($this->id)."', 'fullwidth_only':'on'}); });";
				
				// create and register the modified .js file
				$newjs_path = dirname(__FILE__).'/et_pb_admin.js';
				file_put_contents($newjs_path, $adminjs);
				$newjs_url = plugin_dir_url($newjs_path).'et_pb_admin.js';
				$wp_scripts->registered['et_pb_admin_js']->src = $newjs_url;
			} 
		}
		
		// === User-Callable Option Templates ==== //
		
		// add an option to the module
		function add_option($id, $args) { 
		
			$opt = array();
			$opt['title'] = (empty($args['title'])?'':$args['title']);
			$opt['description_html'] = (empty($args['description_html'])?'':$args['description_html']);
			$opt['default'] = (empty($args['default'])?'':$args['default']);
			
			// handle option types
			if (!empty($args['type'])) {
			
				$opt['type'] = $args['type'];
				
				if ($args['type']=='select') {
					$opt['select_options'] = (empty($args['select_options'])?array():$args['select_options']);
				} elseif ($args['type']=='checkboxes') {
					$opt['checkboxes'] = (empty($args['checkboxes'])?array():$args['checkboxes']); 
				}
			} 
			
			// handle dependencies
			if (!empty($args['depends_on'])) {
				$opt['depends_on'] = $args['depends_on'];
				$opt['depends_on_val'] = $args['depends_on_val'];
				$this->options[$args['depends_on']]['affects'] = $id;
			}
			
			$this->options[$id] = $opt;
		}
		
	}
}