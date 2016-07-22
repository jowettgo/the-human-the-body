<?php


/**
 * Handle csv imports
 */
class csv_import_affections
{
    private $table_affections = 'sp_affections';

    /* add import to run on  */
    function __construct($file = false, $in = false)
    {
        /* run the installer, it only runs once if the database does not exist */
        $this->install();

        /* import the csv and add them to the database */
        if($file) :
            $this->import($file);
        endif;
    }

    /**
     * import csv file and insert the data as columns 3 columns data iso(1) -> country(1) -> cities(N)
     * @method import
     * @param  string   $file absolut path to the csv file
     * @param  integer  $in   assign a category number to the data
     * @return boolean  true or false on success or fail
     */
     function import($file) {
         /* check for the path of file */

         if($file) :
             $this->remove_all();
             $arrResult = array();
             /* create file pointer resource */
             $handle = fopen($file, "r");
             if(empty($handle) === false) :
                /* read line by line */
                while(($data = fgetcsv($handle)) !== FALSE) :

                    $this->insert_affection($data[0]);
                endwhile;
                /* close the file pointer to the file */
                fclose($handle);



                 return true;
             endif;
             return false;
         endif;
         return false;
     }

    /**
     * insert data from csv into the database
     * @method insert
     * @param  string $data csv line
     * @param  unteger $in   identifier
     * @return NULL
     */
    function insert_affection($affection) {
        global $wpdb;
        $table = $wpdb->prefix.$this->table_affections;
        $wpdb->insert(
        	$table,
        	array(
        		'affection' => $affection
        	)
        );
    }

    /**
     * delete all the rows in the category identifier
     * @method delete
     * @param  integer $in identifier for the rows to be deleted
     * @return NULL
     */
    public function remove_all()
    {
        global $wpdb;
        $affections = $wpdb->prefix.$this->table_affections;
        $wpdb->query("TRUNCATE TABLE `$affections`");
    }
    /**
     * count all the csv fields in the database
     * @method count
     * @param  integer $in row category identifier
     * @return integer     return total count of interests
     */
    public function count()
    {
        global $wpdb;
        /* get the count of all the csv fields */
        $table = $wpdb->prefix.$this->table_affections;
        $sql = "SELECT COUNT(*) FROM $table";
        $rows = $wpdb->get_results($sql);
        $rows = (array)$rows[0];
        return $rows['COUNT(*)'];
    }
    public function get_affections()
    {
        global $wpdb;
        $table = $wpdb->prefix.$this->table_affections;
        $sql = "SELECT * FROM `$table`";
        $rows = $wpdb->get_results($sql);
        return $rows;
    }

    /**
     * install the table needed for the csv fields
     * @method install
     * @return boolean
     */
    function install() {
        /* table name */
        $city_table = $this->table_affections;
        /* table installer class */
        $installer = new spinal_table_install();
        /* #NOTE
        the sql statement needs [table_name] and [charset]
        the installer will take care of the rest
         */
        $sql = "CREATE TABLE IF NOT EXISTS [table_name] (
                  `ID` mediumint(9) NOT NULL AUTO_INCREMENT COMMENT 'affection id',
                  `affection` varchar(100) NOT NULL COMMENT 'affection name',
                  PRIMARY KEY (`ID`),
                  UNIQUE KEY `ID` (`ID`)
              ) [charset];";
             /* returns true or false on install success or error */
        $installer->install($sql, $city_table);
    }

}
new csv_import_affections();
 ?>
