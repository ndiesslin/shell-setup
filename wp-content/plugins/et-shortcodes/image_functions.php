<?php
if ( ! function_exists( 'et_new_thumb_resize' ) ){
	function et_new_thumb_resize( $thumbnail, $width, $height, $alt='', $forstyle = false ){
		global $shortname;

		$new_method = true;
		$new_method_thumb = '';
		$external_source = false;

		$allow_new_thumb_method = !$external_source && $new_method;

		if ( $allow_new_thumb_method && $thumbnail <> '' ){
			$et_crop = true;
			$new_method_thumb = et_resize_image( $thumbnail, $width, $height, $et_crop );
			if ( is_wp_error( $new_method_thumb ) ) $new_method_thumb = '';
		}

		$thumb = esc_attr( $new_method_thumb );

		$output = '<img src="' . esc_url( $thumb ) . '" alt="' . esc_attr( $alt ) . '" width =' . esc_attr( $width ) . ' height=' . esc_attr( $height ) . ' />';

		return ( !$forstyle ) ? $output : $thumb;
	}
}

if ( ! function_exists( 'et_resize_image' ) ){
	function et_resize_image( $thumb, $new_width, $new_height, $crop ){
		/*
		 * Fixes the issue with x symbol between width and height values in the filename.
		 * For instance, sports-400x400.jpg file results in 'image not found' in getimagesize() function.
		 */
		$thumb = str_replace( '%26%23215%3B', 'x', rawurlencode( $thumb ) );
		$thumb = rawurldecode( $thumb );

		if ( is_ssl() ) $thumb = preg_replace( '#^http://#', 'https://', $thumb );
		$info = pathinfo($thumb);
		$ext = $info['extension'];
		$name = wp_basename($thumb, ".$ext");
		$is_jpeg = false;
		$site_uri = apply_filters( 'et_resize_image_site_uri', site_url() );
		$site_dir = apply_filters( 'et_resize_image_site_dir', ABSPATH );

		#get main site url on multisite installation
		if ( is_multisite() ){
			switch_to_blog(1);
			$site_uri = site_url();
			restore_current_blog();
		}

		if ( 'jpeg' == $ext ) {
			$ext = 'jpg';
			$name = preg_replace( '#.jpeg$#', '', $name );
			$is_jpeg = true;
		}

		$suffix = "{$new_width}x{$new_height}";

		$destination_dir = '' != get_option( 'et_images_temp_folder' ) ? preg_replace( '#\/\/#', '/', get_option( 'et_images_temp_folder' ) ) : null;

		$matches = apply_filters( 'et_resize_image_site_dir', array(), $site_dir );
		if ( !empty($matches) ){
			preg_match( '#'.$matches[1].'$#', $site_uri, $site_uri_matches );
			if ( !empty($site_uri_matches) ){
				$site_uri = str_replace( $matches[1], '', $site_uri );
				$site_uri = preg_replace( '#/$#', '', $site_uri );
				$site_dir = str_replace( $matches[1], '', $site_dir );
				$site_dir = preg_replace( '#\\\/$#', '', $site_dir );
			}
		}

		#get local name for use in file_exists() and get_imagesize() functions
		$localfile = str_replace( apply_filters( 'et_resize_image_localfile', $site_uri, $site_dir, et_multisite_thumbnail($thumb) ), $site_dir, et_multisite_thumbnail($thumb) );

		$add_to_suffix = '';
		if ( file_exists( $localfile ) ) $add_to_suffix = filesize( $localfile ) . '_';

		#prepend image filesize to be able to use images with the same filename
		$suffix = $add_to_suffix . $suffix;
		$destfilename_attributes = '-' . $suffix . '.' . $ext;

		$checkfilename = ( '' != $destination_dir && null !== $destination_dir ) ? path_join( $destination_dir, $name ) : path_join( dirname( $localfile ), $name );
		$checkfilename .= $destfilename_attributes;

		if ( $is_jpeg ) $checkfilename = preg_replace( '#.jpeg$#', '.jpg', $checkfilename );

		$uploads_dir = wp_upload_dir();
		$uploads_dir['basedir'] = preg_replace( '#\/\/#', '/', $uploads_dir['basedir'] );

		if ( null !== $destination_dir && '' != $destination_dir && apply_filters('et_enable_uploads_detection', true) ){
			$site_dir = trailingslashit( preg_replace( '#\/\/#', '/', $uploads_dir['basedir'] ) );
			$site_uri = trailingslashit( $uploads_dir['baseurl'] );
		}

		#check if we have an image with specified width and height

		if ( file_exists( $checkfilename ) ) return str_replace( $site_dir, trailingslashit( $site_uri ), $checkfilename );

		$size = @getimagesize( $localfile );
		if ( !$size ) return new WP_Error('invalid_image_path', __('Image doesn\'t exist'), $thumb);
		list($orig_width, $orig_height, $orig_type) = $size;

		#check if we're resizing the image to smaller dimensions
		if ( $orig_width > $new_width || $orig_height > $new_height ){
			if ( $orig_width < $new_width || $orig_height < $new_height ){
				#don't resize image if new dimensions > than its original ones
				if ( $orig_width < $new_width ) $new_width = $orig_width;
				if ( $orig_height < $new_height ) $new_height = $orig_height;

				#regenerate suffix and appended attributes in case we changed new width or new height dimensions
				$suffix = "{$add_to_suffix}{$new_width}x{$new_height}";
				$destfilename_attributes = '-' . $suffix . '.' . $ext;

				$checkfilename = ( '' != $destination_dir && null !== $destination_dir ) ? path_join( $destination_dir, $name ) : path_join( dirname( $localfile ), $name );
				$checkfilename .= $destfilename_attributes;

				#check if we have an image with new calculated width and height parameters
				if ( file_exists($checkfilename) ) return str_replace( $site_dir, trailingslashit( $site_uri ), $checkfilename );
			}

			#we didn't find the image in cache, resizing is done here
			if ( ! function_exists( 'wp_get_image_editor' ) ) {
				// compatibility with versions of WordPress prior to 3.5.
				$result = image_resize( $localfile, $new_width, $new_height, $crop, $suffix, $destination_dir );
			} else {
				$et_image_editor = wp_get_image_editor( $localfile );

				if ( ! is_wp_error( $et_image_editor ) ) {
					$et_image_editor->resize( $new_width, $new_height, $crop );

					// generate correct file name/path
					$et_new_image_name = $et_image_editor->generate_filename( $suffix, $destination_dir );

					do_action( 'et_resize_image_before_save', $et_image_editor, $et_new_image_name );

					$et_image_editor->save( $et_new_image_name );

					// assign new image path
					$result = $et_new_image_name;
				} else {
					// assign a WP_ERROR ( WP_Image_Editor instance wasn't created properly )
					$result = $et_image_editor;
				}
			}

			if ( ! is_wp_error( $result ) ) {
				#transform local image path into URI

				if ( $is_jpeg ) $thumb = preg_replace( '#.jpeg$#', '.jpg', $thumb);

				$site_dir = str_replace( '\\', '/', $site_dir );
				$result = str_replace( '\\', '/', $result );
				$result = str_replace( '//', '/', $result );
				$result = str_replace( $site_dir, trailingslashit( $site_uri ), $result );
			}

			#returns resized image path or WP_Error ( if something went wrong during resizing )
			return $result;
		}

		#returns unmodified image, for example in case if the user is trying to resize 800x600px to 1920x1080px image
		return $thumb;
	}
}

