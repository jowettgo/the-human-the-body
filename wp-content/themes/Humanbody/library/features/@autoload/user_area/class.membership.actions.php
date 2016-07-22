<?php
/**
 *
 */
class membership
{
    /**
     * $ID logged in user id
     * @var integer
     */
    private $ID;
    /**
     * $user contains the current user
     * @var [type]
     */
    private $user;

    /* store the current user and id */
    function __construct()
    {
        $user = wp_get_current_user();
        $this->user = $user;
        $this->ID = $user->ID;
    }
    /**
     * method for sending email on expire
     * @method expired
     * @return NULL
     */
    public function expired_send_mail()
    {
        /* get user meta */
        $meta = get_user_meta($this->ID);
        /* get the sent mail flag */
        $sent_mail_flag = $meta['sent-mail'][0] == 'sent' ? false : true;
        /* if mail was not sent then send an email */
        if(!$sent_mail_flag) :
            $emailaddress = $this->user->email;
            $to = $emailaddress;
        	$email = new spinal_send_mail();
            $options = get_option('email-options');
            $message = $options['expired-membership'];
            $title = 'Renew Your Membership on '.get_bloginfo( 'name' );
            //$sent = $email->send($to, $title, $message);
            if($sent) :
                update_user_meta( $this->ID, 'sent-mail', 'sent');
            endif;
        endif;
    }
    public function premium_send_mail()
    {
        /* get user meta */
        $meta = get_user_meta($this->ID);
        /* get the sent mail flag */
        $sent_mail_flag = $meta['sent-mail'][0] == 'sent' ? false : true;
        /* if mail was not sent then send an email */
        if(!$sent_mail_flag) :
            $emailaddress = $this->user->email;
            $to = $emailaddress;
            $email = new spinal_send_mail();
            $options = get_option('email-options');
            $message = $options['premium-membership'];
            $title = 'Membership '.get_bloginfo( 'name' );
            $sent = $email->send($to, $title, $message);
            if($sent) :
                update_user_meta( $this->ID, 'sent-premium', 'sent');
            endif;
        endif;
    }
}
$membership = new membership();
add_action( 'expired-membership', array($membership, 'expired_send_mail'));
add_action( 'premium-membership', array($membership, 'premium_send_mail'));
 ?>
