<?php
function cmb2_render_callback_for_emails( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
	/* raw data returned as array of objects from database */
	$emails = save_contact_email::get_all();
	foreach ($emails as $emailobject) {
		$emailarray = array_values((array)$emailobject);

		list($ID, $date, $name, $email, $message) = $emailarray;



		?>
		<div class="emails-sent" style="margin:20px 0px;">
			<a href="javascript:void(0)"><?php echo "$name - $date" ?></a>
			<div class="container">
				<small><?php echo $email ?></small> <br> <br>
				mesage: <?php echo nl2br($message) ?>
			</div>
		</div>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				var j = jQuery;
				j('.emails-sent a').next().hide()
				j('.emails-sent a').on({
					click: function(e) {
						e.preventDefault();
						j('.emails-sent a').next().hide()
						j(this).next().show()
					}
				})
			})
		</script>
		<?php
	}


}
add_action( 'cmb2_render_emails', 'cmb2_render_callback_for_emails', 10, 5 );

$emails = array(
	array(
		'id'   => 'emails',
		'type' => 'emails',
		'repeatable' => false,
	),
);

/* initialize our emails options page */
emails_options($emails);

/*
End theme options metaboxes
----------------------------------------------------------------------------- */


/* Main Theme Settings Example */
function emails_options($metaboxes= array(), $index=0) {
	static $email_options = null;
	if (is_null($email_options)) {
		/* create our object from class */
		$email_options = new spinal_constructor();
		/* set our admin url */
		$email_options->key = "emails-options";
		$email_options->metabox_id = 'emails-metabox';
		/* theme options page index in admin menu */
		$email_options->index = 2345678;
		/* set our title */
		$email_options->optionsTitle = 'Contact Emails';
		/* set our settings page icon, can use the dashicons that come with wordpress or use an url */
		$email_options->icon = 'dashicons-email';
		/* add our metaboxes input fields */
		$email_options->metaboxes = $metaboxes;
		/* init the hooks inside the theme options */
		$email_options->hooks();

	}
	return $email_options;
}

 ?>
