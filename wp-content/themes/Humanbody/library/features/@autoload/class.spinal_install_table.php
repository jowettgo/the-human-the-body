<?php

/**
 *
 */
class spinal_table_install
{
    /**
     * install table in wordpress, takes the table name and sql syntax and just handles the rest
     * @method install
     * @param  string  $sql         the syntax needs to have the [table] string to replace the table name with the wordpress prefix
     * @param  string  $tablename   the table name without any prefix
     */
    public function install($sql, $tablename)
    {
        global $wpdb;
        /* get table name and add the prefix of the install of wordpress */
        $_table = $wpdb->prefix . $tablename;
        /* table collation */
        $charset_collate = $wpdb->get_charset_collate();

        if(!self::_exists($_table)) :
            /* do a switch on the vars */
            $sql = str_replace(array('[table_name]', '[charset]'), array($_table, $charset_collate), $sql);
            /* get wordpress upgrade */
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            /* run install */
            $done = dbDelta( $sql );
            return $done;
        endif;
        return false;
    }
    /**
     * _exists checks if the table exists (installed), used in the table install check
     * @method _exists
     * @param  string  $table_name  provide the table name to check it against
     * @return boolean              true or false  if the table exists or not
     */
    public function _exists($table_name)
	{
		global $wpdb;
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) :
			return true;
		endif;
		return false;
	}

}

 ?>
