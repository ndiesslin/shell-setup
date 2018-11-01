<?php

/**
 * Represent a simple value or a dynamic one.
 * Used for module attributes and content.
 *
 * @since ??
 */
class ET_Builder_Value {
	/**
	 * Flag whether the value is static or dynamic.
	 *
	 * @since ??
	 *
	 * @var bool
	 */
	protected $dynamic = false;

	/**
	 * Value content. Represents the dynamic content type when dynamic.
	 *
	 * @since ??
	 *
	 * @var string
	 */
	protected $content = '';

	/**
	 * Array of dynamic content settings.
	 *
	 * @since ??
	 *
	 * @var array<string, mixed>
	 */
	protected $settings = array();

	/**
	 * ET_Builder_Value constructor.
	 *
	 * @since ??
	 *
	 * @param boolean $dynamic
	 * @param string $content
	 * @param array $settings
	 */
	public function __construct( $dynamic, $content, $settings = array() ) {
		$this->dynamic = $dynamic;
		$this->content = $content;
		$this->settings = $settings;
	}

	/**
	 * Check if the value is dynamic or not.
	 *
	 * @since ??
	 *
	 * @return bool
	 */
	public function is_dynamic() {
		return $this->dynamic;
	}

	/**
	 * Get the resolved content.
	 *
	 * @since ??
	 *
	 * @param integer $post_id
	 *
	 * @return string
	 */
	public function resolve( $post_id ) {
		if ( ! $this->dynamic ) {
			return $this->content;
		}

		return et_builder_resolve_dynamic_content( $this->content, $this->settings, $post_id, 'display' );
	}

	/**
	 * Get the static content or a serialized representation of the dynamic one.
	 *
	 * @since ??
	 *
	 * @return string
	 */
	public function serialize() {
		if ( ! $this->dynamic ) {
			return $this->content;
		}

		// JSON_UNESCAPED_SLASHES is only supported from 5.4.
		$options = defined( 'JSON_UNESCAPED_SLASHES' ) ? JSON_UNESCAPED_SLASHES : 0;
		$result  = wp_json_encode( array(
			'dynamic' => $this->dynamic,
			'content' => $this->content,
			// Force object type for keyed arrays as empty arrays will be encoded to
			// javascript arrays instead of empty objects.
			'settings' => (object) $this->settings,
		), $options );

		// Use fallback if needed
		return 0 === $options ? str_replace( '\/', '/', $result ) : $result;
	}
}
