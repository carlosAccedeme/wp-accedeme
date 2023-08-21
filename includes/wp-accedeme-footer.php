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
class wp_accedeme_footer
{
	/**
     * Constructor
     */
    public function __construct() {
        //add_action( 'wp_footer', array( $this, 'frontendFooterScript' ), 100);
        add_action( 'wp_enqueue_scripts', array( $this, 'my_plugin_assets' ), 100 );
    }
 
    /**
     * Adds the line.
     */
    function frontendFooterScript(){
        //_e(html_entity_decode(wp_unslash('<script id="accssmm" src="https://widget.accssm.com/accssme/accssmetool.js"></script>'.PHP_EOL)));
    }

    /**
     * Enqueues script
     *
     */
    function my_plugin_assets() {
        //wp_register_script( 'wp-accedeme', plugins_url( '/wp-accedeme/assets/js/wp-accedeme.js' ) );
        wp_register_script( 'wp-accedeme', plugins_url( '/wp-accedeme/assets/js/index.js' ) );
        wp_enqueue_script( 'wp-accedeme' );
    }
}
