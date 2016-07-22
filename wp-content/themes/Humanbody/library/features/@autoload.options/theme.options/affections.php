<?php
//This line disables the "Import interest" from WP admin
return;


add_action( 'cmb2_render_affections', 'cmb2_render_callback_for_afections', 10, 5 );
function cmb2_render_callback_for_afections( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
    //echo $field_type_object->input( array( 'type' => 'file' ) );
    $files = get_option('af-options');
    if($_POST['submit-cmb']) :
        /* get attachment id from file uplaods */
        $attachment_id = $files['affections_csv_id'];

        if($attachment_id > 0) :
            $file = get_attached_file( $attachment_id);

            $imported = new csv_import_affections($file);

            echo 'Import Successfull / ';
        endif;
    endif;

    $imported = new csv_import_affections();
    echo ($imported->count()).' Affections';
}

$affections = array(
    array(
        'name' => 'csv',
        'id'   => 'affections_csv',
        'type' => 'file',
        'description' => 'add csv with affections',
        'repeatable' => false
    ),
    array(
		'id'   => 'afections',
		'type' => 'affections',
		'repeatable' => false
	),
);

/* initialize our cc options page */
affections_options($affections, 12);

/*
End theme options metaboxes
----------------------------------------------------------------------------- */


/* Main Theme Settings Example */
function affections_options($metaboxes= array(), $index=0) {
	static $affections_options = null;
	if (is_null($affections_options)) {
		/* create our object from class */
		$affections_options = new spinal_constructor();
		/* set our admin url */
		$affections_options->key = "af-options";
		$affections_options->metabox_id = 'af-metabox';
		/* theme options page index in admin menu */
		$affections_options->index = 360014;
		/* set our title */
		$affections_options->optionsTitle = 'Import Affections';
		/* set our settings page icon, can use the dashicons that come with wordpress or use an url */
		$affections_options->icon = 'dashicons-universal-access-alt';
		/* add our metaboxes input fields */
		$affections_options->metaboxes = $metaboxes;
		/* init the hooks inside the theme options */
		$affections_options->hooks();
	}
	return $subscriber_options;
}

 ?>
