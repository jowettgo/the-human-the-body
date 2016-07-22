<?php
function to_object($array) {
    $object = new stdClass();
    foreach ($array as $key => $value) :
        $object->$key = $value;
    endforeach;
    return $object;
}

 ?>
