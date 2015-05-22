<?php
/**
 * Abstract class for building forms.
 *
 * Class abstraction for simple form functions.
 *
 * @since 1.0.0
 *
 * @package    WordPress
 */

Abstract Class XT_XML_Form {

	/**
	 * Page Title
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @var string
	 */
	public $title;

	/**
	 * String to call settings fields
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @var string
	 */
	public $settings_fields;

	/**
	 * String to call settings sections
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @var string
	 */
	public $settings_sections;

	/**
	 * @since 0.2.0
	 * @access public
	 *
	 * @var array
	 */
	public $values;

	/**
	 * String identifier for the options
	 *
	 * @since 0.2.0
	 * @access public
	 *
	 * @var string
	 */
	public $options_str;

	/**
	 * Coagulates the functions into a form on the front-end.
	 *
	 * Abstract
	 *
	 * @since 0.0.1
	 * @access public
	 */
	abstract public function render_form();

	/**
	 * Adds the title to the page.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @param $title string Form Title
	 */
	public function form_title( $title = null ) {
		// Takes specialized title, or uses default if empty.
		$title = ( $title === null ? $this->title : $title );
		// Echos title
		echo "<h2>$title</h2>";
	}

	/**
	 * Creates block of text
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param $args array
	 */
	public function paragraph( $args ) {
		$description = $args[0]['description'];
		?><p><?php _e( $description, 'xt_xml' );?></p>
	<?php
	}

	/**
	 * Creates input
	 *
	 * @since 0.2.0
	 * @access public
	 *
	 * @param $args array
	 */
	public function text( $args ) {
		$id    = $this->options_str . '[' . $args['id'] . '][' . $args['name'] . ']';
		$field = $args['field'];
		$title = $args['title'];
		$value = ( ! isset( $this->values[ $args['id'] ][ $args['name'] ] ) ? '' : $this->values[ $args['id'] ][ $args['name'] ] );
		?>
		<td>
			<label for="<?php _e( $id, 'xt_xml' ); ?>">
				<?php _e( $title, 'xt_xml' ); ?>
			</label>
		</td>
		<td>
			<input type="<?php _e( $field, 'xt_xml' ); ?>"
			       id="<?php _e( $id, 'xt_xml' ); ?>"
			       name="<?php _e( $id, 'xt_xml' ); ?>"
			       value="<?php _e( $value, 'xt_xml' ); ?>" />
		</td>
	<?php
	}

	/**
	 * Creates Select Options for widget
	 *
	 * @since 0.2.0
	 * @access public
	 *
	 * @param $args Array
	 */
	public function ads_dropdown( $args ) {
		// Why is it so nested?
		$args   = $args[0];
		// Field values
		$id     = $this->options_str . '[' . $args['id'] . ']';
		$title  = $args['title'];
		$value  = ( ! isset( $this->values[ $args['id'] ] ) ? '' : $this->values[ $args['id'] ] );
		?>
		<div>
			<select id="<?php _e( $id, 'dfp-ads' ); ?>" name="<?php _e( $id, 'dfp-ads' ); ?>">
				<?php dfp_ad_select_options( $value ); ?>
			</select>
			<?php if ( isset( $args['description'] ) ) { ?>
				<p><em><?php _e( $args['description'], 'dfp-ads'); ?></em></p>
			<?php } ?>
		</div>
	<?php
	}

	/**
	 * Button Function
	 *
	 * Creates an HTML button.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @param $value string Value of the submit button
	 * @param $primary bool Mark a button as a primary button
	 */
	public function button( $value, $primary = false ) {
		$value = wp_strip_all_tags( $value, true );
		$button_type = ($primary === false ? 'button-secondary' : 'button-primary' )
		?>
		<input type="submit" name="submit" id="submit" class="button <?php echo $button_type; ?>" value="<?php echo $value; ?>">
	<?php
	}

	/**
	 * Simple method for accessing a submit button
	 *
	 * @since 0.0.1
	 * @access public
	 */
	public function submit_button() {
		$this->button("Save Changes", true);
	}
}