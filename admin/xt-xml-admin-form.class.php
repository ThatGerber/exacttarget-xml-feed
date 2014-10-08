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
		?>
		<div class="wrap">
			<h1><?php echo XT_XML_Admin::PAGE_TITLE; ?></h1>
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

		<?php $this->debug_data(); ?>
	<?php

	}

	/**
	 * Options page callback
	 */
	public function admin_form() {
	}

	private function debug_data() {
		?><h2>Options</h2>
		<?php var_dump( $this->settings ); ?>
		<h2>Transients</h2>
		<p>input</p>
		<?php var_dump( get_transient(XT_XML_Admin::TRANSIENT_1) ); ?>
		<p>new_input</p>
		<?php var_dump( get_transient(XT_XML_Admin::TRANSIENT_2) );
		delete_transient(XT_XML_Admin::TRANSIENT_1);
		delete_transient(XT_XML_Admin::TRANSIENT_2);
	}

}

}