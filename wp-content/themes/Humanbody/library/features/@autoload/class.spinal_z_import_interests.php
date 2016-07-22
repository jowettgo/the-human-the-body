<?php


/**
 * Handle csv imports
 */
class csv_import_interests
{

    private static $table_main = 'main_interests';
    private static $table_categories = 'interests_categories';
    private static $table_types = 'interests_types';

    /* add import to run on  */
    function __construct($file = false, $in = false)
    {

        /* import the csv and add them to the database */
        if($file) :
            $this->import($file, $in);
        endif;
    }

    /**
     * import csv file and insert the data as columns
     * @method import
     * @param  string   $file absolut path to the csv file
     * @param  integer  $in   assign a category number to the data
     * @return boolean  true or false on success or fail
     */
    function import($file, $in) {
        /* check for the path of file */

        if($file) :
            $arrResult  = array();
            /* create file pointer resource */
            $handle = fopen($file, "r");
            /* only continue if the handle is not empty */
            if(empty($handle) === false) :
                /* read line by line */
                while(($data = fgetcsv($handle, 1000, ",")) !== FALSE) :

                    if($data[0]) {
                        $main = $data[0];
                    }
                    if($data[1]) {
                        $category = $data[1];
                    }
                    $temp[$main][$category][] = $data[2];

                    //$this->insert($data[0], $in);
                endwhile;
                /* close the file pointer to the file */
                fclose($handle);

                foreach ($temp as $interest => $category) {
                    // insert main interest in table
                    if($interest) :
                        $interest_id = self::insert_interest($interest);
                        foreach ($category as $categoryname => $types) :
                            // insert categories in table if it exists
                            if($categoryname) :
                                $category_id = self::insert_category($categoryname, $interest_id);
                                if(is_array($types) && count($types) > 0) :
                                    foreach ($types as $type) :
                                        //insert type in table if it exists
                                        if($type) :
                                            self::insert_type($type, $category_id);
                                        endif;
                                    endforeach;
                                endif;
                            endif;
                        endforeach;
                    endif;

                }
                return true;
            endif;
            return false;
        endif;
        return false;
    }
    public static function get_interests()
    {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $main_interests_table = $prefix.self::$table_main;

        $sql = "SELECT * FROM $main_interests_table";
        $rows = $wpdb->get_results($sql);
        return $rows;
    }
    function get_interest_object_by_id($id) {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $type_table = $prefix.self::$table_types;


        $sql = "SELECT * FROM $type_table WHERE `ID`=$id";
        $rows = $wpdb->get_results($sql);
        $interest_type = $rows[0];
        return $interest_type;
    }
    function get_category_object_by_id($id) {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $category_table = $prefix.self::$table_categories;
        $sql = "SELECT * FROM $category_table WHERE `ID`=$id";
        $rows = $wpdb->get_results($sql);
        $category = $rows[0];
        return $category;
    }
    function get_type_object_by_id($id) {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $main_table = $prefix.self::$table_main;
        $sql = "SELECT * FROM $main_table WHERE `ID`=$id";
        $rows = $wpdb->get_results($sql);
        $category = $rows[0];
        return $category;
    }

