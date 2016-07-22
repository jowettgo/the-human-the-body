<?php
/*
theme options metaboxes
----------------------------------------------------------------------------- */
/* set theme options metaboxes */
$themeMetaboxes = array(
	array(
		'name' => __( 'Expired membership email content'),
		// 'desc' => __( 'load logo to be used with the theme'),
		'id'   => 'expired-membership',
		'type' => 'wysiwyg',
		'repeatable' => false,
	),
	array(
		'name' => __( 'Activated premium membership email content'),
		// 'desc' => __( 'load logo to be used with the theme'),
		'id'   => 'premium-membership',
		'type' => 'wysiwyg',
		'repeatable' => false,
	)
);

/* initialize our theme options page */
mail_options($themeMetaboxes, 123567);
/*
End theme options metaboxes
----------------------------------------------------------------------------- */


/* Main Theme Settings Example */
function mail_options($metaboxes= array(), $index=0) {
	static $mail_options = null;
	if (is_null($mail_options)) {
		/* create our object from class */
		$mail_options = new spinal_constructor();
		/* set our admin url */
		$mail_options->key = "email-options";
		$mail_options->metabox_id = 'email-metabox';
		/* theme options page index in admin menu */
		$mail_options->index = $index;
		/* set our title */
		$mail_options->optionsTitle = 'Email Settings';
		/* set our settings page icon, can use the dashicons that come with wordpress or use an url */
		$mail_options->icon = 'dashicons-email';
		/* add our metaboxes input fields */
		$mail_options->metaboxes = $metaboxes;
		/* init the hooks inside the theme options */
		$mail_options->hooks();

	}
	return $mail_options;
}

 ?>
