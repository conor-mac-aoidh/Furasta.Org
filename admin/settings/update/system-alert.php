<?php

/**
 * System Alert, Furasta.Org
 *
 * restored the system alert constant to no value
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_settings
 */

/**
 * make sure ajax script was loaded and user is
 * logged in 
 */
if( !defined( 'AJAX_LOADED' ) || !defined( 'AJAX_VERIFIED' ) )
        die( '1' );

settings_rewrite( $SETTINGS, $DB, $PLUGINS, TEMPLATE_DIR, '' );

die( '0' );
?>
