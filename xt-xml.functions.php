<?php
/**
 * Helper Functions
 */


/**
 * @param $name
 * @param $value
 *
 * @return bool
 */
function xt_update_option($name, $value) {

	if ( get_option( $name ) !== false ) {

		update_option( $name, $value );

		return $value;
	} else {

		add_option( $name, $value );

		return $value;
	}
}

function xt_field_name_slugify( $name ) {

	return wp_kses( sanitize_title( $name ), array() );
}

/**
 * @param $fields
 * @param $name
 *
 * @return XT_XML_Tag
 */
function xt_get_field( $fields = null, $name ) {

	if ( $fields == null ) {
		$fields = get_option(XT_XML_Admin::OPTIONS_STR);
	}

	if ($fields !== null) {
		foreach ( $fields as $field ) {
			if ( $field->id == $name ) {
				$retval = $field;
			}
		}
	}

	return ( isset( $retval ) ? $retval : null ) ;
}

function xt_get_the_category() {

	$cat = get_category( get_query_var( 'cat' ) );

	return ( ! is_wp_error( $cat ) ? $cat : null);
}

function get_xt_get_template_part( $part, $ext = 'php' ) {

	return (
	file_exists( dirname(__FILE__) . '/templates/' . $part . '.' . $ext ) ?
		dirname(__FILE__) . '/templates/' . $part . '.' . $ext :
		false
	);
}

function xt_get_template_part( $part, $ext = 'php' ) {

	if ( $filename = get_xt_get_template_part( $part, $ext ) ) {
		include ( $filename );
	} else {

		return;
	}
}

function xt_get_word_count() {
	$field = xt_get_field( get_option(XT_XML_Admin::OPTIONS_STR), XT_XML_Feed::$cat->slug );
	if ( is_a($field, 'XT_XML_Tag') ) {

		return $field->word_count;
	} else {

		 return 50;
	}
}

function get_image_sizes( $size = '' ) {

	global $_wp_additional_image_sizes;

	$sizes = array();
	$get_intermediate_image_sizes = get_intermediate_image_sizes();

	// Create the full array with sizes and crop info
	foreach( $get_intermediate_image_sizes as $_size ) {

		if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {

			$sizes[ $_size ]['width'] = get_option( $_size . '_size_w' );
			$sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
			$sizes[ $_size ]['crop'] = (bool) get_option( $_size . '_crop' );

		} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {

			$sizes[ $_size ] = array(
				'width' => $_wp_additional_image_sizes[ $_size ]['width'],
				'height' => $_wp_additional_image_sizes[ $_size ]['height'],
				'crop' =>  $_wp_additional_image_sizes[ $_size ]['crop']
			);

		}

	}

	// Get only 1 size if found
	if ( $size ) {

		if( isset( $sizes[ $size ] ) ) {
			return $sizes[ $size ];
		} else {
			return false;
		}

	}

	return $sizes;
}