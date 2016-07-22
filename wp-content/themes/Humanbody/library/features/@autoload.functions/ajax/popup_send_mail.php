<?php

/* Contact ajax */
add_action( 'wp_ajax_nopriv_send_mail', 'send_mail_callback' );
add_action( 'wp_ajax_send_mail', 'send_mail_callback' );


function send_mail_callback() {

    /* Send email if using cotact form submit */
	if(isset($_POST['fullname']) && $_POST['email'] && $_POST['message'] && $_POST['send'] == 'send_mail') {
        $mailContent = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

		/* subject */
		$title = 'Contact | Humanbody';
        /* Headers */
		$email = new spinal_send_mail();
		/* Sender Name */
        $email->from_name = $mailContent['fullname'];
        /* Sender Email */
        $email->from_email = $mailContent['email'];
		/* Mail Content */
        $message .= $mailContent['fullname'] . ' "'.$mailContent['email'].'" sent a mail : <br/><br/>'.$mailContent['message'];

		/* email to send it to is set to false, and it fallback to the admin email  */
        $success = $email->send($email = false, $title, $message);
        if($success) :
            $email = array($mailContent['fullname'],  $mailContent['email'], $mailContent['message']);
        	echo '<div class="mail-ajax-message">Thank you for contacting us, <br/> we will get back to you shortly.</div>';
            do_action('contact-observer', $email);
			exit();
        endif;
        echo '<div class="mail-ajax-message">We are sorry, <br/>here was an error sending this email.</div>';
		exit();

    }
    else {

    }
	exit();
}

?>
