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
    public function __construct() {

    }

    /**
     * Register the Menu Page.
     */
    public function register_menu_page() {
        $this->hook_suffix = add_options_page(
            $this->page_title,  // Page Title
            $this->menu_title,  // Menu Title
            $this->user_cap,    // Capability
            $this->plugin_slug, // Menu Slug
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
        $this->fields = get_option( $this->options_str );
        if ( isset( $_POST['submit'] ) && $_POST['submit'] === 'Add New Tag' ) {
            $this->add_new_tag( $_POST['new_tag'] );
        }
        // Add sections to settings page.
        $this->add_sections();
        // Errors
        add_action( 'admin_notices', array($this, 'add_errors') );

    }

    protected function add_new_tag( $name ) {

        $this->fields = get_option( $this->options_str );
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

        return xt_update_option( $this->options_str, $this->fields);
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
        settings_errors( $this->options_str );
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
            $this->plugin_slug // Page
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
            $this->plugin_slug, // Page
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
               name="<?php echo $this->options_str; ?>[<?php echo $args->id; ?>][tag_name]"
               value="<?php echo $args->tag; ?>" />
        <div>
            <label for="<?php echo $this->options_str; ?>[<?php echo $args->id; ?>][image_size]">
                Image Size:&nbsp;&nbsp;&nbsp;
                <input type="<?php echo $args->field; ?>"
                       name="<?php echo $this->options_str; ?>[<?php echo $args->id; ?>][image_size]"
                       value="<?php echo $this->image_size_field( $args->image_size) ?>" />
            </label>
        </div>
        <div>
            <label for="<?php echo $this->options_str; ?>[<?php echo $args->id; ?>][word_count]">
                Word Count:&nbsp;
                <input type="<?php echo $args->field; ?>"
                       name="<?php echo $this->options_str; ?>[<?php echo $args->id; ?>][word_count]"
                       value="<?php echo $this->input_field_value($args->word_count) ?>" />
            </label>
        </div>
        <div>
            <label for="<?php echo $this->options_str; ?>[<?php echo $args->id; ?>][feed_count]">
                Feed Count: &nbsp;
                <input type="<?php echo $args->field; ?>"
                       name="<?php echo $this->options_str; ?>[<?php echo $args->id; ?>][feed_count]"
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
            $this->options_str,
            'settings_updated',
            $message,
            $type
        );
    }
}