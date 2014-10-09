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
		$this->admin_form();
	}

	/**
	 * Options page callback
	 */
	public function admin_form() {
		?>
		<div class="wrap">
			<h2><?php echo XT_XML_Admin::PAGE_TITLE; ?></h2>
			<div class="postbox ">
				<div class="inside">
					<p>
						This plugin allows you to set image sizes for an XML feed used by Exact Target.
					</p>
					<p>
						To set the image size, choose a tag to associate with that size.
					</p>
					<!-- Add New tag -->
					<form method="post" action="">
						<?php submit_button( 'Add New Tag' ); ?>
					</form>
					<form method="post" action="options.php">
						<?php settings_fields( XT_XML_Settings::OPTIONS_GRP ); ?>
						<?php do_settings_sections( XT_XML_Admin::PLUGIN_SLUG ); ?>
						<?php submit_button( ); ?>
					</form>
				</div>
			</div>
		</div>
	<?php
	}
}

}