<?php
/*
Plugin Name:       Exact Target XML Feed
Description:       Custom XML Feed for Exact Target.
Author:            Christopher Gerber
Version:           0.1.0
Plugin URI:        http://www.github.com/ThatGerber/exacttarget-xml-feed
Github Plugin URI: ThatGerber/exacttarget-xml-feed
GitHub Branch:     master
Author URI:        http://www.chriswgerber.com/
License:           GPL2
*/

/*  Copyright 2014 Christopher Gerber (email : chriswgerber@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* wordpress do all the work because of the "do_feed_" prefix */

if ( function_exists( 'add_image_size' ) ) {
	add_image_size( 'featured-email-thumb', 200, 133, true ); //300 pixels wide (and unlimited height)
	add_image_size( 'email-thumb', 125, 90, true ); //(cropped)
}

add_action( 'do_feed_xtxml', 'do_feed_xtxml', 10, 1 );
function do_feed_xtxml() {
	load_template( dirname(__FILE__) . '/feed-template.php' );
}

if ( is_admin() ) {

	include( 'admin/admin.php');
	new XT_XML_Admin;

}