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

class Hb_Membership_Features_Admin {


	private $plugin_name;
	private $version;
	public $items;
	public $base_url;


	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;

		global $wpdb;
		$wpdb->membership = $wpdb->prefix . 'hb_membership_features';
		$this->items = $wpdb->get_results("SELECT * FROM $wpdb->membership ORDER BY `order` ASC");

		$this->base_url = get_site_url() . '/wp-admin/admin.php?page=hb-membership-features';

//		add_action( 'wp_ajax_nopriv_post', array( $this, 'post' ) );
//		add_action( 'wp_ajax_post', array( $this, 'post' ) );
	}


	public function enqueue_styles() {
		wp_enqueue_style( 'prefix-font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css', array(), '4.0.3' );
        wp_enqueue_style( 'bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/hb-membership-features-admin.css', array(), $this->version, 'all' );
	}


	public function enqueue_scripts() {
        wp_enqueue_script('jquery-ui-sortable'); //https://developer.wordpress.org/reference/functions/wp_enqueue_script/
        wp_enqueue_script( 'bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js' );
        wp_enqueue_script( 'tinymce-js', 'https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.4.1/tinymce.min.js' );
//        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/hb-membership-features-admin.js', array( 'jquery' ), $this->version, false );
	}


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


    public function display_admin_page() {
        echo $this->routes();
    }

    public function routes()
    {
        //TODO: protect routes, post&delete
        if($_REQUEST['edit']) return $this->edit();
        if($_REQUEST['delete']) return $this->delete();
        if($_REQUEST['add']) return $this->add();

        if(isset($_REQUEST['order'])) $this->updateOrder();
        if(isset($_POST['action']) & $_POST['action']=='POST_ITEM') return $this->postItem();

        return $this->get();
    }


    public function get()
    {
		$results = $this->items;
		$base_url = $this->base_url;
        include 'partials/hb-membership-features-admin-display.php';
    }


    public function postItem()
    {
        global $wpdb;
        $wpdb->membership = $wpdb->prefix . 'hb_membership_features';
        $item = [
            'title' => $_POST['item_title'],
            'link' => isset($_POST['item_link']) & $_POST['item_link']!='' ? $_POST['item_link'] : null,
            'content' => isset($_POST['item_content']) & $_POST['item_content']!='' ? $_POST['item_content'] : null,
            'icon' => isset($_POST['item_icon']) & $_POST['item_icon']!='' ? $_POST['item_icon'] : null
        ];
        if(isset($_POST['item_id']) & $_POST['item_id'] != '') {
            $wpdb->update($wpdb->membership, $item, ['id'=>$_POST['item_id']]);
        } else {
            $wpdb->insert($wpdb->membership, $item);
        }

        wp_redirect($this->base_url);
    }



	public function edit()
	{
        global $wpdb;
        $id = $_REQUEST['edit'];
        $item = $wpdb->get_row("SELECT * FROM $wpdb->membership WHERE id=$id");
        $base_url = $this->base_url;
		include 'partials/edit_form.php';
	}

	public function delete()
	{
        $id = $_REQUEST['delete'];
		echo "delete $id";
        global $wpdb;
        $wpdb->delete($wpdb->prefix . 'hb_membership_features', array( 'ID' => $id ) );
        wp_redirect($this->base_url);
	}


    protected function updateOrder() {
        global $wpdb;
        $order = $_REQUEST['order'];
        $wpdb->membership = $wpdb->prefix . 'hb_membership_features';
        $ord = explode(',', $order);
        $i = 1;
        foreach ($ord as $o) {
            $wpdb->update($wpdb->membership, ['order'=>$i], ['id'=>$o]);
            $i += 1;
        }
    }

    public function add()
    {
        $item = null;
        $base_url = $this->base_url;
        include 'partials/edit_form.php';
    }


}
