<?php

/**
 * custom customizable send mail to user
 */
class spinal_send_mail
{
    /* customizable from email */
    public $from_mail = '';
    /* customizable from name */
    public $from_name = '';

    /**
     * send mail with a custom from name email and message
     * @method send
     * @param  string    $email      (optional) the email to send it to, if false, it will fallback to admin email or the email set up with better email for wordpress plugin
     * @param  string    $title      mail subject
     * @param  string    $message    mail content
     * @return boolean
     */
    public function send($email, $title, $message) {
        add_filter( 'spinal_mail_from', array($this,'spinal_wp_mail_from') );
        add_filter( 'wp_mail_from_name', array($this,'spinal_wp_mail_from_name') );
        /* Better email plugin support
         */
        $wpbe = get_option('wpbe_options');
            $wpbe_email = $wpbe['from_email'];
            $wpbe_name = $wpbe['from_name'];

        /* email fallback if false */
        $email = $email ? $email :
                            $wpbe_email ? $wpbe_email : get_option('admin_email');



        /* check if email was sont or not */
        if(!wp_mail( $email, wp_specialchars_decode( $title ), $message )) :
            return false;
        endif;
        return true;
    }

    public function spinal_wp_mail_from( $original_email_address ) {
        //Make sure the email is from the same domain
        //as your website to avoid being marked as spam.
        return $this->from_email ? $this->from_email : get_option('admin_email');
    }
    public function spinal_wp_mail_from_name( $original_email_from ) {
        return $this->from_name ? $this->from_name : get_option('blogname');
    }
}


 ?>
