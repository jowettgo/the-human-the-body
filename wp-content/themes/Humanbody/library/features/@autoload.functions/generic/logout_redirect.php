<?php
function logout_redirect($location = '') {
    /* check if user is logged out */
    if(!wp_get_current_user()->ID) :
        /* redirect if logged out */
        $location = $location ? $location : get_site_url();
        /* redirect */
    	wp_redirect( $location);
    endif;
}
?>
