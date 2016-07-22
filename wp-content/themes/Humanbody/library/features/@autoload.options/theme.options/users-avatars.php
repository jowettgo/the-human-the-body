<?php




/*
theme options metaboxes
----------------------------------------------------------------------------- */
/* set theme options metaboxes */
$metaboxes = array(
	array(
		'name' => __( 'Avatars'),
		'desc' => __( 'Upload or select the avatars to be used'),
		'id'   => 'avatars_list',
		'type' => 'file_list',
		'repeatable' => false,
	),
	array(
		'name' => __( 'Premium Avatars'),
		'desc' => __( 'Upload or select the avatars to be used for premium members'),
		'id'   => 'premium_avatars_list',
		'type' => 'file_list',
		'repeatable' => false,
	),

);

/*
End theme options metaboxes
----------------------------------------------------------------------------- */
/* initialize our theme options page */
users_avatar($metaboxes, 2);

/* Main Theme Settings Example */
function users_avatar($metaboxes= array(), $index=0) {
	static $spinal_options = null;
	if (is_null($spinal_options)) {
		/* create our object from class */
		$spinal_options = new spinal_constructor();
		/* set our admin url */
		$spinal_options->subpage = 'users.php';
		/* set our admin url */
		$spinal_options->key = "avatars";
		/* metabox css id */
		$spinal_options->metabox_id = 'avatars';
		/* theme options page index in admin menu */
		$spinal_options->index = $index;
		/* set our title */
		$spinal_options->optionsTitle = 'Avatars';
		/* add our metaboxes input fields */
		$spinal_options->metaboxes = $metaboxes;
		/* init the hooks inside the theme options */
		$spinal_options->hooks();
	}
	return $spinal_options;
}

 ?>
