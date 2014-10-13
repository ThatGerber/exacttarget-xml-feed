<?php
/**
 * Class XT_XML
 */

if ( ! class_exists( 'XT_XML' ) ) {

class XT_XML {

	/**
	 * Things to do on construct
	 *
	 */
	public function __construct() {

		/** Helper files */
		$this->file_includes();

		// Adds XML feed
		add_action( 'do_feed_xtxml', array($this, 'do_feed_xtxml'), 10, 1 );

		// Adds image sizes
		add_action( 'after_setup_theme', array( $this, 'add_image_sizes') );
	}

	private function file_includes() {
		// Helper Functions
		include('xt-xml.functions.php');

		// Feed Class
		include('xt-xml-feed.class.php');
	}

	/**
	 * Adds the image sizes to be used with the plugin.
	 *
	 */
	public function add_image_sizes() {
		add_image_size( 'featured-email-thumb', 200, 133, true ); //300 pixels wide (and unlimited height)
		add_image_size( 'email-thumb', 125, 90, true ); //(cropped)
	}

	/**
	 * Loads the template File
	 *
	 */
	public function do_feed_xtxml() {
		load_template( dirname(__FILE__) . '/templates/feed-template.php' );
	}

	/**
	 * Echos out the link to the feed image
	 *
	 */
	static function feed_image( $post ) {

		echo XT_XML::get_feed_image( $post );
	}

	/**
	 * Gets the feed image and returns it as a variable.
	 *
	 * @param $post
	 *
	 * @return mixed
	 */
	static function get_feed_image( $post ) {
		if (
			array_search( 'Featured Articles', wp_get_post_tags($post->ID, array( 'fields' => 'names' )) ) !== false
		) {
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'featured-email-thumb' );
		} else {
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'email-thumb' );
		}

		return $image[0];
	}

	/**
	 * Echos out the description for the feed.
	 *
	 * @param     $str
	 * @param int $limit
	 */
	static function the_description( $str, $limit = 30 ) {

		echo XT_XML::get_the_description( $str, $limit );
	}

	/**
	 * Returns the description as a variable.
	 *
	 * @param     $str
	 * @param int $limit
	 *
	 * @return string
	 */
	static function get_the_description( $str, $limit = 30 ) {

		return trim( implode( ' ', array_slice( explode( ' ', strip_tags( $str ) ), 0, $limit ) ) );
	}


}

}