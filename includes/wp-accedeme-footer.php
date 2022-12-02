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
        add_action( 'wp_footer', array( $this, 'frontendFooterScript' ), 100);
    }
 
    /**
     * Adds the line.
     */
    function frontendFooterScript(){
        _e(html_entity_decode(wp_unslash('<script id="accssmm" src="https://widget.accssm.com/accssme/accssmetool.js"></script>'.PHP_EOL)));
    }
}
