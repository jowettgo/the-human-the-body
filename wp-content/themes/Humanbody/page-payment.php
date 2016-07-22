<?php


/*
 Template Name: Payment page
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


// PayPal settings


$paypal_email = 'larisarus13@gmail.com';

$return_url = page('my-account');
$cancel_url = page('my-account');
$notify_url = page('payment');

$item_name = 'Human Body Premium Membership';
$item_amount = 5;
$item_qty = 1;

/* purge everything in the post thats not supposed to be there*/
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

$paid_date = date("Y-m-d H:i:s");

// Check if paypal request or response
if (!isset($_POST["txn_id"]) && !isset($_POST["txn_type"])){

	$querystring = '';

	// Firstly Append paypal account to querystring
	$querystring .= "?business=".urlencode($paypal_email)."&";

	// Append amount& currency (£) to quersytring so it cannot be edited in html

	//The item name and amount can be brought in dynamically by querying the $_POST['item_number'] variable.
	$querystring .= "item_name=".urlencode($item_name)."&";
	$querystring .= "amount=".urlencode($item_amount)."&";
	$querystring .= "quantity=".urlencode($item_qty)."&";

	//loop for posted values and append to querystring
	foreach($_POST as $key => $value){
		$value = urlencode(stripslashes($value));
		$querystring .= "$key=$value&";
	}

	// Append paypal return addresses
	$querystring .= "return=".urlencode(stripslashes($return_url))."&";
	$querystring .= "cancel_return=".urlencode(stripslashes($cancel_url))."&";
	$querystring .= "notify_url=".urlencode($notify_url);

	// Append querystring with custom field
	//$querystring .= "&custom=".USERID;


	/* 
		Store a user_meta in the database so we can know that the payment is on "pending" mode. 
		This user meta will be deleted when we have the payment response
	*/	
	update_user_meta( get_current_user_id (), 'paypal_pending',  time());	


	// Redirect to paypal IPN
	//use www.sandbox.paypal.com/etc for test
	wp_redirect( 'https://www.paypal.com/cgi-bin/webscr'.$querystring );
	

	exit();
} else {
	
	
	
		//SECURE PAYPAL RESPONSE
		//https://github.com/paypal/ipn-code-samples/blob/master/paypal_ipn.php

		define("LOG_FILE", "/home/humanbody/public_html/ipn_log");
		
		
		// Set to 0 once you're ready to go live
		define("USE_SANDBOX", 0);
		define("DEBUG", 0);


		// Read POST data
		// reading posted data directly from $_POST causes serialization
		// issues with array data in POST. Reading raw POST data from input stream instead.
		$raw_post_data = file_get_contents('php://input');
		$raw_post_array = explode('&', $raw_post_data);
		$myPost = array();
		foreach ($raw_post_array as $keyval) {
			$keyval = explode ('=', $keyval);
			if (count($keyval) == 2)
				$myPost[$keyval[0]] = urldecode($keyval[1]);
		}
		
		// read the post from PayPal system and add 'cmd'
		$req = 'cmd=_notify-validate';
		if(function_exists('get_magic_quotes_gpc')) {
			$get_magic_quotes_exists = true;
		}
		
		foreach ($myPost as $key => $value) {
			if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
				$value = urlencode(stripslashes($value));
			} else {
				$value = urlencode($value);
			}
			$req .= "&$key=$value";
		}
		
		// Post IPN data back to PayPal to validate the IPN data is genuine
		// Without this step anyone can fake IPN data
		if(USE_SANDBOX == true) {
			$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
		} else {
			$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
		}
		$ch = curl_init($paypal_url);
		if ($ch == FALSE) {
			return FALSE;
		}
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
		if(DEBUG == true) {
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
		}
		// CONFIG: Optional proxy configuration
		//curl_setopt($ch, CURLOPT_PROXY, $proxy);
		//curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
		// Set TCP timeout to 30 seconds
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
		// CONFIG: Please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path
		// of the certificate as shown below. Ensure the file is readable by the webserver.
		// This is mandatory for some environments.
		//$cert = __DIR__ . "./cacert.pem";
		//curl_setopt($ch, CURLOPT_CAINFO, $cert);
		$res = curl_exec($ch);
		if (curl_errno($ch) != 0) // cURL error
			{
			if(DEBUG == true) {	
				error_log(date('[Y-m-d H:i e] '). "Can't connect to PayPal to validate IPN message: " . curl_error($ch) . PHP_EOL, 3, LOG_FILE);
			}
			curl_close($ch);
			exit;
		} else {
				// Log the entire HTTP response if debug is switched on.
				if(DEBUG == true) {
					error_log(date('[Y-m-d H:i e] '). "HTTP request of validation request:". curl_getinfo($ch, CURLINFO_HEADER_OUT) ." for IPN payload: $req" . PHP_EOL, 3, LOG_FILE);
					error_log(date('[Y-m-d H:i e] '). "HTTP response of validation request: $res" . PHP_EOL, 3, LOG_FILE);
				}
				curl_close($ch);
		}
		// Inspect IPN validation result and act accordingly
		
		//remove pending 
		$userID = explode('-', $_POST['custom']);
		$userID = $userID[1];
		delete_user_meta($userID, 'paypal_pending');
		
					
		// Split response headers and payload, a better way for strcmp
		$tokens = explode("\r\n\r\n", trim($res));
		$res = trim(end($tokens));
		if (strcmp ($res, "VERIFIED") == 0) {
			// check whether the payment_status is Completed
			// check that txn_id has not been previously processed
			// check that receiver_email is your PayPal email
			// check that payment_amount/payment_currency are correct
			// process payment and mark item as paid.
			// assign posted variables to local variables
			//$item_name = $_POST['item_name'];
			//$item_number = $_POST['item_number'];
			//$payment_status = $_POST['payment_status'];
			//$payment_amount = $_POST['mc_gross'];
			//$payment_currency = $_POST['mc_currency'];
			//$txn_id = $_POST['txn_id'];
			//$receiver_email = $_POST['receiver_email'];
			//$payer_email = $_POST['payer_email'];
			
			if(DEBUG == true) {
				error_log(date('[Y-m-d H:i e] '). "Verified IPN: $req ". PHP_EOL, 3, LOG_FILE);
			}
			
			
			// assign posted variables to local variables
			$data['item_name']			= $_POST['item_name'];
			$data['item_number'] 		= $_POST['item_number'];
			$data['payment_status'] 	= $_POST['payment_status'];
			$data['payment_amount'] 	= $_POST['mc_gross'];
			$data['payment_currency']	= $_POST['mc_currency'];
			$data['txn_id']				= $_POST['txn_id'];
			$data['receiver_email'] 	= $_POST['receiver_email'];
			$data['payer_email'] 		= $_POST['payer_email'];
			$data['custom'] 			= $_POST['custom'];
			
		
			$orderid = updatePayments($data, $item_amount);		
			
			
		} else if (strcmp ($res, "INVALID") == 0) {
			// log for manual investigation
			// Add business logic here which deals with invalid IPN messages
			if(DEBUG == true) {
				error_log(date('[Y-m-d H:i e] '). "Invalid IPN: $req" . PHP_EOL, 3, LOG_FILE);
			}
		}

	
	
}




