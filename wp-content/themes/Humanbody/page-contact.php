<?php
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
/*
 Template Name: Contact
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
?>
<?php
	$contactOptions = spinal_get_contact_options();

	$tel = $contactOptions['tel'];
	$fax = $contactOptions['fax'];
	$address = $contactOptions['address'];
	$email = $contactOptions['email'];


	$lat = $contactOptions['lat'];
	$lng = $contactOptions['lng'];

//company name1 name2 tel email address zipcode city message check

	if($_POST['submited']) {

		$subject = $_POST['name1']." ".$_POST['name2'];
		$message = 'Contact<hr/><hr/>
			'.$_POST['message']."
		<hr/>
		Société:".$_POST['company']."
		Tel: ".$_POST['tel']."
		<br>Nom/Prenom: ".$_POST['name1']." ".$_POST['name2']."
		<br>Email: ".$_POST['email'].'
		<br>Address:'.$_POST['address'].'
		<br>Code postal:'.$_POST['zipcode'].'
		<br>Ville:'.$_POST['city']
		;


		$headers[] = 'From: '.$_POST['name1']." ".$_POST['name2'].' <'.$_POST['email'].'>';
		$headers[] = 'Content-Type: text/html; charset=UTF-8';
		$mail = wp_mail( $email, $subject, $message, $headers);
		if($mail) {
			$messageES = 'Merci, nous prendrons contact';
			unset($_POST);
		}
		else {
			$messageES = 'nous sommes désolés, il semble y avoir une erreur avec l`envoi de cet e-mail';
		}
	}
	else {
		$messageES = false;
	}

?>
<?php get_header(); ?>
<!-- start content -->

<?php get_footer(); ?>
