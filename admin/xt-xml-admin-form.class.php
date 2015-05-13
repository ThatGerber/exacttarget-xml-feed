<?php

class XT_XML_Admin_Form extends XT_XML_Form {

	public $terms;

	public $xml;

	public function __construct(XT_XML $xml ) {
		$this->xml = $xml;

	}

	/**
	 * Options page callback
	 */
	public function render_form() {
		?>
		<div class="wrap">
			<?php $this->form_title(); ?>
			<div class="postbox ">
				<div class="inside">
					<form method="post" action="options.php">
						<?php settings_fields( $this->settings_fields ); ?>
						<?php do_settings_sections( $this->settings_sections ); ?>
					</form>
				</div>
			</div>
			<div class="postbox ">
				<div class="inside">
					<h3>Tags</h3>
					<?php $this->get_all_tags(); ?>
				</div>
			</div>
		</div>
	<?php
	}

	public function get_all_tags() {

		$args = array(
			'orderby'           => 'name',
			'hide_empty'        => false,
			'exclude'           => array(),
			'exclude_tree'      => array(),
			'include'           => array(),
			'fields'            => 'all'
		);

		$this->terms = get_terms( $this->xml->taxonomy_name, $args );

		var_dump( $this->terms );
	}
}