<?php

/*
    Copyright 2022  Accedeme  (email: atencionclientes@accedeme.com)
*/

$true_page = 'accedeme';

require_once( WP_ACCEDEME_DIR . 'includes/functions.php' );

function wp_accedeme_settings() {
    add_menu_page( 'Accedeme', 'Accedeme', 'manage_options', 'accedeme', 'accedeme_settings_page', 'dashicons-universal-access-alt' );
}

add_action( 'admin_menu', 'wp_accedeme_settings' );

/**
 *
 */
function wp_accedeme_settings_page() {
	initAccedemeTable();
	global $wpdb;

	$tableName = $wpdb->prefix . 'accedeme';
	$accountDb = $wpdb->get_row( "SELECT * FROM {$tableName} LIMIT 1" );

	$url       = urlencode( ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] );
	$nonceCode = wp_create_nonce( 'wp_rest' );

	$widgetUrl = "https://api.accedeme.org/api/apps/wp?storeUrl={$url}";
	if ( $accountDb ) {
		if ( isset( $accountDb->account_id ) ) {
			$widgetUrl .= "&account_id={$accountDb->account_id}";
		}
		if ( isset( $accountDb->state ) ) {
			$state     = $accountDb->state ? 'true' : 'false';
			$widgetUrl .= "&active=${state}";
		}
	}

	?>
    <div>
        <iframe
                id="accedeme-frame"
                src="<?php echo $widgetUrl ?>"
                title="Accedeme Widget"
                width="100%"
                height="1180px"
                style="border: none;"
        >
        </iframe>
        <script type="text/javascript">
            const MESSAGE_ACTION_TOGGLE = 'WIDGET_TOGGLE';
            const MESSAGE_ACTION_SIGNUP = "WIDGET_SIGNUP";
            const MESSAGE_ACTION_SIGNIN = "WIDGET_SIGNIN";
            const siteUrl = '<?= get_site_url(); ?>';

            const request = (data) => {
                return jQuery.when(
                    jQuery.ajax({
                        url: `${siteUrl}/index.php?rest_route=/accedeme/v1/save`,
                        type: 'POST',
                        contentType: 'application/json',
                        dataType: 'json',
                        beforeSend: function (xhr) {
                            xhr.setRequestHeader('X-WP-Nonce', '<?php echo $nonceCode ?>');
                        },
                        data: JSON.stringify(data),
                    })
                )
            };

            const isPostMessageValid = (postMessage) => {
                return postMessage.data !== undefined
                    && postMessage.data.action
                    && postMessage.data.account !== undefined
                    && postMessage.data.state !== undefined
                    && [MESSAGE_ACTION_TOGGLE, MESSAGE_ACTION_SIGNUP, MESSAGE_ACTION_SIGNIN].includes(postMessage.data.action)
            }

            jQuery(document).ready(function () {
                const selector = document.getElementById('accedeme-frame');
                const frameContentWindow = selector.contentWindow;
                const {url} = selector.dataset;
                window.addEventListener('message', postMessage => {
                    if (postMessage.source !== frameContentWindow || !isPostMessageValid(postMessage)) {
                        return;
                    }
                    console.log('[accedeme/v1/postMassage]', postMessage);
                    request({
                        account: postMessage.data.account,
                        state: postMessage.data.state,
                    }).then(res => console.log(res))
                        .catch(err => console.error(err));
                });
            });
        </script>
    </div>
	<?php
}
