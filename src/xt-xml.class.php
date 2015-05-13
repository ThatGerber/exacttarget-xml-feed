<?php
/**
 * Class XT_XML
 */

class XT_XML {

	/** @var mixed|void  */
	public $tags;

	public $taxonomy_name = 'email_tags';

	/**
	 * PHP5 Constructor
	 *
	 * @param $options_str string
	 */
	public function __construct( $options_str ) {
		$this->tags = get_option( $options_str );
	}

	/**
	 * Adds the image sizes to be used with the plugin.
	 *
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

		register_taxonomy( $this->taxonomy_name, $post_types, $args );
	}
}