<?php
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
/*
 Template Name: Member Message Details
 *
 * This is your custom page template. You can create as many of these as you need.
 * Simply name is "page-whatever.php" and in add the "Template Name" title at the
 * top, the same way it is here.
 *
 * When you create your page, you can just select the template and viola, you have
 * a custom page template to call your very own. Your mother would be so proud.
 *
 * For more info: http://codex.wordpress.org/Page_Templates
*/





/* ----------------------------------------------------------------------------------- */
/* redirect to community if user is not logged in */
logout_redirect(page('sign-in'));
/* ----------------------------------------------------------------------------------- */
/* adds a hook vor invalid room id */
do_action('invalid_room_id');
/* adds a hook to member premium pages */
do_action('member-premium-header-hook');


$mcr = new user_messages();
$room_id = $mcr->decrypt($_GET['hb']);
$room = $mcr->get_room($room_id);
/* add the message to the db and do a redirect to prevent multiple posting on refresh */
if($_POST['send'] && $_POST['reply-message'] && $room_id > 0) :
	$sent = $mcr->send($_POST['reply-message'], $room_id);
    wp_redirect( page('my-account-message-room').'?hb='.$_GET['hb'] );
endif;


?>
<?php get_header() ?>
<?php include('member/account/message-room.php') ?>
<a class="are-you-sure" href="#are-you-sure"></a>
<div class="hidden">
    <div id="are-you-sure" class="text-center">
        <h3>Are you sure?</h3>
        <p>you are about to remove this conversation</p>
        <a href="javascript:void()" class="btn remove-no">no</a>
        <a href="javascript:void()" class="btn remove-yes">yes</a>
    </div>
</div>
<?php get_footer() ?>
