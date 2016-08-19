<?php

wp_enqueue_script('jquery');
wp_enqueue_script( 'select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.js');
wp_enqueue_style('select2-style', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css');


function renderInterests()
{
    global $wpdb;
    $wpdb->interests = $wpdb->prefix . 'interests_types';
    $wpdb->interest_cat = $wpdb->prefix . 'interests_categories';
    $items = $wpdb->get_results("SELECT *, t.ID as iID FROM $wpdb->interests as t LEFT JOIN $wpdb->interest_cat as c ON t.category_id=c.ID ORDER BY t.category_id ASC");
    echo "<select class='select2' name='interests' multiple='multiple' style='width:100%;' placeholder='Click here an begin typing...'>";
    $lastCat = '';
    foreach ($items as $item) {
        if($item->category != $lastCat) {
            if ($lastCat!='') echo "</optgroup>";
            $newCat = true; $lastCat = $item->category;
        } else { $newCat = false; }
        if($newCat) echo "<optgroup label='$lastCat'>";
        echo "<option value='$item->iID'>$item->interest_type</option>";
    }
    echo "</select>";
}


