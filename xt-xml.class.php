<?php
/**
 * Class XT_XML
 */

if ( ! class_exists( 'XT_XML' ) ) {

class XT_XML {

	public $tags;

	/**
	 * Things to do on construct
	 *
	 */
	public function __construct() {

		/** Helper files */
		$this->file_includes();

		$this->tags = get_option( XT_XML_Admin::OPTIONS_STR );

		// Adds XML feed
		add_action( 'do_feed_xtxml', array('XT_XML_Feed', 'instance') );

		// Adds image sizes
		add_action( 'after_setup_theme', array( $this, 'add_image_sizes') );
	}

	private function file_includes() {
		// Helper Functions
		include('xt-xml.functions.php');
		// Admin (stuff
		include('admin/xt-xml-admin.class.php');
		// Tags
		include('admin/xt-xml-tag.class.php');
		// Feed Class
		include('xt-xml-feed.class.php');
	}

	/**
	 * Adds the image sizes to be used with the plugin.
	 *
	 */
	public function add_image_sizes() {

		if (
			is_array( $this->tags ) &&
			count( $this->tags ) >= 0
		) {
			foreach ( $this->tags as $field ) {
				add_image_size(
					$field->id . '-thumb',
					$field->image_size[0],
					$field->image_size[1],
					true
				);
			}
		}

		// Will be deprecated.
		add_image_size( 'featured-email-thumb', 200, 133, true ); //300 pixels wide (and unlimited height)
		add_image_size( 'email-thumb', 125, 90, true ); //(cropped)
	}

}

}