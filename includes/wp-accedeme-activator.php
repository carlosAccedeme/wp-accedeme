<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @package    wp_accedeme
 * @subpackage wp_accedeme/includes
 * @author     Accedeme
 */
class wp_accedeme_activator {

    /**
     * The database version number. Update this every time you make a change to the database structure.
     *
     * @access   protected
     * @var      string    $db_version   The database version number
     */

    /**
     * Code that is run at plugin activation. Notice: This code does NOT run when plugin
     * is updated automatically (or via version control updates)
     *
     * Creates the initial database structure required by the plugin and does other
     * data initialization. For updates, @see Wp_accedeme->update_plugin_data().
	 */
	public static function activate()
	{
		require_once ACCEDEME_DIR . 'includes/wp-accedeme-helpers.php';
        $helpers = new wp_accedeme_helpers();

        if ( !$helpers->accedemeIsTableExist() ) {
            $helpers->accedemeInitTable();
        }
    
        $website = $helpers->accedemeGetWebsite();
    
        if ( ! isset( $website ) ) {
            $website = $helpers->accedemeGetRemoteWebsiteKey();
            if ( $website ) {
                $helpers->accedemeInsert( $website );
            }
        }

		if ( is_wp_error( $website ) ) {
			return false;
		}
		return true;
	}
}