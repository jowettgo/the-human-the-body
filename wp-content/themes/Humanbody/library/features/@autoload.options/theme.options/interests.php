<?php
//This line disables the "Import interest" from WP admin
return;

add_action( 'cmb2_render_interests', 'cmb2_render_callback_for_interests', 10, 5 );
function cmb2_render_callback_for_interests( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
    //echo $field_type_object->input( array( 'type' => 'file' ) );
    $files = get_option('interests-options');

    if($_POST['submit-cmb']) :
        /* remove all interests */
        csv_import_interests::empty_all();
        /* do the import */

            /* get attachment id from file uplaods */
            $attachment_id = $files['interests_csv_id'];

            if($attachment_id > 0) :
                $file = get_attached_file( $attachment_id);
                $imported = new csv_import_interests($file);
            endif;

    endif;
    echo csv_import_interests::count().' total interests';
}

$interests = array(
    array(
        'name' => 'Interests and hobbies csv 3 columns',
		'id'   => 'interests_csv',
		'type' => 'file',
		'repeatable' => false
	),
    array(

		'id'   => 'interests',
		'type' => 'interests',
		'repeatable' => false
	),
);

/* initialize our interests options page */
interests_options($interests, 11);

/*
End theme options metaboxes
----------------------------------------------------------------------------- */


/* Main Theme Settings Example */
function interests_options($metaboxes= array(), $index=0) {
	static $interest_options = null;
	if (is_null($interest_options)) {
		/* create our object from class */
		$interest_options = new spinal_constructor();
		/* set our admin url */
		$interest_options->key = "interests-options";
		$interest_options->metabox_id = 'interests-metabox';
		/* theme options page index in admin menu */
		$interest_options->index = 36000;
		/* set our title */
		$interest_options->optionsTitle = 'Import interests';
		/* set our settings page icon, can use the dashicons that come with wordpress or use an url */
		$interest_options->icon = 'dashicons-clipboard';
		/* add our metaboxes input fields */
		$interest_options->metaboxes = $metaboxes;
		/* init the hooks inside the theme options */
		$interest_options->hooks();
	}
	return $subscriber_options;
}

 ?>
