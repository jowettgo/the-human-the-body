<?php

/**
 * Spinal Custom metaboxes Class [running with CMB2]
 * @version 1.0
 * @author: Marius Moldovan
 *
 *
 */
new CMB2_Taxonomy();
/**
 * Create meta boxes for taxonomies
 */
class CMB2_Taxonomy {
    private $table_name = 'termmeta';
    /**
     * Get started
     */
    public function __construct()
    {
        global $wpdb;
        self::install();
        $wpdb->termmeta = $wpdb->prefix . 'termmeta';

        add_action('init', array($this, 'init_actions'), 9999);
    }
    public function install() {
        /* table name */
		$termmeta_table = $this->table_name;
		/* table installer class */
		$installer = new spinal_table_install();
		/* #NOTE
		the sql statement needs [table_name] and [charset]
		the installer will take care of the rest
		 */
		$sql = "CREATE TABLE IF NOT EXISTS `[table_name]` (
                    `meta_id` bigint(20) unsigned NOT NULL auto_increment,
                    `term_id` bigint(20) unsigned NOT NULL default '0',
                    `meta_key` varchar(255) default NULL,
                    `meta_value` longtext,
                    PRIMARY KEY  (`meta_id`),
                    KEY `term_id` (`term_id`),
                    KEY `meta_key` (`meta_key`)
                ) [charset];";
			 /* returns true or false on install success or error */
		return $installer->install($sql,$termmeta_table);
    }
    /**
     * Initialize the hooks for the actions in the lifecycle of a term.
     */
    function init_actions() {
        if(!is_admin()){
            return;
        }

        $taxonomies = get_taxonomies(array('public' => true), 'names');

        foreach($taxonomies as $taxonomy_name) {
            add_action( "{$taxonomy_name}_add_form_fields", array($this, 'render_meta_fields_add_form'), 10);
            add_action( "{$taxonomy_name}_edit_form", array($this, 'render_meta_fields_edit_form'), 10, 2 );

            // Save our form data
            add_action( "created_{$taxonomy_name}", array( $this, 'save_meta_data' ) );
            add_action( "edited_{$taxonomy_name}", array( $this, 'save_meta_data' ) );

            // Delete it if necessary
            add_action( "delete_{$taxonomy_name}", array( $this, 'delete_meta_data' ) );
        }
    }

    /**
     * Render the meta fields for a certain taxonomy when adding a new term.
     * @param  string $taxonomy_name        The name of the taxonomy
     */
    function render_meta_fields_add_form($taxonomy_name) {
        $this->render_meta_fields($taxonomy_name);
    }

    /**
     * Render the meta fields for a certain taxonomy when editing an existing term.
     * @param  object $term                 The term which is being edited
     * @param  string $taxonomy_name        The name of the taxonomy
     */
    function render_meta_fields_edit_form($term, $taxonomy_name) {
        $this->render_meta_fields($taxonomy_name, $term->term_id);
    }

    /**
     * Render metaboxes inside a term form.
     * @param  string $taxonomy_name Name of the taxonomy metaboxes are for
     * @param  int $term_id         ID of the term metadata is for
     */
    function render_meta_fields($taxonomy_name, $term_id = null) {
        $metaboxes = apply_filters('cmb2-taxonomy_meta_boxes', array());

        foreach($metaboxes as $key => $metabox) {
            if(!in_array($taxonomy_name, $metabox['object_types'])) {
                continue;
            }

            if(null === $term_id){
                $this->render_form($metabox);
            } else {
                $this->render_form($metabox, $term_id);
            }
        }
    }

    /**
     * Render the form of a meta box.
     * @param  string  $metabox     ID of the meta box
     * @param  int $term_id         ID of the term metadata is for
     * @return string               Markup of the form
     */
    function render_form($metabox, $term_id = 0)
    {
        if ( ! class_exists( 'CMB2' ) ) {
            return;
        }

        $cmb = cmb2_get_metabox( $metabox, $term_id );

        // if passing a metabox ID, and that ID was not found
        if ( ! $cmb ) {
            return;
        }

        // Hard-code object type
        $cmb->object_type( 'term' );

        // Enqueue JS/CSS
        if ( $cmb->prop( 'cmb_styles' ) ) {
            CMB2_hookup::enqueue_cmb_css();
        }

        CMB2_hookup::enqueue_cmb_js();

        // Show cmb form
        $cmb->show_form();
    }

    /**
     * Save all metadata for a term
     * @param  int $term_id         ID of the term metadata is for
     */
    public function save_meta_data($term_id)
    {
        if(!isset($_POST['taxonomy'])) {
            return;
        }

        $taxonomy_name = $_POST['taxonomy'];

        if ( ! current_user_can( get_taxonomy( $taxonomy_name )->cap->edit_terms ) ) {
            return;
        }

        $metaboxes = apply_filters('cmb2-taxonomy_meta_boxes', array());

        foreach($metaboxes as $key => $metabox) {
            if(!in_array($taxonomy_name, $metabox['object_types'])) {
                continue;
            }

            $cmb = cmb2_get_metabox( $metabox, $term_id );

             if (
                // check nonce
                isset( $_POST[ $cmb->nonce() ] )
                && wp_verify_nonce( $_POST[ $cmb->nonce() ], $cmb->nonce() )
            ) {
                $cmb->save_fields( $term_id, 'term', $_POST );
            }
        }
    }

    /**
     * Delete all the metadata for a certain term.
     * @param  int $term_id         ID of the term metadata is for
     */
    public function delete_meta_data($term_id)
    {
        global $wpdb;

        $wpdb->query(
            $wpdb->prepare("DELETE FROM {$wpdb->termmeta} WHERE term_id = %s", $term_id)
        );
    }
}

