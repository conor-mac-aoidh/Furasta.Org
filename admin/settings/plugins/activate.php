<?php

/**
 * Activate Plugin, Furasta.Org
 *
 * Activates a plugin. This page is accessed via AJAX.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_settings
 */

$p_name = addslashes( @$_GET[ 'p_name' ] );

if( !plugin_exists( $p_name ) )
	error( 'Plugin does not exist. Please contact bugs@furasta.org for more details.', 'Plugin Error' );

array_push( $PLUGINS, $p_name );

/**
 * rewrite settings file with new plugin array 
 */
settings_rewrite( $SETTINGS, $DB, $PLUGINS );

cache_clear( );

/**
 * run plugin install file 
 */
$file = HOME . '_plugins/' . $p_name . '/install.php';
if( file_exists( $file ) )
	require $file;

header('location: settings.php?page=plugins');
?>
