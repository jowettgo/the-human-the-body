<?php
/*
Plugin Name: MetaBox
Plugin URI: http://en.bainternet.info
Description: My Meta Box Class usage
Version: 3.1.1
Author: Bainternet, Ohad Raz
Author URI: http://en.bainternet.info
*/

//include the main class file
require_once("meta-box-class/my-meta-box-class.php");
if (is_admin()){

    $post_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'] ;
    $template_file = get_post_meta($post_id,'_wp_page_template',TRUE);

      /* 
       * prefix of meta keys, optional
       * use underscore (_) at the beginning to make keys hidden, for example $prefix = '_ba_';
       *  you also can make prefix empty to disable it
       * 
       */

        $prefix = 'ba_';
      /* 
       * configure your meta box
       */
      $config = array(
        'id'             => 'demo_meta_box',          // meta box id, unique per meta box
        'title'          => 'Simple Meta Box fields',          // meta box title
        'pages'          => array('idea', 'galleries', 'affection'),      // post types, accept custom post types as well, default is array('post'); optional
        'context'        => 'normal',            // where the meta box appear: normal (default), advanced, side; optional
        'priority'       => 'high',            // order of meta box: high (default), low; optional
        'fields'         => array(),            // list of meta fields (can be added by field arrays)
        'local_images'   => false,          // Use local or hosted images (meta box images for add/remove)
        'use_with_theme' => false          //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
        );
      
      
      /*
       * Initiate your meta box
       */
      $my_meta =  new AT_Meta_Box($config);
      
      /*
       * Add fields to your meta box
       */
      
      //text field
      $my_meta->addText($prefix.'banner1_link',array('name'=> 'Banner1 link '));
      $my_meta->addImage($prefix.'banner1_image',array('name'=> 'Banner1 image '));

      $my_meta->addText($prefix.'banner2_link',array('name'=> 'Banner2 link '));
      $my_meta->addImage($prefix.'banner2_image',array('name'=> 'Banner2 image '));

      /*
       * Don't Forget to Close up the meta box Declaration 
       */
      //Finish Meta Box Declaration 
      $my_meta->Finish();

      if (($template_file == 'page-membership.php') )  {
        $prefix = 'ba_';
      /* 
       * configure your meta box
       */
      $config = array(
        'id'             => 'demo_meta_box',          // meta box id, unique per meta box
        'title'          => 'Simple Meta Box fields',          // meta box title
        'pages'          => array('page'),      // post types, accept custom post types as well, default is array('post'); optional
        'context'        => 'normal',            // where the meta box appear: normal (default), advanced, side; optional
        'priority'       => 'high',            // order of meta box: high (default), low; optional
        'fields'         => array(),            // list of meta fields (can be added by field arrays)
        'local_images'   => false,          // Use local or hosted images (meta box images for add/remove)
        'use_with_theme' => false          //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
        );
      
      
      /*
       * Initiate your meta box
       */
      $my_meta =  new AT_Meta_Box($config);
      
      /*
       * Add fields to your meta box
       */
      
      //text field
      $my_meta->addWysiwyg($prefix.'month_price',array('name'=> 'Month price '));

      /*
       * Don't Forget to Close up the meta box Declaration 
       */
      //Finish Meta Box Declaration 
      $my_meta->Finish();
      }

  
}
