<?php
/**
 * [dashboard_redirect redirect user from dashboard]
 * @method dashboard_redirect
 * @param  [type]             $url [description]
 * @return [type]                  [description]
 */
function dashboard_redirect($url) {
    global $current_user;
    //echo __dashboard_redirect;
    // is there a user ?
    if(isset($current_user) && is_array($current_user->roles)) {
        // check, whether user has the author role:
        //if($current_user->roles != "admin") {
             $url = 'wp-admin/'.__dashboard_redirect;
        //}
        return $url;
    }
    else {
        return $url;
    }
}
add_filter('login_redirect', 'dashboard_redirect');

function remove_dashboard() {
    if(!is_super_admin()) {
        remove_menu_page('index.php');
        remove_menu_page('edit-comments.php');
        remove_menu_page( 'themes.php' );
        remove_menu_page('tools.php');
        remove_menu_page('profile.php');
        remove_submenu_page( 'themes.php', 'customize.php' );

    }
    //remove_menu_page('edit-comments.php');
    remove_submenu_page( 'themes.php', 'nav-menus.php' );
    add_object_page('menu', 'Menu', 'edit_pages', 'nav-menus.php', '', 'dashicons-menu');
    // get the the role object
    $role_object = get_role('editor');

    // add $cap capability to this role object
    $role_object->add_cap('edit_theme_options');
}
add_filter("admin_menu", "restructure_menu", 999);
function restructure_menu() {
    global $menu;


    /* Menu options page is at 26 */
    reorder_index(26,3);
    /* media page is at 10 */
    //add_admin_menu_separator(9998); // add separator
    //add_admin_menu_separator(9999); // add separator
    reorder_index(10, 10000);

    // posts
    //reorder_index(5, 6);

    // pages
    reorder_index(20, 4);
    // profile
    //reorder_index(70, 19004);



}
/**
 * add separator in admin menu section at specified position
 * @method add_admin_menu_separator
 * @param  [int] $position [index in the menu for the seaparator]
 */
function add_admin_menu_separator( $position ) {
	global $menu;
	$menu[ $position ] = array(
		0	=>	'',
		1	=>	'read',
		2	=>	'separator' . $position,
		3	=>	'',
		4	=>	'wp-menu-separator'
	);
}
function reorder_index($old, $new) {
    global $menu;
    $menu[$new] = $menu[$old];
    unset($menu[$old]);
}

?>
