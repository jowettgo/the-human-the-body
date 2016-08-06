<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.iquatic.com
 * @since      1.0.0
 *
 * @package    Hb_Membership_Features
 * @subpackage Hb_Membership_Features/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Hb_Membership_Features
 * @subpackage Hb_Membership_Features/admin
 * @author     iQuatic <contact@iquatic.com>
 */
class Hb_Membership_Features_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Hb_Membership_Features_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Hb_Membership_Features_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/hb-membership-features-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Hb_Membership_Features_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Hb_Membership_Features_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

        wp_enqueue_script('jquery-ui-sortable'); //https://developer.wordpress.org/reference/functions/wp_enqueue_script/
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/hb-membership-features-admin.js', array( 'jquery' ), $this->version, false );

	}


    /**
     * Adds the admin menu for this plugin
     */
    public function display_admin_menu() {
        //TODO: add a root "Human Body" admin menu to add into all site specific menus
		add_menu_page(
			'Membership Features', //$page_title
			'Membership', //$menu_title
			'manage_options', //$capability
			'hb-membership-features', //$menu_slug
			[$this, 'display_admin_page'], //$function
			'dashicons-editor-ul', //$icon_url
			'3.0' //$position number on menu from top
		);
	}


    /**
     * Render the actual admin page for this plugin
     */
    public function display_admin_page() {
        add_action( 'admin_post_hb-membership-features', 'post' );
        $action = empty( $_REQUEST['action'] ) ? '' : $_REQUEST['action'];
        if(empty($action)) {
            $this->get();
        } else {
            $this->post();
        }
	}


    public function get()
    {
        global $wpdb;
        $wpdb->membership = $wpdb->prefix . 'hb_membership_features';
        $results = $wpdb->get_results("SELECT * FROM $wpdb->membership ORDER BY `order` ASC");

        include 'partials/hb-membership-features-admin-display.php';

    }


    public function post()
    {
        echo 'aaaa';
    }


}
