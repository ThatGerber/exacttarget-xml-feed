<?php

class XT_XML_Admin_Form extends XT_XML_Form {

	public $terms;

	public $xml;

	public function __construct(XT_XML $xml ) {
		$this->xml = $xml;
		$this->values = $this->xml->get_options();
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
					<h2>Tags</h2>
					<form method="post" method="post" action="">
						<?php $this->render_tags(); ?>
					</form>
				</div>
			</div>
			<div class="postbox ">
				<div class="inside">
					<form method="post" action="options.php">
						<?php settings_fields( $this->settings_fields ); ?>
						<?php do_settings_sections( $this->settings_sections ); ?>
					</form>
				</div>
			</div>
			<?php var_dump_all( $_POST ); ?>
			<?php var_dump_all( $this->values ); ?>
		</div>
	<?php
	}

	/**
	 *
	 */
	public function render_tags() {
		if ( count( $tags = $this->xml->get_all_tags() ) === 0 ) {
			printf( __('<p>There are no tags created yet. Create tags at the %1$s edit page.</p>', 'xt_xml'), $this->xml->tag_admin_url('Email Tags') );
		} else {
			foreach ( $tags as $tag ) {
				$this->display_tag( $tag );
			}
			echo '<div>';
			$this->button( 'Update Tags', true );
			echo '</div>';
		}
	}

	/**
	 * Displays Tag Data
	 *
	 * @param $tag stdClass
	 */
	protected function display_tag( $tag ) {
		echo '<div class="email-tag">';
		echo '<h3>' . __( $tag->name ) . '</h3>';
		echo '<table class="form-table">';
		echo '<tr>';
		$this->text( array(
			'title' => 'Article Count',
			'field' => 'number',
			'id'    => $tag->term_taxonomy_id,
			'name'  => 'post_count'
		) );
		echo '</tr><tr>';
		$this->text( array(
			'title' => 'Word Count',
			'field' => 'number',
			'id'    => $tag->term_taxonomy_id,
			'name'  => 'word_count'
		) );
		echo '</tr><tr>';
		$this->text( array(
			'title' => 'Image Size',
			'field' => 'text',
			'id'    => $tag->term_taxonomy_id,
			'name'  => 'image_size'
		) );
		echo '</tr></table></div>';
	}
}