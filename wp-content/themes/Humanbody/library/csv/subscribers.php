<?php
header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=subscribers.csv");
header("Pragma: no-cache");
header("Expires: 0");

error_reporting(E_ALL);
define('WP_USE_THEMES', true);
/** Loads the WordPress Environment and Template */
include "../../../../../wp-load.php";



 /* check if user logged in is admin, security mostly */
if(wp_get_current_user()->caps['administrator']) :

    global $wpdb;
    $table = $wpdb->prefix.'spinal_subscribers';
    $query = "SELECT * FROM $table WHERE 1";
    $rows = $wpdb->get_results($query);

    echo "Email\r\n";
    foreach ($rows as $email) {
        echo $email->email."\r\n";
    }

endif;

 ?>
