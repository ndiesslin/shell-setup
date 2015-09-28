<?php
/*
Plugin Name: Divi Booster 
Plugin URI: 
Description: Bug fixes and enhancements for Elegant Themes' Divi Theme.
Author: Dan Mossop
Version: 1.8.2
Author URI: http://www.danmossop.com
*/				

// === Plugin Updates and Licensing === //

// EDD licensing constants
define('WTFDIVI_STORE_URL', 'https://www.wpthemefaqs.com'); 
define('WTFDIVI_ITEM_NAME', 'Divi Booster Plugin'); 

/************************************
* the code below is just a standard
* options page. Substitute with 
* your own.
*************************************/

function wtfdivi_register_option() {
	// creates our settings in the options table
	register_setting('wtfdivi_license', 'wtfdivi_license_key', 'wtfdivi_sanitize_license' );
}
add_action('admin_init', 'wtfdivi_register_option');

function wtfdivi_sanitize_license( $new ) {
	$old = get_option( 'wtfdivi_license_key' );
	if( $old && $old != $new ) {
		delete_option( 'wtfdivi_license_status' ); // new license has been entered, so must reactivate
	}
	return $new;
}



/************************************
* this illustrates how to activate 
* a license key
*************************************/

function wtfdivi_activate_license() {

	// listen for our activate button to be clicked
	if(isset($_POST['edd_license_activate'])) {

		// Check for the nonce
	 	if(!check_admin_referer('wtfdivi_nonce', 'wtfdivi_nonce')) { 
			return wtfdivi_error('Nonce check failed, please reload the page and try again');
		}

		// make sure license key provided
		if (empty($_POST['wtfdivi_license_key'])) { 
			return wtfdivi_error('License key cannot be empty');
		}
		
		$license = preg_replace('#[^0-9a-z]#', '', trim($_POST['wtfdivi_license_key'])); 
	
		if (strlen($license)!=32) { 
			return wtfdivi_error('License key should be 32 characters long');
		}
			
		// data to send in our API request
		$api_params = array( 
			'edd_action'=> 'activate_license', 
			'license' 	=> urlencode($license), 
			'item_name' => urlencode(WTFDIVI_ITEM_NAME), // the name of our product in EDD
			'url'       => urlencode(home_url())
		);
		
		// Call the custom API.
		$activation_url = add_query_arg($api_params, WTFDIVI_STORE_URL);
		$response = wp_remote_get(esc_url_raw($activation_url), array('timeout'=>15, 'sslverify'=>false));
		
		// Make sure the response came back okay
		if (is_wp_error($response)) { 
			return wtfdivi_error($response->get_error_message(), $activation_url);
		}

		// Check the response looks valid
		if (!isset($response['response']) or !isset($response['response']['code'])) {
			return wtfdivi_error("Not a valid HTTP response", $activation_url);
		}

		// Check the return code
		if ($response['response']['code'] !== 200) {
			return wtfdivi_error("Update server returned HTTP response code ".$response['response']['code'], $activation_url);
		}
		
		if (empty($response['body'])) {
			return wtfdivi_error("Empty / missing response body", $activation_url);
		}
				
		$license_data = json_decode($response['body']);
		
		if (function_exists('json_last_error') and $json_error_code = json_last_error()) {
			return wtfdivi_error("Response is not valid json (error code: ".$json_error_code.")", $activation_url);
		}
		
		if (!isset($license_data->license)) { 
			// $license_data->license should be either "valid" or "invalid"
			return wtfdivi_error("Response is missing license status", $activation_url);
		}
		
		if ($license_data->license !== 'valid') {
			return wtfdivi_error("License key is not valid", $activation_url);
		}

		$option_updated = update_option('wtfdivi_license_status', $license_data->license);
	}
}
add_action('admin_init', 'wtfdivi_activate_license');


function wtfdivi_error($msg, $details="") { 
	update_option('wtfdivi_last_error', $msg);
	update_option('wtfdivi_last_error_details', $details);
	return false;
}

/***********************************************
* Illustrates how to deactivate a license key.
* This will decrease the site count
***********************************************/

