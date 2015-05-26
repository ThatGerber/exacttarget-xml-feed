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
include 'src/xt-xml.functions.php';
include 'src/xt-xml.class.php';
include 'src/xt-xml-feed.class.php';
include 'src/xt-xml-tag.class.php';
$xt_tax_slug = 'email_tags';
$xt_options_str = 'exact_target_xml';
/* Fires up the Factory */
$xt_xml = new XT_XML;
$xt_xml->options_str = $xt_options_str;
$xt_xml->defaults = array(
	'post_count' => '10',
	'word_count' => '25',
	'image_size' => '125x125'
);
$xt_xml->taxonomy_slug = $xt_tax_slug;
$xt_xml->taxonomy_name = 'Email Tags';
/* Register the Email Tag Taxonomy */
add_action( 'init', array( $xt_xml, 'register_taxonomy' ), 0 );
/* Adds image sizes */
add_action( 'after_setup_theme', array( $xt_xml, 'add_image_sizes') );
/* Adds XML feed */
$xt_xml_feed = new XT_XML_Feed( $xt_options_str );
add_action( 'do_feed_xtxml', array( $xt_xml_feed, 'get_feed' ) );
/* Taxonomy Create/Update/Delete Hooks */
// Create
add_action( "created_${xt_tax_slug}", array( $xt_xml, 'add_option' ), 10, 2 );
// Delete
add_action( "deleted_${xt_tax_slug}", array( $xt_xml, 'delete_option' ), 10, 2 );
/* Admin */
if ( is_admin() ) {
	include 'src/xt-xml-admin.class.php';
	include 'src/abstract.xt_xml_form.php';
	include 'src/xt-xml-settings.class.php';
	include 'src/xt-xml-admin-form.class.php';
	/* Setup form */
	$xt_xml_form = new XT_XML_Admin_Form( $xt_xml );
	$xt_xml_admin = new XT_XML_Admin( $xt_xml_form );
	$xt_xml_admin->page_title  = 'Exact Target XML Pages';
	$xt_xml_admin->menu_title  = 'Exact Target XML';
	$xt_xml_admin->user_cap    = 'manage_options';
	$xt_xml_admin->plugin_slug = 'xt_xml';
	$xt_xml_admin->options_str = $xt_options_str;
	$xt_xml_admin->options_grp = "${xt_options_str}-group";
	$xt_xml_admin->fields_str  = "${xt_options_str}_fields";
	/* Admin Scripts */
	add_action('admin_enqueue_scripts', array( $xt_xml_admin, 'scripts_and_styles' ) );
	/* General Section */
	add_filter( $xt_xml_admin->options_str .'_sections', ( function( $sections ) {
		$sections['import_data'] = array(
			'id'    => 'manage_feeds',
			'title' => 'Manage Feeds'
		);

		return $sections;
	} ) );
	/* Setting */
	add_filter( $xt_xml_admin->options_str . '_fields', ( function( $fields ) {
		$fields['intro'] = array(
			'id'          => 'intro',
			'field'       => 'paragraph',
			'callback'    => 'paragraph',
			'title'       => 'Using the email tag manager',
			'section'     => 'manage_feeds',
			'description' => 'Edit the settings for your tag here.'
		);

		return $fields;
	} ) );
	/* Tag Settings Page */
	add_action( 'admin_menu', array( $xt_xml_admin, 'register_menu_page' ) );
	add_action( 'admin_init', array( $xt_xml_admin, 'menu_page_init' ) );
}