<?php

/**
 * Verify Auto Update, Furasta.Org
 *
 * Verifies the auto update package and moves it to
 * the HOME directory. It then includes the perform-update.php
 * file.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_settings
 */

$file = @$_GET[ 'file' ];

if( $file == '' )
	die( 'no filename' );

if( strpos( $file, '..' ) !== false )
	die( 'bad filename' );

if( !file_exists( $file ) )
	die( 'file not found' );

$update = file_get_contents( $file );

file_put_contents( USERFILES . 'update.zip', $update );

require 'perform-update.php';

?>
