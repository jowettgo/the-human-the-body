<?php

/* Contact ajax */
add_action( 'wp_ajax_nopriv_login', 'ajax_login_callback' );
add_action( 'wp_ajax_login', 'ajax_login_callback' );


function ajax_login_callback()
{
    /* filter the post method */
    $_POST = filter::post();
    if(isset($_POST['sign_in'])) :
        /* add sign-in user function to check for signin */
        $signedin_ID = spinal_signin_user($redirect = false);

        if($signedin_ID) :

                $user = new user_info($signedin_ID);
                $fullname = explode(' ', $user->name);
                $name = $fullname[0];
                $surname = $fullname[1];
                echo page('payment')
                 ?>
                 <div class="payment-method clearfix">
                     <form class="paypal" action="<?php echo page('payment') ?>" method="post" id="paypal-form" target="_blank">
                         <input type="hidden" name="cmd" value="_xclick" />
                         <input type="hidden" name="no_note" value="1" />
                         <input type="hidden" name="lc" value="US" />
                         <input type="hidden" name="currency_code" value="USD" />
                         <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest" />
                         <input type="hidden" name="first_name" value="<?php echo $name ?>"  />
                         <input type="hidden" name="last_name" value="<?php echo $surname ?>"  />
                         <input type="hidden" name="payer_email" value="<?php echo $user->email ?>"  />
                         <input type="hidden" name="item_number" value="638322" / >
                         <input type="hidden" name="custom" value="pHBua-<?php echo $user->ID ?>">
                         <input type="hidden" name="no_shipping" value="1">
                         <button name="submit" type="submit" value="submit">continue to payment<i class="fa fa-angle-right"></i></button>
                         <!-- <span class="notice-payment">You don't need to have a Paypal account</span> -->
                     </form>
                 </div>

            <?php

        else :
            echo $signedin; // message
        endif;
    endif;
    exit;
}

add_action( 'spinal-primal', 'spinal_signin_user', $priority = 1, 0);
function spinal_signin_user($redirect = true) {
    /* filter the post method */
    $_POST = filter::post();
    /* check and login */
    if(isset($_POST['logID']) && $_POST['logID'] && $_POST['password'] ) :
     	$message = spinal_email_password_login($_POST['logID'], $_POST['password'], $redirect ? page('my-account') : false);
        return $message;
    else :
        return false;
    endif;
}