function updatePayments($data, $item_amount){
	if (is_array($data)) {

		$paid_date = date("Y-m-d H:i:s");
		$data['paid_on'] = $paid_date;
		$userID = explode('-', $data['custom']);
		$userID = $userID[1];
		$user = new user_info($userID);
		$expire_date = date("Y-m-d H:i:s");
		
		//error_log(serialize($data), 3, "pp-errors.log");
		if((int)$userID > 0) :
			update_user_meta( $userID, 'paypal', $data );


			// check that receiver_email is your PayPal email
			//doesn't work because paypal post invalid email address
			// if ($data['receiver_email']!=$paypal_email) {
				// exit;
			// }
			
			// check that payment_amount/payment_currency are correct
			if (intval($data['payment_amount'])!=$item_amount) {
				if(DEBUG == true) {
					error_log("Payment ammount incorect" . PHP_EOL, 3, LOG_FILE);
				}				
				exit;
			}
						
			
			/*Check status and update the user in db */
			$status = $data['payment_status'];

			if(DEBUG == true) {
				error_log("Payment status ".$data['payment_status'] . PHP_EOL, 3, LOG_FILE);
			}
						
			if($status == 'Completed') {
				$user = new WP_User( $userID );
				$allcaps = $user->get_role_caps();
				
				if(DEBUG == true) {
					error_log("User id ".$userID . PHP_EOL, 3, LOG_FILE);
				}				
				
                /* only change roles if the current user is not an admin */
                if($allcaps['administrator'] != 1) {

					if(DEBUG == true) {
						error_log("Before becoming premium " . PHP_EOL, 3, LOG_FILE);
					}	
				
				                	
                    /* set premium member role */
                    $user->remove_role( 'normal_member' );
                    $user->add_role( 'premium_member' );
                    $data = array(
                    	'ID' => $userID,
                    	'role' => 'premium_member'
                    );
                    /* update user with new roles so they are visible in admin */
                    wp_update_user($data);
					
					//update the date when the user was actually transformed to premium user
					update_user_meta( $userID, 'date_when_became_premium', time() );
					
					if(DEBUG == true) {
						error_log("After becoming premium " . PHP_EOL, 3, LOG_FILE);
					}	
					
										
                    do_action('premium-membership');
										
                }
				
			}
			
			
		endif;
	}
}



?>
