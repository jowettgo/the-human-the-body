<?php
//This line disables the "Import interest" from WP admin
return;


add_action( 'cmb2_render_cc', 'cmb2_render_callback_for_cc', 10, 5 );
function cmb2_render_callback_for_cc( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
    //echo $field_type_object->input( array( 'type' => 'file' ) );
    $files = get_option('cc-options');
    if($_POST['submit-cmb']) :
        /* get attachment id from file uplaods */
        $attachment_id = $files['cities_countries_id'];
        if($attachment_id > 0) :
            $file = get_attached_file( $attachment_id);

            $imported = new csv_import_cities($file);
            echo 'Import Successfull';
        endif;
    endif;

    $imported = new csv_import_cities();
    echo ($imported->count()).' cities';
}

$cc = array(
    array(
        'name' => 'csv',
        'id'   => 'cities_countries',
        'type' => 'file',
        'description' => 'add csv with countries and cities, must be with two columns',
        'repeatable' => false
    ),
    array(
		'id'   => 'cc',
		'type' => 'cc',
		'repeatable' => false
	),
);

/* initialize our cc options page */
cc_options($cc, 11);

/*
End theme options metaboxes
----------------------------------------------------------------------------- */


/* Main Theme Settings Example */
function cc_options($metaboxes= array(), $index=0) {
	static $interest_options = null;
	if (is_null($interest_options)) {
		/* create our object from class */
		$interest_options = new spinal_constructor();
		/* set our admin url */
		$interest_options->key = "cc-options";
		$interest_options->metabox_id = 'cc-metabox';
		/* theme options page index in admin menu */
		$interest_options->index = 36001;
		/* set our title */
		$interest_options->optionsTitle = 'Import Cities';
		/* set our settings page icon, can use the dashicons that come with wordpress or use an url */
		$interest_options->icon = 'dashicons-flag';
		/* add our metaboxes input fields */
		$interest_options->metaboxes = $metaboxes;
		/* init the hooks inside the theme options */
		$interest_options->hooks();
	}
	return $subscriber_options;
}

 ?>
