<?php
/**
 * undocumented class
 *
 * @package default
 * @author
 **/
class WCMPLS2015_Post_Type_Sections {

	public $name = 'section';

	public function __construct() {
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'fm_post_' . $this->name, array( $this, 'fm_meta' ) );
	}

	public function register_post_type() {

		register_post_type( $this->name, array(
			'labels' => array(
				'name' => esc_html__( 'Sections', 'text-domain' ),
				'singular_name' => esc_html__( 'Section', 'text-domain' ),
				'add_new' => esc_html__( 'Add New Section', 'text-domain', 'text-domain' ),
				'add_new_item' => esc_html__( 'Add New Section', 'text-domain' ),
				'edit_item' => esc_html__( 'Edit Section', 'text-domain' ),
				'new_item' => esc_html__( 'New Section', 'text-domain' ),
				'view_item' => esc_html__( 'View Section', 'text-domain' ),
				'search_items' => esc_html__( 'Search Sections', 'text-domain' ),
				'not_found' => esc_html__( 'No Sections found', 'text-domain' ),
				'not_found_in_trash' => esc_html__( 'No Sections found in Trash', 'text-domain' ),
				'parent_item_colon' => esc_html__( 'Parent Section:', 'text-domain' ),
				'menu_name' => esc_html__( 'Sections', 'text-domain' ),
			),
			'supports' => array(
				'title',
				'thumbnail',
				'revisions',
			),
			'public' => true,
		) );
	}

	public function fm_meta() {

		$curation_origin = new Fieldmanager_Datasource( array(
			'options' => array(
				__( 'Manual Post Curation', 'text-domain' ) ,
				__( 'Categories', 'text-domain' ),
				__( 'Post Tags', 'text-domain' ),
			)
		) );

		$fm_section_options = new Fieldmanager_Group( array(
			'name' => 'section_options',
			'limit' => 0,
			'label' => __( 'Source', 'text-domain' ),
			'children' => array(
				'curation_origin' => new Fieldmanager_Select( array(
					'label' => __( 'Curation Source', 'text-domain' ),
					'first_empty' => true,
					'datasource' => $curation_origin,
				) ),
				'post_type_post' => new Fieldmanager_Autocomplete( array(
					'display_if' => array(
						'src' => 'curation_origin',
						'value' => __( 'Manual Post Curation', 'text-domain' ),
					),
					'label' => __( 'Posts' ),
					'limit' => 0,
					'add_more_label' => __( 'Add another post' ),
					'datasource' => new Fieldmanager_Datasource_Post( array(
						'query_args' => array( 'post_type' => 'post' )
					) )
				) ),
				'categories' => new Fieldmanager_Autocomplete( array(
					'display_if' => array(
						'src' => 'curation_origin',
						'value' => __( 'Categories', 'text-domain' ),
					),
					'label' => 'Autocomplete with ajax',
					'datasource' => new Fieldmanager_Datasource_Term( array(
						'taxonomy' => 'category',
						'taxonomy_save_to_terms' => false
					) )
				) ),
				'tags' => new Fieldmanager_Autocomplete( array(
					'display_if' => array(
						'src' => 'curation_origin',
						'value' => __( 'Post Tags', 'text-domain' ),
					),
					'label' => 'Autocomplete with ajax',
					'datasource' => new Fieldmanager_Datasource_Term( array(
						'taxonomy' => 'post_tag',
						'taxonomy_save_to_terms' => false
					) )
				) ),
			), // end children
		) );
		$fm_section_options->add_meta_box( __( 'Section Options', 'text-domain' ), $this->name, 'normal', 'high' );
	}

} // END class

new WCMPLS2015_Post_Type_Sections();
