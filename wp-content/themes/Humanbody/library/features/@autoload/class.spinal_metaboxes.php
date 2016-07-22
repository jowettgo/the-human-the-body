<?php


/**
 * Spinal Custom metaboxes Class [running with CMB2]
 * @version 0.1.2
 * @author: Marius Moldovan
 *
 * usage :
 * $metaboxes = array(
 *	array(
 *        'name' => __( 'Longitude', 'cmb2' ),
 *         'desc' => __( 'field description (optional)', 'cmb2' ),
 *         'id'   => 'test',
 *         'type' => 'text',
 *         // 'repeatable' => true,
 *     ),
 *	array(
 *        'name' => __( 'Longitude', 'cmb2' ),
 *         'desc' => __( 'field description (optional)', 'cmb2' ),
 *         'id'   => 'test2',
 *         'type' => 'text',
 *         // 'repeatable' => true,
 *     )
 *     ..
 * );
 * $shops = new spinal_metaboxes(); // call our class
 * $shops->metaboxes = $metaboxes;  // feed the metaboxes array
 * $shops->prefix = "post_";		// create our prefix
 * $shops->type = 'post';			// show the metaboxes on the post type in admin
 * $shops->hooks();					// call the hooks
 *
 *
 *
  */
class spinal_metaboxes {
	public $metaboxes = '';
	public $prefix = 'sp_';
	public $title = 'Spinal Metabox';

	/**
	 * [$postType single or list of post types ]
	 * @var string || array
	 */
	public $type = 'post';

	function hooks() {
		add_action( 'cmb2_init', array($this, 'add_metaboxes') );
		if(!is_array($this->type) && $this->type == 'category') :
			add_filter('cmb2-taxonomy_meta_boxes', array($this, 'add_cat_metaboxes'));
		elseif(is_array($this->type)) :
			if(in_array('category', $this->type)) :
				add_filter('cmb2-taxonomy_meta_boxes', array($this, 'add_cat_metaboxes'));
			endif;
		endif;
	}
	function add_metaboxes() {
		$metaboxes = $this->metaboxes;
		$prefix = $this->prefix;
		/**
		 * Initiate the metabox
		 */
		$cmb = new_cmb2_box( array(
			'id'            => $prefix,
			'title'         => __( $this->title),
			'object_types'  => is_array($this->type) ? $this->type : array( $this->type ), // Post type
			'context'       => 'normal',
			'priority'      => 'high',
			'show_names'    => true, // Show field names on the left
			// 'cmb_styles' => false, // false to disable the CMB stylesheet
			// 'closed'     => true, // Keep the metabox closed by default
		) );
		/* loop through each Metabox and add them */

		/* add metaboxes */
		if($this->metaboxes) {

			foreach ($this->metaboxes as $key => $input) {
				/* group metaboxes, added support for multiple repeating groups in same array of metaboxes */
				if($key === 'group' || $key === 'group[0]' || $key === 'group[1]' || $key === 'group[2]' || $key === 'group[3]' || $key === 'group[4]' || $key === 'group[5]' || $key === 'group[6]' || $key === 'group[7]') {
					/* loop through each group */
					foreach ($this->metaboxes[$key] as $id => $group) {
						/* loop through each group metaboxes */
						$setup = $cmb->add_field($group['setup']);
						foreach ($group as $g => $meta) {
							if($g !== 'setup')
								$cmb->add_group_field( $setup, $meta);
						}
					}
				}
				else {
					/* this is not a repeating group so its just a normal metabox */
					$cmb->add_field($input);
				}
			}
		}
		/* end add metaboxes */
	}
	public function add_cat_metaboxes() {
		$metaboxes = $this->metaboxes;
		$prefix = $this->prefix;
		/**
		 * Sample metabox to demonstrate each field type included
		 */
		$meta_boxes['spinal'.$prefix] = array(
			'id'            => $prefix,
			'title'         =>  __( $this->title),
			'object_types'  => array('category'), // Post type
			'context'       => 'normal',
			'priority'      => 'high',
			'show_names'    => true, // Show field names on the left
			// 'cmb_styles' => false, // false to disable the CMB stylesheet
			'fields'        => $metaboxes
		);
		return $meta_boxes;
	}
}
/**
 * Spinal Theme Options constructor Class [running with CMB2]
 * @version 0.1.4
 * @author: the Codewritter
 *
 *
 * repeating groups can be added with 'group[0]' => array(...), 'group[1]' => array(...) ...
 */
class spinal_constructor {

	/**
 	 * Option key, and option page slug
 	 * @var string
 	 */
	public $key = 'spinal_options';

	public $subpage = false;
	/**
	 * Option Key used for displaying the title on the page
	 * @var string
	 */

	public $optionsTitle = 'Settings';
	/**
	 * icon used to display the settings
	 * @var string
	 */
	public $icon = '';

	/**
	 * position in the menu for the page
	 * @var integer
	 */
	public $index = 100;
	/**
	 * add input metaboxes inside the inputs var, array of input arrays
	 * @var array
	 */
	public $metaboxes = '';
	/**
 	 * Options page metabox id
 	 * @var string
 	 */
	public $metabox_id = 'spinal_option_metabox';

