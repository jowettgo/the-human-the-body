<?php
/**
 * spinal recover password
 */
class spinal_recover_password
{

    /**
     * send recovery password email to the user
     * @method send_mail
     * @param  string     $email email to recover the password for
     * @return mixed           true or false based on success or fail or wp_error if the email does not exist
     */
    function send_mail($email) {
        /* email is validated and sanitized */
        if(filter_var(sanitize_text_field($email), FILTER_VALIDATE_EMAIL)) :
            /* main send email with the password for the user */
            return self::get_password_send_mail($email);
        /* invalid email */
        else :
            return false;
        endif;
    }
    /**
     * Handles sending password retrieval email to user.
     *
     * @global wpdb         $wpdb      WordPress database abstraction object.
     * @global PasswordHash $wp_hasher Portable PHP password hashing framework.
     *
     * @return bool|WP_Error True: when finish. WP_Error on error
     */
    function get_password_send_mail($email) {
    	global $wpdb, $wp_hasher;

    	$errors = new WP_Error();

    	if ( empty( $email ) ) {
    		$errors->add('empty_username', __('<strong>ERROR</strong>: Enter a username or e-mail address.'));
    	} elseif ( strpos( $email, '@' ) ) {
    		$user_data = get_user_by( 'email', trim( $email ) );
    		if ( empty( $user_data ) )
    			$errors->add('invalid_email', __('<strong>ERROR</strong>: There is no user registered with that email address.'));
    	} else {
    		$login = trim($email);
    		$user_data = get_user_by('login', $login);
    	}

    	/**
    	 * Fires before errors are returned from a password reset request.
    	 */
    	do_action( 'lostpassword_post' );

    	if ( $errors->get_error_code() )
    		return $errors;

    	if ( !$user_data ) {
    		$errors->add('invalidcombo', __('<strong>ERROR</strong>: Invalid username or e-mail.'));
    		return $errors;
    	}

    	// Redefining user_login ensures we return the right case in the email.
    	$user_login = $user_data->user_login;
    	$user_email = $user_data->user_email;

    	/**
    	 * Fires before a new password is retrieved.
    	 *
    	 * @param string $user_login The user login name.
    	 */
    	do_action( 'retreive_password', $user_login );

    	/**
    	 * Fires before a new password is retrieved.
    	 *
    	 * @param string $user_login The user login name.
    	 */
    	do_action( 'retrieve_password', $user_login );

    	/**
    	 * Filter whether to allow a password to be reset.
    	 *
    	 * @param bool true           Whether to allow the password to be reset. Default true.
    	 * @param int  $user_data->ID The ID of the user attempting to reset a password.
    	 */
    	$allow = apply_filters( 'allow_password_reset', true, $user_data->ID );

    	if ( ! $allow ) {
    		return new WP_Error( 'no_password_reset', __('Password reset is not allowed for this user') );
    	} elseif ( is_wp_error( $allow ) ) {
    		return $allow;
    	}

    	// Generate something random for a password reset key.
    	$key = wp_generate_password( 20, false );

    	/**
    	 * Fires when a password reset key is generated.
    	 *
    	 * @param string $user_login The username for the user.
    	 * @param string $key        The generated password reset key.
    	 */
    	do_action( 'retrieve_password_key', $user_login, $key );

    	// Now insert the key, hashed, into the DB.
    	if ( empty( $wp_hasher ) ) {
    		require_once ABSPATH . WPINC . '/class-phpass.php';
    		$wp_hasher = new PasswordHash( 8, true );
    	}
    	$hashed = time() . ':' . $wp_hasher->HashPassword( $key );
    	$wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user_login ) );

    	//$message = __('Someone requested a password reset on this account:') . "\r\n\r\n";
    	//$message .= network_home_url( '/' ) . "\r\n\r\n";
    	//$message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
    	//$message .= __('If this was a mistake, just ignore this email and nothing will happen.') . "\r\n\r\n";
    	//$message .= __('To reset your password, follow the link below:') . "\r\n\r\n";
    	//$message .= "<a href='".page('reset-password')."?action=rp&key=$key&login=" . rawurlencode($user_login) . "' target='_blank'>Reset Password</a>\r\n";

        $message = file_get_contents(ABSPATH.'/wp-content/themes/Humanbody/member/emails/recover.html');
        $message = str_ireplace('##USERNAME##', $user_login, $message);
        $recover_link = "<a href='".page('reset-password')."?action=rp&key=$key&login=" . rawurlencode($user_login) . "' target='_blank'>Reset Password</a>";
        $message = str_ireplace('##RESET##', $recover_link, $message);

    	if ( is_multisite() )
    		$blogname = $GLOBALS['current_site']->site_name;
    	else
            $blogname = get_option('blogname');

    	$title = sprintf( __('Password Reset on %s'), $blogname );

    	/**
    	 * Filter the subject of the password reset email.
    	 *
    	 * @param string $title Default email title.
    	 */
    	$title = apply_filters( 'retrieve_password_title', $title );

    	/**
    	 * Filter the message body of the password reset mail.
    	 *
    	 * @param string  $message    Default mail message.
    	 * @param string  $key        The activation key.
    	 * @param string  $user_login The username for the user.
    	 * @param WP_User $user_data  WP_User object.
    	 */
    	$message = apply_filters( 'retrieve_password_message', $message, $key, $user_login, $user_data );

    	if ( $message && !wp_mail( $user_email, wp_specialchars_decode( $title ), $message ) )
    		wp_die( __('The e-mail could not be sent.') . "<br />\n" . __('Possible reason: your host may have disabled the mail() function.') );
        /* all good and fancy */
    	return true;
    }

}















































 ?>
