<?php
/**
 *
 */

class XT_XML_Admin {

    /**
     * Title of the Admin Page
     *
     * @since 1.0.0
     * @access public
     *
     * @var string
     */
    public $page_title;

    /**
     * Menu name for the admin page
     *
     * @since 1.0.0
     * @access public
     *
     * @var string
     */
    public $menu_title;

    /**
     * Capability to access the page
     *
     * @since 1.0.0
     * @access public
     *
     * @var string
     */
    public $user_cap;

    /**
     * Slug for the plugin page
     *
     * @since 1.0.0
     * @access public
     *
     * @var string
     */
    public $plugin_slug;

    /**
     * String for accessing stored options
     *
     * @since 1.0.0
     * @access public
     *
     * @var string
     */
    public $options_str;

    /**
     * Group name for options
     *
     * @since 1.0.0
     * @access public
     *
     * @var string
     */
    public $options_grp;

    /**
     * String for fields
     *
     * @since 1.0.0
     * @access public
     *
     * @var string
     */
    public $fields_str;

    /**
     * Array of options values
     *
     * @since 0.1.0
     * @access private
     *
     * @var array
     */
    private $options = array();

    protected $sections = array(
        array(
            'id'    => 'basic_settings',
            'title' => 'Tags',
        )
    );


    public $fields = array(
    );

    /**
     * Created by page registration
     *
     * @since 0.1.0
     * @access private
     *
     * @var string
     */
    private $hook_suffix = '';

    /**
     * PHP5 Constructor
     */
    public function __construct( XT_XML_Admin_Form $form ) {
	    $this->form = $form;
    }

	public function scripts_and_styles() {
		wp_enqueue_style( 'xt-xml-admin', plugins_url('../assets/css/admin.css', __FILE__ ) );
	}

    /**
     * Register the Menu Page.
     */
    public function register_menu_page() {
	    $this->sections = apply_filters( $this->options_str . '_sections', array() );
	    $this->fields   = apply_filters( $this->options_str . '_fields', array() );
	    $this->values   = get_option( $this->options_str );
	    $this->hook_suffix = add_options_page(
		    $this->page_title,     // Page Title
		    $this->menu_title,     // Menu Title
		    $this->user_cap,       // Capability
		    $this->plugin_slug,    // Menu Slug
		    array( $this, 'form' ) // Function
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
        // Errors
        add_action( 'admin_notices', array($this, 'add_errors') );

    }

    protected function add_new_tag( $name ) {

	    return true;
    }

    /**
     * Register the plugin settings.
     */
    public function register_settings() {
        // register our settings
        register_setting(
            $this->options_grp,
            $this->options_str,
            array( $this, 'options_validate' )
        );
    }

	/**
	 * Create form for plugin settings.
	 *
	 * @since 0.0.1
	 * @access public
	 */
	public function add_sections() {

		if ( $this->sections ) {
			foreach ( $this->sections as $section ) {
				$this->create_settings_section( $section );
			}
		}

		if ( $this->fields ) {
			foreach ( $this->fields as $setting ) {
				$this->create_settings_field( $setting );
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
        settings_errors( $this->options_str );
    }

	/**
	 * Creates the settings sections
	 *
	 * @since 0.0.1
	 * @access protected
	 *
	 * @param array $section ID = input ID,
	 *                       Title = Name of field,
	 */
	protected function create_settings_section( $section ) {
		add_settings_section(
			$section['id'],    // ID
			$section['title'], // Title
			array( $this, 'basic_section_callback' ), // Callback
			$this->plugin_slug // Page
		);
	}

	/**
	 * Creates settings fields
	 *
	 * @since 0.0.1
	 * @access protected
	 *
	 * @param array $settings
	 *              ID = input ID,
	 *              Title = Name of field,
	 *              Field = Type of field,
	 *              Callback = Callback function
	 *              Description = Description below field
	 */
	protected function create_settings_field( $settings ) {
		add_settings_field(
			$settings['id'], // ID
			$settings['title'], // Title
			array( $this->form, $settings['callback'] ), // Callback
			$this->plugin_slug, // Page
			$settings['section'], // Section
			array($settings) // Args
		);
	}

	/**
	 * Renders Form Object
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function form() {
		$this->form->values            = $this->values;
		$this->form->options_str       = $this->options_str;
		$this->form->title             = $this->page_title;
		$this->form->settings_fields   = $this->options_grp;
		$this->form->settings_sections = $this->plugin_slug;
		$this->form->render_form();
	}

    /**
     * Basic section callback. Creates the settings header.
     *
     * @since 1.0.0
     * @access public
     *
     * @param $args array
     */
    public function basic_section_callback( $args ) {}

	/**
	 * To be replaced
	 *
	 * @param $args array
	 */
    public function basic_input_callback( $args ) {}

    /**
     * Just double checks something is set before it's returned
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
            $this->options_str,
            'settings_updated',
            $message,
            $type
        );
    }
}