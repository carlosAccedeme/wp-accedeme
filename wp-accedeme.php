<?php
/*
Plugin Name: Accesibilidad by AccedeMe.com
Plugin URI: http://accedeme.com/plugins/wordpress/wp-accedeme.zip
Description: accedeMe es un servicio que usa la Inteligencia Artificial para hacer tu web accesible. Nuestro plugin gratuito para WordPress convierte en muy fácil la integración y configuración. accedeMe ayudará al 20% de tus visitas que necesitán de una ayuda para acceder al contenido de tu web facilmente. Desarrollado para ofrecer los mejores resultados tanto para pymes como para grandes empresas, webs del sector público o relacionadas.
Version: 0.1
Author: accedeme.com
Author URI: http://accedeme.com
*/

/*
    Copyright 2022  Accedeme  (email: atencionclientes@accedeme.com)
*/

define( 'WP_ACCEDEME_DIR', plugin_dir_path( __FILE__ ) );
define( 'WP_ACCEDEME_URL', plugin_dir_url( __FILE__ ) );

register_activation_hook( __FILE__, 'wp_accedeme_activation' );
register_uninstall_hook( __FILE__, 'wp_accedeme_uninstall' );

require_once( WP_ACCEDEME_DIR . 'includes/functions.php' );

function wp_accedeme_activation() {
	initAccedemeTable();
}

function wp_accedeme_uninstall() {
	removeUwTable();
}

function wp_accedeme_load() {
	if ( is_admin() ) {
		require_once( WP_ACCEDEME_DIR . 'includes/admin.php' );
	}
	require_once( WP_ACCEDEME_DIR . 'includes/controller.php' );
}

wp_accedeme_load();

function accedeme_addplugin_footer_notice() {
	global $wpdb;

	$table_exist = isUwTableExist();
	$table_name  = $wpdb->prefix . 'accedeme';
	$date        = date( "Y-m-d H:i:s" );

	if ( ! $table_exist ) {
		initAccedemeTable();
	}

	$account = getUwAccount();

	if ( ! isset( $account ) ) {
		$account = getRemoteUwAccountId();
		if ( $account ) {
			$wpdb->insert( $table_name, [
				'account_id'   => $account,
				'state'        => true,
				'created_time' => $date,
				'updated_time' => $date,
			] );
		}
	}

	$account = getUwAccount();

	if ( isset( $account['account_id'] ) && mb_strlen( $account['account_id'] ) > 0 && isset( $account['state'] ) && (boolean) $account['state'] === true ) {
		echo "<script>
              (function(e){
                  var el = document.createElement('script');
                  el.setAttribute('data-account', '" . $account['account_id'] . "');
                  el.setAttribute('src', 'https://cdn.access-me.software/accssme/accssmetool.js');
                  document.body.appendChild(el);
                })();
              </script>";
	}
}

add_action( 'wp_footer', 'accedeme_addplugin_footer_notice' );
