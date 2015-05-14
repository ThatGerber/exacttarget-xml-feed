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
					<h2>Tags</h2>
					<?php $this->render_tags(); ?>
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
			<div class="postbox ">
				<div class="inside">
					<?php var_dump_all( $_POST ); ?>
				</div>
			</div>

		</div>
	<?php
	}

	public function render_tags() {
		if ( count( $tags = $this->get_all_tags() ) === 0 ) {
			printf( __('There are no tags created yet. Create tags at the %1$s edit page.', 'xt_xml'), $this->tag_admin_url('Email Tags') );

			return null;
		} else {
			$this->display_tags( $tags );
		}
	}

	/**
	 * @param $tags array
	 */
	protected function display_tags( $tags ) {
		foreach ( $tags as $tag ) {
			$this->display_tag( $tag );
		}
	}

	protected function display_tag( $tag ) {
		?>
		<div class="email-tag">
			<h3><?php _e( $tag->name ); ?></h3>
			<?php var_dump( $tag ); ?>
		</div>
		<?php
	}

	public function get_all_tags() {

		 return get_terms( $this->xml->taxonomy_slug, array(
			 'orderby'           => 'name',
			 'order'             => 'desc',
			 'hide_empty'        => false,
			 'exclude'           => array(),
			 'exclude_tree'      => array(),
			 'include'           => array(),
			 'fields'            => 'all'
		) );
	}

	public function tag_admin_url( $link ) {
		return '<a href="://' . get_admin_url( null, 'edit-tags.php?taxonomy=' ) . __( $this->xml->taxonomy_slug, 'xt_xml' ) . '">' . __( $link ) . '</a>';
	}
}