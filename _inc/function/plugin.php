<?php

/**
 * Plugin Functions, Furasta.Org
 *
 * This file contains various standard functions
 * which can be used to obtain information on
 * plugins.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    plugin_architecture
 */

/**
 * plugin_exists
 *
 * returns true if a plugin exists in the
 * _plugins dir
 * 
 * @param string $plugin_name 
 * @access public
 * @return bool
 */
function plugin_exists( $plugin_name ){
	$dir = str_replace( ' ', '-', $plugin_name );

	if( file_exists( HOME . '_plugins/' . $dir . '/plugin.php' ) )
		return true;

	return false;
}

/**
 * plugin_installed 
 *
 * returns true if plugin is installed
 * 
 * @param string $plugin_name 
 * @access public
 * @return bool
 */
function plugin_installed($plugin_name){
	$Plugins = Plugins::getInstance( );

	$plugins = $Plugins->registeredPlugins( );

	if( in_array( $plugins, $plugin_name ) )
		return true;

	return false;
}

?>
