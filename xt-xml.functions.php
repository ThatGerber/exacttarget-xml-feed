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

		return update_option( $name, $value );
	} else {

		return add_option( $name, $value );
	}
}