<?php
/* ajax load cities based on id */
add_action( 'wp_ajax_filter_users_cities', 'ajax_filter_users_cities' );
/* ajax load cities based on id */
add_action( 'wp_ajax_nopriv_filter_users_cities', 'ajax_filter_users_cities' );
function ajax_filter_users_cities() {
    $co = new csv_import_cities;
    $_POST = filter::post();
    $current_user = wp_get_current_user();
    $countryname = $co->get_country_name_by_iso($_POST['iso']);
    $cities = explode(',', $_POST['cities'] );
    $fi = explode(',', $_POST['first_interests']);
    $si = explode(',', $_POST['second_interests']);
    $ti = explode(',', $_POST['third_interests']);

    $affections = explode(',', $_POST['alist']);




    if(count($affections) > 0 ) {
        $fullarray['relation'] = 'OR';
        foreach ($affections as $affection) {
            $fullarray[] = array(
                'key'     => 'affections',
                'value'   => $affection,
                'compare' => 'LIKE'
            );
        }
    }


    $args = array(
    	'meta_query' => array(
    		$fullarray,
    	)
     );

    $user_query = new WP_User_Query( $args );


    if($user_query->results) {
        foreach ( $user_query->results as $user ) {
            $meta = get_user_meta($user->ID);
            $country = $meta['country'][0];
            $city = $meta['city'][0];
            $filterCountry = strtolower(trim($country)) == strtolower(trim($countryname));
            if($countryname) {
                $gate = $filterCountry;
            }
            else {
                $gate = true;
            }
            if($current_user->ID == $user->ID) {
                $gate = false;
            }

            if($gate) {
                $user_id = $user->ID;
                $user = new user_info($user_id);
                $interests_1 = explode(',', $user->interests_1);
                $interests_2 = explode(',', $user->interests_2);
                $interests_3 = explode(',', $user->interests_3);


                $exists1 = array_intersect($interests_1, $fi);
                    if(!$exists1[0]) unset($exists1[0]);
                $exists2 = array_intersect($interests_2, $si);
                    if(!$exists2[0]) unset($exists2[0]);
                $exists3 = array_intersect($interests_3, $ti);
                    if(!$exists3[0]) unset($exists3[0]);



                if(((count($exists2) > 0  || count($exists3) > 0 ) || (!$fi[0] && !$si[0] && !$ti[0])) && $current_user->ID != $user->ID) {


                    $meta = get_user_meta($user->ID);
                    $country = $meta['country'][0];

                    if($country) {
                        $countries[$co->get_country_iso_by_name($country)] = $co->get_country_iso_by_name($country);
                    }
                }
            }
        }
    }










    if(count($countries) > 0) {
        foreach ($countries as $key => $value) {
            if(strlen($key) > 0) {
                $list .= '.amcharts-map-area-'.$key.',';
            }
        }
    }

    echo substr($list, 0, -1);
    die();
}
 ?>
