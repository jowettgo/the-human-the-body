<?php
/**
 * redirect to location if user is logged in
 * @method login_redirect
 * @param  string         $location  location to redirect to
 * @return null
 */
function login_redirect($location = '') {
    /* check if user is logged in */
    if(wp_get_current_user()->ID) :
        /* fallback location */
    	$location = $location ? $location : get_site_url();
        /* redirect */
    	wp_redirect( $location);
    endif;
}
?>
