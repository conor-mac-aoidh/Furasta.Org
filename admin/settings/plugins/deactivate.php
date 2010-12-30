<?php

/**
 * Deactivate Plugin, Furasta.Org
 *
 * Deactivates a plugin. This page is accessed via AJAX.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_settings
 */

$p_name = addslashes( @$_GET[ 'p_name' ] );

if( !plugin_exists( $p_name ) )
        error( 'Plugin does not exist. Please contact bugs@furasta.org for more details.', 'Plugin Error' );

/**
 * make new plugins array 
 */
$new_plugs = array( );
foreach( $PLUGINS as $plugin ){
	if( $plugin == $p_name )
		continue;
	array_push( $new_plugs, $plugin );
}

/**
 * rewrite new settings file 
 */
settings_rewrite( $SETTINGS, $DB, $new_plugs );

cache_clear( );

/**
 * run uninstall script 
 */
$file = HOME . '_plugins/' . $p_name . '/uninstall.php';
if( file_exists( $file ) )
        require $file;

header('location: settings.php?page=plugins');
?>
