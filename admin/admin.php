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
		CONST OPTIONS_STR = 'exact_target_xml';

		private $options = array();
		private $hook_suffix = '';

		public function __construct() {

			add_action( 'admin_menu', array( $this, 'register_menu_page' ) );
			add_action( 'admin_init', array( $this, 'menu_page_init' ) );
		}

		/**
		 * Register the Menu Page.
		 */
		public function register_menu_page() {
			$this->hook_suffix = add_options_page(
				self::PAGE_TITLE,            // Page Title
				self::MENU_TITLE,            // Menu Title
				self::USER_CAP,              // Capability
				self::PLUGIN_SLUG,           // Menu Slug
				array( $this, 'admin_form' ) // Function
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

			// Add sections to settings page.
			$this->add_sections();

			// Show Errors
			$this->add_errors();
			add_action( 'admin_notices', array($this, 'your_admin_notices_action') );
		}

		/**
		 * Register the plugin settings.
		 */
		public function register_settings() {
			// register our settings
			register_setting(
				self::OPTIONS_STR . '-group',
				self::OPTIONS_STR,
				array( $this, 'options_validate' )
			);
		}

		/**
		 * Create form for plugin settings.
		 */
		public function add_sections() {

			/** Basic Settings */
			add_settings_section(
				'basic_settings', // ID
				'Settings', // Title
				array( $this, 'basic_section_callback' ), // Callback
				'basic' // Page
			);

			add_settings_field(
				'tag1_size', // ID
				'Image Size 1', // Title
				array( $this, 'tag1_size_callback' ), // Callback
				'basic', // Page
				'basic_settings' // Section
			);

			add_settings_field(
				'tag1_name', // ID
				'Tag Name (1)', // Title
				array( $this, 'tag1_name_callback' ), // Callback
				'basic', // Page
				'basic_settings' // Section
			);

			add_settings_field(
				'tag2_size', // ID
				'Image Size 2', // Title
				array( $this, 'tag2_size_callback' ), // Callback
				'basic', // Page
				'basic_settings' // Section
			);

			add_settings_field(
				'tag2_name',                         // ID
				'Tag Name (2)',                      // Title
				array( $this, 'tag2_name_callback' ), // Callback
				'basic', // Page
				'basic_settings' // Section
			);
		}

		public function add_errors() {


			function your_admin_notices_action() {
				settings_errors( 'your-settings-error-slug' );
			}

		}
		/**
		 * Options page callback
		 */
		public function admin_form() {

			$this->options = get_option( self::OPTIONS_STR );

			?>

			<div class="wrap">

				<h1><?php echo self::PAGE_TITLE; ?></h1>

				<?php var_dump( $this->options ); ?>

				<form method="post" action="options.php">

					<div class="postbox ">
						<div class="inside">
							<?php settings_fields( self::OPTIONS_STR . '-group' ); ?>

							<?php do_settings_sections( 'basic' ); ?>

							<?php submit_button( 'Save All Settings' ); ?>

						</div>
					</div>
				</form>

			</div>
		<?php
		}

		/**
		 * Sanitize and validate input. Accepts an array, return a sanitized array.
		 *
		 * @param array $input
		 *
		 * @return array $new_input
		 */
		public function options_validate( $input ) {
			$new_input = array();

			foreach ( $input as $key => $value ) {

				$new_input[$key] = wp_filter_nohtml_kses( $value );

			}

			return $new_input;
		}

		public function basic_section_callback() {
			?>
			<p>
				This plugin allows you to set image sizes for an XML feed used by Exact Target.</p>
			<p>
				To set the image size, choose a tag to associate with that size.</p>
		<?php
		}

		public function tag1_name_callback() {
			$tag_name = 'tag1_name';
			?>
			<label for="xtxml_settings[<?php echo $tag_name; ?>]; ?>_name]">
				<input type="text" name="xtxml_settings[<?php echo $tag_name; ?>]"
				       value="<?php echo $this->options[$tag_name]; ?>"/>
			</label>
			<p>
				Tag to associate with image size.
			</p>
		<?php
		}

		public function tag1_size_callback() {
			$tag_number = 'tag1_size';
			?>
			<label for="xtxml_settings[<?php echo $tag_number; ?>]">
				<input type="text" name="xtxml_settings[<?php echo $tag_number; ?>]"
				       value="<?php echo $this->options[$tag_number]; ?>"/>
			</label>
			<p>
				Provide Tag Size: Example <kbd>200x133</kbd>
			</p>
		<?php
		}

		public function tag2_name_callback() {
			$tag_name = 'tag2_name';
			?>
			<label for="xtxml_settings[<?php echo $tag_name; ?>]; ?>_name]">
				<input type="text" name="xtxml_settings[<?php echo $tag_name; ?>]"
				       value="<?php echo $this->options[$tag_name]; ?>"/>
			</label>
			<p>
				Tag to associate with image size.
			</p>
		<?php
		}

		public function tag2_size_callback() {
			$tag_number = 'tag2_size';
			?>
			<label for="xtxml_settings[<?php echo $tag_number; ?>]">
				<input type="text" name="xtxml_settings[<?php echo $tag_number; ?>]"
				       value="<?php echo $this->options[$tag_number]; ?>"/>
			</label>
			<p>
				Provide Tag Size: Example <kbd>200x133</kbd>
			</p>
		<?php
		}

		private function notices() {
			$message = null;
			$type = null;

			if ( null != $data ) {

				if ( false === get_option( 'myOption' ) ) {

					add_option( 'myOption', $data );
					$type = 'updated';
					$message = __( 'Successfully saved', 'my-text-domain' );

				} else {

					update_option( 'myOption', $data );
					$type = 'updated';
					$message = __( 'Successfully updated', 'my-text-domain' );

				}

			} else {

				$type = 'error';
				$message = __( 'Data can not be empty', 'my-text-domain' );

			}

			add_settings_error(
				'myUniqueIdentifyer',
				esc_attr( 'settings_updated' ),
				$message,
				$type
			);

		}


	}

}