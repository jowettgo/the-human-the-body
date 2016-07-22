<?php
/**
 * adds support for both email and username login
 * @method spinal_email_password_login
 * @param  string                      $user_email login email or username
 * @param  string                      $password       login password
 * @param  string                      $redirect   redirect url
 * @return mixed                       returns redirect on success or error on fail
 */
function spinal_email_password_login($user_email, $password, $redirect) {
    if(stripos( $user_email, '@' ) !== false) :
        remove_filter('authenticate', 'wp_authenticate_username_password', 20);
        add_filter('authenticate', 'spinal_email_login', 20, 3);
        return spinal_user_login($user_email, $password, $redirect);
    else :
        return spinal_user_login($user_email, $password, $redirect);
    endif;
}
/**
 * wordpress sign in user that switches between username and email
 * @method spinal_user_login
 * @param  string                      $user_email login email or username
 * @param  string                      $password       login password
 * @param  string                      $redirect   redirect url
 * @return mixed                       returns redirect on success or error on fail
 */
function spinal_user_login($user_email, $password, $redirect = false) {
	$creds = array();
    /* setup credentials */
	$creds['user_login'] = $user_email;
	$creds['user_password'] = $password;
	$creds['remember'] = true;
    /* log in the user */
	$user = wp_signon( $creds, false );
    /* check the result */
	if ( is_wp_error($user) ) :
        /* return error */
		return str_replace('ERROR: Invalid username. Lost your password?', 'Invalid Username and/or password!', strip_tags($user->get_error_message()));
    else :
        $roles = $user->get_role_caps();
        if(isset($roles['blocked']) && $roles['blocked'] === 1) :
            wp_logout();
            return 'Your account has been blocked';
        endif;

       if (get_user_meta( $user->ID, 'has_to_be_activated', true ) != false) {
            wp_logout();
            return 'Your account has to be activated';
       }

        /* redirect */
        if($redirect) :
            wp_redirect($redirect);
            exit;
        endif;
        return $user->ID;
    endif;
}

/**
 * email login support
 * @method spinal_email_login
 * @param  string                      $user           user object passed by the wordpress hook authenticate
 * @param  string                      $password       login password
 * @param  string                      $email          user email to match against
 * @return mixed                       returns user on success or error on fail
 */
function spinal_email_login($user, $email, $password) {
    //Check for empty fields
    if(empty($email) || empty ($password)){
        //create new error object and add errors to it.
        $error = new WP_Error();
        if(empty($email)){ //No email
            $error->add('empty_username', __('<strong>ERROR</strong>: Email field is empty.'));
        }
        else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ //Invalid Email
            $error->add('invalid_username', __('<strong>ERROR</strong>: Email is invalid.'));
        }
        if(empty($password)){ //No password
            $error->add('empty_password', __('<strong>ERROR</strong>: Password field is empty.'));
        }
        return $error;
    }
    //Check if user exists in WordPress database
    $user = get_user_by('email', filter_var($email, FILTER_VALIDATE_EMAIL));
    //bad email
    if(!$user){
        $error = new WP_Error();
        $error->add('invalid', __('<strong>ERROR</strong>: Either the email or password you entered is invalid.'));
        return $error;
    }
    else{ //check password
        if(!wp_check_password($password, $user->user_pass, $user->ID)){ //bad password
            $error = new WP_Error();
            $error->add('invalid', __('<strong>ERROR</strong>: Either the email or password you entered is invalid.'));
            return $error;
        } else{
            $roles = $user->get_role_caps();
            if(isset($roles['blocked']) && $roles['blocked'] === 1) :
                wp_logout();
                return 'Your account has been blocked';
            endif;
            return $user; //passed
        }
    }
}

function check_user_login($login) {
    global $wpdb;
    $table = $wpdb->prefix.'users';

    $query = "SELECT * FROM $table WHERE `user_login`='$login' OR `user_email`='$login'";
    $rows = $wpdb->get_results($query);
    $user = $rows[0];

    if($user->ID > 0) {
        return $login;
    }
    return false;
}
