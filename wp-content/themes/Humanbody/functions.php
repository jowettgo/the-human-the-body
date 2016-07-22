<?php

// error_reporting(E_ERROR | E_WARNING | E_PARSE );
/* image directory constant */
define('_IMG_', get_template_directory_uri().'/library/img/');
/*
Author: Eddie Machado
URL: http://themble.com/spinal/

This is where you can drop your custom functions or
just edit things like thumbnail sizes, header images,
sidebars, comments, etc.
*/

// LOAD spinal CORE (if you remove this, the theme will break)
require_once( 'library/spinal.php' );
/* load cmb2 from inside the theme */

 /*
 Add spinal features
 ---------------------------------------------------------------------------- */
/* spinal theme custom features */

$features = array(
    /* add dashboard redirect */
    'dashboard' => array(
        /* enable or disable feature with true or false */
        'feature-enabled' => true,

        /* disable dashboard entirely */
        'disable' => true,
        /* redirect all users except admin, to admin page */
        'variables' => array(
            'constant' => "__dashboard_redirect", // the contant defined is used in dashboard redirect
            'value' => 'admin.php?page=theme-options' // page to display on user redirect
            ),
        /* file including the functions */
        'file' => 'dashboard-redirect.php'
    ),

    /* fire up our admin css */
    'admin_css' => array(
        'feature-enabled' => true,
        'file' => 'admin-css.php'
    ),
);

/**
 * load up the feature generator, class autoloader and options metaboxes autoloader
 */
require_once('library/features/@autoloader.php');
/* fire up the theme options */
load_features($features);


/**
 * Redirect user after successful login.
 *
 * @param string $redirect_to URL to redirect to.
 * @param string $request URL the user is coming from.
 * @param object $user Logged user's data.
 * @return string
 */
function form_login_redirect( $redirect_to, $request, $user ) {
  //is there a user to check?
  global $user;
  if ( isset( $user->roles ) && is_array( $user->roles ) ) {
    //check for admins
    if ( in_array( 'administrator', $user->roles ) ) {
      // redirect them to the default place
      return page('my-account');
    } else {
        return page('my-account');
    }
  } else {
        /* normal members redirect to community */
    return page('my-account');
  }
}

add_filter( 'login_redirect', 'form_login_redirect', 10, 3 );
add_action('after_setup_theme', 'remove_admin_bar');

function remove_admin_bar() {
if (!current_user_can('administrator') && !is_admin()) {
  show_admin_bar(false);
}
}




// CUSTOMIZE THE WORDPRESS ADMIN (off by default)
// require_once( 'library/admin.php' );

/*********************
LAUNCH spinal
Let's get everything up and running.
*********************/

function spinal_ahoy() {

  //Allow editor style.
  add_editor_style( get_stylesheet_directory_uri() . '/library/css/editor-style.css' );

  // let's get language support going, if you need it
  load_theme_textdomain( 'spinaltheme', get_template_directory() . '/library/translation' );

  // launching operation cleanup
  add_action( 'init', 'spinal_head_cleanup' );
  // A better title
  add_filter( 'wp_title', 'rw_title', 10, 3 );
  // remove WP version from RSS
  add_filter( 'the_generator', 'spinal_rss_version' );
  // remove pesky injected css for recent comments widget
  add_filter( 'wp_head', 'spinal_remove_wp_widget_recent_comments_style', 1 );
  // clean up comment styles in the head
  add_action( 'wp_head', 'spinal_remove_recent_comments_style', 1 );
  // clean up gallery output in wp
  add_filter( 'gallery_style', 'spinal_gallery_style' );

  // enqueue base scripts and styles
  add_action( 'wp_enqueue_scripts', 'spinal_scripts_and_styles', 999 );
  // ie conditional wrapper

  // launching this stuff after theme setup
  spinal_theme_support();

  // adding sidebars to Wordpress (these are created in functions.php)
  add_action( 'widgets_init', 'spinal_register_sidebars' );

  // cleaning up random code around images
  add_filter( 'the_content', 'spinal_filter_ptags_on_images' );
  // cleaning up excerpt
  add_filter( 'excerpt_more', 'spinal_excerpt_more' );
  add_filter( 'option_use_smilies', '__return_true' );

} /* end spinal ahoy */

// let's get this party started
add_action( 'after_setup_theme', 'spinal_ahoy' );


/************* OEMBED SIZE OPTIONS *************/

if ( ! isset( $content_width ) ) {
  $content_width = 680;
}

/************* THUMBNAIL SIZE OPTIONS *************/

