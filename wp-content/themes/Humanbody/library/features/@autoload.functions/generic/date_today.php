<?php
function date_today($mysql_date) {
    $date = explode(' ', $mysql_date);
    $old_date_timestamp = strtotime($date[0]);
    $new_date = date('d.m.Y', $old_date_timestamp);
    $currentdate = date('d.m.Y');
    if($new_date == $currentdate) :
        $dateago = 'today';
    elseif($currentdate-$new_date == 1):
        $dateago = 'today';
    else :
        $dateago = $new_date;
    endif;
    $timeadded = strtoupper(date('h:i a', strtotime($date[1])));
    return array($dateago, $timeadded);
}
function member_since($date) {
    $timestamp = strtotime($date);
    return date('d - F - Y', $timestamp);
}
 ?>
