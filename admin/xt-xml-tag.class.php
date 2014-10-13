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
		$this->id         = strtolower( str_replace(' ', '_', $name) );
		$this->image_size = array(125,125);
		$this->feed_count = 10;
		$this->word_count = 200;
		$this->field      = 'text';
		$this->section    = 'basic_settings';
	}


}