// Thumbnail sizes
add_image_size( 'spinal-full-slide', 1670 , 632, true );
add_image_size( 'spinal-full-featured', 1670 , 250, true );
add_image_size( 'spinal-thumb-600', 600, 150, true );
add_image_size( 'spinal-thumb-300', 300, 200, true );
add_image_size( 'spinal-thumb-250', 250, 200, true );
add_image_size( 'spinal-thumb-home-news', 265, 130, true );
add_image_size( 'spinal-thumb-100', 100, 100, true );
add_image_size( 'spinal-thumb-200', 200, 200, true );
add_image_size( 'spinal-thumb-blog', 448, 319, true );
add_image_size( 'spinal-thumb-blog-featured', 720, 920, true );
add_image_size( 'spinal-affections', 321, 900, true );
add_image_size( 'facebook-img', 449, 280, true );


/* add image size for slider slide */

/*
to add more sizes, simply copy a line from above
and change the dimensions & name. As long as you
upload a "featured image" as large as the biggest
set width or height, all the other sizes will be
auto-cropped.

To call a different size, simply change the text
inside the thumbnail function.

For example, to call the 300 x 100 sized image,
we would use the function:
<?php the_post_thumbnail( 'spinal-thumb-300' ); ?>
for the 600 x 150 image:
<?php the_post_thumbnail( 'spinal-thumb-600' ); ?>

You can change the names and dimensions to whatever
you like. Enjoy!
*/

add_filter( 'image_size_names_choose', 'spinal_custom_image_sizes' );

function spinal_custom_image_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'spinal-thumb-900' => __('900px by 400px'),
        'spinal-thumb-600' => __('600px by 150px'),
        'spinal-thumb-300' => __('300px by 200px'),
        'spinal-thumb-250' => __('250px by 200px'),
        'spinal-thumb-100' => __('100px by 100px'),

    ) );
}

/*
The function above adds the ability to use the dropdown menu to select
the new images sizes you have just created from within the media manager
when you add media to your content blocks. If you add more image sizes,
duplicate one of the lines in the array and name it according to your
new image size.
*/

/************* THEME CUSTOMIZE *********************/

/*
  A good tutorial for creating your own Sections, Controls and Settings:
  http://code.tutsplus.com/series/a-guide-to-the-wordpress-theme-customizer--wp-33722

  Good articles on modifying the default options:
  http://natko.com/changing-default-wordpress-theme-customization-api-sections/
  http://code.tutsplus.com/tutorials/digging-into-the-theme-customizer-components--wp-27162

  To do:
  - Create a js for the postmessage transport method
  - Create some sanitize functions to sanitize inputs
  - Create some boilerplate Sections, Controls and Settings
*/

function spinal_theme_customizer($wp_customize) {
  // $wp_customize calls go here.
  //
  // Uncomment the below lines to remove the default customize sections

  // $wp_customize->remove_section('title_tagline');
  // $wp_customize->remove_section('colors');
  // $wp_customize->remove_section('background_image');
  // $wp_customize->remove_section('static_front_page');
  // $wp_customize->remove_section('nav');

  // Uncomment the below lines to remove the default controls
  // $wp_customize->remove_control('blogdescription');

  // Uncomment the following to change the default section titles
  // $wp_customize->get_section('colors')->title = __( 'Theme Colors' );
  // $wp_customize->get_section('background_image')->title = __( 'Images' );
}

add_action( 'customize_register', 'spinal_theme_customizer' );

/************* ACTIVE SIDEBARS ********************/

// Sidebars & Widgetizes Areas
function spinal_register_sidebars() {
  register_sidebar(array(
    'id' => 'sidebar1',
    'name' => __( 'Sidebar 1', 'spinaltheme' ),
    'description' => __( 'The first (primary) sidebar.', 'spinaltheme' ),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h4 class="widgettitle">',
    'after_title' => '</h4>',
  ));

  /*
  to add more sidebars or widgetized areas, just copy
  and edit the above sidebar code. In order to call
  your new sidebar just use the following code:

  Just change the name to whatever your new
  sidebar's id is, for example:

  register_sidebar(array(
    'id' => 'sidebar2',
    'name' => __( 'Sidebar 2', 'spinaltheme' ),
    'description' => __( 'The second (secondary) sidebar.', 'spinaltheme' ),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h4 class="widgettitle">',
    'after_title' => '</h4>',
  ));

  To call the sidebar in your template, you can just copy
  the sidebar.php file and rename it to your sidebar's name.
  So using the above example, it would be:
  sidebar-sidebar2.php

  */
} // don't remove this bracket!


/************* COMMENT LAYOUT *********************/

