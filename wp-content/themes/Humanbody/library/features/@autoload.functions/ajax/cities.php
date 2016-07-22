<?php
/* ajax load cities based on id */
add_action( 'wp_ajax_loadcities', 'ajax_load_cities' );
/* ajax load cities based on id */
add_action( 'wp_ajax_nopriv_loadcities', 'ajax_load_cities' );

/* ajax load cities based on iso ( integration with maps ) */
add_action( 'wp_ajax_loadcitiesiso', 'ajax_load_cities_iso' );
/* ajax load cities based on iso ( integration with maps ) */
add_action( 'wp_ajax_nopriv_loadcitiesiso', 'ajax_load_cities_iso' );

/* ajax load cities based on iso ( integration with maps ) */
add_action( 'wp_ajax_filterusers', 'improved_ajax_search_users' );
/* ajax load cities based on iso ( integration with maps ) */
add_action( 'wp_ajax_nopriv_filterusers', 'improved_ajax_search_users' );


function ajax_load_cities()
{
    /* purge everything on post vars */
    $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    /* get country id */
    $country_id = explode(" ", $_POST['cid']);
    $country_id = $country_id[0];
    /* get the country cities object */
    $co = new csv_import_cities();
    /* get cities */
    $cities = $co->get_cities($country_id);

    /* do a loop and echo the output needed for ajax node population */
    foreach ($cities as $city) {
        echo '<div class="item" data-value="'.$city->city.'" data-text="'.$city->city.'">
			'.$city->city.'
		</div>'."\r\n";
    }
    /* used to disable the 0 from output */
    die();
}

function ajax_load_cities_iso()
{
    /* purge everything on post vars */
    $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    $iso = $_POST['iso'];

    /* get cities */
    if($iso) :
        /* get the country cities object */
        $co = new csv_import_cities();
    $cities = $co->get_cities_by_iso($iso);
    /* do a loop and echo the output needed for ajax node population */
    if(is_array($cities) && count($cities) > 0) :
        foreach ($cities as $city) {
            echo '<div class="item" data-value="'.$city->city.'" data-text="'.$city->city.'">
    			'.$city->city.'
    		</div>'."\r\n";
        }
    else :
        echo 'no cities for country code: '.$iso;
    endif;
endif;
    die();
}


function ajax_search_users() {
    /* purge everything on post vars */
    $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    $cities = explode(',', $_POST['cities'] );
    $fi = explode(',', $_POST['first_interests']);
    $si = explode(',', $_POST['second_interests']);
    $ti = explode(',', $_POST['third_interests']);

    global $wpdb;


    $current_user = new user_info();
    $table= $wpdb->prefix.'usermeta';
    $table_users = $wpdb->prefix.'users';
    //($table.meta_key, LOWER($table.meta_value)) = ('country', '$country') OR

    /* custom sql n cities or statement */
    if(is_array($cities) && count($cities) > 0) :
        $i = 0;
        foreach ($cities as $city) {
            $i++;
            if($i != count($cities)) :
                $or = " OR ";
            else :
                $or = "";
            endif;
            $cities_search .= "LOWER($table.meta_value)='$city'".$or;
        }
        $sql = "SELECT * FROM `$table` WHERE $table.meta_key = 'city' AND (".$cities_search.")";
    else :
        $sql = "SELECT * FROM `$table` WHERE $table.meta_key = 'country'";

    endif;

    $users = $wpdb->get_results($sql);

    foreach ($users as $row) {
        $user_id = $row->user_id;
        $users_array[$user_id][$row->meta_key] = $row->meta_value;
    }
    if(is_array($users_array) && count($users_array) > 0) :

        foreach ($users_array as $user_id => $data) {

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


            if(((count($exists1) > 0 || count($exists1) > 0  || count($exists1) > 0 ) || (!$fi[0] && !$si[0] && !$ti[0])) && $current_user->ID != $user->ID) :
                $mcr = new user_messages();
                $flo = new friends_list();
                $enc_id = $mcr->encrypt($user->ID);
                $profile = page('member').'?u='.$enc_id;
                $message_user = page('my-account-message-room').'?new=r&u='.$enc_id;

                $add = $flo->get_add_link($user_id);
                echo "<tr>
                    <td>
                        <div class='messenger-wrapper'>

                            <div class='messenger-inner'>
                                <img src='$user->avatar' alt='img'>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class='friend-name'>
                            <a href='$profile'> $user->name</a>
                            <span>$user->city, $user->country</span>
                        </div>
                    </td>
                    <td>
                        ".
                        (
                        $flo->is($user_id)
                        ?
                        ''
                        :
                        "<a href='$add'>
                            <img src='"._IMG_."addfriend.svg' alt='img' width='16'>
                            Add to friends list
                        </a>"
                        )
                        ."
                    </td>
                    <td>
                        <a href='$message_user'>
                            <i class='fa fa-envelope-o'></i>
                            Send a message
                        </a>
                    </td>
                    <td>
                        <a href='#fancybox-get-together' class='add-together' data-u='$enc_id'>
                            <img src='"._IMG_."crowd.svg' alt='get-togheter' width='20'>
                            Invite to get-together
                        </a>
                    </td>
                </tr>";

            endif;

    }
    endif;
    die();



    //echo $user;

}
function improved_ajax_search_users() {
    $co = new csv_import_cities;
    $_POST = filter::post();
    $current_user = wp_get_current_user();
    $countryname = $co->get_country_name_by_iso($_POST['country']);
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

            if(is_array($cities) && count($cities) > 0 && $cities[0]) {
                $citygate = false;
                foreach ($cities as $key => $value) {
                    if($city == $value) {
                        $citygate = true;
                    }
                }
            }
            else {
                $citygate = true;
            }

            if($gate && $citygate) {
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


                echo $user->ID;
                if(((count($exists2) > 0  || count($exists3) > 0 ) || (!$fi[0] && !$si[0] && !$ti[0])) && $current_user->ID != $user->ID) :

                    $mcr = new user_messages();
                    $flo = new friends_list();
                    $enc_id = $mcr->encrypt($user->ID);
                    $profile = page('member').'?u='.$enc_id;
                    $message_user = page('my-account-message-room').'?new=r&u='.$enc_id;

                    $add = $flo->get_add_link($user_id);
                    echo "<tr>
                        <td>
                            <div class='messenger-wrapper'>

                                <div class='messenger-inner'>
                                    <img src='$user->avatar' alt='img'>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class='friend-name'>
                                <a href='$profile'> $user->name</a>
                                <span>$user->city, $user->country</span>
                            </div>
                        </td>
                        <td>
                            ".
                            (
                            $flo->is($user_id)
                            ?
                            ''
                            :
                            "<a href='$add'>
                                <img src='"._IMG_."addfriend.svg' alt='img' width='16'>
                                Add to friends list
                            </a>"
                            )
                            ."
                        </td>
                        <td>
                            <a href='$message_user'>
                                <i class='fa fa-envelope-o'></i>
                                Send a message
                            </a>
                        </td>
                        <td>
                            <a href='#fancybox-get-together' class='add-together' data-u='$enc_id'>
                                <img src='"._IMG_."crowd.svg' alt='get-togheter' width='20'>
                                Invite to get-together
                            </a>
                        </td>
                    </tr>";

                endif;
            }
        }
    }
    echo substr($list, 0, -1);
    die();
}
 ?>
