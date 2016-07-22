<?php
$galleryMetaboxes = array(
	array(
		//'name'    => __('Description'),
		'desc'    => 'gallery short description',
		'id'      => 'short',
		'type'    => 'wysiwyg',
		'attributes' => array(
			'placeholder' => 'short description'
		),
		'options' => array(
			'textarea_rows' => get_option('default_post_edit_rows', 5), // rows="..."
		)
		//'repeatable' => true,
	),
);
$gallery = new spinal_metaboxes();
$gallery->metaboxes = $galleryMetaboxes;
$gallery->prefix = "gallery-short-meta";
$gallery->type = 'galleries'; // show on this type of post
$gallery->title = 'Short Description';
$gallery->hooks();


$galleryMetaboxes = array(
	array(
		//'name'    => __('Description'),
		'desc'    => 'Week / Day of the gallery',
		'id'      => 'week',
		'type'    => 'text',
		'attributes' => array(
			'placeholder' => 'week ...'
		)
		//'repeatable' => true,
	),

);
$gallery = new spinal_metaboxes();
$gallery->metaboxes = $galleryMetaboxes;
$gallery->prefix = "gallery-meta";
$gallery->type = 'galleries'; // show on this type of post
$gallery->title = 'Week';
$gallery->hooks();


$galleryMetaboxes = array(
	/* repeatable image slider */

	'group' => array(
		/* start our first group */
		array(
			/* setup our repeatable group metaboxes */
			'setup' => array(
		    'id'          => 'photos',
		    'type'        => 'group',
		    //'description' => __( 'Generates reusable form entries', 'cmb' ),
		    'options'     => array(
		        'group_title'   => __( 'Image {#}', 'cmb' ), // since version 1.1.4, {#} gets replaced by row number
		        'add_button'    => __( 'Add an image', 'cmb' ),
		        'remove_button' => false,
		        'sortable'      => true, // beta
				'closed' => false,
	    		)
			),
			/* add the repeateble field group input */

			array(
			    //'name' => 'Image',
			    'id'   => 'url',
				//'description' => 'image url',
			    'type' => 'file',
			),
			array(
				'name' => 'Aprove',
				'id'   => 'approve',
				//'description' => 'image url',
				'type' => 'checkbox',
			),


		),
	),

);
$gallery = new spinal_metaboxes();
$gallery->metaboxes = $galleryMetaboxes;
$gallery->prefix = "gallery-photos-meta";
$gallery->type = 'galleries'; // show on this type of post
$gallery->title = 'Photos from Users';
$gallery->hooks();

 ?>