// Comment Layout
function spinal_comments( $comment, $args, $depth ) {
   $GLOBALS['comment'] = $comment; ?>
  <div id="comment-<?php comment_ID(); ?>" <?php comment_class('cf'); ?>>
    <article  class="cf">
      <header class="comment-author vcard">
        <?php
        /*
          this is the new responsive optimized comment image. It used the new HTML5 data-attribute to display comment gravatars on larger screens only. What this means is that on larger posts, mobile sites don't have a ton of requests for comment images. This makes load time incredibly fast! If you'd like to change it back, just replace it with the regular wordpress gravatar call:
          echo get_avatar($comment,$size='32',$default='<path_to_url>' );
        */
        ?>
        <?php // custom gravatar call ?>
        <?php
          // create variable
          $bgauthemail = get_comment_author_email();
        ?>
        <img data-gravatar="http://www.gravatar.com/avatar/<?php echo md5( $bgauthemail ); ?>?s=40" class="load-gravatar avatar avatar-48 photo" height="40" width="40" src="<?php echo get_template_directory_uri(); ?>/library/images/nothing.gif" />
        <?php // end custom gravatar call ?>
        <?php printf(__( '<cite class="fn">%1$s</cite> %2$s', 'spinaltheme' ), get_comment_author_link(), edit_comment_link(__( '(Edit)', 'spinaltheme' ),'  ','') ) ?>
        <time datetime="<?php echo comment_time('Y-m-j'); ?>"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php comment_time(__( 'F jS, Y', 'spinaltheme' )); ?> </a></time>

      </header>
      <?php if ($comment->comment_approved == '0') : ?>
        <div class="alert alert-info">
          <p><?php _e( 'Your comment is awaiting moderation.', 'spinaltheme' ) ?></p>
        </div>
      <?php endif; ?>
      <section class="comment_content cf">
        <?php comment_text() ?>
      </section>
      <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
    </article>
  <?php // </li> is added by WordPress automatically ?>
<?php
} // don't remove this bracket!


/* add js to admin */
function spinal_admin_add_scripts($hook) {
    /* conditional targeting pages */
    // if ( 'edit.php' != $hook ) {
    //     return;
    // }
    wp_enqueue_script( 'admin_script_js', get_stylesheet_directory_uri() . '/library/js/admin.js',  array( 'jquery' ) );
}
add_action( 'admin_enqueue_scripts', 'spinal_admin_add_scripts', 999 );


/* spinal custom breadcrumb */
function spinal_breadcrumb() {
    $breadcrumb = new spinal_breadcrumb();
    echo $breadcrumb->get();
}

$option_posts_per_page = get_option( 'posts_per_page' );
add_action( 'init', 'my_modify_posts_per_page', 0);
function my_modify_posts_per_page() {
    add_filter( 'option_posts_per_page', 'my_option_posts_per_page' );
}
function my_option_posts_per_page( $value ) {
    global $option_posts_per_page;
    if ( is_tax( 'effectif') ) {
        return 2;
    } else {
        return $option_posts_per_page;
    }
}

/* post navigation next */
function spinal_next_post($label) {
    global $post;
    /* get next post */
    $next_post = get_next_post();
    /* check if next post exists, and that it has an id */
    if($next_post) {
        $permalink = get_the_permalink($next_post);
        echo '<a href="'.$permalink.'">'.$label.'</a>';
    }
    /* no post so return false */
    return false;
}
/* post navigation previous */
function spinal_prev_post($label) {
    global $post;
    /* get next post */
    $next_post = get_previous_post();
    /* check if next post exists, and that it has an id */
    if($next_post) {
        $permalink = get_the_permalink($next_post);
        echo '<a href="'.$permalink.'">'.$label.'</a>';
    }
    /* no post so return false */
    return false;
}



/* temporary development fix used for permaling rewrites, comment when done developing */




function ntwb_bbpress_custom_pagination( $args ) {

    $args['prev_text'] = '<i class="fa fa-angle-left"></i>';
    $args['next_text'] = '<i class="fa fa-angle-right"></i>';
    $args['type'] = 'array';
    $args['mid_size'] = 6;
    $args['end_size'] = 2;
    return $args;
}
add_filter( 'bbp_topic_pagination',          'ntwb_bbpress_custom_pagination' );
add_filter( 'bbp_replies_pagination',        'ntwb_bbpress_custom_pagination' );
add_filter( 'bbp_search_results_pagination', 'ntwb_bbpress_custom_pagination' );



function bbp_paginate() {
    $bbp = bbpress();
    $links = $bbp->topic_query->pagination_links;
    $paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');
    if( is_array( $links ) ) {
       echo '<ul class="pagination clearfix">';
       foreach ( $links as $page ) {
               echo '<li>'.str_replace('page-numbers current', 'active', $page).'</li>';
       }
       echo '</ul>';
    }

}



add_filter( 'cron_schedules', 'example_add_cron_interval' );
 
function example_add_cron_interval( $schedules ) {
    $schedules['every_minute'] = array(
        'interval' => 60,
        'display'  => esc_html__( 'Every Minute' ),
    );
 
    return $schedules;
}



