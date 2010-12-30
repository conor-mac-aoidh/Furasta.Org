<?php

/**
 * Define File, Furasta.Org
 *
 * The define file loads all core files, and defines all
 * main constans. It also loads the /.settings.php file.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 */

/**
 * set up initial constants and default settings
 */
define( 'START_TIME', microtime( true ) );
define( 'HOME', substr( dirname( __FILE__ ), 0, -4) );

date_default_timezone_set( 'UTC' );

/**
 * redirect to install dir if no config found
 * @todo figure out how to redirect to install dir even when in subdirs 
 */
if( !file_exists( HOME . '.settings.php' ) )
        header( 'location: /install' );

/**
 * require config - personal settings, db settings etc 
 */
require HOME . '.settings.php';

/**
 * add function files 
 */
$function_dir = HOME . '_inc/function/';

require $function_dir . 'system.php';
require $function_dir . 'db.php';
require $function_dir . 'plugin.php';
require $function_dir . 'cache.php';

/**
 * connect to database - display error if details are wrong 
 */

if( ! ( $connect = mysql_connect( $DB[ 'host' ], $DB[ 'user' ], $DB[ 'pass' ] ) ) ) 
	error( 'The MySQL connection details are incorrect. The hostname, username or password are incorrect.' , 'MySQL Connection Failure' );

if( ! mysql_select_db( $DB['name'], $connect ) ) 
	error( 'Cannot connect to the MySQL database. Please make sure that the database name is correct.', 'Database Connection Failure' );


/**
 * start session and get instance of the Plugin class 
 * cycle through active plugins and require plugin files
 * finally refactor the plugins according to the $importance var
 */
session_start( );

/**
 * get instance of plugins class, load plugin files
 * and register plugins
 */
$Plugins = Plugins::getInstance( );

foreach( $PLUGINS as $plugin ){
	require HOME . '_plugins/' . $plugin . '/plugin.php';

	/**
	 * register the $plugin array from the plugins file
	 */
	$Plugins->register( $plugin );
}
//$Plugins->refactor( );
?>
