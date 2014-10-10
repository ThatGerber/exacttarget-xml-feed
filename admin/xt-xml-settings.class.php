<?php

if ( ! class_exists( 'XT_XML_Settings' ) ) {

class XT_XML_Settings {

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

		// Errors
		add_action( 'admin_notices', array($this, 'add_errors') );

	}

	/**
	 * @param string $id
	 * @param string $title
	 * @param string $field
	 * @param string $description
	 */
	public function add_settings_field( $id, $title, $field, $description = '' ) {

		$this->fields[] =  array(
			'id'          => $id,
			'title'       => $title,
			'field'       => $field,
			'description' => $description
		);

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
	 * Sanitize and validate input. Accepts an array, return a sanitized array.
	 *
	 * @param array $input
	 *
	 * @return array $new_input
	 */
	public function options_validate( $input ) {
		$new_input = array();

		set_transient( 'epg_validate_input_data', $input, 60);

		foreach ( $input as $key => $value ) {
			if ($value === '') {
				$this->new_error($key . ' is blank. Please include a value.', 'error');
			}
			$new_input[$key] = wp_filter_nohtml_kses($value);
		}
		set_transient('epg_validate_new-input_data', $new_input, 60);

		return $new_input;
	}

	/**
	 * Create form for plugin settings.
	 */
	public function add_sections() {

		foreach ( $this->sections as $section ) {
			$this->create_settings_section($section);
		}

		foreach ( $this->fields as $setting ) {
			$this->create_settings_field($setting);
		}
	}

	public function add_errors() {
		settings_errors( self::OPTIONS_STR );
	}



	/**
	 * @param array $settings
	 *      ID = input ID,
	 *      Title = Name of field,
	 *      Field = Type of field,
	 *      Description = Description below field
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
	 * @param array $settings
	 *      ID = input ID,
	 *      Title = Name of field,
	 *      Field = Type of field,
	 *      Description = Description below field
	 */
	protected function create_settings_field( $settings ) {
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
		echo '';
	}

	public function basic_input_callback( $args ) {
		?>
		<label for="<?php echo self::OPTIONS_STR; ?>[<?php echo $args['id']; ?>]">
			<input type="<?php echo $args['field']; ?>"
			       name="<?php echo self::OPTIONS_STR; ?>[<?php echo $args['id']; ?>]"
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