<?php
/**
 *
 */

if ( ! class_exists( 'XT_XML_Admin' ) ) {

	class XT_XML_Admin {

		CONST PAGE_TITLE  = 'Exact Target XML Pages';
		CONST MENU_TITLE  = 'Exact Target XML';
		CONST USER_CAP    = 'manage_options';
		CONST PLUGIN_SLUG = 'xt_xml';
		CONST OPTIONS_STR = 'exact_target_xml';
		CONST OPTIONS_GRP = 'exact_target_xml-group';
		CONST FIELDS_STR  = 'exact_target_xml_fields';

		/** @var array $options Array of options values */
		private $options = array();

		protected $sections = array(
			array(
				'id'    => 'basic_settings',
				'title' => 'Tags',
			)
		);

		public $fields = array(
		);

		/** @var string $hook_suffix Created by page registration */
		private $hook_suffix = '';

		public function __construct() {

			require_once( 'xt-xml-tag.class.php');
			require_once( 'xt-xml-settings.class.php');
			require_once( 'xt-xml-admin-form.class.php');

			add_action( 'admin_menu', array( $this, 'register_menu_page' ) );
			add_action( 'admin_init', array( $this, 'menu_page_init' ) );

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

			// Register settings
			$this->register_settings();
			// Creates the settings var to be referred to
			$this->fields = get_option( self::OPTIONS_STR );

			if ( isset( $_POST['submit'] ) && $_POST['submit'] === 'Add New Tag' ) {
				$this->add_new_tag( $_POST['new_tag'] );
			}
			// Add sections to settings page.
			$this->add_sections();

			// Errors
			add_action( 'admin_notices', array($this, 'add_errors') );

		}

		protected function add_new_tag( $name ) {

			$this->fields = get_option( self::OPTIONS_STR );
			$field_names  = array();

			if ( ! empty( $this->fields ) ) {
				foreach ( $this->fields as $field ) {
					$field_names[] = $field->id;
				}
			}

			if (
				false === array_search( xt_field_name_slugify( $name ), $field_names )
			) {
				$this->fields[] = new XT_XML_Tag($name);
			}

			return xt_update_option(self::OPTIONS_STR, $this->fields);
		}

		/**
		 * Register the plugin settings.
		 */
		public function register_settings() {
			// register our settings
			register_setting(
				self::OPTIONS_GRP,
				self::OPTIONS_STR,
				array( $this, 'options_validate' )
			);
		}

		/**
		 * Create form for plugin settings.
		 */
		public function add_sections() {

			foreach ( $this->sections as $section ) {
				$this->create_settings_section($section);
			}

			if ($this->fields) {
				foreach ( $this->fields as $setting ) {
					$this->create_settings_field($setting);
				}
			}
		}

		/**
		 * Sanitize and validate input. Accepts an array, return a sanitized array.
		 *
		 * @param array $input
		 *
		 * @return array $new_input
		 */
		public function options_validate( $input ) {

			if ( is_a( $input[0], 'XT_XML_Tag' ) ) {

				return $input;
			} elseif ( is_array( $input ) ) {
				foreach ( $input as $key => $value ) {
					if (
						( $field = xt_get_field($this->fields, $key) ) !== null
					) {
						$field->update_image_size( $value['image_size'] );
						$field->update_feed_count( $value['feed_count'] );
						$field->update_word_count( $value['word_count'] );
					}
				}
			}
			set_transient( 'epg_validate_input_data', $this->fields, 60);

			return $this->fields;
		}

		/**
		 * Queue up the errors
		 */
		public function add_errors() {
			settings_errors( self::OPTIONS_STR );
		}

		/**
		 * @param array $settings
		 *
		 *              ID = input ID,
		 *              Title = Name of field,
		 */
		protected function create_settings_section( $section ) {
			add_settings_section(
				$section['id'], //    'basic_settings', // ID
				$section['title'], // 'Tags', // Title
				array( $this, 'basic_section_callback' ), // Callback
				XT_XML_Admin::PLUGIN_SLUG // Page
			); // Args
		}

		/**
		 * @param object $settings
		 *              ID = input ID,
		 *              Title = Name of field,
		 *              Field = Type of field,
		 *              Description = Description below field
		 */
		protected function create_settings_field( $settings ) {
			add_settings_field(
				$settings->id, // ID
				$settings->tag, // Title
				array( $this, 'basic_input_callback' ), // Callback
				XT_XML_Admin::PLUGIN_SLUG, // Page
				$settings->section, // Section
				array($settings) // Args
			);
		}

		/**
		 * Basic section callback. Creates the settings header.
		 */
		public function basic_section_callback() {
			echo '';
		}

		public function basic_input_callback( $args ) {
			$args = $args[0];
			?>
			<input type="hidden"
			       name="<?php echo self::OPTIONS_STR; ?>[<?php echo $args->id; ?>][tag_name]"
			       value="<?php echo $args->tag; ?>" />
			<div>
				<label for="<?php echo self::OPTIONS_STR; ?>[<?php echo $args->id; ?>][image_size]">
					Image Size:&nbsp;&nbsp;&nbsp;
					<input type="<?php echo $args->field; ?>"
					       name="<?php echo self::OPTIONS_STR; ?>[<?php echo $args->id; ?>][image_size]"
					       value="<?php echo $this->image_size_field( $args->image_size) ?>" />
				</label>
			</div>
			<div>
				<label for="<?php echo self::OPTIONS_STR; ?>[<?php echo $args->id; ?>][word_count]">
					Word Count:&nbsp;
					<input type="<?php echo $args->field; ?>"
					       name="<?php echo self::OPTIONS_STR; ?>[<?php echo $args->id; ?>][word_count]"
					       value="<?php echo $this->input_field_value($args->word_count) ?>" />
				</label>
			</div>
			<div>
				<label for="<?php echo self::OPTIONS_STR; ?>[<?php echo $args->id; ?>][feed_count]">
					Feed Count: &nbsp;
					<input type="<?php echo $args->field; ?>"
					       name="<?php echo self::OPTIONS_STR; ?>[<?php echo $args->id; ?>][feed_count]"
					       value="<?php echo $this->input_field_value($args->feed_count) ?>" />
				</label>
			</div>
		<?php
		}

		/**
		 * Formats the input field for image sizes.
		 *
		 * @param array $size
		 *
		 * @return string
		 */
		protected function image_size_field( $size ) {

			return ( isset( $size ) ? $size[0] . 'x' . $size[1] : '' );
		}

		/**
		 * Just double checks something is set before it's
		 *
		 * @param $value
		 *
		 * @return string
		 */
		protected function input_field_value($value) {

			return ( isset( $value ) ? $value : '' );
		}

		/**
		 * Add Message to admin page.
		 *
		 * Will warn users of an issue or add a message saying it was successful.
		 *
		 * @param string $message Message to send to user
		 * @param string $type    Type of Message: Error / Updated
		 */
		public function new_error($message, $type) {
			add_settings_error(
				self::OPTIONS_STR,
				'settings_updated',
				$message,
				$type
			);
		}
	}

}