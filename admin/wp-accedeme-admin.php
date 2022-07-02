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
class wp_accedeme_admin
{
	/**
     * Constructor
     */
    public function __construct() {
        add_action('admin_menu', array( $this, 'accedeme_admin_menu_script' ) );
    }

    function accedeme_admin_menu_script(){
        add_options_page( 'WP Accedeme', 'WP Accedeme', 'manage_options', 'accedeme-plugin-option', array( $this, 'accedeme_options_menu_script' ) );
    }

    function accedeme_options_menu_script(){
        
        if(!current_user_can('manage_options') ){
                
            wp_die( __('You do not have sufficient permissions to access this page.','accedeme-plugin') );
            
        }	
        if( !defined( 'ABSPATH' ) ) exit;
    
        $imageUrl = ACCEDEME_URL .'/assets/images/logo_accedeme.png';
    
        $handle = 'accedeme_wp.css';
        $src = ACCEDEME_URL . '/assets/css/accedeme_wp.css';
    
        require_once ACCEDEME_DIR . 'includes/wp-accedeme-helpers.php';
        $helpers = new wp_accedeme_helpers();

        $data = $helpers->accedemeGetRemoteWebsiteKey();
    
        wp_enqueue_style( $handle, $src );
        ?>
        <div class="wrap">
    
            <h2><?php _e('WP Accedeme &raquo; Settings','accedeme-plugin'); ?></h2>
            
            <div class="container-accede">
                <a id="logo-accedeme" href="https://accedeme.com/login" target="_blank">
                    <img src="<?php echo $imageUrl; ?>" alt="Accedeme">
                </a>
                <?php 
                    if ( !empty($data['domain_key']) ) 
                    {
                        echo '<a id="btn_panel" href="https://accedeme.com/control_panel/dashboard" target="_blank">
                            <div>';
                        _e('Panel de control','accedeme-plugin');
                        echo '</div>
                        </a>';
                    }  
                    else 
                    {
                        echo '<div id="reg_text">';
                        _e('Ya sólo queda registrar tu dominio en accedeme.com', 'accedeme-plugin');
                        echo '</div>';
    
                        echo '<a id="btn_register" href="https://accedeme.com/register" target="_blank">
                        <div>';
                        _e('Registra tu dominio ahora','accedeme-plugin');
                        echo '</div>
                        </a>';
                    }
                ?>
            </div>
        </div>
    <?php
    }
}