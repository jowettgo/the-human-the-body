<?php
/**
 * takes all the unfound pages on wordpress and redirects the user to a 404 url
 * this adds the sense that any unfound page is not found permitting the redirect on wp-admin and wp-login.php to redirect on the 404 page
 *
 * @method redirect_404
 * @return redirect
 */
function redirect_404() {
	global $wp_query;

	if(isset($wp_query->query_vars['category_name'])) :
	    wp_redirect(page('404'));
	elseif($wp_query->query_vars['name'] != '404') :
		wp_redirect(page('404'));
	endif;
}
/* this hooks up inside the do_action('404-redirect') added on the 404 page */
add_action( '404-redirect', 'redirect_404', 1 );
?>
