<?php
// $affectionMetaboxes = array(
// 	array(
// 		//'name'    => __('Description'),
// 		'desc'    => 'Affection short description, will appear in the body landing page',
// 		'id'      => 'short',
// 		'type'    => 'textarea',
// 		'attributes' => array(
// 			'placeholder' => 'Affection short description',
// 			'rows' => 5
// 		),
// 		'options' => array(
// 			'textarea_rows' => get_option('default_post_edit_rows', 5), // rows="..."
// 		)
// 		//'repeatable' => true,
// 	),
// );
// $affection = new spinal_metaboxes();
// $affection->metaboxes = $affectionMetaboxes;
// $affection->prefix = "affection-short-meta";
// $affection->type = 'affection'; // show on this type of post
// $affection->title = 'Short Description';
// $affection->hooks();


$affectionMetaboxes = array(
	array(
		'id'      => 'text-color',
		'type'    => 'radio_inline',
		'default' => '#fff',
		 'options' => array(
		 	'#fff' => 'White text',
			'#000' => 'Black text',
		),
	)
);

$affection = new spinal_metaboxes();
$affection->metaboxes = $affectionMetaboxes;
$affection->prefix = "affection-meta-title-color";
$affection->type = 'affection'; // show on this type of post
$affection->title = 'Title color';
$affection->hooks();


$affectionMetaboxes = array(
	array(
		//'name'    => __('Description'),
		'desc'    => 'Affection full description, will appear under the title',
		'id'      => 'description',
		'type'    => 'wysiwyg',
		'attributes' => array(
			'placeholder' => 'Affection full description'
		),
		'options' => array(
			'textarea_rows' => get_option('default_post_edit_rows', 10), // rows="..."
		)
		//'repeatable' => true,
	),
);
$affection = new spinal_metaboxes();
$affection->metaboxes = $affectionMetaboxes;
$affection->prefix = "affection-meta";
$affection->type = 'affection'; // show on this type of post
$affection->title = 'Full Description';
$affection->hooks();


$affectionMetaboxes = array(
	array(
		//'name'    => __('Description'),
		'desc'    => 'Add some advices',
		'id'      => 'Advice',
		'type'    => 'wysiwyg',
		'attributes' => array(
			'placeholder' => 'Advice for this affection'
		),
		'options' => array(
			'textarea_rows' => get_option('default_post_edit_rows', 10), // rows="..."
		)
		//'repeatable' => true,
	),
);



$affection = new spinal_metaboxes();
$affection->metaboxes = $affectionMetaboxes;
$affection->prefix = "affection-advice-meta";
$affection->type = 'affection'; // show on this type of post
$affection->title = 'Advice';
$affection->hooks();

$affectionMetaboxes = array(
	/* repeatable image slider */

	'group' => array(
		/* start our first group */
		array(
			/* setup our repeatable group metaboxes */
			'setup' => array(
		    'id'          => 'myths',
		    'type'        => 'group',
		    //'description' => __( 'Generates reusable form entries', 'cmb' ),
		    'options'     => array(
		        'group_title'   => __( 'Myth {#}', 'cmb' ), // since version 1.1.4, {#} gets replaced by row number
		        'add_button'    => __( 'Add another Myth', 'cmb' ),
		        'remove_button' => 'delete',
		        'sortable'      => true, // beta
				'closed' => false,
	    		)
			),
			/* add the repeateble field group input */

			array(
			    //'name' => 'Image',
			    'id'   => 'url',
				'description' => 'add a thumbnail besides the content',
			    'type' => 'file',
			),
			array(
				//'name'    => __('Description'),
				'desc'    => 'Describe the myth for this affection',
				'id'      => 'content',
				'type'    => 'wysiwyg',
				'options' => array(
					'textarea_rows' => get_option('default_post_edit_rows', 6), // rows="..."
				)
				//'repeatable' => true,
			),


		),
	),
);
$affection = new spinal_metaboxes();
$affection->metaboxes = $affectionMetaboxes;
$affection->prefix = "affection-myth-meta";
$affection->type = 'affection'; // show on this type of post
$affection->title = 'Myths';
$affection->hooks();


$affectionMetaboxes = array(
	array(
		//'name'    => __('Description'),
		'desc'    => 'Affection Statistics',
		'id'      => 'statistics',
		'type'    => 'wysiwyg',
		'attributes' => array(
			'placeholder' => 'Statistics for this affection'
		),
		'options' => array(
			'textarea_rows' => get_option('default_post_edit_rows', 10), // rows="..."
		)
		//'repeatable' => true,
	),
);
$affection = new spinal_metaboxes();
$affection->metaboxes = $affectionMetaboxes;
$affection->prefix = "affection-statistics-meta";
$affection->type = 'affection'; // show on this type of post
$affection->title = 'Statistics';
$affection->hooks();

