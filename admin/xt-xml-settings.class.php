<?php

if ( ! class_exists( 'XT_XML_Settings' ) ) {

class XT_XML_Settings {

	CONST OPTIONS_STR = 'exact_target_xml';
	CONST OPTIONS_GRP = 'exact_target_xml-group';
	CONST TRANSIENT_1 = 'foauhvahuhrrr';
	CONST TRANSIENT_2 = 'foaasdfadggguhrrr';

	/** @var array $options Array of options values */
	private $options = array();

	protected $fields = array(
		array(
			'id'          => 'tag1_size',
			'title'       => 'Tag Size (1)',
			'field'       => 'text',
			'description' => 'Enter size of tag. Example: <kbd>200x133</kbd>'
		),
		array(
			'id'          => 'tag1_name',
			'title'       => 'Tag Name (1)',
			'field'       => 'text',
			'description' => 'Enter the tag to be associated with this size.'
		),
		array(
			'id'          => 'tag2_size',
			'title'       => 'Tag Size (2)',
			'field'       => 'text',
			'description' => 'Enter size of tag. Example: <kbd>200x133</kbd>'
		),
		array(
			'id'          => 'tag2_name',
			'title'       => 'Tag Name (2)',
			'field'       => 'text',
			'description' => 'Enter the tag to be associated with this size.'
		)
	);



	public function __construct() {

		// Register settings
		$this->register_settings();

		// Creates the settings var to be referred to
		$this->options = get_option( self::OPTIONS_STR );

		// Add sections to settings page.
		$this->add_sections();

	}

	/**
	 * Register the plugin settings.
	 */
	protected function register_settings() {
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

		/** Basic Settings */
		add_settings_section(
			'basic_settings', // ID
			'Settings', // Title
			array( $this, 'basic_section_callback' ), // Callback
			XT_XML_Admin::PLUGIN_SLUG // Page
		);

		foreach ( $this->fields as $setting ) {
			$this->create_settings_field($setting);
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
		$new_input = array();

		set_transient(self::TRANSIENT_1, $input, 60);

		$new_input['tag1_size'] = wp_filter_nohtml_kses($input['tag1_size']);
		$new_input['tag1_name'] = wp_filter_nohtml_kses($input['tag1_name']);
		$new_input['tag2_size'] = wp_filter_nohtml_kses($input['tag2_size']);
		$new_input['tag2_name'] = wp_filter_nohtml_kses($input['tag2_name']);

		foreach ( $input as $key => $value ) {
			$new_input[$key] = wp_filter_nohtml_kses( $value );
		}
		set_transient(self::TRANSIENT_2, $new_input, 60);

		return $new_input;
	}

	/**
	 * @param array $settings
	 *      ID = input ID,
	 *      Title = Name of field,
	 *      Field = Type of field,
	 *      Description = Description below field
	 */
	public function create_settings_field( $settings ) {
		add_settings_field(
			$settings['id'], // ID
			$settings['title'], // Title
			array( $this, 'basic_input_callback' ), // Callback
			XT_XML_Admin::PLUGIN_SLUG, // Page
			'basic_settings', // Section
			array(
				'id'          => $settings['id'],
				'field'       => $settings['field'],
				'description' => $settings['description']
			) // Args
		);
	}

	/**
	 * Basic section callback. Creates the settings header.
	 */
	public function basic_section_callback() {
		?>
		<p>
			This plugin allows you to set image sizes for an XML feed used by Exact Target.
		</p>
		<p>
			To set the image size, choose a tag to associate with that size.
		</p>
	<?php
	}

	public function basic_input_callback( $args ) {
		?>
		<label for="<?php echo $args['id']; ?>">
			<input type="<?php echo $args['field']; ?>"
			       name="<?php echo $args['id']; ?>"
			       value="<?php echo $this->input_field_value($args['id']) ?>" />
		</label>
		<p>
			<?php echo $args['description']; ?>
		</p>
	<?php
	}

	protected function input_field_value($value) {

		return ( isset( $this->options[$value] ) ? $this->options[$value] : '');

	}
}

}