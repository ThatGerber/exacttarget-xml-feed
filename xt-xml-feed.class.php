<?php
/**
 * Class for creating feed objects.
 */

if ( !class_exists( 'XT_XML_Feed' ) ) {

class XT_XML_Feed {

	public $field;

	public static $cat;

	protected static $instance;

	static function instance() {
		if (!isset(static::$instance)) {
			static::$instance = new static;
		}

		return static::$instance;
	}

	protected function __construct( ) {

		add_filter( 'excerpt_length', array($this, 'excerpt_length'), 999 );
		// Let's get rid of stupid smart quotes
		remove_filter( 'the_title_rss', 'wptexturize' );
		remove_filter( 'the_content', 'wptexturize' );
		remove_filter( 'comment_text', 'wptexturize' );
		remove_filter( 'the_excerpt', 'wptexturize' );

		static::$cat = xt_get_the_category();

		if ( static::$cat ) {
			$this->field = xt_get_field( get_option(XT_XML_Admin::OPTIONS_STR), static::$cat->slug );
		}

		$this->get_feed();
	}

	/**
	 * Instantiates the feed.
	 */
	public function get_feed() {
		$count = 0;

		$limit = ( is_a( $this->field, 'XT_XML_Tag' ) ? $this->field->feed_count : 10 );

		header('Content-Type: application/xml; charset=UTF-8', true);

		xt_get_template_part('feed-header');

		while( have_posts() ) {
			the_post();
			$count ++;

			xt_get_template_part( 'item-template' );

			if ( $count >= $limit ) {
				break;
			}

		}

		xt_get_template_part('feed-footer', 'xml');
	}



	/**
	 * Echos out the link to the feed image
	 *
	 */
	static function feed_image( $post, $size = 'thumbnail' ) {

		echo self::get_feed_image( $post, $size );
	}

	/**
	 * Gets the feed image and returns it as a variable.
	 *
	 * @param $post
	 *
	 * @return mixed
	 */
	static function get_feed_image( $post, $size = 'thumbnail' ) {

		if ( xt_get_field( get_option(XT_XML_Admin::OPTIONS_STR), $size ) == null ) {
			$size = 'thumbnail';
		}

		if ( $size !== 'thumbnail' ) {
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $size . '-thumb' );
		} else {
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $size );
		}

		return $image[0];
	}

	/**
	 * Echos out the description for the feed.
	 *
	 * @param     $str
	 * @param int $limit
	 */
	static function the_description( ) {

		the_excerpt();
	}

	/**
	 * Edits the length of the excerpt.
	 *
	 * @param $length
	 *
	 * @return int
	 */
	public function excerpt_length( $length ) {

		return xt_get_word_count();
	}

}

}