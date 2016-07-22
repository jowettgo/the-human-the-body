<?php
function cmb2_render_callback_for_subscribers( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
	$subscribers = new spinal_subscribe_csv_download();
	$subscribers->get_all();
    //echo $field_type_object->input( array( 'type' => 'email' ) );
}
add_action( 'cmb2_render_subscribers', 'cmb2_render_callback_for_subscribers', 10, 5 );
function spinal_get_subscribers_options( $key = '' ) {
	return get_option( subscribers_options()->key, $key );
}

$subscribers = array(
	array(
		'id'   => 'subscribers',
		'type' => 'subscribers',
		'repeatable' => false,
	),
);

/* initialize our subscribers options page */
subscribers_options($subscribers, 11);

/*
End theme options metaboxes
----------------------------------------------------------------------------- */


/* Main Theme Settings Example */
function subscribers_options($metaboxes= array(), $index=0) {
	static $subscriber_options = null;
	if (is_null($subscriber_options)) {
		/* create our object from class */
		$subscriber_options = new spinal_constructor();
		/* set our admin url */
		$subscriber_options->key = "subscribers-options";
		$subscriber_options->metabox_id = 'subscribers-metabox';
		/* theme options page index in admin menu */
		$subscriber_options->index = 45755;
		/* set our title */
		$subscriber_options->optionsTitle = 'Subscribers';
		/* set our settings page icon, can use the dashicons that come with wordpress or use an url */
		$subscriber_options->icon = 'dashicons-email';
		/* add our metaboxes input fields */
		$subscriber_options->metaboxes = $metaboxes;
		/* init the hooks inside the theme options */
		$subscriber_options->hooks();

	}
	return $subscriber_options;
}

 ?>
