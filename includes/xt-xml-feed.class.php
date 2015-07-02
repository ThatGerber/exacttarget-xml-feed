<?php
/**
 * Class for creating feed objects.
 */

class XT_XML_Feed {

	public $xml;

	public $field;

	public $cat;

	public $options_str;

	public $desc_meta_key;

	/**
	 * PHP5 Constructor
	 *
	 * @param XT_XML $xml
	 * @param string $options_str
	 */
	public function __construct( XT_XML $xml, $options_str ) {
		$this->xml = $xml;
		$this->options_str = $options_str;
		// Let's get rid of stupid smart quotes
		remove_filter( 'the_content', 'wptexturize' );
		remove_filter( 'the_excerpt', 'wptexturize' );
		remove_filter( 'comment_text', 'wptexturize' );
		remove_filter( 'the_title_rss', 'wptexturize' );
	}

	/**
	 * Instantiates the feed.
	 */
	public function get_feed() {
		global $xt_dirname;

		// Declare an XML feed.
		include( $xt_dirname .'/templates/feed-header.php' );
		if ( ( $query = $this->posts() ) && $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				include( $xt_dirname . '/templates/item-template.php' );
			}
			wp_reset_postdata();
		}
		include( $xt_dirname . '/templates/feed-footer.php' );
	}



	/**
	 * Echos out the link to the feed image
	 *
	 */
	public function feed_image( $size = 'thumbnail' ) {
		if ( has_post_thumbnail() ) {
			echo $this->get_feed_image( $size );
		}
	}

	/**
	 * Gets the feed image and returns it as a variable.
	 *
	 * @param $size
	 *
	 * @return mixed
	 */
	public function get_feed_image( $size = 'thumbnail' ) {
		global $post;
        $size = 'thumbnail';

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
	public function the_description( ) {
		global $post;
		$desc = get_post_meta( $post->ID, $this->desc_meta_key, true );
		if ( is_string( $desc ) && strlen($desc) > 0 ) {
			echo $this->limit_text( __( $desc ) );
		} else {
			echo $this->limit_text( get_the_excerpt() );
		}
	}

	/**
	 * Gets custom posts call
	 *
	 * @return WP_Query
	 */
	protected function posts() {
		$term_settings = $this->get_term_settings();
		$args = array(
			'pagination'     => false,
			'cache_results'  => false,
			'post_status'    => 'publish',
			'posts_per_page' => $term_settings[ 'post_count' ],
			'orderby'        => 'date',
			'tax_query'      => array(
				array(
					'taxonomy' => $this->xml->taxonomy_slug,
					'field'    => 'slug',
					'terms'    => get_query_var( 'pagename' ),
				),
			),
		);

		return new WP_Query( $args );
	}

	protected function limit_text( $text ) {
		$limit = $this->get_word_limit();
		if ( str_word_count( $text ) > $limit ) {
			$text = implode(" ", array_slice( explode(" ", $text), 0, $limit) );
			if ( substr( $text, -1 ) == '.' ) {

				return $text . '..';
			} elseif ( substr( $text, -1 ) == '?') {

				return $text;
			} else {

				return $text . '...';
			}
		}
	}

	protected function get_word_limit() {
		$term_settings = $this->get_term_settings();
		if ( isset( $term_settings[ 'word_count' ] ) ) {

			return $term_settings[ 'word_count' ];
		} else {

			return 25;
		}
	}

	protected function get_term_settings() {
		$values = $this->xml->get_options();
		$term = get_term_by( 'slug', get_query_var( 'pagename' ), $this->xml->taxonomy_slug );
		if ( array_key_exists( $term->term_taxonomy_id, $values ) ) {

			return $values[ $term->term_taxonomy_id ];
		}

		return null;
	}

}