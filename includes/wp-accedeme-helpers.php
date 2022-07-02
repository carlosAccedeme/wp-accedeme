<?php

/**
 * Adds our one line of code to the footer.
 *
 * This class defines all code necessary to run the Accedeme widget.
 *
 * @package    wp_accedeme
 * @subpackage wp_accedeme/includes
 * @author     Accedeme
 */
class wp_accedeme_helpers
{
    /**
     * Constructor
     */
    public function __construct() {
    }

	public function accedemeInitTable() {
        global $wpdb;
        $table_name      = $wpdb->prefix . 'accedeme';
        $charset_collate = $wpdb->get_charset_collate();
    
        $sql = "
        CREATE TABLE IF NOT EXISTS `$table_name` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `domain_key` VARCHAR(32) DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) $charset_collate
        ";
    
        if ( ! function_exists( 'dbDelta' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        }
    
        dbDelta( $sql );
    }
    
    public function accedemeRemoveTable() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'accedeme';
    
        $sql = "DROP TABLE IF EXISTS `$table_name`";
    
        $wpdb->get_results( $sql );
    }
    
    public function accedemeIsTableExist() {
        global $wpdb;
        $table_name  = $wpdb->prefix . 'accedeme';
        $table_exist = false;
        if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) == $table_name ) {
            $table_exist = true;
        }
    
        return $table_exist;
    }

    public function accedemeInsert( $data )
    {
        global $wpdb;

        $table_name  = $wpdb->prefix . 'accedeme';

        $wpdb->insert( $table_name, $data );
    }
    
    public function accedemeGetRemoteWebsiteKey() {
        global $wp_version;

        $website = array();

        $apiUrl = 'https://accedeme.com/plugins/wordpress_get_domain_key';
        $apiParameters = array(
            'domain' => $_SERVER['HTTP_HOST'],
            'version' => $wp_version,
        );
    
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
            ),
            'body'        => json_encode($apiParameters),
            'method'      => 'POST',
            //'data_format' => 'body',
			'timeout'     => 45,
			'sslverify'   => false,
        );

        $response = wp_remote_post( $apiUrl, $args );
        $response_code = wp_remote_retrieve_response_message( $response );
    
        if ( $response_code === 'OK' ) {
            $response_body = json_decode( wp_remote_retrieve_body( $response ), true );
    
            if ( $response_body['status'] == 'ok' ) 
            {
                $website = [
                    'domain_key' => $response_body['data']['domain_key'],
                ];
            }
        }
    
        return $website;
    }
    
    public function accedemeGetWebsite() {
        global $wpdb;
    
        $table_name = $wpdb->prefix . 'accedeme';
        $website    = null;
        $dbData     = $wpdb->get_results( "SELECT * FROM $table_name LIMIT 0, 1" );
    
        if ( isset( $dbData[0] ) ) {
            $website = [
                'domain_key' => $dbData[0]->domain_key,
            ];
        }
    
        return $website;
    }
}
