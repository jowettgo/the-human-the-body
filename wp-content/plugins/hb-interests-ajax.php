<?php
/*
Plugin Name: HumanBody Interests Ajax
Plugin URI: http://www.iquatic.com
Description: access interests by ajax
Version: 1.0
Author: Sebastian
Author URI: http://www.iquatic.com
License: GPL2
*/


add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
add_action( 'wp_ajax_my_action', 'ajax' );
add_action( 'wp_ajax_nopriv_my_action', 'ajax' );

function enqueue_styles()
{
    wp_enqueue_style( 'select2', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css' );
}

function enqueue_scripts()
{
    wp_enqueue_script( 'select2-script', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js' );
}


function ajax()
{
    echo 'ajax';
}