    public static function get_interest_by_id($id)
    {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $main_interests_table = $prefix.self::$table_main;

        $sql = "SELECT * FROM $main_interests_table WHERE `ID`=$id";
        $rows = $wpdb->get_results($sql);
        $rows = $rows[0];
        return $rows->interest;
    }
    public static function get_categories($interest_id)
    {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $category_table = $prefix.self::$table_categories;

        $sql = "SELECT * FROM $category_table WHERE `interest_id`=$interest_id";
        $rows = $wpdb->get_results($sql);
        return $rows;
    }
    public static function get_types($category_id)
    {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $types_table = $prefix.self::$table_types;

        $sql = "SELECT * FROM $types_table WHERE `category_id`=$category_id";
        $rows = $wpdb->get_results($sql);
        return $rows;
    }
    private static function insert_interest($value) {
        global $wpdb;
        $table = $wpdb->prefix.self::$table_main;
        $wpdb->insert(
        	$table,
        	array(
        		'interest' => $value,
        	)
        );
        return $wpdb->insert_id;

    }
    private static function insert_category($value, $interest_id) {
        global $wpdb;
        $table = $wpdb->prefix.self::$table_categories;
        $wpdb->insert(
        	$table,
        	array(
        		'category' => $value,
        		'interest_id' => $interest_id
        	)
        );
        return $wpdb->insert_id;
    }
    private static function insert_type($value, $category_id) {
        global $wpdb;
        $table = $wpdb->prefix.self::$table_types;
        $wpdb->insert(
        	$table,
        	array(
        		'interest_type' => $value,
        		'category_id' => $category_id
        	)
        );
    }
    /**
     * empty all the rows in the interests tables
     * @method empty_all
     * @return NULL
     */
    public static function empty_all()
    {
        global $wpdb;

        $interests = $wpdb->prefix.self::$table_main;
        $types = $wpdb->prefix.self::$table_types;
        $category = $wpdb->prefix.self::$table_categories;
        $wpdb->query("TRUNCATE TABLE `$interests`");
        $wpdb->query("TRUNCATE TABLE `$category`");
        $wpdb->query("TRUNCATE TABLE `$types`");
    }
    /**
     * count all the csv fields in the database
     * @method count
     * @param  integer $in row category identifier
     * @return integer     return total count of interests
     */
    public static function count()
    {
        global $wpdb;
        /* get the count of all the csv fields */
        $types = $wpdb->prefix.self::$table_types;
        $category = $wpdb->prefix.self::$table_categories;
        $sql = "SELECT COUNT(*)  FROM $types";
        $rows1 = $wpdb->get_results($sql);
        $rows1 = (array)$rows1[0];
        $sql = "SELECT COUNT(*)  FROM $category";
        $rows2 = $wpdb->get_results($sql);
        $rows2 = (array)$rows2[0];
        return $rows1['COUNT(*)']+$rows2['COUNT(*)'];
    }
    public function get($type)
    {
        global $wpdb;
        /* get the count of all the csv fields */
        $table = $wpdb->prefix.$this->table_name;
        $sql = "SELECT * FROM `$table` WHERE `in`='$in'";
        $rows = $wpdb->get_results($sql);

        return $rows;
    }
    /**
     * install the table needed for the csv fields
     * @method install
     * @return boolean
     */
    public static function install() {

        /* main interests table
        ---------------------------------------------------- */
        /* table name */
        $interests_main_table = self::$table_main;
        /* table installer class */
        $installer = new spinal_table_install();
        /* #NOTE
        the sql statement needs [table_name] and [charset]
        the installer will take care of the rest
         */
        $sql = "CREATE TABLE IF NOT EXISTS [table_name] (
                  `ID` mediumint(9) NOT NULL AUTO_INCREMENT COMMENT 'interest id',
                  `interest` varchar(100) NOT NULL COMMENT 'interest',
                  PRIMARY KEY (`ID`),
                  UNIQUE KEY `ID` (`ID`)
              ) [charset];";
             /* returns true or false on install success or error */
        $installer->install($sql,$interests_main_table);

        /* categories table
        ---------------------------------------------------- */
        /* table name */
        $interests_category_table = self::$table_categories;
        /* #NOTE
        the sql statement needs [table_name] and [charset]
        the installer will take care of the rest
         */
        $sql = "CREATE TABLE IF NOT EXISTS [table_name] (
                  `ID` mediumint(9) NOT NULL AUTO_INCREMENT COMMENT 'interest id',
                  `category` varchar(100) NOT NULL COMMENT 'interest category',
                  `interest_id` int(10) NOT NULL COMMENT 'the interest parent id',
                  PRIMARY KEY (`ID`),
                  UNIQUE KEY `ID` (`ID`)

              ) [charset];";
             /* returns true or false on install success or error */
        $installer->install($sql,$interests_category_table);

        /* types table
        ---------------------------------------------------- */
        /* table name */
        $interests_type_table = self::$table_types;
        /* #NOTE
        the sql statement needs [table_name] and [charset]
        the installer will take care of the rest
         */
        $sql = "CREATE TABLE IF NOT EXISTS [table_name] (
                  `ID` mediumint(9) NOT NULL AUTO_INCREMENT COMMENT 'interest id',
                  `interest_type` varchar(100) NOT NULL COMMENT 'interest type',
                  `category_id` int(10) NOT NULL COMMENT 'the category parent id',
                  PRIMARY KEY (`ID`),
                  UNIQUE KEY `ID` (`ID`)
              ) [charset];";
             /* returns true or false on install success or error */
        $installer->install($sql,$interests_type_table);
    }

}
/* run the installer, it only runs once if the database does not exist */
csv_import_interests::install();
 ?>
