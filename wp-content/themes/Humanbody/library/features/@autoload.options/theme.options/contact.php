<?php
/**
 * Wrapper function around cmb2_get_option
 * @since  0.1.0
 * @param  string  $key Options array key
 * @return mixed        Option value
 */
function spinal_get_contact_options( $key = '') {
	return get_option( contact_options()->key, $key );
}

/*
Contact page options
----------------------------------------------------------------------------- */
/* create the shop page options (to be integrated with google maps) */
function contact_options($metaboxes= array(), $index=0) {
	static $contact_options = null;
	if (is_null($contact_options)) {
		/* create our object from class */
		$contact_options = new spinal_constructor();
		/* set our admin url */
		$contact_options->key = "contact";
		$contact_options->metabox_id = 'contact-metabox';
		/* theme options page index in admin menu */
		$contact_options->index = $index;
		/* set our title */
		$contact_options->optionsTitle = 'Contact';
		/* set our settings page icon, can use the dashicons that come with wordpress or use an url */
		$contact_options->icon = 'dashicons-phone';
		/* add our metaboxes input fields */
		$contact_options->metaboxes = $metaboxes;
		/* init the hooks inside the theme options */
		$contact_options->hooks();
	}
	return $contact_options;
}
/*
End contact page
----------------------------------------------------------------------------- */

/*
Contact metaboxes
----------------------------------------------------------------------------- */
$contactMetaboxes = array(
	/* social contact details */
	array(
		'name' => __( '<hr><h2>Social</h2>'),
		'desc' => __( 'add social pages'),
		'id'   => 'title_social',
		'type' => 'title',
		'repeatable' => false,
	),
	array(
		'name' => 'Facebook',
		'id'   => 'facebook',
		'description' => 'facebook page url',
		'type' => 'text',
		'attributes'  => array(
        	'placeholder' => 'http://',
    	),
	),
	array(
		'name' => 'Twitter',
		'id'   => 'twitter',
		'description' => 'twitter page url',
		'type' => 'text',
		'attributes'  => array(
        	'placeholder' => 'http://',
    	),
	),
	array(
		'name' => 'LinkedIn',
		'id'   => 'linkedin',
		'description' => 'linkedin page url',
		'type' => 'text',
		'attributes'  => array(
        	'placeholder' => 'http://',
    	),
	),
	/* end social details */
);

/* #INIT custom theme option pages
----------------------------------------------------------------------------- */


/* contact metaboxes */
contact_options($contactMetaboxes, 56);


/* #INIT end custom theme option pages
----------------------------------------------------------------------------- */
?>
