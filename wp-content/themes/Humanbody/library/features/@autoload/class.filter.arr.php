<?php

/* filters a 2 dimension array of magic quotes xss atacks */
class filter
{
    /**
     * returns a filtered value
     * @method value
     * @param  string|integer|float $value
     * @return string|integer|float
     */
    public static function value($value) {
        return self::__filter($value);
    }
    /**
     * return a filtered  $_GET method
     * @method get
     * @return array    filtered $_GET method
     */
    public static function get() {
        return self::__filter($_GET);
    }
    /**
     * return a filtered  $_POST method
     * @method post
     * @return array    filtered $_POST method
     */
    public static function post() {
        return self::__filter($_POST);
    }
    /**
     * return a filtered array or simple value ()
     * @method filter
     * @param  mixed    array or string | number
     * @return array    filtered array
     */
    public static function __filter($mixed) {

        /* array filter support */
        if(is_array($mixed) && count($mixed) > 0) :
           $temp = array();
           foreach ($mixed as $key => $value) :
               if(is_array($value)) :
                   foreach ($value as $key2 => $value2) :
                       /* filter */
                       $filtered = filter_var($value2, FILTER_SANITIZE_STRING);
                       $filtered = filter_var($filtered, FILTER_SANITIZE_MAGIC_QUOTES);
                       /* recompile */
                       $temp[$key][$key2] = $filtered;
                   endforeach;
               else :
                   /* filter */
                   $filtered = filter_var($value, FILTER_SANITIZE_STRING);
                   $filtered = filter_var($filtered, FILTER_SANITIZE_MAGIC_QUOTES);
                   /* recompile */
                   $temp[$key] = $filtered;
               endif;
           endforeach;

           return $temp;
       else :
           $filtered = filter_var($mixed, FILTER_SANITIZE_STRING);
           $filtered = filter_var($filtered, FILTER_SANITIZE_MAGIC_QUOTES);
           return $filtered;
       endif;
   }
}

 ?>
