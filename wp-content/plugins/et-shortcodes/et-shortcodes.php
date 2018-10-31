<?php
/*
 * Plugin Name: ET Shortcodes
 * Plugin URI: http://elegantthemes.com
 * Description: ET Shortcodes plugin.
 * Version: 1.2.1
 * Author: Elegant Themes
 * Author URI: http://elegantthemes.com
 * License: GPLv2 or later
 */

define( 'ET_SHORTCODES_PLUGIN_VERSION', '1.2.1' );
define( 'ET_SHORTCODES_PLUGIN_DIR', trailingslashit( dirname(__FILE__) ) );
define( 'ET_SHORTCODES_PLUGIN_URI', plugins_url('', __FILE__) );

function et_shortcodes_plugin_init() {
	require_once( ET_SHORTCODES_PLUGIN_DIR . 'core/updates_init.php' );
	et_core_enable_automatic_updates( ET_SHORTCODES_PLUGIN_URI, ET_SHORTCODES_PLUGIN_VERSION );
}
add_action( 'plugins_loaded', 'et_shortcodes_plugin_init' );

add_action( 'init', 'et_shortcodes_main_load', 12 );
function et_shortcodes_main_load(){
	if ( function_exists( 'et_setup_theme' ) ) return;

	require_once( ET_SHORTCODES_PLUGIN_DIR . '/shortcodes.php' );
	require_once( ET_SHORTCODES_PLUGIN_DIR . '/image_functions.php' );
}

add_action( 'admin_menu', 'et_shortcodes_options_add_page' );
function et_shortcodes_options_add_page() {
	$plugin_settings_page = add_management_page( __( 'ET Shortcodes' ), __( 'ET Shortcodes' ), 'manage_options', 'et_shortcodes_plugin_options', 'et_shortcodes_options_render_page' );
}

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'et_shortcodes_plugin_add_settings_link', 10, 2 );
function et_shortcodes_plugin_add_settings_link( $links ){
	$settings = '<a href="' . esc_url( admin_url( 'tools.php?page=et_shortcodes_plugin_options' ) ) . '">' . __( 'Settings' ) . '</a>';
	array_push( $links, $settings );
	return $links;
}

add_action( 'admin_init', 'et_shortcodes_plugin_settings_init' );
function et_shortcodes_plugin_settings_init() {
	register_setting( 'et_shortcodes_options', 'et_shortcodes_plugin_settings', 'et_shortcodes_plugin_settings_validate' );

	add_settings_section( 'general', '', '__return_false', 'et_shortcodes_plugin_options' );

	add_settings_field( 'responsive_layout', __( 'Responsive layout' ), 'et_shortcodes_field_responsive_layout_html', 'et_shortcodes_plugin_options', 'general' );

	add_settings_field( 'updates_username', esc_html__( 'Username' ), 'et_updates_field_username_html', 'et_shortcodes_plugin_options', 'general' );

	add_settings_field( 'updates_password', esc_html__( 'API Key' ), 'et_updates_field_password_html', 'et_shortcodes_plugin_options', 'general' );
}

function et_shortcodes_options_render_page() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php esc_html_e( 'ET Shortcodes Plugin Options' ); ?></h2>
		<?php settings_errors(); ?>

		<form method="post" action="options.php">
			<?php
				settings_fields( 'et_shortcodes_options' );
				do_settings_sections( 'et_shortcodes_plugin_options' );
				submit_button();
			?>
		</form>
	</div>
	<?php
}

function et_shortcodes_get_plugin_options() {
	$saved_options = (array) get_option( 'et_shortcodes_plugin_settings' );

	$et_updates_settings = (array) get_option( 'et_automatic_updates_options' );

	$default_settings = array(
		'responsive_layout' => 'on',
		'username'          => '',
		'api_key'           => '',
	);

	if ( ! empty( $et_updates_settings ) ) {
		foreach ( $et_updates_settings as $key => $value ) {
			$saved_options[ $key ] = $value;
		}
	}

	$default_settings = apply_filters( 'et_shortcodes_default_settings', $default_settings );

	$options = wp_parse_args( $saved_options, $default_settings );
	$options = array_intersect_key( $options, $default_settings );

	return $options;
}

/**
 * Save Username and API Key settings into et_automatic_updates_options,
 * when the values are being updated on the plugin settings page
 *
 * @param  mixed  $new_value  Saved value
 * @return void
 */
function et_shortcodes_save_global_updates_settings( $new_value ) {
	$result = array();

	$updates_fields = array(
		'username',
		'api_key',
	);

	foreach ( $updates_fields as $field ) {
		if ( isset( $new_value[ $field ] ) ) {
			$result[ $field ] = $new_value[ $field ];
		}
	}

	update_option( 'et_automatic_updates_options', $result );
}
add_filter( 'pre_update_option_et_shortcodes_plugin_settings', 'et_shortcodes_save_global_updates_settings' );

function et_shortcodes_plugin_settings_validate( $input ) {
	$output = array();

	$output['responsive_layout'] = isset( $input['responsive_layout'] ) ? 'on' : 'off';

	$output['username'] = isset( $input['username'] ) ? sanitize_text_field( $input['username'] ) : '';
	$output['api_key']  = isset( $input['api_key'] ) ? sanitize_text_field( $input['api_key'] ) : '';

	return apply_filters( 'et_shortcodes_plugin_settings_validate', $output, $input );
}

function et_shortcodes_field_responsive_layout_html() {
	$options = et_shortcodes_get_plugin_options();
	?>
	<label for="responsive-layout">
		<input type="checkbox" name="et_shortcodes_plugin_settings[responsive_layout]" id="responsive-layout" <?php checked( 'on', $options['responsive_layout'] ); ?> />
		<?php _e( 'Allow shortcodes to adapt to various screen sizes' ); ?>
	</label>
	<?php
}

function et_updates_field_username_html() {
	$options = et_shortcodes_get_plugin_options();

	$url = sprintf( '<a href="%1$s" target="_blank">%2$s</a>',
		esc_url( 'https://www.elegantthemes.com/members-area/api-key.php' ),
		esc_html__( 'Elegant Themes API Key', 'bloom' )
	); ?>
	<label for="updates-username">
		<input type="password" name="et_shortcodes_plugin_settings[username]" id="updates-username" value="<?php echo esc_attr( $options['username'] ); ?>" />
		<p class="description"><?php printf( esc_html__( 'Enter your %1$s here.' ), $url ); ?></p>
	</label>
	<?php
}

function et_updates_field_password_html() {
	$options = et_shortcodes_get_plugin_options();

	$url = sprintf( '<a href="%1$s" target="_blank">%2$s</a>',
		esc_url( 'https://www.elegantthemes.com/members-area/documentation.html#update' ),
		esc_html__( 'enable updates' )
	);
	?>
	<label for="updates-password">
		<input type="password" name="et_shortcodes_plugin_settings[api_key]" id="updates-password" value="<?php echo esc_attr( $options['api_key'] ); ?>" />
		<p class="description">
			<?php
			printf(
				esc_html__( 'Keeping your plugins updated is important. To %1$s for Bloom, you must first authenticate your Elegant Themes account by inputting your account Username and API Key below. Your username is the same username you use when logging into your Elegant Themes account, and your API Key can be found by logging into your account and navigating to the Account > API Key page.' ),
				$url
			); ?>
		</p>
	</label>
	<?php
}