if ( ! function_exists( 'et_create_images_temp_folder' ) ){
	add_action( 'init', 'et_create_images_temp_folder' );
	function et_create_images_temp_folder(){
		#clean et_temp folder once per week
		if ( false !== $last_time = get_option( 'et_schedule_clean_images_last_time'  ) ){
			$timeout = 86400 * 7;
			if ( ( $timeout < ( time() - $last_time ) ) && '' != get_option( 'et_images_temp_folder' ) ) et_clean_temp_images( get_option( 'et_images_temp_folder' ) );
		}

		if ( false !== get_option( 'et_images_temp_folder' ) ) return;

		$uploads_dir = wp_upload_dir();
		$destination_dir = ( false === $uploads_dir['error'] ) ? path_join( $uploads_dir['basedir'], 'et_temp' ) : null;

		if ( ! wp_mkdir_p( $destination_dir ) ) update_option( 'et_images_temp_folder', '' );
		else {
			update_option( 'et_images_temp_folder', preg_replace( '#\/\/#', '/', $destination_dir ) );
			update_option( 'et_schedule_clean_images_last_time', time() );
		}
	}
}

if ( ! function_exists( 'et_clean_temp_images' ) ){
	function et_clean_temp_images( $directory ){
		$dir_to_clean = @ opendir( $directory );

		if ( $dir_to_clean ) {
			while (($file = readdir( $dir_to_clean ) ) !== false ) {
				if ( substr($file, 0, 1) == '.' )
					continue;
				if ( is_dir( $directory.'/'.$file ) )
					et_clean_temp_images( path_join( $directory, $file ) );
				else
					@ unlink( path_join( $directory, $file ) );
			}
			closedir( $dir_to_clean );
		}

		#set last time cleaning was performed
		update_option( 'et_schedule_clean_images_last_time', time() );
	}
}

if ( ! function_exists( 'et_multisite_thumbnail' ) ){
	function et_multisite_thumbnail( $thumbnail = '' ) {
		// do nothing if it's not a Multisite installation or current site is the main one
		if ( is_main_site() ) return $thumbnail;

		# get the real image url
		preg_match( '#([_0-9a-zA-Z-]+/)?files/(.+)#', $thumbnail, $matches );
		if ( isset( $matches[2] ) ){
			$file = rtrim( BLOGUPLOADDIR, '/' ) . '/' . str_replace( '..', '', $matches[2] );
			if ( is_file( $file ) ) $thumbnail = str_replace( ABSPATH, get_site_url( 1 ), $file );
			else $thumbnail = '';
		}

		return $thumbnail;
	}
}

if ( ! function_exists( 'et_update_uploads_dir' ) ){
	add_filter( 'update_option_upload_path', 'et_update_uploads_dir' );
	function et_update_uploads_dir( $upload_path ){
		$uploads_dir = wp_upload_dir();
		$destination_dir = ( false === $uploads_dir['error'] ) ? path_join( $uploads_dir['basedir'], 'et_temp' ) : null;

		update_option( 'et_images_temp_folder', preg_replace( '#\/\/#', '/', $destination_dir ) );

		return $upload_path;
	}
}