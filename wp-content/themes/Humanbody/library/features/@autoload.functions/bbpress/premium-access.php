<?php
/**
 * redirect user if non premium on the meetings forum and topics
 */
add_action('bbp-meetings','non_premium_redirect' );
function non_premium_redirect() {
    $user = new user_info();
	$u = $user->user_info;
	$current_date = strtotime(date('Y-m-d H:i:s'));
	$date = $u['since'];
	$date = strtotime(date("Y-m-d", strtotime($date)) . " +30 days");
	$since = strtotime(date("Y-m-d",$date));
    if(!$user->premium && ($current_date > $since)) :
    	wp_redirect(page('membership'));
        die();
    endif;
}
 ?>