//Schedule cronjob for premium users
add_action('hb_twicedaily_event', 'check_expired_premium');

function humanbody_check_premium() {
  if ( !wp_next_scheduled( 'hb_twicedaily_event' ) ) {
    wp_schedule_event( time(), 'twicedaily', 'hb_twicedaily_event');
  }
}
add_action('wp', 'humanbody_check_premium');

function check_expired_premium() {

  //get all premium users that became premium before 30 days ago
  $time_to_compare = time() - (30*24*60*60);
  //echo date ('Y-m-d H:s', $time_to_compare);
  
  $premiumusers = get_users( array( 'role' => 'premium_member', 'meta_key'     => 'date_when_became_premium', 'meta_value'   => $time_to_compare, 'meta_compare' => '<') );
  
  foreach ( $premiumusers as $user ) {
    $date_premium = get_user_meta($user->ID, 'date_when_became_premium', true);
    
    if (!empty($date_premium)) {
      
      //If the user was made "premium" from the admin panel, we don't have a date_when_became_premium
      //We only have date_when_became_premium meta when the user actually payed with paypal
      
      
      //We check again if actually has passed 30 days
      $date_premium_timestamp = $date_premium;
      $time_diff = time() - $date_premium_timestamp;
      $days_passed = floor($time_diff/(60*60*24));
      
      
      $allcaps = $user->get_role_caps();
      
            /* only change roles if the current user is not an admin */
            if($allcaps['administrator'] != 1) {      
        if ($days_passed>30) {
          
          $today = time();
          update_user_meta( $user->ID, 'date_premium_has_expired', $today);
          
                /* set normal member role */
                $user->remove_role( 'premium_member' );
                $user->remove_cap( 'premium' );
                $user->add_role( 'normal_member' );
                $data = array(
                  'ID' => $user->ID,
                  'role' => 'normal_member'
                );
                /* update user with new roles so they are visible in admin */
                wp_update_user($data);
                do_action('expired-membership');
                      
        }
      } 
      
    }
  }

}



//Schedule cronjob for premium users trial
add_action('hb_twicedaily_trial_event', 'check_expired_trial_premium');
function humanbody_check_trial_premium() {
  if ( !wp_next_scheduled( 'hb_twicedaily_trial_event' ) ) {
    wp_schedule_event( time(), 'twicedaily', 'hb_twicedaily_trial_event');
  }
}
add_action('wp', 'humanbody_check_trial_premium');

function check_expired_trial_premium() {
  //days of the free trial is set in the admin panel
  $options = get_option('theme-options');
  $trial_days = (isset($options['trial_member']) and !empty($options['trial_member']) )? intval($options['trial_member']) : 30;

  //get all premium users that register and have free trial before N(trial days set by the admin) days ago
  $time_to_compare = time() - ($trial_days*24*60*60);
  
  $premiumusers = get_users( array( 'role' => 'premium_member', 'meta_key' => 'trial_premium', 'meta_value'   => $time_to_compare, 'meta_compare' => '<') );

  foreach ( $premiumusers as $user ) {
    $date_trial_premium = get_user_meta($user->ID, 'trial_premium', true);
    
    $allcaps = $user->get_role_caps();
      
    /* only change roles if the current user is not an admin */
    if($allcaps['administrator'] != 1) {
          
      $today = time();
      update_user_meta( $user->ID, 'date_premium_trial_has_expired', $today);
      
      delete_user_meta( $user->ID, 'trial_premium');
          
          /* set normal member role */
            $user->remove_role( 'premium_member' );
            $user->remove_cap( 'premium' );
            $user->add_role( 'normal_member' );
            $data = array(
              'ID' => $user->ID,
              'role' => 'normal_member'
            );
            /* update user with new roles so they are visible in admin */
            wp_update_user($data);
            do_action('expired-membership');
    }
          
  }


}



/* Clear next scheduled cronjob */
//wp_clear_scheduled_hook( 'hb_twicedaily_event');
//wp_clear_scheduled_hook( 'hb_twicedaily_trial_event');


//Check scheduled cronjobs
function check_cron_times (){
  // $next_premium_cron = wp_next_scheduled( 'hb_twicedaily_event' );
  // echo date('Y-m-d h:m:s', $next_premium_cron);
  // $today = date('Y-m-d h:m:s');
  // echo '<BR>Today: '.$today;

  // $next_premium_cron = wp_next_scheduled( 'hb_twicedaily_trial_event' );
  // echo date('Y-m-d h:m:s', $next_premium_cron);
  // $today = date('Y-m-d h:m:s');
  // echo '<BR>Today: '.$today; 
    
  /* All cronjobs */
  // echo '<pre>'; 
  // print_r( _get_cron_array() ); 
  // echo '</pre>';
}

//check_cron_times();




flush_rewrite_rules();

?>