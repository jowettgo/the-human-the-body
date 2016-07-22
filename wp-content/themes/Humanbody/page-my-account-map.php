<?php
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
/*
 Template Name: Member Map
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
/* adds a hook to member premium pages */
do_action('member-premium-header-hook');
do_action('member-map');
?>
<?php get_header() ?>
<?php include('member/account/map.php') ?>
<?php get_footer() ?>