	/**
	 * Options Page title
	 * @var string
	 */
	protected $title = '';

	/**
	 * Options Page hook
	 * @var string
	 */
	protected $options_page = '';

	/**
	 * Constructor
	 * @since 0.1.0
	 */
	public function __construct() {
		// clear our metaboxes
		$this->metaboxes = array();
		// Set our title
		$this->title = __( $this->optionsTitle, 'spinal' );
	}
	function __destruct() {
		$this->metaboxes = array();
	}
	/**
	 * Initiate our hooks
	 * @since 0.1.0
	 */
	public function hooks() {
		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		add_action( 'cmb2_init', array( $this, 'add_options_page_metabox' ) );
	}


	/**
	 * Register our setting to WP
	 * @since  0.1.0
	 */
	public function init() {
		register_setting( $this->key, $this->key );
	}

	/**
	 * Add menu options page
	 * @since 0.1.0
	 */
	public function add_options_page() {
		$slug = $this->key;
		$function = array( $this, 'admin_page_display' );
		$capability = 'delete_others_pages';
		$Menutitle =  $this->optionsTitle;
		$pageTitle = $this->optionsTitle;
		if(!$this->subpage) :
			$this->options_page = add_menu_page( $pageTitle,$Menutitle, $capability, $slug, $function, $this->icon, $this->index );
		else :
			$this->options_page = add_submenu_page( $this->subpage, $pageTitle, $Menutitle, $capability, $slug, $function );
		endif;
		// Include CMB CSS in the head
		add_action( "admin_print_styles-{$this->options_page}", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );
	}

	/**
	 * Admin page markup. Mostly handled by CMB2
	 * @since  0.1.0
	 */
	public function admin_page_display() {
		?>
		<div class="wrap cmb2-options-page <?php echo $this->key; ?>">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<?php cmb2_metabox_form( $this->metabox_id, $this->key ); ?>
		</div>
		<?php
	}

	/**
	 * Add the options metabox to the array of metaboxes
	 * @since  0.1.0
	 */
	function add_options_page_metabox() {

		$cmb = new_cmb2_box( array(
			'id'         => $this->metabox_id,
			'hookup'     => false,
			'cmb_styles' => false,
			'show_on'    => array(
				// These are important, don't remove
				'key'   => 'options-page',
				'value' => array( $this->key)
			),
		) );

		/* metaboxes are added here */
		if($this->metaboxes) {

			foreach ($this->metaboxes as $key => $input) {
				/* group metaboxes, added support for multiple repeating groups in same array of metaboxes */
				if($key === 'group' || $key === 'group[0]' || $key === 'group[1]' || $key === 'group[2]' || $key === 'group[3]' || $key === 'group[4]' || $key === 'group[5]' || $key === 'group[6]' || $key === 'group[7]') {
					/* loop through each group */
					foreach ($this->metaboxes[$key] as $id => $group) {
						/* loop through each group metaboxes */
						$setup = $cmb->add_field($group['setup']);
						foreach ($group as $g => $meta) {
							if($g !== 'setup')
								$cmb->add_group_field( $setup, $meta);
						}
					}
				}
				else {
					/* this is not a repeating group so its just a normal metabox */
					$cmb->add_field($input);
				}
			}
		}
	}

	/**
	 * Public getter method for retrieving protected/private variables
	 * @since  0.1.0
	 * @param  string  $field Field to retrieve
	 * @return mixed          Field value or exception is thrown
	 */
	public function __get( $field ) {
		// Allowed fields to retrieve
		if ( in_array( $field, array( 'key', 'metabox_id', 'title', $this->key ), true ) ) {
			return $this->{$field};
		}
		throw new Exception( 'Invalid property: ' . $field );
	}
}
/**
 * Metabox for Children of Parent ID
 * @author Kenneth White (GitHub: sprclldr)
 * @link https://github.com/WebDevStudios/CMB2/wiki/Adding-your-own-show_on-filters
 *
 * @param bool $display
 * @param array $meta_box
 * @return bool display metabox
 */
function be_metabox_show_on_child_of( $display, $meta_box ) {
    if ( ! isset( $meta_box['show_on']['key'], $meta_box['show_on']['value'] ) ) {
        return $display;
    }

    if ( 'child_of' !== $meta_box['show_on']['key'] ) {
        return $display;
    }

    $post_id = 0;

    // If we're showing it based on ID, get the current ID
    if ( isset( $_GET['post'] ) ) {
        $post_id = $_GET['post'];
    } elseif ( isset( $_POST['post_ID'] ) ) {
        $post_id = $_POST['post_ID'];
    }

    if ( ! $post_id ) {
        return $display;
    }

    $pageids = array();
    foreach( (array) $meta_box['show_on']['value'] as $parent_id ) {
        $pages = get_pages( array(
            'child_of'    => $parent_id,
            'post_status' => 'publish,draft,pending',
        ) );

        if ( $pages ) {
            foreach( $pages as $page ){
                $pageids[] = $page->ID;
            }
        }
    }
    $pageids_unique = array_unique( $pageids );

    return in_array( $post_id, $pageids_unique );
}
add_filter( 'cmb2_show_on', 'be_metabox_show_on_child_of', 10, 2 );
