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
Github URI:        https://github.com/ThatGerber/exacttarget-xml-feed
Github Branch:     master
*/

/* Files */
include 'includes/functions.xt-xml.php';
include 'includes/class.xt-xml.php';
include 'includes/class.xt-xml-feed.php';
include 'includes/class.xt-xml-tag.php';
include 'includes/class.xt-xml-admin.php';
include 'includes/abstract.xt_xml_form.php';
include 'includes/class.xt-xml-settings.php';
include 'includes/class.xt-xml-admin-form.php';
include 'includes/class.xt-xml-metabox.php';

/* Directory */
$xt_dirname = dirname( __FILE__ );
/* A few constants */
$xt_tax_slug = 'email-tags';
$xt_options_str = 'exact_target_xml';
/* Fires up the instance */
$xt_xml = new XT_XML;
$xt_xml->template_dir  = $xt_dirname;
$xt_xml->options_str   = $xt_options_str;
$xt_xml->taxonomy_slug = $xt_tax_slug;
$xt_xml->taxonomy_name = 'Email Tags';
$xt_xml->defaults      = array(
	'post_count' => 10,
	'word_count' => 25,
	'image_size' => '125x125'
);
/* Register the Email Tag Taxonomy */
add_action( 'init', array( $xt_xml, 'register_taxonomy' ), 0 );
/* Adds image sizes */
add_action( 'wp_loaded', array( $xt_xml, 'add_image_sizes') );
/* Metabox */
$xt_metabox = new XT_XML_Metabox;
/* Settings */
$xt_metabox->id         = 'xt_xml';
$xt_metabox->title      = 'Except for Enewsletter';
$xt_metabox->post_types = $xt_xml->post_types();
$xt_metabox->register_metaboxes();
$xt_metabox->register_save_data();

/* Adds XML feed */
$xt_xml_feed = new XT_XML_Feed( $xt_xml );
$xt_xml_feed->desc_meta_key = $xt_metabox->meta_key;
add_action( 'do_feed_xtxml', array( $xt_xml_feed, 'get_feed' ) );
/* Taxonomy Create/Update/Delete Hooks */
// Create
add_action( "created_${xt_tax_slug}", array( $xt_xml, 'update_option' ), 10, 2 );
// Delete
add_action( "deleted_${xt_tax_slug}", array( $xt_xml, 'delete_option' ), 10, 2 );

/* Admin */
if ( is_admin() ) {
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
	/* Check for custom form submit value. If it exists, update the data that is there. */
	if ( isset( $_POST['submit'] ) && $_POST['submit'] == 'Update Tags' ) {
		update_option( $xt_xml->options_str, $_POST[ $xt_xml->options_str ] );
	}
	/* Tag Settings Page */
	add_action( 'admin_menu', array( $xt_xml_admin, 'register_menu_page' ) );
	add_action( 'admin_init', array( $xt_xml_admin, 'menu_page_init' ) );
}
