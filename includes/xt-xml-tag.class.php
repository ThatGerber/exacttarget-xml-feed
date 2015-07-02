<?php

class XT_XML_Tag {

	public $id;

	public $tag;

	public $image_size;

	public $feed_count;

	public $word_count;

	public $field;

	public $section;

	public function __construct( $name ) {
		$this->tag        = $name;
		$this->id         = xt_field_name_slugify( $name );
		$this->image_size = array(125,125);
		$this->feed_count = 10;
		$this->word_count = 200;
		$this->field      = 'text';
		$this->section    = 'basic_settings';
	}

	/**
	 * Sets Image Size for tag
	 *
	 * @param array $size Array of width and height
	 *
	 * @return mixed
	 */
	public function update_image_size( $size ) {

		return $this->image_size = explode( 'x', strtolower($size) );
	}

	/**
	 * Updates feed count
	 *
	 * @param int $count number of articles to display
	 *
	 * @return int
	 */
	public function update_feed_count( $count ) {

		return $this->feed_count = intval($count);
	}

	/**
	 * @param int $count number of words to display
	 *
	 * @return int
	 */
	public function update_word_count( $count ) {

		return $this->word_count = intval($count);
	}


}