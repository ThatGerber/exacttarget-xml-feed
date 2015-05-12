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
// Main Class
include 'xt-xml.class.php';
// Helper Functions
include 'xt-xml.functions.php';
// Admin (stuff
include 'admin/xt-xml-admin.class.php';
// Tags
include 'admin/xt-xml-tag.class.php';
// Feed Class
include 'xt-xml-feed.class.php';
/* Fires up the Factory */
$xt_xml = new XT_XML( 'exact_target_xml' );
/* Register the Email Tag Taxonomy */
add_action( 'init', array( $xt_xml, 'register_taxonomy' ), 0 );
/* Adds image sizes */
add_action( 'after_setup_theme', array( $xt_xml, 'add_image_sizes') );
/* Adds XML feed */
add_action( 'do_feed_xtxml', array('XT_XML_Feed', 'instance') );

/* Admin */
if ( is_admin() ) {
	include 'admin/xt-xml-admin.class.php';
	include 'admin/xt-xml-settings.class.php';
	include 'admin/xt-xml-admin-form.class.php';
	$xt_xml_admin = new XT_XML_Admin;
	$xt_xml_admin->page_title  = 'Exact Target XML Pages';
	$xt_xml_admin->menu_title  = 'Exact Target XML';
	$xt_xml_admin->user_cap    = 'manage_options';
	$xt_xml_admin->plugin_slug = 'xt_xml';
	$xt_xml_admin->options_str = 'exact_target_xml';
	$xt_xml_admin->options_grp = 'exact_target_xml-group';
	$xt_xml_admin->fields_str  = 'exact_target_xml_fields';
	add_action( 'admin_menu', array( $xt_xml_admin, 'register_menu_page' ) );
	add_action( 'admin_init', array( $xt_xml_admin, 'menu_page_init' ) );


}
