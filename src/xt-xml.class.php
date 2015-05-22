<?php
/**
 * Class XT_XML
 */

class XT_XML {

	/**
	 * @since 1.0.0
	 * @access public
	 *
	 * @var mixed|void
	 */
	public $tags;

	/**
	 * @since 1.0.0
	 * @access public
	 *
	 * @var string
	 */
	public $taxonomy_slug;

	/**
	 * @since 1.0.0
	 * @access public
	 *
	 * @var string
	 */
	public $taxonomy_name;

	/**
	 * @since 1.0.0
	 * @access public
	 *
	 * @var string
	 */
	public $options_str;

	/**
	 * @since 1.0.0
	 * @access public
	 *
	 * @var array
	 */
	public $defaults = array ();

	/**
	 * Returns options for a string
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return mixed|null|void
	 */
	public function get_options() {

		return get_option( $this->options_str );
	}

	/**
	 * Adds the image sizes to be used with the plugin.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function add_image_sizes() {
		if (
			is_array( $this->tags ) &&
			count( $this->tags ) >= 0
		) {
			foreach ( $this->tags as $field ) {
				add_image_size(
					$field->id . '-thumb',
					$field->image_size[0],
					$field->image_size[1],
					true
				);
			}
		}
		add_image_size( 'featured-email-thumb', 200, 133, true );
		add_image_size( 'email-thumb', 125, 90, true );
	}

	/**
	 * Register email Tags taxonomy
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register_taxonomy() {
		// Register Custom Taxonomy
		$labels = array(
			'name'                       => 'Email Tags',
			'singular_name'              => 'Email Tag',
			'menu_name'                  => 'Email Tags',
			'all_items'                  => 'All Tags',
			'parent_item'                => 'Parent Tag',
			'parent_item_colon'          => 'Parent Tag:',
			'new_item_name'              => 'New Email Tag',
			'add_new_item'               => 'Add New Tag',
			'edit_item'                  => 'Edit Tag',
			'update_item'                => 'Update Tag',
			'view_item'                  => 'View Tag',
			'separate_items_with_commas' => 'Separate Email Tags with commas',
			'add_or_remove_items'        => 'Add or remove tags',
			'choose_from_most_used'      => 'Choose from the most used',
			'popular_items'              => 'Popular Tags',
			'search_items'               => 'Search Tags',
			'not_found'                  => 'Not Found',
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => false,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => false,
			'show_tagcloud'              => false,
		);

		/** @var array $post_types */
		$post_types =  apply_filters( 'xt_xml_tag_post_types', array( 'post' ) );

		register_taxonomy( $this->taxonomy_slug, $post_types, $args );
	}

	/**
	 * Returns all tags as objects
	 *
	 * @return array|WP_Error
	 */
	public function get_all_tags() {

		return get_terms( $this->taxonomy_slug, array(
			'orderby'           => 'name',
			'order'             => 'desc',
			'hide_empty'        => false,
			'exclude'           => array(),
			'exclude_tree'      => array(),
			'include'           => array(),
			'fields'            => 'all'
		) );
	}

	/**
	 * @param $link
	 *
	 * @return string
	 */
	public function tag_admin_url( $link ) {
		return '<a href="://' . get_admin_url( null, 'edit-tags.php?taxonomy=' ) . __( $this->taxonomy_slug, 'xt_xml' ) . '">' . __( $link ) . '</a>';
	}

	/**
	 * Add new option to database
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param $term_id int
	 * @param $tt_id   int
	 */
	public function add_option( $term_id, $tt_id ) {
		$options = get_option( $this->options_str );
		//var_dump( $options );
		$options[ 'term_' . $tt_id ] = $this->defaults;
		//var_dump( $options );
		$updated = update_option( $this->options_str, $options );
		//var_dump ( $updated );
		//var_dump ( $this );
		//trigger_error('yes');
	}

	/**
	 * Update database option
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param $term_id int
	 * @param $tt_id   int
	 */
	public function update_option( $term_id, $tt_id ) {

	}

	/**
	 * Delete database option
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param $term_id int
	 * @param $tt_id   int
	 */
	public function delete_option( $term_id, $tt_id ) {

	}


}