<?php

if ( ! class_exists( 'XT_XML_Admin_Form' ) ) {

class XT_XML_Admin_Form {

	protected static $instance;

	static function instance() {
		if (!isset(static::$instance)) {
			static::$instance = new static;
		}

		return static::$instance;
	}


	public function __construct() {

		$this->settings = new XT_XML_Settings;

		$this->admin_form();

	}

	/**
	 * Options page callback
	 */
	public function admin_form() {
		?>
		<div class="wrap">
			<h2><?php echo XT_XML_Admin::PAGE_TITLE; ?></h2>
			<form method="post" action="options.php">
				<div class="postbox ">
					<div class="inside">
						<?php settings_fields( XT_XML_Settings::OPTIONS_GRP ); ?>
						<?php do_settings_sections( XT_XML_Admin::PLUGIN_SLUG ); ?>
						<?php submit_button( ); ?>
					</div>
				</div>
			</form>
		</div>

		<!--<?php $this->debug_data(); ?> -->
	<?php
	}

	private function debug_data() {
		?><h2>Options</h2>
		<?php var_dump( $this->settings ); ?>
		<h2>Transients</h2>
		<p>input</p>
		<?php var_dump( get_transient('epg_validate_input_data') ); ?>
		<p>new_input</p>
		<?php var_dump( get_transient('epg_validate_new-input_data') );
		delete_transient('epg_validate_input_data');
		delete_transient('epg_validate_new-input_data');
	}

}

}