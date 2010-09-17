<?php

/**
 * CSS Load, Furasta.Org
 *
 * This file loads multiple CSS files, caches them,
 * compresses them, gzips them (if possible) and
 * advises the browser as to how long to keep a
 * client side cache.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 */

require '../define.php';

$cache_file = $_SERVER['QUERY_STRING'];

if( $cache_file == '' || strstr( $cache_file, '..' ) )
        die( 'hash cannot be blank, or contain ".." for security reasons.' );

ob_start( 'ob_gzhandler' );
header( 'Content-type: text/css; charset: UTF-8' );
header( 'Expires: ' . gmdate( "D, d M Y H:i:s", time() + '35000000' ) .' GMT' );
header( 'Cache-Control: public, max-age=35000000' );

if( cache_exists( $cache_file, 'CSS' ) )
        echo cache_get( $cache_file, 'CSS' );

ob_end_flush();
?>