if(!function_exists('get_term_meta')) {
	/**
	 * Retrieve metadata for the specified term.
	 *
	 * @param int $term_id ID of the term metadata is for
	 * @param string $meta_key Optional. Metadata key. If not specified, retrieve all metadata for
	 * 		the specified term.
	 * @param bool $single Optional, default is false. If true, return only the first value of the
	 * 		specified meta_key. This parameter has no effect if meta_key is not specified.
	 * @return string|array Single metadata value, or array of values
	 */
	function get_term_meta($term_id, $key = '', $single = false) {
	    return get_metadata('term', $term_id, $key, $single);
	}
}

if(!function_exists('update_term_meta')) {
	/**
	 * Update metadata for the specified term. If no value already exists for the specified term
	 * ID and metadata key, the metadata will be added.
	 *
	 * @param int $term_id ID of the term metadata is for
	 * @param string $meta_key Metadata key
	 * @param mixed $meta_value Metadata value. Must be serializable if non-scalar.
	 * @param mixed $prev_value Optional. If specified, only update existing metadata entries with
	 * 		the specified value. Otherwise, update all entries.
	 * @return int|bool Meta ID if the key didn't exist, true on successful update, false on failure.
	 */
	function update_term_meta($term_id, $meta_key, $meta_value, $prev_value = '') {
	    return update_metadata('term', $term_id, $meta_key, $meta_value, $prev_value);
	}
}

if(!function_exists('add_term_meta')) {
	/**
	 * Add metadata for the specified term.
	 *
	 * @param int $term_id ID of the term metadata is for
	 * @param string $meta_key Metadata key
	 * @param mixed $meta_value Metadata value. Must be serializable if non-scalar.
	 * @param bool $unique Optional, default is false. Whether the specified metadata key should be
	 * 		unique for the term. If true, and the term already has a value for the specified
	 * 		metadata key, no change will be made
	 * @return int|bool The meta ID on success, false on failure.
	 */
	function add_term_meta($term_id, $meta_key, $meta_value, $unique = false) {
	    return add_metadata('term', $term_id, $meta_key, $meta_value, $unique);
	}
}

if(!function_exists('delete_term_meta')) {
	/**
	 * Delete metadata for the specified term.
	 *
	 * @param int $term_id ID of the term metadata is for
	 * @param string $meta_key Metadata key
	 * @param mixed $meta_value Optional. Metadata value. Must be serializable if non-scalar. If specified, only delete metadata entries
	 * 		with this value. Otherwise, delete all entries with the specified meta_key.
	 * @param bool $delete_all Optional, default is false. If true, delete matching metadata entries
	 * 		for all terms, ignoring the specified term_id. Otherwise, only delete matching
	 * 		metadata entries for the specified term_id.
	 * @return bool True on successful delete, false on failure.
	 */
	function delete_term_meta($term_id, $meta_key, $meta_value = '', $delete_all = false) {
	    return delete_metadata('term', $term_id, $meta_key, $meta_value, $delete_all);
	}
}
