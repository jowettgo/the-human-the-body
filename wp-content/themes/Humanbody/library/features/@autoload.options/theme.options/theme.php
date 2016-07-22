<?php

function spinal_get_theme_option( $key = '' ) {
	return get_option( theme_options()->key, $key );
}


/*
theme options metaboxes
----------------------------------------------------------------------------- */
/* set theme options metaboxes */
$themeMetaboxes = array(
	array(
		'name' => __( 'Logo'),
		'desc' => __( 'load logo to be used with the theme'),
		'id'   => 'logo',
		'type' => 'file',
		'repeatable' => false,
	),
	array(
		'name' => __( 'Facebook'),
		'desc' => __( 'add facebook page here'),
		'id'   => 'facebook',
		'type' => 'text',
		'repeatable' => false,
		'attributes' => array(
			'placeholder' => 'http://'
		)
	),
	array(
		'name' => __( 'Twitter'),
		'desc' => __( 'add twitter page here'),
		'id'   => 'twitter',
		'type' => 'text',
		'repeatable' => false,
		'attributes' => array(
			'placeholder' => 'http://'
		)
	),

	array(
		'name' => __( 'Free days of trial for premium users'),
		'desc' => __( 'Trial days'),
		'id'   => 'trial_member',
		'type' => 'text',
		'repeatable' => false,
	),
);

/* initialize our theme options page */
theme_options($themeMetaboxes, 2);
/*
End theme options metaboxes
----------------------------------------------------------------------------- */


/* Main Theme Settings Example */
function theme_options($metaboxes= array(), $index=0) {
	static $theme_options = null;
	if (is_null($theme_options)) {
		/* create our object from class */
		$theme_options = new spinal_constructor();
		/* set our admin url */
		$theme_options->key = "theme-options";
		$theme_options->metabox_id = 'theme-metabox';
		/* theme options page index in admin menu */
		$theme_options->index = $index;
		/* set our title */
		$theme_options->optionsTitle = 'Theme Options';
		/* set our settings page icon, can use the dashicons that come with wordpress or use an url */
		$theme_options->icon = 'dashicons-admin-generic';
		/* add our metaboxes input fields */
		$theme_options->metaboxes = $metaboxes;
		/* init the hooks inside the theme options */
		$theme_options->hooks();

	}
	return $theme_options;
}

 ?>
