<?php
/**
 *
 */

if ( ! class_exists( 'XT_XML_Admin' ) ) {

	class XT_XML_Admin {

		CONST PAGE_TITLE  = 'Exact Target XML Pages';
		CONST MENU_TITLE  = 'Exact Target XML';
		CONST PLUGIN_SLUG = 'xt_xml';
		CONST USER_CAP    = 'manage_options';
		CONST ERROR_STR   = 'xt_xml_errors';
		CONST TRANSIENT_1 = 'foauhvahuhrrr';
		CONST TRANSIENT_2 = 'foaasdfadggguhrrr';

		protected $settings;

		/** @var string $hook_suffix Created by page registration */
		private $hook_suffix = '';

		public function __construct() {

			require_once( 'xt-xml-settings.class.php');
			require_once( 'xt-xml-admin-form.class.php');

			add_action( 'admin_menu', array( $this, 'register_menu_page' ) );
			add_action( 'admin_init', array( $this, 'menu_page_init' ) );
			add_action( 'admin_notices', array($this, 'add_errors') );

		}

		/**
		 * Register the Menu Page.
		 */
		public function register_menu_page() {
			$this->hook_suffix = add_options_page(
				self::PAGE_TITLE,     // Page Title
				self::MENU_TITLE,     // Menu Title
				self::USER_CAP,       // Capability
				self::PLUGIN_SLUG,    // Menu Slug
				array( 'XT_XML_Admin_Form', 'instance' ) // Function
			);
		}

		/*
		 * Initialization function for the settings page.
		 *
		 * Sets up the settings and calls the view.
		 */
		public function menu_page_init() {
			$this->settings = new XT_XML_Settings;
		}

		public function add_errors() {

			settings_errors( self::ERROR_STR );

		}
	}

}