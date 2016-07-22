<?php


/**
 *
 */
class spinal_reset_password
{
    public function get_cookie() {
        $rp_cookie = 'wp-resetpass-' . COOKIEHASH;
        return $_COOKIE[$rp_cookie];
    }
    public function reset()
    {
        if($_GET['action'] == 'rp' || $_GET['action'] == 'resetpass') :

        	list( $rp_path ) = explode( '?', wp_unslash( $_SERVER['REQUEST_URI'] ) );
        	$rp_cookie = 'wp-resetpass-' . COOKIEHASH;

        	if ( isset( $_GET['key'] ) ) {

        		$value = sprintf( '%s:%s', wp_unslash( $_GET['login'] ), wp_unslash( $_GET['key'] ) );
        		setcookie( $rp_cookie, $value, 0, $rp_path, COOKIE_DOMAIN, is_ssl(), true );
        		wp_safe_redirect( remove_query_arg( array( 'key', 'login' ) ) );
        		exit;
        	}


        	if ( isset( $_COOKIE[ $rp_cookie ] ) && 0 < strpos( $_COOKIE[ $rp_cookie ], ':' ) ) {
        		list( $rp_login, $rp_key ) = explode( ':', wp_unslash( $_COOKIE[ $rp_cookie ] ), 2 );

        		$user = check_password_reset_key( $rp_key, $rp_login );


                /* check for rp_key */
        		if ( isset( $_POST['pass1'] ) && ! hash_equals( $rp_key, $_POST['rp_key'] ) ) {
        			$user = false;
        		}
        	} else {
        		$user = false;
        	}


        	if ( ! $user || is_wp_error( $user ) ) {


        		setcookie( $rp_cookie, ' ', time() - YEAR_IN_SECONDS, $rp_path, COOKIE_DOMAIN, is_ssl(), true );
        		if ( $user && ($user->get_error_code() === 'expired_key' || $user->get_error_code() === 'invalidkey') ) :
        			wp_redirect( site_url( 'reset-password/?action=rp&error=expiredkey' ) );
                    exit;
        		else :
        			//wp_redirect( site_url( 'reset-password/?action=rp&error=invalidkey' ) );
        		    //exit;
        		     //deg($user);
                endif;
        	}


        	$errors = new WP_Error();

        	if ( isset($_POST['pass1']) && $_POST['pass1'] != $_POST['pass2'] ) {
        		$errors->add( 'password_reset_mismatch', __( 'The passwords do not match.' ) );
        		$messages[] = 'The passwords do not match.';
            }
        	/**
        	 * Fires before the password reset procedure is validated.
        	 *
        	 * @param object           $errors WP Error object.
        	 * @param WP_User|WP_Error $user   WP_User object if the login and reset key match. WP_Error object otherwise.
        	 */

        	do_action( 'validate_password_reset', $errors, $user );
        	if ($user->user_email && ( ! $errors->get_error_code() ) && isset( $_POST['pass1'] ) && !empty( $_POST['pass1'] ) ) {
        		$sent = $this->reset_password($user, $_POST['pass1']);
        		setcookie( $rp_cookie, ' ', time() - YEAR_IN_SECONDS, $rp_path, COOKIE_DOMAIN, is_ssl(), true );
        		$messages[] = 'Your password has been reset';
                if($sent == true) :
                    $messages[] = 'A mail confirmation has been sent to your email.';
                endif;
        	}
            return $messages;
        endif;
    }
    /**
     * Handles resetting the user's password.
     *
     * @param object $user     The user
     * @param string $new_pass New password for the user in plaintext
     */
    private function reset_password($user, $new_pass)
    {

    	/**
    	 * Fires before the user's password is reset.
    	 *
    	 * @since 1.5.0
    	 *
    	 * @param object $user     The user.
    	 * @param string $new_pass New user password.
    	 */
    	do_action( 'password_reset', $user, $new_pass );

        /* set the password */
    	wp_set_password( $new_pass, $user->ID );

    	update_user_option( $user->ID, 'default_password_nag', false, true );

        /* send mail to user and notify him */
        $to = $user->user_email;
        $email = new spinal_send_mail();

        $signInLink = page('sign-in');
        $title = 'Password Reset | Humanbody';
        $message .= 'Hi '.$user->display_name.', we just wanted you to know that your password has been reset to: '.$new_pass."\r\n\r\n";
        $message .= 'Sign in by following the link below'."\r\n";
        $message .= '<a href="'.$signInLink.'" style="margin:20px 0px;float:left;border: 1px solid #c49460;background: #c49460; padding:10px 20px;color:#fff; border-radius:4px;text-transfromt:uppercase;" target="_blank">Sign In</a>'."\r\n";
        $success = $email->send($to, $title, $message);
        if($success) :
        	return true;
        endif;
        return false;
    }
}


 ?>
