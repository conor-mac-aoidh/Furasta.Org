<?php

/**
 * AJAX Access, Furasta.Org
 *
 * Designed as a method through which logged in users
 * can make AJAX requests through the CMS. This file
 * provides developers access to all main libraries that
 * the CMS provides, through AJAX, and then includes the
 * developers own script. It essentially provides a
 * working enviornment for AJAX calls.
 * 
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 */

/**
 * used to test is the ajax load script was exectued 
 */
define( 'AJAX_LOADED', true );

/**
 * checks if config and login are required
 */
if( isset( $_GET[ 'no_config' ] ) ){
	/**
	 * basic script setup - database connection,
	 * .settings contstants but no libraries 
	 */
	define( 'START_TIME', microtime( true ) );
	define( 'HOME', substr( dirname( __FILE__ ), 0, -4 ) );

	require HOME . '.settings.php';

	if( ! ( $connect = mysql_connect( $DB[ 'host' ], $DB[ 'user' ], $DB[ 'pass' ] ) ) )
        	die( 'The MySQL connection details are incorrect. The hostname, username or password are incorrect.' );

	if( ! mysql_select_db( $DB['name'], $connect ) )
        	die( 'Cannot connect to the MySQL database. Please make sure that the database name is correct.' );
}
else{
	/**
	 * normal setup script - database connection,
	 * .settings constants, libraries
	 * NB: define.php is run so plugins are loaded etc
	 *     also must be logged in 
	 */
	include 'define.php';

	$User = new User( );

	if( !$User->verify( ) )
		die( 'Please login to access this content.' );

	/**
	 * use to test if ajax script is run and user
	 * is verified as logged in
	 */
	define( 'AJAX_VERIFIED', true );
}



$file = @$_GET[ 'file' ];

if( strpos( $file, '..' ) != false )
	die( 'not a valid filename' );

$file = HOME . $file;

//die( $file );

if( !file_exists( $file ) )
	die( 'The file at <i>' . $file . '</i> does not exist.' );

require $file;

exit;
?>
