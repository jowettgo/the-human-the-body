<?php

/**
 * object handler for the user registration
 */
class spinal_register_user
{
    /**
     * id of the new registered user
     * @var [type]
     */
    public $ID;
    /**
     * registers an user and then add all the meta from the form unto the user
     * @method register
     * @return mixed                returns error array on fail or redirect on success
     */
    public function register()
    {


        /* register email */
        $email = isset($_POST['user_email']) ? $_POST['user_email'] : false;
        /* check if the user exists in the database to begin with */
        if(!self::exists($email)) :
            /* register action, returns error object on fail*/
            /* validate data */
            $email = isset($_POST['email']) ? $_POST['email'] : false;
            $fullname = isset($_POST['fullname']) ? $_POST['fullname'] : false;
            $password = isset($_POST['password']) ? $_POST['password'] : false;


            if($email && $fullname && $password) :
                $name_array = explode(' ',$_POST['fullname']);
                $user = array(
                    'user_login' => str_replace(' ', '', $_POST['fullname']),
                    'user_pass' => $_POST['password'],
                    'user_email' => $_POST['email'],
                    'first_name' => $name_array[0],
                    'last_name' => $name_array[1],
                    'role' => 'premium_member'
                    );
                $user_id = wp_insert_user( $user );

                /* if not an error its all good and happy in life */
                if ( !is_wp_error($user_id) ) :
                    //create activation key
                    $code = sha1( $user_id . time() );
                    $activation_page = page('activate-account');
                    $activation_link = add_query_arg( array( 'key' => $code, 'u' => $user_id ), $activation_page);

                    $this->ID = $user_id;
                    /* add the meta */
                    $data = array(
                        'avatar' => $_POST['avatar'],
                        'country' =>  preg_replace('/[^a-z\s\-]/i', '', $_POST['country']),
                        'city' =>  preg_replace('/[^a-z\s\-]/i', '', $_POST['city']),
                        'gender' =>$_POST['gender'],
                        'about' => $_POST['bio'],
                        'birth-date' => $_POST['birth-date'],
                        'affections' => $_POST['affections'],
                        'trial_premium' => time(),
                        'has_to_be_activated' => $code
                    );
                    $this->add_meta($data);

                    $headers  = "From: The Human The Body " . '<no-reply@thehumanthebody.com>' . "\r\n";
                    $headers .= "Reply-To: ". 'no-reply@thehumanthebody.com' . "\r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";


//                    $body_mail = '<!DOCTYPE HTML>
//                    <html>
//                    <head>
//                      <title>Humanbody account validation</title>
//                    </head>
//                    <body>
//                      <h1>Humanbody account validation</h1>
//                      <br><br>
//                      <p>
//                       Hello, you registered on humanbody website with this email. Please validate your account here:
//                       </p>
//
//                       <p><a href="'.$activation_link.'">'.$activation_link.'</a></p>
//                    </body>
//                    </html>
//                    ';
                    $body_mail = file_get_contents(ABSPATH.'/wp-content/themes/Humanbody/member/emails/activation.html');
                    $body_mail = str_ireplace('##USERNAME##', $_POST['fullname'], $body_mail);
                    $body_mail = str_ireplace('##ACTIVATION##', $activation_link, $body_mail);

//                    add_filter( 'wp_mail_from_name', function($name) { return 'The Human The Body'; });
//                    add_filter( 'wp_mail_from', function($email) { return 'no-reply@thehumanthebody.com'; });
//                    add_filter( 'wp_mail_content_type', function($content_type) { return 'text/html'; });
//                    add_filter( 'wp_mail_charset', function($charset) { return 'UTF-32'; });

                    if ( !wp_mail($_POST['email'], 'Account activation', $body_mail, $headers) ) {
                        $msg = __('The e-mail could not be sent.') . "<br />\n";
                        $msg .= __('Possible reason: your host may have disabled the mail() function.') . "<br />\n";
                        wp_die( $msg );
                    }

                    return $user_id;
                    /* and we`re out */
                else :
                    /* return error */
                    return $user_id;
                /* end check for error in the registration */
                endif;
            /* end validate data */
            endif;
        /* end check for user existance */
        endif;
    }
    /**
     * checks to see if there is any user registered already in the database
     * @method exists
     * @param  string $email  email used in the registration
     * @return boolean        true or false based on success or fail
     */
    public function exists($email='')
    {
        $check = get_user_by_email( $email );

        /* check to see if we have an id */
        if($check->ID > 0) :
            /* we have a user */
            return true;
        endif;
        /* no user */
        return false;
    }
    public function add_meta($data = array())
    {
        /* check for data integrity */
        if(is_array($data) && count($data) > 0) :
            /* loop through all the data */
            foreach ($data as $metakey => $metavalue) :
                /* add each of the data keys to the user as meta1 */
                add_user_meta( $this->ID, $metakey, $metavalue );

            endforeach;
        endif;
    }
    public function get_user_by_field($field, $value) {
        global $wpdb;
        $table = $wpdb->prefix.'users';
        $sql = "SELECT * FROM {$table} WHERE `{$field}`='{$value}' LIMIT 0, 1";
        $user = $wpdb->get_results($sql);
        return $user;
    }

}
