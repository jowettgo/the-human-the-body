<?php
/* user mail exists ajax logged in and logged out */
add_action( 'wp_ajax_nopriv_ume', 'ajax_user_mail_exists' );
add_action( 'wp_ajax_ume', 'ajax_user_mail_exists' );
/* user name exists or not */
add_action( 'wp_ajax_nopriv_une', 'ajax_user_name_exists');
add_action( 'wp_ajax_une', 'ajax_user_name_exists');


function ajax_user_mail_exists() {
    $data  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    $mail_exists = spinal_register_user::exists($data['mail']);
    echo $mail_exists ? 'true' : 'false';
    die();
}
function ajax_user_name_exists() {
    $username_exists = spinal_register_user::get_user_by_field('user_nicename', 'design19');
    echo $username_exists->ID ? 'true' : 'false';
    die();
}

?>
