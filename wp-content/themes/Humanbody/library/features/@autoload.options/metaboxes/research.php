<?php

$researchMetaboxes = array(
	array(
		//'name'    => __('Description'),
		'desc'    => 'Research Description',
		'id'      => 'description',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => get_option('default_post_edit_rows', 5), // rows="..."
		)
		//'repeatable' => true,
	),
);
$research = new spinal_metaboxes();
$research->metaboxes = $researchMetaboxes;
$research->prefix = "research-description";
$research->type = 'research'; // show on this type of post
$research->title = 'Research Description';
$research->hooks();



$researchMetaboxes = array(
	array(
		//'name'    => __('Description'),
		'desc'    => 'Research Note Description',
		'id'      => 'note-description',
		'type'    => 'textarea',
		//'repeatable' => true,
		'attributes' => array(
			'rows' => 2
		)
	),
);
$research = new spinal_metaboxes();
$research->metaboxes = $researchMetaboxes;
$research->prefix = "research-note-description";
$research->type = 'research'; // show on this type of post
$research->title = 'Research Note Description';
$research->hooks();



$researchMetaboxes = array(
	array(
		//'name'    => __('Description'),
		'desc'    => 'Research short description (landing page)',
		'id'      => 'short-description',
		'type'    => 'textarea',
		'attributes' => array(
			'rows' => 2
		)
		//'repeatable' => true,
	),
);
$research = new spinal_metaboxes();
$research->metaboxes = $researchMetaboxes;
$research->prefix = "research-short-description";
$research->type = 'research'; // show on this type of post
$research->title = 'Research short description';
$research->hooks();




$researchMetaboxes = array(
	array(
		//'name'    => __('Description'),
		'desc'    => 'Visit Link',
		'id'      => 'link',
		'type'    => 'text',
		'attributes' => array(
			'placeholder' => 'http://'
		)
		//'repeatable' => true,
	),
	array(
		//'name'    => __('Description'),
		'desc'    => 'Donate Link',
		'id'      => 'donate',
		'type'    => 'text',
		'attributes' => array(
			'placeholder' => 'http://'
		)
		//'repeatable' => true,
	),
);
$research = new spinal_metaboxes();
$research->metaboxes = $researchMetaboxes;
$research->prefix = "research-link";
$research->type = 'research'; // show on this type of post
$research->title = 'Research Link';
$research->hooks();





$researchMetaboxes = array(
	array(
		//'name'    => __('Description'),
		'desc'    => 'Color Start',
		'id'      => 'color1',
		'type'    => 'colorpicker',
		//'repeatable' => true,
	),
	array(
		//'name'    => __('Description'),
		'desc'    => 'Color End',
		'id'      => 'color2',
		'type'    => 'colorpicker',
		//'repeatable' => true,
	),
);
$research = new spinal_metaboxes();
$research->metaboxes = $researchMetaboxes;
$research->prefix = "research-color";
$research->type = 'research'; // show on this type of post
$research->title = 'Research Gradient';
$research->hooks();
/* end post featured metabox */

 ?>
