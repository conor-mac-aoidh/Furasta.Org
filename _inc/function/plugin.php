<?php

/**
 * Plugin Functions, Furasta.Org
 *
 * This file contains various standard functions
 * which can be used to obtain information on
 * certain plugins.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    plugin_architecture
 */

function plugin_exists($plugin_name){
	if(file_exists(HOME.'_plugins/'.$plugin_name.'/plugin.php'))
		return true;
	return false;
}

function plugin_installed($plugin_name){
	global $PLUGINS;
	if(in_array($PLUGINS,$plugin_name))
		return true;
	return false;
}

?>
