<?php

/**
 * Delete Plugin, Furasta.Org
 *
 * Deletes a plugin. First it deactivates the plugin, then
 * it removes all the plugin files. This page is accessed
 * via AJAX.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_settings
 */

$p_name= addslashes( @$_GET[ 'p_name' ] );

if( !plugin_exists( $p_name ) )
        error( 'Plugin does not exist. Please contact bugs@furasta.org for more details.', 'Plugin Error' );

/**
 * run uninstall script 
 */
$plugin_dir = HOME . '_plugins/' . $p_name;
$file = $plugin_dir . '/uninstall.php';
if( file_exists( $file ) )
        require $file;

/**
 * remove the plugin's files 
 */
remove_dir( $plugin_dir );

/**
 * remove the plugin from plugin array 
 */
$new_plugs = array( );
foreach( $PLUGINS as $plugin ){
        if( $plugin == $p_name )
                continue;
        array_push( $new_plugs, $plugin );
}

/**
 * write new settings to file 
 */
settings_rewrite( $SETTINGS, $DB, $new_plugs );

cache_clear( );

header('location: settings.php?page=plugins');

?>