$affectionMetaboxes = array(
	array(
		//'name'    => __('Description'),
		'desc'    => 'Causes',
		'id'      => 'causes',
		'type'    => 'wysiwyg',
		'attributes' => array(
			'placeholder' => 'Causes for this affection'
		),
		'options' => array(
			'textarea_rows' => get_option('default_post_edit_rows', 10), // rows="..."
		)
		//'repeatable' => true,
	),
);
$affection = new spinal_metaboxes();
$affection->metaboxes = $affectionMetaboxes;
$affection->prefix = "affection-causes-meta";
$affection->type = 'affection'; // show on this type of post
$affection->title = 'Causes';
$affection->hooks();

$affectionMetaboxes = array(
	array(
		//'name'    => __('Description'),
		'desc'    => 'Symptoms and signs',
		'id'      => 'signs',
		'type'    => 'wysiwyg',
		'attributes' => array(
			'placeholder' => 'Symptoms and signs for this affection'
		),
		'options' => array(
			'textarea_rows' => get_option('default_post_edit_rows', 10), // rows="..."
		)
		//'repeatable' => true,
	),
);
$affection = new spinal_metaboxes();
$affection->metaboxes = $affectionMetaboxes;
$affection->prefix = "affection-signs-meta";
$affection->type = 'affection'; // show on this type of post
$affection->title = 'Symptoms and signs';
$affection->hooks();

$affectionMetaboxes = array(
	array(
		//'name'    => __('Description'),
		'desc'    => 'add some "did you know?" facts for this Affection',
		'id'      => 'know',
		'type'    => 'wysiwyg',
		'attributes' => array(
			'placeholder' => 'add some "did you know?" facts'
		),
		'options' => array(
			'textarea_rows' => get_option('default_post_edit_rows', 10), // rows="..."
		)
		//'repeatable' => true,
	),
);
$affection = new spinal_metaboxes();
$affection->metaboxes = $affectionMetaboxes;
$affection->prefix = "affection-know-meta";
$affection->type = 'affection'; // show on this type of post
$affection->title = 'Did You Know?';
$affection->hooks();

$affectionMetaboxes = array(
	array(
		//'name'    => __('Description'),
		'desc'    => 'support forum link',
		'id'      => 'support-forum',
		'type'    => 'text',
		'attributes' => array(
			'placeholder' => 'http://'
		),
		//'repeatable' => true,
	),
);
$affection = new spinal_metaboxes();
$affection->metaboxes = $affectionMetaboxes;
$affection->prefix = "affection-support-forum";
$affection->type = 'affection'; // show on this type of post
$affection->title = 'Add Support Forum';
$affection->hooks();



$affectionMetaboxes = array(
	/* repeatable image slider */

	'group' => array(
		/* start our first group */
		array(
			/* setup our repeatable group metaboxes */
			'setup' => array(
		    'id'          => 'links',
		    'type'        => 'group',
		    //'description' => __( 'Generates reusable form entries', 'cmb' ),
		    'options'     => array(
		        'group_title'   => __( 'External Link {#}', 'cmb' ), // since version 1.1.4, {#} gets replaced by row number
		        'add_button'    => __( 'Add another link', 'cmb' ),
		        'remove_button' => 'delete',
		        'sortable'      => true, // beta
				'closed' => true,
	    		)
			),
			/* add the repeateble field group input */
			array(
				//'name' => 'Image',
				'id'   => 'image',
				'description' => 'add a thumbnail besides the link',
				'type' => 'file',
				'attributes' => array(
					'placeholder' => 'http://'
				)
			),
			array(
			    //'name' => 'Image',
			    'id'   => 'url',
				'description' => 'add a thumbnail besides the content',
			    'type' => 'text',
				'attributes' => array(
					'placeholder' => 'http://'
				)
			),
			array(
			    //'name' => 'Image',
			    'id'   => 'text',
				'description' => 'link text',
			    'type' => 'text',
			),


		),
	),
);
$affection = new spinal_metaboxes();
$affection->metaboxes = $affectionMetaboxes;
$affection->prefix = "affection-links-meta";
$affection->type = 'affection'; // show on this type of post
$affection->title = 'External Links';
$affection->hooks();
 ?>