function wtfdivi_deactivate_license() {

	// listen for our activate button to be clicked
	if( isset( $_POST['edd_license_deactivate'] ) ) {

		// run a quick security check 
	 	if( ! check_admin_referer( 'wtfdivi_nonce', 'wtfdivi_nonce' ) ) 	
			return; // get out if we didn't click the Activate button

		// retrieve the license from the database
		$license = trim( get_option( 'wtfdivi_license_key' ) );
			

		// data to send in our API request
		$api_params = array( 
			'edd_action'=> 'deactivate_license', 
			'license' 	=> urlencode($license), 
			'item_name' => urlencode(WTFDIVI_ITEM_NAME), // the name of our product in EDD
			'url'       => urlencode(home_url())
		);
		
		// Call the custom API.
		$response = wp_remote_get(esc_url_raw(add_query_arg( $api_params, WTFDIVI_STORE_URL )), array( 'timeout' => 15, 'sslverify' => false ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) )
			return false;

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
		
		// $license_data->license will be either "deactivated" or "failed"
		if( $license_data->license == 'deactivated' )
			delete_option( 'wtfdivi_license_status' );

	}
}
add_action('admin_init', 'wtfdivi_deactivate_license');


		
// === Load the plugin libs === //
include(dirname(__FILE__).'/core/wtfplugin_1_0.class.php');

$divi = wp_get_theme(get_template());

// print_r($divi->Version); exit; 
// $divi_version = (empty(wp_get_theme()->parent()->Version))?(wp_get_theme()->Version):(wp_get_theme()->parent()->Version);
$divi_2_4 = version_compare($divi->Version, '2.3.9', '>=');

$wtfdivi = new wtfplugin_1_0(
	array(
		'theme'=>array(
			'name'=>'Divi',
			'url'=>'http://www.elegantthemes.com/gallery/divi/',	
			'author'=>'Elegant Themes',
			'version'=>$divi->Version,
			'divi2.4+'=>$divi_2_4
		),
		'plugin'=>array(
			'name'=>'Divi Booster',
			'shortname'=>'Divi Booster', // menu name
			'slug'=>'wtfdivi',
			'package_slug'=>'divi-booster',
			'plugin_file'=>__FILE__,
			'url'=>'https://www.wpthemefaqs.com/themes/divi/',
			'basename'=>plugin_basename(__FILE__),
			'js-dependencies'=>array('jquery', 'divi-custom-script'), // ensure plugin js runs after theme js
			'admin_menu'=>($divi_2_4?'et_divi_options':'themes.php')
		),
		'sections'=>array(
			'general'=>'Site-wide Settings',
			'general-layout'=>'Layout',
			'general-speed'=>'Site Speed',
			'general-social'=>'Social Media',
			'header'=>'Header',
			'header-top'=>'Top Header',
			'header-main'=>'Main Header',
			'header-mobile'=>'Mobile Header',
			'posts'=>'Posts',
			'sidebar'=>'Sidebar',
			'footer'=>'Footer',
			'pagebuilder'=>'The Divi Builder',
			'modules'=>'Modules',
			'modules-accordion'=>'Accordion',
			'modules-blurb'=>'Blurb',
			'modules-gallery'=>'Gallery',
			'modules-map'=>'Map',
			'modules-portfolio'=>'Portfolio',
			'modules-portfoliofiltered'=>'Portfolio (Filterable)',
			/*'modules-portfoliofullwidth'=>'Portfolio (Full Width)',*/
			'modules-pricing'=>'Pricing Table',
			'modules-shop'=>'Shop',
			'modules-subscribe'=>'Signup',
			'modules-slider'=>'Slider',
			'modules-text'=>'Text',
			'plugins'=>'Fix Plugin Conflicts',
			'customcss'=>'CSS Manager',
			'developer'=>'Developer Tools',
			'developer-export'=>'Import / Export',
			'developer-css'=>'Generated CSS',
			'developer-js'=>'Generated JS',
			'developer-footer-html'=>'Generated Footer HTML',
			'developer-htaccess'=>'Generated .htaccess Rules',
			'deprecated'=>'Deprecated (now available in Divi)',
			'deprecated-divi24'=>'Divi 2.4',
			'deprecated-divi23'=>'Pre Divi 2.4'
		)
	)
);

// === Check for updates === //

if (get_option('wtfdivi_license_status')=='valid') {
	try {
		require dirname(__FILE__).'/core/updates/plugin-update-checker.php';
		$package_slug = 'divi-booster';
		$MyUpdateChecker = new Divi_Booster_PluginUpdateChecker('https://www.wpthemefaqs.com/plugins/?action=get_metadata&slug='.$package_slug, __FILE__, $package_slug);
	} catch (Exception $e) { echo "Update error: ".$e->getMessage(); exit; }
}

// === Add Sidebar Support Options === //
// function wtfdivi_add_sidebar() {}
// add_action($wtfdivi->slug.'-sidebar', 'wtfdivi_add_sidebar');

?>
