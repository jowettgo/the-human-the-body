<?php
/* add hook to spinal primal, the first hook in the page, permits redirects */
add_filter('spinal-primal',  'spinal_sign_out');
/**
 * use this function with a redirect to further customise the logout built in wordpress
 * @method spinal_sign_out
 * @param  string          $redirect  url to redirect to
 * @return redirect
 */
function spinal_sign_out($redirect) {
    /* fallback link in case redirect is not set, it will go to sign out if that is not set either it will go to the index page */
    $signinpage = page('sign-in') ? page('sign-in') : get_site_url();
    $redirect = $redirect ? $redirect : $signinpage;
    /* check the action on the sign out action and the token needed to sign out */
    if($_GET['a'] == 'sign-out' && wp_verify_nonce($_GET['token'], 'sign-out')) :
        /* native logout function */
        wp_logout();
        /* redirect the user to where ever */
        wp_redirect( $redirect );
    endif;
}
/**
 * generate the sign out url needed to sign out the user
 * @method spinal_sign_out_url
 * @return string    return the signout url
 */
function spinal_sign_out_url() {
    $nonce = wp_create_nonce( 'sign-out' );
    return get_site_url().'?a=sign-out&token='.$nonce;
}
?>
