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


	protected function __construct() {
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
						<strong>Please note: After changing the image size, you will need to
						regenerate thumbnails to apply changes to older images.</strong>
					</p>
					<form method="post" action="options.php">
						<?php settings_fields( XT_XML_Admin::OPTIONS_GRP ); ?>
						<?php do_settings_sections( XT_XML_Admin::PLUGIN_SLUG ); ?>
						<?php submit_button( ); ?>
					</form>
					<!-- Add New tag -->
					<form method="post" action="">
						<p class="submit" style="display:inline-block;">
							Name: <br /><input type="text" value="" name="new_tag" />
							<input type="submit" name="submit" id="submit" class="button button-primary" value="Add New Tag">
						</p>
					</form>
				</div>
			</div>
		</div>
	<?php
	}
}

}