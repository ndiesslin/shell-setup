<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 *
 */

function optionsframework_option_name() {

  // This gets the theme name from the stylesheet (lowercase and without spaces)
  $themename = get_option( 'stylesheet' );
  $themename = preg_replace("/\W/", "_", strtolower($themename) );

  $optionsframework_settings = get_option('optionsframework');
  $optionsframework_settings['id'] = $themename;
  update_option('optionsframework', $optionsframework_settings);

  // echo $themename;
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *
 */

function optionsframework_options() {

  $options = array();

  /**
   * For $settings options see:
   * http://codex.wordpress.org/Function_Reference/wp_editor
   *
   * 'media_buttons' are not supported as there is no post to attach items to
   * 'textarea_name' is set by the 'id' you choose
   */

  /*$wp_editor_settings = array(
    'wpautop' => true, // Default
    'textarea_rows' => 5,
    'tinymce' => array( 'plugins' => 'wordpress' )
  );

  $options[] = array(
    'name' => __('Default Text Editor', 'options_check'),
    'desc' => sprintf( __( 'You can also pass settings to the editor.  Read more about wp_editor in <a href="%1$s" target="_blank">the WordPress codex</a>', 'options_check' ), 'http://codex.wordpress.org/Function_Reference/wp_editor' ),
    'id' => 'example_editor',
    'type' => 'editor',
    'settings' => $wp_editor_settings );

  $wp_editor_settings = array(
    'wpautop' => true, // Default
    'textarea_rows' => 5,
    'media_buttons' => true
  );

  $options[] = array(
    'name' => __('Additional Text Editor', 'options_check'),
    'desc' => sprintf( __( 'This editor includes media button.', 'options_check' ), 'http://codex.wordpress.org/Function_Reference/wp_editor' ),
    'id' => 'example_editor_media',
    'type' => 'editor',
    'settings' => $wp_editor_settings );*/

  //Default Image
  $options[] = array(
    'name' => __('Default Image', 'options_check'),
    'type' => 'heading' );

  //image 1
  $options[] = array(
    'name' => __('Default Image', 'options_check'),
    //'desc' => __('Upload image of 460px X 266px', 'options_check'),
    'id' => 'default-image',
    'type' => 'upload');

  //image 2
  $options[] = array(
    'name' => __('Lanscape Image Size', 'options_check'),
    //'desc' => __('Upload image of 460px X 266px', 'options_check'),
    'id' => 'Landscape-default-image',
    'type' => 'upload');

  return $options;
}