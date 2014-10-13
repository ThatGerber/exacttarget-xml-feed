<?php

if ( ! class_exists( 'XT_XML_Settings' ) ) {

class XT_XML_Settings {

	public $id;

	public $tag;

	public $image_size;

	public $feed_count;

	public $word_count;

	public $field;

	public $section;

	public function __construct( $array ) {

		$this->id  = $array['id'];
		$this->tag = $array['tag'];
		$this->image_size = $array['image_size'];
		$this->feed_count = $array['feed_count'];
		$this->word_count = $array['word_count'];
		$this->field      = $array['field'];
		$this->section    = $array['section'];

	}

}

}