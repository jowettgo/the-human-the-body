<?php
/**
 * redirects all users to 404 accessing the admin area like post and other pages inside the theme
 * once a user is logged in on the front and its the admin then and only then he can access the admin area
 * @method restrict_admin_area
 * @return redirect
 */
function restrict_admin_area() {
	$user = wp_get_current_user();
	$admin = new WP_User($user->ID);
	$allcaps = $user->get_role_caps();
	if($allcaps['administrator'] != 1 && stripos( $_SERVER['SCRIPT_URI'], 'wp-admin' ) !== false && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX )) :
			wp_redirect(get_site_url().'/404/');
		exit;
	endif;
}
/**
 * redirects all users accessing the wp-admin and wp-login.php to the 404 page
 * once a user is logged in on the front and its the admin then and only then he can access the admin area
 * @method restrict_wp_admin
 * @return redirect
 */
function restrict_wp_admin() {
	if ( ! current_user_can( 'manage_options' ) && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
		wp_redirect(get_site_url().'/404/');
	}
}
/* admin page */
add_action( 'admin_page_access_denied', 'restrict_admin_area', 1 );
/* areas of admin like users.php, plugins etc... */
add_action( 'admin_init', 'restrict_admin_area', 1 );
add_action( 'admin_page_access_denied', 'restrict_admin', 1 );
/* login area */
add_action( 'login_head', 'restrict_wp_admin', 1 );

 ?>
