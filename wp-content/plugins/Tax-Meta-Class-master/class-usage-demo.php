<?php
/*
Plugin Name:  Tax meta class
Plugin URI: https://en.bainternet.info
Description: Tax meta class usage
Version: 2.1.0
Author: Bainternet, Ohad Raz
Author URI: https://en.bainternet.info
*/


//include the main class file
require_once("Tax-meta-class/Tax-meta-class.php");
if (is_admin()){


	  /*
	   * prefix of meta keys, optional
	   */
	  $prefix = 'ba_';
	  /*
	   * configure your meta box
	   */
	  $config = array(
	    'id' => 'demo_meta_box',          // meta box id, unique per meta box
	    'title' => 'Demo Meta Box',          // meta box title
	    'pages' => array('category'),        // taxonomy name, accept categories, post_tag and custom taxonomies
	    'context' => 'normal',            // where the meta box appear: normal (default), advanced, side; optional
	    'fields' => array(),            // list of meta fields (can be added by field arrays)
	    'local_images' => false,          // Use local or hosted images (meta box images for add/remove)
	    'use_with_theme' => false          //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
	  );


	  /*
	   * Initiate your meta box
	   */
	  $my_meta =  new Tax_Meta_Class($config);

	  /*
	   * Add fields to your meta box
	   */

	  //text field
	  $my_meta->addText($prefix.'banner1_link',array('name'=> __('Banner1 link ','tax-meta')));
	  $my_meta->addImage($prefix.'banner1_image',array('name'=> __('Banner1 text ','tax-meta')));

	  $my_meta->addText($prefix.'banner2_link',array('name'=> __('Banner2 link ','tax-meta')));
	  $my_meta->addImage($prefix.'banner2_image',array('name'=> __('Banner2 text ','tax-meta')));

	  /*
	   * To Create a reapeater Block first create an array of fields
	   * use the same functions as above but add true as a last param
	   */
	  //Finish Meta Box Decleration
	  $my_meta->Finish();


}
