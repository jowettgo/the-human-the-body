<?php
/**
 * Spinal custom post type
 * @version 0.1.0
 * Developed by: The Codewritter
 * URL: http://codewritter.com/spinal/
*/
class spinal_post {
	/**
	 * [$type name]
	 * @var string
	 */
	public $type ='custom';
	public $capability = 'post';
	/**
	 * [$singular singular name, appended after post type]
	 * @var string
	 */
	public $singular = 'post';
	/**
	 * [$singular multiple name, appended after post type]
	 * @var string
	 */
	public $multiple = 'posts';
	/**
	 * [$description set the description of this custom post type]
	 * @var string
	 */
	public $description = 'custom description';
	/**
	 * [$icon add icon to the menu page in admin, supports url and glaphicons css classes]
	 * @var string
	 */
	public $icon = 'dashicons-media-text';
	/**
	 * [$index set the order in the menu for admin]
	 * @var integer
	 */
	public $index = 15;
	/**
	 * [$slug used for url rewrites]
	 * @var string
	 */
	public $slug = 'custom_slug';
	public $slugAddon = '-cat';
	/**
	 * [$taxonomies enable or disable category and tag taxonomies]
	 * @var [type]
	 */
	public $taxonomies = true;
	/**
	 * [$support add the post support]
	 * @var array
	 */
	public $support = array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'sticky');
	public function hooks() {

		/* create our custom post type */
		$this->spinal_create_post_type();
		/* enable taxonomies */
		if($this->taxonomies) {
			/* category support */
			$this->spinal_custom_category_taxonomy();
			/* tag support */
			$this->spinal_custom_tags_taxonomy();
		}
		/* add action on Wordpress init */
		add_action( 'init', array($this, 'spinal_create_post_type'));

	}
	/**
	 * [create custom post type for spinal]
	 * @method spinal_create_post_type
	 * @return [type]                  [description]
	 */
	function spinal_create_post_type() {

		$singular = $this->singular;
		$multiple = $this->multiple;
		// creating (registering) the custom type
		register_post_type( strtolower($this->type), /* (http://codex.wordpress.org/Function_Reference/register_post_type) */
			// let's now add all the options for this post type
			array( 'labels' => array(
				'name' => __( ucfirst($singular) ), /* This is the Title of the Group */
				'singular_name' => __( $singular ), /* This is the individual type */
				'all_items' => __( 'All '.$multiple ), /* the all items menu item */
				'add_new' => __( 'Add New' ), /* The add new menu item */
				'add_new_item' => __( 'Add New '.$singular ), /* Add New Display Title */
				'edit' => __( 'Edit' ), /* Edit Dialog */
				'edit_item' => __( 'Edit '.$this->singular.' Types' ), /* Edit Display Title */
				'new_item' => __( 'New '.$this->singular.' Type' ), /* New Display Title */
				'view_item' => __( 'View '.$this->singular.' Type' ), /* View Display Title */
				'search_items' => __( 'Search '.$this->singular.' Type' ), /* Search Custom Type Title */
				'not_found' =>  __( 'Nothing found in the Database.' ), /* This displays if there are no entries yet */
				'not_found_in_trash' => __( 'Nothing found in Trash' ), /* This displays if there is nothing in the trash */
				'parent_item_colon' => ''
				), /* end of arrays */
				'description' => __( $this->description ), /* Custom Type Description */
				'public' => true,
				'publicly_queryable' => true,
				'exclude_from_search' => false,
				'show_ui' => true,
				'query_var' => true,
				'menu_position' => $this->index, /* this is what order you want it to appear in on the left hand side menu */
				'menu_icon' => $this->icon, /* the icon for the custom post type menu */
				'rewrite'	=> array( 'slug' => $this->slug, 'with_front' => true, 'pages' => true), /* you can specify its url slug */
				'has_archive' => $this->type, /* you can rename the slug here */
				'capability_type' => $this->capability,
				'hierarchical' => true,
				/* the next one is important, it tells what's enabled in the post editor */
				'supports' => $this->support
			) /* end of options */
		); /* end of register post type */

		if($this->taxonomies) {
			/* this adds your post categories to your custom post type */
			register_taxonomy_for_object_type( $this->slug.'', $this->type );
			/* this adds your post tags to your custom post type */
			register_taxonomy_for_object_type( $this->slug.'', $this->type );
		}
		/* Flush rewrite rules for custom post types */
		add_action( 'after_switch_theme', array($this, 'spinal_flush_rewrite_rules') );
	}
	/**
	 * [create the custom spinal post type taxonomy]
	 * @method spinal_custom_category_taxonomy
	 * @return null
	 */
	function spinal_custom_category_taxonomy() {

		register_taxonomy( strtolower($this->type).'_cat',
			array(strtolower($this->type)), /* if you change the name of register_post_type( $this->type, then you have to change this */
			array('hierarchical' => true,     /* if this is true, it acts like categories */
				'labels' => array(
					'name' => __( ucfirst($this->singular).' Categories' ), /* name of the custom taxonomy */
					'singular_name' => __( $this->singular.' Category' ), /* single taxonomy name */
					'search_items' =>  __( 'Search '.$this->singular.' Categories' ), /* search title for taxomony */
					'all_items' => __( 'All '.$this->type.' Categories' ), /* all title for taxonomies */
					'parent_item' => __( 'Parent '.$this->singular.' Category' ), /* parent title for taxonomy */
					'parent_item_colon' => __( 'Parent '.$this->singular.' Category:' ), /* parent taxonomy title */
					'edit_item' => __( 'Edit '.$this->singular.' Category' ), /* edit custom taxonomy title */
					'update_item' => __( 'Update '.$this->singular.' Category' ), /* update title for taxonomy */
					'add_new_item' => __( 'Add New '.$this->singular.' Category' ), /* add new title for taxonomy */
					'new_item_name' => __( 'New '.$this->singular.' Category Name' ) /* name title for taxonomy */
				),
				'show_admin_column' => true,
				'show_ui' => true,
				'query_var' => true,
				'rewrite' => array( 'slug' =>  $this->slug.''.$this->slugAddon ),
			)
		);
	}
	/**
	 * [add tag taxonomy support for spinal custom pot type]
	 * @method spinal_custom_tags_taxonomy
	 * @return null
	 */
	function spinal_custom_tags_taxonomy() {
		register_taxonomy( strtolower($this->type).'_tag',
			array(strtolower($this->type)), /* if you change the name of register_post_type( $this->type, then you have to change this */
			array('hierarchical' => false,    /* if this is false, it acts like tags */
				'labels' => array(
					'name' => __( $this->singular.' Tags' ), /* name of the custom taxonomy */
					'singular_name' => __( $this->singular.' Tag' ), /* single taxonomy name */
					'search_items' =>  __( 'Search '.$this->singular.' Tags' ), /* search title for taxomony */
					'all_items' => __( 'All '.$this->singular.' Tags' ), /* all title for taxonomies */
					'parent_item' => __( 'Parent '.$this->singular.' Tag' ), /* parent title for taxonomy */
					'parent_item_colon' => __( 'Parent '.$this->singular.' Tag:' ), /* parent taxonomy title */
					'edit_item' => __( 'Edit '.$this->singular.' Tag' ), /* edit custom taxonomy title */
					'update_item' => __( 'Update '.$this->singular.' Tag' ), /* update title for taxonomy */
					'add_new_item' => __( 'Add New '.$this->singular.' Tag' ), /* add new title for taxonomy */
					'new_item_name' => __( 'New '.$this->singular.' Tag Name' ) /* name title for taxonomy */
				),
				'show_admin_column' => true,
				'show_ui' => true,
				'query_var' => true,
			)
		);
	}
	// Flush your rewrite rules
	function spinal_flush_rewrite_rules() {
		flush_rewrite_rules();
	}
}

?>
